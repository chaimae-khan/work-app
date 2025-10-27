<!-- ALL CONSUMPTION VIEW -->
@extends('dashboard.index')

@section('dashboard')

<!-- SECTION 1: JavaScript variables -->
<script>
    var getAllConsumptionData_url = "{{ url('getAllConsumptionData') }}";
    var exportPdfUrl = "{{ url('exportPDF') }}";
    var exportAllPdfUrl = "{{ url('exportAllConsumptionPDF') }}";
    var exportExcelUrl = "{{ url('exportExcel') }}";
    var csrf_token = "{{ csrf_token() }}";
</script>

<!-- SECTION 2: CSS Styles -->
<style>
    .table-responsive {
        overflow-x: auto;
    }
    
    .consumption-section {
        margin-bottom: 30px;
    }
    
    .section-title {
        background-color: #f8f9fa;
        padding: 10px;
        margin: 20px 0;
        font-weight: bold;
        text-align: center;
    }
    
    .category-header {
        background-color: #f8f9fa;
    }
    
    .table-totals {
        background-color: #e9ecef;
    }
    
    /* Fixed table header styling */
    .table thead th {
        position: sticky;
        top: 0;
        background-color: white;
        border-bottom: 2px solid #dee2e6;
        vertical-align: middle;
        text-align: center;
    }
    
    /* Better styling for the table */
    .table-bordered th, 
    .table-bordered td {
        vertical-align: middle;
        padding: 6px;
    }
    
    /* Improve readability with alternating row colors */
    .table-bordered tbody tr:nth-of-type(odd) {
        background-color: rgba(0, 0, 0, 0.02);
    }
    
    /* Make numbers right-aligned */
    .table-bordered td:nth-child(n+2) {
        text-align: right;
    }
    
    /* But keep product names left-aligned */
    .table-bordered td:first-child {
        text-align: left;
    }
    
    /* Pagination styling */
    .pagination-container {
        display: flex;
        justify-content: space-between;
        align-items: center;
        margin-bottom: 15px;
    }
    
    .pagination-info {
        margin-bottom: 0;
    }
    
    .pagination-controls {
        display: flex;
        gap: 5px;
    }
    
    .pagination-controls button {
        padding: 5px 10px;
        border: 1px solid #dee2e6;
        background-color: #fff;
        cursor: pointer;
    }
    
    .pagination-controls button.active {
        background-color: #0d6efd;
        color: white;
        border-color: #0d6efd;
    }
    
    .pagination-controls button:hover:not(.active) {
        background-color: #e9ecef;
    }
    
    .items-per-page {
        display: flex;
        align-items: center;
        gap: 10px;
    }
    
    /* Side-by-side table layout */
    .table-container {
        display: flex;
        justify-content: space-between;
        margin-bottom: 20px;
    }
    
    .table-half {
        width: 48%;
    }
    
    .blue-line {
        height: 2px;
        background-color: #0d6efd;
        margin: 0;
        padding: 0;
        border: none;
    }
    
    .new-page {
        page-break-before: always;
    }
    
    /* Menu attributes styling */
    .menu-section {
        margin: 20px 0;
        page-break-inside: avoid;
    }
    
    .menu-section table {
        width: 100%;
        border-collapse: collapse;
    }
    
    .menu-section th,
    .menu-section td {
        border: 1px solid #333;
        padding: 10px;
        text-align: center;
    }
    
    .menu-section th {
        background-color: #f8f9fa;
        font-weight: bold;
    }
    
    .menu-section .dotted-line {
        border-bottom: 1px dotted #999;
        height: 20px;
        width: 100%;
    }
    
    /* Print-friendly styles */
    @media print {
        .blue-line {
            border-top: 2px solid #0d6efd !important;
            -webkit-print-color-adjust: exact;
            color-adjust: exact;
        }
        
        .table-container {
            page-break-inside: avoid;
        }
        
        .new-page {
            page-break-before: always;
        }
        
        .table-bordered thead th {
            background-color: #f8f9fa !important;
            -webkit-print-color-adjust: exact;
            color-adjust: exact;
        }
        
        .category-header {
            background-color: #f8f9fa !important;
            -webkit-print-color-adjust: exact;
            color-adjust: exact;
        }
        
        .table-totals {
            background-color: #e9ecef !important;
            -webkit-print-color-adjust: exact;
            color-adjust: exact;
        }
        
        .menu-section th {
            background-color: #f8f9fa !important;
            -webkit-print-color-adjust: exact;
            color-adjust: exact;
        }
        
        .pagination-container, 
        #btnSearch, 
        #btnExportPDF, 
        #btnExportExcel,
        .form-control,
        .form-label {
            display: none !important;
        }
    }
</style>

<!-- SECTION 3: HTML Structure -->
<div class="content-page">
    <div class="content">
        <div class="container-fluid">
            <!-- Page Header -->
            <div class="py-3 d-flex align-items-sm-center flex-sm-row flex-column">
                <div class="flex-grow-1">
                    <h4 class="fs-18 fw-semibold m-0">Vue Complète de Consommation</h4>
                </div>
                
                <div class="text-end">
                    <ol class="breadcrumb m-0 py-0">
                        <li class="breadcrumb-item"><a href="javascript: void(0);">Applications</a></li>
                        <li class="breadcrumb-item active">Consommation Complète</li>
                    </ol>
                </div>
            </div>

            <!-- Search Controls -->
            <div class="row mb-4">
                <div class="col-md-4">
                    <label for="consumption_date" class="form-label">Date</label>
                    <input type="date" class="form-control" id="consumption_date" value="{{ date('Y-m-d') }}">
                </div>
                <div class="col-md-8">
                    <br>
                    <button class="btn btn-primary" id="btnSearch">
                        <i class="fa fa-search"></i> Rechercher
                    </button>
                    <button class="btn btn-success" id="btnExportPDF">
                        <i class="fa fa-file-pdf"></i> Exporter PDF
                    </button>
                    <!-- <button class="btn btn-info" id="btnExportExcel">
                        <i class="fa fa-file-excel"></i> Exporter Excel
                    </button> -->
                </div>
            </div>

            <!-- Results Section -->
            <div class="row">
                <div class="col-12">
                    <div class="card">
                        <div class="card-body">
                            <!-- View Toggle Button - Only Côte à Côte now -->
                            <!-- <div class="mb-3">
                                <button class="btn btn-primary" id="btnShowSideBySide">
                                    <i class="fa fa-columns"></i> Vue Côte à Côte
                                </button>
                            </div> -->
                            
                            <!-- Side-by-side tables container -->
                            <div id="side-by-side-container"></div>
                            
                            <!-- Pagination -->
                            <div id="pagination-container" class="pagination-container" style="display: none;">
                                <div class="items-per-page">
                                    <span>Afficher</span>
                                    <select id="items-per-page" class="form-select form-select-sm" style="width: auto;">
                                        <option value="10">10</option>
                                        <option value="25">25</option>
                                        <option value="50">50</option>
                                        <option value="100">100</option>
                                    </select>
                                    <span>produits par page</span>
                                </div>
                                
                                <p class="pagination-info">Affichage de <span id="start-item">1</span> à <span id="end-item">10</span> sur <span id="total-items">0</span> produits</p>
                                
                                <div class="pagination-controls">
                                    <button id="btn-first-page" title="Première page">«</button>
                                    <button id="btn-prev-page" title="Page précédente">‹</button>
                                    <span id="page-numbers"></span>
                                    <button id="btn-next-page" title="Page suivante">›</button>
                                    <button id="btn-last-page" title="Dernière page">»</button>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Hidden printable content div -->
<div id="consumption-printable-content" style="display: none;">
    <div class="consumption-pdf-content"></div>
</div>

<script>
$(document).ready(function() {
    // Add a display name mapping to translate backend menu names to frontend display names
    const menuDisplayNames = {
        'Menu Élèves': 'Menu Standard',
        'Menu eleves': 'Menu Standard'
    };
    
    // Helper function to get display name for a menu
    function getMenuDisplayName(menuType) {
        return menuDisplayNames[menuType] || menuType;
    }
    
    // NEW: Menu attributes function
    function generateMenuAttributesTable(data) {
        // Extract menu attributes for the 3 menu types
        const menuAttributes = {
            'Menu eleves': { entree: '', plat_principal: '', accompagnement: '', dessert: '' },
            'Menu d\'application': { entree: '', plat_principal: '', accompagnement: '', dessert: '' },
            'Menu specials': { entree: '', plat_principal: '', accompagnement: '', dessert: '' }
        };
        
        // Extract attributes from sorties data
        if (data.sorties && data.sorties.consumptions) {
            data.sorties.consumptions.forEach(consumption => {
                const menuType = consumption.type_menu;
                
                if (menuType && menuAttributes[menuType]) {
                    menuAttributes[menuType].entree = consumption.entree || '';
                    menuAttributes[menuType].plat_principal = consumption.plat_principal || '';
                    menuAttributes[menuType].accompagnement = consumption.accompagnement || '';
                    menuAttributes[menuType].dessert = consumption.dessert || '';
                }
            });
        }
        
        // Generate HTML using tables for easier formatting
        let html = `
            <div class="menu-section">
                <!-- Main MENU Table -->
                <h4 style="text-align: center; text-decoration: underline; margin-bottom: 15px;">MENU</h4>
                <table style="margin-bottom: 30px;">
                    <thead>
                        <tr>
                            <th style="text-decoration: underline; width: 33.33%;">Petit Déjeuner</th>
                            <th style="text-decoration: underline; width: 33.33%;">Lunch</th>
                            <th style="text-decoration: underline; width: 33.33%;">Dîner</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr>
                            <td style="height: 50px; vertical-align: top;">
                                <div class="dotted-line"></div>
                            </td>
                            <td style="height: 50px; vertical-align: top;">
                                ${menuAttributes['Menu eleves'].entree}
                            </td>
                            <td style="height: 50px; vertical-align: top;">
                                <div class="dotted-line"></div>
                            </td>
                        </tr>
                        <tr>
                            <td style="height: 50px; vertical-align: top;">
                                <div class="dotted-line"></div>
                            </td>
                            <td style="height: 50px; vertical-align: top;">
                                ${menuAttributes['Menu eleves'].plat_principal}
                            </td>
                            <td style="height: 50px; vertical-align: top;">
                                <div class="dotted-line"></div>
                            </td>
                        </tr>
                        <tr>
                            <td style="height: 50px; vertical-align: top;">
                                <div class="dotted-line"></div>
                            </td>
                            <td style="height: 50px; vertical-align: top;">
                                ${menuAttributes['Menu eleves'].accompagnement}
                            </td>
                            <td style="height: 50px; vertical-align: top;">
                                <div class="dotted-line"></div>
                            </td>
                        </tr>
                        <tr>
                            <td style="height: 50px; vertical-align: top;">
                                <div class="dotted-line"></div>
                            </td>
                            <td style="height: 50px; vertical-align: top;">
                                ${menuAttributes['Menu eleves'].dessert}
                            </td>
                            <td style="height: 50px; vertical-align: top;">
                                <div class="dotted-line"></div>
                            </td>
                        </tr>
                    </tbody>
                </table>
                
                <!-- MENU APPLICATION Table -->
                <h4 style="text-align: center; margin: 20px 0 15px 0;">MENU APPLICATION</h4>
                <table style="margin-bottom: 30px;">
                    <thead>
                        <tr>
                            <th style="text-decoration: underline; width: 33.33%;">Petit Déjeuner</th>
                            <th style="text-decoration: underline; width: 33.33%;">Lunch</th>
                            <th style="text-decoration: underline; width: 33.33%;">Dîner</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr>
                            <td style="height: 40px; vertical-align: top;">
                                <div class="dotted-line"></div>
                            </td>
                            <td style="height: 40px; vertical-align: top;">
                                ${menuAttributes['Menu d\'application'].entree}
                            </td>
                            <td style="height: 40px; vertical-align: top;">
                                <div class="dotted-line"></div>
                            </td>
                        </tr>
                        <tr>
                            <td style="height: 40px; vertical-align: top;">
                                <div class="dotted-line"></div>
                            </td>
                            <td style="height: 40px; vertical-align: top;">
                                ${menuAttributes['Menu d\'application'].plat_principal}
                            </td>
                            <td style="height: 40px; vertical-align: top;">
                                <div class="dotted-line"></div>
                            </td>
                        </tr>
                        <tr>
                            <td style="height: 40px; vertical-align: top;">
                                <div class="dotted-line"></div>
                            </td>
                            <td style="height: 40px; vertical-align: top;">
                                ${menuAttributes['Menu d\'application'].accompagnement}
                            </td>
                            <td style="height: 40px; vertical-align: top;">
                                <div class="dotted-line"></div>
                            </td>
                        </tr>
                        <tr>
                            <td style="height: 40px; vertical-align: top;">
                                <div class="dotted-line"></div>
                            </td>
                            <td style="height: 40px; vertical-align: top;">
                                ${menuAttributes['Menu d\'application'].dessert}
                            </td>
                            <td style="height: 40px; vertical-align: top;">
                                <div class="dotted-line"></div>
                            </td>
                        </tr>
                    </tbody>
                </table>
                
                <!-- MENU SPÉCIAL Table -->
                <h4 style="text-align: center; margin: 20px 0 15px 0;">MENU SPÉCIAL</h4>
                <table>
                    <thead>
                        <tr>
                            <th style="text-decoration: underline; width: 33.33%;">Petit Déjeuner</th>
                            <th style="text-decoration: underline; width: 33.33%;">Lunch</th>
                            <th style="text-decoration: underline; width: 33.33%;">Dîner</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr>
                            <td style="height: 40px; vertical-align: top;">
                                <div class="dotted-line"></div>
                            </td>
                            <td style="height: 40px; vertical-align: top;">
                                ${menuAttributes['Menu specials'].entree}
                            </td>
                            <td style="height: 40px; vertical-align: top;">
                                <div class="dotted-line"></div>
                            </td>
                        </tr>
                        <tr>
                            <td style="height: 40px; vertical-align: top;">
                                <div class="dotted-line"></div>
                            </td>
                            <td style="height: 40px; vertical-align: top;">
                                ${menuAttributes['Menu specials'].plat_principal}
                            </td>
                            <td style="height: 40px; vertical-align: top;">
                                <div class="dotted-line"></div>
                            </td>
                        </tr>
                        <tr>
                            <td style="height: 40px; vertical-align: top;">
                                <div class="dotted-line"></div>
                            </td>
                            <td style="height: 40px; vertical-align: top;">
                                ${menuAttributes['Menu specials'].accompagnement}
                            </td>
                            <td style="height: 40px; vertical-align: top;">
                                <div class="dotted-line"></div>
                            </td>
                        </tr>
                        <tr>
                            <td style="height: 40px; vertical-align: top;">
                                <div class="dotted-line"></div>
                            </td>
                            <td style="height: 40px; vertical-align: top;">
                                ${menuAttributes['Menu specials'].dessert}
                            </td>
                            <td style="height: 40px; vertical-align: top;">
                                <div class="dotted-line"></div>
                            </td>
                        </tr>
                    </tbody>
                </table>
            </div>
        `;
        
        return html;
    }
    
    // Current view mode - only 'sideBySide' now
    let currentViewMode = 'sideBySide';
    
    // Initialize datepicker to current date
    const today = new Date().toISOString().split('T')[0];
    $('#consumption_date').val(today);
    
    // Variables for pagination
    let allProducts = [];
    let currentPage = 1;
    let itemsPerPage = 50; // Set a higher default for side-by-side view
    let totalPages = 1;
    
    // Event handler for items per page change
    $('#items-per-page').on('change', function() {
        itemsPerPage = parseInt($(this).val());
        currentPage = 1; // Reset to first page when changing items per page
        if (allProducts.length > 0) {
            renderSideBySideTables();
        }
    });
    
    // Event handlers for pagination buttons
    $('#btn-first-page').on('click', function() {
        if (currentPage > 1) {
            currentPage = 1;
            renderSideBySideTables();
        }
    });
    
    $('#btn-prev-page').on('click', function() {
        if (currentPage > 1) {
            currentPage--;
            renderSideBySideTables();
        }
    });
    
    $('#btn-next-page').on('click', function() {
        if (currentPage < totalPages) {
            currentPage++;
            renderSideBySideTables();
        }
    });
    
    $('#btn-last-page').on('click', function() {
        if (currentPage < totalPages) {
            currentPage = totalPages;
            renderSideBySideTables();
        }
    });
    
    // Event delegation for page number buttons
    $(document).on('click', '.page-number', function() {
        currentPage = parseInt($(this).data('page'));
        renderSideBySideTables();
    });

    $('#btnSearch').on('click', function(e) {
        e.preventDefault();
        
        const date = $('#consumption_date').val();
        
        if (!date) {
            new AWN().alert('Veuillez sélectionner une date');
            return;
        }
        
        console.log('Sending request with:', { date: date });
        
        $.ajax({
            type: "GET",
            url: getAllConsumptionData_url,
            data: { date: date },
            dataType: "json",
            beforeSend: function() {
                $('#btnSearch').prop('disabled', true);
                $('#btnSearch').html('<i class="fa fa-spinner fa-spin"></i> Chargement...');
            },
            success: function(response) {
                console.log('Full response:', response);
                
                if (response.status == 200) {
                    // Process the data and prepare for pagination
                    processConsumptionData(response.data);
                    
                    // Render the side-by-side view
                    renderSideBySideTables();
                } else {
                    console.log('Error response:', response);
                    new AWN().warning(response.message || 'Aucune donnée trouvée');
                    $('#pagination-container').hide();
                    $('#side-by-side-container').html('<p class="text-center">Aucune donnée disponible pour cette date</p>');
                }
            },
            error: function(xhr, status, error) {
                console.error("Error:", error);
                console.error("Status:", status);
                console.error("Response text:", xhr.responseText);
                new AWN().alert("Erreur lors de la récupération des données");
                $('#pagination-container').hide();
            },
            complete: function() {
                $('#btnSearch').prop('disabled', false);
                $('#btnSearch').html('<i class="fa fa-search"></i> Rechercher');
            }
        });
    });
    
    $('#btnExportPDF').on('click', function(e) {
        e.preventDefault();
        
        const date = $('#consumption_date').val();
        
        if (!date) {
            new AWN().alert('Veuillez sélectionner une date');
            return;
        }
        
        // Create a form and submit it for PDF download
        const form = document.createElement('form');
        form.method = 'GET';
        form.action = exportAllPdfUrl;
        
        // Create hidden field for date
        const dateField = document.createElement('input');
        dateField.type = 'hidden';
        dateField.name = 'date';
        dateField.value = date;
        form.appendChild(dateField);
        
        // Add view_mode parameter based on current view
        const viewModeField = document.createElement('input');
        viewModeField.type = 'hidden';
        viewModeField.name = 'view_mode';
        viewModeField.value = currentViewMode; // Use the currentViewMode variable from your JS
        form.appendChild(viewModeField);
        
        document.body.appendChild(form);
        form.submit();
        document.body.removeChild(form);
    });
    
    // Export Excel button handler
    $('#btnExportExcel').on('click', function(e) {
        e.preventDefault();
        
        const date = $('#consumption_date').val();
        
        if (!date) {
            new AWN().alert('Veuillez sélectionner une date');
            return;
        }
        
        // Create a form and submit it for Excel download
        const form = document.createElement('form');
        form.method = 'GET';
        form.action = exportExcelUrl;
        
        // Create hidden field for date
        const hidden = document.createElement('input');
        hidden.type = 'hidden';
        hidden.name = 'date';
        hidden.value = date;
        form.appendChild(hidden);
        
        document.body.appendChild(form);
        form.submit();
        document.body.removeChild(form);
    });
    
    // SECTION 5: Data Processing Function
    function processConsumptionData(data) {
        // Get products from entrées
        let entriesProducts = [];
        if (data.entrees && data.entrees.consumptions) {
            data.entrees.consumptions.forEach(consumption => {
                if (consumption.products && consumption.products.length > 0) {
                    entriesProducts = entriesProducts.concat(consumption.products);
                }
            });
        }
        
        // Map for quick lookup of entrée products
        let entriesMap = {};
        entriesProducts.forEach(product => {
            entriesMap[product.product_id] = product;
        });
        
        // Get products from sorties and organize by menu type
        let sortiesMenus = {};
        sortiesMenus['Menu Élèves'] = {};
        sortiesMenus['Menu d\'application'] = {}; // Added new menu type
        sortiesMenus['Menu Spécial'] = {};
        
        let allProductsMap = new Map(); // To store all unique products
        
        if (data.sorties && data.sorties.consumptions) {
            data.sorties.consumptions.forEach(menu => {
                const menuType = menu.type_menu;
                
                // Determine which menu to put the products in based on the menu type
                let targetMenu;
                if (menuType === 'Menu specials' || menuType === 'Menu Spécial') {
                    targetMenu = 'Menu Spécial';
                } else if (menuType === "Menu d'application" || menuType === 'Menu d\'application') {
                    targetMenu = 'Menu d\'application';
                } else {
                    targetMenu = 'Menu Élèves';
                }
                
                if (menu.products && menu.products.length > 0) {
                    menu.products.forEach(product => {
                        // Add to the appropriate menu object
                        sortiesMenus[targetMenu][product.product_id] = product;
                        
                        // Add to all products map
                        if (!allProductsMap.has(product.product_id)) {
                            allProductsMap.set(product.product_id, {
                                ...product,
                                in_entrees: !!entriesMap[product.product_id]
                            });
                        }
                    });
                }
            });
        }
        
        // Add any additional products from all_products
        if (data.all_products && data.all_products.length > 0) {
            data.all_products.forEach(product => {
                if (!allProductsMap.has(product.product_id)) {
                    allProductsMap.set(product.product_id, {
                        ...product,
                        in_entrees: !!entriesMap[product.product_id]
                    });
                }
            });
        }
        
        // Convert to array and sort by category then by name
        allProducts = Array.from(allProductsMap.values());
        
        // Sort products by category and then by name
        allProducts.sort((a, b) => {
            // First sort by category name
            const catCompare = (a.category_name || 'Non catégorisé').localeCompare(b.category_name || 'Non catégorisé');
            if (catCompare !== 0) return catCompare;
            
            // Then sort by product name within the same category
            return a.name.localeCompare(b.name);
        });
        
        // Set up pagination
        totalPages = Math.ceil(allProducts.length / itemsPerPage);
        currentPage = 1;
        
        // Store the data for rendering
        window.consumptionData = {
            data: data,
            entriesMap: entriesMap,
            sortiesMenus: sortiesMenus,
            allProducts: allProducts
        };
    }
    
    // Function to render side-by-side tables with continuous product numbering
    function renderSideBySideTables() {
        if (!window.consumptionData || window.consumptionData.allProducts.length === 0) {
            $('#pagination-container').hide();
            $('#side-by-side-container').html('<p class="text-center">Aucune donnée disponible pour cette date</p>');
            return;
        }
        
        const { data, entriesMap, sortiesMenus, allProducts } = window.consumptionData;
        
        // Calculate pagination
        const productsPerPage = itemsPerPage * 2;
        totalPages = Math.ceil(allProducts.length / productsPerPage);
        const startIndex = (currentPage - 1) * productsPerPage;
        const endIndex = Math.min(startIndex + productsPerPage, allProducts.length);
        const currentPageProducts = allProducts.slice(startIndex, endIndex);
        
        // Update pagination info
        $('#start-item').text(startIndex + 1);
        $('#end-item').text(endIndex);
        $('#total-items').text(allProducts.length);
        
        // Generate page numbers
        let pageNumbersHtml = '';
        const maxPageButtons = 5;
        let startPage = Math.max(1, currentPage - Math.floor(maxPageButtons / 2));
        let endPage = Math.min(totalPages, startPage + maxPageButtons - 1);
        
        if (endPage - startPage + 1 < maxPageButtons) {
            startPage = Math.max(1, endPage - maxPageButtons + 1);
        }
        
        for (let i = startPage; i <= endPage; i++) {
            pageNumbersHtml += `<button class="page-number ${i === currentPage ? 'active' : ''}" data-page="${i}">${i}</button>`;
        }
        
        $('#page-numbers').html(pageNumbersHtml);
        
        // Enable/disable pagination buttons
        $('#btn-first-page, #btn-prev-page').prop('disabled', currentPage === 1);
        $('#btn-next-page, #btn-last-page').prop('disabled', currentPage === totalPages);
        
        // Show/hide pagination
        if (totalPages > 1) {
            $('#pagination-container').show();
        } else {
            $('#pagination-container').hide();
        }
        
        // Start building HTML
        let html = `<h4 class="section-title">CONSOMMATION JOURNALIÈRE DU ${data.date}</h4>`;
        html += `<div class="table-container">`;
        
        // Split products between tables
        const totalProductsForPage = currentPageProducts.length;
        const productsPerSide = Math.ceil(totalProductsForPage / 2);
        let startingProductIndex = startIndex + 1;
        
        // Generate left table - SIMPLIFIED CALL (removed global totals)
        const [leftTableHtml, productCountAfterLeftTable] = generateSideTable(
            currentPageProducts, 
            0, 
            productsPerSide, 
            data, 
            entriesMap, 
            sortiesMenus,
            startingProductIndex
        );
        
        html += leftTableHtml;
        
        // Generate right table if needed
        if (productsPerSide < totalProductsForPage) {
            const [rightTableHtml] = generateSideTable(
                currentPageProducts, 
                productsPerSide, 
                totalProductsForPage, 
                data, 
                entriesMap, 
                sortiesMenus,
                productCountAfterLeftTable
            );
            
            html += rightTableHtml;
        }
        
        html += `</div>`;
        
        // Add other sections (EFFECTIF, etc.) only on last page
        const isLastPage = currentPage === totalPages;
        if (data.sorties && data.sorties.grand_totals && data.sorties.grand_totals.total_people > 0 && isLastPage) {
            // Add menu attributes display before EFFECTIF
            html += generateMenuAttributesTable(data);
            
            html += `
                <div class="mt-4 mb-3">
                    <h4 class="section-title">EFFECTIF</h4>
                    <table class="table table-bordered">
                        <thead>
                            <tr>
                                <th rowspan="2"></th>
                                <th>Petit déjeuner</th>
                                <th colspan="3">Lunch</th>
                                <th>Dîner</th>
                            </tr>
                            <tr>
                                <th></th>
                                <th>Menu standard</th>
                                <th>Menu d'application</th>
                                <th>Menu spécial</th>
                                <th></th>
                            </tr>
                        </thead>
                        <tbody>
            `;
            
            // Define menu types we want to specifically find
            const menuEleves = data.sorties.consumptions.find(c => c.type_menu === 'Menu eleves');
            const menuApplication = data.sorties.consumptions.find(c => c.type_menu === "Menu d'application");
            const menuSpeciaux = data.sorties.consumptions.find(c => c.type_menu === 'Menu specials');
            
            // Create rows for each category of people
            const categories = ['Élèves', 'Personnel', 'Invités', 'Divers'];
            
            categories.forEach(category => {
                const categoryKey = category === 'Élèves' ? 'eleves' : 
                                  category === 'Personnel' ? 'personnel' : 
                                  category === 'Invités' ? 'invites' : 'divers';
                
                html += `
                    <tr>
                        <td>${category}</td>
                        <td></td>
                        <td>${menuEleves ? (menuEleves[categoryKey] || 0) : 0}</td>
                        <td>${menuApplication ? (menuApplication[categoryKey] || 0) : 0}</td>
                        <td>${menuSpeciaux ? (menuSpeciaux[categoryKey] || 0) : 0}</td>
                        <td></td>
                    </tr>
                `;
            });
            
            html += `
                        </tbody>
                    </table>
                </div>
            `;
            
            // Add prix de revient section
            html += `<div class="mt-3 mb-5">`;
            
            // Track totals for General Prix Moyen calculation (only include specific menu types)
            let calculableTotalCost = 0;
            let calculableTotalPeople = 0;
            
            // Loop through all consumptions to show each menu type's price
            data.sorties.consumptions.forEach(menu => {
                if (menu.total_cost > 0) {
                    const menuType = menu.type_menu || 'Sans Menu';
                    
                    // Skip displaying Prix de Revient for Fournitures et matériels and Non Alimentaire
                    const skipDisplay = 
                        menuType === 'Fournitures et matériels' || 
                        menuType === 'Non Alimentaire';
                    
                    if (!skipDisplay) {
                        // Format the display name (remove redundant "Menu" word)
                        const displayName = menuType.startsWith('Menu') 
                            ? getMenuDisplayName(menuType) // Use our helper function here
                            : `Menu ${menuType}`;
                        
                        html += `
                            <p>Prix de Revient ${displayName} : <strong>${parseFloat(menu.total_cost).toFixed(2)}</strong></p>
                        `;
                        
                        // Only show Prix Moyen if total_people is greater than 0
                        if (menu.total_people > 0) {
                            html += `
                                <p>Prix Moyen ${displayName} : <strong>${(menu.total_cost / menu.total_people).toFixed(2)}</strong></p>
                            `;
                            
                            // Only include specific menu types in the general calculation
                            const isCalculableMenu = 
                                menuType === 'Menu Spécial' || 
                                menuType === 'Menu specials' || 
                                menuType === 'Menu Élèves' || 
                                menuType === 'Menu eleves' || 
                                menuType === "Menu d'application" || 
                                menuType === 'Menu d\'application';
                            
                            if (isCalculableMenu) {
                                calculableTotalCost += menu.total_cost;
                                calculableTotalPeople += menu.total_people;
                            }
                        }
                    }
                }
            });
            
            // Calculate overall average if we have any people (only using specific menu types)
            if (calculableTotalPeople > 0) {
                html += `
                    <p>Prix Moyen Général : <strong>${(calculableTotalCost / calculableTotalPeople).toFixed(2)}</strong></p>
                `;
            }
            
            html += `</div>`;
        }
        
        $('#side-by-side-container').html(html);
    }

    // UPDATED: Helper function to generate a side table with individual totals
    function generateSideTable(
        products, 
        startIndex, 
        endIndex, 
        data, 
        entriesMap, 
        sortiesMenus,
        startingProductIndex
    ) {
        // Basic table structure
        let tableHtml = `
            <div class="table-half">
                <table class="table table-bordered">
                    <thead>
                        <tr>
                            <th rowspan="2" style="width: 25%;">Désignation des Articles</th>
                            <th colspan="3" style="width: 15%;">ENTRÉES</th>
                            <th colspan="9" style="width: 60%;">SORTIES</th>
                        </tr>
                        <tr>
                            <th>Qté</th>
                            <th>P.U</th>
                            <th>P.T</th>
                            <th colspan="3">MENU STANDARD</th>
                            <th colspan="3">MENU D'APPLICATION</th>
                            <th colspan="3">MENU SPÉCIAL</th>
                        </tr>
                        <tr>
                            <th></th>
                            <th></th>
                            <th></th>
                            <th></th>
                            <th>Qté</th>
                            <th>P.U</th>
                            <th>P.T</th>
                            <th>Qté</th>
                            <th>P.U</th>
                            <th>P.T</th>
                            <th>Qté</th>
                            <th>P.U</th>
                            <th>P.T</th>
                        </tr>
                    </thead>
                    <tbody>
        `;
        
        // Slice the products for this table only
        const sideProducts = products.slice(startIndex, endIndex);
        
        // Initialize totals for THIS TABLE ONLY
        let tableEntreeTotal = 0;
        let tableElevesTotal = 0;
        let tableApplicationTotal = 0;
        let tableSpecialTotal = 0;
        
        // Group products by category
        const categoryGroups = {};
        sideProducts.forEach(product => {
            const categoryName = product.category_name || 'Non catégorisé';
            if (!categoryGroups[categoryName]) {
                categoryGroups[categoryName] = [];
            }
            categoryGroups[categoryName].push(product);
        });
        
        // Sort categories alphabetically
        const sortedCategories = Object.keys(categoryGroups).sort();
        
        // Current product number
        let productNumber = Number(startingProductIndex) || 1;
        
        // Add products by category
        sortedCategories.forEach(categoryName => {
            // Add category header
            tableHtml += `
                <tr class="category-header">
                    <td colspan="13"><strong>${categoryName.toUpperCase()}</strong></td>
                </tr>
            `;
            
            // Sort products by name within the category
            const productsInCategory = categoryGroups[categoryName].sort((a, b) => a.name.localeCompare(b.name));
            
            // Add products in this category
            productsInCategory.forEach((product) => {
                // Get data for each menu type
                const entreeData = entriesMap[product.product_id] || { quantity: 0, unit_price: 0, total_price: 0 };
                const elevesData = sortiesMenus['Menu Élèves']?.[product.product_id] || { quantity: 0, unit_price: 0, total_price: 0 };
                const applicationData = sortiesMenus['Menu d\'application']?.[product.product_id] || { quantity: 0, unit_price: 0, total_price: 0 };
                const specialData = sortiesMenus['Menu Spécial']?.[product.product_id] || { quantity: 0, unit_price: 0, total_price: 0 };
                
                // Add to table totals ONLY if there's actual consumption data
                if (entreeData.total_price && entreeData.total_price > 0) {
                    tableEntreeTotal += Number(entreeData.total_price);
                }
                if (elevesData.total_price && elevesData.total_price > 0) {
                    tableElevesTotal += Number(elevesData.total_price);
                }
                if (applicationData.total_price && applicationData.total_price > 0) {
                    tableApplicationTotal += Number(applicationData.total_price);
                }
                if (specialData.total_price && specialData.total_price > 0) {
                    tableSpecialTotal += Number(specialData.total_price);
                }
                
                tableHtml += `
                    <tr>
                        <td>${productNumber}- ${product.name}</td>
                        <td>${entreeData.quantity > 0 ? entreeData.quantity : ''}</td>
                        <td>${entreeData.unit_price > 0 ? parseFloat(entreeData.unit_price).toFixed(2) : ''}</td>
                        <td>${entreeData.total_price > 0 ? parseFloat(entreeData.total_price).toFixed(2) : ''}</td>
                        <td>${elevesData.quantity > 0 ? elevesData.quantity : ''}</td>
                        <td>${elevesData.unit_price > 0 ? parseFloat(elevesData.unit_price).toFixed(2) : ''}</td>
                        <td>${elevesData.total_price > 0 ? parseFloat(elevesData.total_price).toFixed(2) : ''}</td>
                        <td>${applicationData.quantity > 0 ? applicationData.quantity : ''}</td>
                        <td>${applicationData.unit_price > 0 ? parseFloat(applicationData.unit_price).toFixed(2) : ''}</td>
                        <td>${applicationData.total_price > 0 ? parseFloat(applicationData.total_price).toFixed(2) : ''}</td>
                        <td>${specialData.quantity > 0 ? specialData.quantity : ''}</td>
                        <td>${specialData.unit_price > 0 ? parseFloat(specialData.unit_price).toFixed(2) : ''}</td>
                        <td>${specialData.total_price > 0 ? parseFloat(specialData.total_price).toFixed(2) : ''}</td>
                    </tr>
                `;
                
                productNumber++;
            });
        });
        
        // Add TOTAL row - show only if there are actual totals, otherwise leave empty
        tableHtml += `
            <tr class="table-totals">
                <td><strong>TOTAL</strong></td>
                <td colspan="2"></td>
                <td><strong>${tableEntreeTotal > 0 ? tableEntreeTotal.toFixed(2) : ''}</strong></td>
                <td colspan="2"></td>
                <td><strong>${tableElevesTotal > 0 ? tableElevesTotal.toFixed(2) : ''}</strong></td>
                <td colspan="2"></td>
                <td><strong>${tableApplicationTotal > 0 ? tableApplicationTotal.toFixed(2) : ''}</strong></td>
                <td colspan="2"></td>
                <td><strong>${tableSpecialTotal > 0 ? tableSpecialTotal.toFixed(2) : ''}</strong></td>
            </tr>
        `;
        
        tableHtml += `
                    </tbody>
                </table>
            </div>
        `;
        
        return [tableHtml, productNumber];
    }
});
</script>
@endsection