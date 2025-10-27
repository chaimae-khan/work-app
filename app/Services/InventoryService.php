<?php

namespace App\Services;

use App\Models\Inventory;
use App\Models\InventoryMonthlySummary;
use App\Models\InventoryYearlySummary;
use App\Models\Stock;
use App\Models\Vente;
use App\Models\LigneVente;
use App\Models\Achat;
use App\Models\LigneAchat;
use App\Models\Product;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;
use Illuminate\Support\Facades\Log;

class InventoryService
{
    /**
     * Get inventory data for a specific product, month, and year
     * Returns affect subsequent days but not previous days
     * Total shows sum of all reste magasin
     */
    public function getProductMonthlyData($productId, $year, $month)
    {
        try {
            // Get the first day of the month
            $startDate = Carbon::createFromDate($year, $month, 1)->startOfDay();
            
            // Get the last day of the month
            $endDate = Carbon::createFromDate($year, $month, 1)->endOfMonth()->endOfDay();
            
            // Get all inventory entries for this product in the specified month
            $inventoryEntries = Inventory::where('product_id', $productId)
                ->whereBetween('date', [$startDate, $endDate])
                ->orderBy('date', 'asc')
                ->get();
            
            // Get the stock at the end of the previous month
            $previousMonthEnd = Carbon::createFromDate($year, $month, 1)->subDay();
            $previousStock = $this->getStockAtDate($productId, $previousMonthEnd);
            $runningStock = $previousStock;
            
            // Get number of days in month
            $daysInMonth = Carbon::createFromDate($year, $month, 1)->daysInMonth;
            
            // Initialize each day with previous month's ending stock
            $result = [];
            for ($day = 1; $day <= $daysInMonth; $day++) {
                $result[$day] = [
                    'date' => Carbon::createFromDate($year, $month, $day)->format('Y-m-d'),
                    'entree' => 0,
                    'sortie' => 0,
                    'reste' => $runningStock
                ];
            }
            
            // Group entries by day
            $entriesByDay = [];
            foreach ($inventoryEntries as $entry) {
                $day = Carbon::parse($entry->date)->day;
                if (!isset($entriesByDay[$day])) {
                    $entriesByDay[$day] = [];
                }
                $entriesByDay[$day][] = $entry;
            }
            
            // Process entries for each day in chronological order
            ksort($entriesByDay); // Ensure days are processed in order
            
            foreach ($entriesByDay as $day => $dayEntries) {
                $entreeTotal = 0;
                $sortieTotal = 0;
                
                foreach ($dayEntries as $entry) {
                    $entreeTotal += $entry->entree;
                    $sortieTotal += $entry->sortie;
                }
                
                // Update running stock with this day's movements
                $runningStock += $entreeTotal - $sortieTotal;
                
                // Update the day's data
                $result[$day] = [
                    'date' => Carbon::createFromDate($year, $month, $day)->format('Y-m-d'),
                    'entree' => $entreeTotal,
                    'sortie' => $sortieTotal,
                    'reste' => $runningStock
                ];
                
                // CRITICAL: Update all subsequent days with the new running stock
                // This ensures returns affect following days but not previous days
                for ($nextDay = $day + 1; $nextDay <= $daysInMonth; $nextDay++) {
                    $result[$nextDay]['reste'] = $runningStock;
                }
            }
            
            // Calculate totals for display
            $totalEntrees = 0;
            $totalSorties = 0;
            $totalReste = 0;
            
            foreach ($result as $dayData) {
                $totalEntrees += $dayData['entree'];
                $totalSorties += $dayData['sortie'];
                $totalReste += $dayData['reste']; // Sum of all reste magasin values
            }
            
            // Return data with structure expected by frontend
            return [
                'days' => $result,
                'month_total' => [
                    'total_entrees' => $totalEntrees,
                    'total_sorties' => $totalSorties,
                    'total_reste' => $totalReste, // Total of all reste magasin
                    'end_stock' => $runningStock, // Final stock at end of month
                    'average_price' => 0 // Will be filled by monthly summary if available
                ]
            ];
            
        } catch (\Exception $e) {
            Log::error('Error in getProductMonthlyData: ' . $e->getMessage());
            throw $e;
        }
    }
    
    /**
     * Get the stock level at a specific date
     */
    private function getStockAtDate($productId, $date)
    {
        // Try to find the last inventory entry before or on the given date
        $lastEntry = Inventory::where('product_id', $productId)
            ->where('date', '<=', $date->format('Y-m-d'))
            ->orderBy('date', 'desc')
            ->orderBy('id', 'desc')
            ->first();
        
        if ($lastEntry) {
            return $lastEntry->reste;
        }
        
        // If no entry found, get the current stock and subtract all movements since the date
        $stock = Stock::where('id_product', $productId)->first();
        $currentStock = $stock ? $stock->quantite : 0;
        
        // Get all entries after the date
        $entriesAfter = Inventory::where('product_id', $productId)
            ->where('date', '>', $date->format('Y-m-d'))
            ->get();
        
        // Subtract all movements since the date
        foreach ($entriesAfter as $entry) {
            $currentStock -= $entry->entree;
            $currentStock += $entry->sortie;
        }
        
        return $currentStock;
    }
    
    /**
     * Calculate average purchase price for a product in a specific period
     * Uses the prices stored in the inventory table
     */
    private function calculateAveragePrice($productId, $year = null, $month = null, $day = null)
    {
        try {
            // Build query to get prices from inventory entries
            $query = Inventory::where('product_id', $productId)
                ->whereNotNull('prix_unitaire') // Only consider entries with prices
                ->where('entree', '>', 0); // Only consider purchase entries
            
            // Apply time filters if provided
            if ($year && $month && $day) {
                // Daily average
                $date = Carbon::createFromDate($year, $month, $day)->format('Y-m-d');
                $query->whereDate('date', $date);
            } elseif ($year && $month) {
                // Monthly average
                $query->whereYear('date', $year)
                      ->whereMonth('date', $month);
            } elseif ($year) {
                // Yearly average
                $query->whereYear('date', $year);
            }
            
            $inventoryEntries = $query->get();
            
            if ($inventoryEntries->isEmpty()) {
                // If no entries found with prices, get the product's default price
                $product = Product::find($productId);
                return $product ? $product->price_achat : 0;
            }
            
            // Group entries by day
            $dailyPrices = [];
            
            foreach ($inventoryEntries as $entry) {
                $entryDate = Carbon::parse($entry->date)->format('Y-m-d');
                
                if (!isset($dailyPrices[$entryDate])) {
                    $dailyPrices[$entryDate] = [];
                }
                
                // Store the price for this entry
                $dailyPrices[$entryDate][] = $entry->prix_unitaire;
            }
            
            // Calculate daily averages (simple average of prices for each day)
            $dailyAverages = [];
            foreach ($dailyPrices as $date => $prices) {
                $dailyAverages[$date] = array_sum($prices) / count($prices);
            }
            
            // If calculating for a specific day, return just that day's average
            if ($year && $month && $day) {
                $specificDate = Carbon::createFromDate($year, $month, $day)->format('Y-m-d');
                return isset($dailyAverages[$specificDate]) ? $dailyAverages[$specificDate] : 0;
            }
            
            // For monthly/yearly averages, calculate the average of daily averages
            $average = array_sum($dailyAverages) / count($dailyAverages);
            
            Log::info("Calculated average price for product ID: $productId, result: $average");
            
            return $average;
            
        } catch (\Exception $e) {
            Log::error('Error calculating average price: ' . $e->getMessage());
            
            // In case of error, return default product price
            $product = Product::find($productId);
            return $product ? $product->price_achat : 0;
        }
    }
    
    /**
     * Update inventory records for a sale
     */
    public function updateInventoryForSale($vente)
    {
        Log::info('Updating inventory for sale ID: ' . $vente->id);
        
        // Begin transaction
        DB::beginTransaction();
        
        try {
            // Get sale details
            $ligneVentes = LigneVente::where('idvente', $vente->id)->get();
            
            foreach ($ligneVentes as $ligneVente) {
                // Check if inventory entry already exists to prevent duplicates
                $existingEntry = Inventory::where('id_vente', $vente->id)
                    ->where('product_id', $ligneVente->idproduit)
                    ->first();
                
                if ($existingEntry) {
                    Log::info('Inventory entry already exists for sale ID: ' . $vente->id . ', product ID: ' . $ligneVente->idproduit);
                    continue;
                }
                
                // Get current stock level
                $stock = Stock::where('id_product', $ligneVente->idproduit)->first();
                $currentStock = $stock ? $stock->quantite : 0;
                
                // Get product purchase price
                $product = Product::find($ligneVente->idproduit);
                $prixAchat = $product ? $product->price_achat : 0;
                
                // Record inventory movement
                $inventory = new Inventory();
                $inventory->product_id = $ligneVente->idproduit;
                $inventory->date = $vente->created_at->format('Y-m-d');
                $inventory->entree = 0;
                $inventory->sortie = $ligneVente->qte;
                $inventory->reste = $currentStock; // Record current stock level
                $inventory->prix_unitaire = $prixAchat; // Store the purchase price
                $inventory->id_vente = $vente->id;
                $inventory->id_achat = null;
                $inventory->created_by = auth()->id() ?? 1; // Use authenticated user or default
                $inventory->save();
                
                Log::info('Created inventory entry for sale ID: ' . $vente->id . ', product ID: ' . $ligneVente->idproduit . ', prix_unitaire: ' . $prixAchat);
            }
            
            // Update monthly and yearly summaries
            $this->updateMonthlySummaries($vente->created_at->format('Y'), $vente->created_at->format('n'), $ligneVentes->pluck('idproduit')->unique()->toArray());
            $this->updateYearlySummaries($vente->created_at->format('Y'), $ligneVentes->pluck('idproduit')->unique()->toArray());
            
            DB::commit();
            Log::info('Successfully updated inventory for sale ID: ' . $vente->id);
            
        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Error updating inventory for sale: ' . $e->getMessage());
            Log::error($e->getTraceAsString());
            throw $e;
        }
    }
    
    /**
     * Update inventory records for a purchase
     */
    public function updateInventoryForPurchase($achat)
    {
        Log::info('Updating inventory for purchase ID: ' . $achat->id);
        
        // Begin transaction
        DB::beginTransaction();
        
        try {
            // Get purchase details
            $ligneAchats = LigneAchat::where('idachat', $achat->id)->get();
            $productIds = [];
            
            foreach ($ligneAchats as $ligneAchat) {
                $productIds[] = $ligneAchat->idproduit;
                
                // Check if inventory entry already exists to prevent duplicates
                $existingEntry = Inventory::where('id_achat', $achat->id)
                    ->where('product_id', $ligneAchat->idproduit)
                    ->first();
                
                if ($existingEntry) {
                    Log::info('Inventory entry already exists for purchase ID: ' . $achat->id . ', product ID: ' . $ligneAchat->idproduit);
                    continue;
                }
                
                // Get current stock level
                $stock = Stock::where('id_product', $ligneAchat->idproduit)->first();
                $currentStock = $stock ? $stock->quantite : 0;
                
                // Get product purchase price
                $product = Product::find($ligneAchat->idproduit);
                $prixAchat = $product ? $product->price_achat : 0;
                
                // Record inventory movement with price
                $inventory = new Inventory();
                $inventory->product_id = $ligneAchat->idproduit;
                $inventory->date = $achat->created_at->format('Y-m-d');
                $inventory->entree = $ligneAchat->qte;
                $inventory->sortie = 0;
                $inventory->reste = $currentStock; // Record current stock level
                $inventory->id_vente = null;
                $inventory->id_achat = $achat->id;
                $inventory->prix_unitaire = $prixAchat; // Store the product's purchase price instead of ligne achat price
                $inventory->created_by = auth()->id() ?? 1; // Use authenticated user or default
                $inventory->save();
                
                Log::info('Created inventory entry for purchase ID: ' . $achat->id . ', product ID: ' . $ligneAchat->idproduit . ', prix_unitaire: ' . $prixAchat);
            }
            
            // Update monthly and yearly summaries
            $this->updateMonthlySummaries($achat->created_at->format('Y'), $achat->created_at->format('n'), $productIds);
            $this->updateYearlySummaries($achat->created_at->format('Y'), $productIds);
            
            DB::commit();
            Log::info('Successfully updated inventory for purchase ID: ' . $achat->id);
            
            return true;
            
        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Error updating inventory for purchase: ' . $e->getMessage());
            Log::error($e->getTraceAsString());
            throw $e;
        }
    }
    
    /**
     * Update monthly summaries for specified products
     */
    private function updateMonthlySummaries($year, $month, $productIds = [])
    {
        try {
            // If no product IDs specified, get all active products
            if (empty($productIds)) {
                $productIds = Product::whereNull('deleted_at')->pluck('id')->toArray();
            }
            
            foreach ($productIds as $productId) {
                // Get all inventory entries for this product in the specified month
                $entries = Inventory::where('product_id', $productId)
                    ->whereYear('date', $year)
                    ->whereMonth('date', $month)
                    ->get();
                
                if ($entries->isEmpty()) {
                    continue; // Skip if no entries for this month
                }
                
                $totalEntrees = $entries->sum('entree');
                $totalSorties = $entries->sum('sortie');
                
                // Get the last entry to get the end stock
                $lastEntry = $entries->sortByDesc('date')->sortByDesc('id')->first();
                $endStock = $lastEntry ? $lastEntry->reste : 0;
                
                // Calculate average price from inventory entries
                $priceEntries = $entries->whereNotNull('prix_unitaire');
                $averagePrice = 0;
                
                if ($priceEntries->count() > 0) {
                    $prixUnitaires = $priceEntries->pluck('prix_unitaire')->toArray();
                    $averagePrice = array_sum($prixUnitaires) / count($prixUnitaires);
                } else {
                    // Fallback to product's price_achat if no inventory entries have prices
                    $product = Product::find($productId);
                    $averagePrice = $product ? $product->price_achat : 0;
                }
                
                // Update or create the monthly summary
                InventoryMonthlySummary::updateOrCreate(
                    [
                        'product_id' => $productId,
                        'year' => $year,
                        'month' => $month
                    ],
                    [
                        'total_entrees' => $totalEntrees,
                        'total_sorties' => $totalSorties,
                        'end_stock' => $endStock,
                        'average_price' => $averagePrice
                    ]
                );
                
                Log::info("Updated monthly summary for product ID: $productId, year: $year, month: $month, avg price: $averagePrice");
            }
        } catch (\Exception $e) {
            Log::error('Error updating monthly summaries: ' . $e->getMessage());
            throw $e;
        }
    }
    
    /**
     * Update yearly summaries for specified products
     */
    private function updateYearlySummaries($year, $productIds = [])
    {
        try {
            // If no product IDs specified, get all active products
            if (empty($productIds)) {
                $productIds = Product::whereNull('deleted_at')->pluck('id')->toArray();
            }
            
            foreach ($productIds as $productId) {
                // Get all inventory entries for this product in the specified year
                $entries = Inventory::where('product_id', $productId)
                    ->whereYear('date', $year)
                    ->get();
                
                if ($entries->isEmpty()) {
                    continue; // Skip if no entries for this year
                }
                
                $totalEntrees = $entries->sum('entree');
                $totalSorties = $entries->sum('sortie');
                
                // Get the last entry to get the end stock
                $lastEntry = $entries->sortByDesc('date')->sortByDesc('id')->first();
                $endStock = $lastEntry ? $lastEntry->reste : 0;
                
                // Calculate average price from monthly summaries
                $monthlySummaries = InventoryMonthlySummary::where('product_id', $productId)
                    ->where('year', $year)
                    ->whereNotNull('average_price')
                    ->get();
                
                $averagePrice = 0;
                
                if ($monthlySummaries->count() > 0) {
                    $monthlyPrices = $monthlySummaries->pluck('average_price')->toArray();
                    $averagePrice = array_sum($monthlyPrices) / count($monthlyPrices);
                } else {
                    // Fallback to product's price_achat if no monthly summaries
                    $product = Product::find($productId);
                    $averagePrice = $product ? $product->price_achat : 0;
                }
                
                // Update or create the yearly summary
                InventoryYearlySummary::updateOrCreate(
                    [
                        'product_id' => $productId,
                        'year' => $year
                    ],
                    [
                        'total_entrees' => $totalEntrees,
                        'total_sorties' => $totalSorties,
                        'end_stock' => $endStock,
                        'average_price' => $averagePrice
                    ]
                );
                
                Log::info("Updated yearly summary for product ID: $productId, year: $year, avg price: $averagePrice");
            }
        } catch (\Exception $e) {
            Log::error('Error updating yearly summaries: ' . $e->getMessage());
            throw $e;
        }
    }
}