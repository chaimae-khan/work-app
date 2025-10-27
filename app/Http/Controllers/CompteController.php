<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use App\Models\User;

class CompteController extends Controller
{
    /**
     * Affiche le profil de l'utilisateur connecté.
     */
    public function index()
    {
        // Vérifie si l'utilisateur est connecté
        if (!Auth::check()) {
            return redirect('login');
        }
        
        // Récupère l'utilisateur connecté
        $user = Auth::user();
        
        // Récupère les rôles de l'utilisateur au format chaîne
        $userRoles = $user->getRoleNames()->implode(', ');
        
        return view('compte.index', [
            'user' => $user,
            'userRoles' => $userRoles
        ]);
    }

    /**
     * Récupère les informations de l'utilisateur pour l'édition.
     */
    public function edit()
    {
        // Vérifie si l'utilisateur est connecté
        if (!Auth::check()) {
            return response()->json([
                'status' => 401,
                'message' => 'Non autorisé'
            ], 401);
        }
        
        $user = Auth::user();
        
        if (!$user) {
            return response()->json([
                'status' => 404,
                'message' => 'Utilisateur non trouvé'
            ], 404);
        }

        return response()->json($user);
    }

    /**
     * Met à jour les informations de l'utilisateur.
     */
    public function update(Request $request)
    {
        // Vérifie si l'utilisateur est connecté
        if (!Auth::check()) {
            return response()->json([
                'status' => 401,
                'message' => 'Non autorisé'
            ], 401);
        }
        
        $user = Auth::user();

        if (!$user) {
            return response()->json([
                'status' => 404,
                'message' => 'Utilisateur non trouvé'
            ], 404);
        }

        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email,'.$user->id,
            'password' => 'nullable|min:6|confirmed',
            'password_confirmation' => 'nullable|min:6',
        ], [
            'required' => 'Le champ :attribute est requis.',
            'email.email' => 'Le format de l\'email est invalide.',
            'email.unique' => 'Cet email est déjà utilisé par un autre compte.',
            'password.min' => 'Le mot de passe doit contenir au moins 6 caractères.',
            'password.confirmed' => 'La confirmation du mot de passe ne correspond pas.',
        ], [
            'name' => 'nom complet',
            'email' => 'adresse email',
            'password' => 'mot de passe',
            'password_confirmation' => 'confirmation du mot de passe',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'status' => 400,
                'errors' => $validator->messages(),
            ], 400);
        }

        // Mise à jour des informations de base
        $updateData = [
            'name' => $request->name,
            'email' => $request->email,
        ];

        // Mise à jour du mot de passe si fourni
        if ($request->filled('password')) {
            $updateData['password'] = Hash::make($request->password);
        }

        // Mise à jour de l'utilisateur
        $user->update($updateData);

        return response()->json([
            'status' => 200,
            'message' => 'Profil mis à jour avec succès',
        ]);
    }

    /**
     * Vérifie si le mot de passe actuel est correct.
     */
    public function verifyPassword(Request $request)
    {
        // Vérifie si l'utilisateur est connecté
        if (!Auth::check()) {
            return response()->json([
                'status' => 401,
                'message' => 'Non autorisé'
            ], 401);
        }
        
        $user = Auth::user();
        
        $validator = Validator::make($request->all(), [
            'current_password' => 'required',
        ], [
            'required' => 'Le mot de passe actuel est requis.',
        ]);
        
        if ($validator->fails()) {
            return response()->json([
                'status' => 400,
                'errors' => $validator->messages(),
            ], 400);
        }
        
        if (!Hash::check($request->current_password, $user->password)) {
            return response()->json([
                'status' => 422,
                'message' => 'Le mot de passe actuel est incorrect.',
            ], 422);
        }
        
        return response()->json([
            'status' => 200,
            'message' => 'Mot de passe vérifié avec succès',
        ]);
    }
}