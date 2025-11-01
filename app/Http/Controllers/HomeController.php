<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Fournisseur;
use App\Models\Product;
use App\Models\Vente;
use App\Models\Achat;
use App\Models\Stock;
use App\Models\Perte;
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
    $totalUtilisateurs = User::whereNull('deleted_at')->count();
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

    // Produits en expiration dans les 7 prochains jours (OLD CODE - RESTORED)
    $Product_Exepration = DB::table('products')
                        ->where('date_expiration', '<=', DB::raw('DATE_ADD(CURDATE(), INTERVAL 7 DAY)'))
                        ->get();

    // PERTES - Statistiques des pertes de produits
    
    // Count of pertes by status
    $pertesEnAttente = Perte::where('status', 'En attente')
                        ->whereNull('deleted_at')
                        ->count();
    
    $pertesValidees = Perte::where('status', 'Validé')
                        ->whereNull('deleted_at')
                        ->count();
    
    $pertesRefusees = Perte::where('status', 'Refusé')
                        ->whereNull('deleted_at')
                        ->count();
    
    $totalPertes = Perte::whereNull('deleted_at')->count();
    
    // IMPORTANT: Sum of validated pertes quantities (total damaged products)
    $totalQuantitePertesValidees = Perte::where('status', 'Validé')
                        ->whereNull('deleted_at')
                        ->sum('quantite');
    
    // Count unique products that have validated pertes
    $produitsAvecPertes = Perte::where('status', 'Validé')
                        ->whereNull('deleted_at')
                        ->distinct('id_product')
                        ->count('id_product');
    
    // Récupérer les 5 pertes les plus récentes pour affichage (optionnel)
    $recentPertes = Perte::with(['product', 'category', 'subcategory', 'unite', 'user'])
                        ->whereNull('deleted_at')
                        ->orderBy('created_at', 'desc')
                        ->limit(5)
                        ->get();
    
    return view('home', compact(
        'totalUtilisateurs',
        'totalFournisseurs',
        'totalFormations',
        'commandesEnAttente',
        'achatsEnAttente',
        'commandesValidees',
        'achatsValides',
        'stocksAlertes',
        'Product_Exepration',
        'pertesEnAttente',
        'pertesValidees',
        'pertesRefusees',
        'totalPertes',
        'totalQuantitePertesValidees',
        'produitsAvecPertes',
        'recentPertes'
    ));
}
    
    /**
     * Get chart data for dashboard.
     */
    public function getChartData()
    {
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
        
        // Données des pertes - SUM of quantities (not count)
        $pertesMensuelles = Perte::select(
                DB::raw('SUM(quantite) as total'), // Changed from COUNT to SUM
                DB::raw("DATE_FORMAT(created_at, '%Y-%m') as mois")
            )
            ->whereNull('deleted_at')
            ->where('status', 'Validé') // Only count validated pertes
            ->whereRaw('created_at >= DATE_SUB(NOW(), INTERVAL 12 MONTH)')
            ->groupBy('mois')
            ->get()
            ->keyBy('mois')
            ->toArray();
        
        // Formatage pour le graphique
        $ventesData = [];
        $achatsData = [];
        $pertesData = [];
        
        foreach ($mois as $m) {
            $ventesData[] = isset($ventesMensuelles[$m]) ? (int)$ventesMensuelles[$m]['total'] : 0;
            $achatsData[] = isset($achatsMensuels[$m]) ? (int)$achatsMensuels[$m]['total'] : 0;
            $pertesData[] = isset($pertesMensuelles[$m]) ? (float)$pertesMensuelles[$m]['total'] : 0;
        }
        
        return response()->json([
            'labels' => $labels,
            'ventes' => $ventesData,
            'achats' => $achatsData,
            'pertes' => $pertesData,
        ]);
    }
    
    /**
     * Get status data for dashboard chart.
     */
    public function getStatusData()
    {
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
        
        // Récupération des données de statut des pertes
        $pertesStatus = [
            'en_attente' => Perte::where('status', 'En attente')->whereNull('deleted_at')->count(),
            'valide' => Perte::where('status', 'Validé')->whereNull('deleted_at')->count(),
            'refuse' => Perte::where('status', 'Refusé')->whereNull('deleted_at')->count(),
            // Add total quantity for validated pertes
            'quantite_validee' => Perte::where('status', 'Validé')->whereNull('deleted_at')->sum('quantite'),
        ];
        
        return response()->json([
            'ventes' => $ventesStatus,
            'achats' => $achatsStatus,
            'pertes' => $pertesStatus,
        ]);
    }
}