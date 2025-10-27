<?php

namespace App\Http\Controllers;

use App\Models\SubCategory;
use App\Models\Category;
use Illuminate\View\View;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Validator;
use Yajra\DataTables\Facades\DataTables;
use Illuminate\Support\Facades\DB;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class SubCategoryController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $countCategories = Category::count();
    
        if ($countCategories == 0) {
            return view('Error.index')
                ->withErrors('Tu n\'as pas de catégories');
        }
        if ($request->ajax()) {
            $dataSubCategory = DB::table('sub_categories as sc')
                ->join('users as us', 'us.id', '=', 'sc.iduser')
                ->join('categories as c', 'c.id', '=', 'sc.id_categorie')
                ->whereNull('sc.deleted_at')
                ->select(
                    'sc.id',
                    'sc.name',
                    'c.name as category_name',
                    DB::raw("CONCAT(us.prenom, ' ', us.nom) as username"),
                    'sc.created_at'
                )
                ->orderBy('sc.id', 'desc');
    
            return DataTables::of($dataSubCategory)
                ->addIndexColumn()
                ->filterColumn('username', function($query, $keyword) {
                    $query->whereRaw("LOWER(CONCAT(us.prenom, ' ', us.nom)) LIKE ?", ["%".strtolower($keyword)."%"]);
                })
                ->filterColumn('category_name', function($query, $keyword) {
                    $query->whereRaw("LOWER(c.name) LIKE ?", ["%".strtolower($keyword)."%"]);
                })
                ->addColumn('action', function ($row) {
                    $btn = '';
    
                    if (auth()->user()->can('Famille-modifier')) {
                        // Edit button
                        $btn .= '<a href="#" class="btn btn-sm bg-primary-subtle me-1 editSubCategory"
                                    data-id="' . $row->id . '">
                                    <i class="fa-solid fa-pen-to-square text-primary"></i>
                                </a>';
                    }
    
                    if (auth()->user()->can('Famille-supprimer')) {
                        // Delete button
                        $btn .= '<a href="#" class="btn btn-sm bg-danger-subtle deleteSubCategory"
                                    data-id="' . $row->id . '" data-bs-toggle="tooltip" 
                                    title="Supprimer Sous-catégorie">
                                    <i class="fa-solid fa-trash text-danger"></i>
                                </a>';
                    }
    
                    return $btn;
                })
                ->rawColumns(['action'])
                ->make(true);
        }
        
        $categories = Category::all();
        
        return view('subcategory.index', [
            'subcategories' => SubCategory::latest('id')->paginate(10),
            'categories' => $categories
        ]);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        // Check if user has permission to add subcategories
        if (!auth()->user()->can('Famille-ajoute')) {
            return response()->json([
                'status' => 403,
                'message' => 'Vous n\'avez pas la permission d\'ajouter  une famille.'
            ], 403);
        }

        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:255',
            'id_categorie' => 'required|exists:categories,id',
        ], [
            'required' => 'Le champ :attribute est requis.',
            'exists' => 'La catégorie sélectionnée n\'existe pas.',
        ], [
            'name' => 'nom',
            'id_categorie' => 'catégorie',
        ]);
        
        if ($validator->fails()) {
            return response()->json([
                'status' => 400,
                'errors' => $validator->messages(),
            ], 400);
        }

        // Check if subcategory already exists with the same name in the same category
        $exists = SubCategory::where('name', $request->name)
            ->where('id_categorie', $request->id_categorie)
            ->exists();
            
        if ($exists) {
            return response()->json([
                'status' => 409, // Conflict status code
                'message' => 'Cette une famille existe déjà pour cette catégorie',
            ], 409);
        }

        $subcategory = SubCategory::create([
            'name' => $request->name,
            'id_categorie' => $request->id_categorie,
            'iduser' => Auth::user()->id,
        ]);

        if($subcategory) {
            return response()->json([
                'status' => 200,
                'message' => 'une famille créée avec succès',
            ]);
        } else { 
            return response()->json([
                'status' => 500,
                'message' => 'Quelque chose ne va pas'
            ]);
        }
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit($id)
    {
        // Check if user has permission to modify subcategories
        if (!auth()->user()->can('Famille-modifier')) {
            return response()->json([
                'status' => 403,
                'message' => 'Vous n\'avez pas la permission de modifier des une famille.'
            ], 403);
        }

        $subcategory = SubCategory::find($id);
        
        if (!$subcategory) {
            return response()->json([
                'status' => 404,
                'message' => 'une famille non trouvée'
            ], 404);
        }
        
        return response()->json($subcategory);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request)
    {
        // Check if user has permission to modify subcategories
        if (!auth()->user()->can('Famille-modifier')) {
            return response()->json([
                'status' => 403,
                'message' => 'Vous n\'avez pas la permission de modifier une famille.'
            ], 403);
        }

        $subcategory = SubCategory::find($request->id);
        
        if (!$subcategory) {
            return response()->json([
                'status' => 404,
                'message' => 'Une famille non trouvée'
            ], 404);
        }
        
        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:255',
            'id_categorie' => 'required|exists:categories,id',
        ], [
            'required' => 'Le champ :attribute est requis.',
            'exists' => 'La catégorie sélectionnée n\'existe pas.',
        ], [
            'name' => 'nom',
            'id_categorie' => 'catégorie',
        ]);
        
        if ($validator->fails()) {
            return response()->json([
                'status' => 400,
                'errors' => $validator->messages(),
            ], 400);
        }
        
        // Check if another subcategory with the same name exists in the same category
        $exists = SubCategory::where('name', $request->name)
            ->where('id_categorie', $request->id_categorie)
            ->where('id', '!=', $request->id) // Exclude current record
            ->exists();
            
        if ($exists) {
            return response()->json([
                'status' => 409, // Conflict status code
                'message' => 'Cette famille existe déjà pour cette catégorie',
            ], 409);
        }

        $subcategory->name = $request->name;
        $subcategory->id_categorie = $request->id_categorie;
        $saved = $subcategory->save();
        
        if ($saved) {
            return response()->json([
                'status' => 200,
                'message' => 'famille mise à jour avec succès',
            ]);
        } else {
            return response()->json([
                'status' => 500,
                'message' => 'Erreur lors de la mise à jour de la famille .',
            ], 500);
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Request $request)
    {
        // Check if user has permission to delete subcategories
        if (!auth()->user()->can('Famille-supprimer')) {
            return response()->json([
                'status' => 403,
                'message' => 'Vous n\'avez pas la permission de supprimer une famille.'
            ], 403);
        }

        $subcategory = SubCategory::find($request->id);

        if (!$subcategory) {
            return response()->json([
                'status' => 404,
                'message' => 'une famille non trouvée'
            ], 404);
        }

        if ($subcategory->delete()) {
            return response()->json([
                'status' => 200,
                'message' => 'une famille supprimée avec succès'
            ]);
        }

        return response()->json([
            'status' => 500,
            'message' => 'Une erreur est survenue lors de la suppression'
        ], 500);
    }
    /**
     * Import SubCategories from Excel file.
     */
    public function import(Request $request)
    {
        // Check if user has permission to add subcategories
        if (!auth()->user()->can('Famille-ajoute')) {
            return response()->json([
                'status' => 403,
                'message' => 'Vous n\'avez pas la permission d\'importer des familles'
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
            $notFoundCategories = [];
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
            $categorieIndex = array_search('categorie', $headersLower);
            
            // Si les index ne sont pas trouvés, essayer de détecter en utilisant les noms de colonnes Excel (A, B, C...)
            if ($nomIndex === false && isset($headers[0]) && (empty($headers[0]) || !is_string($headers[0]))) {
                // Utiliser la première colonne pour le nom
                $nomIndex = 0;
            }
            
            if ($categorieIndex === false && isset($headers[1]) && (empty($headers[1]) || !is_string($headers[1]))) {
                // Utiliser la deuxième colonne pour la catégorie
                $categorieIndex = 1;
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
            
            // Si toujours pas d'index pour la catégorie
            if ($categorieIndex === false) {
                // Si nous avons au moins deux colonnes, supposons que la deuxième est la catégorie
                if (count($headers) >= 2) {
                    $categorieIndex = 1;
                } else {
                    return response()->json([
                        'status' => 400,
                        'message' => 'Le fichier doit contenir une colonne pour les catégories',
                        'debug' => $debug
                    ], 400);
                }
            }
            
            $debug['nom_index'] = $nomIndex;
            $debug['categorie_index'] = $categorieIndex;
            
            // Supprimer la ligne d'en-tête
            array_shift($rows);
            
            // Process each row
            foreach ($rows as $index => $row) {
                $rowNum = $index + 2; // +2 car la ligne 1 est l'entête et les indices commencent à 0
                
                // S'assurer que la rangée contient assez de cellules
                if (!isset($row[$nomIndex]) || !isset($row[$categorieIndex])) {
                    $skipped++;
                    continue;
                }
                
                $name = trim($row[$nomIndex]);
                $categorieName = trim($row[$categorieIndex]);
                
                // Skip empty names or categories
                if (empty($name) || empty($categorieName)) {
                    $skipped++;
                    continue;
                }
                
                // Find category ID by name
                $category = Category::whereRaw('LOWER(TRIM(name)) = ?', [strtolower($categorieName)])->first();
                
                if (!$category) {
                    $notFoundCategories[] = $categorieName;
                    $skipped++;
                    continue;
                }
                
                $id_categorie = $category->id;
                
                // Clean name for comparison (lowercase)
                $cleanedName = strtolower($name);
                
                // Check for duplicates in the current import batch (case insensitive for name in same category)
                $isDuplicate = false;
                foreach ($data as $item) {
                    if (strtolower($item['name']) === $cleanedName && $item['id_categorie'] === $id_categorie) {
                        $duplicates[] = $name . ' (dans la catégorie ' . $categorieName . ')';
                        $isDuplicate = true;
                        break;
                    }
                }
                
                if ($isDuplicate) {
                    $skipped++;
                    continue;
                }
                
                // Check if SubCategory already exists in the database (case insensitive name in same category)
                $nameExists = SubCategory::whereRaw('LOWER(TRIM(name)) = ?', [strtolower($name)])
                    ->where('id_categorie', $id_categorie)
                    ->exists();
                
                if ($nameExists) {
                    $duplicates[] = $name . ' (existe déjà dans la catégorie ' . $categorieName . ')';
                    $skipped++;
                    continue;
                }
                
                // Add to data array for import
                $data[] = [
                    'name' => $name,
                    'id_categorie' => $id_categorie
                ];
            }
            
            // Insert valid SubCategory records
            foreach ($data as $item) {
                SubCategory::create([
                    'name' => $item['name'],
                    'id_categorie' => $item['id_categorie'],
                    'iduser' => Auth::user()->id
                ]);
                $imported++;
            }
            
            $message = $imported . ' familles ont été importées avec succès.';
            if ($skipped > 0) {
                $message .= ' ' . $skipped . ' ont été ignorées (doublons, catégories inexistantes ou vides).';
            }
            
            if (!empty($notFoundCategories)) {
                $message .= ' Les catégories suivantes n\'ont pas été trouvées: ' . implode(', ', array_unique($notFoundCategories)) . '.';
            }
            
            return response()->json([
                'status' => 200,
                'message' => $message,
                'imported' => $imported,
                'skipped' => $skipped,
                'duplicates' => $duplicates,
                'notFoundCategories' => array_unique($notFoundCategories),
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
}