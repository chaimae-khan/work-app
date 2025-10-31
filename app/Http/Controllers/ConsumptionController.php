<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\DailyConsumption;
use App\Models\ConsumptionProductDetail;
use App\Models\Vente;
use App\Models\LigneVente;
use App\Models\Achat;
use App\Models\LigneAchat;
use App\Models\Product;
use Carbon\Carbon;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\DB;
use Barryvdh\DomPDF\Facade\Pdf;

class ConsumptionController extends Controller
{
 public function index()
{
    if (!auth()->user()->can('Voir-Consommation')) {
        abort(403, 'Vous n\'avez pas la permission de voir la consommation');
    }
    
    return view('consumption.index');
}

  public function getConsumptionData(Request $request)
{
    $request->validate([
        'date' => 'required|date',
        'type_operation' => 'required|string|in:entree,sortie',
        'type_commande' => 'sometimes|string|nullable', 
        'type_menu' => 'sometimes|string|nullable',
        'filter_category' => 'sometimes|string|nullable' // Add this new parameter
    ]);

    $date = Carbon::parse($request->date);
    $typeOperation = $request->type_operation;
    $typeCommande = $request->type_commande;
    $typeMenu = $request->type_menu;
    $filterCategory = $request->filter_category; // Get the category filter
    
    try {
        // Process or retrieve consumption data
        $this->processConsumptionForDate($date);
        
        // Get consumption data based on filters
        $consumptionData = $this->getFilteredConsumptionData($date, $typeOperation, $typeCommande, $typeMenu, $filterCategory);
        
        // Debug output
        Log::info('Consumption data returned:', [
            'data' => $consumptionData,
            'is_empty' => empty($consumptionData),
            'has_consumptions' => isset($consumptionData['consumptions']),
            'consumptions_empty' => empty($consumptionData['consumptions'] ?? [])
        ]);
        
        // Modified check to properly handle array structure
        if (!isset($consumptionData['consumptions']) || empty($consumptionData['consumptions'])) {
            Log::info('No consumptions found - returning 404');
            return response()->json([
                'status' => 404,
                'message' => 'Aucune commande trouvée pour cette date'
            ]);
        }

        return response()->json([
            'status' => 200,
            'data' => $consumptionData
        ]);

    } catch (\Exception $e) {
        Log::error('Error fetching consumption data: ' . $e->getMessage());
        Log::error($e->getTraceAsString());
        return response()->json([
            'status' => 500,
            'message' => 'Erreur lors de la récupération des données'
        ], 500);
    }
}

    /**
     * Process consumption data for a specific date
     */
    private function processConsumptionForDate(Carbon $date)
    {
        DB::beginTransaction();
        try {
            // Process ventes (sorties)
            $this->processVentes($date);
            
            // Process achats (entrées)
            $this->processAchats($date);
            
            DB::commit();
        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Error processing consumption: ' . $e->getMessage());
            throw $e;
        }
    }

private function processVentes(Carbon $date)
{
    $ventes = Vente::whereDate('created_at', $date)
        ->where('status', 'Validation')
        ->where(function($query) {
            $query->where('is_transfer', false)
                  ->orWhereNull('is_transfer');
        })
        ->get();

    foreach ($ventes as $vente) {
        $totalCost = 0;
        $totalTVA = 0;
        $categoryCosts = []; // Initialize category costs array
        
        $consumption = DailyConsumption::where('vente_id', $vente->id)->first() 
            ?? DailyConsumption::create([
                'consumption_date' => $date,
                'vente_id' => $vente->id,
                'type_commande' => $vente->type_commande,
                'type_menu' => $vente->type_menu,
                'total_people' => $vente->eleves + $vente->personnel + $vente->invites + $vente->divers,
                'eleves' => $vente->eleves,
                'personnel' => $vente->personnel,
                'invites' => $vente->invites,
                'divers' => $vente->divers,
                'type' => 'sortie'
            ]);

        $ligneVentes = LigneVente::where('idvente', $vente->id)->get();
        
        foreach ($ligneVentes as $ligne) {
            $product = Product::with(['tva', 'category'])->find($ligne->idproduit);
            if ($product) {
                // Determine TVA rate
                $tvaRate = $product->tva ? $product->tva->value : 0;
                
                // Calculate prices
                $unitPrice = $product->price_achat; // HT price
                $tvaAmount = $unitPrice * ($tvaRate / 100) * $ligne->qte;
                $totalPrice = $unitPrice * $ligne->qte * (1 + ($tvaRate / 100)); // TTC price

                // Accumulate totals
                $totalCost += $totalPrice;
                $totalTVA += $tvaAmount;

                // Create or update product detail
                ConsumptionProductDetail::updateOrCreate(
                    [
                        'consumption_id' => $consumption->id,
                        'ligne_vente_id' => $ligne->id
                    ],
                    [
                        'product_id' => $product->id,
                        'quantity' => $ligne->qte,
                        'unit_price' => $unitPrice,
                        'tva_rate' => $tvaRate,
                        'tva_amount' => $tvaAmount,
                        'total_price' => $totalPrice
                    ]
                );
                
                // Track category costs
                $categoryId = $product->id_categorie;
                $categoryName = $product->category->name ?? 'Non catégorisé';
                
                if (!isset($categoryCosts[$categoryId])) {
                    $categoryCosts[$categoryId] = [
                        'id' => $categoryId,
                        'name' => $categoryName,
                        'total_cost' => 0,
                        'total_tva' => 0
                    ];
                }
                
                $categoryCosts[$categoryId]['total_cost'] += $totalPrice;
                $categoryCosts[$categoryId]['total_tva'] += $tvaAmount;
            }
        }

        // Update consumption totals including category costs
        $consumption->update([
            'total_cost' => $totalCost,
            'total_tva' => $totalTVA,
            'average_cost_per_person' => $consumption->total_people > 0 
                ? $totalCost / $consumption->total_people 
                : 0,
            'category_costs' => $categoryCosts // Save category costs
        ]);
    }
}


  

private function processAchats(Carbon $date)
{
    $achats = Achat::whereDate('created_at', $date)
        ->where('status', 'Validation')
        ->get();

    foreach ($achats as $achat) {
        $consumptionTotal = 0;
        $totalTVA = 0;
        $categoryCosts = []; // Initialize category costs array
        
        // Check if consumption record already exists
        $consumption = DailyConsumption::where('achat_id', $achat->id)->first();
        
        if (!$consumption) {
            $consumption = DailyConsumption::create([
                'consumption_date' => $date,
                'achat_id' => $achat->id,
                'type_commande' => null, // Set to null for achats
                'type' => 'entree'
            ]);
        }

        // Process product details
        $ligneAchats = LigneAchat::where('idachat', $achat->id)->get();
        
        foreach ($ligneAchats as $ligne) {
            $product = Product::with(['tva', 'category'])->find($ligne->idproduit);
            if ($product) {
                // Determine TVA rate
                $tvaRate = $product->tva ? $product->tva->value : 0;
                
                // Calculate prices
                $unitPrice = $product->price_achat; // HT price
                $tvaAmount = $unitPrice * ($tvaRate / 100) * $ligne->qte;
                $totalPrice = $unitPrice * $ligne->qte * (1 + ($tvaRate / 100)); // TTC price
                
                $consumptionTotal += $totalPrice;
                $totalTVA += $tvaAmount;

                // Create or update product detail
                ConsumptionProductDetail::updateOrCreate(
                    [
                        'consumption_id' => $consumption->id,
                        'ligne_achat_id' => $ligne->id
                    ],
                    [
                        'consumption_id' => $consumption->id,
                        'product_id' => $product->id,
                        'ligne_achat_id' => $ligne->id,
                        'quantity' => $ligne->qte,
                        'unit_price' => $unitPrice,
                        'tva_rate' => $tvaRate,
                        'tva_amount' => $tvaAmount,
                        'total_price' => $totalPrice
                    ]
                );
                
                // Track category costs
                $categoryId = $product->id_categorie;
                $categoryName = $product->category->name ?? 'Non catégorisé';
                
                if (!isset($categoryCosts[$categoryId])) {
                    $categoryCosts[$categoryId] = [
                        'id' => $categoryId,
                        'name' => $categoryName,
                        'total_cost' => 0,
                        'total_tva' => 0
                    ];
                }
                
                $categoryCosts[$categoryId]['total_cost'] += $totalPrice;
                $categoryCosts[$categoryId]['total_tva'] += $tvaAmount;
            }
        }

        // Update consumption total including category costs
        $consumption->update([
            'total_cost' => $consumptionTotal,
            'total_tva' => $totalTVA,
            'category_costs' => $categoryCosts // Save category costs
        ]);
    }
}
/**
 * Get filtered consumption data with menu attributes support
 */
private function getFilteredConsumptionData(Carbon $date, string $typeOperation, string $typeCommande = null, string $typeMenu = null, string $filterCategory = null)
{
    $query = DailyConsumption::with([
        'productDetails.product.category', 
        'productDetails.product.unite', 
        'productDetails.product.tva', 
        'vente', // Include vente to get menu attributes
        'achat'
    ])
        ->where('consumption_date', $date)
        ->where('type', $typeOperation);

    if ($typeOperation === 'sortie' && $typeCommande && $typeCommande !== 'all') {
        $query->where('type_commande', $typeCommande);
    }

    if ($typeOperation === 'sortie' && !empty($typeMenu)) {
        $query->where('type_menu', $typeMenu);
    }

    // Filter by category class if provided
    if (!empty($filterCategory)) {
        // Get category IDs that belong to the specified class
        $categoryIds = \App\Models\Category::where('classe', $filterCategory)
            ->pluck('id')
            ->toArray();
        
        if (!empty($categoryIds)) {
            // Find consumption records that have product details with these categories
            $query->whereHas('productDetails.product', function($q) use ($categoryIds) {
                $q->whereIn('id_categorie', $categoryIds);
            });
        }
    }

    $consumptions = $query->get();

    if ($typeOperation === 'entree') {
        // Format data for achats (entries)
        $formattedData = [];
        $grandTotal = 0;
        $grandTotalTVA = 0;
        $categoryCosts = [];

        foreach ($consumptions as $consumption) {
            $productsList = [];
            foreach ($consumption->productDetails as $detail) {
                $productsList[] = [
                    'product_id' => $detail->product_id,
                    'name' => $detail->product->name,
                    'category_name' => $detail->product->category->name ?? 'Non catégorisé',
                    'id_categorie' => $detail->product->id_categorie,
                    'quantity' => $detail->quantity,
                    'unit_price' => $detail->unit_price,
                    'tva_rate' => $detail->tva_rate,
                    'tva_amount' => $detail->tva_amount,
                    'total_price' => $detail->total_price,
                    'unite_mesure' => $detail->product->unite->name ?? ''
                ];
                
                // Accumulate category costs
                $categoryId = $detail->product->id_categorie;
                $categoryName = $detail->product->category->name ?? 'Non catégorisé';
                
                if (!isset($categoryCosts[$categoryId])) {
                    $categoryCosts[$categoryId] = [
                        'id' => $categoryId,
                        'name' => $categoryName,
                        'total_cost' => 0,
                        'total_tva' => 0
                    ];
                }
                
                $categoryCosts[$categoryId]['total_cost'] += $detail->total_price;
                $categoryCosts[$categoryId]['total_tva'] += $detail->tva_amount;
            }

            $formattedData[] = [
                'achat_id' => $consumption->achat_id,
                'type_commande' => $consumption->type_commande,
                'total_cost' => $consumption->total_cost,
                'total_tva' => $consumption->total_tva ?? 0,
                'products' => $productsList,
                'category_costs' => $consumption->category_costs ?? []
            ];

            $grandTotal += $consumption->total_cost;
            $grandTotalTVA += $consumption->total_tva ?? 0;
        }

        return [
            'date' => $date->format('d/m/Y'),
            'type_operation' => $typeOperation,
            'consumptions' => $formattedData,
            'grand_totals' => [
                'total_cost' => $grandTotal,
                'total_tva' => $grandTotalTVA
            ],
            'global_category_costs' => array_values($categoryCosts)
        ];
    } else {
        // Format data for ventes (sorties)
        if ($typeCommande === 'Fournitures et matériels' || $typeCommande === 'Non Alimentaire') {
            // Don't group by menu for Fournitures et matériels or Non Alimentaire
            $totalCost = 0;
            $totalTVA = 0;
            $allProducts = [];
            $categoryCosts = [];
            
            foreach ($consumptions as $consumption) {
                $totalCost += $consumption->total_cost;
                $totalTVA += $consumption->total_tva ?? 0;
                
                // Include category costs from model if available
                if (!empty($consumption->category_costs)) {
                    foreach ($consumption->category_costs as $categoryId => $categoryData) {
                        if (!isset($categoryCosts[$categoryId])) {
                            $categoryCosts[$categoryId] = [
                                'id' => $categoryId,
                                'name' => $categoryData['name'],
                                'total_cost' => 0,
                                'total_tva' => 0
                            ];
                        }
                        $categoryCosts[$categoryId]['total_cost'] += $categoryData['total_cost'];
                        $categoryCosts[$categoryId]['total_tva'] += $categoryData['total_tva'];
                    }
                } else {
                    // Calculate from product details if not available in model
                    foreach ($consumption->productDetails as $detail) {
                        $categoryId = $detail->product->id_categorie;
                        $categoryName = $detail->product->category->name ?? 'Non catégorisé';
                        
                        if (!isset($categoryCosts[$categoryId])) {
                            $categoryCosts[$categoryId] = [
                                'id' => $categoryId,
                                'name' => $categoryName,
                                'total_cost' => 0,
                                'total_tva' => 0
                            ];
                        }
                        
                        $categoryCosts[$categoryId]['total_cost'] += $detail->total_price;
                        $categoryCosts[$categoryId]['total_tva'] += $detail->tva_amount;
                    }
                }
                
                foreach ($consumption->productDetails as $detail) {
                    $productId = $detail->product_id;
                    
                    if (!isset($allProducts[$productId])) {
                        $allProducts[$productId] = [
                            'product_id' => $productId,
                            'name' => $detail->product->name,
                            'category_name' => $detail->product->category->name ?? 'Non catégorisé',
                            'id_categorie' => $detail->product->id_categorie,
                            'quantity' => 0,
                            'unit_price' => $detail->unit_price,
                            'tva_rate' => $detail->tva_rate,
                            'tva_amount' => 0,
                            'total_price' => 0,
                            'unite_mesure' => $detail->product->unite->name ?? ''
                        ];
                    }
                    
                    $allProducts[$productId]['quantity'] += $detail->quantity;
                    $allProducts[$productId]['tva_amount'] += $detail->tva_amount;
                    $allProducts[$productId]['total_price'] += $detail->total_price;
                }
            }
            
            return [
                'date' => $date->format('d/m/Y'),
                'type_operation' => $typeOperation,
                'type_commande' => $typeCommande,
                'consumptions' => [[
                    'type_menu' => $typeCommande,
                    'total_cost' => $totalCost,
                    'total_tva' => $totalTVA,
                    'total_people' => 0,
                    'products' => array_values($allProducts),
                    'category_costs' => array_values($categoryCosts)
                ]],
                'grand_totals' => [
                    'total_cost' => $totalCost,
                    'total_tva' => $totalTVA,
                    'total_people' => 0
                ],
                'global_category_costs' => array_values($categoryCosts)
            ];
        } else {
            // Regular menu grouping logic with menu attributes
            $groupedData = [];
            $grandTotal = 0;
            $grandTotalTVA = 0;
            $grandTotalPeople = 0;
            $globalCategoryCosts = [];

            foreach ($consumptions as $consumption) {
                $menuType = $consumption->type_menu ?? 'Sans Menu';
                
                if (!isset($groupedData[$menuType])) {
                    $groupedData[$menuType] = [
                        'type_menu' => $menuType,
                        // Add menu attributes from the vente
                        'entree' => null,
                        'plat_principal' => null,
                        'accompagnement' => null,
                        'dessert' => null,
                        'eleves' => 0,
                        'personnel' => 0,
                        'invites' => 0,
                        'divers' => 0,
                        'total_people' => 0,
                        'total_cost' => 0,
                        'total_tva' => 0,
                        'products' => [],
                        'category_costs' => []
                    ];
                }

                // Update the menu attributes if they exist from the vente
                if ($consumption->vente) {
                    if ($consumption->vente->entree && !$groupedData[$menuType]['entree']) {
                        $groupedData[$menuType]['entree'] = $consumption->vente->entree;
                    }
                    if ($consumption->vente->plat_principal && !$groupedData[$menuType]['plat_principal']) {
                        $groupedData[$menuType]['plat_principal'] = $consumption->vente->plat_principal;
                    }
                    if ($consumption->vente->accompagnement && !$groupedData[$menuType]['accompagnement']) {
                        $groupedData[$menuType]['accompagnement'] = $consumption->vente->accompagnement;
                    }
                    if ($consumption->vente->dessert && !$groupedData[$menuType]['dessert']) {
                        $groupedData[$menuType]['dessert'] = $consumption->vente->dessert;
                    }
                }

                $groupedData[$menuType]['eleves'] += $consumption->eleves;
                $groupedData[$menuType]['personnel'] += $consumption->personnel;
                $groupedData[$menuType]['invites'] += $consumption->invites;
                $groupedData[$menuType]['divers'] += $consumption->divers;
                $groupedData[$menuType]['total_people'] += $consumption->total_people;
                
                // Include category costs from model if available
                if (!empty($consumption->category_costs)) {
                    foreach ($consumption->category_costs as $categoryId => $categoryData) {
                        // Add to menu category costs
                        if (!isset($groupedData[$menuType]['category_costs'][$categoryId])) {
                            $groupedData[$menuType]['category_costs'][$categoryId] = [
                                'id' => $categoryId,
                                'name' => $categoryData['name'],
                                'total_cost' => 0,
                                'total_tva' => 0
                            ];
                        }
                        $groupedData[$menuType]['category_costs'][$categoryId]['total_cost'] += $categoryData['total_cost'];
                        $groupedData[$menuType]['category_costs'][$categoryId]['total_tva'] += $categoryData['total_tva'];
                        
                        // Add to global category costs
                        if (!isset($globalCategoryCosts[$categoryId])) {
                            $globalCategoryCosts[$categoryId] = [
                                'id' => $categoryId,
                                'name' => $categoryData['name'],
                                'total_cost' => 0,
                                'total_tva' => 0
                            ];
                        }
                        $globalCategoryCosts[$categoryId]['total_cost'] += $categoryData['total_cost'];
                        $globalCategoryCosts[$categoryId]['total_tva'] += $categoryData['total_tva'];
                    }
                    
                    // Add category costs to the menu total
                    $menuTotalCost = array_sum(array_column($consumption->category_costs, 'total_cost'));
                    $menuTotalTVA = array_sum(array_column($consumption->category_costs, 'total_tva'));
                    
                    $groupedData[$menuType]['total_cost'] += $menuTotalCost;
                    $groupedData[$menuType]['total_tva'] += $menuTotalTVA;
                } else {
                    // Calculate from product details if not available in model
                    $menuCategoryCosts = [];
                    
                    foreach ($consumption->productDetails as $detail) {
                        $categoryId = $detail->product->id_categorie;
                        $categoryName = $detail->product->category->name ?? 'Non catégorisé';
                        
                        // Add to menu category costs
                        if (!isset($menuCategoryCosts[$categoryId])) {
                            $menuCategoryCosts[$categoryId] = [
                                'id' => $categoryId,
                                'name' => $categoryName,
                                'total_cost' => 0,
                                'total_tva' => 0
                            ];
                        }
                        $menuCategoryCosts[$categoryId]['total_cost'] += $detail->total_price;
                        $menuCategoryCosts[$categoryId]['total_tva'] += $detail->tva_amount;
                        
                        // Add to global category costs
                        if (!isset($globalCategoryCosts[$categoryId])) {
                            $globalCategoryCosts[$categoryId] = [
                                'id' => $categoryId,
                                'name' => $categoryName,
                                'total_cost' => 0,
                                'total_tva' => 0
                            ];
                        }
                        $globalCategoryCosts[$categoryId]['total_cost'] += $detail->total_price;
                        $globalCategoryCosts[$categoryId]['total_tva'] += $detail->tva_amount;
                    }
                    
                    // Merge menu category costs
                    foreach ($menuCategoryCosts as $categoryId => $categoryData) {
                        if (!isset($groupedData[$menuType]['category_costs'][$categoryId])) {
                            $groupedData[$menuType]['category_costs'][$categoryId] = $categoryData;
                        } else {
                            $groupedData[$menuType]['category_costs'][$categoryId]['total_cost'] += $categoryData['total_cost'];
                            $groupedData[$menuType]['category_costs'][$categoryId]['total_tva'] += $categoryData['total_tva'];
                        }
                    }
                    
                    $groupedData[$menuType]['total_cost'] += $consumption->total_cost;
                    $groupedData[$menuType]['total_tva'] += $consumption->total_tva ?? 0;
                }

                // Group products by product_id
                foreach ($consumption->productDetails as $detail) {
                    $productId = $detail->product_id;
                    
                    if (!isset($groupedData[$menuType]['products'][$productId])) {
                        $groupedData[$menuType]['products'][$productId] = [
                            'product_id' => $productId,
                            'name' => $detail->product->name,
                            'category_name' => $detail->product->category->name ?? 'Non catégorisé',
                            'id_categorie' => $detail->product->id_categorie,
                            'quantity' => 0,
                            'unit_price' => $detail->unit_price,
                            'tva_rate' => $detail->tva_rate,
                            'tva_amount' => 0,
                            'total_price' => 0,
                            'unite_mesure' => $detail->product->unite->name ?? ''
                        ];
                    }

                    $groupedData[$menuType]['products'][$productId]['quantity'] += $detail->quantity;
                    $groupedData[$menuType]['products'][$productId]['tva_amount'] += $detail->tva_amount;
                    $groupedData[$menuType]['products'][$productId]['total_price'] += $detail->total_price;
                }

                $grandTotal += $consumption->total_cost;
                $grandTotalTVA += $consumption->total_tva ?? 0;
                $grandTotalPeople += $consumption->total_people;
            }

            // Calculate averages and format final data
            $finalData = [];
            foreach ($groupedData as $menuType => $data) {
                $data['average_cost_per_person'] = $data['total_people'] > 0 
                    ? $data['total_cost'] / $data['total_people'] 
                    : 0;
                $data['products'] = array_values($data['products']);
                $data['category_costs'] = array_values($data['category_costs']);
                $finalData[] = $data;
            }

            return [
                'date' => $date->format('d/m/Y'),
                'type_operation' => $typeOperation,
                'type_commande' => $typeCommande,
                'consumptions' => $finalData,
                'grand_totals' => [
                    'total_cost' => $grandTotal,
                    'total_tva' => $grandTotalTVA,
                    'total_people' => $grandTotalPeople
                ],
                'global_category_costs' => array_values($globalCategoryCosts)
            ];
        }
    }
}

    /**
     * Method to process consumption for a specific vente
     */
public function processVenteConsumption($venteId)
{
    $vente = Vente::find($venteId);
    
    if (!$vente || $vente->status !== 'Validation') {
        return;
    }
    
    DB::beginTransaction();
    try {
        $consumption = DailyConsumption::where('vente_id', $vente->id)->first();
        
        if (!$consumption) {
            $consumption = DailyConsumption::create([
                'consumption_date' => $vente->created_at->format('Y-m-d'),
                'vente_id' => $vente->id,
                'type_commande' => $vente->type_commande,
                'type_menu' => $vente->type_menu,
                'total_people' => $vente->eleves + $vente->personnel + $vente->invites + $vente->divers,
                'eleves' => $vente->eleves,
                'personnel' => $vente->personnel,
                'invites' => $vente->invites,
                'divers' => $vente->divers,
                'type' => 'sortie'
            ]);
        }

        // Clear existing product details
        ConsumptionProductDetail::where('consumption_id', $consumption->id)->delete();
        
        $consumptionTotal = 0;
        $totalTVA = 0;
        $categoryCosts = []; // Initialize category costs array
        
        $ligneVentes = LigneVente::where('idvente', $vente->id)->get();
        
        foreach ($ligneVentes as $ligne) {
            $product = Product::with(['tva', 'category'])->find($ligne->idproduit);
            if ($product) {
                // Determine TVA rate
                $tvaRate = $product->tva ? $product->tva->value : 0;
                
                // Calculate prices
                $unitPrice = $product->price_achat; // HT price
                $tvaAmount = $unitPrice * ($tvaRate / 100) * $ligne->qte;
                $totalPrice = $unitPrice * $ligne->qte * (1 + ($tvaRate / 100)); // TTC price
                
                $consumptionTotal += $totalPrice;
                $totalTVA += $tvaAmount;

                ConsumptionProductDetail::create([
                    'consumption_id' => $consumption->id,
                    'product_id' => $product->id,
                    'ligne_vente_id' => $ligne->id,
                    'quantity' => $ligne->qte,
                    'unit_price' => $unitPrice,
                    'tva_rate' => $tvaRate,
                    'tva_amount' => $tvaAmount,
                    'total_price' => $totalPrice
                ]);
                
                // Track category costs
                $categoryId = $product->id_categorie;
                $categoryName = $product->category->name ?? 'Non catégorisé';
                
                if (!isset($categoryCosts[$categoryId])) {
                    $categoryCosts[$categoryId] = [
                        'id' => $categoryId,
                        'name' => $categoryName,
                        'total_cost' => 0,
                        'total_tva' => 0
                    ];
                }
                
                $categoryCosts[$categoryId]['total_cost'] += $totalPrice;
                $categoryCosts[$categoryId]['total_tva'] += $tvaAmount;
            }
        }

        // Update consumption totals
        $consumption->total_cost = $consumptionTotal;
        $consumption->total_tva = $totalTVA; 
        $consumption->average_cost_per_person = $consumption->total_people > 0 
            ? $consumptionTotal / $consumption->total_people 
            : 0;
        $consumption->category_costs = $categoryCosts; // Save category costs
        $consumption->save();
        
        DB::commit();
    } catch (\Exception $e) {
        DB::rollBack();
        Log::error('Error processing vente consumption: ' . $e->getMessage());
        throw $e;
    }
}
 public function processAchatConsumption($achatId)
{
    $achat = Achat::find($achatId);
    
    if (!$achat || $achat->status !== 'Validation') {
        return;
    }
    
    DB::beginTransaction();
    try {
        $consumption = DailyConsumption::where('achat_id', $achat->id)->first();
        
        if (!$consumption) {
            $consumption = DailyConsumption::create([
                'consumption_date' => $achat->created_at->format('Y-m-d'),
                'achat_id' => $achat->id,
                'type_commande' => null, // Set to null for achats
                'type' => 'entree'
            ]);
        }

        // Clear existing product details
        ConsumptionProductDetail::where('consumption_id', $consumption->id)->delete();
        
        $consumptionTotal = 0;
        $totalTVA = 0;
        $categoryCosts = []; // Initialize category costs array
        
        $ligneAchats = LigneAchat::where('idachat', $achat->id)->get();
        
        foreach ($ligneAchats as $ligne) {
            $product = Product::with(['tva', 'category'])->find($ligne->idproduit);
            if ($product) {
                // Determine TVA rate
                $tvaRate = $product->tva ? $product->tva->value : 0;
                
                // Calculate prices
                $unitPrice = $product->price_achat; // HT price
                $tvaAmount = $unitPrice * ($tvaRate / 100) * $ligne->qte;
                $totalPrice = $unitPrice * $ligne->qte * (1 + ($tvaRate / 100)); // TTC price
                
                $consumptionTotal += $totalPrice;
                $totalTVA += $tvaAmount;

                ConsumptionProductDetail::create([
                    'consumption_id' => $consumption->id,
                    'product_id' => $product->id,
                    'ligne_achat_id' => $ligne->id,
                    'quantity' => $ligne->qte,
                    'unit_price' => $unitPrice,
                    'tva_rate' => $tvaRate,
                    'tva_amount' => $tvaAmount,
                    'total_price' => $totalPrice
                ]);
                
                // Track category costs
                $categoryId = $product->id_categorie;
                $categoryName = $product->category->name ?? 'Non catégorisé';
                
                if (!isset($categoryCosts[$categoryId])) {
                    $categoryCosts[$categoryId] = [
                        'id' => $categoryId,
                        'name' => $categoryName,
                        'total_cost' => 0,
                        'total_tva' => 0
                    ];
                }
                
                $categoryCosts[$categoryId]['total_cost'] += $totalPrice;
                $categoryCosts[$categoryId]['total_tva'] += $tvaAmount;
            }
        }

        // Update consumption total
        $consumption->total_cost = $consumptionTotal;
        $consumption->total_tva = $totalTVA;
        $consumption->category_costs = $categoryCosts; // Save category costs
        $consumption->save();
        
        DB::commit();
    } catch (\Exception $e) {
        DB::rollBack();
        Log::error('Error processing achat consumption: ' . $e->getMessage());
        throw $e;
    }
}
    /**
     * Method to sync all existing data to consumption table
     */
    public function syncAllConsumption()
    {
        try {
            $ventes = Vente::where('status', 'Validation')->get();
            $achats = Achat::where('status', 'Validation')->get();
            
            foreach ($ventes as $vente) {
                $this->processVenteConsumption($vente->id);
            }
            
            foreach ($achats as $achat) {
                $this->processAchatConsumption($achat->id);
            }
            
            return response()->json([
                'message' => 'Consumption data synced successfully',
                'total_ventes_processed' => $ventes->count(),
                'total_achats_processed' => $achats->count()
            ]);
        } catch (\Exception $e) {
            Log::error('Error syncing consumption data: ' . $e->getMessage());
            return response()->json([
                'error' => 'Error syncing consumption data'
            ], 500);
        }
    }
public function exportPDF(Request $request)
{
    $request->validate([
        'date' => 'required|date',
        'type_operation' => 'required|string|in:entree,sortie',
        'type_commande' => 'sometimes|string|nullable',
        'type_menu' => 'sometimes|string|nullable'
    ]);

    $date = Carbon::parse($request->date);
    $typeOperation = $request->type_operation;
    $typeCommande = $request->type_commande;
    $typeMenu = $request->type_menu;
    
    try {
        // Get consumption data for the selected menu
        $consumptionData = $this->getFilteredConsumptionData($date, $typeOperation, $typeCommande, $typeMenu);
        
        if (!isset($consumptionData['consumptions']) || empty($consumptionData['consumptions'])) {
            return response()->json([
                'status' => 404,
                'message' => 'Aucune commande trouvée pour cette date'
            ]);
        }
        
        // ===== CALCULATE PRIX MOYEN GÉNÉRAL FOR ALL MENUS ON THIS DATE =====
        // Get ALL menus for this date (ignore the type_menu filter)
        $allMenusData = $this->getFilteredConsumptionData($date, $typeOperation, $typeCommande, null); // null = all menus
        
        $generalAveragePrice = 0;
        $sumAllMenusCost = 0;
        $sumAllMenusPeople = 0;
        $allMenusCount = 0;
        
        if (isset($allMenusData['consumptions']) && is_array($allMenusData['consumptions'])) {
            $allMenusCount = count($allMenusData['consumptions']);
            
            foreach ($allMenusData['consumptions'] as $menu) {
                $sumAllMenusCost += floatval($menu['total_cost'] ?? 0);
                $sumAllMenusPeople += intval($menu['total_people'] ?? 0);
            }
            
            if ($sumAllMenusPeople > 0) {
                $generalAveragePrice = $sumAllMenusCost / $sumAllMenusPeople;
            }
        }
        
        // Calculate individual menu average (for the selected menu)
        $averagePrice = 0;
        if (isset($consumptionData['consumptions'][0])) {
            $firstConsumption = $consumptionData['consumptions'][0];
            if (isset($firstConsumption['total_people']) && $firstConsumption['total_people'] > 0) {
                $averagePrice = floatval($firstConsumption['total_cost']) / intval($firstConsumption['total_people']);
            }
        }
        
        // Get logo images
        $imagePath = public_path('images/logo_top.png');
        $imageData = base64_encode(file_get_contents($imagePath));
        $logo_bottom = public_path('images/logo_bottom.png');
        $imageData_bottom = base64_encode(file_get_contents($logo_bottom));
        
        // Generate PDF - PASS THE CALCULATED AVERAGES
        $pdf = Pdf::loadView('consumption.pdf_template', [
            'data' => $consumptionData,
            'date' => $date->format('d/m/Y'),
            'imageData' => $imageData,
            'imageData_bottom' => $imageData_bottom,
            // Pass pre-calculated averages
            'averagePrice' => $averagePrice,  // For selected menu
            'generalAveragePrice' => $generalAveragePrice,  // For ALL menus on this date
            'allMenusCount' => $allMenusCount,  // How many total menus exist
            'sumAllMenusPeople' => $sumAllMenusPeople
        ]);
        
        // Set page size and orientation
        $pdf->setPaper('a4', 'portrait');
        
        // Generate filename
        $filename = 'consumption_' . $date->format('Ymd') . '_' . $typeOperation;
        if ($typeCommande) {
            $filename .= '_' . str_replace(' ', '_', $typeCommande);
        }
        $filename .= '.pdf';
        
        // Download the PDF
        return $pdf->download($filename);
        
    } catch (\Exception $e) {
        Log::error('Error generating PDF: ' . $e->getMessage());
        Log::error($e->getTraceAsString());
        return response()->json([
            'status' => 500,
            'message' => 'Erreur lors de la génération du PDF: ' . $e->getMessage()
        ], 500);
    }
}
public function exportAllConsumptionPDF(Request $request)
{
    $request->validate([
        'date' => 'required|date',
        'view_mode' => 'sometimes|string|in:full,sideBySide'
    ]);

    $date = Carbon::parse($request->date);
    $viewMode = $request->view_mode ?? 'sideBySide'; // Default to side-by-side view
    
    try {
        // Create a new request with the date
        $consumptionRequest = new Request(['date' => $date->format('Y-m-d')]);
        
        // Get the raw data from getAllConsumptionData method
        $response = $this->getAllConsumptionData($consumptionRequest);
        
        // Process the response based on its type
        if ($response instanceof \Illuminate\Http\JsonResponse) {
            // Convert JsonResponse to array
            $responseData = json_decode($response->getContent(), true);
            
            // Check status and data
            if ($responseData['status'] !== 200 || empty($responseData['data'])) {
                return response()->json([
                    'status' => 404,
                    'message' => 'Aucune donnée trouvée pour cette date'
                ]);
            }
            
            $consumptionData = $responseData['data'];
        } else {
            // If it's already an array
            $consumptionData = $response['data'] ?? [];
            
            if (empty($consumptionData)) {
                return response()->json([
                    'status' => 404,
                    'message' => 'Aucune donnée trouvée pour cette date'
                ]);
            }
        }
        
        // Load and encode the header image
        $imagePath = public_path('images/lgtitlee.png');
        $imageData = '';
        if (file_exists($imagePath)) {
            $imageData = base64_encode(file_get_contents($imagePath));
        }
        
        // Load and encode the menu image (keeping this for backward compatibility)
        $menuImagePath = public_path('images/menu.png');
        $menuImageData = '';
        if (file_exists($menuImagePath)) {
            $menuImageData = base64_encode(file_get_contents($menuImagePath));
        }
        
        // Select the appropriate view based on view_mode
        $view = ($viewMode === 'sideBySide') 
            ? 'consumption.all_pdf_template' 
            : 'consumption.all';
        
        $options = [
            'enable-javascript' => true,
            'javascript-delay' => 1000,
            'no-stop-slow-scripts' => true,
            'margin-top' => '5mm', 
            'margin-right' => '5mm', 
            'margin-bottom' => '5mm', 
            'margin-left' => '5mm', 
            'orientation' => 'landscape',
            'page-size' => 'A4',
            'dpi' => 300,
            'image-dpi' => 300,
            'enable-smart-shrinking' => true,
            'no-background' => false,
            'lowquality' => false,
            'print-media-type' => true,
            'zoom' => 1.0,
            'viewport-size' => '1280x1024'
        ];
        
        // Generate PDF using our view - now including both imageData and menuImageData
        $pdf = Pdf::loadView($view, [
            'data' => $consumptionData,
            'date' => $date->format('d/m/Y'),
            'imageData' => $imageData,
            'menuImageData' => $menuImageData // This is still passed but will be replaced by menu tables
        ]);
        
        // Apply PDF options
        $pdf->setOptions($options);
        
        // Generate filename
        $filename = 'all_consumption_' . $date->format('Ymd');
        if ($viewMode === 'sideBySide') {
            $filename .= '_side_by_side';
        }
        $filename .= '.pdf';
        
        // Download the PDF
        return $pdf->download($filename);
        
    } catch (\Exception $e) {
        Log::error('Error generating PDF: ' . $e->getMessage());
        Log::error($e->getTraceAsString());
        return response()->json([
            'status' => 500,
            'message' => 'Erreur lors de la génération du PDF: ' . $e->getMessage()
        ], 500);
    }
}
    // Alternative method if you don't want to use a headless browser
    // public function exportAllConsumptionPDFAlternative(Request $request)
    // {
    //     $request->validate([
    //         'date' => 'required|date'
    //     ]);

    //     $date = Carbon::parse($request->date);
        
    //     try {
    //         // Process or retrieve consumption data
    //         $this->processConsumptionForDate($date);
            
    //         // Get all consumption data
    //         $result = $this->getAllConsumptionData($request);
    //         $responseData = json_decode($result->getContent(), true);
            
    //         if ($responseData['status'] !== 200) {
    //             return response()->json([
    //                 'status' => $responseData['status'],
    //                 'message' => $responseData['message'] ?? 'Aucune donnée trouvée pour cette date'
    //             ]);
    //         }
            
    //         $consumptionData = $responseData['data'];
            
    //         // Get logo images
    //         $headerImagePath = public_path('images/top-con.jpg');
    //         $headerImageData = base64_encode(file_get_contents($headerImagePath));
            
    //         $footerImagePath = public_path('images/c3.png');
    //         $footerImageData = base64_encode(file_get_contents($footerImagePath));
            
    //         // Create a view that processes the data with the same logic
    //         // as your JavaScript function but in PHP
    //         $htmlView = view('consumption.pdf_side_by_side', [
    //             'data' => $consumptionData,
    //             'date' => $date->format('d/m/Y'),
    //             'headerImageData' => $headerImageData,
    //             'footerImageData' => $footerImageData
    //         ])->render();
            
    //         // Generate PDF using the HTML
    //         $pdf = PDF::loadHTML($htmlView);
            
    //         // Set page size and orientation
    //         $pdf->setPaper('a4', 'portrait');
            
    //         // Generate filename
    //         $filename = 'consumption_complete_' . $date->format('Ymd') . '.pdf';
            
    //         // Download the PDF
    //         return $pdf->download($filename);
            
    //     } catch (\Exception $e) {
    //         Log::error('Error generating PDF: ' . $e->getMessage());
    //         Log::error($e->getTraceAsString());
    //         return response()->json([
    //             'status' => 500,
    //             'message' => 'Erreur lors de la génération du PDF: ' . $e->getMessage()
    //         ], 500);
    //     }
    // }

public function getAllConsumptionData(Request $request)
{
    $request->validate([
        'date' => 'required|date'
    ]);

    $date = Carbon::parse($request->date);
    
    try {
        // Process or retrieve consumption data
        $this->processConsumptionForDate($date);
        
        // Get entrees
        $entrees = $this->getFilteredConsumptionData($date, 'entree');
        
        // Get all three types of sorties
        $fournitures = $this->getFilteredConsumptionData($date, 'sortie', 'Fournitures et matériels');
        $alimentaire = $this->getFilteredConsumptionData($date, 'sortie', 'Alimentaire');
        $nonAlimentaire = $this->getFilteredConsumptionData($date, 'sortie', 'Non Alimentaire');
        
        // Combine the sorties data
        $combinedSorties = [
            'date' => $date->format('d/m/Y'),
            'type_operation' => 'sortie',
            'consumptions' => [],
            'grand_totals' => [
                'total_cost' => 0,
                'total_people' => 0
            ]
        ];
        
        // Add fournitures data
        if (isset($fournitures['consumptions'])) {
            $combinedSorties['consumptions'] = array_merge($combinedSorties['consumptions'], $fournitures['consumptions']);
            $combinedSorties['grand_totals']['total_cost'] += $fournitures['grand_totals']['total_cost'] ?? 0;
        }
        
        // Add alimentaire data
        if (isset($alimentaire['consumptions'])) {
            $combinedSorties['consumptions'] = array_merge($combinedSorties['consumptions'], $alimentaire['consumptions']);
            $combinedSorties['grand_totals']['total_cost'] += $alimentaire['grand_totals']['total_cost'] ?? 0;
            $combinedSorties['grand_totals']['total_people'] += $alimentaire['grand_totals']['total_people'] ?? 0;
        }
        
        // Add non alimentaire data
        if (isset($nonAlimentaire['consumptions'])) {
            $combinedSorties['consumptions'] = array_merge($combinedSorties['consumptions'], $nonAlimentaire['consumptions']);
            $combinedSorties['grand_totals']['total_cost'] += $nonAlimentaire['grand_totals']['total_cost'] ?? 0;
        }
        
        // NEW: Get all products regardless of consumption
        $allProducts = Product::with('category')->get()->map(function($product) {
            return [
                'product_id' => $product->id,
                'name' => $product->name,
                'category_name' => $product->category->name ?? 'Non catégorisé',
                'id_categorie' => $product->id_categorie,
                'unit_price' => $product->price_achat,
                'quantity' => 0,
                'total_price' => 0
            ];
        });
        
        // Format the response
        $responseData = [
            'date' => $date->format('d/m/Y'),
            'entrees' => $entrees,
            'sorties' => $combinedSorties,
            'all_products' => $allProducts // NEW: Add all products to the response
        ];
        
        // Only check for entries and sorties for the response status
        if (empty($entrees['consumptions']) && empty($combinedSorties['consumptions'])) {
            // Even if no consumption found, we still return the allProducts data with status 200
            return response()->json([
                'status' => 200,
                'data' => $responseData,
                'message' => 'Aucune consommation trouvée pour cette date'
            ]);
        }

        return response()->json([
            'status' => 200,
            'data' => $responseData
        ]);

    } catch (\Exception $e) {
        Log::error('Error fetching all consumption data: ' . $e->getMessage());
        Log::error($e->getTraceAsString());
        return response()->json([
            'status' => 500,
            'message' => 'Erreur lors de la récupération des données'
        ], 500);
    }
}
public function allConsumption()
{
    if (!auth()->user()->can('Voir-Consommation-Complète')) {
        abort(403, 'Vous n\'avez pas la permission de voir la consommation complète');
    }
    
    return view('consumption.all');
}

public function getCategoryCostsData(Request $request)
{
    $request->validate([
        'date' => 'required|date',
        'type_menu' => 'required|string'
    ]);

    $date = Carbon::parse($request->date);
    $typeMenu = $request->type_menu;
    
    try {
        // Process consumption data for the date
        $this->processConsumptionForDate($date);
        
        // Get all food-related categories regardless of usage
        $foodCategories = \App\Models\Category::where('classe', 'DENRÉES ALIMENTAIRES')
            ->get();
        
        // Initialize data structure with all food categories set to zero
        $categoryCosts = [];
        foreach ($foodCategories as $category) {
            $categoryCosts[$category->id] = [
                'id' => $category->id,
                'name' => $category->name,
                'total_cost' => 0,
                'total_tva' => 0
            ];
        }
        
        $totalCost = 0;
        $totalPeople = 0;
        
        // Query to get consumption data for "sortie" and specified menu type
        $query = DailyConsumption::with(['productDetails.product.category'])
            ->where('consumption_date', $date)
            ->where('type', 'sortie')
            ->where('type_commande', 'Alimentaire');
        
        if ($typeMenu !== 'all') {
            $query->where('type_menu', $typeMenu);
        }

        $consumptions = $query->get();
        
        // Process the consumption data to update category costs
        foreach ($consumptions as $consumption) {
            $totalPeople += $consumption->total_people;

            // Process category costs
            if (!empty($consumption->category_costs)) {
                foreach ($consumption->category_costs as $categoryId => $categoryData) {
                    // Only include food categories
                    if (isset($categoryCosts[$categoryId])) {
                        $categoryCosts[$categoryId]['total_cost'] += $categoryData['total_cost'];
                        $categoryCosts[$categoryId]['total_tva'] += $categoryData['total_tva'];
                        $totalCost += $categoryData['total_cost'];
                    }
                }
            } else {
                // Calculate from product details if not available in model
                foreach ($consumption->productDetails as $detail) {
                    $categoryId = $detail->product->id_categorie;
                    
                    // Only include food categories
                    if (isset($categoryCosts[$categoryId])) {
                        $categoryCosts[$categoryId]['total_cost'] += $detail->total_price;
                        $categoryCosts[$categoryId]['total_tva'] += $detail->tva_amount;
                        $totalCost += $detail->total_price;
                    }
                }
            }
        }

        // Sort categories by name for consistent display
        uasort($categoryCosts, function($a, $b) {
            return $a['name'] <=> $b['name'];
        });
        
        // Calculate price per person
        $prixMoyen = $totalPeople > 0 ? $totalCost / $totalPeople : 0;
        
        // Format the response
        $responseData = [
            'date' => $date->format('d/m/Y'),
            'type_menu' => $typeMenu,
            'total_cost' => $totalCost,
            'total_people' => $totalPeople,
            'prix_moyen' => $prixMoyen,
            'category_costs' => array_values($categoryCosts)
        ];

        return response()->json([
            'status' => 200,
            'data' => $responseData
        ]);

    } catch (\Exception $e) {
        Log::error('Error fetching category costs data: ' . $e->getMessage());
        Log::error($e->getTraceAsString());
        return response()->json([
            'status' => 500,
            'message' => 'Erreur lors de la récupération des données: ' . $e->getMessage()
        ], 500);
    }
}
public function getMonthlyBreakdownData(Request $request)
{
    $request->validate([
        'month' => 'required|date_format:Y-m',
        'type_menu' => 'required|string',
        'type_commande' => 'required|string'
    ]);

    try {
        // Parse the first and last day of the selected month
        $firstDayOfMonth = Carbon::createFromFormat('Y-m', $request->month)->startOfMonth();
        $lastDayOfMonth = Carbon::createFromFormat('Y-m', $request->month)->endOfMonth();
        $typeMenu = $request->type_menu;
        $typeCommande = $request->type_commande;
        
        // Get all food-related categories regardless of usage
        $foodCategories = \App\Models\Category::where('classe', 'DENRÉES ALIMENTAIRES')
            ->get();
        
        // STEP 1: Get all unique days in the month that have actual Vente records
        $venteDates = Vente::where('status', 'Validation')
            ->where('type_commande', $typeCommande)
            ->whereBetween('created_at', [$firstDayOfMonth, $lastDayOfMonth])
            ->when($typeMenu !== 'all', function($query) use ($typeMenu) {
                return $query->where('type_menu', $typeMenu);
            })
            ->selectRaw('DATE(created_at) as date')
            ->distinct()
            ->pluck('date')
            ->toArray();
            
        // Sort dates
        sort($venteDates);
        
        // STEP 2: Process consumption data for ALL these dates first
        foreach ($venteDates as $dateString) {
            $date = Carbon::parse($dateString);
            $this->processConsumptionForDate($date);
        }
        
        $monthlyData = [];
        $monthTotalCost = 0;
        $monthTotalPeople = 0;
        
        // STEP 3: Now process each day to get the consumption data
        foreach ($venteDates as $dateString) {
            $date = Carbon::parse($dateString);
            
            // Initialize data structure with all food categories set to zero
            $dayCategoryCosts = [];
            foreach ($foodCategories as $category) {
                $dayCategoryCosts[$category->id] = [
                    'id' => $category->id,
                    'name' => $category->name,
                    'total_cost' => 0,
                    'total_tva' => 0
                ];
            }
            
            // Get consumption data for this day
            $query = DailyConsumption::with(['productDetails.product.category'])
                ->where('consumption_date', $date)
                ->where('type', 'sortie')
                ->where('type_commande', $typeCommande);
                
            if ($typeMenu !== 'all') {
                $query->where('type_menu', $typeMenu);
            }
            
            $consumptions = $query->get();
            
            // Skip this day if no consumption data found (shouldn't happen after processing)
            if ($consumptions->isEmpty()) {
                Log::warning("No consumption data found for date: " . $date->format('Y-m-d') . " after processing");
                continue;
            }
            
            $dayTotalCost = 0;
            $dayTotalPeople = 0;
            
            // Process the consumption data to update category costs
            foreach ($consumptions as $consumption) {
                $dayTotalPeople += $consumption->total_people;
                
                // Process category costs
                if (!empty($consumption->category_costs)) {
                    foreach ($consumption->category_costs as $categoryId => $categoryData) {
                        if (isset($dayCategoryCosts[$categoryId])) {
                            $dayCategoryCosts[$categoryId]['total_cost'] += $categoryData['total_cost'];
                            $dayCategoryCosts[$categoryId]['total_tva'] += $categoryData['total_tva'];
                            $dayTotalCost += $categoryData['total_cost'];
                        }
                    }
                } else {
                    // Calculate from product details if not available in model
                    foreach ($consumption->productDetails as $detail) {
                        $categoryId = $detail->product->id_categorie;
                        
                        if (isset($dayCategoryCosts[$categoryId])) {
                            $dayCategoryCosts[$categoryId]['total_cost'] += $detail->total_price;
                            $dayCategoryCosts[$categoryId]['total_tva'] += $detail->tva_amount;
                            $dayTotalCost += $detail->total_price;
                        }
                    }
                }
            }
            
            // Only add days that have actual costs
            if ($dayTotalCost > 0) {
                // Calculate price per person
                $dayPrixMoyen = $dayTotalPeople > 0 ? $dayTotalCost / $dayTotalPeople : 0;
                
                // Sort categories by name
                uasort($dayCategoryCosts, function($a, $b) {
                    return $a['name'] <=> $b['name'];
                });
                
                // Add daily data to monthly array
                $monthlyData[] = [
                    'date' => $date->format('d/m/Y'),
                    'total_cost' => $dayTotalCost,
                    'total_people' => $dayTotalPeople,
                    'prix_moyen' => $dayPrixMoyen,
                    'category_costs' => array_values($dayCategoryCosts)
                ];
                
                // Update monthly totals
                $monthTotalCost += $dayTotalCost;
                $monthTotalPeople += $dayTotalPeople;
            }
        }
        
        // Calculate monthly average price per person
        $monthPrixMoyen = $monthTotalPeople > 0 ? $monthTotalCost / $monthTotalPeople : 0;
        
        // Log for debugging
        Log::info("Monthly breakdown processed", [
            'month' => $firstDayOfMonth->format('Y-m'),
            'type_menu' => $typeMenu,
            'type_commande' => $typeCommande,
            'vente_dates_found' => count($venteDates),
            'days_with_consumption' => count($monthlyData),
            'total_cost' => $monthTotalCost
        ]);
        
        // Format the response
        $responseData = [
            'month' => $firstDayOfMonth->format('F Y'),
            'type_menu' => $typeMenu,
            'type_commande' => $typeCommande,
            'days_data' => $monthlyData,
            'month_totals' => [
                'total_cost' => $monthTotalCost,
                'total_people' => $monthTotalPeople,
                'prix_moyen' => $monthPrixMoyen
            ],
            'all_categories' => $foodCategories->map(function($category) {
                return [
                    'id' => $category->id,
                    'name' => $category->name
                ];
            })
        ];
        
        return response()->json([
            'status' => 200,
            'data' => $responseData
        ]);
        
    } catch (\Exception $e) {
        Log::error('Error fetching monthly breakdown data: ' . $e->getMessage());
        Log::error($e->getTraceAsString());
        return response()->json([
            'status' => 500,
            'message' => 'Erreur lors de la récupération des données: ' . $e->getMessage()
        ], 500);
    }
}

public function monthlyBreakdownView()
{
    // Check permission for viewing monthly breakdown
    if (!auth()->user()->can('Voir-Rapport-Mensuel-Consommation')) {
        abort(403, 'Vous n\'avez pas la permission de voir le rapport mensuel de consommation');
    }
    
    return view('consumption.monthly-category-costs');
}
public function exportMonthlyBreakdownPDF(Request $request)
{
    $request->validate([
        'month' => 'required|date_format:Y-m',
        'type_menu' => 'required|string',
        'type_commande' => 'required|string'
    ]);

    try {
        // Parse month for display
        $monthDate = Carbon::createFromFormat('Y-m', $request->month);
        $monthName = $monthDate->format('F Y');
        $typeMenu = $request->type_menu;
        
        // Create a descriptive title that includes menu type
        $reportTitle = "Consommation du mois " . $monthName;
        if ($typeMenu && $typeMenu !== 'all') {
            $reportTitle .= " - " . $typeMenu;
        }
        
        // Get the data directly from your existing method
        $response = $this->getMonthlyBreakdownData($request);
        
        // Parse the JSON response
        $responseData = json_decode($response->getContent(), true);
        
        // Check if we have data
        if ($responseData['status'] !== 200 || empty($responseData['data']['days_data'])) {
            return response()->json([
                'status' => 404,
                'message' => 'Aucune donnée trouvée pour ce mois et ces critères'
            ]);
        }
        
        // Get logo images
        $imagePath = public_path('images/logo_top.png');
        $imageData_top = base64_encode(file_get_contents($imagePath));
        $logo_bottom = public_path('images/logo_bottom.png');
        $imageData_bottom = base64_encode(file_get_contents($logo_bottom));
        
        // Generate PDF using the template
        $pdf = Pdf::loadView('consumption.monthly_breakdown_pdf', [
            'data' => $responseData['data'],
            'month' => $monthName,
            'report_title' => $reportTitle, // Pass the complete title
            'type_menu' => $typeMenu, // Pass menu type separately if needed
            'imageData_top' => $imageData_top,
            'imageData_bottom' => $imageData_bottom
        ]);
        
        // Set page size and orientation
        $pdf->setPaper('a4', 'portrait');
        
        // Generate filename that includes menu type
        $filename = 'reporting_mensuel_' . $monthDate->format('Y_m');
        if ($typeMenu && $typeMenu !== 'all') {
            $filename .= '_' . str_replace(' ', '_', strtolower($typeMenu));
        }
        $filename .= '.pdf';
        
        // Download the PDF
        return $pdf->download($filename);
        
    } catch (\Exception $e) {
        Log::error('Error generating monthly PDF: ' . $e->getMessage());
        Log::error($e->getTraceAsString());
        return response()->json([
            'status' => 500,
            'message' => 'Erreur lors de la génération du PDF: ' . $e->getMessage()
        ], 500);
    }
}

}