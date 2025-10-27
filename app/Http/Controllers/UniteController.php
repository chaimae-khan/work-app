<?php

namespace App\Http\Controllers;

use App\Models\Unite;
use Illuminate\View\View;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Validator;
use Yajra\DataTables\Facades\DataTables;
use Illuminate\Support\Facades\DB;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class UniteController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        if ($request->ajax()) {
            $dataUnite = DB::table('unite as un')
                ->join('users as us', 'us.id', '=', 'un.iduser')
                ->whereNull('un.deleted_at')
                ->select(
                    'un.id',
                    'un.name',
                    DB::raw("CONCAT(us.prenom, ' ', us.nom) as username"),
                    'un.created_at'
                )
                ->orderBy('un.id', 'desc');

            return DataTables::of($dataUnite)
                ->addIndexColumn()
                ->filterColumn('username', function($query, $keyword) {
                    $query->whereRaw("LOWER(CONCAT(us.prenom, ' ', us.nom)) LIKE ?", ["%".strtolower($keyword)."%"]);
                })
                ->addColumn('action', function ($row) {
                    $btn = '';

                    if (auth()->user()->can('Unité-modifier')) {
                        // Edit button
                        $btn .= '<a href="#" class="btn btn-sm bg-primary-subtle me-1 editUnite"
                                    data-id="' . $row->id . '">
                                    <i class="fa-solid fa-pen-to-square text-primary"></i>
                                </a>';
                    }

                    if (auth()->user()->can('Unité-supprimer')) {
                        // Delete button
                        $btn .= '<a href="#" class="btn btn-sm bg-danger-subtle deleteUnite"
                                    data-id="' . $row->id . '" data-bs-toggle="tooltip" 
                                    title="Supprimer Unité">
                                    <i class="fa-solid fa-trash text-danger"></i>
                                </a>';
                    }

                    return $btn;
                })
                ->rawColumns(['action'])
                ->make(true);
        }
        
        return view('unite.index', [
            'unites' => Unite::latest('id')->paginate(10)
        ]);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        // Check if user has permission to add unites
        if (!auth()->user()->can('Unité-ajoute')) {
            return response()->json([
                'status' => 403,
                'message' => 'Vous n\'avez pas la permission d\'ajouter des unités'
            ], 403);
        }
    
        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:255',
        ], [
            'required' => 'Le champ :attribute est requis.',
        ], [
            'name' => 'nom',
        ]);
        
        if ($validator->fails()) {
            return response()->json([
                'status' => 400,
                'errors' => $validator->messages(),
            ], 400);
        }
    
        // Clean and prepare the name for case-insensitive check
        $cleanedName = strtolower(trim($request->name));
        
        // Check if unite already exists with the same name (case insensitive)
        $exists = Unite::whereRaw('LOWER(TRIM(name)) = ?', [$cleanedName])->exists();
            
        if ($exists) {
            return response()->json([
                'status' => 409, // Conflict status code
                'message' => 'Cette unité existe déjà',
            ], 409);
        }
    
        $unite = Unite::create([
            'name' => $request->name,
            'iduser' => Auth::user()->id,
        ]);
    
        if($unite) {
            return response()->json([
                'status' => 200,
                'message' => 'Unité créée avec succès',
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
        // Check if user has permission to modify unites
        if (!auth()->user()->can('Unité-modifier')) {
            return response()->json([
                'status' => 403,
                'message' => 'Vous n\'avez pas la permission de modifier des unités'
            ], 403);
        }

        $unite = Unite::find($id);
        
        if (!$unite) {
            return response()->json([
                'status' => 404,
                'message' => 'Unité non trouvée'
            ], 404);
        }
        
        return response()->json($unite);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request)
    {
        // Check if user has permission to modify unites
        if (!auth()->user()->can('Unité-modifier')) {
            return response()->json([
                'status' => 403,
                'message' => 'Vous n\'avez pas la permission de modifier des unités'
            ], 403);
        }
    
        $unite = Unite::find($request->id);
        
        if (!$unite) {
            return response()->json([
                'status' => 404,
                'message' => 'Unité non trouvée'
            ], 404);
        }
        
        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:255',
        ], [
            'required' => 'Le champ :attribute est requis.',
        ], [
            'name' => 'nom',
        ]);
        
        if ($validator->fails()) {
            return response()->json([
                'status' => 400,
                'errors' => $validator->messages(),
            ], 400);
        }
        
        // Clean and prepare the name for case-insensitive check
        $cleanedName = strtolower(trim($request->name));
        
        // Check if another unite with the same name exists (case insensitive)
        $exists = Unite::where('id', '!=', $request->id)
            ->whereRaw('LOWER(TRIM(name)) = ?', [$cleanedName])
            ->exists();
            
        if ($exists) {
            return response()->json([
                'status' => 409, // Conflict status code
                'message' => 'Cette unité existe déjà',
            ], 409);
        }
    
        $unite->name = $request->name;
        $saved = $unite->save();
        
        if ($saved) {
            return response()->json([
                'status' => 200,
                'message' => 'Unité mise à jour avec succès',
            ]);
        } else {
            return response()->json([
                'status' => 500,
                'message' => 'Erreur lors de la mise à jour de l\'unité',
            ], 500);
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Request $request)
    {
        // Check if user has permission to delete unites
        if (!auth()->user()->can('Unité-supprimer')) {
            return response()->json([
                'status' => 403,
                'message' => 'Vous n\'avez pas la permission de supprimer des unités'
            ], 403);
        }

        $unite = Unite::find($request->id);

        if (!$unite) {
            return response()->json([
                'status' => 404,
                'message' => 'Unité non trouvée'
            ], 404);
        }

        if ($unite->delete()) {
            return response()->json([
                'status' => 200,
                'message' => 'Unité supprimée avec succès'
            ]);
        }

        return response()->json([
            'status' => 500,
            'message' => 'Une erreur est survenue lors de la suppression'
        ], 500);
    }
    
    /**
     * Import Unites from Excel file.
     */
    public function import(Request $request)
    {
        // Check if user has permission to add unites
        if (!auth()->user()->can('Unité-ajoute')) {
            return response()->json([
                'status' => 403,
                'message' => 'Vous n\'avez pas la permission d\'importer des unités'
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

            // Obtenir les entêtes et déterminer l'indice de la colonne nom
            $headers = array_shift($rows);
            $nomIndex = array_search('nom', array_map('strtolower', $headers));
            
            // Si pas d'en-tête "nom" trouvé, vérifier si la première colonne contient des données
            if ($nomIndex === false) {
                // Si le fichier n'a qu'une seule colonne, utiliser la première
                if (count($headers) === 1) {
                    $nomIndex = 0;
                } else {
                    return response()->json([
                        'status' => 400,
                        'message' => 'Le fichier doit contenir une colonne "nom"',
                        'headers' => $headers
                    ], 400);
                }
            }

            // Process each row
            foreach ($rows as $index => $row) {
                $rowNum = $index + 2; // +2 car la ligne 1 est l'entête et les indices commencent à 0
                
                // Vérifier si l'indice existe dans la ligne
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
                
                // Clean name for comparison (lowercase)
                $cleanedName = strtolower($name);
                
                // Check for duplicates in the current import batch (case insensitive for name)
                $isDuplicate = false;
                foreach ($data as $item) {
                    if (strtolower($item) === $cleanedName) {
                        $duplicates[] = $name;
                        $isDuplicate = true;
                        break;
                    }
                }
                
                if ($isDuplicate) {
                    $skipped++;
                    continue;
                }
                
                // Check if Unite already exists in the database (case insensitive name)
                $nameExists = Unite::whereRaw('LOWER(TRIM(name)) = ?', [strtolower($name)])->exists();
                
                if ($nameExists) {
                    $duplicates[] = $name . ' (existe déjà)';
                    $skipped++;
                    continue;
                }
                
                // Add to data array for import
                $data[] = $name;
            }
            
            // Insert valid Unite records
            foreach ($data as $name) {
                Unite::create([
                    'name' => $name,
                    'iduser' => Auth::user()->id
                ]);
                $imported++;
            }
            
            return response()->json([
                'status' => 200,
                'message' => $imported . ' unités ont été importées avec succès. ' . 
                            ($skipped > 0 ? $skipped . ' ont été ignorées (doublons ou vides).' : ''),
                'imported' => $imported,
                'skipped' => $skipped,
                'duplicates' => $duplicates
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