<?php

namespace App\Http\Controllers;

use App\Models\Tva;
use Illuminate\View\View;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Validator;
use Yajra\DataTables\Facades\DataTables;
use Illuminate\Support\Facades\DB;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class TvaController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        if ($request->ajax()) {
            try {
                $dataTva = DB::table('tvas as t')
                    ->leftJoin('users as u', 'u.id', '=', 't.iduser')  // Correct join syntax with equals operator
                    ->whereNull('t.deleted_at')
                    ->select(
                        't.id',
                        't.name',
                        't.value',
                        DB::raw("CONCAT(u.prenom, ' ', u.nom) as username"),
                        't.created_at'
                    )
                    ->orderBy('t.id', 'desc');
    
                return DataTables::of($dataTva)
                    ->addIndexColumn()
                    ->filterColumn('username', function($query, $keyword) {
                        $query->whereRaw("LOWER(CONCAT(u.prenom, ' ', u.nom)) LIKE ?", ["%".strtolower($keyword)."%"]);
                    })
                    ->addColumn('action', function ($row) {
                        $btn = '';
                        if (auth()->user()->can('Taxes-modifier')) { 
                            // Edit button
                            $btn .= '<a href="#" class="btn btn-sm bg-primary-subtle me-1 editTva" data-id="' . $row->id . '">
                                        <i class="fa-solid fa-pen-to-square text-primary"></i>
                                    </a>';
                        }
                       
                        if (auth()->user()->can('Taxes-supprimer')) { 
                            // Delete button
                            $btn .= '<a href="#" class="btn btn-sm bg-danger-subtle deleteTva"
                                        data-id="' . $row->id . '" data-bs-toggle="tooltip" 
                                        title="Supprimer TVA">
                                        <i class="fa-solid fa-trash text-danger"></i>
                                    </a>';
                        }
                        return $btn;
                    })
                    ->rawColumns(['action'])
                    ->make(true);
            } catch (\Exception $e) {
                // Log the error
                \Log::error('DataTables error in TvaController: ' . $e->getMessage());
                
                // Return a friendly error response
                return response()->json([
                    'error' => true,
                    'message' => 'Une erreur est survenue lors du chargement des données',
                    'details' => $e->getMessage()
                ], 500);
            }
        }
             
        return view('tva.index', [
            'tvas' => Tva::latest('id')->paginate(10)
        ]);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:255',
            'value' => 'required|numeric',
        ], [
            'required' => 'Le champ :attribute est requis.',
            'numeric' => 'Le champ :attribute doit être un nombre.',
        ], [
            'name' => 'nom',
            'value' => 'valeur',
        ]);
        
        if ($validator->fails()) {
            return response()->json([
                'status' => 400,
                'errors' => $validator->messages(),
            ], 400);
        }
    
        // Nettoyer et standardiser le nom pour la comparaison
        $cleanedName = strtolower(trim($request->name));
        $cleanedValue = trim($request->value);
        
        // Vérifier si une TVA avec un nom similaire existe déjà (insensible à la casse)
        $nameExists = Tva::whereRaw('LOWER(TRIM(name)) = ?', [$cleanedName])->count();
        
        if ($nameExists > 0) {
            return response()->json([
                'status' => 422,
                'message' => 'Une TVA avec ce nom existe déjà',
            ], 422);
        }
        
        // Vérifier si une TVA avec cette valeur existe déjà
        $valueExists = Tva::where('value', $cleanedValue)->count();
        
        if ($valueExists > 0) {
            return response()->json([
                'status' => 422,
                'message' => 'Une TVA avec cette valeur existe déjà',
            ], 422);
        }
    
        // Créer la TVA avec les valeurs nettoyées
        $tva = Tva::create([
            'name' => trim($request->name), // Conserver la casse d'origine mais supprimer les espaces
            'value' => $cleanedValue,
            'iduser' => Auth::user()->id,
        ]);
    
        if($tva) {
            return response()->json([
                'status' => 200,
                'message' => 'TVA créée avec succès',
            ]);
        } else { 
            return response()->json([
                'status' => 500,
                'message' => 'Quelque chose ne va pas'
            ]);
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(Tva $tva): RedirectResponse
    {
        return redirect()->route('tva.index');
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Request $request, $id)
    {
        $tva = Tva::find($id);
        
        if (!$tva) {
            return response()->json([
                'status' => 404,
                'message' => 'TVA non trouvée'
            ], 404);
        }

        return response()->json($tva);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:255',
            'value' => 'required|numeric',
        ], [
            'required' => 'Le champ :attribute est requis.',
            'numeric' => 'Le champ :attribute doit être un nombre.',
        ], [
            'name' => 'nom',
            'value' => 'valeur',
        ]);
        
        if ($validator->fails()) {
            return response()->json([
                'status' => 400,
                'errors' => $validator->messages(),
            ], 400);
        }
    
        $tva = Tva::find($request->id);
        
        if (!$tva) {
            return response()->json([
                'status' => 404,
                'message' => 'TVA non trouvée'
            ], 404);
        }
        
        // Nettoyer et standardiser le nom et la valeur
        $cleanedName = strtolower(trim($request->name));
        $cleanedValue = trim($request->value);
        
        // Vérifier si une autre TVA avec ce nom existe déjà (en excluant l'ID actuel)
        $nameExists = Tva::whereRaw('LOWER(TRIM(name)) = ?', [$cleanedName])
                         ->where('id', '!=', $request->id)
                         ->count();
        
        if ($nameExists > 0) {
            return response()->json([
                'status' => 422,
                'message' => 'Une TVA avec ce nom existe déjà',
            ], 422);
        }
        
        // Vérifier si une autre TVA avec cette valeur existe déjà (en excluant l'ID actuel)
        $valueExists = Tva::where('value', $cleanedValue)
                          ->where('id', '!=', $request->id)
                          ->count();
        
        if ($valueExists > 0) {
            return response()->json([
                'status' => 422,
                'message' => 'Une TVA avec cette valeur existe déjà',
            ], 422);
        }
        
        $tva->update([
            'name' => trim($request->name),
            'value' => $cleanedValue,
        ]);
        
        return response()->json([
            'status' => 200,
            'message' => 'TVA mise à jour avec succès',
        ]);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Request $request)
    {
        $tva = Tva::find($request->id);

        if (!$tva) {
            return response()->json([
                'status' => 404,
                'message' => 'TVA non trouvée'
            ], 404);
        }

        if ($tva->delete()) {
            return response()->json([
                'status' => 200,
                'message' => 'TVA supprimée avec succès'
            ]);
        }

        return response()->json([
            'status' => 500,
            'message' => 'Une erreur est survenue lors de la suppression'
        ], 500);
    }
    public function import(Request $request)
    {
        // Check if user has permission to add TVA
        if (!auth()->user()->can('Taxes-ajoute')) {
            return response()->json([
                'status' => 403,
                'message' => 'Vous n\'avez pas la permission d\'importer des TVA'
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
    
            // Obtenir les entêtes et déterminer les indices des colonnes
            $headers = array_shift($rows);
            $nomIndex = array_search('nom', array_map('strtolower', $headers));
            $valeurIndex = array_search('valeur', array_map('strtolower', $headers));
            
            $debug['headers'] = $headers;
            $debug['nomIndex'] = $nomIndex;
            $debug['valeurIndex'] = $valeurIndex;
            
            // Vérifier si les colonnes nécessaires existent
            if ($nomIndex === false || $valeurIndex === false) {
                return response()->json([
                    'status' => 400,
                    'message' => 'Le fichier doit contenir les colonnes "nom" et "valeur"',
                    'debug' => $debug
                ], 400);
            }
    
            // Process each row
            foreach ($rows as $index => $row) {
                $rowNum = $index + 2; // +2 car la ligne 1 est l'entête et les indices commencent à 0
                
                // Vérifier si les indices existent dans la ligne
                if (!isset($row[$nomIndex]) || !isset($row[$valeurIndex])) {
                    $debug['skipped_row_' . $rowNum] = 'Indices manquants';
                    $skipped++;
                    continue;
                }
                
                $name = trim($row[$nomIndex]);
                $rawValue = trim($row[$valeurIndex]);
                
                // Skip empty names
                if (empty($name)) {
                    $debug['skipped_row_' . $rowNum] = 'Nom vide';
                    $skipped++;
                    continue;
                }
                
                // Nettoyer la valeur (supprimer le symbole %)
                $value = $rawValue;
                if (is_string($rawValue) && strpos($rawValue, '%') !== false) {
                    $value = str_replace('%', '', $rawValue);
                }
                
                // Convertir en nombre
                $value = trim($value);
                if (!is_numeric($value)) {
                    $debug['skipped_row_' . $rowNum] = 'Valeur non numérique: ' . $rawValue;
                    $skipped++;
                    continue;
                }
                
                // Format value as decimal
                $value = (float) $value;
                
                $debug['processing_row_' . $rowNum] = [
                    'original_name' => $row[$nomIndex],
                    'processed_name' => $name,
                    'original_value' => $rawValue,
                    'processed_value' => $value
                ];
                
                // Check for duplicates in the current import batch
                $isDuplicate = false;
                foreach ($data as $item) {
                    if (strtolower($item['name']) === strtolower($name) || $item['value'] == $value) {
                        $duplicates[] = $name . ' (' . $value . '%)';
                        $isDuplicate = true;
                        break;
                    }
                }
                
                if ($isDuplicate) {
                    $debug['skipped_row_' . $rowNum] = 'Doublon dans le lot d\'importation';
                    $skipped++;
                    continue;
                }
                
                // Check if TVA already exists in the database
                $nameExists = Tva::whereRaw('LOWER(TRIM(name)) = ?', [strtolower($name)])->exists();
                $valueExists = Tva::where('value', $value)->exists();
                
                if ($nameExists) {
                    $debug['skipped_row_' . $rowNum] = 'Nom existe déjà: ' . $name;
                    $duplicates[] = $name . ' (nom existe déjà)';
                    $skipped++;
                    continue;
                }
                
                if ($valueExists) {
                    $debug['skipped_row_' . $rowNum] = 'Valeur existe déjà: ' . $value;
                    $duplicates[] = $name . ' (valeur ' . $value . '% existe déjà)';
                    $skipped++;
                    continue;
                }
                
                // Add to data array for import
                $data[] = [
                    'name' => $name,
                    'value' => $value
                ];
            }
            
            // Insert valid TVA records
            foreach ($data as $item) {
                Tva::create([
                    'name' => $item['name'],
                    'value' => $item['value'],
                    'iduser' => Auth::user()->id
                ]);
                $imported++;
            }
            
            if ($imported === 0 && count($debug) > 0) {
                // Si rien n'a été importé, retourner les informations de débogage
                return response()->json([
                    'status' => 200,
                    'message' => 'Aucune TVA importée. ' . $skipped . ' lignes ignorées.',
                    'imported' => $imported,
                    'skipped' => $skipped,
                    'duplicates' => $duplicates,
                    'debug' => $debug
                ]);
            }
            
            return response()->json([
                'status' => 200,
                'message' => $imported . ' TVA ont été importées avec succès. ' . 
                            ($skipped > 0 ? $skipped . ' ont été ignorées.' : ''),
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