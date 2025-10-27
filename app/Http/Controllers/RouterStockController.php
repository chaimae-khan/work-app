<?php
namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Stock;
use App\Models\User;
use App\Models\Vente;
use App\Models\LigneVente;
use App\Models\TmpStockTransfer;
use App\Models\LineTransfer;
use App\Models\StockTransfer;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use Yajra\DataTables\Facades\DataTables;

class RouterStockController extends Controller
{
  public function index(Request $request)
    {
        // Check permission for viewing router list
        if (!auth()->user()->can('retour')) {
            return view('Error.index')
                ->withErrors('Vous n\'avez pas la permission de voir cette page');
        }

        $countStock = Stock::count();
        if($countStock == 0)
        {
            return view('Error.index')
            ->withErrors('tu n\'as pas de stock ');
        }
        
        if($request->ajax())
        {
            $query = DB::table('stocktransfer as st')
                ->join('users as created_by', 'st.id_user', '=', 'created_by.id')
                ->join('users as to_user', 'st.to', '=', 'to_user.id')
                ->leftJoin('users as from_user', 'st.from', '=', 'from_user.id')
                ->select(
                    'st.id',
                    'st.status',
                    'st.refusal_reason',
                    'st.created_at',
                    DB::raw("CONCAT(to_user.prenom, ' ', to_user.nom) as to_name"),
                    DB::raw("CONCAT(created_by.prenom, ' ', created_by.nom) as created_by_name"),
                    DB::raw("CASE WHEN st.from IS NULL THEN NULL ELSE CONCAT(from_user.prenom, ' ', from_user.nom) END as from_name"),
                    // Count total products in this transfer
                    DB::raw("(SELECT COUNT(DISTINCT id_product) FROM line_transfer WHERE id_stocktransfer = st.id) as product_count"),
                    // Sum total quantity in this transfer
                    DB::raw("(SELECT COALESCE(SUM(quantite), 0) FROM line_transfer WHERE id_stocktransfer = st.id) as total_quantity")
                )
                ->whereIn('st.status', ['Validation', 'Création', 'Refus'])
                ->whereNull('st.from')
                ->whereNull('st.deleted_at'); // Exclude soft-deleted records
                
            return DataTables::of($query)
                ->addIndexColumn()
                ->addColumn('reference', function ($row) {
                    return 'RET-' . str_pad($row->id, 6, '0', STR_PAD_LEFT);
                })
                ->addColumn('status', function ($row) {
                    $statusColors = [
                        'Création' => 'bg-warning',
                        'Validation' => 'bg-success',
                        'Routage' => 'bg-info',
                        'Terminé' => 'bg-primary',
                        'Refus' => 'bg-danger',
                        'default' => 'bg-secondary'
                    ];
        
                    $status = $row->status ?? 'Création';
                    $color = $statusColors[$status] ?? $statusColors['default'];
                    
                    $statusHtml = '<span class="badge ' . $color . '">' . $status . '</span>';
                    
                    // Add refusal reason if status is "Refus" and reason exists
                    if ($status === 'Refus' && !empty($row->refusal_reason)) {
                        $statusHtml .= '<br><small class="text-muted mt-1 d-block">' . 
                                      '<i class="fa-solid fa-info-circle me-1"></i>' . 
                                      htmlspecialchars($row->refusal_reason) . 
                                      '</small>';
                    }
                    
                    return $statusHtml;
                })
                ->addColumn('action', function ($row) {
                    $btn = '';
                    $isAdmin = auth()->user()->hasRole('Administrateur');

                    // If status is "Refus", only show action icons if user is Admin
                    if ($row->status === 'Refus' && !$isAdmin) {
                        return '';
                    }

                    // Edit button - only show if status is "Création" and user has edit permission
                    if (auth()->user()->can('retour-modifier') && $row->status === 'Création') {
                        $btn .= '<a href="#" class="btn btn-sm bg-primary-subtle me-1 edit-btn" 
                                    data-id="' . $row->id . '">
                                    <i class="fa-solid fa-pen-to-square text-primary"></i>
                                </a>';
                    }

                    // View button - show for all statuses if user has view permission
                    if (auth()->user()->can('retour')) {
                        $btn .= '<a href="' . url('router/' . $row->id) . '" class="btn btn-sm bg-success-subtle me-1" 
                                    data-bs-toggle="tooltip" 
                                    title="Voir Détails">
                                    <i class="fa-solid fa-eye text-success"></i>
                                </a>';
                    }

                    // Delete button - don't show if status is "Validation" and user has delete permission
                    if (auth()->user()->can('retour-supprimer') && $row->status !== 'Validation') {
                        $btn .= '<a href="#" class="btn btn-sm bg-danger-subtle DeleteRouter"
                                    data-id="' . $row->id . '" 
                                    data-bs-toggle="tooltip" 
                                    title="Supprimer Retour">
                                    <i class="fa-solid fa-trash text-danger"></i>
                                </a>';
                    }

                    return $btn;
                })
                ->filterColumn('to_name', function($query, $keyword) {
                    $query->whereRaw("LOWER(CONCAT(to_user.prenom, ' ', to_user.nom)) LIKE ?", ["%".strtolower($keyword)."%"]);
                })
                ->filterColumn('created_by_name', function($query, $keyword) {
                    $query->whereRaw("LOWER(CONCAT(created_by.prenom, ' ', created_by.nom)) LIKE ?", ["%".strtolower($keyword)."%"]);
                })
                ->rawColumns(['status', 'refusal_reason_display', 'action'])
                ->make(true);
        }

        $statuses = DB::table('stocktransfer')
            ->select('status')
            ->distinct()
            ->pluck('status');

        // Get only the currently connected user
        $Formateur = User::where('id', Auth::id())->get();

        return view('router.index')
            ->with('Formateur', $Formateur)
            ->with('statuses', $statuses);
    }


    public function getFormateurCommands(Request $request)
    {
        // This is a supporting method, no permission check needed
        $IdFormateurSend = $request->id;
       
        if($IdFormateurSend)
        {
            $CommandeByFormateurSend = DB::table('ventes as v')
                ->join('users as u', 'u.id','=','v.id_user')
                ->where('v.id_formateur',$IdFormateurSend)
                ->where('v.status','Validation')
                ->select(
                    'v.*',
                    DB::raw("CONCAT(u.prenom, ' ', u.nom) as name"),
                    DB::raw("LPAD(v.id, 4, '0') as matricule")
                )
                ->get();

            return response()->json([
                'status' => 200,
                'dataCommandeByFormateurSend' => $CommandeByFormateurSend
            ]);
        }

        return response()->json([
            'status' => 400,
            'message' => 'ID formateur requis'
        ]);
    }

    public function GetLigneCommandeByCommand(Request $request)
    {
        // This is a supporting method, no permission check needed
        $data = DB::table('ligne_vente as l')
            ->join('products as p','p.id' , '=','l.idproduit')
            ->join('stock as s','s.id_product','=','p.id')
            ->select('p.name','p.code_article','p.seuil','l.contete_formateur','p.id','l.idvente')
            ->where('l.idvente',$request->id)
            ->get();
        
        if($data)
        {
            return response()->json([
                'status' => 200,
                'data' => $data,
            ]);
        }
    }

    public function StoreProductStockTransfer(Request $request)
    {
        // Check permission before storing product to temp stock transfer
        if (!auth()->user()->can('retour-ajouter')) {
            return response()->json([
                'status' => 403,
                'message' => 'Vous n\'avez pas la permission d\'ajouter un retour'
            ], 403);
        }
        
        $data = $request->input('data');          
        $data['id_user'] = Auth::user()->id;
        $data['qteSend'] = 1;
        
        DB::beginTransaction();
    
        try {
            // check qte if insert or not
            $checkQteTransfer = TmpStockTransfer::where('id_product', $data['id'])
                ->where('to', $request->to)
                ->where('iduser', $data['id_user'])
                ->where('idcommande', $request->idcommande)
                ->sum('quantite_transfer');
            
            if($checkQteTransfer == $data['contete_formateur'])
            {
                return response()->json([
                    'status' => 440,
                    'message' => " Impossible de modifier la quantité, car la quantité est égale à 0."
                ]);
            }
    
            $existingProduct = TmpStockTransfer::where('id_product', $data['id'])
                ->where('to', $request->to)
                ->where('iduser', $data['id_user'])
                ->where('idcommande', $request->idcommande)
                ->first();
    
            if ($existingProduct) {
                $existingProduct->increment('quantite_transfer', 1);
                DB::commit();
    
                return response()->json([
                    'status' => 200,
                    'message' => 'Quantité mise à jour avec succès',
                ]);
            } else {
                TmpStockTransfer::create([
                    'id_product' => $data['id'],
                    'quantite_stock' => $data['contete_formateur'],
                    'quantite_transfer' => $data['qteSend'],
                    'from' => Auth::id(),
                    'to' => $request->to,
                    'iduser' => $data['id_user'],
                    'idcommande' => $request->idcommande,
                ]);
                
                DB::commit();
    
                return response()->json([
                    'status' => 200,
                    'message' => 'Ajouté avec succès',
                ]);
            }
        } catch (\Exception $e) {
            DB::rollBack();
    
            return response()->json([
                'status' => 500,
                'message' => 'Une erreur est survenue. Veuillez réessayer.',
                'error' => $e->getMessage(),
            ]);
        }
    }
    
    public function StoreRouter(Request $request)
    {
        // Check permission before storing router
        if (!auth()->user()->can('retour-ajouter')) {
            return response()->json([
                'status' => 403,
                'message' => 'Vous n\'avez pas la permission d\'ajouter un router'
            ], 403);
        }
        
        DB::beginTransaction();
        
        try {
            // Get all temporary stock transfers for this formateur
            $tmpStockTransfers = TmpStockTransfer::where('to', $request->to)
                ->where('idcommande', $request->idcommande)
                ->get();
            
            if ($tmpStockTransfers->isEmpty()) {
                return response()->json([
                    'status' => 400,
                    'message' => 'Aucun produit à router'
                ]);
            }
            
            // Create stocktransfer record for tracking (this will create audit record automatically)
            // Set from to null for router entries
            $stockTransfer = StockTransfer::create([
                'id_user' => Auth::user()->id,
                'status' => 'Création', // Using 'Création' as default
                'from' => null, // Setting from to null for router entries
                'to' => $request->to
            ]);
            
            foreach ($tmpStockTransfers as $item) {
                // Get product details for TVA and Unite
                $product = DB::table('products')
                    ->join('stock', 'stock.id_product', '=', 'products.id')
                    ->select('products.*', 'stock.id_tva', 'stock.id_unite')
                    ->where('products.id', $item->id_product)
                    ->first();
                
                // Create line transfer record (this will create audit record automatically)
                LineTransfer::create([
                    'id_user' => Auth::user()->id, // User performing the action
                    'id_product' => $item->id_product,
                    'id_tva' => $product ? $product->id_tva : null,
                    'id_unite' => $product ? $product->id_unite : null,
                    'idcommande' => $request->idcommande,
                    'id_stocktransfer' => $stockTransfer->id,
                    'quantite' => $item->quantite_transfer
                ]);
            }
            
            // Delete the temporary records
            TmpStockTransfer::where('to', $request->to)
                ->where('idcommande', $request->idcommande)
                ->delete();
            
            DB::commit();
            
            return response()->json([
                'status' => 200,
                'message' => 'Produits routés avec succès'
            ]);
            
        } catch (\Exception $e) {
            DB::rollBack();
            
            return response()->json([
                'status' => 500,
                'message' => 'Une erreur est survenue: ' . $e->getMessage(),
                'error' => $e->getMessage()
            ]);
        }
    }

    public function GetTmpStockTransferByFormateur(Request $request)
    {
        // This is a data retrieval method, no permission check needed
        \Log::info('GetTmpStockTransferByFormateur called with:', $request->all());
        
        $data = DB::table('tmpstocktransfer as t')
            ->join('products as p', 't.id_product', '=', 'p.id')
            ->join('users as u', 't.to', '=', 'u.id')
            ->where('t.to', $request->Formateur)
            ->select([
                't.id',
                'p.name as name_product',
                'p.code_article',
                't.quantite_stock',
                't.quantite_transfer',
                DB::raw("CONCAT(u.prenom, ' ', u.nom) as to_name")
            ])
            ->get();
        
        \Log::info('Query result count:', ['count' => $data->count()]);
        \Log::info('Query results:', $data->toArray());
        
        return response()->json([
            'data' => $data
        ]);
    }
    
    public function DeleteRowsTmpStockTransfer(Request $request)
    {
        // No permission check needed for temporary records management
        // This is one of the methods that should not require permissions
        
        $TmpStockTransfer = TmpStockTransfer::where('id', $request->id)->delete();
        if($TmpStockTransfer)
        {
            return response()->json([
                'status' => 200,
                'message' => 'Suppression effectuée avec succès.'
            ]);
        }
    }

    public function edit(Request $request, $id)
    {
        // Check permission before editing
        if (!auth()->user()->can('retour-modifier')) {
            return response()->json([
                'status' => 403,
                'message' => 'Vous n\'avez pas la permission de modifier un retour'
            ], 403);
        }
        
        $transfer = StockTransfer::find($id);
        
        if (!$transfer) {
            return response()->json([
                'status' => 404,
                'message' => 'Router non trouvé'
            ], 404);
        }

        return response()->json($transfer);
    }

  public function update(Request $request)
{
    $transfer = StockTransfer::find($request->id);
    
    if (!$transfer) {
        return response()->json([
            'status' => 404,
            'message' => 'Router non trouvé'
        ], 404);
    }

    $validator = \Illuminate\Support\Facades\Validator::make($request->all(), [
        'status' => 'required|string|in:Création,Validation,Refus',
    ], [
        'required' => 'Le champ :attribute est requis.',
        'in' => 'Le statut doit être l\'un des suivants: Création, Validation, Refus',
    ], [
        'status' => 'statut',
    ]);

    if ($validator->fails()) {
        return response()->json([
            'status' => 400,
            'errors' => $validator->messages(),
        ], 400);
    }
    
    if ($request->status === 'Validation') {
        //DB::beginTransaction();

        try {
            $lineTransfers = LineTransfer::where('id_stocktransfer', $transfer->id)->get();

            foreach ($lineTransfers as $lineTransfer) {
                $ligneVente = LigneVente::where('idvente', $lineTransfer->idcommande)
                    ->where('idproduit', $lineTransfer->id_product)
                    ->first();

                if (!$ligneVente) {
                    throw new \Exception("Ligne vente introuvable pour le produit ID: " . $lineTransfer->id_product);
                }

                if ($ligneVente->contete_formateur < $lineTransfer->quantite) {
                    throw new \Exception("Quantité insuffisante dans contete_formateur pour le produit ID: " . $lineTransfer->id_product);
                }

                // Reduce contete_formateur
                $ligneVente->contete_formateur -= $lineTransfer->quantite;
                $ligneVente->save();

                // Update stock
                $stock = Stock::firstOrNew(['id_product' => $lineTransfer->id_product]);
                if (!$stock->exists) {
                    $stock->id_tva = $lineTransfer->id_tva;
                    $stock->id_unite = $lineTransfer->id_unite;
                    $stock->quantite = $lineTransfer->quantite;
                } else {
                    $stock->quantite += $lineTransfer->quantite;
                }
                $stock->save();
            }
            
            $transfer->status = 'Validation';
            $transfer->save();

            DB::commit();

            return response()->json([
                'status' => 200,
                'message' => 'Router validé avec succès',
            ]);

        } catch (\Exception $e) {
            DB::rollBack();

            return response()->json([
                'status' => 500,
                'message' => 'Erreur lors de la validation : ' . $e->getMessage(),
            ]);
        }
    }

    // For other statuses (Refus, Création)
    $transfer->status = $request->status;

    // Optional: handle refusal reason if provided
    if ($request->has('refusal_reason')) {
        $transfer->refusal_reason = $request->refusal_reason;
    }

    $transfer->save();

    return response()->json([
        'status' => 200,
        'message' => 'Statut du router mis à jour avec succès',
    ]);
}


    public function ChangeStatusRouter(Request $request)
    {
        // Check permission before changing status
        if (!auth()->user()->can('retour-modifier')) {
            return response()->json([
                'status' => 403,
                'message' => 'Vous n\'avez pas la permission de modifier le statut d\'un retour'
            ], 403);
        }
        
        try {
            $data = $request->all();
            \Log::info('ChangeStatusRouter called with data:', $data);
    
            // Retrieve the transfer record
            $transfer = StockTransfer::find($data['id']);
            
            if (!$transfer) {
                return response()->json([
                    'status' => 404,
                    'message' => 'Router non trouvé'
                ], 404);
            }
    
            // Store old status for audit logging
            $oldStatus = $transfer->status;
    
            if($data['status'] == 'Validation')
            {
                // Begin transaction
                DB::beginTransaction();
                
                try {
                    // First, update the transfer status (this will trigger audit trail automatically)
                    $transfer->status = 'Validation';
                    $transfer->refusal_reason = null; // Clear refusal reason on validation
                    $transfer->save();
                    
                    // Get all LineTransfer records associated with this transfer
                    $lineTransfers = LineTransfer::where('id_stocktransfer', $data['id'])->get();
                    \Log::info('Found ' . $lineTransfers->count() . ' line items for router ID: ' . $data['id']);
    
                    foreach($lineTransfers as $lineTransfer)
                    {
                        // Find the ligne_vente record for this product and idvente
                        $ligneVente = LigneVente::where('idvente', $lineTransfer->idcommande)
                                              ->where('idproduit', $lineTransfer->id_product)
                                              ->first();
                        
                        if($ligneVente) {
                            \Log::info('Processing ligne_vente for product ID: ' . $lineTransfer->id_product . 
                                      ' with commande ID: ' . $lineTransfer->idcommande);
                            
                            // Check if contete_formateur has enough quantity
                            if ($ligneVente->contete_formateur >= $lineTransfer->quantite) {
                                // Reduce the quantity from contete_formateur (this will trigger audit trail automatically)
                                $ligneVente->contete_formateur -= $lineTransfer->quantite;
                                $ligneVente->save();
                                
                                \Log::info('Reduced contete_formateur to: ' . $ligneVente->contete_formateur . 
                                          ' after subtracting ' . $lineTransfer->quantite);
                                
                                // Update or create stock entry (this will trigger audit trail automatically)
                                $stock = Stock::firstOrNew(['id_product' => $lineTransfer->id_product]);
                                if (!$stock->exists) {
                                    $stock->id_tva = $lineTransfer->id_tva;
                                    $stock->id_unite = $lineTransfer->id_unite;
                                    $stock->quantite = $lineTransfer->quantite;
                                } else {
                                    $stock->quantite += $lineTransfer->quantite;
                                }
                                $stock->save();
                                
                                \Log::info('Updated stock for product ID: ' . $lineTransfer->id_product . 
                                          ' to quantity: ' . $stock->quantite);
                            } else {
                                throw new \Exception('Quantité insuffisante dans contete_formateur pour le produit ID: ' . $lineTransfer->id_product);
                            }
                        } else {
                            \Log::warning('Aucun ligne_vente trouvé pour le produit ID: ' . $lineTransfer->id_product . 
                                          ' et commande ID: ' . $lineTransfer->idcommande);
                        }
                    }
                    
                    DB::commit();
                    
                    // Log the status change
                    \Log::info('StockTransfer (Retour) status changed from "' . $oldStatus . '" to "Validation" for transfer ID: ' . $transfer->id . ' by user: ' . auth()->user()->id);
                    
                    return response()->json([
                        'status' => 200,
                        'message' => 'Validation réussie'
                    ]);
                    
                } catch (\Exception $e) {
                    DB::rollBack();
                    \Log::error('Error in validation process: ' . $e->getMessage());
                    \Log::error($e->getTraceAsString());
                    
                    return response()->json([
                        'status' => 500,
                        'message' => 'Une erreur est survenue lors de la validation: ' . $e->getMessage(),
                        'error' => $e->getMessage()
                    ]);
                }
            }
            else if($data['status'] == 'Refus')
            {
                // Validate refusal reason is provided
                if (empty($data['refusal_reason'])) {
                    return response()->json([
                        'status' => 400,
                        'message' => 'Le motif de refus est requis'
                    ], 400);
                }
                
                $transfer->status = 'Refus';
                $transfer->refusal_reason = $data['refusal_reason'];
                $result = $transfer->save(); // This will create audit record automatically
                
                \Log::info('Updated router status to Refus with reason. Result: ' . ($result ? 'success' : 'failed'));
                
                // Log the status change
                \Log::info('StockTransfer (Retour) status changed from "' . $oldStatus . '" to "Refus" with reason: "' . $data['refusal_reason'] . '" for transfer ID: ' . $transfer->id . ' by user: ' . auth()->user()->id);
                
                return response()->json([
                    'status' => 200,
                    'message' => 'Refus enregistré avec succès'
                ]);
            }
            else
            {
                $transfer->status = $data['status'];
                $transfer->refusal_reason = null; // Clear refusal reason for other statuses
                $result = $transfer->save(); // This will create audit record automatically
                
                \Log::info('Updated router status to: ' . $data['status'] . '. Result: ' . ($result ? 'success' : 'failed'));
                
                // Log the status change
                \Log::info('StockTransfer (Retour) status changed from "' . $oldStatus . '" to "' . $data['status'] . '" for transfer ID: ' . $transfer->id . ' by user: ' . auth()->user()->id);
                
                return response()->json([
                    'status' => 200,
                    'message' => 'Opération réussie'
                ]);
            }
        } catch (\Exception $e) {
            \Log::error('Error in ChangeStatusRouter: ' . $e->getMessage());
            \Log::error($e->getTraceAsString());
            
            return response()->json([
                'status' => 500,
                'message' => 'Une erreur est survenue lors du changement de statut: ' . $e->getMessage(),
                'error' => $e->getMessage()
            ]);
        }
    }
    
    public function UpdateQteRouterTmp(Request $request)
    {
        // No permission check needed for temporary records management
        // This is one of the methods that should not require permissions
        
        $validator = \Illuminate\Support\Facades\Validator::make($request->all(), [
            'qte' => 'required',
        ], [
            'required' => 'Le champ :attribute est requis.',
        ], [
            'qte' => 'quantité',
        ]);
        
        if ($validator->fails()) {
            return response()->json([
                'status' => 400,
                'errors' => $validator->messages(),
            ], 400);
        }
        
        $TmpStockTransfer = TmpStockTransfer::where('id', $request->id)->update([
            'quantite_transfer' => $request->qte,
        ]);
        
        if($TmpStockTransfer)
        {
            return response()->json([
                'status'    => 200,
                'message'   => 'Mise à jour effectuée avec succès.'
            ]);
        }
    }

    public function deleteRouter(Request $request)
    {
        // Check permission before deleting
        if (!auth()->user()->can('retour-supprimer')) {
            return response()->json([
                'status' => 403,
                'message' => 'Vous n\'avez pas la permission de supprimer un retour'
            ], 403);
        }
        
        try {
            DB::beginTransaction();
            
            // Find the router
            $router = StockTransfer::findOrFail($request->id);
            
            // Check if router can be deleted (only if status is not "Validation")
            if ($router->status === 'Validation') {
                return response()->json([
                    'status' => 400,
                    'message' => 'Impossible de supprimer un retour validé'
                ], 400);
            }
            
            // Delete related line transfer records first (this will create audit records automatically)
            LineTransfer::where('id_stocktransfer', $router->id)->delete();
            
            // Then delete the router record (this will create audit record automatically)
            $router->delete();
            
            DB::commit();
            
            return response()->json([
                'status' => 200,
                'message' => 'Retour supprimé avec succès.'
            ]);
            
        } catch (\Exception $e) {
            DB::rollBack();
            \Log::error('Error deleting router: ' . $e->getMessage());
            
            return response()->json([
                'status' => 500,
                'message' => 'Une erreur est survenue lors de la suppression du retour: ' . $e->getMessage()
            ], 500);
        }
    }

public function show($id)
{
    // Get the stock transfer (retour) with relationships
    $stockTransfer = StockTransfer::with(['user', 'toUser', 'fromUser'])
        ->findOrFail($id);
    
    // Get all line transfers for this retour with related data
    $lineTransfers = LineTransfer::where('id_stocktransfer', $id)
        ->with(['product', 'tva', 'unite', 'vente', 'user'])
        ->get();
    
    // Calculate totals
    $totalProducts = $lineTransfers->count();
    $totalQuantity = $lineTransfers->sum('quantite');
    
    // Get the user who changed the status from audit logs
    $statusChanger = null;
    $statusChangeDate = null;
    $audit = DB::table('audits')
        ->where('auditable_type', 'App\\Models\\StockTransfer')
        ->where('auditable_id', $id)
        ->where('event', 'updated')
        ->whereRaw("JSON_EXTRACT(new_values, '$.status') IS NOT NULL")
        ->orderBy('created_at', 'desc')
        ->first();
    
    if ($audit) {
        $changerUser = User::find($audit->user_id);
        if ($changerUser) {
            $statusChanger = $changerUser->prenom . ' ' . $changerUser->nom;
        }
        $statusChangeDate = \Carbon\Carbon::parse($audit->created_at)->format('d/m/Y H:i');
    }
    
    // Return the view with data
    return view('router.detail', compact(
        'stockTransfer',
        'lineTransfers',
        'totalProducts',
        'totalQuantity',
        'statusChanger',
        'statusChangeDate'
    ));
}





}
