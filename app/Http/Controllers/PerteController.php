<?php

namespace App\Http\Controllers;

use App\Models\Perte;
use App\Models\Product;
use App\Models\Category;
use App\Models\SubCategory;
use App\Models\Unite;
use App\Models\Stock;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Yajra\DataTables\Facades\DataTables;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;

class PerteController extends Controller
{
  /**
 * Display a listing of pertes
 */
public function index(Request $request)
{
    // Check for required data
    $countCategories = Category::count();
    $countSubCategories = SubCategory::count();
    $countProducts = Product::count();
    
    if ($countCategories == 0) {
        return view('Error.index')
            ->withErrors('Tu n\'as pas de catégories');
    }
    
    if ($countSubCategories == 0) {
        return view('Error.index')
            ->withErrors('Tu n\'as pas de famille');
    }
    
    if ($countProducts == 0) {
        return view('Error.index')
            ->withErrors('Tu n\'as pas de produits');
    }
    
    if ($request->ajax()) {
        $query = DB::table('pertes as pt')
            ->leftJoin('products as p', 'pt.id_product', '=', 'p.id')
            ->leftJoin('categories as c', 'pt.id_category', '=', 'c.id')
            ->leftJoin('sub_categories as sc', 'pt.id_subcategorie', '=', 'sc.id')
            ->leftJoin('unite as u', 'pt.id_unite', '=', 'u.id')
            ->leftJoin('users as us', 'pt.id_user', '=', 'us.id')
            ->whereNull('pt.deleted_at');
        
        // Apply filters if provided
        if ($request->filled('filter_status')) {
            $query->where('pt.status', $request->filter_status);
        }
        
        if ($request->filled('filter_categorie')) {
            $query->where('pt.id_category', $request->filter_categorie);
        }
        
        if ($request->filled('filter_subcategorie')) {
            $query->where('pt.id_subcategorie', $request->filter_subcategorie);
        }
        
        $pertes = $query->select(
            'pt.id',
            'pt.classe',
            'c.name as categorie',
            'sc.name as famille',
            'pt.designation',
            'u.name as unite',
            'pt.quantite',
            'pt.nature',
            'pt.date_perte',
            'pt.cause',
            'pt.status',
            'pt.refusal_reason',
            DB::raw("CONCAT(us.prenom, ' ', us.nom) as username"),
            'pt.created_at'
        )
        ->orderBy('pt.id', 'desc');

        return DataTables::of($pertes)
            ->addIndexColumn()
            ->addColumn('status_badge', function ($row) {
                $badges = [
                    'En attente' => '<span class="badge bg-warning text-dark"><i class="fa-solid fa-clock"></i> En attente</span>',
                    'Validé' => '<span class="badge bg-success"><i class="fa-solid fa-check"></i> Validé</span>',
                    'Refusé' => '<span class="badge bg-danger"><i class="fa-solid fa-times"></i> Refusé</span>',
                ];
                return $badges[$row->status] ?? $row->status;
            })
            ->addColumn('action', function ($row) {
                $btn = '';
                
                // Show details button - redirects to detail page
                $btn .= '<a href="'.route('pertes.show', $row->id).'" class="btn btn-sm bg-info-subtle me-1" title="Voir les détails">
                        <i class="fa-solid fa-eye text-info"></i></a>';
                
                // Show edit status button only for admin and if status is "En attente"
                if (auth()->user()->can('Pertes-valider') && $row->status == 'En attente') {
                    $btn .= '<a href="#" class="btn btn-sm bg-primary-subtle me-1 edit-perte-btn" data-id="'.$row->id.'" title="Modifier le statut">
                            <i class="fa-solid fa-pen-to-square text-primary"></i></a>';
                }
                
                // Show delete button for authorized users (not if status is "Validé")
                if (auth()->user()->can('Pertes-supprimer') && $row->status !== 'Validé') {
                    $btn .= '<a href="#" class="btn btn-sm bg-danger-subtle deletePerte" data-id="'.$row->id.'" title="Supprimer">
                            <i class="fa-solid fa-trash text-danger"></i></a>';
                }
                
                return $btn;
            })
            ->rawColumns(['status_badge', 'action'])
            ->make(true);
    }
    
    // Get required data for dropdowns
    $categories = Category::all();
    $subcategories = SubCategory::all();
    $class = DB::select("SELECT DISTINCT(classe) as classe FROM categories");
    
    return view('pertes.index', compact('categories', 'subcategories', 'class'));
}

    /**
     * Get products by subcategory
     */
    public function getProductsBySubcategory($subcategoryId)
    {
        try {
            $validator = Validator::make(
                ['subcategory_id' => $subcategoryId],
                ['subcategory_id' => 'required|integer|exists:sub_categories,id']
            );

            if ($validator->fails()) {
                return response()->json([
                    'status' => 400,
                    'message' => 'ID de famille invalide',
                    'products' => []
                ], 400);
            }

            $products = Product::where('id_subcategorie', $subcategoryId)
                ->with(['unite'])
                ->select('id', 'name', 'id_unite')
                ->orderBy('name', 'asc')
                ->get();
            
            Log::info('Products retrieved', [
                'subcategory_id' => $subcategoryId,
                'count' => $products->count()
            ]);
            
            return response()->json([
                'status' => 200,
                'products' => $products
            ]);
        } catch (\Exception $e) {
            Log::error('Erreur lors de la récupération des produits', [
                'subcategory_id' => $subcategoryId,
                'error_message' => $e->getMessage(),
                'error_trace' => $e->getTraceAsString()
            ]);
            
            return response()->json([
                'status' => 500,
                'message' => 'Erreur lors de la récupération des produits',
                'products' => []
            ], 500);
        }
    }

    /**
     * Store a newly created perte
     */
    public function store(Request $request)
    {
        // Check if user has permission to add pertes
        if (!auth()->user()->can('Pertes-ajouter')) {
            return response()->json([
                'status' => 403,
                'message' => 'Vous n\'avez pas la permission d\'ajouter des pertes'
            ], 403);
        }
    
        $validator = Validator::make($request->all(), [
            'classe' => 'required|string|max:255',
            'id_category' => 'required|exists:categories,id',
            'id_subcategorie' => 'required|exists:sub_categories,id',
            'id_product' => 'required|exists:products,id',
            'quantite' => 'required|numeric|min:0.01',
            'nature' => 'required|string|max:255',
            'date_perte' => 'required|date',
            'cause' => 'required|string',
        ], [
            'required' => 'Le champ :attribute est requis.',
            'numeric' => 'Le champ :attribute doit être un nombre.',
            'exists' => 'La valeur sélectionnée pour :attribute est invalide.',
            'date' => 'Le champ :attribute doit être une date valide.',
            'min' => 'Le champ :attribute doit être au moins :min.',
        ]);
        
        if ($validator->fails()) {
            return response()->json([
                'status' => 400,
                'errors' => $validator->messages(),
            ], 400);
        }
    
        try {
            DB::beginTransaction();
            
            // Get product details
            $product = Product::with(['unite', 'category', 'subcategory'])->find($request->id_product);
            
            if (!$product) {
                return response()->json([
                    'status' => 404,
                    'message' => 'Produit non trouvé',
                ], 404);
            }
            
            // Verify the relationship between category and subcategory
            $subcategory = SubCategory::find($request->id_subcategorie);
            if ($subcategory->id_categorie != $request->id_category) {
                return response()->json([
                    'status' => 400,
                    'message' => 'La famille sélectionnée n\'appartient pas à cette catégorie',
                ], 400);
            }
            
            // Create perte
            $perte = Perte::create([
                'id_product' => $product->id,
                'id_category' => $request->id_category,
                'id_subcategorie' => $request->id_subcategorie,
                'id_unite' => $product->id_unite,
                'classe' => $request->classe,
                'designation' => $product->name, // Store current product name
                'quantite' => $request->quantite,
                'nature' => $request->nature,
                'date_perte' => $request->date_perte,
                'cause' => $request->cause,
                'status' => 'En attente',
                'id_user' => Auth::id(),
            ]);
            
            DB::commit();
            
            return response()->json([
                'status' => 200,
                'message' => 'Perte déclarée avec succès',
            ]);
            
        } catch (\Exception $e) {
            DB::rollBack();
            
            Log::error('Error creating perte: ' . $e->getMessage(), [
                'request' => $request->all(),
                'trace' => $e->getTraceAsString()
            ]);
            
            return response()->json([
                'status' => 500,
                'message' => 'Une erreur est survenue. Veuillez réessayer.',
            ], 500);
        }
    }

    /**
     * Show the perte details
     */
    public function show($id)
    {
        try {
            $perte = Perte::with(['product', 'category', 'subcategory', 'unite', 'user'])
                ->findOrFail($id);
            
            return view('pertes.detail', compact('perte'));
            
        } catch (\Exception $e) {
            return redirect()->route('pertes.index')
                ->with('error', 'Perte non trouvée');
        }
    }

    /**
     * Get perte for editing/viewing
     */
    public function edit($id)
    {
        // Check if user has permission to view pertes
        if (!auth()->user()->can('Pertes-valider')) {
            return response()->json([
                'status' => 403,
                'message' => 'Vous n\'avez pas la permission de voir cette perte'
            ], 403);
        }

        try {
            $perte = Perte::with(['product', 'category', 'subcategory', 'unite', 'user'])
                ->findOrFail($id);
            
            return response()->json($perte);
            
        } catch (\Exception $e) {
            Log::error('Error fetching perte: ' . $e->getMessage());
            
            return response()->json([
                'status' => 404,
                'message' => 'Perte non trouvée'
            ], 404);
        }
    }

    /**
     * Validate or refuse a perte
     */
  public function changeStatus(Request $request)
{
    // Check if user has permission to validate pertes
    if (!auth()->user()->can('Pertes-valider')) {
        return response()->json([
            'status' => 403,
            'message' => 'Vous n\'avez pas la permission de valider/refuser des pertes'
        ], 403);
    }

    try {
        $data = $request->all();
        Log::info('changeStatus called with data:', $data);

        $perte = Perte::find($data['id']);
        
        if (!$perte) {
            return response()->json([
                'status' => 404,
                'message' => 'Perte non trouvée'
            ], 404);
        }

        // Store old status for logging
        $oldStatus = $perte->status;

        if ($data['status'] == 'Validé') {
            DB::beginTransaction();
            
            try {
                // Validate: Reduce stock quantity
                $stock = Stock::where('id_product', $perte->id_product)->first();
                
                if (!$stock) {
                    throw new \Exception('Stock non trouvé pour ce produit');
                }
                
                // Log current stock before update
                Log::info('Before stock update', [
                    'product_id' => $perte->id_product,
                    'current_stock' => $stock->quantite,
                    'perte_quantity' => $perte->quantite
                ]);
                
                // Check if stock has enough quantity
                if ($stock->quantite < $perte->quantite) {
                    throw new \Exception('Quantité en stock insuffisante. Stock disponible: ' . $stock->quantite);
                }
                
                // Calculate new quantity
                $oldQuantity = $stock->quantite;
                $newQuantity = $oldQuantity - $perte->quantite;
                
                // IMPORTANT: Use 'stock' (singular) not 'stocks' (plural)
                DB::table('stock')
                    ->where('id_product', $perte->id_product)
                    ->update([
                        'quantite' => $newQuantity,
                        'updated_at' => now()
                    ]);
                
                // Refresh stock model to get updated value
                $stock->refresh();
                
                // Log after update
                Log::info('After stock update', [
                    'product_id' => $perte->id_product,
                    'old_quantity' => $oldQuantity,
                    'perte_quantity' => $perte->quantite,
                    'new_quantity' => $stock->quantite,
                    'expected_quantity' => $newQuantity
                ]);
                
                // Update perte status
                $perte->status = 'Validé';
                $perte->refusal_reason = null;
                $perte->save();
                
                DB::commit();
                
                Log::info('Perte validated successfully', [
                    'perte_id' => $perte->id,
                    'stock_reduced_from' => $oldQuantity,
                    'stock_reduced_to' => $stock->quantite
                ]);
                
                return response()->json([
                    'status' => 200,
                    'message' => 'Perte validée avec succès. Stock réduit de ' . $oldQuantity . ' à ' . $stock->quantite
                ]);
                
            } catch (\Exception $e) {
                DB::rollBack();
                Log::error('Error in validation process: ' . $e->getMessage(), [
                    'trace' => $e->getTraceAsString()
                ]);
                
                return response()->json([
                    'status' => 500,
                    'message' => 'Erreur lors de la validation: ' . $e->getMessage()
                ]);
            }
        }
        else if ($data['status'] == 'Refusé') {
            // Validate refusal reason is provided
            if (empty($data['refusal_reason'])) {
                return response()->json([
                    'status' => 400,
                    'message' => 'Le motif de refus est requis'
                ], 400);
            }
            
            $perte->status = 'Refusé';
            $perte->refusal_reason = $data['refusal_reason'];
            $perte->save();
            
            Log::info('Perte refused with reason: "' . $data['refusal_reason'] . '" for perte ID: ' . $perte->id);
            
            return response()->json([
                'status' => 200,
                'message' => 'Perte refusée avec succès'
            ]);
        }
        else {
            return response()->json([
                'status' => 400,
                'message' => 'Statut invalide'
            ], 400);
        }
        
    } catch (\Exception $e) {
        Log::error('Error in changeStatus: ' . $e->getMessage(), [
            'trace' => $e->getTraceAsString()
        ]);
        
        return response()->json([
            'status' => 500,
            'message' => 'Une erreur est survenue: ' . $e->getMessage()
        ]);
    }
}

    /**
     * Delete a perte
     */
    public function destroy(Request $request)
    {
        // Check if user has permission to delete pertes
        if (!auth()->user()->can('Pertes-supprimer')) {
            return response()->json([
                'status' => 403,
                'message' => 'Vous n\'avez pas la permission de supprimer des pertes'
            ], 403);
        }

        try {
            DB::beginTransaction();
            
            $perte = Perte::find($request->id);
            
            if (!$perte) {
                return response()->json([
                    'status' => 404,
                    'message' => 'Perte non trouvée'
                ], 404);
            }
            
            // Check if perte can be deleted (only if status is not "Validé")
            if ($perte->status === 'Validé') {
                return response()->json([
                    'status' => 400,
                    'message' => 'Impossible de supprimer une perte validée'
                ], 400);
            }
            
            // Delete the perte
            $perte->delete();
            
            DB::commit();
            
            Log::info('Perte deleted. ID: ' . $request->id);
            
            return response()->json([
                'status' => 200,
                'message' => 'Perte supprimée avec succès'
            ]);
            
        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Error deleting perte: ' . $e->getMessage());
            
            return response()->json([
                'status' => 500,
                'message' => 'Une erreur est survenue lors de la suppression'
            ], 500);
        }
    }
}