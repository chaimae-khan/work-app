<?php

namespace App\Http\Controllers;

use App\Models\Local;
use Illuminate\View\View;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Validator;
use Yajra\DataTables\Facades\DataTables;
use Illuminate\Support\Facades\DB;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class LocalController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        if ($request->ajax()) {
            $dataLocal = DB::table('locals as l')
                ->join('users as us', 'us.id', '=', 'l.iduser')
                ->whereNull('l.deleted_at')
                ->select(
                    'l.id',
                    'l.name',
                    DB::raw("CONCAT(us.prenom, ' ', us.nom) as username"),
                    'l.created_at'
                )
                ->orderBy('l.id', 'desc');
    
            return DataTables::of($dataLocal)
                ->addIndexColumn()
                ->filterColumn('username', function($query, $keyword) {
                    $query->whereRaw("LOWER(CONCAT(us.prenom, ' ', us.nom)) LIKE ?", ["%".strtolower($keyword)."%"]);
                })
                ->addColumn('action', function ($row) {
                    $btn = '';
    
                    if (auth()->user()->can('Local-modifier')) {
                        // Edit button
                        $btn .= '<a href="#" class="btn btn-sm bg-primary-subtle me-1 editLocal"
                                    data-id="' . $row->id . '">
                                    <i class="fa-solid fa-pen-to-square text-primary"></i>
                                </a>';
                    }
    
                    if (auth()->user()->can('Local-supprimer')) {
                        // Delete button
                        $btn .= '<a href="#" class="btn btn-sm bg-danger-subtle deleteLocal"
                                    data-id="' . $row->id . '" data-bs-toggle="tooltip" 
                                    title="Supprimer Local">
                                    <i class="fa-solid fa-trash text-danger"></i>
                                </a>';
                    }
    
                    return $btn;
                })
                ->rawColumns(['action'])
                ->make(true);
        }
        
        return view('local.index', [
            'locals' => Local::latest('id')->paginate(10)
        ]);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        // Check if user has permission to add locals
        if (!auth()->user()->can('Local-ajoute')) {
            return response()->json([
                'status' => 403,
                'message' => 'Vous n\'avez pas la permission d\'ajouter des locaux'
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
        
        // Check if local already exists with the same name (case insensitive)
        $exists = Local::whereRaw('LOWER(TRIM(name)) = ?', [$cleanedName])->exists();
            
        if ($exists) {
            return response()->json([
                'status' => 409, // Conflict status code
                'message' => 'Ce local existe déjà',
            ], 409);
        }
    
        $local = Local::create([
            'name' => $request->name,
            'iduser' => Auth::user()->id,
        ]);
    
        if($local) {
            return response()->json([
                'status' => 200,
                'message' => 'Local créé avec succès',
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
        // Check if user has permission to modify locals
        if (!auth()->user()->can('Local-modifier')) {
            return response()->json([
                'status' => 403,
                'message' => 'Vous n\'avez pas la permission de modifier des locaux'
            ], 403);
        }

        $local = Local::find($id);
        
        if (!$local) {
            return response()->json([
                'status' => 404,
                'message' => 'Local non trouvé'
            ], 404);
        }
        
        return response()->json($local);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request)
    {
        // Check if user has permission to modify locals
        if (!auth()->user()->can('Local-modifier')) {
            return response()->json([
                'status' => 403,
                'message' => 'Vous n\'avez pas la permission de modifier des locaux'
            ], 403);
        }
    
        $local = Local::find($request->id);
        
        if (!$local) {
            return response()->json([
                'status' => 404,
                'message' => 'Local non trouvé'
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
        
        // Check if another local with the same name exists (case insensitive)
        $exists = Local::where('id', '!=', $request->id) // Exclude current record
            ->whereRaw('LOWER(TRIM(name)) = ?', [$cleanedName])
            ->exists();
            
        if ($exists) {
            return response()->json([
                'status' => 409, // Conflict status code
                'message' => 'Ce local existe déjà',
            ], 409);
        }
    
        $local->name = $request->name;
        $saved = $local->save();
        
        if ($saved) {
            return response()->json([
                'status' => 200,
                'message' => 'Local mis à jour avec succès',
            ]);
        } else {
            return response()->json([
                'status' => 500,
                'message' => 'Une erreur est survenue lors de la mise à jour',
            ], 500);
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Request $request)
    {
        // Check if user has permission to delete locals
        if (!auth()->user()->can('Local-supprimer')) {
            return response()->json([
                'status' => 403,
                'message' => 'Vous n\'avez pas la permission de supprimer des locaux'
            ], 403);
        }

        $local = Local::find($request->id);

        if (!$local) {
            return response()->json([
                'status' => 404,
                'message' => 'Local non trouvé'
            ], 404);
        }

        if ($local->delete()) {
            return response()->json([
                'status' => 200,
                'message' => 'Local supprimé avec succès'
            ]);
        }

        return response()->json([
            'status' => 500,
            'message' => 'Une erreur est survenue lors de la suppression'
        ], 500);
    }

    public function import(Request $request)
    {
        // Check if user has permission to add locals
        if (!auth()->user()->can('Local-ajoute')) {
            return response()->json([
                'status' => 403,
                'message' => 'Vous n\'avez pas la permission d\'importer des locaux'
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

            // Read Excel file
            if ($file->getClientOriginalExtension() == 'csv') {
                $reader = new \PhpOffice\PhpSpreadsheet\Reader\Csv();
            } else {
                $reader = new \PhpOffice\PhpSpreadsheet\Reader\Xlsx();
            }
            
            $spreadsheet = $reader->load($file->getPathname());
            $worksheet = $spreadsheet->getActiveSheet();
            $rows = $worksheet->toArray();

            // Skip the header row
            array_shift($rows);

            // Process each row
            foreach ($rows as $row) {
                // Check if the first column has data (nom)
                if (!empty($row[0])) {
                    $name = trim($row[0]);
                    
                    // Skip empty names
                    if (empty($name)) {
                        $skipped++;
                        continue;
                    }
                    
                    // Check for duplicates in the current import batch
                    if (in_array(strtolower($name), array_map('strtolower', $data))) {
                        $duplicates[] = $name;
                        $skipped++;
                        continue;
                    }
                    
                    // Check if local already exists in the database (case insensitive)
                    $exists = Local::whereRaw('LOWER(TRIM(name)) = ?', [strtolower($name)])->exists();
                    
                    if ($exists) {
                        $duplicates[] = $name;
                        $skipped++;
                        continue;
                    }
                    
                    // Add to data array for import
                    $data[] = $name;
                }
            }
            
            // Insert valid locals
            foreach ($data as $name) {
                Local::create([
                    'name' => $name,
                    'iduser' => Auth::user()->id
                ]);
                $imported++;
            }
            
            return response()->json([
                'status' => 200,
                'message' => $imported . ' locaux ont été importés avec succès. ' . 
                            ($skipped > 0 ? $skipped . ' ont été ignorés (doublons ou vides).' : ''),
                'imported' => $imported,
                'skipped' => $skipped,
                'duplicates' => $duplicates
            ]);
            
        } catch (\Exception $e) {
            return response()->json([
                'status' => 500,
                'message' => 'Une erreur est survenue lors de l\'importation: ' . $e->getMessage()
            ], 500);
        }
    }
}