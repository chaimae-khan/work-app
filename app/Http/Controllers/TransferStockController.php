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
use Illuminate\Support\Facades\Validator;

class TransferStockController extends Controller
{
public function index(Request $request)
{
    // Check permission for viewing transfer list
    if (!auth()->user()->can('Transfer')) {
        return view('Error.index')
            ->withErrors('Vous n\'avez pas la permission de voir cette page');
    }

    $countStock = Stock::count();
    if($countStock == 0)
    {
        return view('Error.index')
        ->withErrors('tu n\'as pas de stock');
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
                // // Count total products in this transfer
                // DB::raw("(SELECT COUNT(DISTINCT id_product) FROM line_transfer WHERE id_stocktransfer = st.id) as product_count"),
                // Sum total quantity in this transfer
                DB::raw("(SELECT COALESCE(SUM(quantite), 0) FROM line_transfer WHERE id_stocktransfer = st.id) as total_quantity")
            )
            ->whereIn('st.status', ['Validation', 'Création', 'Refus'])
            ->whereNotNull('st.from')
            ->whereNotNull('st.to')
            ->whereNull('st.deleted_at'); // Exclude soft-deleted records
            
        return DataTables::of($query)
            ->addIndexColumn()
            ->addColumn('reference', function ($row) {
                return 'TRA-' . str_pad($row->id, 6, '0', STR_PAD_LEFT);
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
                if (auth()->user()->can('Transfer-modifier') && $row->status === 'Création') {
                    $btn .= '<a href="#" class="btn btn-sm bg-primary-subtle me-1 edit-btn" 
                                data-id="' . $row->id . '">
                                <i class="fa-solid fa-pen-to-square text-primary"></i>
                            </a>';
                }

                // View button - show for all statuses if user has view permission
                if (auth()->user()->can('Transfer')) {
                    $btn .= '<a href="' . url('transfer/' . $row->id) . '" class="btn btn-sm bg-success-subtle me-1" 
                                data-bs-toggle="tooltip" 
                                title="Voir Détails">
                                <i class="fa-solid fa-eye text-success"></i>
                            </a>';
                }

                // Delete button - don't show if status is "Validation" and user has delete permission
                if (auth()->user()->can('Transfer-supprimer') && $row->status !== 'Validation') {
                    $btn .= '<a href="#" class="btn btn-sm bg-danger-subtle DeleteTransfer"
                                data-id="' . $row->id . '" 
                                data-bs-toggle="tooltip" 
                                title="Supprimer Transfer">
                                <i class="fa-solid fa-trash text-danger"></i>
                            </a>';
                }

                return $btn;
            })
            ->filterColumn('to_name', function($query, $keyword) {
                $query->whereRaw("LOWER(CONCAT(to_user.prenom, ' ', to_user.nom)) LIKE ?", ["%".strtolower($keyword)."%"]);
            })
            ->filterColumn('from_name', function($query, $keyword) {
                $query->whereRaw("LOWER(CASE WHEN st.from IS NULL THEN '' ELSE CONCAT(from_user.prenom, ' ', from_user.nom) END) LIKE ?", ["%".strtolower($keyword)."%"]);
            })
            ->filterColumn('created_by_name', function($query, $keyword) {
                $query->whereRaw("LOWER(CONCAT(created_by.prenom, ' ', created_by.nom)) LIKE ?", ["%".strtolower($keyword)."%"]);
            })
            ->rawColumns(['status', 'action'])
            ->make(true);
    }

    $statuses = DB::table('stocktransfer')
        ->select('status')
        ->distinct()
        ->pluck('status');

    // Get only the currently authenticated user for "D'un formateur" dropdown
    $Formateur = User::where('id', Auth::id())->get();
    
    // Get all users EXCEPT the authenticated user for "À formateur" dropdown
    $ToFormateurs = User::where('id', '!=', Auth::id())->get();

    return view('Transfer.index')
        ->with('Formateur', $Formateur)
        ->with('ToFormateurs', $ToFormateurs)
        ->with('statuses', $statuses);
}

    public function getFormateurNotSelected(Request $request)
    {
        // Check permission before getting formateur data
        if (!auth()->user()->can('Transfer')) {
            return response()->json([
                'status' => 403,
                'message' => 'Vous n\'avez pas la permission d\'accéder à ces données'
            ], 403);
        }
        
        $IdFormateurSend = $request->id;
       
        // Get all users except the sender and format their names
        $Formateur = User::where('id', '!=', $IdFormateurSend)
            ->get()
            ->map(function($user) {
                return [
                    'id' => $user->id,
                    'name' => $user->prenom . ' ' . $user->nom,  // Concatenate prenom and nom
                    'matricule' => $user->matricule,
                    'email' => $user->email,
                    'telephone' => $user->telephone,
                    'fonction' => $user->fonction,
                    // Add any other fields you need
                ];
            });
        
        if($Formateur->isNotEmpty())
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
                'status'                        => 200,
                'data'                          => $Formateur,
                'dataCommandeByFormateurSend'   => $CommandeByFormateurSend
            ]);
        }
        
        return response()->json([
            'status' => 404,
            'message' => 'No users found'
        ]);
    }

    // The rest of your controller methods remain unchanged
    public function GetLigneCommandeByCommand(Request $request)
    {
        // Check permission before getting command line data
        if (!auth()->user()->can('Transfer')) {
            return response()->json([
                'status' => 403,
                'message' => 'Vous n\'avez pas la permission d\'accéder à ces données'
            ], 403);
        }
        
        $data = DB::table('ligne_vente as l')
        ->join('products as p','p.id' , '=','l.idproduit')
        ->join('stock as s','s.id_product','=','p.id')
        ->select('p.name','p.code_article','p.seuil','l.contete_formateur','p.id','l.idvente')
        ->where('l.idvente',$request->id)
        ->get();
        if($data)
        {
            return response()->json([
                'status'       => 200,
                'data'         => $data,
            ]);
        }
    }

    public function StoreProductStockTr(Request $request)
    {
        // Check permission before adding to transfer
        if (!auth()->user()->can('Transfer-ajoute')) {
            return response()->json([
                'status' => 403,
                'message' => 'Vous n\'avez pas la permission d\'ajouter un transfert'
            ], 403);
        }
        
        $data = $request->input('data');          
        $data['id_user'] = Auth::user()->id;
        $data['qteSend'] = 1;
        
        DB::beginTransaction();
    
        try {
            // check qte if insert or not
            $checkQteTransfer = TmpStockTransfer::where('id_product', $data['id'])
                ->where('from', $request->from)
                ->where('to', $request->to)
                ->where('iduser', $data['id_user'])
                ->where('idcommande', $request->idcommande)
                ->sum('quantite_transfer');
            
            if($checkQteTransfer == $data['contete_formateur'])
            {
                return response()->json([
                    'status'      => 440,
                    'message'     => " Impossible de modifier la quantité, car la quantité est égale à 0."
                ]);
            }

            $existingProduct = TmpStockTransfer::where('id_product', $data['id'])
                ->where('from', $request->from)
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
                    'id_product'        => $data['id'],
                    'quantite_stock'    => $data['contete_formateur'],
                    'quantite_transfer' => $data['qteSend'],
                    'from'              => $request->from,
                    'to'                => $request->to,
                    'iduser'            => $data['id_user'],
                    'idcommande'        => $request->idcommande,
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

    public function GetTmpStockTransferByTwoFormateur(Request $request)
    {
        \Log::info('GetTmpStockTransferByTwoFormateur called with:', $request->all());
        
        // Validate required parameters
        if (!$request->From_Formateur || !$request->To_Formateur) {
            \Log::warning('Missing required parameters', [
                'From_Formateur' => $request->From_Formateur,
                'To_Formateur' => $request->To_Formateur
            ]);
            
            return response()->json([
                'status' => 400,
                'message' => 'Paramètres From_Formateur et To_Formateur requis',
                'data' => []
            ], 400);
        }
        
        try {
            $data = DB::table('tmpstocktransfer as t')
                ->join('products as p', 't.id_product', '=', 'p.id')
                ->join('users as u', 't.from', '=', 'u.id')
                ->join('users as usr', 't.to', '=', 'usr.id')
                ->where('t.from', $request->From_Formateur)
                ->where('t.to', $request->To_Formateur)
                ->select([
                    't.id',
                    'p.name as name_product',
                    'p.code_article',
                    't.quantite_stock',
                    't.quantite_transfer',
                    DB::raw("CONCAT(u.prenom, ' ', u.nom) as `from`"),      // Keep as 'from' to match JS
                    DB::raw("CONCAT(usr.prenom, ' ', usr.nom) as `to`")     // Keep as 'to' to match JS
                ])
                ->orderBy('t.id', 'desc')  // Order by most recent first
                ->get();
            
            // Add debugging
            \Log::info('Query result count:', ['count' => $data->count()]);
            \Log::info('Query results:', $data->toArray());
            
            // Return the data directly for AJAX (DataTables format)
            return response()->json([
                'data' => $data
            ]);
            
        } catch (\Exception $e) {
            \Log::error('Error in GetTmpStockTransferByTwoFormateur: ' . $e->getMessage(), [
                'From_Formateur' => $request->From_Formateur,
                'To_Formateur' => $request->To_Formateur,
                'trace' => $e->getTraceAsString()
            ]);
            
            return response()->json([
                'status' => 500,
                'message' => 'Erreur lors de la récupération des données: ' . $e->getMessage(),
                'data' => []
            ], 500);
        }
    }

    public function DeleteRowsTmpStockTr(Request $request)
    {
        // REMOVED: Permission check - no longer checking for Transfer-supprimer permission
        
        // Validate the request
        if (!$request->id) {
            return response()->json([
                'status' => 400,
                'message' => 'ID requis pour la suppression'
            ], 400);
        }
        
        // Try to find the record first
        $tmpTransfer = TmpStockTransfer::find($request->id);
        
        if (!$tmpTransfer) {
            return response()->json([
                'status' => 404,
                'message' => 'Enregistrement non trouvé'
            ], 404);
        }
        
        // Log the deletion attempt
        \Log::info('Attempting to delete temporary transfer', [
            'id' => $request->id,
            'product_id' => $tmpTransfer->id_product,
            'from' => $tmpTransfer->from,
            'to' => $tmpTransfer->to
        ]);
        
        // Delete the record
        $deleted = TmpStockTransfer::where('id', $request->id)->delete();
        
        if ($deleted) {
            \Log::info('Temporary transfer deleted successfully', ['id' => $request->id]);
            
            return response()->json([
                'status' => 200,
                'message' => 'Suppression effectuée avec succès.'
            ]);
        } else {
            \Log::error('Failed to delete temporary transfer', ['id' => $request->id]);
            
            return response()->json([
                'status' => 500,
                'message' => 'Erreur lors de la suppression.'
            ], 500);
        }
    }

    public function StoreTransfer(Request $request)
    {
        // Check permission before storing transfer
        if (!auth()->user()->can('Transfer-ajoute')) {
            return response()->json([
                'status' => 403,
                'message' => 'Vous n\'avez pas la permission d\'ajouter un transfert'
            ], 403);
        }
        
        DB::beginTransaction();
        
        try {
            // Create the stock transfer record
            $stockTransfer = StockTransfer::create([
                'id_user' => Auth::user()->id,
                'status' => 'Création',
                'from' => $request->from,
                'to' => $request->to
            ]);
    
            // Get all temporary stock transfers
            $tmpStockTransfers = TmpStockTransfer::where('from', $request->from)
                ->where('to', $request->to)
                ->where('idcommande', $request->idcommande)
                ->get();
    
            // Process each temporary stock transfer
            foreach($tmpStockTransfers as $item) {
                // Get product details to get TVA and Unite
                $product = DB::table('products')
                    ->join('stock', 'stock.id_product', '=', 'products.id')
                    ->select('products.*', 'stock.id_tva', 'stock.id_unite')
                    ->where('products.id', $item->id_product)
                    ->first();
    
                // Create line transfer record with TVA and Unite values
                LineTransfer::create([
                    'id_user'        => Auth::user()->id,
                    'id_product'     => $item->id_product,
                    'id_tva'         => $product ? $product->id_tva : null,
                    'id_unite'       => $product ? $product->id_unite : null,
                    'idcommande'     => $request->idcommande,
                    'id_stocktransfer' => $stockTransfer->id,
                    'quantite'       => $item->quantite_transfer
                ]);
    
                // Note: We no longer update ligne_vente records here
                // The contente_transfert will be updated only during validation
            }
    
            // Delete the temporary records
            TmpStockTransfer::where('from', $request->from)
                ->where('to', $request->to)
                ->where('idcommande', $request->idcommande)
                ->delete();
    
            DB::commit();
            
            return response()->json([
                'status'  => 200,
                'message' => 'Opération réussie'
            ]);
            
        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json([
                'status'  => 500,
                'message' => 'Une erreur est survenue: ' . $e->getMessage()
            ]);
        }
    }

    /**
     * Edit a stock transfer
     *
     * @param Request $request
     * @param int $id
     * @return \Illuminate\Http\JsonResponse
     */
    public function edit(Request $request, $id)
    {
        // Check permission before editing
        if (!auth()->user()->can('Transfer-modifier')) {
            return response()->json([
                'status' => 403,
                'message' => 'Vous n\'avez pas la permission de modifier un transfert'
            ], 403);
        }
        
        $transfer = StockTransfer::find($id);
        
        if (!$transfer) {
            return response()->json([
                'status' => 404,
                'message' => 'Transfer non trouvé'
            ], 404);
        }

        return response()->json($transfer);
    }

    /**
     * Update a stock transfer (handles status changes including refusal)
     *
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function update(Request $request)
    {
        // Check permission before updating
        if (!auth()->user()->can('Transfer-modifier')) {
            return response()->json([
                'status' => 403,
                'message' => 'Vous n\'avez pas la permission de modifier un transfert'
            ], 403);
        }
        
        $transfer = StockTransfer::find($request->id);

        if (!$transfer) {
            return response()->json([
                'status' => 404,
                'message' => 'Transfer non trouvé'
            ], 404);
        }

        $validator = \Illuminate\Support\Facades\Validator::make($request->all(), [
            'status' => 'required|string|in:Création,Validation,Refus',
            'refusal_reason' => 'required_if:status,Refus|nullable|string|max:500',
        ], [
            'required' => 'Le champ :attribute est requis.',
            'required_if' => 'Le motif de refus est requis lorsque le statut est "Refus".',
            'in' => 'Le statut doit être l\'un des suivants: Création, Validation, Refus',
            'max' => 'Le motif de refus ne peut pas dépasser 500 caractères.',
        ], [
            'status' => 'statut',
            'refusal_reason' => 'motif de refus',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'status' => 400,
                'errors' => $validator->messages(),
            ], 400);
        }

        // Store old status for logging
        $oldStatus = $transfer->status;
        
        // Handle status change with validation logic
        if ($request->status === 'Validation') {
            // Use the existing ChangeStatusTransfer logic for validation
            return $this->ChangeStatusTransfer($request);
        } else {
            // Handle simple status changes (Création or Refus)
            $transfer->status = $request->status;
            
            // Handle refusal reason
            if ($request->status === 'Refus') {
                $transfer->refusal_reason = $request->refusal_reason;
            } else {
                $transfer->refusal_reason = null; // Clear refusal reason for other statuses
            }
            
            $transfer->save(); // This will create audit record automatically

            // Log the status change
            \Log::info('StockTransfer (Transfer) status changed from "' . $oldStatus . '" to "' . $request->status . '" for transfer ID: ' . $transfer->id . ' by user: ' . auth()->user()->id);

            return response()->json([
                'status' => 200,
                'message' => 'Transfer mis à jour avec succès',
            ]);
        }
    }

    /**
     * Change the status of a stock transfer with special handling for validation
     *
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
 public function ChangeStatusTransfer(Request $request)
{
    // Check permission before changing status
    if (!auth()->user()->can('Transfer-modifier')) {
        return response()->json([
            'status' => 403,
            'message' => 'Vous n\'avez pas la permission de modifier le statut d\'un transfert'
        ], 403);
    }
    
    try {
        $data = $request->all();
        \Log::info('ChangeStatusTransfer called with data:', $data);

        // Retrieve the transfer record
        $transfer = StockTransfer::find($data['id']);
        
        if (!$transfer) {
            return response()->json([
                'status' => 404,
                'message' => 'Transfer non trouvé'
            ], 404);
        }

        // Store old status for audit logging
        $oldStatus = $transfer->status;

        if($data['status'] == 'Validation')
        {
            // Begin transaction
            DB::beginTransaction();
            
            try {
                // First, update the transfer status
                $transfer->status = 'Validation';
                $transfer->refusal_reason = null;
                $transfer->save();
                
                // Get all LineTransfer records associated with this transfer
                $lineTransfers = LineTransfer::where('id_stocktransfer', $data['id'])->get();
                \Log::info('Found ' . $lineTransfers->count() . ' line items for transfer ID: ' . $data['id']);

                // Group line transfers by command (idcommande) for efficient processing
                $commandeGroups = $lineTransfers->groupBy('idcommande');

                foreach($commandeGroups as $idcommande => $commandLineTransfers)
                {
                    // Get the original vente record to copy its details
                    $originalVente = Vente::find($idcommande);
                    
                    if (!$originalVente) {
                        throw new \Exception('Vente originale non trouvée pour la commande ID: ' . $idcommande);
                    }

                    // Create a new vente record for the receiving user
                    $newVente = Vente::create([
                        'id_user' => $transfer->to,
                        'id_client' => $originalVente->id_client,
                        'id_formateur' => $transfer->to, // Important: set as formateur
                        'status' => 'Validation', // Auto-validate the received stock
                        'is_transfer' => 1, // Mark as transfer
                        'total' => 0, // Initialize total as 0, will be calculated if needed
                        'date_vente' => now(),
                        // Copy other fields from original if they exist
                        'tva' => $originalVente->tva ?? 0,
                        'remise' => $originalVente->remise ?? 0,
                        'type_vente' => $originalVente->type_vente ?? 'transfer',
                    ]);
                    
                    \Log::info('Created new vente for receiving user: ' . $newVente->id);

                    $totalTransferred = 0; // To calculate the total value

                    // Process each line transfer in this command group
                    foreach($commandLineTransfers as $lineTransfer)
                    {
                        // Update sender's ligne_vente (reduce contete_formateur)
                        $senderLigneVente = LigneVente::where('idvente', $lineTransfer->idcommande)
                                                    ->where('idproduit', $lineTransfer->id_product)
                                                    ->first();
                        
                        if($senderLigneVente) {
                            \Log::info('Processing sender ligne_vente for product ID: ' . $lineTransfer->id_product);
                            
                            // Check if contete_formateur has enough quantity
                            if ($senderLigneVente->contete_formateur >= $lineTransfer->quantite) {
                                // Initialize contente_transfert to 0 if it's null
                                if ($senderLigneVente->contente_transfert === null) {
                                    $senderLigneVente->contente_transfert = 0;
                                }
                                
                                // Add the transfer quantity to contente_transfert
                                $senderLigneVente->contente_transfert += $lineTransfer->quantite;
                                
                                // Reduce the quantity from contete_formateur
                                $senderLigneVente->contete_formateur -= $lineTransfer->quantite;
                                
                                $senderLigneVente->save();
                                
                                \Log::info('Updated sender contente_transfert to: ' . $senderLigneVente->contente_transfert . 
                                          ' and reduced contete_formateur to: ' . $senderLigneVente->contete_formateur);
                            } else {
                                throw new \Exception('Quantité insuffisante dans contete_formateur pour le produit ID: ' . $lineTransfer->id_product);
                            }
                        }

                        // Get product details for calculating total
                        $product = DB::table('products')->where('id', $lineTransfer->id_product)->first();
                        if ($product) {
                            $totalTransferred += ($product->price_achat ?? 0) * $lineTransfer->quantite;
                        }

                        // Create new ligne_vente for receiver
                        LigneVente::create([
                            'id_user' => $transfer->to,
                            'idvente' => $newVente->id,
                            'idproduit' => $lineTransfer->id_product,
                            'qte' => $lineTransfer->quantite,
                            'contete_formateur' => $lineTransfer->quantite, // Received quantity goes to contete_formateur
                            'contente_transfert' => null, // No transfers yet for this user
                        ]);
                        
                        \Log::info('Created new receiver ligne_vente with contete_formateur: ' . $lineTransfer->quantite);
                    }

                    // Update the total of the new vente
                    $newVente->update(['total' => $totalTransferred]);
                }
                
                DB::commit();
                
                // Log the status change
                \Log::info('StockTransfer (Transfer) status changed from "' . $oldStatus . '" to "Validation" for transfer ID: ' . $transfer->id . ' by user: ' . auth()->user()->id);
                
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
            $result = $transfer->save();
            
            \Log::info('Updated transfer status to Refus with reason: ' . $data['refusal_reason'] . ' for transfer ID: ' . $transfer->id . ' by user: ' . auth()->user()->id);
            
            if($result) {
                return response()->json([
                    'status' => 200,
                    'message' => 'Refus enregistré avec succès'
                ]);
            } else {
                return response()->json([
                    'status' => 500,
                    'message' => 'Erreur lors de l\'enregistrement du refus'
                ]);
            }
        }
        else 
        {
            // Handle other status changes (like back to Création)
            $transfer->status = $data['status'];
            $transfer->refusal_reason = null;
            $result = $transfer->save();
            
            \Log::info('Updated transfer status to ' . $data['status'] . ' for transfer ID: ' . $transfer->id . ' by user: ' . auth()->user()->id);
            
            if($result) {
                return response()->json([
                    'status' => 200,
                    'message' => 'Statut mis à jour avec succès'
                ]);
            } else {
                return response()->json([
                    'status' => 500,
                    'message' => 'Erreur lors de la mise à jour du statut'
                ]);
            }
        }
        
    } catch (\Exception $e) {
        \Log::error('Error in ChangeStatusTransfer: ' . $e->getMessage());
        return response()->json([
            'status' => 500,
            'message' => 'Une erreur est survenue: ' . $e->getMessage()
        ]);
    }
}

    /**
     * Delete a stock transfer
     *
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function destroy(Request $request)
    {
        // Check permission before deleting
        if (!auth()->user()->can('Transfer-supprimer')) {
            return response()->json([
                'status' => 403,
                'message' => 'Vous n\'avez pas la permission de supprimer un transfert'
            ], 403);
        }
        
        $transfer = StockTransfer::find($request->id);
        
        if (!$transfer) {
            return response()->json([
                'status' => 404,
                'message' => 'Transfer non trouvé'
            ], 404);
        }
        
        // Don't allow deletion of validated transfers
        if ($transfer->status === 'Validation') {
            return response()->json([
                'status' => 400,
                'message' => 'Impossible de supprimer un transfert validé'
            ], 400);
        }
        
        try {
            DB::beginTransaction();
            
            // Delete related line transfers first
            LineTransfer::where('id_stocktransfer', $transfer->id)->delete();
            
            // Delete the transfer
            $transfer->delete();
            
            DB::commit();
            
            \Log::info('Transfer deleted successfully', ['id' => $transfer->id, 'deleted_by' => auth()->user()->id]);
            
            return response()->json([
                'status' => 200,
                'message' => 'Transfer supprimé avec succès'
            ]);
            
        } catch (\Exception $e) {
            DB::rollBack();
            \Log::error('Error deleting transfer: ' . $e->getMessage());
            
            return response()->json([
                'status' => 500,
                'message' => 'Une erreur est survenue lors de la suppression'
            ]);
        }
    }
    public function UpdateQteTmpTransfer(Request $request)
{
    
    $validator = Validator::make($request->all(), [
        'qte' => 'required|numeric|min:1',
    ], [
        'required' => 'Le champ :attribute est requis.',
        'numeric' => 'Le champ :attribute doit être un nombre.',
        'min' => 'Le champ :attribute doit être au moins :min.',
    ], [
        'qte' => 'quantité',
    ]);
    
    if ($validator->fails()) {
        return response()->json([
            'status' => 400,
            'errors' => $validator->messages(),
        ], 400);
    }
    
    // Try to find the record
    $tmpTransfer = TmpStockTransfer::find($request->id);
    
    if (!$tmpTransfer) {
        return response()->json([
            'status' => 404,
            'message' => 'Enregistrement non trouvé',
        ], 404);
    }
    
    // Check if quantity is valid (not more than available)
    if ($request->qte > $tmpTransfer->quantite_stock) {
        return response()->json([
            'status' => 422,
            'message' => 'La quantité demandée (' . $request->qte . ') est supérieure à la quantité disponible (' . $tmpTransfer->quantite_stock . ')',
        ], 422);
    }
    
    // Update the quantity
    $updated = TmpStockTransfer::where('id', $request->id)->update([
        'quantite_transfer' => $request->qte,
    ]);
    
    if ($updated) {
        \Log::info('Temporary transfer quantity updated successfully', [
            'id' => $request->id,
            'old_quantity' => $tmpTransfer->quantite_transfer,
            'new_quantity' => $request->qte
        ]);
        
        return response()->json([
            'status' => 200,
            'message' => 'Mise à jour effectuée avec succès.',
        ]);
    } else {
        \Log::error('Failed to update temporary transfer quantity', [
            'id' => $request->id,
            'quantity' => $request->qte
        ]);
        
        return response()->json([
            'status' => 500,
            'message' => 'Impossible de modifier la quantité',
        ], 500);
    }
}
   public function deleteTransfer(Request $request)
    {
        // Check permission before deleting
        if (!auth()->user()->can('Transfer-supprimer')) {
            return response()->json([
                'status' => 403,
                'message' => 'Vous n\'avez pas la permission de supprimer un transfert'
            ], 403);
        }
        
        try {
            DB::beginTransaction();
            
            // Find the transfer
            $transfer = StockTransfer::findOrFail($request->id);
            
            // Check if transfer can be deleted (only if status is not "Validation")
            if ($transfer->status === 'Validation') {
                return response()->json([
                    'status' => 400,
                    'message' => 'Impossible de supprimer un transfert validé'
                ], 400);
            }
            
            // Delete related line transfer records first
            LineTransfer::where('id_stocktransfer', $transfer->id)->delete();
            
            // Then delete the transfer record
            $transfer->delete();
            
            DB::commit();
            
            return response()->json([
                'status' => 200,
                'message' => 'Transfert supprimé avec succès.'
            ]);
            
        } catch (\Exception $e) {
            DB::rollBack();
            \Log::error('Error deleting transfer: ' . $e->getMessage());
            
            return response()->json([
                'status' => 500,
                'message' => 'Une erreur est survenue lors de la suppression du transfert: ' . $e->getMessage()
            ], 500);
        }
    }
    public function showTransferDetail($id)
    {
        // Get the stock transfer with relationships
        $stockTransfer = StockTransfer::with(['user'])->findOrFail($id);
        
        // Get the "from" and "to" users
        $fromUser = User::find($stockTransfer->from);
        $toUser = User::find($stockTransfer->to);
        
        // Get all line transfers for this transfer with related data
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
        return view('Transfer.detail', compact(
            'stockTransfer',
            'lineTransfers',
            'totalProducts',
            'totalQuantity',
            'statusChanger',
            'statusChangeDate',
            'fromUser',
            'toUser'
        ));
    }
}