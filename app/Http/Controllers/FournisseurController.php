<?php

namespace App\Http\Controllers;

use App\Models\Fournisseur;
use Illuminate\View\View;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Validator;
use Yajra\DataTables\Facades\DataTables;
use Illuminate\Support\Facades\DB;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class FournisseurController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        if ($request->ajax()) {
            $dataFournisseur = DB::table('fournisseurs as f')
                        ->join('users as us','us.id','=','f.iduser')
                        ->whereNull('f.deleted_at')
                ->select(
                    'f.id',
                    'f.entreprise',
                    'f.Telephone',
                    'f.Email',
                    'f.ICE',
                    'f.siege_social',
                    'f.RC',
                    'f.Patente',
                    'f.IF',
                    'f.CNSS',
                    DB::raw("CONCAT(us.prenom, ' ', us.nom) as username"),
                    'f.created_at'
                )
                ->orderBy('f.id', 'desc');
    
            return DataTables::of($dataFournisseur)
                ->addIndexColumn()
                ->filterColumn('username', function($query, $keyword) {
                    $query->whereRaw("LOWER(CONCAT(us.prenom, ' ', us.nom)) LIKE ?", ["%".strtolower($keyword)."%"]);
                })
                ->addColumn('action', function ($row) {
                    $btn = '';
                    
                    if (auth()->user()->can('Fournisseurs-modifier')) {
                        // Edit button
                        $btn .= '<a href="#" class="btn btn-sm bg-primary-subtle me-1 editFournisseur"
                                    data-id="' . $row->id . '">
                                    <i class="fa-solid fa-pen-to-square text-primary"></i>
                                </a>';
                    }
                    
                    if (auth()->user()->can('Fournisseurs-supprimer')) {
                        // Delete button
                        $btn .= '<a href="#" class="btn btn-sm bg-danger-subtle deleteFournisseur"
                                    data-id="' . $row->id . '" data-bs-toggle="tooltip" 
                                    title="Supprimer Fournisseur">
                                    <i class="fa-solid fa-trash text-danger"></i>
                                </a>';
                    }
    
                    return $btn;
                })
                ->rawColumns(['action'])
                ->make(true);
        }
             
        return view('fournisseur.index', [
            'fournisseurs' => Fournisseur::latest('id')->paginate(10)
        ]);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        // Check if user has permission to add suppliers
        if (!auth()->user()->can('Fournisseurs-ajoute')) {
            return response()->json([
                'status' => 403,
                'message' => 'Vous n\'avez pas la permission d\'ajouter des fournisseurs'
            ], 403);
        }
    
        $validator = Validator::make($request->all(), [
            'entreprise' => 'required|string|max:255',
            'Telephone' => 'required|string|max:20',
            'Email' => 'required|email|max:255',
            'ICE' => 'nullable|string|max:255',
            'siege_social' => 'nullable|string|max:255',
            'RC' => 'nullable|string|max:255',
            'Patente' => 'nullable|string|max:255',
            'IF' => 'nullable|string|max:255',
            'CNSS' => 'nullable|string|max:255',
        ], [
            'required' => 'Le champ :attribute est requis.',
            'email' => 'Le champ :attribute doit être une adresse email valide.',
            'max' => 'Le champ :attribute ne doit pas dépasser :max caractères.',
        ], [
            'entreprise' => 'entreprise',
            'Telephone' => 'téléphone',
            'Email' => 'email',
        ]);
        
        if ($validator->fails()) {
            return response()->json([
                'status' => 400,
                'errors' => $validator->messages(),
            ], 400);
        }
        
        // Trim the entreprise name and check if it already exists
        $trimmedEntreprise = trim($request->entreprise);
        $exists = Fournisseur::where('entreprise', $trimmedEntreprise)->count();
        
        if ($exists > 0) {
            return response()->json([
                'status' => 422,
                'message' => 'Un fournisseur avec ce nom d\'entreprise existe déjà',
            ], 422);
        }
    
        $fournisseur = Fournisseur::create([
            'entreprise' => $trimmedEntreprise,
            'Telephone' => $request->Telephone,
            'Email' => $request->Email,
            'iduser' => Auth::user()->id,
            'ICE' => $request->ICE ?? null,
            'siege_social' => $request->siege_social ?? null,
            'RC' => $request->RC ?? null,
            'Patente' => $request->Patente ?? null,
            'IF' => $request->IF ?? null,
            'CNSS' => $request->CNSS ?? null,
        ]);
    
        if($fournisseur) {
            return response()->json([
                'status' => 200,
                'message' => 'Fournisseur créé avec succès',
            ]);
        } else { 
            return response()->json([
                'status' => 500,
                'message' => 'Une erreur est survenue. Veuillez réessayer.'
            ]);
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(Fournisseur $fournisseur): RedirectResponse
    {
        return redirect()->route('fournisseur.index');
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Request $request, $id)
    {
        $fournisseur = Fournisseur::find($id);
        
        if (!$fournisseur) {
            return response()->json([
                'status' => 404,
                'message' => 'Fournisseur non trouvé'
            ], 404);
        }

        return response()->json($fournisseur);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request)
    {
        // Check if user has permission to modify suppliers
        if (!auth()->user()->can('Fournisseurs-modifier')) {
            return response()->json([
                'status' => 403,
                'message' => 'Vous n\'avez pas la permission de modifier des fournisseurs'
            ], 403);
        }
    
        $fournisseur = Fournisseur::find($request->id);
    
        if (!$fournisseur) {
            return response()->json([
                'status' => 404,
                'message' => 'Fournisseur non trouvé'
            ], 404);
        }
    
        $validator = Validator::make($request->all(), [
            'entreprise' => 'required|string|max:255',
            'Telephone' => 'required|string|max:20',
            'Email' => 'required|email|max:255',
            'ICE' => 'nullable|string|max:255',
            'siege_social' => 'nullable|string|max:255',
            'RC' => 'nullable|string|max:255',
            'Patente' => 'nullable|string|max:255',
            'IF' => 'nullable|string|max:255',
            'CNSS' => 'nullable|string|max:255',
        ], [
            'required' => 'Le champ :attribute est requis.',
            'email' => 'Le champ :attribute doit être une adresse email valide.',
            'max' => 'Le champ :attribute ne doit pas dépasser :max caractères.',
        ], [
            'entreprise' => 'entreprise',
            'Telephone' => 'téléphone',
            'Email' => 'email',
        ]);
    
        if ($validator->fails()) {
            return response()->json([
                'status' => 400,
                'errors' => $validator->messages(),
            ], 400);
        }
    
        // Trim le nom d'entreprise et vérifie s'il existe déjà (en excluant le fournisseur actuel)
        $trimmedEntreprise = trim($request->entreprise);
        $exists = Fournisseur::where('entreprise', $trimmedEntreprise)
                    ->where('id', '!=', $request->id)
                    ->count();
        
        if ($exists > 0) {
            return response()->json([
                'status' => 422,
                'message' => 'Un fournisseur avec ce nom d\'entreprise existe déjà',
            ], 422);
        }
    
        $fournisseur->update([
            'entreprise' => $trimmedEntreprise,
            'Telephone' => $request->Telephone,
            'Email' => $request->Email,
            'ICE' => $request->ICE ?? null,
            'siege_social' => $request->siege_social ?? null,
            'RC' => $request->RC ?? null,
            'Patente' => $request->Patente ?? null,
            'IF' => $request->IF ?? null,
            'CNSS' => $request->CNSS ?? null,
        ]);
    
        return response()->json([
            'status' => 200,
            'message' => 'Fournisseur mis à jour avec succès',
        ]);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Request $request)
    {
        // Check if user has permission to delete suppliers
        if (!auth()->user()->can('Fournisseurs-supprimer')) {
            return response()->json([
                'status' => 403,
                'message' => 'Vous n\'avez pas la permission de supprimer des fournisseurs'
            ], 403);
        }
        
        $fournisseur = Fournisseur::find($request->id);

        if (!$fournisseur) {
            return response()->json([
                'status' => 404,
                'message' => 'Fournisseur non trouvé'
            ], 404);
        }

        if ($fournisseur->delete()) {
            return response()->json([
                'status' => 200,
                'message' => 'Fournisseur supprimé avec succès'
            ]);
        }

        return response()->json([
            'status' => 500,
            'message' => 'Une erreur est survenue lors de la suppression'
        ], 500);
    }
    public function import(Request $request)
{
    // Check if user has permission to add suppliers
    if (!auth()->user()->can('Fournisseurs-ajoute')) {
        return response()->json([
            'status' => 403,
            'message' => 'Vous n\'avez pas la permission d\'importer des fournisseurs'
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
        $entrepriseIndex = array_search('entreprise', array_map('strtolower', $headers));
        $telephoneIndex = array_search('telephone', array_map('strtolower', $headers));
        $emailIndex = array_search('email', array_map('strtolower', $headers));
        $iceIndex = array_search('ice', array_map('strtolower', $headers));
        $rcIndex = array_search('rc', array_map('strtolower', $headers));
        $siegeSocialIndex = array_search('siege_social', array_map('strtolower', $headers));
        $patenteIndex = array_search('patente', array_map('strtolower', $headers));
        $ifIndex = array_search('identifiant_fiscal', array_map('strtolower', $headers));
        $cnssIndex = array_search('cnss', array_map('strtolower', $headers));
        
        $debug['headers'] = $headers;
        $debug['entrepriseIndex'] = $entrepriseIndex;
        $debug['telephoneIndex'] = $telephoneIndex;
        $debug['emailIndex'] = $emailIndex;
        
        // Vérifier si les colonnes nécessaires existent
        if ($entrepriseIndex === false || $telephoneIndex === false || $emailIndex === false) {
            return response()->json([
                'status' => 400,
                'message' => 'Le fichier doit contenir au minimum les colonnes "entreprise", "telephone" et "email"',
                'debug' => $debug
            ], 400);
        }

        // Process each row
        foreach ($rows as $index => $row) {
            $rowNum = $index + 2; // +2 car la ligne 1 est l'entête et les indices commencent à 0
            
            // Vérifier si les indices existent dans la ligne
            if (!isset($row[$entrepriseIndex]) || !isset($row[$telephoneIndex]) || !isset($row[$emailIndex])) {
                $debug['skipped_row_' . $rowNum] = 'Indices manquants';
                $skipped++;
                continue;
            }
            
            $entreprise = trim($row[$entrepriseIndex]);
            $telephone = trim($row[$telephoneIndex]);
            $email = trim($row[$emailIndex]);
            
            // Skip empty required fields
            if (empty($entreprise) || empty($telephone) || empty($email)) {
                $debug['skipped_row_' . $rowNum] = 'Champs requis vides';
                $skipped++;
                continue;
            }

            // Validate email format
            if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
                $debug['skipped_row_' . $rowNum] = 'Format email invalide: ' . $email;
                $skipped++;
                continue;
            }
            
            // Get optional fields
            $ice = isset($row[$iceIndex]) ? trim($row[$iceIndex]) : null;
            $rc = isset($row[$rcIndex]) ? trim($row[$rcIndex]) : null;
            $siegeSocial = isset($row[$siegeSocialIndex]) ? trim($row[$siegeSocialIndex]) : null;
            $patente = isset($row[$patenteIndex]) ? trim($row[$patenteIndex]) : null;
            $if = isset($row[$ifIndex]) ? trim($row[$ifIndex]) : null;
            $cnss = isset($row[$cnssIndex]) ? trim($row[$cnssIndex]) : null;
            
            $debug['processing_row_' . $rowNum] = [
                'entreprise' => $entreprise,
                'telephone' => $telephone,
                'email' => $email,
                'ice' => $ice,
                'rc' => $rc,
                'siege_social' => $siegeSocial,
                'patente' => $patente,
                'if' => $if,
                'cnss' => $cnss
            ];
            
            // Check for duplicates in the current import batch
            $isDuplicate = false;
            foreach ($data as $item) {
                if (strtolower($item['entreprise']) === strtolower($entreprise)) {
                    $duplicates[] = $entreprise;
                    $isDuplicate = true;
                    break;
                }
            }
            
            if ($isDuplicate) {
                $debug['skipped_row_' . $rowNum] = 'Doublon dans le lot d\'importation';
                $skipped++;
                continue;
            }
            
            // Check if supplier already exists in the database
            $entrepriseExists = Fournisseur::where('entreprise', $entreprise)->exists();
            
            if ($entrepriseExists) {
                $debug['skipped_row_' . $rowNum] = 'Entreprise existe déjà: ' . $entreprise;
                $duplicates[] = $entreprise . ' (existe déjà)';
                $skipped++;
                continue;
            }
            
            // Add to data array for import
            $data[] = [
                'entreprise' => $entreprise,
                'telephone' => $telephone,
                'email' => $email,
                'ice' => $ice,
                'rc' => $rc,
                'siege_social' => $siegeSocial,
                'patente' => $patente,
                'if' => $if,
                'cnss' => $cnss
            ];
        }
        
        // Insert valid supplier records
        foreach ($data as $item) {
            Fournisseur::create([
                'entreprise' => $item['entreprise'],
                'Telephone' => $item['telephone'],
                'Email' => $item['email'],
                'ICE' => $item['ice'],
                'RC' => $item['rc'],
                'siege_social' => $item['siege_social'],
                'Patente' => $item['patente'],
                'IF' => $item['if'],
                'CNSS' => $item['cnss'],
                'iduser' => Auth::user()->id
            ]);
            $imported++;
        }
        
        if ($imported === 0 && count($debug) > 0) {
            // Si rien n'a été importé, retourner les informations de débogage
            return response()->json([
                'status' => 200,
                'message' => 'Aucun fournisseur importé. ' . $skipped . ' lignes ignorées.',
                'imported' => $imported,
                'skipped' => $skipped,
                'duplicates' => $duplicates,
                'debug' => $debug
            ]);
        }
        
        return response()->json([
            'status' => 200,
            'message' => $imported . ' fournisseurs ont été importés avec succès. ' . 
                        ($skipped > 0 ? $skipped . ' ont été ignorés.' : ''),
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