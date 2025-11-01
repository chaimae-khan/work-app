<?php

namespace App\Http\Controllers;
use App\Services\InventoryService;
use Illuminate\Http\Request;
use App\Models\Fournisseur;
use App\Models\Category;
use App\Models\Local;
use App\Models\SubCategory;
use App\Models\Rayon;
use App\Models\Tva;
use App\Models\Unite;
use Illuminate\Support\Facades\DB;
use Yajra\DataTables\Facades\DataTables;
use Illuminate\Support\Facades\Auth;
use App\Models\TempAchat;
use App\Models\Achat;
use App\Models\LigneAchat;
use Illuminate\Support\Facades\Validator;
use Hashids\Hashids;
use Barryvdh\DomPDF\Facade\Pdf;
use App\Models\Product;
use App\Models\Stock;
use App\Models\User;
use App\Notifications\SystemNotification;

class AchatController extends Controller
{
    protected $inventoryService;

    public function __construct(InventoryService $inventoryService)
    {
        $this->inventoryService = $inventoryService;
    }

    public function index(Request $request)
{
    $countFournisseur = Fournisseur::count();
    if($countFournisseur == 0)
    {
        return view('Error.index')
        ->withErrors('tu n\'as pas de fournisseur ');
    }
    $countProduct = Product::count();
    if($countProduct == 0)
    {
        return view('Error.index')
        ->withErrors('tu n\'as pas de produits ');   
    }
    
    $this->autoDeleteOldAchats();
    
    if($request->ajax())
    {
        $hashids = new Hashids();
        $Data_Achat = DB::table('achats as a')
        ->join('fournisseurs as f','f.id','=','a.id_Fournisseur')
        ->join('users as us','us.id','=','a.id_user')
        ->select('a.total','a.status','f.entreprise',DB::raw("CONCAT(us.prenom, ' ', us.nom) as name"),'a.created_at','a.id')
        ->whereNull('a.deleted_at')
        ->orderBy('a.id', 'desc')
        ->get();
        return DataTables::of($Data_Achat)
                ->addIndexColumn()
                ->addColumn('action', function ($row) use ($hashids) {
                    $btn = '';
                    $isAdmin = auth()->user()->hasRole('Administrateur');

                    // If status is "Refus", only show action icons if user is Admin
                    if ($row->status === 'Refus' && !$isAdmin) {
                        return '';
                    }

                    // Edit button - don't show if status is "Validation"
                    if (auth()->user()->can('Achat-modifier') && $row->status !== 'Validation') {
                        $btn .= '<a href="#" class="btn btn-sm bg-primary-subtle me-1" 
                                    data-id="' . $row->id . '">
                                    <i class="fa-solid fa-pen-to-square text-primary"></i>
                                </a>';
                    }

                    // Detail button (hashed ID) - View permission - show for all statuses
                    if (auth()->user()->can('Achat')) {
                        $btn .= '<a href="' . url('ShowBonReception/' . $hashids->encode($row->id)) . '" 
                                    class="btn btn-sm bg-success-subtle me-1" 
                                    data-id="' . $row->id . '" 
                                    target="_blank">
                                    <i class="fa-solid fa-eye text-success"></i>
                                </a>';
                    }

                    // Print button code has been removed

                    // Delete button - don't show if status is "Validation"
                    if (auth()->user()->can('Achat-supprimer') && $row->status !== 'Validation') {
                        $btn .= '<a href="#" class="btn btn-sm bg-danger-subtle DeleteAchat" 
                                    data-id="' . $row->id . '" data-bs-toggle="tooltip" 
                                    title="Supprimer Achat">
                                    <i class="fa-solid fa-trash text-danger"></i>
                                </a>';
                    }

                    return $btn;
                })
                ->rawColumns(['action'])
                ->make(true);
    }
    $Fournisseur  = Fournisseur::all();
    $categories = Category::all();
    $subcategories = SubCategory::all();
    $locals = Local::all();
    $rayons = Rayon::all();
    $tvas = Tva::all();
    $unites = Unite::all();
    $class = DB::select("select distinct(classe) as classe from categories");
    
    return view('achat.index')
        ->with('Fournisseur',$Fournisseur)
        ->with('categories',$categories)
        ->with('subcategories',$subcategories)
        ->with('locals',$locals)
        ->with('rayons',$rayons)
        ->with('tvas',$tvas)
        ->with('unites',$unites)
        ->with('class',$class); 
}

    /**
     * Automatically check and mark old achats as "Refus" instead of deleting them
     * This function is called from other controller methods to run the check automatically
     * 
     * @return void
     */
    private function autoDeleteOldAchats()
    {
        try {
            // Find achats older than 24 hours with status "Création"
            $cutoffTime = now()->subHours(24);
            
            $oldAchats = Achat::where('status', 'Création')
                    ->where('created_at', '<', $cutoffTime)
                    ->get();
            
            $count = 0;
            
            foreach ($oldAchats as $achat) {
                // Begin transaction to ensure atomicity
                DB::beginTransaction();
                
                try {
                    // Update the achat status to "Refus" instead of deleting (this will trigger audit trail)
                    $achat->status = 'Refus';
                    $achat->save();
                    
                    DB::commit();
                    $count++;
                } catch (\Exception $e) {
                    DB::rollBack();
                    \Log::error("Failed to update achat ID: {$achat->id} to status Refus. Error: " . $e->getMessage());
                }
            }
            
            if ($count > 0) {
                \Log::info("Auto-updated {$count} achats that were 24+ hours old to status Refus with audit trail.");
            }
            
        } catch (\Exception $e) {
            \Log::error("Error in autoDeleteOldAchats method: " . $e->getMessage());
        }
    }

    public function getProduct(Request $request)
    {
        $name_product = $request->product;
        $category = $request->category;
        $filter_subcategorie = $request->filter_subcategorie;
        $type_command = $request->type_commande;

        $classe = $type_command == "Non Alimentaire" ? "NON ALIMENTAIRE" : "DENREES ALIMENTAIRES";

        if ($request->ajax()) 
        {
            // Get category IDs that belong to the selected classe
            $get_id_category = Category::where('classe', $classe)->pluck('id')->toArray();

            $Data_Product = DB::table('products as p')
                ->join('stock as s', 'p.id', '=', 's.id_product')
                ->join('locals as l', 'p.id_local', '=', 'l.id')
                ->join('categories as c', 'c.id', '=', 'p.id_categorie')
                ->whereNull('p.deleted_at')
                ->select(
                    'p.name',
                    's.quantite',
                    'p.seuil',
                    'p.price_achat',
                    'l.name as name_local',
                    'p.id',
                    'p.price_vente'
                );

            $Data_Product->when($name_product, function ($q, $name_product) {
                return $q->where('p.name', 'like',  $name_product . '%');
            });

            $Data_Product->when($category, function ($q, $category) {
                return $q->where('p.id_categorie', $category);
            });

            $Data_Product->when($filter_subcategorie, function ($q, $filter_subcategorie) {
                return $q->where('p.id_subcategorie', $filter_subcategorie);
            });

            // Apply class-based category filter
            $Data_Product->when($get_id_category, function ($q) use ($get_id_category) {
                return $q->whereIn('p.id_categorie', $get_id_category);
            });

            $results = $Data_Product->get();

            return response()->json(
                [
                        'status' => 200,
                        'data'   => $results
                    ]);
        }

           // dd($request);

            
        
    }

    public function PostInTmpAchat(Request $request)
    {
        // Check permission before posting to temp achat
        if (!auth()->user()->can('Achat-ajoute')) {
            return response()->json([
                'status' => 403,
                'message' => 'Vous n\'avez pas la permission d\'ajouter un achat'
            ], 403);
        }
        
        $data = $request->all();
        $data['id_user'] = Auth::user()->id;
        $data['qte'] = 1;
        
        DB::beginTransaction();
    
        try {
            $existingProduct = TempAchat::where('idproduit', $data['idproduit'])
                ->where('id_fournisseur', $data['id_fournisseur'])
                ->where('id_user', $data['id_user'])
                ->first();
    
            if ($existingProduct) {
                $existingProduct->increment('qte', 1);
                DB::commit();
    
                return response()->json([
                    'status' => 200,
                    'message' => 'Quantité mise à jour avec succès',
                ]);
            } else {
                TempAchat::create($data);
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

public function GetTmpAchatByFournisseur(Request $request)
{
    $Data = DB::table('temp_achat as t')
    ->join('fournisseurs as f', 't.id_fournisseur', '=', 'f.id')
    ->join('products as p', 't.idproduit', '=', 'p.id')
    ->join('users as us', 't.id_user', '=', 'us.id')
    ->where('t.id_fournisseur', '=', $request->id_fournisseur)
    ->whereNull('p.deleted_at')
    ->select('t.id', 'p.name', 'p.price_achat', 'f.entreprise', 't.qte');
    
    return DataTables::of($Data)
            ->addIndexColumn()
            ->addColumn('action', function ($row) {
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
                            title="Supprimer Catégorie">
                            <i class="fa-solid fa-trash text-danger"></i>
                        </a>';

                return $btn;
            })
            ->rawColumns(['action'])
            ->make(true);
}

    public function Store(Request $request)
    {
        // Check permission before storing
        if (!auth()->user()->can('Achat-ajoute')) {
            return response()->json([
                'status' => 403,
                'message' => 'Vous n\'avez pas la permission d\'ajouter un achat'
            ], 403);
        }
        
        $userId = Auth::id();
        $fournisseur = $request->id_fournisseur;

        // Retrieve temporary purchase data
        $TempAchat = DB::table('temp_achat as t')
            ->join('products as p', 'p.id', '=', 't.idproduit')
            ->where('t.id_user', $userId)
            ->where('t.id_fournisseur', $fournisseur)
            ->whereNull('p.deleted_at')
            ->select('t.id_fournisseur', 't.qte', 't.idproduit', 'p.price_achat', 
                DB::raw('t.qte * p.price_achat as total_by_product'))
            ->get();

        if ($TempAchat->isEmpty()) {
            return response()->json([
                'status'  => 400,
                'message' => 'Aucun article trouvé pour ce fournisseur'
            ]);
        }

        // Calculate total purchase amount
        $SumAchat = $TempAchat->sum('total_by_product');

        // Create new purchase with audit trail (id_user will be tracked automatically)
        $Achat = Achat::create([
            'total'         => $SumAchat,
            'status'        => "Création", 
            'id_Fournisseur'=> $fournisseur,
            'id_user'       => $userId,
        ]);

        if (!$Achat) {
            return response()->json([
                'status'  => 500,
                'message' => 'Échec de la création de l\'enregistrement d\'achat'
            ]);
        }
        
        // Add notification code here
        // Get all admin users
        $adminUsers = User::whereHas('roles', function($query) {
            $query->where('name', 'Administrateur');
        })->get();
        
        // Create hashids for secure URL
        $hashids = new Hashids();
        $encodedId = $hashids->encode($Achat->id);
        
        // Get the current user for notification
        $currentUser = User::find(Auth::id());
        $userName = $currentUser->prenom . ' ' . $currentUser->nom;
        
        // Notify each admin
        foreach ($adminUsers as $admin) {
            $admin->notify(new \App\Notifications\SystemNotification([
                'message' => 'Nouvel achat créé par ' . $userName,
                'status' => 'Création',
                'approve_url' => route('admin.achat.approve', $Achat->id),
                'reject_url' => route('admin.achat.reject', $Achat->id),
                'view_url' => url('ShowBonReception/' . $encodedId)
            ]));
        }

        // Insert purchase details individually for audit trail
        foreach ($TempAchat as $item) {
            LigneAchat::create([
                'id_user'   => $userId,
                'idachat'   => $Achat->id,
                'idproduit' => $item->idproduit,
                'qte'       => $item->qte,
            ]);
        }

        // Delete temporary purchase records
        TempAchat::where('id_user', $userId)
            ->where('id_fournisseur', $fournisseur)
            ->delete();

        return response()->json([
            'status'  => 200,
            'message' => 'Achat ajouté avec succès'
        ]);
    }

  public function UpdateQteTmp(Request $request)
{
    // REMOVED: Permission check - no longer checking for Achat-modifier permission
    
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
    
    $TempAchat = TempAchat::where('id',$request->id)->update([
        'qte'   => $request->qte,
    ]);
    
    if($TempAchat)
    {
        return response()->json([
            'status'    => 200,
            'message'   => 'Mise à jour effectuée avec succès.'
        ]);
    }
}

   public function DeleteRowsTmpAchat(Request $request)
{
    
    $TempAchat = TempAchat::where('id',$request->id)->delete();
     
    if($TempAchat)
    {
        return response()->json([
            'status'    => 200,
            'message'   => 'Suppression effectuée avec succès.'
        ]);
    }
}

    public function DeleteAchat(Request $request)
    {
        // Check permission before deleting
        if (!auth()->user()->can('Achat-supprimer')) {
            return response()->json([
                'status' => 403,
                'message' => 'Vous n\'avez pas la permission de supprimer un achat'
            ], 403);
        }
        
        $achat = Achat::find($request->id);
        
        if (!$achat) {
            return response()->json([
                'status' => 404,
                'message' => 'Achat non trouvé'
            ], 404);
        }

        // Prevent deletion if status is validated (except for admins)
        if ($achat->status === 'Validation' && !auth()->user()->hasRole('Administrateur')) {
            return response()->json([
                'status' => 422,
                'message' => 'Impossible de supprimer un achat déjà validé'
            ], 422);
        }

        DB::beginTransaction();
        
        try {
            // First delete related records in ligne_achat
            LigneAchat::where('idachat', $achat->id)->delete();
            
            // Then delete the achat record (this will trigger audit trail)
            $achat->delete();
            
            DB::commit();
            
            return response()->json([
                'status'    => 200,
                'message'   => 'Achat supprimé avec succès.'
            ]);
        } catch (\Exception $e) {
            DB::rollBack();
            \Log::error('Error deleting achat: ' . $e->getMessage());
            
            return response()->json([
                'status' => 500,
                'message' => 'Une erreur est survenue lors de la suppression'
            ], 500);
        }
    }

    public function GetTotalTmpByForunisseurAndUser(Request $request)
    {
        $userId = Auth::id();
        $fournisseur = $request->id_fournisseur;

        // Retrieve temporary purchase data
        $TempAchat = DB::table('temp_achat as t')
            ->join('products as p', 'p.id', '=', 't.idproduit')
            ->where('t.id_user', $userId)
            ->where('t.id_fournisseur', $fournisseur)
            ->whereNull('p.deleted_at')
            ->select('t.id_fournisseur', 't.qte', 't.idproduit', 'p.price_achat', 
                DB::raw('t.qte * p.price_achat as total_by_product'))
            ->get();

        // Calculate total purchase amount
        $SumAchat = $TempAchat->sum('total_by_product');

        return response()->json([
            'status'    => 200,
            'total'     => $SumAchat
        ]);
    }

    public function ShowBonReception($id)
    {
        // Check permission for viewing 
        if (!auth()->user()->can('Achat')) {
            abort(403, 'Vous n\'avez pas la permission de voir ce bon de réception');
        }
        
        $hashids = new Hashids();
        $decoded = $hashids->decode($id);
    
        if (empty($decoded)) {
            abort(404); // Handle invalid hash
        }
    
        $id = $decoded[0]; // Extract the original ID
    
        // Now, use $id to retrieve the BonReception
        $bonReception = Achat::findOrFail($id);
        $Fournisseur  = DB::table('fournisseurs as f')
        ->join('achats as a','a.id_Fournisseur','=','f.id')
        ->select('f.*')
        ->where('a.id',$id)
        ->first();
        $Data_Achat = $data = DB::table('achats as a')
        ->join('ligne_achat as l', 'a.id', '=', 'l.idachat')
        ->join('products as p', 'l.idproduit', '=', 'p.id')
        ->whereNull('p.deleted_at')
        ->select('p.price_achat', 'l.qte', DB::raw('p.price_achat * l.qte as total'), 'p.name')
        ->where('a.id', $id)
        ->get();
    
        return view('achat.list', compact('bonReception','Fournisseur','Data_Achat'));
    }

    public function Invoice($id)
    {
        // Check permission for viewing invoice
        if (!auth()->user()->can('Achat')) {
            abort(403, 'Vous n\'avez pas la permission de voir cette facture');
        }
        
        $hashids = new Hashids();
        $decoded = $hashids->decode($id);
    
        if (empty($decoded)) {
            abort(404); // Handle invalid hash
        }
    
        $id = $decoded[0]; // Extract the original ID
        $Data_Achat = $data = DB::table('achats as a')
        ->join('ligne_achat as l', 'a.id', '=', 'l.idachat')
        ->join('products as p', 'l.idproduit', '=', 'p.id')
        ->whereNull('p.deleted_at')
        ->select('p.price_achat', 'l.qte', DB::raw('p.price_achat * l.qte as total'), 'p.name','a.created_at')
        ->where('a.id', $id)
        ->get();
    
        $imagePath = public_path('images/logo_top.png');
        $imageData = base64_encode(file_get_contents($imagePath));
        $logo_bottom = public_path('images/logo_bottom.png');
        $imageData_bottom = base64_encode(file_get_contents($logo_bottom));
        $context = stream_context_create([
            'ssl'  => [
                'verify_peer'  => FALSE,
                'verify_peer_name' => FALSE,
                'allow_self_signed' => TRUE,
            ]
        ]);
        $html = view('achat.facture', [
            'Data_Achat' => $Data_Achat,
            'imageData' => $imageData,
            'imageData_bottom' => $imageData_bottom,
        ])->render();
    
        // Load HTML to PDF
        $pdf = Pdf::loadHTML($html)->output();
    
        // Set response headers
        $headers = [
            "Content-type" => "application/pdf",
        ];
        return response()->streamDownload(
            fn() => print($pdf),
            "Bon.pdf",
            $headers
        );
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Request $request, $id)
    {
        // Check permission before editing
        if (!auth()->user()->can('Achat-modifier')) {
            return response()->json([
                'status' => 403,
                'message' => 'Vous n\'avez pas la permission de modifier un achat'
            ], 403);
        }

        $achat = Achat::find($id);
        
        if (!$achat) {
            return response()->json([
                'status' => 404,
                'message' => 'Achat non trouvé'
            ], 404);
        }

        return response()->json($achat);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request)
    {
        // Check permission before updating
        if (!auth()->user()->can('Achat-modifier')) {
            return response()->json([
                'status' => 403,
                'message' => 'Vous n\'avez pas la permission de modifier un achat'
            ], 403);
        }

        $achat = Achat::find($request->id);
    
        if (!$achat) {
            return response()->json([
                'status' => 404,
                'message' => 'Achat non trouvé'
            ], 404);
        }

        // Prevent modification if status is already validated (except for admins)
        if ($achat->status === 'Validation' && !auth()->user()->hasRole('Administrateur')) {
            return response()->json([
                'status' => 422,
                'message' => 'Impossible de modifier un achat déjà validé'
            ], 422);
        }
    
        $validator = Validator::make($request->all(), [
            'status' => 'required|string|in:Création,Validation,Refus,Livraison,Réception',
        ], [
            'required' => 'Le champ :attribute est requis.',
            'in' => 'Le statut doit être l\'un des suivants: Création, Validation, Refus, Livraison, Réception',
        ], [
            'status' => 'statut',
        ]);
    
        if ($validator->fails()) {
            return response()->json([
                'status' => 400,
                'errors' => $validator->messages(),
            ], 400);
        }

        // Store old values for audit comparison
        $oldStatus = $achat->status;
    
        $achat->status = $request->status;
        $achat->save();

        // Log significant status changes
        if ($oldStatus !== $request->status) {
            \Log::info('Achat status changed from "' . $oldStatus . '" to "' . $request->status . '" for achat ID: ' . $achat->id . ' by user: ' . auth()->user()->id);
        }
    
        return response()->json([
            'status' => 200,
            'message' => 'Achat mis à jour avec succès',
        ]);
    }

    public function ChangeStatusAchat(Request $request)
    {
        // Check permission before changing status
        if (!auth()->user()->can('Achat-modifier')) {
            return response()->json([
                'status' => 403,
                'message' => 'Vous n\'avez pas la permission de modifier le statut d\'un achat'
            ], 403);
        }

        try {
            $data = $request->all();
            \Log::info('ChangeStatusAchat called with data:', $data);
            
            // Retrieve the achat record
            $achat = Achat::find($data['id']);
            
            if (!$achat) {
                return response()->json([
                    'status' => 404,
                    'message' => 'Achat non trouvé'
                ], 404);
            }

            // Store old status for audit logging
            $oldStatus = $achat->status;
            
            if ($data['status'] == 'Validation')
            {
                // Begin transaction
                DB::beginTransaction();
                
                try {
                    // First, update the achat status (this will trigger audit trail)
                    $achat->status = 'Validation';
                    $achat->save();
                    
                    // Extract product from ligne achat 
                    $data_ligne_product = LigneAchat::where('idachat', $data['id'])->get();
                    \Log::info('Found ' . $data_ligne_product->count() . ' line items for achat ID: ' . $data['id']);
                    
                    foreach ($data_ligne_product as $value)
                    {
                        // Get product name for logging
                        $product = DB::table('products')->where('id', $value->idproduit)->first();
                        $productName = $product ? $product->name : 'Unknown Product';
                        
                        \Log::info('Processing product: "' . $productName . '" with quantity: ' . $value->qte);
                        
                        // Insert in stock but before insert check if has product
                        $check_product = Stock::where('id_product', $value->idproduit)->count();
                        
                        if ($check_product == 0)
                        {
                            // Extract id_tva and id_unite
                            $infoProduct = DB::table('Products as p')
                                ->join('tvas as t', 't.id', '=', 'p.id_tva')
                                ->join('unite as u', 'u.id', '=', 'p.id_unite')
                                ->whereNull('p.deleted_at')
                                ->select('t.id as id_tva', 'u.id as idunite', 'p.seuil')
                                ->where('p.id', $value->idproduit)
                                ->first();
                                
                            if (!$infoProduct) {
                                throw new \Exception("Product information not found for ID: " . $value->idproduit);
                            }
                            
                            // Insert in stock 
                            $Stock = Stock::create([
                                'id_product' => $value->idproduit,
                                'id_tva'     => $infoProduct->id_tva,    
                                'id_unite'   => $infoProduct->idunite,
                                'quantite'   => $value->qte,
                                'seuil'      => $infoProduct->seuil,
                            ]);
                            
                            \Log::info('Created new stock record for product: "' . $productName . '"');
                        }
                        else
                        {
                            // Update qte stock by products
                            $stock = Stock::where('id_product', $value->idproduit)->first();
                            $stock->quantite += $value->qte;
                            $stock->save();
                            
                            \Log::info('Updated existing stock for product: "' . $productName . '" - New quantity: ' . $stock->quantite);
                        }
                    }
                    
                    // Update inventory using the service - this only records the movement, doesn't modify stock
                    $this->inventoryService->updateInventoryForPurchase($achat);
                    \Log::info('Updated inventory for purchase ID: ' . $achat->id);
                    
                    DB::commit();
                    
                    // Notify the user who created this purchase
                    $creatorUser = User::find($achat->id_user);
                    if ($creatorUser) {
                        // Create hashids for secure URL
                        $hashids = new Hashids();
                        $encodedId = $hashids->encode($achat->id);
                        
                        $creatorUser->notify(new \App\Notifications\SystemNotification([
                            'message' => 'Votre achat a été approuvé',
                            'status' => 'Validation',
                            'view_url' => url('ShowBonReception/' . $encodedId)
                        ]));
                    }

                    // Log the status change
                    \Log::info('Achat status changed from "' . $oldStatus . '" to "Validation" for achat ID: ' . $achat->id . ' by user: ' . auth()->user()->id);
                    
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
            else if ($data['status'] == 'Refus')
            {
                $achat->status = 'Refus';
                $result = $achat->save();
                
                \Log::info('Updated achat status to Refus. Result: ' . ($result ? 'success' : 'failed'));
                
                // Notify the user who created this purchase
                $creatorUser = User::find($achat->id_user);
                if ($creatorUser) {
                    // Create hashids for secure URL
                    $hashids = new Hashids();
                    $encodedId = $hashids->encode($achat->id);
                    
                    $creatorUser->notify(new \App\Notifications\SystemNotification([
                        'message' => 'Votre achat a été refusé',
                        'status' => 'Refus',
                        'view_url' => url('ShowBonReception/' . $encodedId)
                    ]));
                }

                // Log the status change
                \Log::info('Achat status changed from "' . $oldStatus . '" to "Refus" for achat ID: ' . $achat->id . ' by user: ' . auth()->user()->id);
                
                return response()->json([
                    'status' => 200,
                    'message' => 'Opération réussie'
                ]);
            }
            else if ($data['status'] == 'Livraison')
            {
                $achat->status = 'Livraison';
                $result = $achat->save();
                
                \Log::info('Updated achat status to Livraison. Result: ' . ($result ? 'success' : 'failed'));
                \Log::info('Achat status changed from "' . $oldStatus . '" to "Livraison" for achat ID: ' . $achat->id . ' by user: ' . auth()->user()->id);
                
                return response()->json([
                    'status' => 200,
                    'message' => 'Opération réussie'
                ]);
            }
            else
            {
                $achat->status = 'Réception';
                $result = $achat->save();
                
                \Log::info('Updated achat status to Réception. Result: ' . ($result ? 'success' : 'failed'));
                \Log::info('Achat status changed from "' . $oldStatus . '" to "Réception" for achat ID: ' . $achat->id . ' by user: ' . auth()->user()->id);
                
                return response()->json([
                    'status' => 200,
                    'message' => 'Opération réussie'
                ]);
            }
        } catch (\Exception $e) {
            \Log::error('Error in ChangeStatusAchat: ' . $e->getMessage());
            \Log::error($e->getTraceAsString());
            
            return response()->json([
                'status' => 500,
                'message' => 'Une erreur est survenue lors du changement de statut: ' . $e->getMessage(),
                'error' => $e->getMessage()
            ]);
        }
    }
}