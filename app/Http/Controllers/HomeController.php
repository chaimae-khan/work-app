<?php

namespace App\Http\Controllers;

use App\Models\User; // Changed from Client to User
use App\Models\Fournisseur;
use App\Models\Product;
use App\Models\Vente;
use App\Models\Achat;
use App\Models\Stock;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class HomeController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth');
    }

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function index()
    {
        // Récupérer les statistiques de base
        $totalUtilisateurs = User::whereNull('deleted_at')->count(); // Changed variable name and model
        $totalFournisseurs = Fournisseur::whereNull('deleted_at')->count();
        $totalFormations = Vente::whereNull('deleted_at')->count();
        
        // Commandes et achats en attente ou validés
        $commandesEnAttente = Vente::where('status', 'Création')->count();
        $achatsEnAttente = Achat::where('status', 'Création')->count();
        $commandesValidees = Vente::where('status', 'Validation')->count();
        $achatsValides = Achat::where('status', 'Validation')->count();
        
        // Produits dont le stock est presque épuisé (quantité <= seuil)
        $stocksAlertes = DB::table('stock as s')
                        ->join('products as p', 'p.id', 's.id_product')
                        ->whereNull('s.deleted_at')
                        ->whereRaw('s.quantite <= p.seuil')
                        ->count();
        
        return view('home', compact(
            'totalUtilisateurs', // Changed variable name
            'totalFournisseurs',
            'totalFormations',
            'commandesEnAttente',
            'achatsEnAttente',
            'commandesValidees',
            'achatsValides',
            'stocksAlertes'
        ));
    }
    
    /**
     * Get chart data for dashboard.
     */
    public function getChartData()
    {
        // Le reste du code reste inchangé
        $mois = [];
        $labels = [];
        
        for ($i = 11; $i >= 0; $i--) {
            $date = Carbon::now()->subMonths($i);
            $mois[] = $date->format('Y-m');
            $labels[] = $date->locale('fr')->format('M');
        }
        
        // Données des ventes
        $ventesMensuelles = Vente::select(
                DB::raw('COUNT(*) as total'),
                DB::raw("DATE_FORMAT(created_at, '%Y-%m') as mois")
            )
            ->whereNull('deleted_at')
            ->whereRaw('created_at >= DATE_SUB(NOW(), INTERVAL 12 MONTH)')
            ->groupBy('mois')
            ->get()
            ->keyBy('mois')
            ->toArray();
        
        // Données des achats
        $achatsMensuels = Achat::select(
                DB::raw('COUNT(*) as total'),
                DB::raw("DATE_FORMAT(created_at, '%Y-%m') as mois")
            )
            ->whereNull('deleted_at')
            ->whereRaw('created_at >= DATE_SUB(NOW(), INTERVAL 12 MONTH)')
            ->groupBy('mois')
            ->get()
            ->keyBy('mois')
            ->toArray();
        
        // Formatage pour le graphique
        $ventesData = [];
        $achatsData = [];
        
        foreach ($mois as $m) {
            $ventesData[] = isset($ventesMensuelles[$m]) ? (int)$ventesMensuelles[$m]['total'] : 0;
            $achatsData[] = isset($achatsMensuels[$m]) ? (int)$achatsMensuels[$m]['total'] : 0;
        }
        
        return response()->json([
            'labels' => $labels,
            'ventes' => $ventesData,
            'achats' => $achatsData,
        ]);
    }
    
    /**
     * Get status data for dashboard chart.
     */
    public function getStatusData()
    {
        // Le reste du code reste inchangé
        // Récupération des données de statut des ventes
        $ventesStatus = [
            'creation' => Vente::where('status', 'Création')->count(),
            'validation' => Vente::where('status', 'Validation')->count(),
            'livraison' => Vente::where('status', 'Livraison')->count(),
            'reception' => Vente::where('status', 'Réception')->count(),
        ];
        
        // Récupération des données de statut des achats
        $achatsStatus = [
            'creation' => Achat::where('status', 'Création')->count(),
            'validation' => Achat::where('status', 'Validation')->count(),
            'livraison' => Achat::where('status', 'Livraison')->count(),
            'reception' => Achat::where('status', 'Réception')->count(),
        ];
        
        return response()->json([
            'ventes' => $ventesStatus,
            'achats' => $achatsStatus
        ]);
    }
}