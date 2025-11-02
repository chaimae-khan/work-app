<?php

namespace App\Http\Controllers;

use App\Models\Category;
use Illuminate\View\View;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Validator;
use Yajra\DataTables\Facades\DataTables;
use Illuminate\Support\Facades\DB;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class CategoriesController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        if ($request->ajax()) {
            $dataCategory = DB::table('categories as c')
                ->join('users as us', 'us.id', '=', 'c.iduser')
                ->whereNull('c.deleted_at')
                ->select(
                    'c.id',
                    'c.name',
                    'c.classe', // Added classe field
                    DB::raw("CONCAT(us.prenom, ' ', us.nom) as username"),
                    'c.created_at'
                )
                ->orderBy('c.id', 'desc');
    
            return DataTables::of($dataCategory)
                ->addIndexColumn()
                ->filterColumn('username', function($query, $keyword) {
                    $query->whereRaw("LOWER(CONCAT(us.prenom, ' ', us.nom)) LIKE ?", ["%".strtolower($keyword)."%"]);
                })
                ->addColumn('action', function ($row) {
                    $btn = '';
    
                    if (auth()->user()->can('Categories-modifier')) {
                        // Edit button
                        $btn .= '<a href="#" class="btn btn-sm bg-primary-subtle me-1 editCategory"
                                    data-id="' . $row->id . '">
                                    <i class="fa-solid fa-pen-to-square text-primary"></i>
                                </a>';
                    }
    
                    if (auth()->user()->can('Categories-supprimer')) {
                        // Delete button
                        $btn .= '<a href="#" class="btn btn-sm bg-danger-subtle deleteCategory"
                                    data-id="' . $row->id . '" data-bs-toggle="tooltip" 
                                    title="Supprimer Catégorie">
                                    <i class="fa-solid fa-trash text-danger"></i>
                                </a>';
                    }
    
                    return $btn;
                })
                ->rawColumns(['action'])
                ->make(true);
        }
             
        return view('categories.index');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        // Check if user has permission to add categories
        if (!auth()->user()->can('Categories-ajoute')) {
            return response()->json([
                'status' => 403,
                'message' => 'Vous n\'avez pas la permission d\'ajouter des catégories'
            ], 403);
        }
    
        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:255',
            'classe' => 'nullable|string|max:255', // Added validation for classe
        ], [
            'required' => 'Le champ :attribute est requis.',
        ], [
            'name' => 'nom',
            'classe' => 'classe',
        ]);
        
        if ($validator->fails()) {
            return response()->json([
                'status' => 400,
                'errors' => $validator->messages(),
            ], 400);
        }
    
        // Vérification insensible à la casse et en supprimant les espaces
        $cleanedName = strtolower(trim($request->name));
        $exists = Category::whereRaw('LOWER(TRIM(name)) = ?', [$cleanedName])->count();
            
        if ($exists > 0) {
            return response()->json([
                'status' => 422, // Utilisation de 422 pour être cohérent avec les autres fonctions
                'message' => 'Cette catégorie existe déjà',
            ], 422);
        }
    
        $category = Category::create([
            'name' => trim($request->name), // Suppression des espaces
            'classe' => $request->classe, // Added classe field
            'iduser' => Auth::user()->id,
        ]);
    
        if($category) {
            return response()->json([
                'status' => 200,
                'message' => 'Catégorie créée avec succès',
            ]);
        } else { 
            return response()->json([
                'status' => 500,
                'message' => 'Une erreur est survenue. Veuillez réessayer.'
            ]);
        }
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit($id)
    {
        // Check if user has permission to modify categories
        if (!auth()->user()->can('Categories-modifier')) {
            return response()->json([
                'status' => 403,
                'message' => 'Vous n\'avez pas la permission de modifier des catégories'
            ], 403);
        }

        $category = Category::find($id);
        
        if (!$category) {
            return response()->json([
                'status' => 404,
                'message' => 'Catégorie non trouvée'
            ], 404);
        }
        
        return response()->json($category);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request)
    {
        // Check if user has permission to modify categories
        if (!auth()->user()->can('Categories-modifier')) {
            return response()->json([
                'status' => 403,
                'message' => 'Vous n\'avez pas la permission de modifier des catégories'
            ], 403);
        }
    
        $category = Category::find($request->id);
        
        if (!$category) {
            return response()->json([
                'status' => 404,
                'message' => 'Catégorie non trouvée'
            ], 404);
        }
        
        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:255',
            'classe' => 'nullable|string|max:255', // Added validation for classe
        ], [
            'required' => 'Le champ :attribute est requis.',
        ], [
            'name' => 'nom',
            'classe' => 'classe',
        ]);
        
        if ($validator->fails()) {
            return response()->json([
                'status' => 400,
                'errors' => $validator->messages(),
            ], 400);
        }
        
        // Vérification insensible à la casse et en supprimant les espaces
        $cleanedName = strtolower(trim($request->name));
        $exists = Category::whereRaw('LOWER(TRIM(name)) = ?', [$cleanedName])
            ->where('id', '!=', $request->id) // Exclure l'enregistrement actuel
            ->count();
            
        if ($exists > 0) {
            return response()->json([
                'status' => 422, // Utilisation de 422 pour être cohérent avec les autres fonctions
                'message' => 'Cette catégorie existe déjà',
            ], 422);
        }
    
        $category->name = trim($request->name); // Suppression des espaces
        $category->classe = $request->classe; // Added classe field update
        $saved = $category->save();
        
        if ($saved) {
            return response()->json([
                'status' => 200,
                'message' => 'Catégorie mise à jour avec succès',
            ]);
        } else {
            return response()->json([
                'status' => 500,
                'message' => 'Erreur lors de la mise à jour de la catégorie',
            ], 500);
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Request $request)
    {
        // Check if user has permission to delete categories
        if (!auth()->user()->can('Categories-supprimer')) {
            return response()->json([
                'status' => 403,
                'message' => 'Vous n\'avez pas la permission de supprimer des catégories'
            ], 403);
        }

        $category = Category::find($request->id);

        if (!$category) {
            return response()->json([
                'status' => 404,
                'message' => 'Catégorie non trouvée'
            ], 404);
        }

        if ($category->delete()) {
            return response()->json([
                'status' => 200,
                'message' => 'Catégorie supprimée avec succès'
            ]);
        }

        return response()->json([
            'status' => 500,
            'message' => 'Une erreur est survenue lors de la suppression'
        ], 500);
    }
    
    /**
     * Import Categories from Excel file.
     */
    public function import(Request $request)
    {
        // Check if user has permission to add categories
        if (!auth()->user()->can('Categories-ajoute')) {
            return response()->json([
                'status' => 403,
                'message' => 'Vous n\'avez pas la permission d\'importer des catégories'
            ], 403);
        }

        $validator = Validator::make($request->all(), [
            'file' => 'required|mimes:xlsx,xls,csv|max:2048',
        ], [
            'required' => 'Le fichier est requis.',
            'mimes' => 'Le fichier doit être de type: xlsx, xls ou csv.',
            'max' => 'La taille du fichier ne doit pas dépasser 2MB.',
        ]);
        
        if ($validator->fails()) {
            return response()->json([
                'status' => 400,
                'errors' => $validator->messages(),
            ], 400);
        }

        try {
            $file = $request->file('file');
            $data = [];
            $imported = 0;
            $skipped = 0;
            $duplicates = [];
            $debug = []; // Pour collecter des informations de débogage

            // Read Excel file
            if ($file->getClientOriginalExtension() == 'csv') {
                $reader = new \PhpOffice\PhpSpreadsheet\Reader\Csv();
            } else {
                $reader = new \PhpOffice\PhpSpreadsheet\Reader\Xlsx();
            }
            
            $spreadsheet = $reader->load($file->getPathname());
            $worksheet = $spreadsheet->getActiveSheet();
            $rows = $worksheet->toArray();
            
            // Logging pour débogage
            $debug['total_rows'] = count($rows);
            $debug['first_rows'] = array_slice($rows, 0, 5);
            
            // S'assurer qu'il y a des données
            if (count($rows) < 1) {
                return response()->json([
                    'status' => 400,
                    'message' => 'Le fichier est vide',
                ], 400);
            }

            // Obtenir les entêtes et déterminer l'indice des colonnes
            $headers = $rows[0];
            $debug['headers'] = $headers;
            
            // Transformer les entêtes en minuscules pour une comparaison insensible à la casse
            $headersLower = array_map('strtolower', $headers);
            $debug['headers_lower'] = $headersLower;
            
            $nomIndex = array_search('nom', $headersLower);
            $classeIndex = array_search('classe', $headersLower);
            
            // Si les index ne sont pas trouvés, essayer de détecter en utilisant les noms de colonnes Excel (A, B, C...)
            if ($nomIndex === false && isset($headers[0]) && (empty($headers[0]) || !is_string($headers[0]))) {
                // Utiliser la première colonne pour le nom
                $nomIndex = 0;
            }
            
            if ($classeIndex === false && isset($headers[1]) && (empty($headers[1]) || !is_string($headers[1]))) {
                // Utiliser la deuxième colonne pour la classe
                $classeIndex = 1;
            }
            
            // Si toujours pas d'index pour le nom
            if ($nomIndex === false) {
                // Si nous avons au moins deux colonnes, supposons que la première est le nom
                if (count($headers) >= 1) {
                    $nomIndex = 0;
                } else {
                    return response()->json([
                        'status' => 400,
                        'message' => 'Le fichier doit contenir au moins une colonne pour les noms',
                        'debug' => $debug
                    ], 400);
                }
            }
            
            // Si toujours pas d'index pour la classe
            if ($classeIndex === false && count($headers) >= 2) {
                // Si nous avons au moins deux colonnes, supposons que la deuxième est la classe
                $classeIndex = 1;
            }
            
            $debug['nom_index'] = $nomIndex;
            $debug['classe_index'] = $classeIndex;
            
            // Supprimer la ligne d'en-tête
            array_shift($rows);
            
            // Process each row
            foreach ($rows as $index => $row) {
                $rowNum = $index + 2; // +2 car la ligne 1 est l'entête et les indices commencent à 0
                
                // S'assurer que la rangée contient assez de cellules
                if (!isset($row[$nomIndex])) {
                    $skipped++;
                    continue;
                }
                
                $name = trim($row[$nomIndex]);
                
                // Skip empty names
                if (empty($name)) {
                    $skipped++;
                    continue;
                }
                
                // Get classe value if available
                $classe = null;
                if ($classeIndex !== false && isset($row[$classeIndex])) {
                    $classe = trim($row[$classeIndex]);
                }
                
                // Clean name for comparison (lowercase)
                $cleanedName = strtolower($name);
                
                // Check for duplicates in the current import batch (case insensitive for name)
                $isDuplicate = false;
                foreach ($data as $item) {
                    if (strtolower($item['name']) === $cleanedName) {
                        $duplicates[] = $name;
                        $isDuplicate = true;
                        break;
                    }
                }
                
                if ($isDuplicate) {
                    $skipped++;
                    continue;
                }
                
                // Check if Category already exists in the database (case insensitive name)
                $nameExists = Category::whereRaw('LOWER(TRIM(name)) = ?', [strtolower($name)])->exists();
                
                if ($nameExists) {
                    $duplicates[] = $name . ' (existe déjà)';
                    $skipped++;
                    continue;
                }
                
                // Add to data array for import
                $data[] = [
                    'name' => $name,
                    'classe' => $classe
                ];
            }
            
            // Insert valid Category records
            foreach ($data as $item) {
                Category::create([
                    'name' => $item['name'],
                    'classe' => $item['classe'],
                    'iduser' => Auth::user()->id
                ]);
                $imported++;
            }
            
            return response()->json([
                'status' => 200,
                'message' => $imported . ' catégories ont été importées avec succès. ' . 
                            ($skipped > 0 ? $skipped . ' ont été ignorées (doublons ou vides).' : ''),
                'imported' => $imported,
                'skipped' => $skipped,
                'duplicates' => $duplicates,
                'debug' => $debug // Inclure les informations de débogage dans la réponse
            ]);
            
        } catch (\Exception $e) {
            return response()->json([
                'status' => 500,
                'message' => 'Une erreur est survenue lors de l\'importation: ' . $e->getMessage(),
                'line' => $e->getLine(),
                'file' => $e->getFile()
            ], 500);
        }
    }
    public function GetCategorieByClass(Request $request)
    {
        $class = $request->class;

        $categories = DB::table('categories')
                        ->where('classe', $class)
                        ->get();

        $categoryIds = $categories->pluck('id');

        $products = DB::table('products')
                    ->whereIn('id_categorie', $categoryIds)
                    ->get();

        return response()->json([
            'status'  => 200,
            'data'    => $categories,
            'products' => $products
        ]);
    }
}