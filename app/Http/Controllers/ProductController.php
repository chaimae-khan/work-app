<?php

namespace App\Http\Controllers;

use App\Models\Product;
use App\Models\Stock;
use App\Models\Category;
use App\Models\SubCategory;
use App\Models\Local;
use App\Models\Rayon;
use App\Models\Tva;
use App\Models\Unite;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Yajra\DataTables\Facades\DataTables;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Str;
use App\Helpers\ChunkReadFilter;
use League\Csv\Reader;
use League\Csv\Statement; 
use App\Models\Fournisseur;

class ProductController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $countCategories = Category::count();
        $countSubCategories = SubCategory::count();
        $countLocals = Local::count();
        $countRayons = Rayon::count();
        $countFournisseur = Fournisseur::count();
        
        // Check for required tables first
        if ($countCategories == 0) {
            return view('Error.index')
                ->withErrors('Tu n\'as pas de catégories');
        }
        if ($countFournisseur == 0) {
            return view('Error.index')
                ->withErrors('Tu n\'as pas de fournisseur');
        }
        
        if ($countSubCategories == 0) {
            return view('Error.index')
                ->withErrors('Tu n\'as pas de famille');
        }
        
        if ($countLocals == 0) {
            return view('Error.index')
                ->withErrors('Tu n\'as pas de locaux');
        }
        
        if ($countRayons == 0) {
            return view('Error.index')
                ->withErrors('Tu n\'as pas de rayons');
        }
        
        // Optional checks for nullable fields
        $countTvas = Tva::count();
        if ($countTvas == 0) {
            return view('Error.index')
                ->withErrors('Tu n\'as pas de TVAs');
        }
        
        $countUnites = Unite::count();
        if ($countUnites == 0) {
            return view('Error.index')
                ->withErrors('Tu n\'as pas d\'unités');
        }
        
        if ($request->ajax()) {
            $query = DB::table('products as p')
                ->leftJoin('stock as s', 'p.id', '=', 's.id_product')
                ->leftJoin('categories as c', 'p.id_categorie', '=', 'c.id')
                ->leftJoin('sub_categories as sc', 'p.id_subcategorie', '=', 'sc.id')
                ->leftJoin('locals as l', 'p.id_local', '=', 'l.id')
                ->leftJoin('rayons as r', 'p.id_rayon', '=', 'r.id')
                ->leftJoin('tvas as t', 's.id_tva', '=', 't.id')
                ->leftJoin('unite as u', 's.id_unite', '=', 'u.id')
                ->leftJoin('users as us', 'p.id_user', '=', 'us.id')
                ->whereNull('p.deleted_at');
            
          // Apply class filter if provided
if ($request->filled('filter_class')) {
    $query->where('c.classe', $request->filter_class);
}

// Apply category filter if provided
if ($request->filled('filter_categorie')) {
    $query->where('p.id_categorie', $request->filter_categorie);
}

// Apply subcategory filter if provided
if ($request->filled('filter_subcategorie')) {
    $query->where('p.id_subcategorie', $request->filter_subcategorie);
}

// Apply designation (name) filter if provided
if ($request->filled('filter_designation')) {
    $query->where('p.name', 'LIKE', '%' . $request->filter_designation . '%');
}
            $products = $query->select(
                'p.id',
                'p.name',
                'p.code_article',
                'u.name as unite',
                'c.name as categorie',
                'sc.name as famille',
                'p.emplacement',
                's.quantite as stock',
                'p.price_achat',
                't.value as taux_taxe',
                'p.seuil',
                'p.code_barre',
                'p.photo',
                'p.date_expiration',
                DB::raw("CONCAT(us.prenom, ' ', us.nom) as username"),
                'p.created_at',
                'p.id_tva', 
                'p.id_unite' 
            )
            ->orderBy('p.id', 'desc');
    
            return DataTables::of($products)
                ->addIndexColumn()
                ->addColumn('action', function ($row) {
                    $btn = '';
                    if (auth()->user()->can('Products-modifier')) {
                        $btn .= '<a href="#" class="btn btn-sm bg-primary-subtle me-1 editProduct" data-id="'.$row->id.'">
                                <i class="fa-solid fa-pen-to-square text-primary"></i></a>';
                    }
                    if (auth()->user()->can('Products-supprimer')) {
                        $btn .= '<a href="#" class="btn btn-sm bg-danger-subtle deleteProduct" data-id="'.$row->id.'">
                                <i class="fa-solid fa-trash text-danger"></i></a>';
                    }
                    return $btn;
                })
                ->rawColumns(['action'])
                ->make(true);
        }
        
        // Get required data for dropdowns
        $categories = Category::all();
        $subcategories = SubCategory::all();
        $locals = Local::all();
        $rayons = Rayon::all();
        $tvas = Tva::all();
        $unites = Unite::all();
        $Fournisseur=Fournisseur::all();
        
        $class  = DB::select("select distinct(classe) as classe from categories");
        
        return view('products.index',
         compact('categories', 'subcategories', 'locals', 'rayons', 'tvas', 'unites','class','Fournisseur'));
    }

    /**
     * Get subcategories for a category.
     */
    public function getSubcategories($categoryId)
    {
        try {
            // Validate the category ID
            $validator = Validator::make(
                ['category_id' => $categoryId],
                ['category_id' => 'required|integer|exists:categories,id']
            );

            if ($validator->fails()) {
                return response()->json([
                    'status' => 400,
                    'message' => 'ID de catégorie invalide',
                    'subcategories' => []
                ], 400);
            }

            // Retrieve subcategories with eager loading to improve performance
            $subcategories = SubCategory::where('id_categorie', $categoryId)
                ->select('id', 'name')
                ->orderBy('name', 'asc')
                ->get();
            
            $Products = DB::table('products')->where('id_categorie',$categoryId)->get();
            
            // Log the retrieval for debugging
            Log::info('Subcategories retrieved', [
                'category_id' => $categoryId,
                'count' => $subcategories->count()
            ]);
            
            return response()->json([
                'status' => 200,
                'subcategories' => $subcategories,
                'products' => $Products
            ]);
        } catch (\Exception $e) {
            // Log the error with more context
            Log::error('Erreur lors de la récupération des sous-catégories', [
                'category_id' => $categoryId,
                'error_message' => $e->getMessage(),
                'error_trace' => $e->getTraceAsString()
            ]);
            
            return response()->json([
                'status' => 500,
                'message' => 'Erreur lors de la récupération des sous-catégories',
                'subcategories' => []
            ], 500);
        }
    }

    /**
     * Get rayons for a local.
     */
    public function getRayons($localId)
    {
        try {
            // Validate the local ID
            $validator = Validator::make(
                ['local_id' => $localId],
                ['local_id' => 'required|integer|exists:locals,id']
            );

            if ($validator->fails()) {
                return response()->json([
                    'status' => 400,
                    'message' => 'ID de local invalide',
                    'rayons' => []
                ], 400);
            }

            // Retrieve rayons with eager loading to improve performance
            $rayons = Rayon::where('id_local', $localId)
                ->select('id', 'name')
                ->orderBy('name', 'asc')
                ->get();
            
            // Log the retrieval for debugging
            Log::info('Rayons retrieved', [
                'local_id' => $localId,
                'count' => $rayons->count()
            ]);
            
            return response()->json([
                'status' => 200,
                'rayons' => $rayons
            ]);
        } catch (\Exception $e) {
            // Log the error with more context
            Log::error('Erreur lors de la récupération des rayons', [
                'local_id' => $localId,
                'error_message' => $e->getMessage(),
                'error_trace' => $e->getTraceAsString()
            ]);
            
            return response()->json([
                'status' => 500,
                'message' => 'Erreur lors de la récupération des rayons',
                'rayons' => []
            ], 500);
        }
    }

    /**
     * Store a newly created resource in storage.
     */
  
     public function store(Request $request)
     {
         // Check if user has permission to add products
         if (!auth()->user()->can('Products-ajoute')) {
             return response()->json([
                 'status' => 403,
                 'message' => 'Vous n\'avez pas la permission d\'ajouter des produits'
             ], 403);
         }
     
         $validator = Validator::make($request->all(), [
             'name' => 'required|string|max:255',
             'price_achat' => 'required|numeric',
             'class' => 'required|string|max:255',
             'id_categorie' => 'required|exists:categories,id',
             'id_subcategorie' => 'required|exists:sub_categories,id',
             'id_local' => 'required|exists:locals,id',
             'id_rayon' => 'required|exists:rayons,id',
             'id_unite' => 'required|exists:unite,id',
             'code_barre' => 'nullable|string|max:255',
             'photo' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
             'date_expiration' => 'nullable|date',
             'quantite' => 'required|numeric',
             'seuil' => 'required|numeric',
             'id_tva' => 'nullable|exists:tvas,id',
         ], [
             'required' => 'Le champ :attribute est requis.',
             'numeric' => 'Le champ :attribute doit être un nombre.',
             'exists' => 'La valeur sélectionnée pour :attribute est invalide.',
             'image' => 'Le fichier doit être une image.',
             'mimes' => 'Le fichier doit être du type: :values.',
             'max' => 'Le fichier ne doit pas dépasser :max kilo-octets.',
             'date' => 'Le champ :attribute doit être une date valide.'
         ]);
         
         if ($validator->fails()) {
             return response()->json([
                 'status' => 400,
                 'errors' => $validator->messages(),
             ], 400);
         }
     
         // Vérifier si un produit avec ce nom existe déjà (insensible à la casse)
         /* $cleanedName = strtolower(trim($request->name));
         $nameExists = Product::whereRaw('LOWER(TRIM(name)) = ?', [$cleanedName])->count();
         
         if ($nameExists > 0) {
             return response()->json([
                 'status' => 422,
                 'message' => 'Un produit avec ce nom existe déjà',
             ], 422);
         } */
     
         try {
             
             DB::beginTransaction();
             
             // Verify the relationship between category and subcategory
             $subcategory = SubCategory::find($request->id_subcategorie);
             
             if ($subcategory->id_categorie != $request->id_categorie) {
                 return response()->json([
                     'status' => 400,
                     'message' => 'La famille sélectionnée n\'appartient pas à cette catégorie',
                 ], 400);
             }
            
             
             // Verify the relationship between local and rayon
             $rayon = Rayon::find($request->id_rayon);
             if ($rayon->id_local != $request->id_local) {
                 return response()->json([
                     'status' => 400,
                     'message' => 'Le rayon sélectionné n\'appartient pas à ce local',
                 ], 400);
             }
             
             // Get category and subcategory names for code generation
             $category = Category::find($request->id_categorie);
             
             // Generate code_article
             $code_article = Product::generateCodeArticle(
                 $category->name, 
                 $subcategory->name
             );

            
             
             // Handle file upload
             $photoPath = null;
             if ($request->hasFile('photo')) {
                 $photoFile = $request->file('photo');
                 $filename = time() . '_' . $photoFile->getClientOriginalName();
                 $photoPath = $photoFile->storeAs('product_photos', $filename, 'public');
             }
             
             // Process date_expiration field - ensure it's correctly handled if empty
             $dateExpiration = $request->date_expiration;
             if (empty($dateExpiration)) {
                 $dateExpiration = null;
             }
             $idTva = $request->filled('id_tva') ? $request->id_tva : 1;

            

             /************************** Youssef  *******************/
            $dateExpiration = $request->date_expiration;
            $dateReception  = $request->date_reception;
            $priceAchat     = $request->price_achat;

           

            $existingProduct = Product::where('name', trim($request->name))
            ->where('date_expiration', $dateExpiration)
            ->where('price_achat', $priceAchat)
            ->where('date_reception', $dateReception)
            ->first();
             
            //dd($dateReception,$existingProduct);
            if ($existingProduct) 
            {
                
                $stock = Stock::where('id_product', $existingProduct->id)->first();

                if ($stock) {
                    $stock->quantite += $request->quantite;
                    $stock->save();
                } else {
                    // if no stock record exists, create a new one
                    Stock::create([
                        'id_product' => $existingProduct->id,
                        'id_tva'     => $request->id_tva,
                        'id_unite'   => $request->id_unite,
                        'quantite'   => $request->quantite,
                    ]);
                }

                DB::commit();

                return response()->json([
                    'status'  => 200,
                    'message' => 'Quantity updated successfully for existing product.',
                    'product' => $existingProduct,
                ]);
            }
             
             /************************** End youssef check product **********/
             // Create product with the new foreign keys
             $product = Product::create([
                 'name' => trim($request->name),
                 'code_article' => $code_article,
                 'price_achat' => $request->price_achat,
                 'code_barre' => $request->code_barre,
                 'photo' => $photoPath,
                 'date_expiration' => $dateExpiration,
                 'class' => $request->class,
                 'id_categorie' => $request->id_categorie,
                 'id_subcategorie' => $request->id_subcategorie,
                 'seuil' => $request->seuil,
                 'id_local' => $request->id_local,
                 'id_rayon' => $request->id_rayon,
                 'id_tva' => $idTva,       
                 'id_unite' => $request->id_unite,   
                 'id_user' => Auth::id(),
                 'date_reception'    => $dateReception,
             ]);

             //dd($request->all());
            
     
             
             // Update emplacement after creating product
             $product->emplacement = $product->generateEmplacement();
             $product->save();
             
             // Create stock entry - keep all fields as before, including id_tva and id_unite
             Stock::create([
                 'id_product' => $product->id,
                 'id_tva' => $request->id_tva,     // Keep in stock table
                 'id_unite' => $request->id_unite, // Keep in stock table
                 'quantite' => $request->quantite,
             ]);
             
             DB::commit(); 
             
             return response()->json([
                 'status' => 200,
                 'message' => 'Produit créé avec succès',
             ]);
             
         } catch (\Exception $e) {
             DB::rollBack();
             
             Log::error('Error creating product: ' . $e->getMessage(), [
                 'request' => $request->all()
             ]);
             
             return response()->json([
                 'status' => 500,
                 'message' => 'Une erreur est survenue. Veuillez réessayer.',
             ], 500);
         }
     }

  
   /**
 * Show the form for editing the specified resource.
 */
public function edit($id)
{
    // Check if user has permission to modify products
    if (!auth()->user()->can('Products-modifier')) {
        return response()->json([
            'status' => 403,
            'message' => 'Vous n\'avez pas la permission de modifier des produits'
        ], 403);
    }

    try {
        // Using with() to eager load related models
        $product = Product::with(['stock', 'category', 'subcategory', 'local', 'rayon'])
            ->select(
                'id', 
                'name', 
                'code_article', 
                'price_achat', 
                'code_barre',
                'emplacement',
                'seuil',
                'photo',
                'date_expiration', 
                'class',
                'id_categorie',
                'id_subcategorie',
                'id_local',
                'id_rayon',
                'id_tva',
                'id_unite'
            )
            ->find($id);
        
        if (!$product) {
            return response()->json([
                'status' => 404,
                'message' => 'Produit non trouvé',
            ], 404);
        }

        // Debug log to verify data is being retrieved correctly
        Log::info('Product data for edit:', [
            'product_id' => $id,
            'photo' => $product->photo, // Added photo to the debug log
            'date_expiration' => $product->date_expiration
        ]);
        
        return response()->json($product);
        
    } catch (\Exception $e) {
        Log::error('Error retrieving product for edit: ' . $e->getMessage(), [
            'id' => $id,
            'trace' => $e->getTraceAsString() // Added stack trace for better debugging
        ]);
        
        return response()->json([
            'status' => 500,
            'message' => 'Une erreur est survenue. Veuillez réessayer.',
        ], 500);
    }
}


  /**
 * Update the specified resource in storage.
 */
public function update(Request $request)
{
     // Check if user has permission to modify products
    if (!auth()->user()->can('Products-modifier')) {
        return response()->json([
            'status' => 403,
            'message' => 'Vous n\'avez pas la permission de modifier des produits'
        ], 403);
    }

    $validator = Validator::make($request->all(), [
        'id' => 'required|exists:products,id',
        'name' => 'required|string|max:255',
        'price_achat' => 'required|numeric',
        'id_categorie' => 'required|exists:categories,id',
        'id_subcategorie' => 'required|exists:sub_categories,id',
        'id_local' => 'required|exists:locals,id',
        'id_rayon' => 'required|exists:rayons,id',
        'id_unite' => 'required|exists:unite,id',
        'code_barre' => 'nullable|string|max:255',
        'photo' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
        'current_photo_path' => 'nullable|string',
        'class' => 'required|string|max:255',
        'date_expiration' => 'nullable|date',
        'quantite' => 'required|numeric',
        'seuil' => 'required|numeric',
        'id_tva' => 'nullable|exists:tvas,id',
    ], [
        'required' => 'Le champ :attribute est requis.',
        'numeric' => 'Le champ :attribute doit être un nombre.',
        'exists' => 'La valeur sélectionnée pour :attribute est invalide.',
        'image' => 'Le fichier doit être une image.',
        'mimes' => 'Le fichier doit être du type: :values.',
        'max' => 'Le fichier ne doit pas dépasser :max kilo-octets.',
        'date' => 'Le champ :attribute doit être une date valide.'
    ]);
    
    if ($validator->fails()) {
        return response()->json([
            'status' => 400,
            'errors' => $validator->messages(),
        ], 400);
    }
    
    try {
        DB::beginTransaction();
        
        $product = Product::find($request->id);
        
        if (!$product) {
            return response()->json([
                'status' => 404,
                'message' => 'Produit non trouvé',
            ], 404);
        }
         
        // Vérifier si un produit avec ce nom existe déjà (insensible à la casse)
        $cleanedName = strtolower(trim($request->name));
        $nameExists = Product::whereRaw('LOWER(TRIM(name)) = ?', [$cleanedName])
                      ->where('id', '!=', $request->id)
                      ->count();
        
        if ($nameExists > 0) {
            return response()->json([
                'status' => 422,
                'message' => 'Un produit avec ce nom existe déjà',
            ], 422);
        }
        if(is_null($request->id_categorie) || is_null($request->id_subcategorie))
        {
            return response()->json([
                'status'   => 405,
                'message'  => 'Please selected category or subcategory',
            ]);
        }

         
        // Verify the relationship between category and subcategory
        $subcategory = SubCategory::find($request->id_subcategorie);
        if ($subcategory->id_categorie != $request->id_categorie) {
            return response()->json([
                'status' => 400,
                'message' => 'La famille sélectionnée n\'appartient pas à cette catégorie',
            ], 400);
        }
        
        // Verify the relationship between local and rayon
        $rayon = Rayon::find($request->id_rayon);
        if ($rayon->id_local != $request->id_local) {
            return response()->json([
                'status' => 400,
                'message' => 'Le rayon sélectionné n\'appartient pas à ce local',
            ], 400);
        }
       
        // Check if category or subcategory changed
        $categoryChanged = $product->id_categorie != $request->id_categorie;
        $subcategoryChanged = $product->id_subcategorie != $request->id_subcategorie;
        
        // If category or subcategory changed, only update the prefix part of the code_article 
        // but keep the same sequential number
        if ($categoryChanged || $subcategoryChanged) {
            $category = Category::find($request->id_categorie);
            
            // Clean and normalize the strings to get prefixes
            $categoryName = Str::of($category->name)->trim()->lower()->ascii();
            $subcategoryName = Str::of($subcategory->name)->trim()->lower()->ascii();
            
            // Get first 3 letters of each
            $categoryPrefix = Str::substr($categoryName, 0, 3);
            $subcategoryPrefix = Str::substr($subcategoryName, 0, 3);
            
            // Get the current sequence number from the existing code
            $currentSequence = Str::substr($product->code_article, -3);
            
            // Create the new code by combining new prefixes with the existing sequence
            $product->code_article = $categoryPrefix . $subcategoryPrefix . $currentSequence;
        }
        
        // Handle file upload or keep existing photo
        $photoPath = $product->photo; // Default to current photo
        
        if ($request->hasFile('photo')) {
            $photoFile = $request->file('photo');
            $filename = time() . '_' . $photoFile->getClientOriginalName();
            $photoPath = $photoFile->storeAs('product_photos', $filename, 'public');
            
            // Log photo update
            Log::info('Product photo updated: ' . $photoPath);
            
            // Delete old photo if exists
            if ($product->photo && file_exists(storage_path('app/public/' . $product->photo))) {
                unlink(storage_path('app/public/' . $product->photo));
                Log::info('Deleted old photo: ' . $product->photo);
            }
        } elseif ($request->filled('current_photo_path')) {
            // Keep the current photo path if it's not empty
            $photoPath = $request->current_photo_path;
            Log::info('Keeping existing photo: ' . $photoPath);
        }
        
        // Log update data
        Log::info('Updating product with data:', [
            'id' => $request->id,
            'photo' => $photoPath,
            'date_expiration' => $request->date_expiration
        ]);
        $idTva = $request->filled('id_tva') ? $request->id_tva : 1;
        
        // Update product with the new values
        $product->update([
            'name' => trim($request->name),
            'price_achat' => $request->price_achat,
            'code_barre' => $request->code_barre,
            'photo' => $photoPath,
            'date_expiration' => $request->date_expiration,
            'class' => $request->class,
            'id_categorie' => $request->id_categorie,
            'id_subcategorie' => $request->id_subcategorie,
            'id_local' => $request->id_local,
            'id_rayon' => $request->id_rayon,
            'id_tva' => $request->id_tva,
            'id_unite' => $request->id_unite,
            'seuil' => $request->seuil,
        ]);
        
        // Update emplacement after updating product
        $product->emplacement = $product->generateEmplacement();
        $product->save();
        
        // Update or create stock
        $stock = Stock::where('id_product', $product->id)->first();
        
        if ($stock) {
            $stock->update([
                'id_tva' => $request->id_tva,
                'id_unite' => $request->id_unite,
                'quantite' => $request->quantite,
            ]);
        } else {
            Stock::create([
                'id_product' => $product->id,
                'id_tva' => $request->id_tva,
                'id_unite' => $request->id_unite,
                'quantite' => $request->quantite,
            ]);
        }
        
        DB::commit();
        
        return response()->json([
            'status' => 200,
            'message' => 'Produit mis à jour avec succès',
        ]);
        
    } catch (\Exception $e) {
        DB::rollBack();
        
        Log::error('Error updating product: ' . $e->getMessage(), [
            'id' => $request->id,
            'request' => $request->all(),
            'trace' => $e->getTraceAsString()
        ]);
        
        return response()->json([
            'status' => 500,
            'message' => 'Une erreur est survenue. Veuillez réessayer.',
            'error' => $e->getMessage()
        ], 500);
    }
}

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Request $request)
    {
        // Check if user has permission to delete products
        if (!auth()->user()->can('Products-supprimer')) {
            return response()->json([
                'status' => 403,
                'message' => 'Vous n\'avez pas la permission de supprimer des produits'
            ], 403);
        }

        try {
            DB::beginTransaction();
            
            $product = Product::find($request->id);
            
            if (!$product) {
                return response()->json([
                    'status' => 404,
                    'message' => 'Produit non trouvé',
                ], 404);
            }
            
            $productName = $product->name;
            $productId = $product->id;
            
            // Delete stock first (foreign key constraint)
            Stock::where('id_product', $product->id)->delete();
            
            // Delete product
            $product->delete();
            
            DB::commit();
            
            return response()->json([
                'status' => 200,
                'message' => 'Produit supprimé avec succès',
            ]);
            
        } catch (\Exception $e) {
            DB::rollBack();
            
            Log::error('Error deleting product: ' . $e->getMessage(), [
                'id' => $request->id
            ]);
            
            return response()->json([
                'status' => 500,
                'message' => 'Une erreur est survenue. Veuillez réessayer.',
            ], 500);
        }
    }


  
    
public function import(Request $request)
{
    // Add debug logging
    Log::info('Starting import process');
    
    // Check if user has permission to add products
    if (!auth()->user()->can('Products-ajoute')) {
        return response()->json([
            'status' => 403,
            'message' => 'Vous n\'avez pas la permission d\'importer des produits'
        ], 403);
    }

    // Add tracking for skipped reasons
    $skippedReasons = [
        'missing_fields' => 0,
        'duplicate_name' => 0,
        'category_not_found' => 0,
        'subcategory_not_found' => 0,
        'local_not_found' => 0,
        'rayon_not_found' => 0,
        'unite_not_found' => 0,
        'other_errors' => 0
    ];

    $validator = Validator::make($request->all(), [
        'file' => 'required|mimes:xlsx,xls,csv|max:10240', // 10MB limit
    ], [
        'required' => 'Le fichier est requis.',
        'mimes' => 'Le fichier doit être de type: xlsx, xls ou csv.',
        'max' => 'La taille du fichier ne doit pas dépasser 10MB.',
    ]);
    
    if ($validator->fails()) {
        return response()->json([
            'status' => 400,
            'errors' => $validator->messages(),
        ], 400);
    }

    try {
        // Increase memory limit and execution time for large files
        ini_set('memory_limit', '512M');
        ini_set('max_execution_time', 600); // 10 minutes
        
        // Instead of using storeAs which puts files in private storage
        $file = $request->file('file');
        $originalExtension = $file->getClientOriginalExtension();
        
        // Get the temporary uploaded file path directly
        $tempPath = $file->getRealPath();
        
        // Create a CSV Reader instance right from the temp file
        if ($originalExtension == 'csv') {
            // Direct CSV handling
            $csv = Reader::createFromPath($tempPath, 'r');
        } else {
            // If Excel file, convert to CSV in memory using PhpSpreadsheet
            $reader = \PhpOffice\PhpSpreadsheet\IOFactory::createReader(
                $originalExtension == 'xlsx' ? 'Xlsx' : 'Xls'
            );
            $spreadsheet = $reader->load($tempPath);
            
            // Create a temporary CSV file in the system temp directory
            $csvPath = sys_get_temp_dir() . '/temp_import_' . time() . '.csv';
            $writer = \PhpOffice\PhpSpreadsheet\IOFactory::createWriter($spreadsheet, 'Csv');
            $writer->save($csvPath);
            
            $csv = Reader::createFromPath($csvPath, 'r');
        }
        
        $imported = 0;
        $skipped = 0;
        $duplicates = [];
        $notFoundCategories = [];
        $notFoundSubCategories = [];
        $notFoundLocals = [];
        $notFoundRayons = [];
        $notFoundUnites = [];

        // Set CSV header and options
        $csv->setHeaderOffset(0); // First row contains headers
        $csv->setDelimiter(',');
        
        // Create a Statement to efficiently process the CSV in chunks
        $stmt = Statement::create();
        $records = $stmt->process($csv);
        
        // Log total rows
        Log::info('Total rows in file: ' . count($records));
        
        // Get headers for mapping
        $headers = $csv->getHeader();
        $headersLower = array_map('strtolower', $headers);
        
        // Map column indices for easier access - Make case-insensitive column mapping
        // REMOVED 'tva' from column mappings
        $columnMappings = [
            'name' => ['designation', 'nom', 'name'],
            'price' => ['prix_achat', 'prix', 'price'],
            'categorie' => ['categorie', 'category'],
            'sousCategorie' => ['famille', 'sous-categorie', 'subcategory'],
            'local' => ['local'],
            'rayon' => ['rayon'],
            'quantite' => ['quantite', 'stock', 'quantity'],
            'seuil' => ['seuil', 'threshold'],
            'unite' => ['unite', 'unit'],
            'codeBarre' => ['code_barre', 'barcode'],
            'dateExpiration' => ['date_expiration', 'expiration_date'],
            'codeArticle' => ['code_article', 'product_code']
        ];
        
        // Find indices for all needed columns
        $indices = [];
        foreach ($columnMappings as $key => $possibleNames) {
            $indices[$key] = null;
            foreach ($possibleNames as $name) {
                $index = array_search(strtolower($name), $headersLower);
                if ($index !== false) {
                    $indices[$key] = $index;
                    break;
                }
            }
        }
        
        // Check for required columns (removed 'tva' from required)
        $requiredColumns = [
            'name' => $indices['name'] !== null,
            'price' => $indices['price'] !== null,
            'categorie' => $indices['categorie'] !== null,
            'sousCategorie' => $indices['sousCategorie'] !== null,
            'local' => $indices['local'] !== null,
            'rayon' => $indices['rayon'] !== null,
            'quantite' => $indices['quantite'] !== null,
            'unite' => $indices['unite'] !== null,
        ];
        
        $missingColumns = [];
        foreach ($requiredColumns as $name => $exists) {
            if (!$exists) {
                $missingColumns[] = $name;
            }
        }
        
        if (!empty($missingColumns)) {
            return response()->json([
                'status' => 400,
                'message' => 'Colonnes obligatoires manquantes: ' . implode(', ', $missingColumns),
            ], 400);
        }
        
        // Begin DB transaction
        DB::beginTransaction();
        
        // Use small batches for commits
        $batchSize = 100;
        $batchCount = 0;
        $totalRows = 0;
        
        // Process records
        foreach ($records as $rowIndex => $row) {
            $totalRows++;
            $rowNum = $rowIndex + 2; // +2 because row 1 is header and indices start at 0
            
            // Extract data from the row by header name
            $name = trim($row[$headers[$indices['name']]] ?? '');
            $price = floatval($row[$headers[$indices['price']]] ?? 0);
            $categorieName = trim($row[$headers[$indices['categorie']]] ?? '');
            $sousCategoryName = trim($row[$headers[$indices['sousCategorie']]] ?? '');
            $localName = trim($row[$headers[$indices['local']]] ?? '');
            $rayonName = trim($row[$headers[$indices['rayon']]] ?? '');
            $quantite = floatval($row[$headers[$indices['quantite']]] ?? 0);
            $seuil = isset($indices['seuil']) && isset($row[$headers[$indices['seuil']]]) ? floatval($row[$headers[$indices['seuil']]]) : 0;
            $uniteName = trim($row[$headers[$indices['unite']]] ?? '');
            
            // Log row processing
            Log::debug('Processing row ' . $rowNum, [
                'name' => $name,
                'price' => $price,
                'categorie' => $categorieName,
                'famille' => $sousCategoryName,
                'local' => $localName,
                'rayon' => $rayonName,
                'quantite' => $quantite,
                'unite' => $uniteName
            ]);
            
            // Handle optional fields
            $codeBarre = isset($indices['codeBarre']) && isset($row[$headers[$indices['codeBarre']]]) && !empty($row[$headers[$indices['codeBarre']]]) 
                ? trim($row[$headers[$indices['codeBarre']]]) 
                : null;
            
            // Improved date handling
            $dateExpiration = null;
            if (isset($indices['dateExpiration']) && isset($row[$headers[$indices['dateExpiration']]])) {
                $dateValue = trim($row[$headers[$indices['dateExpiration']]]);
                if (!empty($dateValue)) {
                    try {
                        // Try multiple date formats
                        $dateObj = \DateTime::createFromFormat('d/m/Y', $dateValue);
                        if (!$dateObj) {
                            $dateObj = \DateTime::createFromFormat('Y-m-d', $dateValue);
                        }
                        if (!$dateObj) {
                            $dateObj = new \DateTime($dateValue);
                        }
                        if ($dateObj) {
                            $dateExpiration = $dateObj->format('Y-m-d');
                        }
                    } catch (\Exception $e) {
                        Log::debug('Date parse error: ' . $e->getMessage() . ' for value: ' . $dateValue);
                        $dateExpiration = null;
                    }
                }
            }
            
            // Get code_article from CSV if available
            $code_article = null;
            if (isset($indices['codeArticle']) && isset($row[$headers[$indices['codeArticle']]]) && !empty($row[$headers[$indices['codeArticle']]])) {
                $code_article = trim($row[$headers[$indices['codeArticle']]]);
            }
            
            // Skip empty rows or invalid data (removed tvaValue check)
            if (empty($name) || $price <= 0 || empty($categorieName) || empty($sousCategoryName) || 
                empty($localName) || empty($rayonName) || $quantite < 0 || empty($uniteName)) {
                Log::debug('Skipping row with missing required fields', [
                    'row' => $rowNum,
                    'name' => $name,
                    'price' => $price,
                    'categorie' => $categorieName,
                ]);
                $skipped++;
                $skippedReasons['missing_fields']++;
                
                // Log skip
                Log::info('Row skipped. Current counts: imported=' . $imported . ', skipped=' . $skipped);
                
                continue;
            }
            
            // Check if product with this name already exists - CASE INSENSITIVE
            $nameExists = Product::whereRaw('LOWER(TRIM(name)) = ?', [strtolower(trim($name))])->exists();
            
            if ($nameExists) {
                $duplicates[] = $name;
                $skipped++;
                $skippedReasons['duplicate_name']++;
                
                // Log skip
                Log::info('Row skipped. Current counts: imported=' . $imported . ', skipped=' . $skipped);
                
                continue;
            }
            
            // Commit in batches to avoid transaction timeout
            if ($batchCount > 0 && $batchCount % $batchSize === 0) {
                DB::commit();
                DB::beginTransaction();
            }
            
            try {
                // CASE INSENSITIVE LOOKUP FOR ALL RELATED ENTITIES
                
                // Find category ID - CASE INSENSITIVE
                $category = Category::whereRaw('LOWER(TRIM(name)) = ?', [strtolower(trim($categorieName))])->first();
                if (!$category) {
                    if (!in_array($categorieName, $notFoundCategories)) {
                        $notFoundCategories[] = $categorieName;
                    }
                    $skipped++;
                    $skippedReasons['category_not_found']++;
                    
                    // Log skip
                    Log::info('Row skipped. Current counts: imported=' . $imported . ', skipped=' . $skipped);
                    
                    continue;
                }
                
                // Find subcategory ID - CASE INSENSITIVE
                $subcategory = SubCategory::whereRaw('LOWER(TRIM(name)) = ?', [strtolower(trim($sousCategoryName))])
                    ->where('id_categorie', $category->id)
                    ->first();
                
                if (!$subcategory) {
                    $notFoundSubCategories[] = $sousCategoryName . ' (dans categorie ' . $categorieName . ')';
                    $skipped++;
                    $skippedReasons['subcategory_not_found']++;
                    
                    // Log skip
                    Log::info('Row skipped. Current counts: imported=' . $imported . ', skipped=' . $skipped);
                    
                    continue;
                }
                
                // Find local ID - CASE INSENSITIVE
                $local = Local::whereRaw('LOWER(TRIM(name)) = ?', [strtolower(trim($localName))])->first();
                if (!$local) {
                    $notFoundLocals[] = $localName;
                    $skipped++;
                    $skippedReasons['local_not_found']++;
                    
                    // Log skip
                    Log::info('Row skipped. Current counts: imported=' . $imported . ', skipped=' . $skipped);
                    
                    continue;
                }
                
                // Find rayon ID - CASE INSENSITIVE
                $rayon = Rayon::whereRaw('LOWER(TRIM(name)) = ?', [strtolower(trim($rayonName))])
                    ->where('id_local', $local->id)
                    ->first();
                
                if (!$rayon) {
                    $notFoundRayons[] = $rayonName . ' (dans local ' . $localName . ')';
                    $skipped++;
                    $skippedReasons['rayon_not_found']++;
                    
                    // Log skip
                    Log::info('Row skipped. Current counts: imported=' . $imported . ', skipped=' . $skipped);
                    
                    continue;
                }
                
                // Find unite ID - CASE INSENSITIVE
                $unite = Unite::whereRaw('LOWER(TRIM(name)) = ?', [strtolower(trim($uniteName))])->first();
                if (!$unite) {
                    $notFoundUnites[] = $uniteName;
                    $skipped++;
                    $skippedReasons['unite_not_found']++;
                    
                    // Log skip
                    Log::info('Row skipped. Current counts: imported=' . $imported . ', skipped=' . $skipped);
                    
                    continue;
                }
                
                // ALWAYS USE id_tva = 1 for all imported products
                $tvaId = 1;
                
                // Generate code_article only if not provided in CSV
                if (empty($code_article)) {
                    $code_article = Product::generateCodeArticle(
                        $category->name, 
                        $subcategory->name
                    );
                }
                
                // Create product
                $product = Product::create([
                    'name' => $name,
                    'code_article' => $code_article,
                    'price_achat' => $price,
                    'id_categorie' => $category->id,
                    'id_subcategorie' => $subcategory->id,
                    'id_local' => $local->id,
                    'id_rayon' => $rayon->id,
                    'seuil' => $seuil,
                    'code_barre' => $codeBarre,
                    'photo' => null, // Always set photo to null during import
                    'date_expiration' => $dateExpiration,
                    'id_tva' => $tvaId,       // Always 1
                    'id_unite' => $unite->id,
                    'id_user' => Auth::id(),
                ]);
                
                // Update emplacement
                $product->emplacement = $product->generateEmplacement();
                $product->save();
                
                // Create stock entry
                Stock::create([
                    'id_product' => $product->id,
                    'id_tva' => $tvaId,       // Always 1
                    'id_unite' => $unite->id,
                    'quantite' => $quantite,
                ]);
                
                $imported++;
                $batchCount++;
                
            } catch (\Exception $e) {
                Log::error('Erreur importation produit ligne ' . $rowNum . ': ' . $e->getMessage(), [
                    'trace' => $e->getTraceAsString()
                ]);
                $skipped++;
                $skippedReasons['other_errors']++;
                
                // Log skip
                Log::info('Row skipped. Current counts: imported=' . $imported . ', skipped=' . $skipped);
                
                continue;
            }
            
            // Free memory periodically
            if ($totalRows % 100 == 0) {
                gc_collect_cycles();
            }
        }
        
        // Log final statistics
        Log::info('Import completed. Final counts: imported=' . $imported . ', skipped=' . $skipped);
        Log::info('Skipped reasons:', $skippedReasons);
        
        // Commit any remaining records
        if ($imported > 0) {
            DB::commit();
        } else {
            DB::rollBack();
        }
        
        // Clean up temporary files if created
        if (isset($csvPath) && file_exists($csvPath)) {
            @unlink($csvPath);
        }
        
        $message = $imported . ' produits ont été importés avec succès.';
        if ($skipped > 0) {
            $message .= ' ' . $skipped . ' ont été ignorés (doublons, données invalides ou relations inexistantes).';
        }
        
        if (!empty($notFoundCategories)) {
            $message .= ' Catégories non trouvées: ' . implode(', ', array_unique($notFoundCategories)) . '.';
        }
        
        if (!empty($notFoundSubCategories)) {
            $message .= ' Familles non trouvées: ' . implode(', ', array_unique($notFoundSubCategories)) . '.';
        }
        
        if (!empty($notFoundLocals)) {
            $message .= ' Locaux non trouvés: ' . implode(', ', array_unique($notFoundLocals)) . '.';
        }
        
        if (!empty($notFoundRayons)) {
            $message .= ' Rayons non trouvés: ' . implode(', ', array_unique($notFoundRayons)) . '.';
        }
        
        if (!empty($notFoundUnites)) {
            $message .= ' Unités non trouvées: ' . implode(', ', array_unique($notFoundUnites)) . '.';
        }
        
        return response()->json([
            'status' => 200,
            'message' => $message,
            'imported' => $imported,
            'skipped' => $skipped,
            'duplicates' => $duplicates,
            'total_rows' => $totalRows,
            'skipped_reasons' => $skippedReasons
        ]);
        
    } catch (\Exception $e) {
        DB::rollBack();
        
        Log::error('Erreur lors de l\'importation des produits: ' . $e->getMessage(), [
            'trace' => $e->getTraceAsString()
        ]);
        
        return response()->json([
            'status' => 500,
            'message' => 'Une erreur est survenue lors de l\'importation: ' . $e->getMessage(),
            'line' => $e->getLine(),
            'file' => $e->getFile()
        ], 500);
    }
}
    /**
 * Search product names for autocomplete
 */
public function searchProductNames(Request $request)
{
    try {
        $query = $request->get('query', '');
        
        if (strlen($query) < 2) {
            return response()->json([
                'status' => 200,
                'products' => []
            ]);
        }
        
        $products = Product::where('name', 'LIKE', '%' . $query . '%')
            ->whereNull('deleted_at')
            ->select('id', 'name')
            ->limit(10)
            ->get();
        
        return response()->json([
            'status' => 200,
            'products' => $products
        ]);
        
    } catch (\Exception $e) {
        Log::error('Error searching product names', [
            'error' => $e->getMessage()
        ]);
        
        return response()->json([
            'status' => 500,
            'message' => 'Erreur lors de la recherche',
            'products' => []
        ], 500);
    }
}

    public function GetProductByFamaille(Request $request)
    {
        $Products = DB::table('products')->where('id_subcategorie',$request->id_sub_category)->get();
        if($Products)
        {
            return response()->json([
                'status'     => 200,
                'products'    => $Products
            ]);
        }
    }


   public function getUnitebyProduct(Request $request)
{
    $id_unite = DB::table('products')
        ->where('id', $request->product)
        ->select('id_unite')
        ->first();

    if (!$id_unite) {
        return response()->json([
            'status' => 404,
            'message' => 'Product not found',
        ]);
    }

    $unite = DB::table('unite')
        ->where('id', $id_unite->id_unite)
        ->first();

    return response()->json([
        'status' => 200,
        'unite'  => $unite,
    ]);
}

}