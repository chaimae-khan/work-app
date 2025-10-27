<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use OwenIt\Auditing\Models\Audit;
use Yajra\DataTables\Facades\DataTables;
use App\Models\Client;
use App\Models\User;
use App\Models\Fournisseur;
use App\Models\Local;
use App\Models\Tva;
use App\Models\Rayon;
use App\Models\Unite;
use App\Models\Category;
use App\Models\Product;
use App\Models\Stock;
use App\Models\SubCategory;
use App\Models\Vente;
use App\Models\LigneVente;
use App\Models\Achat;
use App\Models\LigneAchat;
use App\Models\StockTransfer;
use App\Models\LineTransfer;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;

class AuditController extends Controller
{
    /**
     * Display a listing of all audits.
     */
    public function index(Request $request)
    {
        // Add permission check
        if (!auth()->user()->can('Historique')) {
            abort(403, 'Vous n\'avez pas la permission de voir l\'historique');
        }
        
        if ($request->ajax()) {
            try {
                // Use the same approach as TvaController - raw SQL with CONCAT
                $dataAudit = DB::table('audits as a')
                    ->leftJoin('users as u', 'u.id', '=', 'a.user_id')
                    ->select(
                        'a.id',
                        'a.user_id',
                        'a.event',
                        'a.auditable_id',
                        'a.auditable_type',
                        'a.old_values',
                        'a.new_values',
                        'a.created_at',
                        DB::raw("CONCAT(COALESCE(u.prenom, ''), ' ', COALESCE(u.nom, '')) as name") // Create 'name' like TvaController
                    );

                // Apply model type filter with special handling for transfer/retour
                if ($request->has('model_type') && !empty($request->model_type)) {
                    $modelType = $request->model_type;
                    
                    if ($modelType === 'transfer') {
                        // Filter for Transfer operations only (both from and to are not null)
                        $dataAudit->where('a.auditable_type', StockTransfer::class)
                                  ->whereExists(function($query) {
                                      $query->select(DB::raw(1))
                                            ->from('stocktransfer as st')
                                            ->whereRaw('st.id = a.auditable_id')
                                            ->whereNotNull('st.from')
                                            ->whereNotNull('st.to');
                                  });
                    } elseif ($modelType === 'retour') {
                        // Filter for Retour operations only (from is null, to is not null)
                        $dataAudit->where('a.auditable_type', StockTransfer::class)
                                  ->whereExists(function($query) {
                                      $query->select(DB::raw(1))
                                            ->from('stocktransfer as st')
                                            ->whereRaw('st.id = a.auditable_id')
                                            ->whereNull('st.from')
                                            ->whereNotNull('st.to');
                                  });
                    } else {
                        // Regular model filtering
                        $modelClass = $this->getModelClass($modelType);
                        if ($modelClass) {
                            $dataAudit->where('a.auditable_type', $modelClass);
                        }
                    }
                }

                // Apply user filter
                if ($request->has('user_id') && !empty($request->user_id)) {
                    $dataAudit->where('a.user_id', $request->user_id);
                }

                // Apply event type filter
                if ($request->has('event') && !empty($request->event)) {
                    $dataAudit->where('a.event', $request->event);
                }

                // Apply date range filter
                if ($request->has('start_date') && $request->has('end_date')) {
                    try {
                        $start = Carbon::parse($request->start_date)->startOfDay();
                        $end = Carbon::parse($request->end_date)->endOfDay();
                        $dataAudit->whereBetween('a.created_at', [$start, $end]);
                    } catch (\Exception $e) {
                        // Continue with query without date filter
                    }
                }

                $dataAudit->orderBy('a.id', 'desc');

                return DataTables::of($dataAudit)
                    ->addIndexColumn()
                    ->filterColumn('name', function($query, $keyword) {
                        $query->whereRaw("LOWER(CONCAT(COALESCE(u.prenom, ''), ' ', COALESCE(u.nom, ''))) LIKE ?", ["%".strtolower($keyword)."%"]);
                    })
                    ->addColumn('model_type', function ($row) {
                        return $this->getReadableModelName($row->auditable_type, $row->auditable_id);
                    })
                    ->addColumn('model_name', function ($row) {
                        return $this->getModelName($row->auditable_type, $row->auditable_id);
                    })
                    ->addColumn('user_name', function ($row) {
                        return $row->name ?: 'Système'; // Use the 'name' from CONCAT
                    })
                    ->addColumn('changes', function ($row) {
                        // Just a placeholder for the view details button
                        return '';
                    })
                    ->editColumn('event', function ($row) {
                        $labels = [
                            'created' => '<span class="badge bg-success">Création</span>',
                            'updated' => '<span class="badge bg-info">Modification</span>',
                            'deleted' => '<span class="badge bg-danger">Suppression</span>',
                            'restored' => '<span class="badge bg-warning">Restauration</span>',
                        ];
                        
                        return $labels[$row->event] ?? '<span class="badge bg-secondary">' . $row->event . '</span>';
                    })
                    ->rawColumns(['model_type', 'event', 'changes'])
                    ->make(true);
            } catch (\Exception $e) {
                // Log the error like TvaController
                \Log::error('DataTables error in AuditController: ' . $e->getMessage());
                
                // Return a friendly error response
                return response()->json([
                    'error' => true,
                    'message' => 'Une erreur est survenue lors du chargement des données',
                    'details' => $e->getMessage()
                ], 500);
            }
        }
        
        return view('audit.index');
    }

public function details($id)
{
    if (!auth()->user()->can('Historique-montrer')) {
        abort(403, 'Vous n\'avez pas la permission de voir les détails de l\'historique');
    }

    $audit = DB::table('audits as a')
        ->leftJoin('users as u', 'u.id', '=', 'a.user_id')
        ->select(
            'a.*',
            DB::raw("CONCAT(COALESCE(u.prenom, ''), ' ', COALESCE(u.nom, '')) as name")
        )
        ->where('a.id', $id)
        ->first();

    if (!$audit) {
        return redirect('audit')->with('error', 'Audit non trouvé');
    }

    $oldValues = is_string($audit->old_values) ? json_decode($audit->old_values, true) : $audit->old_values;
    $newValues = is_string($audit->new_values) ? json_decode($audit->new_values, true) : $audit->new_values;

    $oldValues = $oldValues ?: [];
    $newValues = $newValues ?: [];

    $oldValues = $this->processSpecialFields($audit->auditable_type, $oldValues, $audit->auditable_id);
    $newValues = $this->processSpecialFields($audit->auditable_type, $newValues, $audit->auditable_id);

    unset($oldValues['id'], $newValues['id']);

    $fieldsToHide = [
        'id_formateur', 
        'formateur', 
        'element', 
        'model_name', 
        'id_Fournisseur', 
        'fournisseur',
        'status_label'
    ];

    foreach ($fieldsToHide as $field) {
        unset($oldValues[$field], $newValues[$field]);
    }

    $fieldNames = $this->getFieldNames($audit->auditable_type);

    if ($audit->auditable_type === 'App\\Models\\StockTransfer') {
        $isRetour = false;

        $stockTransferData = DB::table('stocktransfer')
            ->select('from', 'to')
            ->where('id', $audit->auditable_id)
            ->first();

        if ($stockTransferData) {
            $isRetour = is_null($stockTransferData->from) && !is_null($stockTransferData->to);
        } else {
            if (isset($newValues['from'], $newValues['to'])) {
                $isRetour = is_null($newValues['from']) && !is_null($newValues['to']);
            } elseif (isset($oldValues['from'], $oldValues['to'])) {
                $isRetour = is_null($oldValues['from']) && !is_null($oldValues['to']);
            }
        }

        if ($isRetour) {
            unset(
                $oldValues['from'], $newValues['from'], $fieldNames['from'],
                $oldValues['to'], $newValues['to'], $fieldNames['to']
            );
        }
    }

    $formattedOldValues = [];
    $formattedNewValues = [];

    foreach ($oldValues as $key => $value) {
        $formattedOldValues[$key] = $this->formatValue($value);
    }

    foreach ($newValues as $key => $value) {
        $formattedNewValues[$key] = $this->formatValue($value);
    }

    foreach ($fieldsToHide as $field) {
        unset($fieldNames[$field]);
    }

    $fieldNames = array_filter($fieldNames, function($value, $key) use ($fieldsToHide) {
        if (in_array(strtolower($value), ['formateur', 'élément', 'element', 'fournisseur', 'status_label'])) {
            return false;
        }
        if (in_array($key, $fieldsToHide)) {
            return false;
        }
        return true;
    }, ARRAY_FILTER_USE_BOTH);

    $ligneAchatDetails = [];
    $achatInfo = null;
    $ligneVenteDetails = [];
    $venteInfo = null;
    $lineTransferDetails = [];
    $stockTransferInfo = null;

    if ($audit->auditable_type === 'App\\Models\\Achat') {
        $achatInfo = DB::table('achats as a')
            ->join('fournisseurs as f', 'a.id_Fournisseur', '=', 'f.id')
            ->join('users as u', 'a.id_user', '=', 'u.id')
            ->select(
                'a.*',
                'f.entreprise as fournisseur_name',
                DB::raw("CONCAT(COALESCE(u.prenom, ''), ' ', COALESCE(u.nom, '')) as created_by_name")
            )
            ->where('a.id', $audit->auditable_id)
            ->first();

        if ($achatInfo) {
            $ligneAchatDetails = DB::table('ligne_achat as la')
                ->join('products as p', 'la.idproduit', '=', 'p.id')
                ->join('users as u', 'la.id_user', '=', 'u.id')
                ->select(
                    'p.name as product_name',
                    'p.code_article',
                    'p.price_achat',
                    'la.qte',
                    DB::raw('p.price_achat * la.qte as total_line'),
                    DB::raw("CONCAT(COALESCE(u.prenom, ''), ' ', COALESCE(u.nom, '')) as created_by")
                )
                ->where('la.idachat', $audit->auditable_id)
                ->get();
        }
    }

    if ($audit->auditable_type === 'App\\Models\\Vente') {
        $venteInfo = DB::table('ventes as v')
            ->join('users as f', 'v.id_formateur', '=', 'f.id')
            ->join('users as u', 'v.id_user', '=', 'u.id')
            ->select(
                'v.*',
                DB::raw("CONCAT(COALESCE(f.prenom, ''), ' ', COALESCE(f.nom, '')) as formateur_name"),
                DB::raw("CONCAT(COALESCE(u.prenom, ''), ' ', COALESCE(u.nom, '')) as created_by_name")
            )
            ->where('v.id', $audit->auditable_id)
            ->first();

        if ($venteInfo) {
            $ligneVenteDetails = DB::table('ligne_vente as lv')
                ->join('products as p', 'lv.idproduit', '=', 'p.id')
                ->join('users as u', 'lv.id_user', '=', 'u.id')
                ->select(
                    'p.name as product_name',
                    'p.code_article',
                    'p.price_achat',
                    'lv.qte',
                    DB::raw('p.price_achat * lv.qte as total_line'),
                    DB::raw("CONCAT(COALESCE(u.prenom, ''), ' ', COALESCE(u.nom, '')) as created_by")
                )
                ->where('lv.idvente', $audit->auditable_id)
                ->get();
        }
    }

    if ($audit->auditable_type === 'App\\Models\\StockTransfer') {
        $stockTransferInfo = DB::table('stocktransfer as st')
            ->leftJoin('users as from_user', 'st.from', '=', 'from_user.id')
            ->leftJoin('users as to_user', 'st.to', '=', 'to_user.id')
            ->join('users as creator', 'st.id_user', '=', 'creator.id')
            ->select(
                'st.*',
                DB::raw("CONCAT(COALESCE(from_user.prenom, ''), ' ', COALESCE(from_user.nom, '')) as from_user_name"),
                DB::raw("CONCAT(COALESCE(to_user.prenom, ''), ' ', COALESCE(to_user.nom, '')) as to_user_name"),
                DB::raw("CONCAT(COALESCE(creator.prenom, ''), ' ', COALESCE(creator.nom, '')) as created_by_name")
            )
            ->where('st.id', $audit->auditable_id)
            ->first();

        if ($stockTransferInfo) {
            $lineTransferDetails = DB::table('line_transfer as lt')
                ->join('products as p', 'lt.id_product', '=', 'p.id')
                ->join('users as u', 'lt.id_user', '=', 'u.id')
                ->leftJoin('ventes as v', 'lt.idcommande', '=', 'v.id')
                ->leftJoin('users as formateur', 'v.id_formateur', '=', 'formateur.id')
                ->select(
                    'p.name as product_name',
                    'p.code_article',
                    'lt.quantite',
                    'v.id as commande_id',
                    DB::raw("CONCAT(COALESCE(formateur.prenom, ''), ' ', COALESCE(formateur.nom, '')) as formateur_name"),
                    DB::raw("CONCAT(COALESCE(u.prenom, ''), ' ', COALESCE(u.nom, '')) as created_by")
                )
                ->where('lt.id_stocktransfer', $audit->auditable_id)
                ->get();
        }
    }

    $auditObj = (object) [
        'id' => $audit->id,
        'user_id' => $audit->user_id,
        'event' => $audit->event,
        'auditable_id' => $audit->auditable_id,
        'auditable_type' => $audit->auditable_type,
        'old_values' => $audit->old_values,
        'new_values' => $audit->new_values,
        'created_at' => $audit->created_at,
        'user' => $audit->name ? (object)['name' => $audit->name] : null
    ];

    return view('audit.details', [
        'audit' => $auditObj,
        'oldValues' => $oldValues,
        'newValues' => $newValues,
        'formattedOldValues' => $formattedOldValues,
        'formattedNewValues' => $formattedNewValues,
        'modelType' => $this->getReadableModelName($audit->auditable_type, $audit->auditable_id),
        'modelName' => $this->getModelName($audit->auditable_type, $audit->auditable_id),
        'userName' => $audit->name ?: 'Système',
        'fieldNames' => $fieldNames,
        'eventName' => $this->getReadableEvent($audit->event),
        'ligneAchatDetails' => $ligneAchatDetails,
        'achatInfo' => $achatInfo,
        'ligneVenteDetails' => $ligneVenteDetails,
        'venteInfo' => $venteInfo,
        'lineTransferDetails' => $lineTransferDetails,
        'stockTransferInfo' => $stockTransferInfo,
    ]);
}

    
    /**
     * Process special fields like resolving IDs to names.
     */
    private function processSpecialFields($modelClass, $values, $auditableId = null)
    {
        $processedValues = $values;
        
        // Process IDs to names for specific fields
        foreach ($processedValues as $key => $value) {
            // Process user IDs to names using raw SQL like TvaController
            if (in_array($key, ['iduser', 'id_user']) && !empty($value)) {
                $user = DB::table('users')
                    ->select(DB::raw("CONCAT(COALESCE(prenom, ''), ' ', COALESCE(nom, '')) as name"))
                    ->where('id', $value)
                    ->first();
                if ($user) {
                    $processedValues[$key] = $user->name;
                }
            }
            
            // Process other foreign keys as needed
            if ($key === 'id_local' && !empty($value)) {
                $local = Local::find($value);
                if ($local) {
                    $processedValues[$key] = $local->name;
                }
            }
            
            if ($key === 'id_rayon' && !empty($value)) {
                $rayon = Rayon::find($value);
                if ($rayon) {
                    $processedValues[$key] = $rayon->name;
                }
            }
            
            if ($key === 'id_categorie' && !empty($value)) {
                $category = Category::find($value);
                if ($category) {
                    $processedValues[$key] = $category->name;
                }
            }
            
            if ($key === 'id_subcategorie' && !empty($value)) {
                $subcategory = SubCategory::find($value);
                if ($subcategory) {
                    $processedValues[$key] = $subcategory->name;
                }
            }
            
            // Process Fournisseur ID for Achat model
            if ($key === 'id_Fournisseur' && !empty($value)) {
                $fournisseur = Fournisseur::find($value);
                if ($fournisseur) {
                    $processedValues[$key] = $fournisseur->entreprise;
                }
            }
            
            // Handling unite for Product model
            if ($key === 'unite' && $modelClass === Product::class) {
                // For products, if unite is null, get it from the Stock table
                if (empty($value) && $auditableId) {
                    $stock = Stock::where('id_product', $auditableId)->first();
                    if ($stock && $stock->id_unite) {
                        $unite = Unite::find($stock->id_unite);
                        if ($unite) {
                            $processedValues[$key] = $unite->name;
                        }
                    }
                } else if (!empty($value)) {
                    $unite = Unite::find($value);
                    if ($unite) {
                        $processedValues[$key] = $unite->name;
                    }
                }
            }
        }
        
        // Process special fields for Vente model
        if ($modelClass === Vente::class) {
            foreach ($processedValues as $key => $value) {
                // Process formateur ID
                if ($key === 'id_formateur' && !empty($value)) {
                    $user = DB::table('users')
                        ->select(DB::raw("CONCAT(COALESCE(prenom, ''), ' ', COALESCE(nom, '')) as name"))
                        ->where('id', $value)
                        ->first();
                    if ($user) {
                        $processedValues[$key] = $user->name;
                    }
                }
            }
        }
        
        // Process special fields for Achat model
        if ($modelClass === Achat::class) {
            foreach ($processedValues as $key => $value) {
                // Process fournisseur ID
                if ($key === 'id_Fournisseur' && !empty($value)) {
                    $fournisseur = Fournisseur::find($value);
                    if ($fournisseur) {
                        $processedValues[$key] = $fournisseur->entreprise;
                    }
                }
            }
        }
        
        // Process special fields for StockTransfer model
        if ($modelClass === StockTransfer::class) {
            foreach ($processedValues as $key => $value) {
                // Process from user ID (for Transfer operations)
                if ($key === 'from' && !empty($value)) {
                    $user = DB::table('users')
                        ->select(DB::raw("CONCAT(COALESCE(prenom, ''), ' ', COALESCE(nom, '')) as name"))
                        ->where('id', $value)
                        ->first();
                    if ($user) {
                        $processedValues[$key] = $user->name;
                    }
                }
                
                // Process to user ID (for both Transfer and Retour operations)
                if ($key === 'to' && !empty($value)) {
                    $user = DB::table('users')
                        ->select(DB::raw("CONCAT(COALESCE(prenom, ''), ' ', COALESCE(nom, '')) as name"))
                        ->where('id', $value)
                        ->first();
                    if ($user) {
                        $processedValues[$key] = $user->name;
                    }
                }
            }
        }
        
        return $processedValues;
    }
    
    /**
     * Export audits as CSV.
     */
    public function export(Request $request)
    {
        // Add permission check
        if (!auth()->user()->can('Historique-Export')) {
            abort(403, 'Vous n\'avez pas la permission d\'exporter l\'historique');
        }
        
        try {
            // Use raw SQL like TvaController
            $auditsQuery = DB::table('audits as a')
                ->leftJoin('users as u', 'u.id', '=', 'a.user_id')
                ->select(
                    'a.*',
                    DB::raw("CONCAT(COALESCE(u.prenom, ''), ' ', COALESCE(u.nom, '')) as name")
                );
            
            // Apply filters - same as index method with special transfer/retour handling
            if ($request->has('model_type') && !empty($request->model_type)) {
                $modelType = $request->model_type;
                
                if ($modelType === 'transfer') {
                    // Filter for Transfer operations only
                    $auditsQuery->where('a.auditable_type', StockTransfer::class)
                               ->whereExists(function($query) {
                                   $query->select(DB::raw(1))
                                         ->from('stocktransfer as st')
                                         ->whereRaw('st.id = a.auditable_id')
                                         ->whereNotNull('st.from')
                                         ->whereNotNull('st.to');
                               });
                } elseif ($modelType === 'retour') {
                    // Filter for Retour operations only
                    $auditsQuery->where('a.auditable_type', StockTransfer::class)
                               ->whereExists(function($query) {
                                   $query->select(DB::raw(1))
                                         ->from('stocktransfer as st')
                                         ->whereRaw('st.id = a.auditable_id')
                                         ->whereNull('st.from')
                                         ->whereNotNull('st.to');
                               });
                } else {
                    // Regular model filtering
                    $modelClass = $this->getModelClass($modelType);
                    if ($modelClass) {
                        $auditsQuery->where('a.auditable_type', $modelClass);
                    }
                }
            }
            
            if ($request->has('user_id') && !empty($request->user_id)) {
                $auditsQuery->where('a.user_id', $request->user_id);
            }
            
            if ($request->has('event') && !empty($request->event)) {
                $auditsQuery->where('a.event', $request->event);
            }
            
            if ($request->has('start_date') && $request->has('end_date')) {
                try {
                    $start = Carbon::parse($request->start_date)->startOfDay();
                    $end = Carbon::parse($request->end_date)->endOfDay();
                    $auditsQuery->whereBetween('a.created_at', [$start, $end]);
                } catch (\Exception $e) {
                    // Continue without date filter
                }
            }
            
            $audits = $auditsQuery->orderBy('a.created_at', 'desc')->get();
            
            $headers = [
                'Content-Type' => 'text/csv',
                'Content-Disposition' => 'attachment; filename="historique_' . date('Y-m-d') . '.csv"',
                'Pragma' => 'no-cache',
                'Cache-Control' => 'must-revalidate, post-check=0, pre-check=0',
                'Expires' => '0'
            ];
            
            $callback = function() use ($audits) {
                $file = fopen('php://output', 'w');
                fputs($file, "\xEF\xBB\xBF"); // UTF-8 BOM
                
                // Headers
                fputcsv($file, ['Type', 'Élément', 'Action', 'Utilisateur', 'Modifications', 'Date']);
                
                foreach ($audits as $audit) {
                    $oldValues = is_string($audit->old_values) ? json_decode($audit->old_values, true) : $audit->old_values;
                    $oldValues = $oldValues ?: [];
                    
                    $newValues = is_string($audit->new_values) ? json_decode($audit->new_values, true) : $audit->new_values;
                    $newValues = $newValues ?: [];
                    
                    // Remove ID from values
                    if (isset($oldValues['id'])) {
                        unset($oldValues['id']);
                    }
                    if (isset($newValues['id'])) {
                        unset($newValues['id']);
                    }
                    
                    // Process special fields
                    $oldValues = $this->processSpecialFields($audit->auditable_type, $oldValues, $audit->auditable_id);
                    $newValues = $this->processSpecialFields($audit->auditable_type, $newValues, $audit->auditable_id);
                    
                    // Format the changes description
                    $changes = $this->formatChangesForCsv($audit->event, $oldValues, $newValues, $audit->auditable_type);
                    
                    fputcsv($file, [
                        $this->getReadableModelName($audit->auditable_type, $audit->auditable_id),
                        $this->getModelName($audit->auditable_type, $audit->auditable_id),
                        $this->getReadableEvent($audit->event),
                        $audit->name ?: 'Système',
                        $changes,
                        Carbon::parse($audit->created_at)->format('d/m/Y H:i:s')
                    ]);
                }
                
                fclose($file);
            };
            
            return response()->stream($callback, 200, $headers);
        } catch (\Exception $e) {
            return response()->json([
                'status' => 500,
                'message' => 'Une erreur est survenue lors de l\'exportation: ' . $e->getMessage(),
                'line' => $e->getLine(),
                'file' => $e->getFile()
            ], 500);
        }
    }
    
    /**
     * Format changes for CSV export.
     */
    private function formatChangesForCsv($event, $oldValues, $newValues, $modelClass)
    {
        $changes = '';
        
        if ($event === 'created') {
            $changes = 'Création: ';
            foreach ($newValues as $key => $value) {
                if ($key !== 'id') { // Ne pas inclure l'ID dans les changements
                    $fieldName = $this->getReadableFieldName($modelClass, $key);
                    $changes .= $fieldName . ': ' . $this->formatValue($value) . '; ';
                }
            }
        } else if ($event === 'updated') {
            $changes = 'Modification: ';
            foreach ($newValues as $key => $value) {
                if ($key !== 'id' && isset($oldValues[$key])) { // Ne pas inclure l'ID dans les changements
                    $fieldName = $this->getReadableFieldName($modelClass, $key);
                    $changes .= $fieldName . ': ' . $this->formatValue($oldValues[$key]) . ' → ' . 
                               $this->formatValue($value) . '; ';
                }
            }
        } else if ($event === 'deleted') {
            $changes = 'Suppression';
        } else if ($event === 'restored') {
            $changes = 'Restauration';
        }
        
        return $changes;
    }
    
    /**
     * Format a value for display.
     */
    private function formatValue($value)
    {
        if ($value === null) {
            return 'Non défini';
        }
        
        if (is_array($value)) {
            return json_encode($value);
        }
        
        // Handle date values
        if (is_string($value) && preg_match('/^\d{4}-\d{2}-\d{2}T\d{2}:\d{2}:\d{2}/', $value)) {
            try {
                return Carbon::parse($value)->format('d/m/Y H:i:s');
            } catch (\Exception $e) {
                // Not a valid date, return as is
            }
        }
        
        return $value;
    }
    
    /**
     * Get field names for a model type.
     */
    private function getFieldNames($modelClass)
    {
        // Client model field names (now Formateur)
        if ($modelClass === Client::class) {
            return [
                'first_name' => 'Prénom',
                'last_name' => 'Nom',
                'Telephone' => 'Téléphone',
                'Email' => 'Adresse email',
                'iduser' => 'Créé par',
                'deleted_at' => 'Date de suppression'
            ];
        }
        
        // User model field names - Updated to use nom and prenom
        else if ($modelClass === User::class) {
            return [
                'matricule' => 'Matricule',
                'nom' => 'Nom',
                'prenom' => 'Prénom',
                'email' => 'Email',
                'password' => 'Mot de passe',
                'telephone' => 'Téléphone',
                'fonction' => 'Fonction',
                'deleted_at' => 'Date de suppression'
            ];
        }
        
        // Fournisseur model field names
        else if ($modelClass === Fournisseur::class) {
            return [
                'entreprise' => 'Entreprise',
                'Telephone' => 'Téléphone',
                'Email' => 'Adresse email',
                'iduser' => 'Créé par',
                'deleted_at' => 'Date de suppression'
            ];
        }
        
        // Local model field names
        else if ($modelClass === Local::class) {
            return [
                'name' => 'Nom',
                'iduser' => 'Créé par',
                'deleted_at' => 'Date de suppression'
            ];
        }
        
        // Tva model field names
        else if ($modelClass === Tva::class) {
            return [
                'name' => 'Nom',
                'value' => 'Valeur (%)',
                'iduser' => 'Créé par',
                'deleted_at' => 'Date de suppression'
            ];
        }
        
        // Rayon model field names
        else if ($modelClass === Rayon::class) {
            return [
                'name' => 'Nom',
                'iduser' => 'Créé par',
                'id_local' => 'Local',
                'deleted_at' => 'Date de suppression'
            ];
        }
        
        // Unite model field names
        else if ($modelClass === Unite::class) {
            return [
                'name' => 'Nom',
                'iduser' => 'Créé par',
                'deleted_at' => 'Date de suppression'
            ];
        }
        
        // Category model field names
        else if ($modelClass === Category::class) {
            return [
                'name' => 'Nom',
                'iduser' => 'Créé par',
                'deleted_at' => 'Date de suppression'
            ];
        }
        
        // SubCategory model field names (now Famille)
        else if ($modelClass === SubCategory::class) {
            return [
                'name' => 'Nom',
                'id_categorie' => 'Catégorie',
                'iduser' => 'Créé par',
                'deleted_at' => 'Date de suppression'
            ];
        }
        
        // Product model field names
        else if ($modelClass === Product::class) {
            return [
                'name' => 'Nom',
                'code_article' => 'Code article',
                'unite' => 'Unité',
                'price_achat' => 'Prix d\'achat',
                'price_vente' => 'Prix de vente',
                'code_barre' => 'Code barre',
                'emplacement' => 'Emplacement',
                'id_categorie' => 'Catégorie',
                'id_subcategorie' => 'Famille',  // Changed from 'Sous-catégorie' to 'Famille'
                'id_local' => 'Local',
                'id_rayon' => 'Rayon',
                'id_user' => 'Créé par',
                'deleted_at' => 'Date de suppression'
            ];
        }
        
        // Vente model field names
        else if ($modelClass === Vente::class) {
            return [
                'total' => 'Total',
                'status' => 'Statut',
                'type_commande' => 'Type de commande',
                'type_menu' => 'Type de menu',
                'id_formateur' => 'Formateur',
                'id_user' => 'Créé par',
                'eleves' => 'Nombre d\'élèves',
                'personnel' => 'Nombre de personnel',
                'invites' => 'Nombre d\'invités',
                'divers' => 'Divers',
                'entree' => 'Entrée',
                'plat_principal' => 'Plat principal',
                'accompagnement' => 'Accompagnement',
                'dessert' => 'Dessert',
                'deleted_at' => 'Date de suppression'
            ];
        }
        
        // Achat model field names
        else if ($modelClass === Achat::class) {
            return [
                'total' => 'Total',
                'status' => 'Statut',
                'id_Fournisseur' => 'Fournisseur',
                'id_user' => 'Créé par',
                'deleted_at' => 'Date de suppression'
            ];
        }
        
        // StockTransfer model field names
        else if ($modelClass === StockTransfer::class) {
            return [
                'status' => 'Statut',
                'from' => 'De (utilisateur)',
                'to' => 'À (utilisateur)',
                'id_user' => 'Créé par',
                'deleted_at' => 'Date de suppression'
            ];
        }
        
        return [];
    }
    
    /**
     * Get model class from type string.
     */
    private function getModelClass($type)
    {
        $mapping = [
            'formateur' => Client::class,         // Changed from 'client' to 'formateur'
            'user' => User::class,
            'fournisseur' => Fournisseur::class,
            'local' => Local::class,
            'tva' => Tva::class,
            'rayon' => Rayon::class,
            'unite' => Unite::class,
            'category' => Category::class,
            'famille' => SubCategory::class,      // Changed from 'subcategory' to 'famille'
            'product' => Product::class,
            'commande' => Vente::class,           // Add vente as commande
            'achat' => Achat::class,              // Add achat
            'transfer' => StockTransfer::class,   // Add StockTransfer for transfers only
            'retour' => StockTransfer::class,     // Add StockTransfer for retours only
            // For backward compatibility
            'client' => Client::class,            // Keep this for backward compatibility
            'subcategory' => SubCategory::class,  // Keep this for backward compatibility
            // Add other mappings as needed
        ];
        
        return $mapping[$type] ?? null;
    }
    
    /**
     * Get readable event name.
     */
    private function getReadableEvent($event)
    {
        $events = [
            'created' => 'Création',
            'updated' => 'Modification',
            'deleted' => 'Suppression',
            'restored' => 'Restauration',
        ];
        
        return $events[$event] ?? ucfirst($event);
    }
    
/**
 * Get readable model name with enhanced StockTransfer detection.
 */
private function getReadableModelName($modelClass, $auditableId = null, $auditData = null)
{
    // Special handling for StockTransfer to distinguish between Transfer and Retour
    if ($modelClass === StockTransfer::class) {
        
        // First try to get from current database record
        if ($auditableId) {
            $stockTransferData = DB::table('stocktransfer')
                ->select('from', 'to')
                ->where('id', $auditableId)
                ->first();
                
            if ($stockTransferData) {
                if (!is_null($stockTransferData->from) && !is_null($stockTransferData->to)) {
                    return 'Transfer';
                } elseif (is_null($stockTransferData->from) && !is_null($stockTransferData->to)) {
                    return 'Retour';
                }
            }
        }
        
        // If record doesn't exist in DB, try to analyze audit data
        if ($auditData) {
            $oldValues = is_string($auditData->old_values) ? json_decode($auditData->old_values, true) : $auditData->old_values;
            $newValues = is_string($auditData->new_values) ? json_decode($auditData->new_values, true) : $auditData->new_values;
            
            // Check old values first
            if ($oldValues && is_array($oldValues)) {
                if (array_key_exists('from', $oldValues) && array_key_exists('to', $oldValues)) {
                    if (!is_null($oldValues['from']) && !is_null($oldValues['to'])) {
                        return 'Transfer';
                    } elseif (is_null($oldValues['from']) && !is_null($oldValues['to'])) {
                        return 'Retour';
                    }
                }
            }
            
            // Check new values if old values don't have the info
            if ($newValues && is_array($newValues)) {
                if (array_key_exists('from', $newValues) && array_key_exists('to', $newValues)) {
                    if (!is_null($newValues['from']) && !is_null($newValues['to'])) {
                        return 'Transfer';
                    } elseif (is_null($newValues['from']) && !is_null($newValues['to'])) {
                        return 'Retour';
                    }
                }
            }
        }
        
        // Fallback - this should rarely happen now
        return 'Transfer/Retour';
    }
    
    $mapping = [
        Client::class => 'Formateur',          
        User::class => 'Utilisateur',
        Fournisseur::class => 'Fournisseur',
        Local::class => 'Local',
        Tva::class => 'TVA',
        Rayon::class => 'Rayon',
        Unite::class => 'Unité',
        Category::class => 'Catégorie',
        SubCategory::class => 'Famille',       
        Product::class => 'Produit',
        Vente::class => 'Commande',            
        Achat::class => 'Achat',               
        StockTransfer::class => 'Transfer/Retour', // This line should never be reached now
    ];
    
    return $mapping[$modelClass] ?? class_basename($modelClass);
}

// Update the details method to pass audit data
// In your details method, change this line:
// 'modelType' => $this->getReadableModelName($audit->auditable_type, $audit->auditable_id),
// To:
// 'modelType' => $this->getReadableModelName($audit->auditable_type, $audit->auditable_id, $audit),
    
    /**
     * Get a friendly name for a model instance.
     */
    private function getModelName($modelClass, $id)
    {
        if ($modelClass === Client::class) {
            $client = Client::withTrashed()->find($id);
            return $client ? $client->first_name . ' ' . $client->last_name : 'Formateur';  // Changed from 'Client'
        }
        
        if ($modelClass === User::class) {
            // Use raw SQL like TvaController
            $user = DB::table('users')
                ->select(DB::raw("CONCAT(COALESCE(prenom, ''), ' ', COALESCE(nom, '')) as name"))
                ->where('id', $id)
                ->first();
            
            return $user ? $user->name : 'Utilisateur';
        }
        
        if ($modelClass === Fournisseur::class) {
            $fournisseur = Fournisseur::withTrashed()->find($id);
            return $fournisseur ? $fournisseur->entreprise : 'Fournisseur';
        }
        
        if ($modelClass === Local::class) {
            $local = Local::withTrashed()->find($id);
            return $local ? $local->name : 'Local';
        }
        
        if ($modelClass === Tva::class) {
            $tva = Tva::withTrashed()->find($id);
            return $tva ? $tva->name . ' (' . $tva->value . '%)' : 'TVA';
        }
        
        if ($modelClass === Rayon::class) {
            $rayon = Rayon::withTrashed()->find($id);
            return $rayon ? $rayon->name : 'Rayon';
        }
        
        if ($modelClass === Unite::class) {
            $unite = Unite::withTrashed()->find($id);
            return $unite ? $unite->name : 'Unité';
        }
        
        if ($modelClass === Category::class) {
            $category = Category::withTrashed()->find($id);
            return $category ? $category->name : 'Catégorie';
        }
        
        if ($modelClass === SubCategory::class) {
            $subcategory = SubCategory::withTrashed()->find($id);
            return $subcategory ? $subcategory->name : 'Famille';  // Changed from 'Sous-catégorie'
        }
        
        if ($modelClass === Product::class) {
            $product = Product::withTrashed()->find($id);
            return $product ? $product->name . ' (' . $product->code_article . ')' : 'Produit';
        }
        
        if ($modelClass === Vente::class) {
            $vente = Vente::withTrashed()->find($id);
            if ($vente) {
                $formateur = DB::table('users')
                    ->select(DB::raw("CONCAT(COALESCE(prenom, ''), ' ', COALESCE(nom, '')) as name"))
                    ->where('id', $vente->id_formateur)
                    ->first();
                
                return 'Commande #' . $id . ' - ' . ($formateur ? $formateur->name : 'Formateur inconnu');
            }
            return 'Commande #' . $id;
        }
        
        if ($modelClass === Achat::class) {
            $achat = Achat::withTrashed()->find($id);
            if ($achat) {
                $fournisseur = Fournisseur::withTrashed()->find($achat->id_Fournisseur);
                
                return 'Achat #' . $id . ' - ' . ($fournisseur ? $fournisseur->entreprise : 'Fournisseur inconnu');
            }
            return 'Achat #' . $id;
        }
        
        if ($modelClass === StockTransfer::class) {
            $stockTransfer = StockTransfer::withTrashed()->find($id);
            if ($stockTransfer) {
                // Determine if it's a Transfer or Retour
                $operationType = $stockTransfer->getOperationType();
                
                if ($stockTransfer->isTransfer()) {
                    // For transfers, show both from and to users
                    $fromUser = $stockTransfer->fromUser;
                    $toUser = $stockTransfer->toUser;
                    
                    $fromName = $fromUser ? "{$fromUser->prenom} {$fromUser->nom}" : 'Utilisateur inconnu';
                    $toName = $toUser ? "{$toUser->prenom} {$toUser->nom}" : 'Utilisateur inconnu';
                    
                    return "{$operationType} #{$id} - De: {$fromName} À: {$toName}";
                } elseif ($stockTransfer->isRetour()) {
                    // For retours, show only the to user
                    $toUser = $stockTransfer->toUser;
                    $toName = $toUser ? "{$toUser->prenom} {$toUser->nom}" : 'Utilisateur inconnu';
                    
                    return "{$operationType} #{$id} - À: {$toName}";
                }
                
                return "{$operationType} #{$id}";
            }
            return 'Transfer/Retour #' . $id;
        }
        
        // Pour tout autre modèle, retourner simplement le nom de la classe
        return class_basename($modelClass);
    }
}