<?php

namespace App\Http\Controllers;

use App\Services\InventoryService;
use Illuminate\Http\Request;
use App\Models\User;
use App\Models\Category;
use App\Models\Local;
use App\Models\SubCategory;
use App\Models\Rayon;
use App\Models\Tva;
use App\Models\Unite;
use Illuminate\Support\Facades\DB;
use Yajra\DataTables\Facades\DataTables;
use Illuminate\Support\Facades\Auth;
use App\Models\TempVente;
use App\Models\Vente;
use App\Models\LigneVente;
use Illuminate\Support\Facades\Validator;
use Hashids\Hashids;
use Barryvdh\DomPDF\Facade\Pdf;
use App\Models\Stock;
use App\Notifications\SystemNotification;
use App\Models\Historique_Sig;

use function PHPUnit\Framework\isNull;

class VenteController extends Controller
{
    protected $inventoryService;
    
    public function __construct(InventoryService $inventoryService)
    {
        $this->inventoryService = $inventoryService;
    }
    
   public function index(Request $request)
{
    /* $user = Auth::user();
    $role = $user->getRoleNames()->first();
    dd(Auth::user()->hasRole('Formateur')); */
    $this->autoDeleteOldVentes();

    if($request->ajax())
    {
        $hashids = new Hashids();
       
        $query = DB::table('ventes as v')
            ->join('users as f','f.id','=','v.id_formateur')
            ->join('users as u','u.id','=','v.id_user')
            ->select('v.id', 'v.total', 'v.status', 'v.type_commande', 'v.type_menu',
                     DB::raw("CONCAT(f.prenom, ' ', f.nom) as formateur_name"),
                     DB::raw("CONCAT(u.prenom, ' ', u.nom) as name"), 'v.created_at',
                     'v.eleves', 'v.personnel', 'v.invites', 'v.divers',
                     'v.entree', 'v.plat_principal', 'v.accompagnement', 'v.dessert', 'v.date_usage')
            ->whereNull('v.deleted_at');
            if(Auth::user()->hasRole('Formateur'))
            {
                $query->where('v.id_formateur',Auth::id());
            }
            $Data_Vente = $query->orderBy('v.id','desc')->get();
            //dd($Data_Vente);
            /* ->orderBy('v.id','desc')
            ->get(); */

        return DataTables::of($Data_Vente)
            ->addIndexColumn()
            ->addColumn('action', function ($row) use ($hashids) {
                $btn = '';

                $isAdmin = DB::table('model_has_roles')
                    ->join('roles', 'model_has_roles.role_id', '=', 'roles.id')
                    ->where('model_has_roles.model_id', auth()->id())
                    ->where('roles.name', 'Administrateur')
                    ->exists();

                 if ($row->status === 'Refus' && !$isAdmin) {
                        return '';
                    }

                    // Edit button - don't show if status is "Validation"
                    if (auth()->user()->can('Commande-modifier') && $row->status !== 'Validation') {
                        $btn .= '<a href="#" class="btn btn-sm bg-primary-subtle me-1"
                                    data-id="' . $row->id . '">
                                    <i class="fa-solid fa-pen-to-square text-primary"></i>
                                </a>';
                    }
                    
                    // Detail button with hash ID - show for all statuses
                    if (auth()->user()->can('Commande')) {
                        $btn .= '<a href="' . url('ShowBonVente/' . $hashids->encode($row->id)) . '" 
                                    class="btn btn-sm bg-success-subtle me-1" 
                                    data-id="' . $row->id . '" 
                                    target="_blank">
                                    <i class="fa-solid fa-eye text-success"></i>
                                </a>';
                    }
                    
                    // Print invoice button - don't show if status is "Refus"
                    if (auth()->user()->can('Commande') && $row->status !== 'Refus') {
                        $btn .= '<a href="' . url('FactureVente/' . $hashids->encode($row->id)) . '" 
                                    class="btn btn-sm bg-info-subtle me-1" 
                                    data-id="' . $row->id . '" 
                                    target="_blank">
                                    <i class="fa-solid fa-print text-info"></i>
                                </a>';
                    }

                    // Delete button - don't show if status is "Validation"
                    if (auth()->user()->can('Commande-supprimer') && $row->status !== 'Validation') {
                        $btn .= '<a href="#" class="btn btn-sm bg-danger-subtle DeleteVente"
                                    data-id="' . $row->id . '" 
                                    data-bs-toggle="tooltip" 
                                    title="Supprimer Vente">
                                    <i class="fa-solid fa-trash text-danger"></i>
                                </a>';
                    }

                return $btn;
            })
            ->rawColumns(['action'])
            ->make(true);
    }

    $formateurs = User::where('id', Auth::id())->get();
    $categories = Category::all();
    $subcategories = SubCategory::all();
    $locals = Local::all();
    $rayons = Rayon::all();
    $tvas = Tva::all();
    $unites = Unite::all();
    $class = DB::select("select distinct(classe) as classe from categories");


    $Plat_Entre = DB::select("select * from plats where type='Entrée'");
    $Plat_Dessert = DB::select("select * from plats where type='Dessert'");
    $Plat_Principal = DB::select("select * from plats where type='Plat Principal'");

    return view('vente.index')
        ->with('formateurs', $formateurs)
        ->with('categories', $categories)
        ->with('subcategories', $subcategories)
        ->with('locals', $locals)
        ->with('rayons', $rayons)
        ->with('tvas', $tvas)
        ->with('unites', $unites)
        ->with('Plat_Entre', $Plat_Entre)
        ->with('Plat_Dessert', $Plat_Dessert)
        ->with('Plat_Principal', $Plat_Principal)
        ->with('class',$class); 
        
}


//   public function getProduct(Request $request)
// {
//     try {
//         $name_product = $request->product;
       
    
//         if ($request->ajax()) {
//             $query = DB::table('products as p')
//                 ->join('stock as s', 'p.id', '=', 's.id_product')
//                 ->join('locals as l', 'p.id_local', '=', 'l.id')
//                 ->leftJoin('categories as c', 'p.id_categorie', '=', 'c.id')
//                 ->leftJoin('sub_categories as sc', 'p.id_subcategorie', '=', 'sc.id')
//                 ->where('p.name', 'like', '%' . $name_product . '%')
//                 ->whereNull('p.deleted_at');
            
//             // Apply class filter if provided
//             if ($request->filled('filter_class')) {
//                 $query->where('c.classe', $request->filter_class);
//             }

//             // Apply category filter if provided
//             if ($request->filled('filter_categorie')) {
//                 $query->where('p.id_categorie', $request->filter_categorie);
//             }

//             // Apply subcategory filter if provided
//             if ($request->filled('filter_subcategorie')) {
//                 $query->where('p.id_subcategorie', $request->filter_subcategorie);
//             }
            
//             $Data_Product = $query->select('p.name', 's.quantite', 'p.seuil', 'p.price_achat', 'l.name as name_local', 'p.id')
//                 ->get();
                
//             return response()->json([
//                 'status' => 200,
//                 'data'   => $Data_Product
//             ]);
//         }
//     } catch (\Exception $e) {
//         \Log::error('Error in getProduct: ' . $e->getMessage());
        
//         return response()->json([
//             'status' => 500,
//             'message' => 'Une erreur est survenue lors de la recherche de produits',
//             'error' => $e->getMessage()
//         ], 500);
//     }
// }

    public function PostInTmpVente(Request $request)
    {
        // Check permission before posting to temp vente
        if (!auth()->user()->can('Commande-ajoute')) {
            return response()->json([
                'status' => 403,
                'message' => 'Vous n\'avez pas la permission d\'ajouter une commande'
            ], 403);
        }
        
        $data = $request->all();
        $data['id_user'] = Auth::user()->id;
        $data['qte'] = 1;
        
        DB::beginTransaction();

        try {
            // Get stock for this product
            $stock = Stock::where('id_product', $data['idproduit'])->first();
            
            // Get product name for error message
            $product = DB::table('products')->where('id', $data['idproduit'])->first();
            $productName = $product ? $product->name : 'Unknown Product';

            $existingProduct = TempVente::where('idproduit', $data['idproduit'])
                ->where('id_formateur', $data['id_formateur'])
                ->where('id_user', $data['id_user'])
                ->first();

            // Calculate the requested quantity (1 for new items or current+1 for existing)
            $requestedQty = $existingProduct ? $existingProduct->qte + 1 : 1;
                
            // Check if requested quantity is available in stock
            if (!$stock || $stock->quantite < $requestedQty) {
                // Log the warning in the same format as ChangeStatusVente
                \Log::warning('Insufficient stock for product: "' . $productName . 
                          '" (Requested: ' . $requestedQty . ', Available: ' . 
                          ($stock ? $stock->quantite : 0) . ')');
                
                DB::rollBack();
                
                // Return error in the format that will be displayed to the user
                return response()->json([
                    'status' => 400,
                    'message' => 'ERROR',
                    'details' => 'Stock insuffisant pour "' . $productName . '". Disponible: ' . 
                               ($stock ? $stock->quantite : 0) . ', Demandé: ' . $requestedQty,
                    'type' => 'error'
                ]);
            }

            if ($existingProduct) {
                $existingProduct->increment('qte', 1);
                DB::commit();

                return response()->json([
                    'status' => 200,
                    'message' => 'SUCCESS',
                    'details' => 'La quantité a été mise à jour avec succès',
                    'type' => 'success'
                ]);
            } else {
                TempVente::create($data);
                DB::commit();

                return response()->json([
                    'status' => 200,
                    'message' => 'SUCCESS',
                    'details' => 'Ajouté avec succès',
                    'type' => 'success'
                ]);
            }
        } catch (\Exception $e) {
            DB::rollBack();
            \Log::error('Error in PostInTmpVente: ' . $e->getMessage());

            return response()->json([
                'status' => 500,
                'message' => 'ERROR',
                'details' => 'Une erreur s\'est produite. Veuillez réessayer.',
                'type' => 'error',
                'error' => $e->getMessage(),
            ]);
        }
    }

 public function GetTmpVenteByFormateur(Request $request)
{
   
   
    $Data = DB::table('temp_vente as t')
        ->join('users as f', 't.id_formateur', '=', 'f.id')
        ->join('products as p', 't.idproduit', '=', 'p.id')
        ->join('users as u', 't.id_user', '=', 'u.id')
        ->where('t.id_formateur', '=', $request->id_formateur)
        ->select('t.id', 'p.name', DB::raw("CONCAT(f.prenom, ' ', f.nom) as formateur_name"), 't.qte');
    
    return DataTables::of($Data)
            ->addIndexColumn()
           ->addColumn('action', function ($row) use ($request) {
               
                
                $btn = '';

                // REMOVED: Permission checks - always show edit and delete buttons
                
                // Edit button - always show
                $btn .= '<a href="#" class="btn btn-sm bg-primary-subtle me-1 EditTmp" 
                            data-id="' . $row->id . '">
                            <i class="fa-solid fa-pen-to-square text-primary"></i>
                        </a>';

                // Delete button - always show
                $btn .= '<a href="#" class="btn btn-sm bg-danger-subtle DeleteTmp"
                            data-id="' . $row->id . '" data-bs-toggle="tooltip" 
                            title="Supprimer Vente">
                            <i class="fa-solid fa-trash text-danger"></i>
                        </a>';

                return $btn;
            })
            ->filterColumn('name', function($query, $keyword) {
                $query->where('p.name', 'LIKE', "%{$keyword}%");
            })
            ->filterColumn('formateur_name', function($query, $keyword) {
                $query->whereRaw("LOWER(CONCAT(f.prenom, ' ', f.nom)) LIKE ?", ["%".strtolower($keyword)."%"]);
            })
            ->rawColumns(['action'])
            ->make(true);
}

public function store(Request $request)
{
   
    if (!auth()->user()->can('Commande-ajoute')) {
        return response()->json([
            'status' => 403,
            'message' => 'Vous n\'avez pas la permission d\'ajouter une commande'
        ], 403);
    }
    
    $validator = Validator::make($request->all(), [
        'eleves' => 'sometimes|integer|min:0',
        'personnel' => 'sometimes|integer|min:0',
        'invites' => 'sometimes|integer|min:0',
        'divers' => 'sometimes|integer|min:0',
        'type_commande' => 'required|string|in:Alimentaire,Non Alimentaire,Fournitures et matériels',
        'entree' => 'sometimes|string|max:255|nullable',
        'plat_principal' => 'sometimes|string|max:255|nullable',
        'accompagnement' => 'sometimes|string|max:255|nullable',
        'dessert' => 'sometimes|string|max:255|nullable',
        'date_usage' => 'sometimes|date|nullable',
    ], [
        'integer' => 'Le nombre doit être un nombre entier',
        'min' => 'Le nombre doit être positif',
        'type_commande.required' => 'Le type de commande est requis',
        'type_commande.in' => 'Le type de commande doit être soit Alimentaire, Non Alimentaire, ou Fournitures et matériels',
        'string' => 'Le champ :attribute doit être du texte',
        'max' => 'Le champ :attribute ne peut pas dépasser :max caractères',
        'date' => 'Le champ :attribute doit être une date valide',
    ], [
        'eleves' => 'Le nombre d\'élèves',
        'personnel' => 'Le nombre de personnel',
        'invites' => 'Le nombre d\'invités',
        'divers' => 'Le nombre divers',
        'type_commande' => 'Type de commande',
        'entree' => 'Entrée',
        'plat_principal' => 'Plat principal',
        'accompagnement' => 'Accompagnement',
        'dessert' => 'Dessert',
        'date_usage' => 'Date d\'usage',
    ]);

    if ($validator->fails()) {
        return response()->json([
            'status' => 400,
            'errors' => $validator->messages(),
        ], 400);
    }
  
    
    $userId = Auth::id();
    $formateur = $request->id_formateur;

    // Retrieve temporary sales data
    $TempVente = DB::table('temp_vente as t')
        ->join('products as p', 'p.id', '=', 't.idproduit')
        ->where('t.id_user', $userId)
        ->where('t.id_formateur', $formateur)
        ->select('t.id_formateur', 't.qte', 't.idproduit',
            DB::raw('t.qte * p.price_achat as total_by_product'))
        ->get();

    if ($TempVente->isEmpty()) {
        return response()->json([
            'status'  => 400,
            'message' => 'Aucun article trouvé pour ce demandeur'
        ]);
    }

    // Calculate total sales amount
    $SumVente = $TempVente->sum('total_by_product');
    
    // Helper function to convert empty strings to null
    $convertEmptyToNull = function($value) {
        return empty($value) ? null : $value;
    };
    
    // Set default values based on command type
    $type_menu = null;
    $eleves = 0;
    $personnel = 0;
    $invites = 0;
    $divers = 0;
    $entree = null;
    $plat_principal = null;
    $accompagnement = null;
    $dessert = null;
    $date_usage = $convertEmptyToNull($request->date_usage);
    
    // Only set these values if the command type is Alimentaire
    if ($request->type_commande === 'Alimentaire') {
        $type_menu = $convertEmptyToNull($request->type_menu);
        $eleves = $request->eleves ?? 0;
        $personnel = $request->personnel ?? 0;
        $invites = $request->invites ?? 0;
        $divers = $request->divers ?? 0;
        
        // Convert empty strings to null for menu fields
        $entree = $convertEmptyToNull($request->entree);
        $plat_principal = $convertEmptyToNull($request->plat_principal);
        $accompagnement = $convertEmptyToNull($request->accompagnement);
        $dessert = $convertEmptyToNull($request->dessert);
    }

    // Create new sale with audit trail (id_user will be tracked automatically)
    $Vente = Vente::create([
        'total'     => $SumVente,
        'status'    => "Création",
        'type_commande' => $request->type_commande,
        'type_menu' => $type_menu,
        'id_formateur' => $formateur,
        'id_user'   => $userId,
        'eleves'    => $eleves,
        'personnel' => $personnel,
        'invites'   => $invites,
        'divers'    => $divers,
        'entree'    => $entree,
        'plat_principal' => $plat_principal,
        'accompagnement' => $accompagnement,
        'dessert'   => $dessert,
        'date_usage' => $date_usage,
    ]);

    if (!$Vente) {
        return response()->json([
            'status'  => 500,
            'message' => 'Échec de la création de l\'enregistrement de commande'
        ]);
    }
    $path_signature = Auth::user()->signature;
                Historique_Sig::create([
                        'signature'   => $path_signature,
                        'iduser'      => Auth::user()->id,
                        'idvente'     => $Vente->id,
                        'status'      => 'Création'  
                    ]);


  
    
 
    // Get all admin users
    $adminUserIds = DB::table('model_has_roles')
        ->join('roles', 'model_has_roles.role_id', '=', 'roles.id')
        ->where('roles.name', 'Administrateur')
        ->pluck('model_has_roles.model_id');
    $adminUsers = User::whereIn('id', $adminUserIds)->get();
    
    // Create hashids for secure URL
    $hashids = new Hashids();
    $encodedId = $hashids->encode($Vente->id);
    
    // Get the current user for notification
    $currentUser = User::find(Auth::id());
    $userName = $currentUser->prenom . ' ' . $currentUser->nom;
    
    // Notify each admin
    foreach ($adminUsers as $admin) {
        $admin->notify(new \App\Notifications\SystemNotification([
            'message' => 'Nouvelle commande créée par ' . $userName,
            'status' => 'Création',
            'approve_url' => route('admin.vente.approve', $Vente->id),
            'reject_url' => route('admin.vente.reject', $Vente->id),
            'view_url' => url('ShowBonVente/' . $encodedId)
        ]));
    }

    // Insert sales details individually for audit trail
    foreach ($TempVente as $item) {
        LigneVente::create([
            'id_user'   => $userId,
            'idvente'   => $Vente->id,
            'idproduit' => $item->idproduit,
            'qte'       => $item->qte,
        ]);
    }

    // Delete temporary sales records
    TempVente::where('id_user', $userId)
        ->where('id_formateur', $formateur)
        ->delete();

    return response()->json([
        'status'  => 200,
        'message' => 'Commande ajoutée avec succès'
    ]);
}


    public function UpdateQteTmpVente(Request $request)
{
    // REMOVED: Permission check - no longer checking for Commande-modifier permission
    
    $validator = Validator::make($request->all(), [
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
    
    try {
        // First get the temp vente item to get the product ID
        $tempVenteItem = TempVente::where('id', $request->id)->first();
        
        if (!$tempVenteItem) {
            return response()->json([
                'status' => 404,
                'message' => 'ERROR',
                'details' => 'Item de commande non trouvé.',
                'type' => 'error'
            ], 404);
        }
        
        // Get stock for this product
        $stock = Stock::where('id_product', $tempVenteItem->idproduit)->first();
        
        // Get product name for error message
        $product = DB::table('products')->where('id', $tempVenteItem->idproduit)->first();
        $productName = $product ? $product->name : 'Unknown Product';
        
        // Check if requested quantity is available in stock
        if (!$stock || $stock->quantite < $request->qte) {
            // Log the warning in the same format as ChangeStatusVente
            \Log::warning('Insufficient stock for product: "' . $productName . 
                        '" (Requested: ' . $request->qte . ', Available: ' . 
                        ($stock ? $stock->quantite : 0) . ')');
            
            // Return error in the format that will be displayed to the user
            return response()->json([
                'status' => 400, // Using 400 instead of 500 as it's a validation error
                'message' => 'ERROR',
                'details' => 'Stock insuffisant pour "' . $productName . '". Disponible: ' . 
                           ($stock ? $stock->quantite : 0) . ', Demandé: ' . $request->qte,
                'type' => 'error'
            ]);
        }
        
        // If we have enough stock, proceed with the update
        $TempVente = TempVente::where('id', $request->id)->update([
            'qte' => $request->qte,
        ]);
        
        if($TempVente) {
            return response()->json([
                'status' => 200,
                'message' => 'SUCCESS',
                'details' => 'La quantité a été mise à jour avec succès',
                'type' => 'success'
            ]);
        } else {
            return response()->json([
                'status' => 500,
                'message' => 'ERROR',
                'details' => 'Échec de la mise à jour de la quantité.',
                'type' => 'error'
            ]);
        }
    } catch (\Exception $e) {
        \Log::error('Error in UpdateQteTmpVente: ' . $e->getMessage());
        
        return response()->json([
            'status' => 500,
            'message' => 'ERROR',
            'details' => 'Une erreur s\'est produite lors de la mise à jour: ' . $e->getMessage(),
            'type' => 'error'
        ]);
    }
}

public function DeleteRowsTmpVente(Request $request)
{
    // REMOVED: Permission check - no longer checking for Commande-supprimer permission
    
    $TempVente = TempVente::where('id', $request->id)->delete();
    if($TempVente) {
        return response()->json([
            'status'    => 200,
            'message'   => 'Suppression effectuée avec succès.'
        ]);
    }
}
/**
 * Get status change history from audit logs for a specific vente/command
 */
private function getStatusHistory($venteId)
{
    return DB::table('audits as a')
        ->leftJoin('users as u', 'u.id', '=', 'a.user_id')
        ->select(
            'a.new_values',
            'a.created_at',
            DB::raw("CONCAT(COALESCE(u.prenom, ''), ' ', COALESCE(u.nom, '')) as user_name")
        )
        ->where('a.auditable_type', 'App\\Models\\Vente')
        ->where('a.auditable_id', $venteId)
        ->where('a.event', 'updated')
        ->whereRaw("JSON_EXTRACT(a.new_values, '$.status') IS NOT NULL")
        ->orderBy('a.created_at', 'asc')
        ->get()
        ->map(function($audit) {
            $newValues = json_decode($audit->new_values, true);
            return (object)[
                'status' => $newValues['status'] ?? null,
                'date' => $audit->created_at,
                'user_name' => $audit->user_name ?: 'Système'
            ];
        })
        ->filter(function($item) {
            return !is_null($item->status);
        });
}

    public function deleteOldVentes()
    {
        // Check permission before bulk deleting 
        if (!auth()->user()->can('Commande-supprimer')) {
            return response()->json([
                'status' => 403,
                'message' => 'Vous n\'avez pas la permission de supprimer des commandes'
            ], 403);
        }
        
        try {
            // Find ventes older than 24 hours with status "En cours de traitement"
            $cutoffTime = now()->subHours(24);
            
            $oldVentes = Vente::where('status', 'Création')
                     ->where('created_at', '<', $cutoffTime)
                     ->get();
            
            $count = 0;
            
            foreach ($oldVentes as $vente) {
                // Begin transaction to ensure atomicity
                DB::beginTransaction();
                
                try {
                    // Delete related line items first
                    LigneVente::where('idvente', $vente->id)->delete();
                    
                    // Then delete the vente itself
                    $vente->delete();
                    
                    DB::commit();
                    $count++;
                } catch (\Exception $e) {
                    DB::rollBack();
                    \Log::error("Failed to delete vente ID: {$vente->id}. Error: " . $e->getMessage());
                }
            }
            
            \Log::info("Auto-deleted {$count} ventes that were 24+ hours old with unchanged status.");
            
            return response()->json([
                'status' => 200,
                'message' => "Successfully deleted {$count} old sales orders."
            ]);
        } catch (\Exception $e) {
            \Log::error("Error in deleteOldVentes method: " . $e->getMessage());
            
            return response()->json([
                'status' => 500,
                'message' => "An error occurred while trying to delete old sales orders."
            ]);
        }
    }

    public function GetTotalTmpByFormateurAndUser(Request $request)
    {
        $userId = Auth::id();
        $formateur = $request->id_formateur;

        // Retrieve temporary sales data
        $TempVente = DB::table('temp_vente as t')
            ->join('products as p', 'p.id', '=', 't.idproduit')
            ->where('t.id_user', $userId)
            ->where('t.id_formateur', $formateur)
            ->select('t.id_formateur', 't.qte', 't.idproduit', 'p.price_achat',
                DB::raw('t.qte * p.price_achat as total_by_product'))
            ->get();

        // Calculate total sales amount
        $SumVente = $TempVente->sum('total_by_product');

        return response()->json([
            'status'    => 200,
            'total'     => $SumVente
        ]);
    }


public function ShowBonVente($id)
{
    if (!auth()->user()->can('Commande')) {
        abort(403, 'Vous n\'avez pas la permission de voir ce bon de commande');
    }
    
    $hashids = new Hashids();
    $decoded = $hashids->decode($id);

    if (empty($decoded)) {
        abort(404);
    }

    $id = $decoded[0];

    // Retrieve the BonVente
    $bonVente = Vente::findOrFail($id);
    
    // ✅ CONVERT PLAT IDs TO NAMES
    if ($bonVente->entree) {
        $entreeIds = explode(',', $bonVente->entree);
        $entreeNames = DB::table('plats')->whereIn('id', $entreeIds)->pluck('name')->toArray();
        $bonVente->entree_names = implode(', ', $entreeNames);
    } else {
        $bonVente->entree_names = null;
    }
    
    if ($bonVente->plat_principal) {
        $platIds = explode(',', $bonVente->plat_principal);
        $platNames = DB::table('plats')->whereIn('id', $platIds)->pluck('name')->toArray();
        $bonVente->plat_principal_names = implode(', ', $platNames);
    } else {
        $bonVente->plat_principal_names = null;
    }
    
    if ($bonVente->dessert) {
        $dessertIds = explode(',', $bonVente->dessert);
        $dessertNames = DB::table('plats')->whereIn('id', $dessertIds)->pluck('name')->toArray();
        $bonVente->dessert_names = implode(', ', $dessertNames);
    } else {
        $bonVente->dessert_names = null;
    }
    
    // Rest of the existing code...
    $Formateur = DB::table('users as f')
        ->join('ventes as v', 'v.id_formateur', '=', 'f.id')
        ->select('f.*')
        ->where('v.id', $id)
        ->first();
        
    $Data_Vente = DB::table('ventes as v')
        ->join('ligne_vente as l', 'v.id', '=', 'l.idvente')
        ->join('products as p', 'l.idproduit', '=', 'p.id')
        ->select(
            'p.price_achat', 
            'l.qte', 
            DB::raw('p.price_achat * l.qte as total'), 
            'p.name',
            'l.idproduit',
            'l.contente_transfert'
        )
        ->where('v.id', $id)
        ->get();
    
    $transferDetails = [];
    foreach ($Data_Vente as $item) {
        $transfers = DB::table('line_transfer as lt')
            ->join('stocktransfer as st', 'lt.id_stocktransfer', '=', 'st.id')
            ->join('users as u', 'st.to', '=', 'u.id')
            ->where('lt.idcommande', $id)
            ->where('lt.id_product', $item->idproduit)
            ->where('st.status', 'Validation')
            ->whereNotNull('st.from')
            ->select(
                DB::raw("CONCAT(u.prenom, ' ', u.nom) as recipient_name"),
                'lt.quantite',
                'st.created_at as transfer_date'
            )
            ->get();
            
        if ($transfers->isNotEmpty()) {
            $transferDetails[$item->idproduit] = $transfers;
        }
    }
    
    $returnDetails = [];
    foreach ($Data_Vente as $item) {
        $returns = DB::table('line_transfer as lt')
            ->join('stocktransfer as st', 'lt.id_stocktransfer', '=', 'st.id')
            ->join('users as u', 'st.to', '=', 'u.id')
            ->where('lt.idcommande', $id)
            ->where('lt.id_product', $item->idproduit)
            ->where('st.status', 'Validation')
            ->whereNull('st.from')
            ->select(
                DB::raw("CONCAT(u.prenom, ' ', u.nom) as recipient_name"),
                'lt.quantite',
                'st.created_at as return_date'
            )
            ->get();
            
        if ($returns->isNotEmpty()) {
            $returnDetails[$item->idproduit] = $returns;
        }
    }

    $statusHistory = $this->getStatusHistory($id);
    
    $creatorUser = DB::table('users')
        ->where('id', $bonVente->id_user)
        ->select(DB::raw("CONCAT(prenom, ' ', nom) as name"))
        ->first();
    
    $creationRecord = (object)[
        'status' => 'Création',
        'date' => $bonVente->created_at,
        'user_name' => $creatorUser ? $creatorUser->name : 'Système'
    ];
    
    $statusHistory = collect([$creationRecord])->merge($statusHistory);

    return view('vente.list', compact('bonVente', 'Formateur', 'Data_Vente', 'transferDetails', 'returnDetails', 'statusHistory'));
}

    public function FactureVente($id)
{
    if (!auth()->user()->can('Commande')) {
        abort(403, 'Vous n\'avez pas la permission de voir cette facture');
    }
    
    $hashids = new Hashids();
    $decoded = $hashids->decode($id);

    if (empty($decoded)) {
        abort(404);
    }

    $id = $decoded[0];
    
    $bonVente = Vente::findOrFail($id);
    
    // ✅ CONVERT PLAT IDs TO NAMES (same as ShowBonVente)
    if ($bonVente->entree) {
        $entreeIds = explode(',', $bonVente->entree);
        $entreeNames = DB::table('plats')->whereIn('id', $entreeIds)->pluck('name')->toArray();
        $bonVente->entree_names = implode(', ', $entreeNames);
    } else {
        $bonVente->entree_names = null;
    }
    
    if ($bonVente->plat_principal) {
        $platIds = explode(',', $bonVente->plat_principal);
        $platNames = DB::table('plats')->whereIn('id', $platIds)->pluck('name')->toArray();
        $bonVente->plat_principal_names = implode(', ', $platNames);
    } else {
        $bonVente->plat_principal_names = null;
    }
    
    if ($bonVente->dessert) {
        $dessertIds = explode(',', $bonVente->dessert);
        $dessertNames = DB::table('plats')->whereIn('id', $dessertIds)->pluck('name')->toArray();
        $bonVente->dessert_names = implode(', ', $dessertNames);
    } else {
        $bonVente->dessert_names = null;
    }
    
    // Rest of existing code...
    $Formateur = DB::table('users as f')
        ->join('ventes as v', 'v.id_formateur', '=', 'f.id')
        ->select('f.*')
        ->where('v.id', $id)
        ->first();

    $getHistorique_sig = DB::table('hostorique_sig as h')
        ->join('ventes as v','v.id','h.idvente')
        ->join('users as u','u.id','h.iduser')
        ->where('h.idvente',$id)
        ->select(DB::raw('concat(u.nom," ",u.prenom) as name'), 'h.created_at', 'h.status', 'h.signature')
        ->get()
        ->map(function($item) {
            if (!empty($item->signature) && file_exists(public_path($item->signature))) {
                $item->signature = base64_encode(file_get_contents(public_path($item->signature)));
            }
            return $item;
        });

    $creation = $getHistorique_sig->firstWhere('status', 'Création');
    $validation = $getHistorique_sig->firstWhere('status', 'Validation');
    $livraison = $getHistorique_sig->firstWhere('status', 'Livraison');
    $reception = $getHistorique_sig->firstWhere('status', 'Réception');
    
    $Data_Vente = DB::table('ventes as v')
        ->join('ligne_vente as l', 'v.id', '=', 'l.idvente')
        ->join('products as p', 'l.idproduit', '=', 'p.id')
        ->select(
            'p.price_achat', 
            'l.qte', 
            DB::raw('p.price_achat * l.qte as total'), 
            'p.name', 
            'v.created_at', 
            'v.type_menu', 
            'v.type_commande'
        )
        ->where('v.id', $id)
        ->get();

    $imagePath = public_path('images/logo_top.png');
    $imageData = base64_encode(file_get_contents($imagePath));
    $logo_bottom = public_path('images/logo_bottom.png');
    $imageData_bottom = base64_encode(file_get_contents($logo_bottom));
    
    $html = view('vente.facture', compact(
        'bonVente', 
        'Formateur', 
        'Data_Vente',
        'imageData',
        'imageData_bottom',
        'getHistorique_sig'
    ))->render();

    $pdf = Pdf::loadHTML($html)->output();

    $headers = [
        "Content-type" => "application/pdf",
    ];
    return response()->streamDownload(
        fn() => print($pdf),
        "BonDeCommande.pdf",
        $headers
    );
}


    public function edit(Request $request, $id)
    {
        if (!auth()->user()->can('Commande-modifier')) {
            return response()->json([
                'status' => 403,
                'message' => 'Vous n\'avez pas la permission de modifier une commande'
            ], 403);
        }

        $vente = Vente::find($id);
        
        if (!$vente) {
            return response()->json([
                'status' => 404,
                'message' => 'Vente non trouvée'
            ], 404);
        }

        return response()->json($vente);
    }

public function update(Request $request)
{
    if (!auth()->user()->can('Commande-modifier')) {
        return response()->json([
            'status' => 403,
            'message' => 'Vous n\'avez pas la permission de modifier une commande'
        ], 403);
    }

    $vente = Vente::find($request->id);

    if (!$vente) {
        return response()->json([
            'status' => 404,
            'message' => 'Vente non trouvée'
        ], 404);
    }

    // Prevent modification if status is already validated (except for admins)
    if ($vente->status === 'Validation' && !auth()->user()->hasRole('Administrateur')) {
        return response()->json([
            'status' => 422,
            'message' => 'Impossible de modifier une commande déjà validée'
        ], 422);
    }

    // Updated validator to include the new menu attributes and Visé status
    $validator = Validator::make($request->all(), [
        'status' => 'required|string|in:Création,Validation,Refus,Livraison,Réception,Visé',
        'type_menu' => 'sometimes|string|in:Menu eleves,Menu specials,Menu d\'application',
        'type_commande' => 'sometimes|string|in:Alimentaire,Non Alimentaire,Fournitures et matériels',
        'entree' => 'sometimes|string|max:255|nullable',
        'plat_principal' => 'sometimes|string|max:255|nullable',
        'accompagnement' => 'sometimes|string|max:255|nullable',
        'dessert' => 'sometimes|string|max:255|nullable',
        
    ], [
        'required' => 'Le champ :attribute est requis.',
        'in' => 'Le statut doit être l\'un des suivants: Création, Validation, Refus, Livraison, Réception, Visé',
        'string' => 'Le champ :attribute doit être du texte',
        'max' => 'Le champ :attribute ne peut pas dépasser :max caractères',
    ], [
        'status' => 'statut',
        'type_menu' => 'type de menu',
        'type_commande' => 'type de commande',
        'entree' => 'Entrée',
        'plat_principal' => 'Plat principal',
        'accompagnement' => 'Accompagnement',
        'dessert' => 'Dessert',
    ]);

    if ($validator->fails()) {
        return response()->json([
            'status' => 400,
            'errors' => $validator->messages(),
        ], 400);
    }

    // Store old values for audit comparison
    $oldStatus = $vente->status;

    $vente->status = $request->status;
    
    // Update type_menu if provided
    if ($request->has('type_menu')) {
        $vente->type_menu = $request->type_menu;
    }
    
    // Update menu attributes if provided
    if ($request->has('entree')) {
        $vente->entree = $request->entree;
    }
    
    if ($request->has('plat_principal')) {
        $vente->plat_principal = $request->plat_principal;
    }
    
    if ($request->has('accompagnement')) {
        $vente->accompagnement = $request->accompagnement;
    }
    
    if ($request->has('dessert')) {
        $vente->dessert = $request->dessert;
    }
    
    $vente->save();
    if($request->status == 'Réception')
    {
        $path_signature = Auth::user()->signature;
                Historique_Sig::create([
                        'signature'   => $path_signature,
                        'iduser'      => Auth::user()->id,
                        'idvente'     => $vente->id,
                        'status'      => 'Réception'  
                    ]);
    }
     if($request->status == 'Livraison')
    {
        $path_signature = Auth::user()->signature;
                Historique_Sig::create([
                        'signature'   => $path_signature,
                        'iduser'      => Auth::user()->id,
                        'idvente'     => $vente->id,
                        'status'      => 'Livraison'  
                    ]);
    }


    // Log significant status changes
    if ($oldStatus !== $request->status) {
        \Log::info('Vente status changed from "' . $oldStatus . '" to "' . $request->status . '" for vente ID: ' . $vente->id . ' by user: ' . auth()->user()->id);
    }

    return response()->json([
        'status' => 200,
        'message' => 'Commande mise à jour avec succès',
    ]);
}

    public function deleteVente(Request $request)
    {
        // Check permission before deleting
        if (!auth()->user()->can('Commande-supprimer')) {
            return response()->json([
               'status' => 403,
                'message' => 'Vous n\'avez pas la permission de supprimer une commande'
            ], 403);
        }
        
        $vente = Vente::find($request->id);
        
        if (!$vente) {
            return response()->json([
                'status' => 404,
                'message' => 'Vente non trouvée'
            ], 404);
        }

        // Prevent deletion if status is validated (except for admins)
        if ($vente->status === 'Validation' && !auth()->user()->hasRole('Administrateur')) {
            return response()->json([
                'status' => 422,
                'message' => 'Impossible de supprimer une commande déjà validée'
            ], 422);
        }

        DB::beginTransaction();
        
        try {
            // First delete related records in ligne_vente
            LigneVente::where('idvente', $vente->id)->delete();
            
            // Then delete the vente record (this will trigger audit trail)
            $vente->delete();
            
            DB::commit();
            
            return response()->json([
                'status'    => 200,
                'message'   => 'Commande supprimée avec succès.'
            ]);
        } catch (\Exception $e) {
            DB::rollBack();
            \Log::error('Error deleting vente: ' . $e->getMessage());
            
            return response()->json([
                'status' => 500,
                'message' => 'Une erreur est survenue lors de la suppression'
            ], 500);
        }
    }

    /**
     * Auto delete old ventes
     * @return void
     */
  public function autoDeleteOldVentes()
{
    try {
        // Find ventes older than 48 hours with status "Création"
        $cutoffTime = now()->subHours(48);
        
        $oldVentes = Vente::where('status', 'Création')
                 ->where('created_at', '<', $cutoffTime)
                 ->get();
        
        $count = 0;
        
        foreach ($oldVentes as $vente) {
            // Begin transaction to ensure atomicity
            DB::beginTransaction();
            
            try {
                // Update the vente status to "Refus" instead of deleting (this will be audited)
                $vente->status = 'Refus';
                $vente->save();
                
                DB::commit();
                $count++;
            } catch (\Exception $e) {
                DB::rollBack();
                \Log::error("Failed to update vente ID: {$vente->id} to status Refus. Error: " . $e->getMessage());
            }
        }
        
        if ($count > 0) {
            \Log::info("Auto-updated {$count} ventes that were 48+ hours old to status Refus.");
        }
        
    } catch (\Exception $e) {
        \Log::error("Error in autoDeleteOldVentes method: " . $e->getMessage());
    }
}
    
// public function ChangeStatusVente(Request $request) 
// {
//     if (!auth()->user()->can('Commande-modifier')) {
//         return response()->json([
//             'status' => 403,
//             'message' => 'Vous n\'avez pas la permission de modifier le statut d\'une commande'
//         ], 403);
//     }

//     try {
//         $data = $request->all();
//         \Log::info('ChangeStatusVente called with data:', $data);

//         $vente = Vente::find($data['id']);
        
//         if (!$vente) {
//             return response()->json([
//                 'status' => 404,
//                 'message' => 'Vente non trouvée'
//             ], 404);
//         }

//         $oldStatus = $vente->status;

//         // ✅ Simple status update for Validation (no stock changes)
//         if($data['status'] == 'Validation') {
//             $vente->status = 'Validation';
//             $result = $vente->save();
            
//             \Log::info('Updated vente status to Validation. Result: ' . ($result ? 'success' : 'failed'));
            
//             $creatorUser = User::find($vente->id_user);
//             if ($creatorUser) {
//                 $hashids = new Hashids();
//                 $encodedId = $hashids->encode($vente->id);
                
//                 $creatorUser->notify(new \App\Notifications\SystemNotification([
//                     'message' => 'Votre commande a été approuvée',
//                     'status' => 'Validation',
//                     'view_url' => url('ShowBonVente/' . $encodedId)
//                 ]));
//             }

//             \Log::info('Vente status changed from "' . $oldStatus . '" to "Validation" for vente ID: ' . $vente->id . ' by user: ' . auth()->user()->id);
            
//             return response()->json([
//                 'status' => 200,
//                 'message' => 'Opération réussie'
//             ]);
//         }

//         else if($data['status'] == 'Refus') {
//             $vente->status = 'Refus';
//             $result = $vente->save();
            
//             \Log::info('Updated vente status to Refus. Result: ' . ($result ? 'success' : 'failed'));
            
//             $creatorUser = User::find($vente->id_user);
//             if ($creatorUser) {
//                 $hashids = new Hashids();
//                 $encodedId = $hashids->encode($vente->id);
                
//                 $creatorUser->notify(new \App\Notifications\SystemNotification([
//                     'message' => 'Votre commande a été refusée',
//                     'status' => 'Refus',
//                     'view_url' => url('ShowBonVente/' . $encodedId)
//                 ]));
//             }

//             \Log::info('Vente status changed from "' . $oldStatus . '" to "Refus" for vente ID: ' . $vente->id . ' by user: ' . auth()->user()->id);
            
//             return response()->json([
//                 'status' => 200,
//                 'message' => 'Opération réussie'
//             ]);
//         }

//         else if($data['status'] == 'Livraison') {
//             $vente->status = 'Livraison';
//             $result = $vente->save();
            
//             \Log::info('Updated vente status to Livraison. Result: ' . ($result ? 'success' : 'failed'));
//             \Log::info('Vente status changed from "' . $oldStatus . '" to "Livraison" for vente ID: ' . $vente->id . ' by user: ' . auth()->user()->id);
            
//             return response()->json([
//                 'status' => 200,
//                 'message' => 'Opération réussie'
//             ]);
//         }

//         else if($data['status'] == 'Visé') {
//             $vente->status = 'Visé';
//             $result = $vente->save();
            
//             \Log::info('Updated vente status to Visé. Result: ' . ($result ? 'success' : 'failed'));
            
//             $creatorUser = User::find($vente->id_user);
//             if ($creatorUser) {
//                 $hashids = new Hashids();
//                 $encodedId = $hashids->encode($vente->id);
                
//                 $creatorUser->notify(new \App\Notifications\SystemNotification([
//                     'message' => 'Votre commande a été visée par l\'économe',
//                     'status' => 'Visé',
//                     'view_url' => url('ShowBonVente/' . $encodedId)
//                 ]));
//             }

//             \Log::info('Vente status changed from "' . $oldStatus . '" to "Visé" for vente ID: ' . $vente->id . ' by user: ' . auth()->user()->id);
            
//             return response()->json([
//                 'status' => 200,
//                 'message' => 'Opération réussie'
//             ]);
//         }

//         // ✅ Stock reduction logic AND formateur stock assignment for Réception
//         else if($data['status'] == 'Réception') {
//             DB::beginTransaction();
            
//             try {
//                 $vente->status = 'Réception';
//                 $vente->save();
                
//                 $data_ligne_product = LigneVente::where('idvente', $data['id'])->get();

//                 foreach($data_ligne_product as $value) {
//                     $product = DB::table('products')->where('id', $value->idproduit)->first();
//                     $productName = $product ? $product->name : 'Unknown Product';

//                     // ✅ THIS IS THE MISSING PIECE - Assign to formateur stock
//                     $value->contete_formateur = (string)$value->qte;
//                     $value->save();

//                     $stock = Stock::where('id_product', $value->idproduit)->first();

//                     if($stock) {
//                         if($stock->quantite >= $value->qte) {
//                             $stock->quantite -= $value->qte;
//                             $stock->save();

//                             if($stock->quantite <= $product->seuil) {
//                                 $adminUsers = User::whereHas('roles', function($query) {
//                                     $query->where('name', 'Administrateur');
//                                 })->get();

//                                 foreach ($adminUsers as $admin) {
//                                     $admin->notify(new SystemNotification([
//                                         'message' => "Stock faible: {$productName} - Quantité: {$stock->quantite}, Seuil: {$product->seuil}",
//                                         'status' => 'Stock Bas',
//                                         'view_url' => url('stock')
//                                     ]));
//                                 }
//                             }

//                         } else {
//                             throw new \Exception('Stock insuffisant pour le produit: "' . $productName . '"');
//                         }
//                     } else {
//                         throw new \Exception('Aucun stock trouvé pour le produit: "' . $productName . '"');
//                     }
//                 }

//                 $this->inventoryService->updateInventoryForSale($vente);
//                 DB::commit();

//                 $creatorUser = User::find($vente->id_user);
//                 if ($creatorUser) {
//                     $hashids = new Hashids();
//                     $encodedId = $hashids->encode($vente->id);

//                     $creatorUser->notify(new \App\Notifications\SystemNotification([
//                         'message' => 'Votre commande a été reçue et le stock a été mis à jour',
//                         'status' => 'Réception',
//                         'view_url' => url('ShowBonVente/' . $encodedId)
//                     ]));
//                 }

//                 \Log::info('Vente status changed from "' . $oldStatus . '" to "Réception" for vente ID: ' . $vente->id . ' by user: ' . auth()->user()->id);

//                 return response()->json([
//                     'status' => 200,
//                     'message' => 'Opération réussie'
//                 ]);

//             } catch (\Exception $e) {
//                 DB::rollBack();
//                 \Log::error('Error in reception process: ' . $e->getMessage());

//                 return response()->json([
//                     'status' => 500,
//                     'message' => 'Une erreur est survenue lors de la réception: ' . $e->getMessage(),
//                     'error' => $e->getMessage()
//                 ]);
//             }
//         }

//         // If no match
//         else {
//             return response()->json([
//                 'status' => 400,
//                 'message' => 'Statut inconnu'
//             ]);
//         }

//     } catch (\Exception $e) {
//         \Log::error('Error in ChangeStatusVente: ' . $e->getMessage());

//         return response()->json([
//             'status' => 500,
//             'message' => 'Une erreur est survenue lors du changement de statut: ' . $e->getMessage(),
//             'error' => $e->getMessage()
//         ]);
//     }
// }
 public function ChangeStatusVente(Request $request)
    {
        if (!auth()->user()->can('Commande-modifier')) {
            return response()->json([
                'status' => 403,
                'message' => 'Vous n\'avez pas la permission de modifier le statut d\'une commande'
            ], 403);
        }

        try {
            $data = $request->all();
            \Log::info('ChangeStatusVente called with data:', $data);

            // Retrieve the vente record
            $vente = Vente::find($data['id']);
            
            if (!$vente) {
                return response()->json([
                    'status' => 404,
                    'message' => 'Vente non trouvée'
                ], 404);
            }

            // Store old status for audit logging
            $oldStatus = $vente->status;

            if($data['status'] == 'Validation')
            {
                // Begin transaction
                DB::beginTransaction();
                
                try {
                    // First, update the vente status (this will trigger audit trail)
                    $vente->status = 'Validation';
                    $vente->save();
                    $path_signature= Auth::user()->signature;
                    Historique_Sig::create([
                        'signature'   => $path_signature,
                        'iduser'      => Auth::user()->id,
                        'idvente'     => $vente->id,
                        'status'      => 'Validation'
                    ]);
                    
                    // Extract product from ligne vente 
                    $data_ligne_product = LigneVente::where('idvente', $data['id'])->get();
                    \Log::info('Found ' . $data_ligne_product->count() . ' line items for vente ID: ' . $data['id']);

                    foreach($data_ligne_product as $value)
                    {
                        // Get product name
                        $product = DB::table('products')->where('id', $value->idproduit)->first();
                        $productName = $product ? $product->name : 'Unknown Product';
                        
                        // Save the current qte value to contete_formateur (this will trigger audit trail)
                        $value->contete_formateur = (string)$value->qte;
                        $value->save();
                        
                        \Log::info('Processing product: "' . $productName . '" with quantity: ' . $value->qte);
                        
                        // Get stock for this product
                        $stock = Stock::where('id_product', $value->idproduit)->first();
                        
                        if($stock) {
                            \Log::info('Current stock quantity for "' . $productName . '": ' . $stock->quantite);
                            
                            if($stock->quantite >= $value->qte) {
                                // Subtract quantity from stock
                                $stock->quantite -= $value->qte;
                                $stock->save();
                                \Log::info('Updated stock quantity for "' . $productName . '": ' . $stock->quantite);
                                
                                // Check if this product is now low stock after validation
                                if($stock->quantite <= $product->seuil) {
                                    // Get administrators
                                    $adminUsers = User::whereHas('roles', function($query) {
                                        $query->where('name', 'Administrateur');
                                    })->get();
                                    
                                    foreach ($adminUsers as $admin) {
                                        $admin->notify(new SystemNotification([
                                            'message' => "Stock faible: {$productName} - Quantité: {$stock->quantite}, Seuil: {$product->seuil}",
                                            'status' => 'Stock Bas',
                                            'view_url' => url('stock')
                                        ]));
                                    }
                                }
                                
                            } else {
                                \Log::warning('Insufficient stock for product: "' . $productName . 
                                            '" (Requested: ' . $value->qte . ', Available: ' . $stock->quantite . ')');
                                
                                throw new \Exception('Stock insuffisant pour le produit: "' . $productName . '"');
                            }
                        } else {
                            \Log::warning('No stock found for product: "' . $productName . '"');
                            throw new \Exception('Aucun stock trouvé pour le produit: "' . $productName . '"');
                        }
                    }
                    
                    // Update inventory using the service - this only records the movement, doesn't modify stock
                    $this->inventoryService->updateInventoryForSale($vente);
                    \Log::info('Updated inventory for sale ID: ' . $vente->id);
                    
                    DB::commit();
                    
                    // Notify the user who created this sale
                    $creatorUser = User::find($vente->id_user);
                    if ($creatorUser) {
                        // Create hashids for secure URL
                        $hashids = new Hashids();
                        $encodedId = $hashids->encode($vente->id);
                        
                        $creatorUser->notify(new \App\Notifications\SystemNotification([
                            'message' => 'Votre commande a été approuvée',
                            'status' => 'Validation',
                            'view_url' => url('ShowBonVente/' . $encodedId)
                        ]));
                    }

                    // Log the status change
                    \Log::info('Vente status changed from "' . $oldStatus . '" to "Validation" for vente ID: ' . $vente->id . ' by user: ' . auth()->user()->id);
                    
                    return response()->json([
                        'status' => 200,
                        'message' => 'Opération réussie'
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
                $vente->status = 'Refus';
                $result = $vente->save();
                
                \Log::info('Updated vente status to Refus. Result: ' . ($result ? 'success' : 'failed'));
                
                // Notify the user who created this sale
                $creatorUser = User::find($vente->id_user);
                if ($creatorUser) {
                    // Create hashids for secure URL
                    $hashids = new Hashids();
                    $encodedId = $hashids->encode($vente->id);
                    
                    $creatorUser->notify(new \App\Notifications\SystemNotification([
                        'message' => 'Votre commande a été refusée',
                        'status' => 'Refus',
                        'view_url' => url('ShowBonVente/' . $encodedId)
                    ]));
                }

                // Log the status change
                \Log::info('Vente status changed from "' . $oldStatus . '" to "Refus" for vente ID: ' . $vente->id . ' by user: ' . auth()->user()->id);
                
                return response()->json([
                    'status' => 200,
                    'message' => 'Opération réussie'
                ]);
            }
            else if($data['status'] == 'Livraison')
            {
                $vente->status = 'Livraison';
                $result = $vente->save();
                
                \Log::info('Updated vente status to Livraison. Result: ' . ($result ? 'success' : 'failed'));
                \Log::info('Vente status changed from "' . $oldStatus . '" to "Livraison" for vente ID: ' . $vente->id . ' by user: ' . auth()->user()->id);
                
                return response()->json([
                    'status' => 200,
                    'message' => 'Opération réussie'
                ]);
            }
            else if($data['status'] == 'Visé')
{
    $vente->status = 'Visé';
    $result = $vente->save();
    
    \Log::info('Updated vente status to Visé. Result: ' . ($result ? 'success' : 'failed'));
    
    // Notify the user who created this sale
    $creatorUser = User::find($vente->id_user);
    if ($creatorUser) {
        // Create hashids for secure URL
        $hashids = new Hashids();
        $encodedId = $hashids->encode($vente->id);
        
        $creatorUser->notify(new \App\Notifications\SystemNotification([
            'message' => 'Votre commande a été visée par l\'économe',
            'status' => 'Visé',
            'view_url' => url('ShowBonVente/' . $encodedId)
        ]));
    }

    // Log the status change
    \Log::info('Vente status changed from "' . $oldStatus . '" to "Visé" for vente ID: ' . $vente->id . ' by user: ' . auth()->user()->id);
    
    return response()->json([
        'status' => 200,
        'message' => 'Opération réussie'
    ]);
}

            else
            {
                $vente->status = 'Réception';
                $result = $vente->save();
                
                \Log::info('Updated vente status to Réception. Result: ' . ($result ? 'success' : 'failed'));
                \Log::info('Vente status changed from "' . $oldStatus . '" to "Réception" for vente ID: ' . $vente->id . ' by user: ' . auth()->user()->id);
                
                return response()->json([
                    'status' => 200,
                    'message' => 'Opération réussie'
                ]);
            }
        } catch (\Exception $e) {
            \Log::error('Error in ChangeStatusVente: ' . $e->getMessage());
            \Log::error($e->getTraceAsString());
            
            return response()->json([
                'status' => 500,
                'message' => 'Une erreur est survenue lors du changement de statut: ' . $e->getMessage(),
                'error' => $e->getMessage()
            ]);
        }
    }
/**
 * Get categories by class for filter
 */
public function getCategoriesByClass(Request $request)
{
    try {
        $class = $request->get('class');
        
        if (!$class) {
            return response()->json([
                'status' => 400,
                'message' => 'Classe non spécifiée',
                'data' => []
            ], 400);
        }
        
        $categories = DB::table('categories')
            ->where('classe', $class)
            ->whereNull('deleted_at')
            ->select('id', 'name')
            ->orderBy('name', 'asc')
            ->get();
        
        return response()->json([
            'status' => 200,
            'data' => $categories
        ]);
    } catch (\Exception $e) {
        \Log::error('Error fetching categories by class', [
            'error' => $e->getMessage(),
            'class' => $request->get('class')
        ]);
        
        return response()->json([
            'status' => 500,
            'message' => 'Erreur lors de la récupération des catégories',
            'data' => []
        ], 500);
    }
}

/**
 * Get subcategories by category for filter
 */
public function getSubcategories($categoryId)
{
    try {
        $subcategories = DB::table('sub_categories')
            ->where('id_categorie', $categoryId)
            ->whereNull('deleted_at')
            ->select('id', 'name')
            ->orderBy('name', 'asc')
            ->get();


        
        
        return response()->json([
            'status' => 200,
            'subcategories' => $subcategories
        ]);

    } catch (\Exception $e) {
        \Log::error('Error fetching subcategories', [
            'error' => $e->getMessage(),
            'category_id' => $categoryId
        ]);
        
        return response()->json([
            'status' => 500,
            'message' => 'Erreur lors de la récupération des familles',
            'subcategories' => []
        ], 500);
    }
}

public function getcategorybytypemenu(Request $request)
{
    
    $classe = "";
    if($request->type_commande == "Non Alimentaire")
    {
        $classe = "NON ALIMENTAIRE";
    }
    else
    {
        $classe = "DENREES ALIMENTAIRES";
    }
    $data   = Category::where('classe',$classe)->get();
    
    return response()->json([
        'status'  => 200,
        'data'    => $data,
    ]);
}


   public function sendPlatToTmpVente(Request $request)
    {
        $idsPlat = $request->idplat;
            
        if($request->idremove == null)
        {
            
            $content = [];

            

            $lignePlats = DB::table('ligne_plat as l')
                ->join('products as p', 'p.id', '=', 'l.idproduit')
                ->select(
                    'l.qte',
                    'l.idproduit',
                    DB::raw(Auth::id() . ' as id_user'),
                    DB::raw(Auth::id() . ' as id_formateur',
                ),'l.id'
                )
                ->where('id_plat', $idsPlat)
                ->get(); // get() returns all rows

            if($lignePlats->count() > 0) {
                // merge all rows into $content
                $content = array_merge($content, $lignePlats->toArray());
            }
        
            foreach($content as $item)
            {
                $TempVente = TempVente::create([
                    'id_user'          => $item->id_user,
                    'idproduit'        => $item->idproduit,
                    'id_client'        => null,
                    'id_formateur'     => $item->id_formateur,
                    'qte'             => $item->qte * $request->qte,
                    'idplat'          => $idsPlat
                ]);
            }
        }
        else
        {
        
        $TempVente = TempVente::where('idplat',$request->idremove)->delete();
        }
        

        return response()->json([
            'status' => 200,
            
        ]);
    }


/**
 * Search product names for autocomplete in filters
 */
// public function searchProductNames(Request $request)
// {
//     try {
//         $query = $request->get('query', '');
        
//         if (strlen($query) < 2) {
//             return response()->json([
//                 'status' => 200,
//                 'products' => []
//             ]);
//         }
        
//         $products = DB::table('products')
//             ->where('name', 'LIKE', '%' . $query . '%')
//             ->whereNull('deleted_at')
//             ->select('id', 'name')
//             ->limit(10)
//             ->get();
        
//         return response()->json([
//             'status' => 200,
//             'products' => $products
//         ]);
        
//     } catch (\Exception $e) {
//         \Log::error('Error searching product names', [
//             'error' => $e->getMessage()
//         ]);
        
//         return response()->json([
//             'status' => 500,
//             'message' => 'Erreur lors de la recherche',
//             'products' => []
//         ], 500);
//     }
// }

}