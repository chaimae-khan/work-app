@extends('dashboard.index')

@section('dashboard')
<script>
    var csrf_token = "{{ csrf_token() }}";
    var lowStockUrl = "{{ url('stock/low-stock') }}";
    var alertCountUrl = "{{ url('stock/alert-count') }}";
    var getSubcategories_url = "{{ url('stock/subcategories') }}";
    var GetCategorieByClass = "{{ url('stock/categories-by-class') }}";
    var searchProductNames_url = "{{ url('stock/search-product-names') }}";
</script>

<div class="content-page">
    <div class="content">
        <div class="container-fluid">
            <div class="py-3 d-flex align-items-sm-center flex-sm-row flex-column">
                <div class="flex-grow-1">
                    <h4 class="fs-18 fw-semibold m-0">Produits en Stock Faible</h4>
                </div>
                
                <div class="text-end">
                    <ol class="breadcrumb m-0 py-0">
                        <li class="breadcrumb-item"><a href="javascript: void(0);">Applications</a></li>
                        <li class="breadcrumb-item"><a href="{{ url('stock') }}">Stock</a></li>
                        <li class="breadcrumb-item active">Stock Faible</li>
                    </ol>
                </div>
            </div>

            <div class="row">
                <div class="col-12">
                    <div class="card">
                        <div class="card-body">
                            <div class="alert alert-warning alert-dismissible fade" role="alert" id="stock-alert" style="display: none;">
                                <strong><i class="fa-solid fa-triangle-exclamation me-2"></i>Attention!</strong> 
                                <span id="alert-count">0</span> produit(s) avec un stock faible ou épuisé.
                                <div id="product-names" class="mt-2"></div>
                                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                            </div>

                            <!-- Filter Section -->
                            <div class="row mb-3">
                                <div class="col-md-3">
                                    <label for="filter_class" class="form-label">Classe</label>
                                    <select class="form-select" id="filter_class" name="filter_class">
                                        <option value="">Toutes les classes</option>
                                        @foreach($class as $cl)
                                            <option value="{{ $cl->classe }}">{{ $cl->classe }}</option>
                                        @endforeach
                                    </select>
                                </div>
                                
                                <div class="col-md-3">
                                    <label for="filter_categorie" class="form-label">Catégorie</label>
                                    <select class="form-select" id="filter_categorie" name="filter_categorie">
                                        <option value="">Toutes les catégories</option>
                                    </select>
                                </div>
                                
                                <div class="col-md-3">
                                    <label for="filter_subcategorie" class="form-label">Famille</label>
                                    <select class="form-select" id="filter_subcategorie" name="filter_subcategorie">
                                        <option value="">Toutes les familles</option>
                                    </select>
                                </div>
                                
                                <div class="col-md-3">
                                    <label for="filter_designation" class="form-label">Désignation</label>
                                    <div class="position-relative">
                                        <input type="text" class="form-control" id="filter_designation" 
                                               placeholder="Rechercher un produit...">
                                        <div id="designation_suggestions" class="list-group position-absolute w-100" 
                                             style="z-index: 1000; display: none; max-height: 200px; overflow-y: auto;">
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <div class="row mb-3">
                                <div class="col-12">
                                    <button type="button" class="btn btn-secondary" id="btn_reset_filter">
                                        <i class="fa-solid fa-filter-circle-xmark me-1"></i> Réinitialiser les filtres
                                    </button>
                                </div>
                            </div>
                                                    
                            <div class="table-responsive">
                                <table class="table datatable TableLowStock">
                                    <thead>
                                        <tr>
                                            <th>Code article</th>
                                            <th>Nom du Produit</th>
                                            <th>Unité</th>
                                            <th>Catégorie</th>
                                            <th>Famille</th>
                                            <th>Emplacement</th>
                                            <th>Stock Actuel</th>
                                            <th>Seuil</th>
                                            <th>Déficit</th>
                                            <th>Prix d'achat</th>
                                            <th>TVA</th>
                                            <th>Statut</th>
                                            <th>Photo</th>
                                            <th>Date de réception</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<style>
.TableLowStock tr.out-of-stock-row {
    background-color: rgba(var(--bs-dark-rgb), 0.15) !important;
}

.TableLowStock tr.low-stock-row {
    background-color: rgba(var(--bs-danger-rgb), 0.15) !important;
}
</style>

<script>
$(document).ready(function () {
    // Initialize filters
    initializeLowStockFilters();

    function initializeLowStockFilters() {
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
            
            $('.TableLowStock').DataTable().ajax.reload();
        });
        
        // Category filter change
        $('#filter_categorie').on('change', function() {
            var categoryId = $(this).val();
            
            $('#filter_subcategorie').empty().append('<option value="">Toutes les familles</option>');
            
            if (categoryId) {
                loadFilterSubcategories(categoryId);
            }
            
            $('.TableLowStock').DataTable().ajax.reload();
        });
        
        // Subcategory filter change
        $('#filter_subcategorie').on('change', function() {
            $('.TableLowStock').DataTable().ajax.reload();
        });
        
        // Designation autocomplete
        let designationTimeout;
        $('#filter_designation').on('keyup', function() {
            clearTimeout(designationTimeout);
            const query = $(this).val();
            
            if (query.length < 2) {
                $('#designation_suggestions').hide().empty();
                if (query.length === 0) {
                    $('.TableLowStock').DataTable().ajax.reload();
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
            $('.TableLowStock').DataTable().ajax.reload();
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
            
            $('.TableLowStock').DataTable().ajax.reload();
        });
    }

    // Load all categories
    function loadAllCategories() {
        var categorySelect = $('#filter_categorie');
        
        $.ajax({
            type: "GET",
            url: lowStockUrl.replace('/low-stock', '') + '/categories',
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

    // Check low stock count
    function checkAlertCount() {
        $.ajax({
            type: "GET",
            url: alertCountUrl,
            dataType: "json",
            success: function (response) {
                if (response.status == 200) {
                    if (response.count > 0) {
                        $('#alert-count').text(response.count);
                        
                        if (response.products && response.products.length > 0) {
                            var productList = '<ul class="mb-0 ps-3">';
                            response.products.forEach(function(product) {
                                productList += '<li>' + product + '</li>';
                            });
                            productList += '</ul>';
                            $('#product-names').html(productList);
                        }
                        
                        $('#stock-alert').addClass('show').show();
                    }
                }
            }
        });
    }

    checkAlertCount();

    // Initialize DataTable
    var tableLowStock = $('.TableLowStock').DataTable({
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
                extend: 'excelHtml5',
                text: 'Excel',
                exportOptions: {
                    columns: ':visible'
                }
            },
            {
                extend: 'pdfHtml5',
                text: 'PDF',
                exportOptions: {
                    columns: ':visible'
                }
            }
        ],
        processing: true,
        serverSide: true,
        ajax: {
            url: lowStockUrl,
            data: function(d) {
                // Add filter parameters
                d.filter_class = $('#filter_class').val();
                d.filter_categorie = $('#filter_categorie').val();
                d.filter_subcategorie = $('#filter_subcategorie').val();
                d.filter_designation = $('#filter_designation').val();
            },
            error: function(xhr, error, thrown) {
                console.log('DataTables error: ' + error + ' ' + thrown);
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
            { data: 'seuil', name: 'p.seuil' },
            { data: 'deficit', name: 'deficit' },
            { data: 'price_achat', name: 'p.price_achat' },
            { data: 'tva_value', name: 't.value' },
            { 
                data: 'stock_status', 
                name: 'stock_status', 
                orderable: false, 
                searchable: false 
            },
            { 
                data: 'photo_display', 
                name: 'photo_display', 
                orderable: false, 
                searchable: false 
            },
            { data: 'created_at', name: 'p.created_at' }
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
            if (data.quantite == 0) {
                $(row).addClass('out-of-stock-row');
            } else {
                $(row).addClass('low-stock-row');
            }
        }
    });
});
</script>
@endsection