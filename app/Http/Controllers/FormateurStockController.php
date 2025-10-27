<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use Yajra\DataTables\Facades\DataTables;
use App\Models\User;

class FormateurStockController extends Controller
{
public function index(Request $request)
{
    // Check permission for viewing demandeur stock
    if (!auth()->user()->can('Voir-Stock-Demandeur')) {
        abort(403, 'Vous n\'avez pas la permission de voir le stock du demandeur');
    }

    if ($request->ajax()) {
        // Get the current authenticated user
        $userId = Auth::id();
        
        // Use the EXACT same pattern as your working code, just add missing columns
        $query = DB::table('ligne_vente as lv')
            ->join('products as p', 'lv.idproduit', '=', 'p.id')
            ->join('ventes as v', 'lv.idvente', '=', 'v.id')
            ->leftJoin('categories as c', 'p.id_categorie', '=', 'c.id')
            ->leftJoin('sub_categories as sc', 'p.id_subcategorie', '=', 'sc.id')
            ->leftJoin('tvas as t', 'p.id_tva', '=', 't.id')
            ->leftJoin('unite as u', 'p.id_unite', '=', 'u.id')
            ->where('v.id_formateur', $userId)
            ->where('v.status', 'Validation')
            ->whereNull('lv.deleted_at')
            ->select(
                'p.id',
                'p.code_article',     
                'p.name',                   
                'u.name as unite_name',       
                'c.name as categorie',
                'sc.name as famille', 
                'p.emplacement',      
                'p.price_achat',      
                'p.seuil',                  
                't.value as tva_value',     
                'p.code_barre',       
                'p.photo',            
                'p.date_expiration',  
                'p.created_at',       
                // Keep your exact same quantite calculation
                DB::raw('SUM(IFNULL(lv.contete_formateur, 0)) as quantite')
            )
            // Update groupBy to include all the new columns
            ->groupBy('p.id', 'p.code_article', 'p.name', 'p.seuil', 't.value', 'u.name', 'c.name', 'sc.name', 'p.emplacement', 'p.price_achat', 'p.code_barre', 'p.photo', 'p.date_expiration', 'p.created_at')
            ->having('quantite', '>', 0);

        return DataTables::of($query)
            ->addIndexColumn()
            ->addColumn('photo_display', function ($row) {
                if ($row->photo) {
                    return '<img src="' . asset('storage/' . $row->photo) . '" alt="Photo" style="width: 50px; height: 50px; object-fit: cover;" class="rounded">';
                }
                return '<span class="text-muted">Pas d\'image</span>';
            })
            ->editColumn('price_achat', function ($row) {
                return $row->price_achat ? number_format($row->price_achat, 2) : '0.00';
            })
            ->editColumn('tva_value', function ($row) {
                return $row->tva_value ? number_format($row->tva_value, 2) : '0.00';
            })
            ->editColumn('date_expiration', function ($row) {
                return $row->date_expiration ? \Carbon\Carbon::parse($row->date_expiration)->format('d/m/Y') : '';
            })
            ->editColumn('created_at', function ($row) {
                return $row->created_at ? \Carbon\Carbon::parse($row->created_at)->format('d/m/Y H:i') : '';
            })
            ->rawColumns(['photo_display'])
            ->make(true);
    }
    
    return view('formateur-stock.index');
}
}