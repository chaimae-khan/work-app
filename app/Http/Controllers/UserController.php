<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Http\Requests\StoreUserRequest;
use App\Http\Requests\UpdateUserRequest;
use Illuminate\View\View;
use Illuminate\Http\RedirectResponse;
use Spatie\Permission\Models\Role;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Yajra\DataTables\Facades\DataTables;
use Illuminate\Support\Facades\DB;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Auth;

class UserController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        if ($request->ajax()) {
            $dataUser = DB::table('users')
                ->leftJoin('model_has_roles', 'users.id', '=', 'model_has_roles.model_id')
                ->leftJoin('roles', 'model_has_roles.role_id', '=', 'roles.id')
                ->whereNull('users.deleted_at')
                ->select(
                    'users.id',
                    'users.matricule',
                    'users.nom',
                    'users.prenom',
                    'users.email',
                    'users.telephone',
                    'users.fonction',
                    'users.password',
                    'users.created_at',
                    DB::raw("GROUP_CONCAT(roles.name SEPARATOR ', ') as roles"),
                    DB::raw("CONCAT(users.prenom, ' ', users.nom) as name") // Virtual attribute for display
                )
                ->groupBy('users.id', 'users.matricule', 'users.nom', 'users.prenom', 'users.email', 'users.telephone', 'users.fonction', 'users.password', 'users.created_at')
                ->orderBy('users.id', 'desc');
    
            return DataTables::of($dataUser)
                ->addIndexColumn()
                ->addColumn('action', function ($row) {
                    $btn = '';
    
                    // Add permission check
                    if (auth()->user()->can('utilisateur-modifier')) {
                        $btn .= '<a href="#" class="btn btn-sm bg-primary-subtle me-1 editUser"
                                    data-id="' . $row->id . '"    title="modifier roles"
                                    >
                                    <i class="fa-solid fa-pen-to-square text-primary"></i>
                                </a>';
                    }
    
                    // Add permission check
                    if (auth()->user()->can('utilisateur-supprimer')) {
                        $btn .= '<a href="#" class="btn btn-sm bg-danger-subtle deleteuser"
                                    data-id="' . $row->id . '" data-bs-toggle="tooltip" 
                                    title="Supprimer roles">
                                    <i class="fa-solid fa-trash text-danger"></i>
                                </a>';
                    }
    
                    return $btn;
                })
                ->rawColumns(['action'])
                ->make(true);
        }
                 
        return view('users.index', [
            'users' => User::latest('id')->paginate(3),
            'roles' => Role::pluck('name')->all()
        ]);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create(): View
    {
        return view('users.create', [
            'roles' => Role::pluck('name')->all()
        ]);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
{
    $validator = Validator::make($request->all(), [
        'matricule' => 'nullable|unique:users,matricule',
        'nom' => 'required',
        'prenom' => 'required',
        'email' => 'required|email|unique:users,email',
        'password' => 'required|min:6',
        'telephone' => 'nullable|numeric',
        'fonction' => 'nullable',
        'roles' => 'required',
        'image' => 'required', // ensure signature exists
    ], [
        'required' => 'Le champ :attribute est requis.',
        'email.email' => 'Le champ mail doit être une adresse valide.',
        'email.unique' => 'Cet email est déjà utilisé, veuillez en choisir un autre.',
        'matricule.unique' => 'Ce matricule est déjà utilisé, veuillez en choisir un autre.',
        'password.min' => 'Le mot de passe doit contenir au moins 6 caractères.',
        'telephone.numeric' => 'Le téléphone doit contenir uniquement des chiffres.',
    ], [
        'matricule' => 'matricule',
        'nom' => 'nom',
        'prenom' => 'prénom',
        'email' => 'mail',
        'password' => 'mot de passe',
        'telephone' => 'téléphone',
        'fonction' => 'fonction',
        'image' => 'signature',
    ]);

    if ($validator->fails()) {
        return response()->json([
            'status' => 400,
            'errors' => $validator->messages(),
        ], 400);
    }

    // Create user
    $input = $request->all();
    $input['password'] = Hash::make($request->password);
    $user = User::create($input);

    if ($user) {
        // Save the signature (Base64 image)
        $image = $request->image;

        if (!empty($image)) {
            // Clean the base64 string
            $image = str_replace('data:image/png;base64,', '', $image);
            $image = str_replace(' ', '+', $image);

            // Generate unique name
            $imageName = 'signature_' . $user->id . '_' . time() . '.png';

            // Define folder path (inside public/images/signatures)
            $folderPath = public_path('images/signatures');

            // Create directory if it doesn’t exist
            if (!file_exists($folderPath)) {
                mkdir($folderPath, 0777, true);
            }

            // Save the image to /public/images/signatures
            file_put_contents($folderPath . '/' . $imageName, base64_decode($image));

            // Save signature path in the database (you can use a Signature model if you have one)
            User::where('id',$user->id)->update([
                'signature'=> 'images/signatures/' . $imageName,
            ]);
           /*  User::updateOrCreate(
                ['id' => $user->id],
                ['signature' => 'images/signatures/' . $imageName]
            ); */
        }

        // Assign user role
        $user->assignRole($request->roles);

        return response()->json([
            'status' => 200,
            'message' => 'Utilisateur créé avec succès avec signature',
        ]);
    } 
    else {
        return response()->json([
            'status' => 500,
            'message' => 'Quelque chose ne va pas',
        ]);
    }
}


    /**
     * Display the specified resource.
     */
    public function show(User $user): RedirectResponse
    {
        return redirect()->route('users.index');
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(User $user): View
    {
        // Check Only Super Admin can update his own Profile
        if ($user->hasRole('Super Admin')){
            if($user->id != auth()->user()->id){
                abort(403, 'USER DOES NOT HAVE THE RIGHT PERMISSIONS');
            }
        }

        return view('users.edit', [
            'user' => $user,
            'roles' => Role::pluck('name')->all(),
            'userRoles' => $user->roles->pluck('name')->all()
        ]);
    }


public function getUser($id)
{
    Log::info("Getting user with ID: " . $id);
    
    try {
        $user = User::with('roles')->find($id);
        
        if (!$user) {
            Log::warning("User not found with ID: " . $id);
            return response()->json([
                'status' => 404,
                'message' => 'Utilisateur non trouvé'
            ], 404);
        }

        // Create a clean user object with just the data we need
        $userData = [
            'id' => $user->id,
            'matricule' => $user->matricule,
            'nom' => $user->nom,
            'prenom' => $user->prenom,
            'email' => $user->email,
            'telephone' => $user->telephone,
            'fonction' => $user->fonction,
            'roles' => $user->roles->pluck('name')->toArray()
        ];
        
        Log::info("User data found:", $userData);
        
        // Return the user data directly, not in a DataTables format
        return response()->json($userData);
    } catch (\Exception $e) {
        Log::error("Error retrieving user: " . $e->getMessage(), [
            'id' => $id,
            'file' => $e->getFile(),
            'line' => $e->getLine(),
            'trace' => $e->getTraceAsString()
        ]);
        
        return response()->json([
            'status' => 500,
            'message' => 'Erreur lors de la récupération des données: ' . $e->getMessage()
        ], 500);
    }
}

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request)
    {
        Log::info("Update user request received:", ['request_data' => $request->all()]);
        
        $user = User::find($request->id);

        if (!$user) {
            Log::warning("User not found for update with ID: " . $request->id);
            return response()->json([
                'status' => 404,
                'message' => 'Utilisateur non trouvé'
            ], 404);
        }

        $validator = Validator::make($request->all(), [
            'matricule' => 'nullable|unique:users,matricule,' . $request->id,
            'nom' => 'required',
            'prenom' => 'required',
            'email' => 'required|email|unique:users,email,' . $request->id,
            'password' => 'nullable|min:6',
            'telephone' => 'nullable|numeric',
            'fonction' => 'nullable',
        ], [
            'required' => 'Le champ :attribute est requis.',
            'email.email' => 'Le champ mail doit être une adresse valide.',
            'email.unique' => 'Cet email est déjà utilisé, veuillez en choisir un autre.',
            'matricule.unique' => 'Ce matricule est déjà utilisé, veuillez en choisir un autre.',
            'password.min' => 'Le mot de passe doit contenir au moins 6 caractères.',
            'telephone.numeric' => 'Le téléphone doit contenir uniquement des chiffres.',
        ], [
            'matricule' => 'matricule',
            'nom' => 'nom',
            'prenom' => 'prénom',
            'email' => 'mail',
            'password' => 'mot de passe',
            'telephone' => 'téléphone',
            'fonction' => 'fonction',
        ]);

        if ($validator->fails()) {
            Log::warning("Validation failed for user update:", ['errors' => $validator->errors()->toArray()]);
            return response()->json([
                'status' => 400,
                'errors' => $validator->messages(),
            ], 400);
        }

        try {
            $user->update([
                'matricule' => $request->matricule,
                'nom' => $request->nom,
                'prenom' => $request->prenom,
                'email' => $request->email,
                'telephone' => $request->telephone,
                'fonction' => $request->fonction,
                'password' => $request->filled('password') ? Hash::make($request->password) : $user->password,
            ]);

            // Role synchronization
            if ($request->has('roles')) {
                $user->syncRoles($request->roles);
            }

            Log::info("User updated successfully:", [
                'id' => $user->id,
                'matricule' => $user->matricule,
                'nom' => $user->nom,
                'prenom' => $user->prenom,
                'email' => $user->email
            ]);

            return response()->json([
                'status' => 200,
                'message' => 'Utilisateur mis à jour avec succès',
            ]);
        } catch (\Exception $e) {
            Log::error("Error updating user: " . $e->getMessage(), [
                'id' => $request->id,
                'file' => $e->getFile(),
                'line' => $e->getLine()
            ]);
            
            return response()->json([
                'status' => 500,
                'message' => 'Une erreur est survenue lors de la mise à jour: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Request $request)
    {
        $user = User::find($request->id);

        if (!$user) {
            return response()->json([
                'status' => 404,
                'message' => 'Utilisateur non trouvé'
            ], 404);
        }

        // Delete user
        if ($user->delete()) {
            return response()->json([
                'status' => 200,
                'message' => 'Utilisateur supprimé avec succès'
            ]);
        }

        return response()->json([
            'status' => 500,
            'message' => 'Une erreur est survenue lors de la suppression'
        ], 500);
    }

    /**
     * Import users from Excel file
     */
    public function import(Request $request)
    {
        // Check if user has permission to add users
        if (!auth()->user()->can('utilisateur-ajoute')) {
            return response()->json([
                'status' => 403,
                'message' => 'Vous n\'avez pas la permission d\'importer des utilisateurs'
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
            $matriculeIndex = array_search('matricule', array_map('strtolower', $headers));
            $nomIndex = array_search('nom', array_map('strtolower', $headers));
            $prenomIndex = array_search('prenom', array_map('strtolower', $headers));
            $emailIndex = array_search('email', array_map('strtolower', $headers));
            $telephoneIndex = array_search('telephone', array_map('strtolower', $headers));
            $fonctionIndex = array_search('fonction', array_map('strtolower', $headers));
            $roleIndex = array_search('role', array_map('strtolower', $headers));
            
            $debug['headers'] = $headers;
            $debug['indices'] = [
                'matricule' => $matriculeIndex,
                'nom' => $nomIndex,
                'prenom' => $prenomIndex,
                'email' => $emailIndex,
                'telephone' => $telephoneIndex,
                'fonction' => $fonctionIndex,
                'role' => $roleIndex
            ];
            
            // Vérifier si les colonnes nécessaires existent
            if ($nomIndex === false || $prenomIndex === false || $emailIndex === false || $roleIndex === false) {
                return response()->json([
                    'status' => 400,
                    'message' => 'Le fichier doit contenir au minimum les colonnes "nom", "prenom", "email" et "role"',
                    'debug' => $debug
                ], 400);
            }

            // Get all available roles
            $availableRoles = Role::pluck('name')->toArray();
            
            // Process each row
            foreach ($rows as $index => $row) {
                $rowNum = $index + 2; // +2 car la ligne 1 est l'entête et les indices commencent à 0
                
                // Extract data from the row
                $nom = isset($row[$nomIndex]) ? trim($row[$nomIndex]) : null;
                $prenom = isset($row[$prenomIndex]) ? trim($row[$prenomIndex]) : null;
                $email = isset($row[$emailIndex]) ? trim($row[$emailIndex]) : null;
                $role = isset($row[$roleIndex]) ? trim($row[$roleIndex]) : null;
                
                // Optional fields
                $matricule = ($matriculeIndex !== false && isset($row[$matriculeIndex])) ? trim($row[$matriculeIndex]) : null;
                $telephone = ($telephoneIndex !== false && isset($row[$telephoneIndex])) ? trim($row[$telephoneIndex]) : null;
                $fonction = ($fonctionIndex !== false && isset($row[$fonctionIndex])) ? trim($row[$fonctionIndex]) : null;
                
                // Skip rows with missing required data
                if (empty($nom) || empty($prenom) || empty($email) || empty($role)) {
                    $debug['skipped_row_' . $rowNum] = 'Données requises manquantes';
                    $skipped++;
                    continue;
                }
                
                // Validate email format
                if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
                    $debug['skipped_row_' . $rowNum] = 'Format email invalide: ' . $email;
                    $skipped++;
                    continue;
                }
                
                // Check if email already exists in the database
                $emailExists = User::where('email', $email)->exists();
                if ($emailExists) {
                    $debug['skipped_row_' . $rowNum] = 'Email existe déjà: ' . $email;
                    $duplicates[] = $prenom . ' ' . $nom . ' (email existe déjà)';
                    $skipped++;
                    continue;
                }
                
                // Check if matricule already exists (if provided)
                if (!empty($matricule)) {
                    $matriculeExists = User::where('matricule', $matricule)->exists();
                    if ($matriculeExists) {
                        $debug['skipped_row_' . $rowNum] = 'Matricule existe déjà: ' . $matricule;
                        $duplicates[] = $prenom . ' ' . $nom . ' (matricule existe déjà)';
                        $skipped++;
                        continue;
                    }
                }
                
                // Check if role exists
                if (!in_array($role, $availableRoles)) {
                    $debug['skipped_row_' . $rowNum] = 'Rôle invalide: ' . $role;
                    $skipped++;
                    continue;
                }
                
                // Check for duplicate emails in the current import batch
                $isDuplicate = false;
                foreach ($data as $item) {
                    if ($item['email'] === $email) {
                        $duplicates[] = $prenom . ' ' . $nom . ' (email en doublon dans le lot)';
                        $isDuplicate = true;
                        break;
                    }
                    
                    // Check for duplicate matricule if provided
                    if (!empty($matricule) && !empty($item['matricule']) && $item['matricule'] === $matricule) {
                        $duplicates[] = $prenom . ' ' . $nom . ' (matricule en doublon dans le lot)';
                        $isDuplicate = true;
                        break;
                    }
                }
                
                if ($isDuplicate) {
                    $debug['skipped_row_' . $rowNum] = 'Doublon dans le lot d\'importation';
                    $skipped++;
                    continue;
                }
                
                // Add to data array for import
                $data[] = [
                    'matricule' => $matricule,
                    'nom' => $nom,
                    'prenom' => $prenom,
                    'email' => $email,
                    'telephone' => $telephone,
                    'fonction' => $fonction,
                    'role' => $role
                ];
            }
            
            // Insert valid user records
            foreach ($data as $item) {
                // Generate password based on name initials and current year
                $password = $this->generatePassword($item['prenom'], $item['nom']);
                
                $user = User::create([
                    'matricule' => $item['matricule'],
                    'nom' => $item['nom'],
                    'prenom' => $item['prenom'],
                    'email' => $item['email'],
                    'telephone' => $item['telephone'],
                    'fonction' => $item['fonction'],
                    'password' => Hash::make($password)
                ]);
                
                // Assign role
                if ($user) {
                    $user->assignRole($item['role']);
                    $imported++;
                }
            }
            
            if ($imported === 0 && count($debug) > 0) {
                // Si rien n'a été importé, retourner les informations de débogage
                return response()->json([
                    'status' => 200,
                    'message' => 'Aucun utilisateur importé. ' . $skipped . ' lignes ignorées.',
                    'imported' => $imported,
                    'skipped' => $skipped,
                    'duplicates' => $duplicates,
                    'debug' => $debug
                ]);
            }
            
            return response()->json([
                'status' => 200,
                'message' => $imported . ' utilisateurs ont été importés avec succès. ' . 
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

    /**
     * Generate a password based on the user's first and last name
     */
    private function generatePassword($prenom, $nom)
    {
        // Extract first two letters from first name and last name
        $prenomPart = mb_substr($prenom, 0, 2);
        $nomPart = mb_substr($nom, 0, 2);
        
        // Format: PrNo@2025, KaAn@2025, ChEm@2025, JaBo@2025
        $firstPart = ucfirst(strtolower($prenomPart)) . ucfirst(strtolower($nomPart));
        
        // Add the current year
        $year = date('Y');
        
        // Combine to create password
        return $firstPart . '@' . $year;
    }
}