@extends('dashboard.index')

@section('dashboard')

<script src="{{ asset('js/consumption/script.js') }}"></script>
<script>
    var getConsumptionData_url = "{{ url('getConsumptionData') }}";
    var exportPDF_url = "{{ url('exportPDF') }}";
    var csrf_token = "{{ csrf_token() }}";
</script>

<style>
    .table-responsive {
        overflow-x: auto;
    }
    .fa-spinner {
        animation: spin 1s linear infinite;
    }
    @keyframes spin {
        0% { transform: rotate(0deg); }
        100% { transform: rotate(360deg); }
    }
    @media print {
        body * {
            visibility: hidden;
        }
        #printSection, #printSection * {
            visibility: visible;
        }
        #printSection {
            position: absolute;
            left: 0;
            top: 0;
            width: 100%;
        }
        .no-print {
            display: none !important;
        }
    }
    .category-header {
        background-color: #f8f9fa;
        font-weight: bold;
    }
    .table-totals {
        background-color: #e9ecef;
        font-weight: bold;
    }
    /* Add styles for the TVA columns */
    .tva-column {
        background-color: #f0f8ff;
    }
    .tva-total {
        background-color: #e6f2ff;
        font-weight: bold;
    }
    /* Make the modal wider to accommodate the additional columns */
    @media (min-width: 992px) {
        .modal-lg {
            max-width: 95%;
        }
    }
    /* Improve table styling for better readability with more columns */
    .table thead th {
        vertical-align: middle;
        text-align: center;
        font-size: 0.9rem;
        padding: 0.5rem;
    }
    .table-bordered td {
        padding: 0.5rem;
        font-size: 0.9rem;
    }
    .table-bordered td:nth-child(n+2) {
        text-align: right;
    }
    .table-bordered td:first-child {
        text-align: left;
    }
    
    /* Menu attributes styling */
    .menu-attributes {
        background-color: #f8f9fa;
        padding: 12px;
        border-radius: 6px;
        margin-bottom: 15px;
        border-left: 4px solid #007bff;
        box-shadow: 0 2px 4px rgba(0,0,0,0.1);
    }
    
    .menu-attributes strong {
        color: #495057;
        font-size: 0.9rem;
    }
    
    .menu-attributes span {
        color: #6c757d;
        font-size: 0.85rem;
        display: block;
        margin-top: 2px;
    }
    
    .menu-attributes .col-md-3 {
        margin-bottom: 8px;
    }
</style>

<div class="content-page">
    <div class="content">
        <div class="container-fluid">
            <div class="py-3 d-flex align-items-sm-center flex-sm-row flex-column">
                <div class="flex-grow-1">
                    <h4 class="fs-18 fw-semibold m-0">Feuille de Consommation Journalière</h4>
                </div>
                
                <div class="text-end">
                    <ol class="breadcrumb m-0 py-0">
                        <li class="breadcrumb-item"><a href="javascript: void(0);">Applications</a></li>
                        <li class="breadcrumb-item active">Consommation</li>
                    </ol>
                </div>
            </div>

            <div class="row">
                <div class="col-12">
                    <div class="card">
                        <div class="card-body">
                            <div class="row mb-4">
                                <div class="col-md-3">
                                    <label for="consumption_date" class="form-label">Date</label>
                                    <input type="date" class="form-control" id="consumption_date" value="{{ date('Y-m-d') }}">
                                </div>
                                <div class="col-md-3">
                                    <label for="type_operation" class="form-label">Type d'opération</label>
                                    <select class="form-select" id="type_operation">
                                        <option value="sortie">Sortie </option>
                                        <option value="entree">Entrée</option>
                                    </select>
                                </div>
                                <div class="col-md-3" id="type_commande_container">
                                    <label for="type_commande" class="form-label">Type de commande</label>
                                    <select class="form-select" id="type_commande">
                                        <option value="Alimentaire">Alimentaire</option>
                                        <option value="Non Alimentaire">Non Alimentaire</option>
                                        <option value="Fournitures et matériels">Fournitures et matériels</option>
                                    </select>
                                </div>
                                <div class="col-md-3" id="type_menu_container">
                                    <label for="type_menu" class="form-label">Type de menu</label>
                                    <select class="form-select" id="type_menu">
                                        <option value="">Tous les menus</option>
                                        <option value="Menu eleves">Menu standard</option>
                                        <option value="Menu specials">Menu spéciaux</option>
                                        <option value="Menu d'application">Menu d'application</option>
                                    </select>
                                </div>
                            </div>
                            
                            <div class="row mb-4">
                                <div class="col-md-12">
                                    <button class="btn btn-primary" id="btnSearch">
                                        <i class="fa fa-search"></i> Rechercher
                                    </button>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Modal -->
<div class="modal fade" id="ModalConsumption" tabindex="-1" aria-labelledby="ModalConsumption" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="ModalConsumptionLabel">FEUILLE DE CONSOMMATION JOURNALIERE</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Fermer"></button>
            </div>
            <div class="modal-body">
                <div id="printSection" class="table-responsive">
                    <div id="consumptionTableData"></div>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary no-print" data-bs-dismiss="modal">Fermer</button>
                <!-- <button type="button" class="btn btn-success no-print" id="btnPrint">
                    <i class="fa fa-print"></i> Imprimer
                </button> -->
                <button type="button" class="btn btn-primary no-print" id="btnExportPDF">
                    <i class="fa fa-file-pdf"></i> Exporter PDF
                </button>
            </div>
        </div>
    </div>
</div>
<script>
 $(document).ready(function() {
  // Helper function for menu display - defined at the document level so it's available everywhere
  window.formatMenuName = function(menuType) {
    if (menuType === 'Menu eleves') {
      return 'Menu standard';
    }
    return menuType;
  };

  // NEW: Helper function to display menu attributes
  function displayMenuAttributes(menu) {
    let menuAttributesHtml = '';
    
    // Check if any menu attributes exist
    if (menu.entree || menu.plat_principal || menu.accompagnement || menu.dessert) {
        menuAttributesHtml = `
            <div class="menu-attributes mb-3">
                <div class="row">
        `;
        
        if (menu.entree) {
            menuAttributesHtml += `
                <div class="col-md-3">
                    <strong>Entrée:</strong>
                    <span>${menu.entree}</span>
                </div>
            `;
        }
        
        if (menu.plat_principal) {
            menuAttributesHtml += `
                <div class="col-md-3">
                    <strong>Plat Principal:</strong>
                    <span>${menu.plat_principal}</span>
                </div>
            `;
        }
        
        if (menu.accompagnement) {
            menuAttributesHtml += `
                <div class="col-md-3">
                    <strong>Accompagnement:</strong>
                    <span>${menu.accompagnement}</span>
                </div>
            `;
        }
        
        if (menu.dessert) {
            menuAttributesHtml += `
                <div class="col-md-3">
                    <strong>Dessert:</strong>
                    <span>${menu.dessert}</span>
                </div>
            `;
        }
        
        menuAttributesHtml += `
                </div>
            </div>
        `;
    }
    
    return menuAttributesHtml;
  }

  $('#type_operation').on('change', function() {
    const isEntree = $(this).val() === 'entree';
    
    // Existing code for showing/hiding filters
    if (isEntree) {
        $('#type_commande_container').hide();
        $('#type_menu_container').hide();
        $('#type_commande').val('');
        $('#type_menu').val('');
        
        // Hide print and export buttons in the modal
        $('#btnPrint, #btnExportPDF').hide();
    } else {
        $('#type_commande_container').show();
        if ($('#type_commande').val() === 'Fournitures et matériels' || $('#type_commande').val() === 'Non Alimentaire') {
            $('#type_menu_container').hide();
        } else {
            $('#type_menu_container').show();
        }
        
        // Show print and export buttons in the modal
        $('#btnPrint, #btnExportPDF').show();
    }
  });

  $('#type_commande').on('change', function() {
    if ($('#type_operation').val() === 'sortie') {
        if ($(this).val() === 'Fournitures et matériels' || $(this).val() === 'Non Alimentaire') {
            $('#type_menu_container').hide();
            $('#type_menu').val('');
        } else {
            $('#type_menu_container').show();
        }
    }
  });

  // Initialize datepicker to current date
  const today = new Date().toISOString().split('T')[0];
  $('#consumption_date').val(today);

  $('#btnSearch').on('click', function(e) {
    e.preventDefault();
    
    const date = $('#consumption_date').val();
    const typeOperation = $('#type_operation').val();
    const typeCommande = $('#type_commande').val();
    const typeMenu = $('#type_menu').val();
    
    if (!date) {
        new AWN().alert('Veuillez sélectionner une date');
        return;
    }
    
    console.log('Sending request with:', {
        date: date,
        type_operation: typeOperation,
        type_commande: typeCommande,
        type_menu: typeMenu
    });
    
    // Before sending the AJAX request, set the visibility of buttons
    const isEntree = typeOperation === 'entree';
    if (isEntree) {
        $('#btnPrint, #btnExportPDF').hide();
    } else {
        $('#btnPrint, #btnExportPDF').show();
    }
    
    $.ajax({
        type: "GET",
        url: getConsumptionData_url,
        data: {
            date: date,
            type_operation: typeOperation,
            type_commande: typeCommande,
            type_menu: typeMenu
        },
        dataType: "json",
        beforeSend: function() {
            $('#btnSearch').prop('disabled', true);
            $('#btnSearch').html('<i class="fa fa-spinner fa-spin"></i> Chargement...');
        },
        success: function(response) {
            console.log('Full response:', response);
            console.log('Response status:', response.status);
            
            if (response.status == 200) {
                console.log('Data received:', response.data);
                console.log('Data structure:', JSON.stringify(response.data, null, 2));
                
                displayConsumptionData(response.data);
                $('#ModalConsumption').modal('show');
            } else {
                console.log('Error response:', response);
                new AWN().warning(response.message || 'Aucune donnée trouvée');
            }
        },
        error: function(xhr, status, error) {
            console.error("Error:", error);
            console.error("XHR:", xhr);
            console.error("Status:", status);
            console.error("Response text:", xhr.responseText);
            new AWN().alert("Erreur lors de la récupération des données");
        },
        complete: function() {
            $('#btnSearch').prop('disabled', false);
            $('#btnSearch').html('<i class="fa fa-search"></i> Rechercher');
        }
    });
  });
    
  // PDF Export functionality
  $('#btnExportPDF').on('click', function(e) {
    e.preventDefault();
    
    const date = $('#consumption_date').val();
    const typeOperation = $('#type_operation').val();
    const typeCommande = $('#type_commande').val();
    const typeMenu = $('#type_menu').val();
    
    if (!date) {
        new AWN().alert('Veuillez sélectionner une date');
        return;
    }
    
    // Create a form and submit it for PDF download
    const form = document.createElement('form');
    form.method = 'GET';
    form.action = exportPDF_url;
    
    // Create hidden fields
    const fields = {
        date: date,
        type_operation: typeOperation,
        type_commande: typeCommande,
        type_menu: typeMenu
    };
    
    for (const [key, value] of Object.entries(fields)) {
        const hidden = document.createElement('input');
        hidden.type = 'hidden';
        hidden.name = key;
        hidden.value = value;
        form.appendChild(hidden);
    }
    
    document.body.appendChild(form);
    form.submit();
    document.body.removeChild(form);
  });

  function displayConsumptionData(data) {
    let html = '';
    
    if (data.type_operation === 'entree') {
        // For entree (achat)
        html = `
            <h4 class="text-center">ENTRÉES</h4>
            <table class="table table-bordered">
                <thead>
                    <tr>
                        <th>Désignation des Articles</th>
                        <th>Qté</th>
                        <th>P.U</th>
                        <th>P.T</th>
                    </tr>
                </thead>
                <tbody>
        `;
        
        // Group products by category
        let productsByCategory = {};
        
        data.consumptions.forEach((achat) => {
            achat.products.forEach((product) => {
                const categoryName = product.category_name || 'Non catégorisé';
                
                if (!productsByCategory[categoryName]) {
                    productsByCategory[categoryName] = [];
                }
                
                productsByCategory[categoryName].push(product);
            });
        });
        
        // Sort categories and display products
        const sortedCategories = Object.keys(productsByCategory).sort();
        
        sortedCategories.forEach(category => {
            // Add category header
            html += `
                <tr class="category-header">
                    <td colspan="4"><strong>${category.toUpperCase()}</strong></td>
                </tr>
            `;
            
            // Sort products by name within category
            const productsInCategory = productsByCategory[category].sort((a, b) => 
                a.name.localeCompare(b.name)
            );
            
            // Add products in this category
            productsInCategory.forEach((product, index) => {
                html += `
                    <tr>
                        <td>${index + 1}- ${product.name}</td>
                        <td>${product.quantity}</td>
                        <td>${parseFloat(product.unit_price || 0).toFixed(2)}</td>
                        <td>${parseFloat(product.total_price || 0).toFixed(2)}</td>
                    </tr>
                `;
            });
        });
        
        html += `
                    <tr>
                        <td colspan="3" class="text-end"><strong>Total:</strong></td>
                        <td><strong>${parseFloat(data.grand_totals.total_cost || 0).toFixed(2)}</strong></td>
                    </tr>
                </tbody>
            </table>
            <div class="mt-4">
                <p>Prix de Revient de la journée : <strong>${parseFloat(data.grand_totals.total_cost || 0).toFixed(2)}</strong></p>
            </div>
        `;
    } else {
        // For sortie (vente)
        if (data.consumptions[0].type_commande === 'Fournitures et matériels' || 
            data.consumptions[0].type_commande === 'Non Alimentaire') {
            
            html += `
                <h4 class="text-center">SORTIES - ${data.consumptions[0].type_commande}</h4>
                <table class="table table-bordered">
                    <thead>
                        <tr>
                            <th>Désignation des Articles</th>
                            <th>Qté</th>
                            <th>P.U</th>
                            <th>P.T</th>
                        </tr>
                    </thead>
                    <tbody>
            `;
            
            // Group all products by category across all consumptions
            let productsByCategory = {};
            
            data.consumptions.forEach((consumption) => {
                consumption.products.forEach((product) => {
                    const categoryName = product.category_name || 'Non catégorisé';
                    
                    if (!productsByCategory[categoryName]) {
                        productsByCategory[categoryName] = [];
                    }
                    
                    productsByCategory[categoryName].push(product);
                });
            });
            
            if (Object.keys(productsByCategory).length > 0) {
                // Sort categories and display products
                const sortedCategories = Object.keys(productsByCategory).sort();
                
                sortedCategories.forEach(category => {
                    // Add category header
                    html += `
                        <tr class="category-header">
                            <td colspan="4"><strong>${category.toUpperCase()}</strong></td>
                        </tr>
                    `;
                    
                    // Sort products by name within category
                    const productsInCategory = productsByCategory[category].sort((a, b) => 
                        a.name.localeCompare(b.name)
                    );
                    
                    // Add products in this category
                    productsInCategory.forEach((product, index) => {
                        html += `
                            <tr>
                                <td>${index + 1}- ${product.name}</td>
                                <td>${product.quantity}</td>
                                <td>${parseFloat(product.unit_price || 0).toFixed(2)}</td>
                                <td>${parseFloat(product.total_price || 0).toFixed(2)}</td>
                            </tr>
                        `;
                    });
                });
            }
            
            html += `
                        <tr>
                            <td colspan="3" class="text-end"><strong>Total:</strong></td>
                            <td><strong>${parseFloat(data.grand_totals.total_cost || 0).toFixed(2)}</strong></td>
                        </tr>
                    </tbody>
                </table>
            `;
            
            // Price summary for Fournitures et matériels or Non Alimentaire
            html += `
                <div class="mt-4">
                    <p>Prix de Revient de la journée : <strong>${parseFloat(data.grand_totals.total_cost || 0).toFixed(2)}</strong></p>
                </div>
            `;
        } else {
            // Regular menu logic (Alimentaire)
            data.consumptions.forEach((menu) => {
                // Use formatted menu name
                const displayMenuName = window.formatMenuName(menu.type_menu);
                
                html += `
                    <h4 class="text-center">${displayMenuName}</h4>
                `;
                
                // Add menu attributes if they exist
                html += displayMenuAttributes(menu);
                
                html += `
                    <table class="table table-bordered">
                        <thead>
                            <tr>
                                <th>Désignation des Articles</th>
                                <th>Qté</th>
                                <th>P.U</th>
                                <th>P.T</th>
                            </tr>
                        </thead>
                        <tbody>
                `;
                
                // Group products by category
                let productsByCategory = {};
                
                menu.products.forEach((product) => {
                    const categoryName = product.category_name || 'Non catégorisé';
                    
                    if (!productsByCategory[categoryName]) {
                        productsByCategory[categoryName] = [];
                    }
                    
                    productsByCategory[categoryName].push(product);
                });
                
                // Sort categories and display products
                const sortedCategories = Object.keys(productsByCategory).sort();
                
                sortedCategories.forEach(category => {
                    // Add category header
                    html += `
                        <tr class="category-header">
                            <td colspan="4"><strong>${category.toUpperCase()}</strong></td>
                        </tr>
                    `;
                    
                    // Sort products by name within category
                    const productsInCategory = productsByCategory[category].sort((a, b) => 
                        a.name.localeCompare(b.name)
                    );
                    
                    // Add products in this category
                    productsInCategory.forEach((product, index) => {
                        html += `
                            <tr>
                                <td>${index + 1}- ${product.name}</td>
                                <td>${product.quantity}</td>
                                <td>${parseFloat(product.unit_price || 0).toFixed(2)}</td>
                                <td>${parseFloat(product.total_price || 0).toFixed(2)}</td>
                            </tr>
                        `;
                    });
                });
                
                html += `
                            <tr>
                                <td colspan="3" class="text-end"><strong>Sous-total:</strong></td>
                                <td><strong>${parseFloat(menu.total_cost || 0).toFixed(2)}</strong></td>
                            </tr>
                        </tbody>
                    </table>
                `;
            });
            
            // Add effectif section for sortie only (excluding Fournitures et matériels and Non Alimentaire)
            if (data.grand_totals && data.grand_totals.total_people > 0) {
                // Get all unique menu types to create the subcolumns
                let menuTypes = [];
                data.consumptions.forEach(menu => {
                    const menuType = menu.type_menu || 'Sans Menu';
                    if (!menuTypes.includes(menuType)) {
                        menuTypes.push(menuType);
                    }
                });
                
                html += `
                    <h4 class="text-center mt-4">EFFECTIF</h4>
                    <table class="table table-bordered">
                        <thead>
                            <tr>
                                <th></th>
                                <th>Petit déjeuner</th>
                                <th colspan="${menuTypes.length}">Lunch</th>
                                <th>Dîner</th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr>
                                <td>Standard</td>
                                <td></td>
                                ${menuTypes.map(menu => {
                                    const menuConsumption = data.consumptions.find(c => (c.type_menu || 'Sans Menu') === menu);
                                    return `<td>${menuConsumption?.eleves || 0}</td>`;
                                }).join('')}
                                <td></td>
                            </tr>
                            <tr>
                                <td>Personnel</td>
                                <td></td>
                                ${menuTypes.map(menu => {
                                    const menuConsumption = data.consumptions.find(c => (c.type_menu || 'Sans Menu') === menu);
                                    return `<td>${menuConsumption?.personnel || 0}</td>`;
                                }).join('')}
                                <td></td>
                            </tr>
                            <tr>
                                <td>Invités</td>
                                <td></td>
                                ${menuTypes.map(menu => {
                                    const menuConsumption = data.consumptions.find(c => (c.type_menu || 'Sans Menu') === menu);
                                    return `<td>${menuConsumption?.invites || 0}</td>`;
                                }).join('')}
                                <td></td>
                            </tr>
                            <tr>
                                <td>Divers</td>
                                <td></td>
                                ${menuTypes.map(menu => {
                                    const menuConsumption = data.consumptions.find(c => (c.type_menu || 'Sans Menu') === menu);
                                    return `<td>${menuConsumption?.divers || 0}</td>`;
                                }).join('')}
                                <td></td>
                            </tr>
                        </tbody>
                    </table>
                    
                    <div class="mt-4">
                `;
                
                // Show Prix de Revient for each menu with formatted menu names
                data.consumptions.forEach((menu) => {
                    const displayMenuName = window.formatMenuName(menu.type_menu);
                    
                    html += `
                        <p>Prix de Revient de la journée ${displayMenuName} : <strong>${parseFloat(menu.total_cost || 0).toFixed(2)}</strong></p>
                    `;
                    
                    // Only show Prix Moyen if total_people is greater than 0
                    if (menu.total_people > 0) {
                        html += `
                            <p>Prix Moyen ${displayMenuName} : <strong>${(menu.total_cost / menu.total_people).toFixed(2)}</strong></p>
                        `;
                    }
                });
                
                // ===== FIXED CALCULATION FOR PRIX MOYEN GÉNÉRAL =====
                // Calculate overall average by dividing total cost by total people
                // This fixes both issues:
                // 1. It won't show for single menu (because it would be redundant)
                // 2. It uses the correct formula: sum of all costs / sum of all people
                
                let totalCost = 0;
                let totalPeople = 0;
                
                data.consumptions.forEach((menu) => {
                    totalCost += parseFloat(menu.total_cost || 0);
                    totalPeople += parseInt(menu.total_people || 0);
                });
                
                // Only show Prix Moyen Général if there are multiple menus
                if (data.consumptions.length > 1 && totalPeople > 0) {
                    const prixMoyenGeneral = totalCost / totalPeople;
                    html += `
                        <p>Prix Moyen Général : <strong>${prixMoyenGeneral.toFixed(2)}</strong></p>
                    `;
                }
                
                html += `</div>`;
            } else {
                html += `
                    <div class="mt-4">
                        <p>Prix de Revient de la journée : <strong>${parseFloat(data.grand_totals.total_cost || 0).toFixed(2)}</strong></p>
                    </div>
                `;
            }
        }
    }
    
    $('#consumptionTableData').html(html);
    
    // Add some CSS for better styling
    $('<style>')
        .text(`
            .category-header {
                background-color: #f8f9fa;
                font-weight: bold;
            }
            .table-totals {
                background-color: #e9ecef;
                font-weight: bold;
            }
            .table thead th {
                vertical-align: middle;
                text-align: center;
            }
            .table-bordered td:nth-child(n+2) {
                text-align: right;
            }
            .table-bordered td:first-child {
                text-align: left;
            }
        `)
        .appendTo('head');
  }

  // Add this event handler to catch any "Menu eleves" text that might appear
  // This is a fallback in case any are missed by our other changes
  $('#ModalConsumption').on('show.bs.modal', function () {
    setTimeout(function() {
      // Find any text nodes with "Menu eleves" and replace with "Menu standard"
      $('#ModalConsumption').find('*').contents().each(function() {
        if (this.nodeType === 3) { // Text node
          if (this.nodeValue && this.nodeValue.includes('Menu eleves')) {
            this.nodeValue = this.nodeValue.replace(/Menu eleves/g, 'Menu standard');
          }
        }
      });
    }, 100); // Short delay to ensure the modal is fully rendered
  });
});
</script>

@endsection