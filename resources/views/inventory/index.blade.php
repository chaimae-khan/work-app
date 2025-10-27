@extends('dashboard.index')

@section('dashboard')

<!-- Scripts personnalisés -->
<script>
    var csrf_token = "{{csrf_token()}}";
    var getProductInventory = "{{url('getProductInventory')}}";
    var generatePdf = "{{url('generateInventoryPdf')}}";
    var generateMultiMonthPdf = "{{url('generateMultiMonthPdf')}}";
    var getMonthlyReport = "{{url('getMonthlyReport')}}";
    var getYearlyReport = "{{url('getYearlyReport')}}";
    var getProductAveragePriceUrl = "{{url('getProductAveragePrice')}}";
    var generateAnnualBalancePdf = "{{url('generateAnnualBalancePdf')}}";
    var cardexUrl = "{{url('cardex')}}";
    var exportCardexExcelUrl = "{{url('exportCardexExcel')}}";
</script>
<script src="{{asset('js/inventory/script.js')}}"></script>

<!-- Add Select2 CSS for searchable dropdown -->
<link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />
<script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>

<style>
    /* Existing styles remain the same */
    .table-responsive {
        overflow-x: auto;
    }
    .inventory-table {
        min-width: 100%;
        border-collapse: collapse;
    }
    .inventory-table th, .inventory-table td {
        border: 1px solid #dee2e6;
        padding: 4px 8px;
        text-align: center;
        vertical-align: middle;
    }
    .inventory-table th {
        background-color: #f8f9fa;
        font-weight: 600;
        text-transform: uppercase;
    }
    .day-col {
        font-weight: 600;
        width: 30px;
    }
    .entree-cell {
        color: #198754;
    }
    .sortie-cell {
        color: #dc3545;
    }
    .reste-cell {
        font-weight: 600;
    }
    .totals-row {
        background-color: #f8f9fa;
        font-weight: 600;
    }
    
    /* New styles for inventory tables based on the images */
    .monthly-inventory-table {
        width: 100%;
        border-collapse: collapse;
    }
    .monthly-inventory-table th, .monthly-inventory-table td {
        border: 1px solid #000;
        padding: 3px 5px;
        height: 20px;
        vertical-align: middle;
        text-align: center;
    }
    .monthly-inventory-table th {
        background-color: #f8f9fa;
        font-weight: bold;
        text-transform: uppercase;
    }
    .month-header {
        text-align: center;
        font-weight: bold;
        text-transform: uppercase;
    }
    
    /* Annual balance table styles */
    #annual-balance-table {
        width: auto;
        min-width: 350px;
        margin: 0 auto;
        border-collapse: collapse;
    }
    #annual-balance-table th, #annual-balance-table td {
        border: 1px solid #000;
        padding: 5px 8px;
        text-align: center;
    }
    #annual-balance-table th:first-child, #annual-balance-table td:first-child {
        text-align: left;
    }
    #annual-balance-table tfoot {
        border-top: 2px solid #000;
    }
    
    /* Added styles for price summary box */
    .price-info-box {
        background-color: #f8f9fa;
        border-radius: 0.25rem;
        padding: 10px 15px;
        border-left: 4px solid #0d6efd;
    }
    .price-info-box .label {
        font-size: 0.9rem;
        color: #6c757d;
    }
    .price-info-box .value {
        font-size: 1.1rem;
        font-weight: bold;
        color: #0d6efd;
    }
    .price-info-box .unit {
        font-size: 0.9rem;
        color: #6c757d;
        margin-left: 2px;
    }
    
    /* Print button styles */
    @media print {
        .no-print {
            display: none !important;
        }
        .card {
            box-shadow: none !important;
            border: none !important;
        }
        .inventory-table {
            font-size: 10px;
        }
        .page-break {
            page-break-before: always;
        }
    }
    
    /* Pagination styles */
    .inventory-pagination {
        display: flex;
        justify-content: center;
        margin-top: 20px;
    }
    .inventory-pagination .page-link {
        color: #0d6efd;
        background-color: #fff;
        border: 1px solid #dee2e6;
        padding: 0.375rem 0.75rem;
        margin: 0 3px;
        border-radius: 0.25rem;
        text-decoration: none;
        cursor: pointer;
    }
    .inventory-pagination .page-link.active {
        color: #fff;
        background-color: #0d6efd;
        border-color: #0d6efd;
    }
    .inventory-pagination .page-link:hover {
        background-color: #e9ecef;
    }
    
    /* Style for Select2 dropdown to match your form-select */
    .select2-container .select2-selection--single {
        height: 38px !important;
        padding: 6px 12px !important;
        font-size: 1rem !important;
        border: 1px solid #ced4da !important;
        border-radius: 0.25rem !important;
    }
    
    .select2-container--default .select2-selection--single .select2-selection__rendered {
        line-height: 26px !important;
    }
    
    .select2-container--default .select2-selection--single .select2-selection__arrow {
        height: 36px !important;
    }
</style>
/* Add these styles to your cardex.blade.php file */
<style>
    @page {
        size: A4 portrait;
        margin: 1cm;
    }
    
    body {
        font-family: Arial, sans-serif;
        margin: 0;
        padding: 0;
        font-size: 10px; /* Fixed base font size for consistency */
    }
    
    /* Fixed dimensions to maintain layout */
    .cardex-table {
        width: 100%;
        table-layout: fixed; /* This prevents column width from changing */
        border-collapse: collapse;
    }
    
    .cardex-table th, .cardex-table td {
        border: 1px solid #000;
        padding: 2px;
        text-align: center;
        height: 16px;
        font-size: 9px;
        overflow: hidden;
        white-space: nowrap; /* Prevents text from wrapping */
    }
    
    /* Specific widths for columns */
    .cardex-table .date-column {
        width: 30px;
    }
    
    .cardex-table .entree-column,
    .cardex-table .sortie-column,
    .cardex-table .reste-column {
        width: 50px;
    }
    
    /* Annual balance table fixed dimensions */
    .annual-table {
        width: 100%;
        table-layout: fixed;
        border-collapse: collapse;
    }
    
    .annual-table th, .annual-table td {
        border: 1px solid #000;
        padding: 3px;
        text-align: center;
        font-size: 9px;
    }
    
    /* Ensure content doesn't scale unpredictably */
    .header-content {
        width: 100%;
        font-size: 10px;
    }
    
    /* Fixed size for product box */
    .product-box {
        width: 250px;
        height: 25px;
        border: 1px solid #000;
        display: inline-block;
        text-align: center;
        font-size: 12px;
        padding: 5px;
        margin-top: 5px;
        overflow: hidden;
        white-space: nowrap;
        text-overflow: ellipsis;
    }
</style>

<div class="content-page"> 
    <div class="content">
        <!-- Début du contenu -->
        <div class="container-fluid">
            <div class="py-3 d-flex align-items-sm-center flex-sm-row flex-column">
                <div class="flex-grow-1">
                    <h4 class="fs-18 fw-semibold m-0">Suivi d'inventaire</h4>
                </div>
                
                <div class="text-end">
                    <ol class="breadcrumb m-0 py-0">
                        <li class="breadcrumb-item"><a href="javascript: void(0);">Applications</a></li>
                        <li class="breadcrumb-item active">Inventaire</li>
                    </ol>
                </div>
            </div>
 
            <div class="row mb-3">
    <div class="col-12 text-end">
        <button class="btn btn-primary" id="print-cardex-btn">
            <i class="fa-solid fa-print me-1"></i> Imprimer CARDEX
        </button>
          <button class="btn btn-success" id="export-cardex-excel-btn">
            <i class="fa-solid fa-file-excel me-1"></i> Exporter Excel
        </button>
    </div>

</div>





            <div class="row">
                <div class="col-12">
                    <div class="card">
                        <div class="card-body">
                            <div class="row mb-3">
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="product_selector" class="form-label">Produit</label>
                                        <select class="form-select" id="product_selector">
                                            <option value="">Sélectionner un produit</option>
                                            @foreach ($products as $product)
                                                <option value="{{ $product->id }}">{{ $product->name }}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>
                                
                                <!-- Hidden year field with current year default -->
                                <input type="hidden" id="year_selector" value="{{ date('Y') }}">
                                
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label class="form-label">Prix Unitaire</label>
                                        <div class="price-info-box">
                                            <div class="d-flex align-items-baseline">
                                                <span class="value" id="unit-price">0.00</span>
                                                <span class="unit">DH</span>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            
                            <div class="row mb-3">
                                <!-- <div class="col-12 text-end">
                                    <button class="btn btn-outline-secondary me-2 no-print" id="print-btn">
                                        <i class="fa-solid fa-print me-1"></i> Imprimer
                                    </button>
                                    <button class="btn btn-outline-primary no-print" id="export-pdf">
                                        <i class="fa-solid fa-file-pdf me-1"></i> Exporter PDF
                                    </button>
                                </div> -->
                            </div>

                            <div id="inventory-view">
                                <div id="inventory-alert" class="alert alert-info d-flex align-items-center" role="alert">
                                    <i class="fa-solid fa-circle-info me-2"></i>
                                    <div>
                                        Veuillez sélectionner un produit pour afficher les données d'inventaire.
                                    </div>
                                </div>
                                
                                <div id="inventory-content" style="display: none;">
                                    <div class="d-flex justify-content-between align-items-center mb-3">
                                        <h5 id="inventory-title" class="mb-0">
                                            Inventaire pour : <span class="product-name fw-bold"></span> | 
                                            Année : <span class="year-value fw-bold">{{ date('Y') }}</span>
                                        </h5>
                                        <div class="no-print">
                                            <button class="btn btn-sm btn-outline-secondary" id="scroll-to-current">
                                                <i class="fa-solid fa-calendar-day me-1"></i> Mois actuel
                                            </button>
                                        </div>
                                    </div>
                                    
                                    <!-- Pagination for inventory pages -->
                                    <div class="inventory-pagination no-print mb-3">
                                        <button class="page-link" id="prev-page" disabled>Précédent</button>
                                        <button class="page-link active" data-page="1">1</button>
                                        <button class="page-link" data-page="2">2</button>
                                        <button class="page-link" id="next-page">Suivant</button>
                                    </div>
                                    
                                    <!-- Page 1: First half of months (January to July) -->
                                    <div class="inventory-page" id="page-1">
                                        <div class="table-responsive">
                                            <table class="monthly-inventory-table" id="first-half-table">
                                                <thead>
                                                    <tr>
                                                        <th rowspan="2">Dates</th>
                                                        <th colspan="3">JANVIER</th>
                                                        <th colspan="3">FEVRIER</th>
                                                        <th colspan="3">MARS</th>
                                                        <th colspan="3">AVRIL</th>
                                                        <th colspan="3">MAI</th>
                                                        <th colspan="3">JUIN</th>
                                                        <th colspan="3">JUILLET</th>
                                                    </tr>
                                                    <tr>
                                                        <th>Entrées</th>
                                                        <th>Sorties</th>
                                                        <th>Reste en magasin</th>
                                                        <th>Entrées</th>
                                                        <th>Sorties</th>
                                                        <th>Reste en magasin</th>
                                                        <th>Entrées</th>
                                                        <th>Sorties</th>
                                                        <th>Reste en magasin</th>
                                                        <th>Entrées</th>
                                                        <th>Sorties</th>
                                                        <th>Reste en magasin</th>
                                                        <th>Entrées</th>
                                                        <th>Sorties</th>
                                                        <th>Reste en magasin</th>
                                                        <th>Entrées</th>
                                                        <th>Sorties</th>
                                                        <th>Reste en magasin</th>
                                                        <th>Entrées</th>
                                                        <th>Sorties</th>
                                                        <th>Reste en magasin</th>
                                                    </tr>
                                                </thead>
                                                <tbody>
                                                    @for ($day = 1; $day <= 31; $day++)
                                                        <tr>
                                                            <td class="day-col">{{ $day }}</td>
                                                            <!-- January -->
                                                            <td class="entree-cell" data-day="{{ $day }}" data-month="1"></td>
                                                            <td class="sortie-cell" data-day="{{ $day }}" data-month="1"></td>
                                                            <td class="reste-cell" data-day="{{ $day }}" data-month="1"></td>
                                                            <!-- February -->
                                                            <td class="entree-cell" data-day="{{ $day }}" data-month="2"></td>
                                                            <td class="sortie-cell" data-day="{{ $day }}" data-month="2"></td>
                                                            <td class="reste-cell" data-day="{{ $day }}" data-month="2"></td>
                                                            <!-- March -->
                                                            <td class="entree-cell" data-day="{{ $day }}" data-month="3"></td>
                                                            <td class="sortie-cell" data-day="{{ $day }}" data-month="3"></td>
                                                            <td class="reste-cell" data-day="{{ $day }}" data-month="3"></td>
                                                            <!-- April -->
                                                            <td class="entree-cell" data-day="{{ $day }}" data-month="4"></td>
                                                            <td class="sortie-cell" data-day="{{ $day }}" data-month="4"></td>
                                                            <td class="reste-cell" data-day="{{ $day }}" data-month="4"></td>
                                                            <!-- May -->
                                                            <td class="entree-cell" data-day="{{ $day }}" data-month="5"></td>
                                                            <td class="sortie-cell" data-day="{{ $day }}" data-month="5"></td>
                                                            <td class="reste-cell" data-day="{{ $day }}" data-month="5"></td>
                                                            <!-- June -->
                                                            <td class="entree-cell" data-day="{{ $day }}" data-month="6"></td>
                                                            <td class="sortie-cell" data-day="{{ $day }}" data-month="6"></td>
                                                            <td class="reste-cell" data-day="{{ $day }}" data-month="6"></td>
                                                            <!-- July -->
                                                            <td class="entree-cell" data-day="{{ $day }}" data-month="7"></td>
                                                            <td class="sortie-cell" data-day="{{ $day }}" data-month="7"></td>
                                                            <td class="reste-cell" data-day="{{ $day }}" data-month="7"></td>
                                                        </tr>
                                                    @endfor
                                                </tbody>
                                                <tfoot>
                                                    <tr class="totals-row">
                                                        <th>TOTAUX</th>
                                                        <!-- January -->
                                                        <th class="month-total-entree" data-month="1">0.00</th>
                                                        <th class="month-total-sortie" data-month="1">0.00</th>
                                                        <th class="month-final-reste" data-month="1">0.00</th>
                                                        <!-- February -->
                                                        <th class="month-total-entree" data-month="2">0.00</th>
                                                        <th class="month-total-sortie" data-month="2">0.00</th>
                                                        <th class="month-final-reste" data-month="2">0.00</th>
                                                        <!-- March -->
                                                        <th class="month-total-entree" data-month="3">0.00</th>
                                                        <th class="month-total-sortie" data-month="3">0.00</th>
                                                        <th class="month-final-reste" data-month="3">0.00</th>
                                                        <!-- April -->
                                                        <th class="month-total-entree" data-month="4">0.00</th>
                                                        <th class="month-total-sortie" data-month="4">0.00</th>
                                                        <th class="month-final-reste" data-month="4">0.00</th>
                                                        <!-- May -->
                                                        <th class="month-total-entree" data-month="5">0.00</th>
                                                        <th class="month-total-sortie" data-month="5">0.00</th>
                                                        <th class="month-final-reste" data-month="5">0.00</th>
                                                        <!-- June -->
                                                        <th class="month-total-entree" data-month="6">0.00</th>
                                                        <th class="month-total-sortie" data-month="6">0.00</th>
                                                        <th class="month-final-reste" data-month="6">0.00</th>
                                                        <!-- July -->
                                                        <th class="month-total-entree" data-month="7">0.00</th>
                                                        <th class="month-total-sortie" data-month="7">0.00</th>
                                                        <th class="month-final-reste" data-month="7">0.00</th>
                                                    </tr>
                                                </tfoot>
                                            </table>
                                        </div>
                                    </div>
                                    
                                    <!-- Page 2: Second half of months (August to December) with Annual Balance -->
                                    <div class="inventory-page" id="page-2" style="display: none;">
                                        <div class="row">
                                            <div class="col-md-8">
                                                <table class="monthly-inventory-table" id="second-half-table">
                                                    <thead>
                                                        <tr>
                                                            <th rowspan="2">Dates</th>
                                                            <th colspan="3">AOUT</th>
                                                            <th colspan="3">SEPTEMBRE</th>
                                                            <th colspan="3">OCTOBRE</th>
                                                            <th colspan="3">NOVEMBRE</th>
                                                            <th colspan="3">DECEMBRE</th>
                                                        </tr>
                                                        <tr>
                                                            <th>Entrées</th>
                                                            <th>Sorties</th>
                                                            <th>Reste en magasin</th>
                                                            <th>Entrées</th>
                                                            <th>Sorties</th>
                                                            <th>Reste en magasin</th>
                                                            <th>Entrées</th>
                                                            <th>Sorties</th>
                                                            <th>Reste en magasin</th>
                                                            <th>Entrées</th>
                                                            <th>Sorties</th>
                                                            <th>Reste en magasin</th>
                                                            <th>Entrées</th>
                                                            <th>Sorties</th>
                                                            <th>Reste en magasin</th>
                                                        </tr>
                                                    </thead>
                                                    <tbody>
                                                        @for ($day = 1; $day <= 31; $day++)
                                                            <tr>
                                                                <td class="day-col">{{ $day }}</td>
                                                                <!-- August -->
                                                                <td class="entree-cell" data-day="{{ $day }}" data-month="8"></td>
                                                                <td class="sortie-cell" data-day="{{ $day }}" data-month="8"></td>
                                                                <td class="reste-cell" data-day="{{ $day }}" data-month="8"></td>
                                                                <!-- September -->
                                                                <td class="entree-cell" data-day="{{ $day }}" data-month="9"></td>
                                                                <td class="sortie-cell" data-day="{{ $day }}" data-month="9"></td>
                                                                <td class="reste-cell" data-day="{{ $day }}" data-month="9"></td>
                                                                <!-- October -->
                                                                <td class="entree-cell" data-day="{{ $day }}" data-month="10"></td>
                                                                <td class="sortie-cell" data-day="{{ $day }}" data-month="10"></td>
                                                                <td class="reste-cell" data-day="{{ $day }}" data-month="10"></td>
                                                                <!-- November -->
                                                                <td class="entree-cell" data-day="{{ $day }}" data-month="11"></td>
                                                                <td class="sortie-cell" data-day="{{ $day }}" data-month="11"></td>
                                                                <td class="reste-cell" data-day="{{ $day }}" data-month="11"></td>
                                                                <!-- December -->
                                                                <td class="entree-cell" data-day="{{ $day }}" data-month="12"></td>
                                                                <td class="sortie-cell" data-day="{{ $day }}" data-month="12"></td>
                                                                <td class="reste-cell" data-day="{{ $day }}" data-month="12"></td>
                                                            </tr>
                                                        @endfor
                                                    </tbody>
                                                    <tfoot>
                                                        <tr class="totals-row">
                                                            <th>TOTAUX</th>
                                                            <!-- August -->
                                                            <th class="month-total-entree" data-month="8">0.00</th>
                                                            <th class="month-total-sortie" data-month="8">0.00</th>
                                                            <th class="month-final-reste" data-month="8">0.00</th>
                                                            <!-- September -->
                                                            <th class="month-total-entree" data-month="9">0.00</th>
                                                            <th class="month-total-sortie" data-month="9">0.00</th>
                                                            <th class="month-final-reste" data-month="9">0.00</th>
                                                            <!-- October -->
                                                            <th class="month-total-entree" data-month="10">0.00</th>
                                                            <th class="month-total-sortie" data-month="10">0.00</th>
                                                            <th class="month-final-reste" data-month="10">0.00</th>
                                                            <!-- November -->
                                                            <th class="month-total-entree" data-month="11">0.00</th>
                                                            <th class="month-total-sortie" data-month="11">0.00</th>
                                                            <th class="month-final-reste" data-month="11">0.00</th>
                                                            <!-- December -->
                                                            <th class="month-total-entree" data-month="12">0.00</th>
                                                            <th class="month-total-sortie" data-month="12">0.00</th>
                                                            <th class="month-final-reste" data-month="12">0.00</th>
                                                        </tr>
                                                    </tfoot>
                                                </table>
                                            </div>
                                            
                                            <div class="col-md-4">
                                                <h5 class="text-center mb-2">BALANCE ANNUELLE</h5>
                                                <table class="table table-bordered" id="annual-balance-table">
                                                    <thead>
                                                        <tr>
                                                            <th>Mois</th>
                                                            <th>Entrées</th>
                                                            <th>Sorties</th>
                                                        </tr>
                                                    </thead>
                                                    <tbody>
                                                        @foreach ($months as $monthNum => $monthName)
                                                        <tr>
                                                            <td>{{ $monthName }}</td>
                                                            <td class="text-end annual-entree" data-month="{{ $monthNum }}">-</td>
                                                            <td class="text-end annual-sortie" data-month="{{ $monthNum }}">-</td>
                                                        </tr>
                                                        @endforeach
                                                    </tbody>
                                                    <tfoot>
                                                        <tr class="fw-bold">
                                                            <td>Totaux de l'année</td>
                                                            <td class="text-end" id="annual-total-entree">-</td>
                                                            <td class="text-end" id="annual-total-sortie">-</td>
                                                        </tr>
                                                    </tfoot>
                                                </table>
                                                
                                                <!-- <div class="d-flex justify-content-end mt-3 no-print">
                                                    <button class="btn btn-sm btn-outline-secondary me-2" id="print-annual-btn">
                                                        <i class="fa-solid fa-print me-1"></i> Imprimer
                                                    </button>
                                                    <button class="btn btn-sm btn-outline-primary" id="export-annual-pdf">
                                                        <i class="fa-solid fa-file-pdf me-1"></i> PDF
                                                    </button>
                                                </div> -->
                                            </div>
                                        </div>
                                    </div>
                                    
                                    <!-- Bottom pagination for inventory pages -->
                                    <div class="inventory-pagination no-print mt-3">
                                        <button class="page-link" id="prev-page-bottom" disabled>Précédent</button>
                                        <button class="page-link active" data-page="1">1</button>
                                        <button class="page-link" data-page="2">2</button>
                                        <button class="page-link" id="next-page-bottom">Suivant</button>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Initialize Select2 on product selector -->
<script>
$(document).ready(function() {
    // Make the select searchable
    $('#product_selector').select2({
        placeholder: 'Sélectionner un produit',
        allowClear: true,
        width: '100%'
    });
    
    // Add print button handler to your existing script
    $('#print-btn').on('click', function() {
        window.print();
    });
    
    // Pagination functionality
    let currentPage = 1;
    
    function updatePagination() {
        // Hide all pages
        $('.inventory-page').hide();
        
        // Show current page
        $('#page-' + currentPage).show();
        
        // Update active state
        $('.inventory-pagination .page-link[data-page]').removeClass('active');
        $('.inventory-pagination .page-link[data-page="' + currentPage + '"]').addClass('active');
        
        // Enable/disable prev/next buttons
        if (currentPage === 1) {
            $('#prev-page, #prev-page-bottom').attr('disabled', true);
        } else {
            $('#prev-page, #prev-page-bottom').removeAttr('disabled');
        }
        
        if (currentPage === 2) {
            $('#next-page, #next-page-bottom').attr('disabled', true);
        } else {
            $('#next-page, #next-page-bottom').removeAttr('disabled');
        }
    }
    
    // Page number buttons
    $('.inventory-pagination .page-link[data-page]').on('click', function() {
        currentPage = parseInt($(this).data('page'));
        updatePagination();
    });
    
    // Previous page button
    $('#prev-page, #prev-page-bottom').on('click', function() {
        if (currentPage > 1) {
            currentPage--;
            updatePagination();
        }
    });
    
    // Next page button
    $('#next-page, #next-page-bottom').on('click', function() {
        if (currentPage < 2) {
            currentPage++;
            updatePagination();
        }
    });
    
    // Initialize pagination
    updatePagination();
});
</script>

<script>
// CARDEX print button handler for direct PDF generation
$('#print-cardex-btn').on('click', function() {
    // Get the selected product ID
    var productId = $('#product_selector').val();
    
    if (!productId) {
        alert('Veuillez sélectionner un produit avant d\'imprimer le CARDEX');
        return;
    }
    
    // Get the selected year
    var year = $('#year_selector').val() || new Date().getFullYear();
    
    // Show loading indicator
    var btn = $(this);
    var originalText = btn.html();
    btn.html('<i class="fa-solid fa-spinner fa-spin me-1"></i> Préparation...');
    btn.prop('disabled', true);
    
    // Create the URL for CARDEX PDF generation
    var pdfUrl = cardexUrl + "?product_id=" + productId + "&year=" + year + "&download=true";
    
    // Trigger PDF download
    window.location.href = pdfUrl;
    
    // Reset button after a delay
    setTimeout(function() {
        btn.html(originalText);
        btn.prop('disabled', false);
    }, 3000);
});
</script>
<script>
// Excel export button handler
$('#export-cardex-excel-btn').on('click', function() {
    // Get the selected product ID
    var productId = $('#product_selector').val();
    
    if (!productId) {
        alert('Veuillez sélectionner un produit avant d\'exporter le CARDEX');
        return;
    }
    
    // Get the selected year
    var year = $('#year_selector').val() || new Date().getFullYear();
    
    // Show loading indicator
    var btn = $(this);
    var originalText = btn.html();
    btn.html('<i class="fa-solid fa-spinner fa-spin me-1"></i> Préparation...');
    btn.prop('disabled', true);
    
    // Create the URL for CARDEX Excel export
    var excelUrl = "{{ url('exportCardexExcel') }}" + "?product_id=" + productId + "&year=" + year;
    
    // Trigger Excel download
    window.location.href = excelUrl;
    
    // Reset button after a delay
    setTimeout(function() {
        btn.html(originalText);
        btn.prop('disabled', false);
    }, 3000);
});
</script>

@endsection