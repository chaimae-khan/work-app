<?php

namespace App\Http\Controllers;

use App\Models\Stock;
use App\Models\Product;
use Illuminate\View\View;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Yajra\DataTables\Facades\DataTables;
// Fix imports for PhpSpreadsheet
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;
use PhpOffice\PhpSpreadsheet\Style\Fill;
use PhpOffice\PhpSpreadsheet\Style\Alignment;
// Import for PDF
use Barryvdh\DomPDF\Facade\Pdf;

class StockController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        if ($request->ajax()) {
            $dataStock = DB::table('stock as s')
                        ->join('products as p', 'p.id', 's.id_product')
                        ->leftJoin('categories as c', 'p.id_categorie', '=', 'c.id')
                        ->leftJoin('sub_categories as sc', 'p.id_subcategorie', '=', 'sc.id')
                        ->leftJoin('locals as l', 'p.id_local', '=', 'l.id')
                        ->leftJoin('rayons as r', 'p.id_rayon', '=', 'r.id')
                        ->leftJoin('tvas as t', 'p.id_tva', '=', 't.id')
                        ->leftJoin('unite as u', 'p.id_unite', '=', 'u.id')
                        ->leftJoin('users as us', 'p.id_user', '=', 'us.id')
                        ->whereNull('s.deleted_at')
                        ->whereNull('p.deleted_at')
                        ->select(
                            's.id',
                            'p.code_article',
                            'p.name',
                            'u.name as unite_name',
                            'c.name as categorie',
                            'sc.name as famille',
                            'p.emplacement',
                            's.quantite',
                            'p.price_achat',
                            't.value as tva_value',
                            'p.seuil',
                            'p.code_barre',
                            'p.photo',
                            'p.date_expiration',
                            DB::raw("CONCAT(COALESCE(us.prenom, ''), ' ', COALESCE(us.nom, '')) as username"),
                            'p.created_at'
                        );
            
            // Apply class filter if provided
            if ($request->filled('filter_class')) {

                $dataStock->where('c.classe', $request->filter_class);
            }

            // Apply category filter if provided
            if ($request->filled('filter_categorie')) {
                $dataStock->where('p.id_categorie', $request->filter_categorie);
            }

            // Apply subcategory filter if provided
            if ($request->filled('filter_subcategorie')) {
                $dataStock->where('p.id_subcategorie', $request->filter_subcategorie);
            }

            // Apply designation (name) filter if provided
            if ($request->filled('filter_designation')) {
                $dataStock->where('p.name', 'LIKE', '%' . $request->filter_designation . '%');
            }
    
            return DataTables::of($dataStock)
                ->addIndexColumn()
                ->addColumn('status', function ($row) {
                    if ($row->quantite <= $row->seuil) {
                        return '<span class="badge bg-danger">Stock Bas</span>';
                    }
                    return '<span class="badge bg-success">Stock Normal</span>';
                })
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
                ->rawColumns(['status', 'photo_display'])
                ->make(true);
        }
        
        // Get distinct classes for filter dropdown
        $class = DB::table('categories')
                    ->whereNotNull('classe')
                    ->where('classe', '!=', '')
                    ->distinct()
                    ->pluck('classe')
                    ->map(function($classe) {
                        return (object)['classe' => $classe];
                    });
             
        return view('stock.index', compact('class'));
    }

    /**
     * Get the number of products below threshold.
     */
    public function getAlertCount()
    {
        $lowStockProducts = DB::table('stock as s')
                    ->join('products as p', 'p.id', 's.id_product')
                    ->whereNull('s.deleted_at')
                    ->whereNull('p.deleted_at')
                    ->whereRaw('s.quantite <= p.seuil')
                    ->select('p.name')
                    ->get();

        $alertCount = $lowStockProducts->count();
        $productNames = $lowStockProducts->pluck('name')->toArray();

        return response()->json([
            'status' => 200,
            'count' => $alertCount,
            'products' => $productNames
        ]);
    }
    
    /**
     * Get column data based on column index
     */
    private function getColumnByIndex($index)
    {
        $columns = [
            0 => ['field' => 'p.code_article', 'title' => 'Code article', 'data' => 'code_article'],
            1 => ['field' => 'p.name', 'title' => 'Nom du Produit', 'data' => 'name'],
            2 => ['field' => 'u.name', 'title' => 'Unité', 'data' => 'unite_name'],
            3 => ['field' => 'c.name', 'title' => 'Catégorie', 'data' => 'categorie'],
            4 => ['field' => 'sc.name', 'title' => 'Famille', 'data' => 'famille'],
            5 => ['field' => 'p.emplacement', 'title' => 'Emplacement', 'data' => 'emplacement'],
            6 => ['field' => 's.quantite', 'title' => 'Stock', 'data' => 'quantite'],
            7 => ['field' => 'p.price_achat', 'title' => 'Prix d\'achat', 'data' => 'price_achat'],
            8 => ['field' => 't.value', 'title' => 'Taux TVA', 'data' => 'tva_value'],
            9 => ['field' => 'p.seuil', 'title' => 'Seuil', 'data' => 'seuil'],
            10 => ['field' => 'p.code_barre', 'title' => 'Code barre', 'data' => 'code_barre'],
            11 => ['field' => 'p.photo', 'title' => 'Photo', 'data' => 'photo_display'],
            12 => ['field' => 'p.date_expiration', 'title' => 'Date d\'expiration', 'data' => 'date_expiration'],
            13 => ['field' => 'p.created_at', 'title' => 'Date de réception', 'data' => 'created_at'],
            14 => ['field' => 'status', 'title' => 'Statut', 'data' => 'status']
        ];
        
        return isset($columns[$index]) ? $columns[$index] : null;
    }
    
    /**
     * Export all stock data to Excel with selected columns
     */
    public function exportExcel(Request $request)
    {
        // Parse columns from request
        $selectedColumnIndices = [];
        if ($request->has('columns')) {
            $selectedColumnIndices = explode(',', $request->input('columns'));
        } else {
            // Default to all columns if none specified
            $selectedColumnIndices = range(0, 14);
        }
        
        // Get column information for selected columns
        $selectedColumns = [];
        $columnTitles = [];
        $columnData = [];
        
        foreach ($selectedColumnIndices as $index) {
            $column = $this->getColumnByIndex(intval($index));
            if ($column) {
                $selectedColumns[] = $column['field'];
                $columnTitles[] = $column['title'];
                $columnData[] = $column['data'];
            }
        }
        
        // Get all stock data with selected columns
        $query = DB::table('stock as s')
                ->join('products as p', 'p.id', 's.id_product')
                ->leftJoin('categories as c', 'p.id_categorie', '=', 'c.id')
                ->leftJoin('sub_categories as sc', 'p.id_subcategorie', '=', 'sc.id')
                ->leftJoin('locals as l', 'p.id_local', '=', 'l.id')
                ->leftJoin('rayons as r', 'p.id_rayon', '=', 'r.id')
                ->leftJoin('tvas as t', 'p.id_tva', '=', 't.id')
                ->leftJoin('unite as u', 'p.id_unite', '=', 'u.id')
                ->leftJoin('users as us', 'p.id_user', '=', 'us.id')
                ->whereNull('s.deleted_at')
                ->whereNull('p.deleted_at')
                ->select(
                    'p.code_article',
                    'p.name',
                    'u.name as unite_name',
                    'c.name as categorie',
                    'sc.name as famille',
                    'p.emplacement',
                    's.quantite',
                    'p.price_achat',
                    't.value as tva_value',
                    'p.seuil',
                    'p.code_barre',
                    'p.photo',
                    'p.date_expiration',
                    'p.created_at'
                );
                
        $stockData = $query->get();
        
        // Create new Spreadsheet object
        $spreadsheet = new Spreadsheet();
        $sheet = $spreadsheet->getActiveSheet();
        
        // Set header row with selected columns
        $colIndex = 'A';
        foreach ($columnTitles as $title) {
            $sheet->setCellValue($colIndex . '1', $title);
            $colIndex++;
        }
        
        // Style header row
        $headerStyle = [
            'font' => [
                'bold' => true,
            ],
            'alignment' => [
                'horizontal' => Alignment::HORIZONTAL_CENTER,
            ],
            'fill' => [
                'fillType' => Fill::FILL_SOLID,
                'startColor' => [
                    'rgb' => 'EEEEEE',
                ],
            ],
        ];
        
        $sheet->getStyle('A1:' . chr(64 + count($columnTitles)) . '1')->applyFromArray($headerStyle);
        
        // Add data rows
        $row = 2;
        foreach ($stockData as $stock) {
            $colIndex = 'A';
            
            foreach ($columnData as $i => $field) {
                $value = '';
                
                if ($field === 'status') {
                    $value = ($stock->quantite <= $stock->seuil) ? 'Stock Bas' : 'Stock Normal';
                } elseif ($field === 'photo_display') {
                    $value = $stock->photo ? 'Oui' : 'Non';
                } elseif ($field === 'created_at') {
                    $value = $stock->created_at ? \Carbon\Carbon::parse($stock->created_at)->format('d/m/Y H:i') : '';
                } elseif ($field === 'date_expiration') {
                    $value = $stock->date_expiration ? \Carbon\Carbon::parse($stock->date_expiration)->format('d/m/Y') : '';
                } elseif ($field === 'price_achat') {
                    $value = $stock->price_achat ? number_format($stock->price_achat, 2) . ' DH' : '0.00 DH';
                } elseif ($field === 'tva_value') {
                    $value = $stock->tva_value ? number_format($stock->tva_value, 2) . '%' : '0.00%';
                } else {
                    $value = $stock->{$field} ?? '';
                }
                
                $sheet->setCellValue($colIndex . $row, $value);
                $colIndex++;
            }
            
            // Style low stock rows
            if ($stock->quantite <= $stock->seuil) {
                $lowStockStyle = [
                    'font' => [
                        'color' => ['rgb' => 'FF0000'],
                    ],
                ];
                $sheet->getStyle('A' . $row . ':' . chr(64 + count($columnTitles)) . $row)->applyFromArray($lowStockStyle);
            }
            
            // Center align all data cells
            $sheet->getStyle('A' . $row . ':' . chr(64 + count($columnTitles)) . $row)->getAlignment()->setHorizontal(
                Alignment::HORIZONTAL_CENTER
            );
            
            $row++;
        }
        
        // Auto size columns
        foreach (range('A', chr(64 + count($columnTitles))) as $column) {
            $sheet->getColumnDimension($column)->setAutoSize(true);
        }
        
        // Create writer
        $writer = new Xlsx($spreadsheet);
        
        // Set headers for download
        $fileName = 'GESTOCK TOUARGA - Stock - ' . date('d-m-Y') . '.xlsx';
        header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
        header('Content-Disposition: attachment; filename="' . $fileName . '"');
        header('Cache-Control: max-age=0');
        
        // Save file to output
        $writer->save('php://output');
        exit;
    }
    
    /**
     * Export all stock data to PDF with selected columns
     */
    public function exportPdf(Request $request)
    {
        // Parse columns from request
        $selectedColumnIndices = [];
        if ($request->has('columns')) {
            $selectedColumnIndices = explode(',', $request->input('columns'));
        } else {
            // Default to all columns if none specified
            $selectedColumnIndices = range(0, 14);
        }
        
        // Get column information for selected columns
        $selectedColumns = [];
        $columnTitles = [];
        $columnData = [];
        
        foreach ($selectedColumnIndices as $index) {
            $column = $this->getColumnByIndex(intval($index));
            if ($column) {
                $selectedColumns[] = $column['field'];
                $columnTitles[] = $column['title'];
                $columnData[] = $column['data'];
            }
        }
        
        // Get all stock data
        $stockData = DB::table('stock as s')
                    ->join('products as p', 'p.id', 's.id_product')
                    ->leftJoin('categories as c', 'p.id_categorie', '=', 'c.id')
                    ->leftJoin('sub_categories as sc', 'p.id_subcategorie', '=', 'sc.id')
                    ->leftJoin('locals as l', 'p.id_local', '=', 'l.id')
                    ->leftJoin('rayons as r', 'p.id_rayon', '=', 'r.id')
                    ->leftJoin('tvas as t', 'p.id_tva', '=', 't.id')
                    ->leftJoin('unite as u', 'p.id_unite', '=', 'u.id')
                    ->leftJoin('users as us', 'p.id_user', '=', 'us.id')
                    ->whereNull('s.deleted_at')
                    ->whereNull('p.deleted_at')
                    ->select(
                        'p.code_article',
                        'p.name',
                        'u.name as unite_name',
                        'c.name as categorie',
                        'sc.name as famille',
                        'p.emplacement',
                        's.quantite',
                        'p.price_achat',
                        't.value as tva_value',
                        'p.seuil',
                        'p.code_barre',
                        'p.photo',
                        'p.date_expiration',
                        'p.created_at'
                    )
                    ->get();
        
        // Transform data for view with selected columns
        $stocks = [];
        foreach ($stockData as $stock) {
            $stockItem = [
                'is_low_stock' => ($stock->quantite <= $stock->seuil)
            ];
            
            // Add only selected fields
            foreach ($columnData as $field) {
                if ($field === 'status') {
                    $stockItem[$field] = ($stock->quantite <= $stock->seuil) ? 'Stock Bas' : 'Stock Normal';
                } elseif ($field === 'photo_display') {
                    $stockItem[$field] = $stock->photo ? 'Oui' : 'Non';
                } elseif ($field === 'created_at') {
                    $stockItem[$field] = $stock->created_at ? \Carbon\Carbon::parse($stock->created_at)->format('d/m/Y H:i') : '';
                } elseif ($field === 'date_expiration') {
                    $stockItem[$field] = $stock->date_expiration ? \Carbon\Carbon::parse($stock->date_expiration)->format('d/m/Y') : '';
                } elseif ($field === 'price_achat') {
                    $stockItem[$field] = $stock->price_achat ? number_format($stock->price_achat, 2) . ' DH' : '0.00 DH';
                } elseif ($field === 'tva_value') {
                    $stockItem[$field] = $stock->tva_value ? number_format($stock->tva_value, 2) . '%' : '0.00%';
                } else {
                    $stockItem[$field] = $stock->{$field} ?? '';
                }
            }
            
            $stocks[] = $stockItem;
        }
        
        // Generate PDF
        $pdf = PDF::loadView('stock.pdf_export', [
            'stocks' => $stocks,
            'columns' => $columnTitles,
            'columnData' => $columnData,
            'date' => date('d/m/Y')
        ]);
        
        // Make PDF landscape and A4
        $pdf->setPaper('a4', 'landscape');
        
        // Download PDF
        return $pdf->download('GESTOCK TOUARGA - Stock - ' . date('d-m-Y') . '.pdf');
    }
    
    /**
     * Search product names for autocomplete in stock filters
     */
    public function searchProductNames(Request $request)
    {
        try {
            $query = $request->get('query', '');
            
            if (strlen($query) < 2) {
                return response()->json([
                    'status' => 200,
                    'products' => []
                ]);
            }
            
            $products = Product::where('name', 'LIKE', '%' . $query . '%')
                ->whereNull('deleted_at')
                ->select('id', 'name')
                ->limit(10)
                ->get();
            
            return response()->json([
                'status' => 200,
                'products' => $products
            ]);
            
        } catch (\Exception $e) {
            Log::error('Error searching product names', [
                'error' => $e->getMessage()
            ]);
            
            return response()->json([
                'status' => 500,
                'message' => 'Erreur lors de la recherche',
                'products' => []
            ], 500);
        }
    }
    
    /**
     * Get all categories for filter
     */
    public function getCategories()
    {
        try {
            $categories = DB::table('categories')
                ->whereNull('deleted_at')
                ->select('id', 'name')
                ->orderBy('name', 'asc')
                ->get();
            
            return response()->json([
                'status' => 200,
                'categories' => $categories
            ]);
        } catch (\Exception $e) {
            Log::error('Error fetching categories', [
                'error' => $e->getMessage()
            ]);
            
            return response()->json([
                'status' => 500,
                'message' => 'Erreur lors de la récupération des catégories',
                'categories' => []
            ], 500);
        }
    }
    
    /**
     * Get categories by class for filter
     */

public function getCategoriesByClass(Request $request)
{
    try {
        $class = $request->get('class');
        
        if (!$class) {
            return response()->json([
                'status' => 400,
                'message' => 'Classe non spécifiée',
                'data' => []
            ], 400);
        }
        
        $categories = DB::table('categories')
            ->where('classe', $class)
            ->whereNull('deleted_at')
            ->select('id', 'name')
            ->orderBy('name', 'asc')
            ->get();
        
        return response()->json([
            'status' => 200,
            'data' => $categories
        ]);
    } catch (\Exception $e) {
        Log::error('Error fetching categories by class', [
            'error' => $e->getMessage(),
            'class' => $request->get('class')
        ]);
        
        return response()->json([
            'status' => 500,
            'message' => 'Erreur lors de la récupération des catégories',
            'data' => []
        ], 500);
        }
    }
    
    /**
     * Get subcategories by category for filter
     */
    public function getSubcategories($categoryId)
    {
        try {
            $subcategories = DB::table('sub_categories')
                ->where('id_categorie', $categoryId)
                ->whereNull('deleted_at')
                ->select('id', 'name')
                ->orderBy('name', 'asc')
                ->get();
            
            return response()->json([
                'status' => 200,
                'subcategories' => $subcategories
            ]);
        } catch (\Exception $e) {
            Log::error('Error fetching subcategories', [
                'error' => $e->getMessage(),
                'category_id' => $categoryId
            ]);
            
            return response()->json([
                'status' => 500,
                'message' => 'Erreur lors de la récupération des familles',
                'subcategories' => []
            ], 500);
        }
    }
    /**
 * Display products that are expiring soon (within 7 days) or already expired
 */
public function expiringProducts(Request $request)
{
    if ($request->ajax()) {
        $dataExpiring = DB::table('products as p')
                    ->leftJoin('categories as c', 'p.id_categorie', '=', 'c.id')
                    ->leftJoin('sub_categories as sc', 'p.id_subcategorie', '=', 'sc.id')
                    ->leftJoin('stock as s', 'p.id', '=', 's.id_product')
                    ->leftJoin('unite as u', 'p.id_unite', '=', 'u.id')
                    ->whereNull('p.deleted_at')
                    ->whereNotNull('p.date_expiration')
                    ->where('p.date_expiration', '<=', DB::raw('DATE_ADD(CURDATE(), INTERVAL 7 DAY)'))
                    ->select(
                        'p.id',
                        'p.code_article',
                        'p.name',
                        'u.name as unite_name',
                        'c.name as categorie',
                        'sc.name as famille',
                        'p.emplacement',
                        's.quantite',
                        'p.date_expiration',
                        'p.photo',
                        'p.created_at',
                        DB::raw('DATEDIFF(p.date_expiration, CURDATE()) as days_until_expiry')
                    );
        
        // Apply class filter if provided
        if ($request->filled('filter_class')) {
            $dataExpiring->where('c.classe', $request->filter_class);
        }

        // Apply category filter if provided
        if ($request->filled('filter_categorie')) {
            $dataExpiring->where('p.id_categorie', $request->filter_categorie);
        }

        // Apply subcategory filter if provided
        if ($request->filled('filter_subcategorie')) {
            $dataExpiring->where('p.id_subcategorie', $request->filter_subcategorie);
        }

        // Apply designation (name) filter if provided
        if ($request->filled('filter_designation')) {
            $dataExpiring->where('p.name', 'LIKE', '%' . $request->filter_designation . '%');
        }
        
        $dataExpiring->orderBy('p.date_expiration', 'asc');

        return DataTables::of($dataExpiring)
            ->addIndexColumn()
            ->addColumn('expiry_status', function ($row) {
                if ($row->days_until_expiry < 0) {
                    return '<span class="badge bg-dark">Expiré</span>';
                } elseif ($row->days_until_expiry == 0) {
                    return '<span class="badge bg-danger">Expire aujourd\'hui</span>';
                } elseif ($row->days_until_expiry <= 3) {
                    return '<span class="badge bg-danger">Expire dans ' . $row->days_until_expiry . ' jour(s)</span>';
                } else {
                    return '<span class="badge bg-warning">Expire dans ' . $row->days_until_expiry . ' jour(s)</span>';
                }
            })
            ->addColumn('photo_display', function ($row) {
                if ($row->photo) {
                    return '<img src="' . asset('storage/' . $row->photo) . '" alt="Photo" style="width: 50px; height: 50px; object-fit: cover;" class="rounded">';
                }
                return '<span class="text-muted">Pas d\'image</span>';
            })
            ->editColumn('date_expiration', function ($row) {
                return $row->date_expiration ? \Carbon\Carbon::parse($row->date_expiration)->format('d/m/Y') : '';
            })
            ->editColumn('created_at', function ($row) {
                return $row->created_at ? \Carbon\Carbon::parse($row->created_at)->format('d/m/Y H:i') : '';
            })
            ->rawColumns(['expiry_status', 'photo_display'])
            ->make(true);
    }
    
    // Get distinct classes for filter dropdown
    $class = DB::table('categories')
                ->whereNotNull('classe')
                ->where('classe', '!=', '')
                ->distinct()
                ->pluck('classe')
                ->map(function($classe) {
                    return (object)['classe' => $classe];
                });
    
    return view('stock.expiring', compact('class'));
}

/**
 * Display products with low stock (at or below threshold)
 */
public function lowStockProducts(Request $request)
{
    if ($request->ajax()) {
        $dataLowStock = DB::table('stock as s')
                    ->join('products as p', 'p.id', 's.id_product')
                    ->leftJoin('categories as c', 'p.id_categorie', '=', 'c.id')
                    ->leftJoin('sub_categories as sc', 'p.id_subcategorie', '=', 'sc.id')
                    ->leftJoin('unite as u', 'p.id_unite', '=', 'u.id')
                    ->leftJoin('tvas as t', 'p.id_tva', '=', 't.id')
                    ->whereNull('s.deleted_at')
                    ->whereNull('p.deleted_at')
                    ->whereRaw('s.quantite <= p.seuil')
                    ->select(
                        's.id',
                        'p.code_article',
                        'p.name',
                        'u.name as unite_name',
                        'c.name as categorie',
                        'sc.name as famille',
                        'p.emplacement',
                        's.quantite',
                        'p.price_achat',
                        't.value as tva_value',
                        'p.seuil',
                        'p.photo',
                        'p.created_at',
                        DB::raw('(p.seuil - s.quantite) as deficit')
                    );
        
        // Apply class filter if provided
        if ($request->filled('filter_class')) {
            $dataLowStock->where('c.classe', $request->filter_class);
        }

        // Apply category filter if provided
        if ($request->filled('filter_categorie')) {
            $dataLowStock->where('p.id_categorie', $request->filter_categorie);
        }

        // Apply subcategory filter if provided
        if ($request->filled('filter_subcategorie')) {
            $dataLowStock->where('p.id_subcategorie', $request->filter_subcategorie);
        }

        // Apply designation (name) filter if provided
        if ($request->filled('filter_designation')) {
            $dataLowStock->where('p.name', 'LIKE', '%' . $request->filter_designation . '%');
        }
        
        $dataLowStock->orderBy('deficit', 'desc');

        return DataTables::of($dataLowStock)
            ->addIndexColumn()
            ->addColumn('stock_status', function ($row) {
                if ($row->quantite == 0) {
                    return '<span class="badge bg-dark">Stock Épuisé</span>';
                } elseif ($row->quantite < $row->seuil) {
                    return '<span class="badge bg-danger">Stock Critique</span>';
                } else {
                    return '<span class="badge bg-warning">Au Seuil</span>';
                }
            })
            ->addColumn('photo_display', function ($row) {
                if ($row->photo) {
                    return '<img src="' . asset('storage/' . $row->photo) . '" alt="Photo" style="width: 50px; height: 50px; object-fit: cover;" class="rounded">';
                }
                return '<span class="text-muted">Pas d\'image</span>';
            })
            ->editColumn('price_achat', function ($row) {
                return $row->price_achat ? number_format($row->price_achat, 2) . ' DH' : '0.00 DH';
            })
            ->editColumn('tva_value', function ($row) {
                return $row->tva_value ? number_format($row->tva_value, 2) . '%' : '0.00%';
            })
            ->editColumn('created_at', function ($row) {
                return $row->created_at ? \Carbon\Carbon::parse($row->created_at)->format('d/m/Y H:i') : '';
            })
            ->rawColumns(['stock_status', 'photo_display'])
            ->make(true);
    }
    
    // Get distinct classes for filter dropdown
    $class = DB::table('categories')
                ->whereNotNull('classe')
                ->where('classe', '!=', '')
                ->distinct()
                ->pluck('classe')
                ->map(function($classe) {
                    return (object)['classe' => $classe];
                });
    
    return view('stock.low_stock', compact('class'));
}
}