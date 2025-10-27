<?php

namespace App\Http\Controllers;

use App\Models\Product;
use App\Models\Inventory;
use App\Models\InventoryMonthlySummary;
use App\Models\InventoryYearlySummary;
use App\Services\InventoryService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;
use Barryvdh\DomPDF\Facade\Pdf;
use App\Models\LigneAchat;
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;
use PhpOffice\PhpSpreadsheet\Style\Fill;
use PhpOffice\PhpSpreadsheet\Style\Alignment;

use Illuminate\Support\Facades\Log;
class InventoryController extends Controller
{
    protected $inventoryService;

    public function __construct(InventoryService $inventoryService)
    {
        $this->inventoryService = $inventoryService;
    }

    /**
     * Display inventory tracking page (with both single and multi-month views)
     */
    public function index(Request $request)
    {
        // Check permission for viewing inventory
        if (!auth()->user()->can('Inventaire')) {
            abort(403, 'Vous n\'avez pas la permission de voir l\'inventaire');
        }
        
        // Get all active products
        $products = Product::whereNull('deleted_at')->get();
        
        // Default to current month/year if not specified
        $year = $request->input('year', date('Y'));
        $month = $request->input('month', date('m'));
        
        // List of months for dropdown
        $months = [
            1 => 'Janvier', 2 => 'Février', 3 => 'Mars', 4 => 'Avril',
            5 => 'Mai', 6 => 'Juin', 7 => 'Juillet', 8 => 'Août',
            9 => 'Septembre', 10 => 'Octobre', 11 => 'Novembre', 12 => 'Décembre'
        ];
        
        // Generate list of years (last 5 years)
        $currentYear = date('Y');
        $years = range($currentYear - 4, $currentYear);
        
        // Fixed set of display months for multi-month view (August to December)
        $displayMonths = [
            8 => 'Août', 
            9 => 'Septembre', 
            10 => 'Octobre', 
            11 => 'Novembre', 
            12 => 'Décembre'
        ];
        
        // Pass variables to the view
        return view('inventory.index', compact(
            'products', 
            'year', 
            'month', 
            'months', 
            'years',
            'displayMonths'
        ));
    }

    /**
     * Get inventory data for a specific product, month, and year
     */
   public function getInventoryData(Request $request)
{
    $request->validate([
        'product_id' => 'required|exists:products,id',
        'year' => 'required|integer',
        'month' => 'required',
    ]);

    $productId = $request->input('product_id');
    $year = $request->input('year');
    $month = (int)$request->input('month');
    
    try {
        // Service now properly handles running stock with returns
        $data = $this->inventoryService->getProductMonthlyData($productId, $year, $month);
        
        return response()->json([
            'status' => 200,
            'data' => $data
        ]);
    } catch (\Exception $e) {
        return response()->json([
            'status' => 500,
            'message' => 'Erreur lors de la récupération des données d\'inventaire',
            'error' => $e->getMessage()
        ], 500);
    }
}

    /**
     * Get monthly report data
     */
    public function getMonthlyReport(Request $request)
    {
        $year = $request->input('year', date('Y'));
        $productId = $request->input('product_id');
        
        try {
            // Base query for monthly summaries
            $query = InventoryMonthlySummary::with('product')
                ->where('year', $year)
                ->whereHas('product', function($q) {
                    $q->whereNull('deleted_at');
                });
            
            // Optional product filter
            if ($productId) {
                $query->where('product_id', $productId);
            }
            
            // Get data grouped by product and month
            $summaries = $query->get();
            
            // Format data for the frontend
            $result = [];
            foreach ($summaries as $summary) {
                $productId = $summary->product_id;
                
                if (!isset($result[$productId])) {
                    $result[$productId] = [
                        'product_id' => $productId,
                        'product_name' => $summary->product->name,
                        'months' => [],
                        'totals' => [
                            'entrees' => 0,
                            'sorties' => 0
                        ]
                    ];
                }
                
                $result[$productId]['months'][$summary->month] = [
                    'entrees' => $summary->total_entrees,
                    'sorties' => $summary->total_sorties,
                    'end_stock' => $summary->end_stock,
                    'average_price' => $summary->average_price // Add average price to response
                ];
                
                $result[$productId]['totals']['entrees'] += $summary->total_entrees;
                $result[$productId]['totals']['sorties'] += $summary->total_sorties;
            }
            
            return response()->json([
                'status' => 200,
                'data' => array_values($result)
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'status' => 500,
                'message' => 'Erreur lors de la récupération des données mensuelles',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Get yearly report data
     */
    public function getYearlyReport(Request $request)
    {
        $productId = $request->input('product_id');
        
        try {
            // Base query for yearly summaries
            $query = InventoryYearlySummary::with('product')
                ->whereHas('product', function($q) {
                    $q->whereNull('deleted_at');
                })
                ->orderBy('year', 'desc');
            
            // Optional product filter
            if ($productId) {
                $query->where('product_id', $productId);
            }
            
            // Get data grouped by product and year
            $summaries = $query->get();
            
            // Format data for the frontend
            $result = [];
            foreach ($summaries as $summary) {
                $productId = $summary->product_id;
                
                if (!isset($result[$productId])) {
                    $result[$productId] = [
                        'product_id' => $productId,
                        'product_name' => $summary->product->name,
                        'years' => [],
                        'totals' => [
                            'entrees' => 0,
                            'sorties' => 0
                        ]
                    ];
                }
                
                $result[$productId]['years'][$summary->year] = [
                    'entrees' => $summary->total_entrees,
                    'sorties' => $summary->total_sorties,
                    'end_stock' => $summary->end_stock,
                    'average_price' => $summary->average_price // Add average price to response
                ];
                
                $result[$productId]['totals']['entrees'] += $summary->total_entrees;
                $result[$productId]['totals']['sorties'] += $summary->total_sorties;
            }
            
            return response()->json([
                'status' => 200,
                'data' => array_values($result)
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'status' => 500,
                'message' => 'Erreur lors de la récupération des données annuelles',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Export daily inventory as PDF
     */
    public function exportInventoryPDF(Request $request)
    {
        $request->validate([
            'product_id' => 'required|exists:products,id',
            'year' => 'required|integer',
            'month' => 'required',
            'product_name' => 'required|string',
            'month_name' => 'required|string',
        ]);

        $productId = $request->input('product_id');
        $year = $request->input('year');
        $month = (int)$request->input('month');
        $productName = $request->input('product_name');
        $monthName = $request->input('month_name');
        
        try {
            $data = $this->inventoryService->getProductMonthlyData($productId, $year, $month);
            
            // Get average price from monthly summary
            $summary = InventoryMonthlySummary::where('product_id', $productId)
                ->where('year', $year)
                ->where('month', $month)
                ->first();
                
            $averagePrice = $summary ? $summary->average_price : 0;
            
            $pdf = PDF::loadView('inventory.pdf.daily', [
                'data' => $data,
                'product_name' => $productName,
                'month_name' => $monthName,
                'year' => $year,
                'average_price' => $averagePrice
            ]);
            
            return $pdf->download("inventaire_{$productName}_{$monthName}_{$year}.pdf");
        } catch (\Exception $e) {
            return back()->with('error', 'Erreur lors de la génération du PDF: ' . $e->getMessage());
        }
    }

    /**
     * Export monthly report as PDF
     */
    public function exportMonthlyPDF(Request $request)
    {
        $year = $request->input('year', date('Y'));
        $productId = $request->input('product_id');
        
        try {
            // Query for monthly data (similar to getMonthlyReport)
            $query = InventoryMonthlySummary::with('product')
                ->where('year', $year)
                ->whereHas('product', function($q) {
                    $q->whereNull('deleted_at');
                });
            
            if ($productId) {
                $query->where('product_id', $productId);
            }
            
            $summaries = $query->get();
            
            // Process data
            $result = [];
            foreach ($summaries as $summary) {
                $productId = $summary->product_id;
                
                if (!isset($result[$productId])) {
                    $result[$productId] = [
                        'product_name' => $summary->product->name,
                        'months' => [],
                        'totals' => [
                            'entrees' => 0,
                            'sorties' => 0
                        ]
                    ];
                }
                
                $result[$productId]['months'][$summary->month] = [
                    'entrees' => $summary->total_entrees,
                    'sorties' => $summary->total_sorties,
                    'end_stock' => $summary->end_stock,
                    'average_price' => $summary->average_price
                ];
                
                $result[$productId]['totals']['entrees'] += $summary->total_entrees;
                $result[$productId]['totals']['sorties'] += $summary->total_sorties;
            }
            
            $pdf = PDF::loadView('inventory.pdf.monthly', [
                'data' => array_values($result),
                'year' => $year,
                'months' => [
                    1 => 'Janvier', 2 => 'Février', 3 => 'Mars', 4 => 'Avril',
                    5 => 'Mai', 6 => 'Juin', 7 => 'Juillet', 8 => 'Août',
                    9 => 'Septembre', 10 => 'Octobre', 11 => 'Novembre', 12 => 'Décembre'
                ]
            ]);
            
            return $pdf->download("resume_mensuel_{$year}.pdf");
        } catch (\Exception $e) {
            return back()->with('error', 'Erreur lors de la génération du PDF: ' . $e->getMessage());
        }
    }

    /**
     * Export yearly report as PDF
     */
    public function exportYearlyPDF(Request $request)
    {
        $productId = $request->input('product_id');
        
        try {
            // Query for yearly data (similar to getYearlyReport)
            $query = InventoryYearlySummary::with('product')
                ->whereHas('product', function($q) {
                    $q->whereNull('deleted_at');
                })
                ->orderBy('year', 'desc');
            
            if ($productId) {
                $query->where('product_id', $productId);
            }
            
            $summaries = $query->get();
            
            // Process data
            $result = [];
            foreach ($summaries as $summary) {
                $productId = $summary->product_id;
                
                if (!isset($result[$productId])) {
                    $result[$productId] = [
                        'product_name' => $summary->product->name,
                        'years' => [],
                        'totals' => [
                            'entrees' => 0,
                            'sorties' => 0
                        ]
                    ];
                }
                
                $result[$productId]['years'][$summary->year] = [
                    'entrees' => $summary->total_entrees,
                    'sorties' => $summary->total_sorties,
                    'end_stock' => $summary->end_stock,
                    'average_price' => $summary->average_price
                ];
                
                $result[$productId]['totals']['entrees'] += $summary->total_entrees;
                $result[$productId]['totals']['sorties'] += $summary->total_sorties;
            }
            
            $pdf = PDF::loadView('inventory.pdf.yearly', [
                'data' => array_values($result)
            ]);
            
            return $pdf->download("resume_annuel_inventaire.pdf");
        } catch (\Exception $e) {
            return back()->with('error', 'Erreur lors de la génération du PDF: ' . $e->getMessage());
        }
    }

    /**
     * Export multi-month view as PDF
     */
    public function exportMultiMonthPDF(Request $request)
    {
        $request->validate([
            'product_id' => 'required|exists:products,id',
            'year' => 'required|integer',
            'product_name' => 'required|string',
        ]);

        $productId = $request->input('product_id');
        $year = $request->input('year');
        $productName = $request->input('product_name');
        
        // Fixed set of display months (August to December)
        $displayMonths = [
            8 => 'Août', 
            9 => 'Septembre', 
            10 => 'Octobre', 
            11 => 'Novembre', 
            12 => 'Décembre'
        ];
        
        try {
            // Get monthly summaries for totals
            $monthlySummaries = InventoryMonthlySummary::where('product_id', $productId)
                ->where('year', $year)
                ->whereIn('month', array_keys($displayMonths))
                ->get()
                ->keyBy('month');
            
            // Get inventory data for each month
            $monthlyData = [];
            foreach (array_keys($displayMonths) as $month) {
                $monthData = $this->inventoryService->getProductMonthlyData($productId, $year, $month);
                $monthlyData[$month] = $monthData;
            }
            
            // Get the yearly average price
            $yearlySummary = InventoryYearlySummary::where('product_id', $productId)
                ->where('year', $year)
                ->first();
                
            $averagePrice = $yearlySummary ? $yearlySummary->average_price : 0;
            
            $pdf = PDF::loadView('inventory.pdf.multi_month', [
                'data' => $monthlyData,
                'summaries' => $monthlySummaries,
                'product_name' => $productName,
                'year' => $year,
                'months' => $displayMonths,
                'average_price' => $averagePrice
            ]);
            
            return $pdf->download("inventaire_multi_mois_{$productName}_{$year}.pdf");
        } catch (\Exception $e) {
            return back()->with('error', 'Erreur lors de la génération du PDF: ' . $e->getMessage());
        }
    }

  /**
 * Get product average unit price
 */
public function getProductAveragePrice(Request $request)
{
    $request->validate([
        'product_id' => 'required|exists:products,id',
        'year' => 'sometimes|integer',
        'month' => 'sometimes|integer',
        'day' => 'sometimes|integer',
    ]);

    $productId = $request->input('product_id');
    $year = $request->input('year');
    $month = $request->input('month');
    $day = $request->input('day');
    
    try {
        // Add debugging
        Log::info('Calculating average price for product ID: ' . $productId . 
            ($year ? ', year: ' . $year : '') . 
            ($month ? ', month: ' . $month : '') .
            ($day ? ', day: ' . $day : ''));
        
        // First try to get from the summary tables if year (and optionally month) are provided
        if ($year && $month && !$day) {
            $summary = InventoryMonthlySummary::where('product_id', $productId)
                ->where('year', $year)
                ->where('month', $month)
                ->first();
            
            if ($summary && $summary->average_price > 0) {
                Log::info('Retrieved average price from monthly summary: ' . $summary->average_price);
                return response()->json([
                    'status' => 200,
                    'average_price' => $summary->average_price
                ]);
            }
        } elseif ($year && !$month) {
            $summary = InventoryYearlySummary::where('product_id', $productId)
                ->where('year', $year)
                ->first();
            
            if ($summary && $summary->average_price > 0) {
                Log::info('Retrieved average price from yearly summary: ' . $summary->average_price);
                return response()->json([
                    'status' => 200,
                    'average_price' => $summary->average_price
                ]);
            }
        }
        
        // If no summary data found or specific day requested, calculate from inventory records
        $query = Inventory::where('product_id', $productId)
            ->whereNotNull('prix_unitaire')
            ->where('entree', '>', 0); // Only consider purchase entries
        
        // Apply filters if provided
        if ($year && $month && $day) {
            $date = Carbon::createFromDate($year, $month, $day)->format('Y-m-d');
            $query->whereDate('date', $date);
        } elseif ($year && $month) {
            $query->whereYear('date', $year)
                  ->whereMonth('date', $month);
        } elseif ($year) {
            $query->whereYear('date', $year);
        }
        
        $inventoryEntries = $query->get();
        
        Log::info('Found ' . $inventoryEntries->count() . ' inventory entries with prices');
        
        if ($inventoryEntries->isEmpty()) {
            // If no entries found, use the product's default price
            $product = Product::find($productId);
            $defaultPrice = $product ? $product->price_achat : 0;
            
            Log::info('No inventory entries with prices found, using product price_achat: ' . $defaultPrice);
            
            return response()->json([
                'status' => 200,
                'average_price' => $defaultPrice
            ]);
        }
        
        // Store all prices in an array
        $priceArray = [];
        
        // Group entries by day to calculate daily averages
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
            $averagePrice = isset($dailyAverages[$specificDate]) ? $dailyAverages[$specificDate] : 0;
        } else {
            // For monthly/yearly averages, calculate the average of daily averages
            $averagePrice = array_sum($dailyAverages) / count($dailyAverages);
        }
        
        Log::info('Calculated average price: ' . $averagePrice);
        
        // Update the summary tables with the calculated price if appropriate
        if ($averagePrice > 0) {
            if ($year && $month && !$day) {
                InventoryMonthlySummary::updateOrCreate(
                    ['product_id' => $productId, 'year' => $year, 'month' => $month],
                    ['average_price' => $averagePrice]
                );
            }
            
            if ($year && !$month) {
                InventoryYearlySummary::updateOrCreate(
                    ['product_id' => $productId, 'year' => $year],
                    ['average_price' => $averagePrice]
                );
            }
        }
        
        return response()->json([
            'status' => 200,
            'average_price' => $averagePrice
        ]);
        
    } catch (\Exception $e) {
        Log::error('Error calculating average price: ' . $e->getMessage());
        Log::error($e->getTraceAsString());
        
        // In case of error, try to return the product's direct price
        try {
            $product = Product::find($productId);
            if ($product) {
                return response()->json([
                    'status' => 200,
                    'average_price' => $product->price_achat
                ]);
            }
        } catch (\Exception $innerEx) {
            Log::error('Error getting fallback product price: ' . $innerEx->getMessage());
        }
        
        return response()->json([
            'status' => 500,
            'message' => 'Erreur lors du calcul du prix unitaire moyen',
            'error' => $e->getMessage()
        ], 500);
    }
}

public function cardex(Request $request) {
    // Check permission for viewing inventory cardex
    if (!auth()->user()->can('Inventaire')) {
        abort(403, 'Vous n\'avez pas la permission de voir le cardex d\'inventaire');
    }
    
    $request->validate([
        'product_id' => 'required|exists:products,id',
        'year' => 'required|integer',
    ]);
    
    $productId = $request->input('product_id');
    $year = $request->input('year');
    $download = $request->input('download', false);
    
    // Get product information
    $product = Product::findOrFail($productId);
    
    // Get months data - using your existing service
    $monthsData = [];
    for ($month = 1; $month <= 12; $month++) {
        $monthsData[$month] = $this->inventoryService->getProductMonthlyData($productId, $year, $month);
    }
    
    // Get yearly average price from summary
    $yearlySummary = InventoryYearlySummary::where('product_id', $productId)
                ->where('year', $year)
                ->first();
                
    $averagePrice = $yearlySummary ? $yearlySummary->average_price : 0;
    
    // If no value found, try to get product's default price
    if (!$averagePrice || $averagePrice <= 0) {
        $averagePrice = $product->price_achat ?? 0;
    }
    
    // Get monthly summaries for annual balance
    $monthlySummaries = InventoryMonthlySummary::where('product_id', $productId)
        ->where('year', $year)
        ->get()
        ->keyBy('month');
    
    // Month names in French
    $months = [
        1 => 'Janvier', 2 => 'Février', 3 => 'Mars', 4 => 'Avril',
        5 => 'Mai', 6 => 'Juin', 7 => 'Juillet', 8 => 'Août',
        9 => 'Septembre', 10 => 'Octobre', 11 => 'Novembre', 12 => 'Décembre'
    ];
    
    // Add this code to load and encode the header image
    $imagePath = public_path('images/lgtitle.png');
    $imageData = '';
    if (file_exists($imagePath)) {
        $imageData = base64_encode(file_get_contents($imagePath));
    }
    
    // Prepare data for view or PDF
    $viewData = compact(
        'product',
        'year',
        'monthsData',
        'monthlySummaries',
        'averagePrice',
        'months',
        'imageData' 
    );
    
  
    if ($download) {
        $pdf = PDF::loadView('inventory.cardex', $viewData);
        
     
        $pdf->setPaper('a4', 'portrait');
        $pdf->setOptions([
            'isRemoteEnabled' => true,
            'isHtml5ParserEnabled' => true,
            'isFontSubsettingEnabled' => true
        ]);
        
        $sanitizedProductName = preg_replace('/[^A-Za-z0-9_\-]/', '_', $product->name);
        
        return $pdf->download("CARDEX_{$sanitizedProductName}_{$year}.pdf");
    }
    
    return view('inventory.cardex', $viewData);
}
/**
 * Export CARDEX data to Excel
 */
public function exportCardexExcel(Request $request)
{
    $request->validate([
        'product_id' => 'required|exists:products,id',
        'year' => 'required|integer',
    ]);

    $productId = $request->input('product_id');
    $year = $request->input('year');
    
    // Get product information
    $product = Product::findOrFail($productId);
    
    // Get months data using existing service
    $monthsData = [];
    for ($month = 1; $month <= 12; $month++) {
        $monthsData[$month] = $this->inventoryService->getProductMonthlyData($productId, $year, $month);
    }
    
    // Get yearly average price from summary
    $yearlySummary = InventoryYearlySummary::where('product_id', $productId)
                ->where('year', $year)
                ->first();
                
    $averagePrice = $yearlySummary ? $yearlySummary->average_price : 0;
    
    // If no value found, try to get product's default price
    if (!$averagePrice || $averagePrice <= 0) {
        $averagePrice = $product->price_achat ?? 0;
    }
    
    // Get monthly summaries for annual balance
    $monthlySummaries = InventoryMonthlySummary::where('product_id', $productId)
        ->where('year', $year)
        ->get()
        ->keyBy('month');
    
    // Month names in French
    $months = [
        1 => 'Janvier', 2 => 'Février', 3 => 'Mars', 4 => 'Avril',
        5 => 'Mai', 6 => 'Juin', 7 => 'Juillet', 8 => 'Août',
        9 => 'Septembre', 10 => 'Octobre', 11 => 'Novembre', 12 => 'Décembre'
    ];
    
    // Create new Spreadsheet object
    $spreadsheet = new Spreadsheet();
    
    // First sheet - January-July
    $firstSheet = $spreadsheet->getActiveSheet();
    $firstSheet->setTitle('Janvier-Juillet');
    
    // Add header information
    $firstSheet->setCellValue('A1', 'CARDEX');
    $firstSheet->setCellValue('A2', 'PRODUIT: ' . $product->name);
    $firstSheet->setCellValue('A3', 'ANNÉE: ' . $year);
    $firstSheet->setCellValue('A4', 'PRIX UNITAIRE: ' . number_format($averagePrice, 2) . ' DH');
    
    // Bold header
    $firstSheet->getStyle('A1:A4')->getFont()->setBold(true);
    
    // Add months 1-7 headers
    $firstSheet->setCellValue('A6', 'Dates');
    
    $col = 'B';
    for ($month = 1; $month <= 7; $month++) {
        $firstSheet->setCellValue($col . '6', mb_strtoupper($months[$month]));
        $firstSheet->mergeCells($col . '6:' . chr(ord($col) + 2) . '6');
        $firstSheet->setCellValue($col . '7', 'Entrées');
        $firstSheet->setCellValue(chr(ord($col) + 1) . '7', 'Sorties');
        $firstSheet->setCellValue(chr(ord($col) + 2) . '7', 'Reste');
        $col = chr(ord($col) + 3);
    }
    
    // Style header rows
    $headerStyle = [
        'font' => [
            'bold' => true,
        ],
        'alignment' => [
            'horizontal' => \PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER,
        ],
        'fill' => [
            'fillType' => \PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID,
            'startColor' => [
                'rgb' => 'EEEEEE',
            ],
        ],
    ];
    
    $firstSheet->getStyle('A6:' . chr(ord($col) - 1) . '7')->applyFromArray($headerStyle);
    
    // Add data for months 1-7
    $row = 8;
    for ($day = 1; $day <= 31; $day++) {
        $firstSheet->setCellValue('A' . $row, $day);
        
        $dataCol = 'B';
        for ($month = 1; $month <= 7; $month++) {
            // Check if day exists in this month
            $daysInMonth = cal_days_in_month(CAL_GREGORIAN, $month, $year);
            if ($day <= $daysInMonth) {
                // Get day data
                $dayData = isset($monthsData[$month]['days'][$day]) ? $monthsData[$month]['days'][$day] : null;
                $hasEntree = $dayData && floatval($dayData['entree']) > 0;
                $hasSortie = $dayData && floatval($dayData['sortie']) > 0;
                $hasActivity = $hasEntree || $hasSortie;
                
                // Set cell values
                $firstSheet->setCellValue($dataCol . $row, $hasEntree ? $dayData['entree'] : '');
                $firstSheet->setCellValue(chr(ord($dataCol) + 1) . $row, $hasSortie ? $dayData['sortie'] : '');
                $firstSheet->setCellValue(chr(ord($dataCol) + 2) . $row, $hasActivity ? $dayData['reste'] : '');
            }
            
            $dataCol = chr(ord($dataCol) + 3);
        }
        
        $row++;
    }
    
    // Add totals row for months 1-7
    $firstSheet->setCellValue('A' . $row, 'TOTAUX');
    $firstSheet->getStyle('A' . $row)->getFont()->setBold(true);
    
    $dataCol = 'B';
    for ($month = 1; $month <= 7; $month++) {
        $monthTotal = isset($monthsData[$month]['month_total']) ? $monthsData[$month]['month_total'] : null;
        $totalEntrees = $monthTotal ? floatval($monthTotal['total_entrees']) : 0;
        $totalSorties = $monthTotal ? floatval($monthTotal['total_sorties']) : 0;
        $endStock = $monthTotal ? floatval($monthTotal['end_stock']) : 0;
        
        // Only show totals if there's any activity in the month (entrées or sorties)
        $hasActivity = $totalEntrees > 0 || $totalSorties > 0;
        
        $firstSheet->setCellValue($dataCol . $row, $hasActivity && $totalEntrees > 0 ? $totalEntrees : '');
        $firstSheet->setCellValue(chr(ord($dataCol) + 1) . $row, $hasActivity && $totalSorties > 0 ? $totalSorties : '');
        $firstSheet->setCellValue(chr(ord($dataCol) + 2) . $row, $hasActivity ? $endStock : '');
        
        $firstSheet->getStyle($dataCol . $row . ':' . chr(ord($dataCol) + 2) . $row)->getFont()->setBold(true);
        
        $dataCol = chr(ord($dataCol) + 3);
    }
    
    // Format all numbers with 2 decimal places
    $firstSheet->getStyle('B8:' . chr(ord($dataCol) - 1) . $row)->getNumberFormat()->setFormatCode('#,##0.00');
    
    // Center align all cells
    $firstSheet->getStyle('A6:' . chr(ord($dataCol) - 1) . $row)->getAlignment()->setHorizontal(
        \PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER
    );
    
    // Second sheet - August-December and Annual Balance
    $secondSheet = $spreadsheet->createSheet();
    $secondSheet->setTitle('Aout-Décembre');
    
    // Add header information
    $secondSheet->setCellValue('A1', 'CARDEX');
    $secondSheet->setCellValue('A2', 'PRODUIT: ' . $product->name);
    $secondSheet->setCellValue('A3', 'ANNÉE: ' . $year);
    $secondSheet->setCellValue('A4', 'PRIX UNITAIRE: ' . number_format($averagePrice, 2) . ' DH');
    
    // Bold header
    $secondSheet->getStyle('A1:A4')->getFont()->setBold(true);
    
    // Add months 8-12 headers
    $secondSheet->setCellValue('A6', 'Dates');
    
    $col = 'B';
    for ($month = 8; $month <= 12; $month++) {
        $secondSheet->setCellValue($col . '6', mb_strtoupper($months[$month]));
        $secondSheet->mergeCells($col . '6:' . chr(ord($col) + 2) . '6');
        $secondSheet->setCellValue($col . '7', 'Entrées');
        $secondSheet->setCellValue(chr(ord($col) + 1) . '7', 'Sorties');
        $secondSheet->setCellValue(chr(ord($col) + 2) . '7', 'Reste');
        $col = chr(ord($col) + 3);
    }
    
    // Style header rows
    $secondSheet->getStyle('A6:' . chr(ord($col) - 1) . '7')->applyFromArray($headerStyle);
    
    // Add data for months 8-12
    $row = 8;
    for ($day = 1; $day <= 31; $day++) {
        $secondSheet->setCellValue('A' . $row, $day);
        
        $dataCol = 'B';
        for ($month = 8; $month <= 12; $month++) {
            // Check if day exists in this month
            $daysInMonth = cal_days_in_month(CAL_GREGORIAN, $month, $year);
            if ($day <= $daysInMonth) {
                // Get day data
                $dayData = isset($monthsData[$month]['days'][$day]) ? $monthsData[$month]['days'][$day] : null;
                $hasEntree = $dayData && floatval($dayData['entree']) > 0;
                $hasSortie = $dayData && floatval($dayData['sortie']) > 0;
                $hasActivity = $hasEntree || $hasSortie;
                
                // Set cell values
                $secondSheet->setCellValue($dataCol . $row, $hasEntree ? $dayData['entree'] : '');
                $secondSheet->setCellValue(chr(ord($dataCol) + 1) . $row, $hasSortie ? $dayData['sortie'] : '');
                $secondSheet->setCellValue(chr(ord($dataCol) + 2) . $row, $hasActivity ? $dayData['reste'] : '');
            }
            
            $dataCol = chr(ord($dataCol) + 3);
        }
        
        $row++;
    }
    
    // Add totals row for months 8-12
    $secondSheet->setCellValue('A' . $row, 'TOTAUX');
    $secondSheet->getStyle('A' . $row)->getFont()->setBold(true);
    
    $dataCol = 'B';
    for ($month = 8; $month <= 12; $month++) {
        $monthTotal = isset($monthsData[$month]['month_total']) ? $monthsData[$month]['month_total'] : null;
        $totalEntrees = $monthTotal ? floatval($monthTotal['total_entrees']) : 0;
        $totalSorties = $monthTotal ? floatval($monthTotal['total_sorties']) : 0;
        $endStock = $monthTotal ? floatval($monthTotal['end_stock']) : 0;
        
        // Only show totals if there's any activity in the month (entrées or sorties)
        $hasActivity = $totalEntrees > 0 || $totalSorties > 0;
        
        $secondSheet->setCellValue($dataCol . $row, $hasActivity && $totalEntrees > 0 ? $totalEntrees : '');
        $secondSheet->setCellValue(chr(ord($dataCol) + 1) . $row, $hasActivity && $totalSorties > 0 ? $totalSorties : '');
        $secondSheet->setCellValue(chr(ord($dataCol) + 2) . $row, $hasActivity ? $endStock : '');
        
        $secondSheet->getStyle($dataCol . $row . ':' . chr(ord($dataCol) + 2) . $row)->getFont()->setBold(true);
        
        $dataCol = chr(ord($dataCol) + 3);
    }
    
    // Format all numbers with 2 decimal places
    $secondSheet->getStyle('B8:' . chr(ord($dataCol) - 1) . $row)->getNumberFormat()->setFormatCode('#,##0.00');
    
    // Center align all cells
    $secondSheet->getStyle('A6:' . chr(ord($dataCol) - 1) . $row)->getAlignment()->setHorizontal(
        \PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER
    );
    
    // Add Annual Balance section
    $secondSheet->setCellValue('Q6', 'BALANCE ANNUELLE');
    $secondSheet->mergeCells('Q6:S6');
    $secondSheet->setCellValue('Q7', 'Mois');
    $secondSheet->setCellValue('R7', 'Entrées');
    $secondSheet->setCellValue('S7', 'Sorties');
    
    // Style Annual Balance header
    $secondSheet->getStyle('Q6:S7')->applyFromArray($headerStyle);
    
    // Add monthly data to Annual Balance
    $balanceRow = 8;
    $annualTotalEntrees = 0;
    $annualTotalSorties = 0;
    
    foreach ($months as $monthNum => $monthName) {
        $monthData = isset($monthlySummaries[$monthNum]) ? $monthlySummaries[$monthNum] : null;
        $entrees = $monthData ? floatval($monthData->total_entrees) : 0;
        $sorties = $monthData ? floatval($monthData->total_sorties) : 0;
        
        $annualTotalEntrees += $entrees;
        $annualTotalSorties += $sorties;
        
        $secondSheet->setCellValue('Q' . $balanceRow, $monthName);
        $secondSheet->setCellValue('R' . $balanceRow, $entrees);
        $secondSheet->setCellValue('S' . $balanceRow, $sorties);
        
        $balanceRow++;
    }
    
    // Add annual totals
    $secondSheet->setCellValue('Q' . $balanceRow, 'Totaux de l\'année');
    $secondSheet->setCellValue('R' . $balanceRow, $annualTotalEntrees);
    $secondSheet->setCellValue('S' . $balanceRow, $annualTotalSorties);
    $secondSheet->getStyle('Q' . $balanceRow . ':S' . $balanceRow)->getFont()->setBold(true);
    
    // Format Annual Balance numbers
    $secondSheet->getStyle('R8:S' . $balanceRow)->getNumberFormat()->setFormatCode('#,##0.00');
    
    // Center align Annual Balance cells (except month names)
    $secondSheet->getStyle('R8:S' . $balanceRow)->getAlignment()->setHorizontal(
        \PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER
    );
    
    // Auto-size columns for both sheets
    foreach (range('A', 'Z') as $column) {
        if ($column === 'Q') {
            $secondSheet->getColumnDimension($column)->setWidth(15);
        } else {
            $firstSheet->getColumnDimension($column)->setAutoSize(true);
            $secondSheet->getColumnDimension($column)->setAutoSize(true);
        }
    }
    
    // Create writer
    $writer = new \PhpOffice\PhpSpreadsheet\Writer\Xlsx($spreadsheet);
    
    // Sanitize product name for filename
    $sanitizedProductName = preg_replace('/[^A-Za-z0-9_\-]/', '_', $product->name);
    
    // Set headers for download
    $fileName = 'CARDEX_' . $sanitizedProductName . '_' . $year . '.xlsx';
    header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
    header('Content-Disposition: attachment; filename="' . $fileName . '"');
    header('Cache-Control: max-age=0');
    
    // Save file to output
    $writer->save('php://output');
    exit;
}
}