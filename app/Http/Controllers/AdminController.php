<?php

namespace App\Http\Controllers;

use App\Models\Achat;
use App\Models\Vente;
use App\Notifications\SystemNotification;

class AdminController extends Controller
{
    public function approveAchat($id)
    {
        $achat = Achat::findOrFail($id);
        $achat->status = 'Validation';
        $achat->save();
        
        $user = \App\Models\User::find($achat->id_user);
        $user->notify(new SystemNotification([
            'message' => 'Votre achat  a été approuvé',
            'status' => 'Validation',
            'view_url' => route('ShowBonReception', $achat->id)
        ]));
        
        return back()->with('success', 'Achat approuvé avec succès');
    }
    
    public function rejectAchat($id)
    {
        $achat = Achat::findOrFail($id);
        $achat->status = 'Refus';
        $achat->save();
        
        // Notify the creator
        $user = \App\Models\User::find($achat->id_user);
        $user->notify(new SystemNotification([
            'message' => 'Votre achat  a été refusé',
            'status' => 'Refus',
            'view_url' => route('ShowBonReception', $achat->id)
        ]));
        
        return back()->with('success', 'Achat refusé');
    }
    
    // Similar functions for vente approval/rejection
    public function approveVente($id)
    {
        $vente = Vente::findOrFail($id);
        $vente->status = 'Validation';
        $vente->save();
        
        $user = \App\Models\User::find($vente->id_user);
        $user->notify(new SystemNotification([
            'message' => 'Votre vente a été approuvée',
            'status' => 'Validation',
            'view_url' => route('ShowBonVente', $vente->id)
        ]));
        
        return back()->with('success', 'Vente approuvée avec succès');
    }
    
    public function rejectVente($id)
    {
        $vente = Vente::findOrFail($id);
        $vente->status = 'Refus';
        $vente->save();
        
        $user = \App\Models\User::find($vente->id_user);
        $user->notify(new SystemNotification([
            'message' => 'Votre vente  a été refusée',
            'status' => 'Refus',
            'view_url' => route('ShowBonVente', $vente->id)
        ]));
        
        return back()->with('success', 'Vente refusée');
    }
}