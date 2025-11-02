$(document).ready(function () {
    
    // Function to check and display alert count
    function checkAlertCount() {
        $.ajax({
            type: "GET",
            url: alertCountUrl,
            dataType: "json",
            success: function (response) {
                if (response.status == 200) {
                    if (response.count > 0) {
                        $('#alert-count').text(response.count);
                        
                        // Display product names if available
                        if (response.products && response.products.length > 0) {
                            var productList = '<br><strong>Produits:</strong><br>';
                            response.products.forEach(function(product) {
                                productList += '• ' + product + '<br>';
                            });
                            $('#product-names').html(productList);
                        }
                        
                        $('#stock-alert').addClass('show').show();
                    } else {
                        $('#stock-alert').removeClass('show').hide();
                    }
                }
            },
            error: function() {
                console.log("Erreur lors de la récupération du nombre d'alertes de stock");
            }
        });
    }

    // Initial check for alerts
    checkAlertCount();

    // Initialize DataTable
    // Initialize filters
initializeStockFilters();

function initializeStockFilters() {
    // Class filter change
    $('#filter_class').on('change', function() {
        var className = $(this).val();
        var categorySelect = $('#filter_categorie');
        var subcategorySelect = $('#filter_subcategorie');
        
        // Reset dependent dropdowns
        categorySelect.empty().append('<option value="">Toutes les catégories</option>');
        subcategorySelect.empty().append('<option value="">Toutes les familles</option>');
        
        if (className) {
            loadFilterCategoriesByClass(className);
        } else {
            loadAllCategories();
        }
        
        $('.TableStock').DataTable().ajax.reload();
    });
    
    // Category filter change
    $('#filter_categorie').on('change', function() {
        var categoryId = $(this).val();
        
        $('#filter_subcategorie').empty().append('<option value="">Toutes les familles</option>');
        
        if (categoryId) {
            loadFilterSubcategories(categoryId);
        }
        
        $('.TableStock').DataTable().ajax.reload();
    });
    
    // Subcategory filter change
    $('#filter_subcategorie').on('change', function() {
        $('.TableStock').DataTable().ajax.reload();
    });
    
    // Designation autocomplete
    let designationTimeout;
    $('#filter_designation').on('keyup', function() {
        clearTimeout(designationTimeout);
        const query = $(this).val();
        
        if (query.length < 2) {
            $('#designation_suggestions').hide().empty();
            if (query.length === 0) {
                $('.TableStock').DataTable().ajax.reload();
            }
            return;
        }
        
        designationTimeout = setTimeout(function() {
            $.ajax({
                url: searchProductNames_url,
                type: 'GET',
                data: { query: query },
                success: function(response) {
                    if (response.status === 200 && response.products.length > 0) {
                        let suggestions = '';
                        $.each(response.products, function(key, product) {
                            suggestions += '<a href="#" class="list-group-item list-group-item-action designation-item" data-id="' + product.id + '" data-name="' + product.name + '">' + product.name + '</a>';
                        });
                        $('#designation_suggestions').html(suggestions).show();
                    } else {
                        $('#designation_suggestions').hide().empty();
                    }
                }
            });
        }, 300);
    });
    
    // Click on suggestion
    $(document).on('click', '.designation-item', function(e) {
        e.preventDefault();
        const name = $(this).data('name');
        $('#filter_designation').val(name);
        $('#designation_suggestions').hide().empty();
        $('.TableStock').DataTable().ajax.reload();
    });
    
    // Hide suggestions when clicking outside
    $(document).on('click', function(e) {
        if (!$(e.target).closest('#filter_designation, #designation_suggestions').length) {
            $('#designation_suggestions').hide();
        }
    });
    
    // Reset filters button
    $('#btn_reset_filter').on('click', function() {
        $('#filter_class').val('');
        $('#filter_designation').val('');
        $('#designation_suggestions').hide().empty();
        
        $('#filter_categorie').empty().append('<option value="">Toutes les catégories</option>');
        loadAllCategories();
        
        $('#filter_subcategorie').empty().append('<option value="">Toutes les familles</option>');
        
        $('.TableStock').DataTable().ajax.reload();
    });
}

// Load all categories
function loadAllCategories() {
    var categorySelect = $('#filter_categorie');
    
    $.ajax({
        type: "GET",
        url: stockUrl + '/categories',
        dataType: "json",
        success: function(response) {
            if (response.status === 200) {
                $.each(response.categories, function(index, category) {
                    categorySelect.append('<option value="' + category.id + '">' + category.name + '</option>');
                });
            }
        }
    });
}

// Load categories by class for filter
function loadFilterCategoriesByClass(className) {
    var categorySelect = $('#filter_categorie');
    
    $.ajax({
        type: "GET",
        url: GetCategorieByClass,
        data: { class: className },
        dataType: "json",
        success: function (response) {
            if (response.status === 200) {
                $.each(response.data, function(index, item) {
                    categorySelect.append('<option value="' + item.id + '">' + item.name + '</option>');
                });
            }
        }
    });
}

// Load subcategories for filter
function loadFilterSubcategories(categoryId) {
    var subcategorySelect = $('#filter_subcategorie');
    
    $.ajax({
        url: getSubcategories_url + "/" + categoryId,
        type: 'GET',
        dataType: 'json',
        success: function(response) {
            if (response.status === 200 && response.subcategories.length > 0) {
                $.each(response.subcategories, function(key, subcategory) {
                    subcategorySelect.append(
                        `<option value="${subcategory.id}">${subcategory.name}</option>`
                    );
                });
            }
        }
    });
}
    if ($.fn.DataTable.isDataTable('.TableStock')) {
        $('.TableStock').DataTable().destroy();
    }
    
    var tableStock = $('.TableStock').DataTable({
        dom: 'Bfrtip',
        buttons: [
            {
                extend: 'copyHtml5',
                text: 'Copier',
                exportOptions: {
                    columns: ':visible'
                }
            },
            {
                text: 'Exporter Excel',
                className: 'btn-export-all',
                action: function (e, dt, button, config) {
                    // Get visible columns
                    var visibleColumnsIndices = [];
                    dt.columns().every(function (index) {
                        if (dt.column(index).visible()) {
                            visibleColumnsIndices.push(index);
                        }
                    });
                    
                    // Redirect to server-side export with visible columns as parameter
                    window.location.href = stockExportExcelUrl + '?columns=' + visibleColumnsIndices.join(',');
                }
            },
            {
                text: 'Exporter PDF',
                className: 'btn-export-all',
                action: function (e, dt, button, config) {
                    // Get visible columns
                    var visibleColumnsIndices = [];
                    dt.columns().every(function (index) {
                        if (dt.column(index).visible()) {
                            visibleColumnsIndices.push(index);
                        }
                    });
                    
                    // Redirect to server-side export with visible columns as parameter
                    window.location.href = stockExportPdfUrl + '?columns=' + visibleColumnsIndices.join(',');
                }
            },
            {
                extend: 'colvis',
                text: 'Colonnes'
            }
        ],
        processing: true,
        serverSide: true,
        ajax: {
            url: stockUrl,
            data: function(d) {
        // Add filter parameters
        d.filter_class = $('#filter_class').val();
        d.filter_categorie = $('#filter_categorie').val();
        d.filter_subcategorie = $('#filter_subcategorie').val();
        d.filter_designation = $('#filter_designation').val();
    },
            dataSrc: function (json) {
                if (json.data.length === 0) {
                    $('.paging_full_numbers').css('display', 'none');
                }
                return json.data;
            },
            error: function(xhr, error, thrown) {
                console.log('DataTables error: ' + error + ' ' + thrown);
                console.log(xhr);
            }
        },
        columns: [
            { data: 'code_article', name: 'p.code_article' },
            { data: 'name', name: 'p.name' },
            { data: 'unite_name', name: 'u.name' },
            { data: 'categorie', name: 'c.name' },
            { data: 'famille', name: 'sc.name' },
            { data: 'emplacement', name: 'p.emplacement' },
            { data: 'quantite', name: 's.quantite' },
            { data: 'price_achat', name: 'p.price_achat' },
            { data: 'seuil', name: 'p.seuil' },
            // { data: 'code_barre', name: 'p.code_barre' },
            // { 
            //     data: 'photo_display', 
            //     name: 'photo_display', 
            //     orderable: false, 
            //     searchable: false 
            // },
            { data: 'date_expiration', name: 'p.date_expiration' },
            { data: 'created_at', name: 'p.created_at' },
            { 
                data: 'status', 
                name: 'status', 
                orderable: false, 
                searchable: false
            }
        ],
        language: {
            "sInfo": "",
            "sInfoEmpty": "Affichage de l'élément 0 à 0 sur 0 élément",
            "sInfoFiltered": "(filtré à partir de _MAX_ éléments au total)",
            "sLengthMenu": "Afficher _MENU_ éléments",
            "sLoadingRecords": "Chargement...",
            "sProcessing": "Traitement...",
            "sSearch": "Rechercher :",
            "sZeroRecords": "Aucun élément correspondant trouvé",
            "oPaginate": {
                "sFirst": "Premier",
                "sLast": "Dernier",
                "sNext": "Suivant",
                "sPrevious": "Précédent"
            }
        },
        createdRow: function(row, data, dataIndex) {
            if (parseInt(data.quantite) <= parseInt(data.seuil)) {
                $(row).addClass('bg-danger-subtle text-danger');
                
                $(row).attr('data-bs-toggle', 'tooltip');
                $(row).attr('data-bs-placement', 'top');
                $(row).attr('title', 'Attention : la quantité de ce produit est presque épuisée.');
            }
        },
        drawCallback: function() {
            var tooltipTriggerList = [].slice.call(document.querySelectorAll('[data-bs-toggle="tooltip"]'));
            tooltipTriggerList.map(function (tooltipTriggerEl) {
                return new bootstrap.Tooltip(tooltipTriggerEl);
            });
        }
    });
    
    // Add custom styling to the export buttons
    $('.btn-export-all').addClass('btn-success');
});