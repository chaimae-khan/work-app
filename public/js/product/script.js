$(document).ready(function () {
    // Dynamic script and CSS loading
    var datatablesScript = document.createElement('script');
    datatablesScript.src = 'https://cdn.datatables.net/1.11.5/js/jquery.dataTables.min.js';
    document.head.appendChild(datatablesScript);

    var datatablesCssLink = document.createElement('link');
    datatablesCssLink.rel = 'stylesheet';
    datatablesCssLink.href = 'https://cdn.datatables.net/1.11.5/css/dataTables.bootstrap5.min.css';
    document.head.appendChild(datatablesCssLink);

    // Wait for DataTables to load
    setTimeout(function() {
        initializeDataTable();
    }, 500);

    // Initialize dependent dropdowns
    initializeDropdowns();
    
    // Initialize filters
    initializeFilters();

    // DataTable Initialization
 
function initializeDataTable() {
    try {
        // Destroy existing DataTable if it exists
        if ($.fn.DataTable.isDataTable('.TableProducts')) {
            $('.TableProducts').DataTable().destroy();
        }
        
        // Initialize DataTable
        var tableProducts = $('.TableProducts').DataTable({
            processing: true,
            serverSide: true,
            ajax: {
                url: products_url,
                data: function(d) {
                    // Add filter parameters
                    d.filter_categorie = $('#filter_categorie').val();
                    d.filter_subcategorie = $('#filter_subcategorie').val();
                },
                dataSrc: function (json) {
                    if (json.data.length === 0) {
                        $('.paging_full_numbers').css('display', 'none');
                    }
                    return json.data;
                },
                error: function(xhr, error, thrown) {
                    console.error('DataTables error:', error, thrown);
                    new AWN().alert("Erreur de chargement des données", { durations: { alert: 5000 } });
                }
            },
            columns: [
                { data: 'code_article', name: 'p.code_article' },
                { data: 'name', name: 'p.name' },
                { data: 'unite', name: 'u.name' },
                { data: 'categorie', name: 'c.name' },
                { data: 'famille', name: 'sc.name' },
                { data: 'emplacement', name: 'p.emplacement' },
                { data: 'stock', name: 's.quantite' },
                { data: 'price_achat', name: 'p.price_achat' },
                // { data: 'taux_taxe', name: 't.value' },
                { data: 'seuil', name: 'p.seuil' },
                { 
                    data: 'date_expiration', 
                    name: 'p.date_expiration',
                    render: function(data) {
                        if (data) {
                            // Format date as DD/MM/YYYY
                            const date = new Date(data);
                            return date.toLocaleDateString('fr-FR');
                        } else {
                            return '<span class="text-muted">Non définie</span>';
                        }
                    }
                },
                {
                    data: 'created_at',
                    name: 'p.created_at',
                    render: function(data) {
                        if (data) {
                            // Format date and time as DD/MM/YYYY HH:MM
                            const date = new Date(data);
                            return date.toLocaleDateString('fr-FR') + ' ' + date.toLocaleTimeString('fr-FR', {hour: '2-digit', minute:'2-digit'});
                        } else {
                            return '<span class="text-muted">Non définie</span>';
                        }
                    }
                },
                { data: 'action', name: 'action', orderable: false, searchable: false }
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
            }
        });
    
     
   // Edit Product Handler
$('.TableProducts tbody').on('click', '.editProduct', function(e) {
    e.preventDefault();
    var productId = $(this).attr('data-id');
    
    // Disable edit button during loading
    $(this).prop('disabled', true);
    
    $.ajax({
        type: "GET",
        url: editProduct_url + "/" + productId,
        dataType: "json",
        success: function(response) {
            // Enable edit button
            $('.editProduct').prop('disabled', false);
            
            // Detailed logging
            console.log("Données du produit:", response);
            
            // Clear any previous dropdown options to prevent duplicates
            $('#edit_Categorie_Class').empty().append('<option value="">Sélectionner une catégorie</option>');
            $('#edit_id_subcategorie').empty().append('<option value="">Sélectionner une famille</option>');
            $('#edit_id_rayon').empty().append('<option value="">Sélectionner un rayon</option>');
            
            // Show edit modal
            $('#ModalEditProduct').modal("show");
            
            // Clear any previous validation errors
            $('.validationEditProduct').html("").removeClass('alert alert-danger');
            
            // Populate basic product information
            $('#edit_id').val(response.id);
            $('#edit_name').val(response.name);
            $('#edit_price_achat').val(response.price_achat);
            $('#edit_code_barre').val(response.code_barre);
            
            // Handle photo path - ensure it's properly captured
            if (!$('#current_photo_path').length) {
                $('<input>').attr({
                    type: 'hidden',
                    id: 'current_photo_path',
                    name: 'current_photo_path'
                }).appendTo('#FormUpdateProduct');
            }
            
            // Set the current photo path and show the image
            if (response.photo) {
                $('#current_photo_path').val(response.photo);
                $('#current_photo_container').html('<img src="/storage/' + response.photo + '" alt="Current Photo" class="img-thumbnail" style="width: 100px; height: 100px;"><p class="mt-2">Photo actuelle</p>');
                $('#current_photo_container').show();
                console.log("Photo path stored:", response.photo);
            } else {
                $('#current_photo_path').val('');
                $('#current_photo_container').hide();
                console.log("No photo path to store");
            }
            
            // Reset file input to ensure it doesn't retain previous selection
            $('#edit_photo').val('');
            $('#edit_photo_preview').hide();
            
            // Set date_expiration if exists
            if (response.date_expiration) {
                const expDate = new Date(response.date_expiration);
                const formattedDate = expDate.toISOString().split('T')[0];
                $('#edit_date_expiration').val(formattedDate);
                console.log("Setting expiration date:", formattedDate);
            } else {
                $('#edit_date_expiration').val('');
                console.log("No expiration date found");
            }
            
            // Set seuil value directly from product
            $('#edit_seuil').val(response.seuil);
            
            // Display code_article in a disabled field if you want to show it
            if ($('#edit_code_article').length) {
                $('#edit_code_article').val(response.code_article);
            }
            
            // Set local dropdown
            $('#edit_id_local').val(response.id_local);
            
            // Load rayons for the selected local
            loadRayons('#edit_id_local', '#edit_id_rayon', response.id_rayon);
            
            // Handle class and category cascading dropdowns
            if (response.class) {
                // Set class value
                $('#edit_Class_Categorie').val(response.class);
                
                // Load categories for this class, then set the category
                $.ajax({
                    type: "GET",
                    url: GetCategorieByClass,
                    data: { class: response.class },
                    dataType: "json",
                    success: function (classResponse) {
                        if(classResponse.status == 200) {
                            var categorySelect = $('#edit_Categorie_Class');
                            categorySelect.empty().append('<option value="">Sélectionner une catégorie</option>');
                            
                            $.each(classResponse.data, function(index, item) {
                                categorySelect.append('<option value="' + item.id + '">' + item.name + '</option>');
                            });
                            
                            // Set the category value after categories are loaded
                            categorySelect.val(response.id_categorie);
                            
                            // Load subcategories after category is set
                            loadSubcategories('#edit_Categorie_Class', '#edit_id_subcategorie', response.id_subcategorie);
                        }
                    },
                    error: function(xhr, status, error) {
                        console.error("Erreur de chargement des catégories:", error);
                        // Fallback: load subcategories directly if class loading fails
                        loadSubcategories('#edit_id_categorie', '#edit_id_subcategorie', response.id_subcategorie);
                    }
                });
            } else {
                // Fallback for existing products without class - use old category dropdown
                $('#edit_id_categorie').val(response.id_categorie);
                loadSubcategories('#edit_id_categorie', '#edit_id_subcategorie', response.id_subcategorie);
            }
            
            // Set TVA and Unite from product
            // if (response.id_tva) {
            //     $('#edit_id_tva').val(response.id_tva);
            // } else if (response.stock && response.stock.id_tva) {
            //     // Fallback to stock if product doesn't have it yet
            //     $('#edit_id_tva').val(response.stock.id_tva);
            // }
            
            if (response.id_unite) {
                $('#edit_id_unite').val(response.id_unite);
            } else if (response.stock && response.stock.id_unite) {
                // Fallback to stock if product doesn't have it yet
                $('#edit_id_unite').val(response.stock.id_unite);
            }
            
            // Set stock quantity if stock exists
            if (response.stock) {
                $('#edit_quantite').val(response.stock.quantite);
            } else {
                // Reset stock quantity field if no stock data
                $('#edit_quantite').val('');
            }
        },
        error: function(xhr, status, error) {
            // Enable edit button
            $('.editProduct').prop('disabled', false);
            
            // Detailed error logging
            console.error("Erreur lors de la récupération du produit:", {
                status: status,
                error: error,
                responseText: xhr.responseText
            });
            
            // User-friendly error notification
            let errorMessage = "Erreur de chargement du produit";
            
            try {
                // Try to parse error response
                var errorResponse = JSON.parse(xhr.responseText);
                if (errorResponse && errorResponse.message) {
                    errorMessage = errorResponse.message;
                }
            } catch(e) {
                errorMessage = "Le format de la réponse est invalide. Veuillez contacter l'administrateur.";
                console.error("Erreur d'analyse JSON:", e);
            }
            
            // Show error notification
            new AWN().alert(errorMessage, { 
                durations: { alert: 5000 } 
            });
        }
    });
});

        // Delete Product Handler
        $('.TableProducts tbody').on('click', '.deleteProduct', function(e) {
            e.preventDefault();
            var productId = $(this).attr('data-id');
            let notifier = new AWN();

            let onOk = () => {
                $.ajax({
                    type: "POST",
                    url: deleteProduct_url,
                    data: {
                        id: productId,
                        _token: csrf_token,
                    },
                    dataType: "json",
                    success: function (response) {
                        if(response.status == 200) {
                            notifier.success(response.message, {durations: {success: 5000}});
                            $('.TableProducts').DataTable().ajax.reload();
                        } else {
                            notifier.alert(response.message, {durations: {alert: 5000}});
                        }
                    },
                    error: function(xhr) {
                        notifier.alert("Erreur lors de la suppression", { durations: { alert: 5000 } });
                    }
                });
            };

            let onCancel = () => {
                notifier.info('Suppression annulée');
            };

            notifier.confirm(
                'Voulez-vous vraiment supprimer ce produit ?',
                onOk,
                onCancel,
                {
                    labels: {
                        confirm: 'Supprimer',
                        cancel: 'Annuler'
                    }
                }
            );
        });
    } catch (error) {
        console.error("Erreur d'initialisation du DataTable:", error);
        new AWN().alert("Erreur d'initialisation du tableau", { durations: { alert: 5000 } });
    }
}
    
// Load Subcategories Function
function loadSubcategories(categorySelector, subcategorySelector, selectedValue = null) {
    var categoryId = $(categorySelector).val();
    var subcategorySelect = $(subcategorySelector);
    
    // Reset subcategory dropdown
    subcategorySelect.empty().append('<option value="">Sélectionner une famille</option>');
    
    if (!categoryId) {
        console.warn('Aucune catégorie sélectionnée');
        return;
    }

    $.ajax({
        url: getSubcategories_url + "/" + categoryId,
        type: 'GET',
        dataType: 'json',
        success: function(response) {
            console.log("Réponse des sous-catégories:", response);
            
            if (response.status === 200 && response.subcategories.length > 0) {
                $.each(response.subcategories, function(key, subcategory) {
                    subcategorySelect.append(
                        `<option value="${subcategory.id}">${subcategory.name}</option>`
                    );
                });

                let $drodownProduct = $('#name');
                $drodownProduct.empty();
                $drodownProduct.append('<option value="0">Veuillez sélectionner des produits</option>');
                $.each(response.products, function (index, value) 
                { 
                     $drodownProduct.append('<option value="' +value.id+ '">' + value.name + '</option>');
                });
                
                // Set selected value if provided
                if (selectedValue) {
                    subcategorySelect.val(selectedValue);
                }
            } else {
                console.warn('Aucune sous-catégorie trouvée');
                new AWN().warning("Aucune famille trouvée pour cette catégorie", { durations: { warning: 5000 } });
            }
        },
        error: function(xhr, status, error) {
            console.error("Erreur de chargement des sous-catégories:", error);
            new AWN().alert("Impossible de charger les familles", { durations: { alert: 5000 } });
        }
    });
}

// Load Rayons Function
function loadRayons(localSelector, rayonSelector, selectedValue = null) {
    var localId = $(localSelector).val();
    var rayonSelect = $(rayonSelector);
    
    // Reset rayon dropdown
    rayonSelect.empty().append('<option value="">Sélectionner un rayon</option>');
    
    if (!localId) {
        console.warn('Aucun local sélectionné');
        return;
    }

    $.ajax({
        url: getRayons_url + "/" + localId,
        type: 'GET',
        dataType: 'json',
        success: function(response) {
            console.log("Réponse des rayons:", response);
            
            if (response.status === 200 && response.rayons.length > 0) {
                $.each(response.rayons, function(key, rayon) {
                    rayonSelect.append(
                        `<option value="${rayon.id}">${rayon.name}</option>`
                    );
                });
                
                // Set selected value if provided
                if (selectedValue) {
                    rayonSelect.val(selectedValue);
                }
            } else {
                console.warn('Aucun rayon trouvé');
                new AWN().warning("Aucun rayon trouvé pour ce local", { durations: { warning: 5000 } });
            }
        },
        error: function(xhr, status, error) {
            console.error("Erreur de chargement des rayons:", error);
            new AWN().alert("Impossible de charger les rayons", { durations: { alert: 5000 } });
        }
    });
}

function initializeFilters() {
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
            // If no class selected, reload all categories via AJAX
            loadAllCategories();
        }
        
        $('.TableProducts').DataTable().ajax.reload();
    });
    
    // Category filter change
    $('#filter_categorie').on('change', function() {
        var categoryId = $(this).val();
        
        $('#filter_subcategorie').empty().append('<option value="">Toutes les familles</option>');
        
        if (categoryId) {
            loadFilterSubcategories(categoryId);
        }
        
        $('.TableProducts').DataTable().ajax.reload();
    });
    
    // Subcategory filter change
    $('#filter_subcategorie').on('change', function() {
        $('.TableProducts').DataTable().ajax.reload();
    });
    
    // Designation autocomplete
    let designationTimeout;
    $('#filter_designation').on('keyup', function() {
        clearTimeout(designationTimeout);
        const query = $(this).val();
        
        if (query.length < 2) {
            $('#designation_suggestions').hide().empty();
            if (query.length === 0) {
                $('.TableProducts').DataTable().ajax.reload();
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
        $('.TableProducts').DataTable().ajax.reload();
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
        
        // Reset category dropdown
        $('#filter_categorie').empty().append('<option value="">Toutes les catégories</option>');
        loadAllCategories();
        
        // Reset subcategory dropdown
        $('#filter_subcategorie').empty().append('<option value="">Toutes les familles</option>');
        
        // Reload table
        $('.TableProducts').DataTable().ajax.reload();
    });
}

// Load all categories (used when no class filter is selected)
function loadAllCategories() {
    var categorySelect = $('#filter_categorie');
    
    $.ajax({
        type: "GET",
        url: products_url + '/categories',
        dataType: "json",
        success: function(response) {
            if (response.status === 200) {
                $.each(response.categories, function(index, category) {
                    categorySelect.append('<option value="' + category.id + '">' + category.name + '</option>');
                });
            }
        },
        error: function(xhr, status, error) {
            console.error("Erreur de chargement de toutes les catégories:", error);
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
        },
        error: function(xhr, status, error) {
            console.error("Erreur de chargement des catégories pour le filtre:", error);
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
            } else {
                console.warn('Aucune sous-catégorie trouvée pour le filtre');
            }
        },
        error: function(xhr, status, error) {
            console.error("Erreur de chargement des sous-catégories pour le filtre:", error);
        }
    });
}

// Initialize Dropdowns
function initializeDropdowns() {
    // Class change - load categories for ADD form
    $('#Class_Categorie').on('change', function() {
        let className = $(this).val();
        let categorySelect = $('#Categorie_Class');
        let subcategorySelect = $('#id_subcategorie');
        
        // Reset dependent dropdowns
        categorySelect.empty().append('<option value="">Sélectionner une catégorie</option>');
        subcategorySelect.empty().append('<option value="">Sélectionner une famille</option>');
        
        if (className) {
            loadCategoriesByClass(className, categorySelect);
        }
    });
    
    // Class change - load categories for EDIT form
    $('#edit_Class_Categorie').on('change', function() {
        let className = $(this).val();
        let categorySelect = $('#edit_Categorie_Class');
        let subcategorySelect = $('#edit_id_subcategorie');
        
        // Reset dependent dropdowns
        categorySelect.empty().append('<option value="">Sélectionner une catégorie</option>');
        subcategorySelect.empty().append('<option value="">Sélectionner une famille</option>');
        
        if (className) {
            loadCategoriesByClass(className, categorySelect);
        }
    });
    
    // Category change - load subcategories for ADD form
    $('#Categorie_Class').on('change', function() {
        loadSubcategories('#Categorie_Class', '#id_subcategorie');
    });
    
    // Category change - load subcategories for EDIT form
    $('#edit_Categorie_Class').on('change', function() {
        loadSubcategories('#edit_Categorie_Class', '#edit_id_subcategorie');
    });
    
    // Local change - load rayons (existing logic)
    $('#id_local, #edit_id_local').on('change', function() {
        var targetLocal = $(this).attr('id') === 'id_local' 
            ? '#id_rayon' 
            : '#edit_id_rayon';
        
        loadRayons(
            '#' + $(this).attr('id'), 
            targetLocal
        );
    });
}

function loadCategoriesByClass(className, categorySelect) {
    $.ajax({
        type: "GET",
        url: GetCategorieByClass,
        data: { class: className },
        dataType: "json",
        success: function (response) {
            if (response.status === 200) {
                // ✅ Always clear existing options first
                categorySelect.empty().append('<option value="">Sélectionner une catégorie</option>');

                // ✅ Append categories without duplication
                $.each(response.data, function(index, item) {
                    categorySelect.append('<option value="' + item.id + '">' + item.name + '</option>');
                });
            } else {
                console.warn("Aucune catégorie trouvée pour la classe:", className);
            }
        },
        error: function(xhr, status, error) {
            console.error("Erreur de chargement des catégories:", error);
            new AWN().alert("Impossible de charger les catégories", { durations: { alert: 5000 } });
        }
    });
}


// Add Product Handler
$('#BtnAddProduct').on('click', function(e) {
    e.preventDefault();
    
    let formData = new FormData($('#FormAddProduct')[0]);
    formData.append('_token', csrf_token);
    let productSelect = $('#name');
    let productValue = productSelect.val();               // e.g., "2"
    let productText  = productSelect.find('option:selected').text();
    formData.append('name', productText);
    $('#BtnAddProduct').prop('disabled', true).text('Enregistrement...');
    
    $.ajax({
        type: "POST",
        url: addProduct_url,
        data: formData,
        processData: false,
        contentType: false,
        dataType: "json",
        success: function (response) {
            $('#BtnAddProduct').prop('disabled', false).text('Sauvegarder');
            
            if(response.status == 200) {
                new AWN().success(response.message, {durations: {success: 5000}});
                $('#ModalAddProduct').modal('hide');
                $('.TableProducts').DataTable().ajax.reload();
                $('#FormAddProduct')[0].reset();
                $('#photo_preview').hide();
            } else if(response.status == 400) {
                $('.validationAddProduct').html("");
                $('.validationAddProduct').addClass('alert alert-danger');
                $.each(response.errors, function(key, list_err) {
                    $('.validationAddProduct').append('<li>' + list_err + '</li>');
                });
                
                setTimeout(() => {
                    $('.validationAddProduct').fadeOut('slow', function() {
                        $(this).html("").removeClass('alert alert-danger').show();
                    });
                }, 5000);
            } else if(response.status == 422) {
                // Traitement spécifique pour le cas où un produit avec le même nom existe déjà
                new AWN().alert(response.message, { durations: { alert: 5000 } });
            } else {
                new AWN().alert(response.message, { durations: { alert: 5000 } });
            }
        },
       error: function(xhr, status, error) {
    $('#BtnAddProduct').prop('disabled', false).text('Sauvegarder');
    
    // Handle validation errors (400 status) - Show in AWN notification instead of modal
    if (xhr.status === 400 && xhr.responseJSON && xhr.responseJSON.errors) {
        // Combine all validation messages into one string
        let errorMessages = [];
        $.each(xhr.responseJSON.errors, function(key, list_err) {
            errorMessages.push(list_err);
        });
        
        // Display all messages in the same AWN error notification
        new AWN().alert(errorMessages.join('<br>'), { durations: { alert: 8000 } });
    }
    // Handle duplicate name error (422 status)
    else if (xhr.status === 422 && xhr.responseJSON) {
        new AWN().alert(xhr.responseJSON.message, { durations: { alert: 5000 } });
    }
    // Handle other errors
    else {
        if (xhr.responseJSON && xhr.responseJSON.message) {
            new AWN().alert(xhr.responseJSON.message, { durations: { alert: 5000 } });
        } else {
            new AWN().alert("Une erreur est survenue", { durations: { alert: 5000 } });
        }
    }
}
    });
});

// Update Product Handler
$('#BtnUpdateProduct').on('click', function(e) {
    e.preventDefault();
    
    // Make sure we're using the correct form
    if ($('#FormUpdateProduct').length === 0) {
        console.error("Form #FormUpdateProduct not found!");
        new AWN().alert("Erreur: formulaire introuvable", { durations: { alert: 5000 } });
        return;
    }
    
    // Create FormData from the form
    let formData = new FormData($('#FormUpdateProduct')[0]);
    
    // Add token and ID
    formData.append('_token', csrf_token);
    formData.append('id', $('#edit_id').val());
    
    // Explicitly add the current photo path if no new file is selected
   /*  if (!$('#edit_photo')[0].files.length && $('#current_photo_path').val()) {
        // Make sure the current_photo_path is included in the formData
        // If the field is already in the form, this ensures it's correctly set
        formData.set('current_photo_path', $('#current_photo_path').val());
        console.log("Using existing photo path:", $('#current_photo_path').val());
    } */
    
    // Debug: Log all form data entries
    console.log("Form data being sent:");
    for (var pair of formData.entries()) {
        console.log(pair[0] + ': ' + pair[1]);
    }
    
    $('#BtnUpdateProduct').prop('disabled', true).text('Mise à jour...');
    
    $.ajax({
        type: "POST",
        url: updateProduct_url,
        data: formData,
        processData: false,
        contentType: false,
        dataType: "json",
        success: function(response) {
            $('#BtnUpdateProduct').prop('disabled', false).text('Mettre à jour');
            
            if (response.status == 200) {
                new AWN().success(response.message, {durations: {success: 5000}});
                $('#ModalEditProduct').modal('hide');
                $('.TableProducts').DataTable().ajax.reload();
                $('#edit_photo_preview').hide();
            } else if (response.status == 400) {
                $('.validationEditProduct').html("");
                $('.validationEditProduct').addClass('alert alert-danger');
                $.each(response.errors, function(key, list_err) {
                    $('.validationEditProduct').append('<li>' + list_err + '</li>');
                });
                
                setTimeout(() => {
                    $('.validationEditProduct').fadeOut('slow', function() {
                        $(this).html("").removeClass('alert alert-danger').show();
                    });
                }, 5000);
            } else if (response.status == 422) {
                // Traitement spécifique pour le cas où un produit avec le même nom existe déjà
                new AWN().alert(response.message, { durations: { alert: 5000 } });
            }else if(response.status == 405)
            {
                new AWN().alert(response.message, { durations: { alert: 5000 } }); 
            }
            
            else {
                new AWN().alert(response.message, { durations: { alert: 5000 } });
            }
        },
     error: function(xhr) {
    $('#BtnUpdateProduct').prop('disabled', false).text('Mettre à jour');
    
    // Handle validation errors (400 status) - Show in AWN notification
    if (xhr.status === 400 && xhr.responseJSON && xhr.responseJSON.errors) {
        // Combine all validation messages into one string
        let errorMessages = [];
        $.each(xhr.responseJSON.errors, function(key, list_err) {
            errorMessages.push(list_err);
        });
        
        // Display all messages in the same AWN error notification
        new AWN().alert(errorMessages.join('<br>'), { durations: { alert: 8000 } });
    }
    // Handle duplicate name error (422 status)
    else if (xhr.status === 422 && xhr.responseJSON) {
        new AWN().alert(xhr.responseJSON.message, { durations: { alert: 5000 } });
    }
    // Handle other errors
    else {
        if (xhr.responseJSON && xhr.responseJSON.message) {
            new AWN().alert(xhr.responseJSON.message, { durations: { alert: 5000 } });
        } else {
            new AWN().alert("Une erreur est survenue", { durations: { alert: 5000 } });
        }
    }
}
    });
});

// Add this to handle file input change and provide preview
$('#edit_photo').on('change', function() {
    if (this.files && this.files[0]) {
        var reader = new FileReader();
        
        reader.onload = function(e) {
            $('#edit_photo_preview').html('<img src="' + e.target.result + '" alt="Preview" class="img-thumbnail" style="width: 100px; height: 100px;"><p class="mt-2">Nouvelle photo</p>');
            $('#edit_photo_preview').show();
            // Hide the current photo container when a new photo is selected
            $('#current_photo_container').hide();
        }
        
        reader.readAsDataURL(this.files[0]);
    } else {
        $('#edit_photo_preview').hide();
        // Show the current photo container again if no new photo is selected
        if ($('#current_photo_path').val()) {
            $('#current_photo_container').show();
        }
    }
});

// Initial dropdown population on page load
$(document).ready(function() {
    // Populate subcategories if category is pre-selected
    if ($('#id_categorie').val()) {
        loadSubcategories('#id_categorie', '#id_subcategorie');
    }
    
    // Populate rayons if local is pre-selected
    if ($('#id_local').val()) {
        loadRayons('#id_local', '#id_rayon');
    }
    // Initialize file inputs for photo preview
    initializeFileInputs();
});

// Import Product Handler
$(document).on('click', '#BtnImportProduct', function(e) {
    e.preventDefault();
    
    let formData = new FormData($('#FormImportProduct')[0]);
    formData.append('_token', csrf_token);

    // Check if file is selected
    if ($('#import_file').val() == '') {
        new AWN().warning('Veuillez sélectionner un fichier.', {durations: {warning: 5000}});
        return;
    }

    // Add loading state with progress info
    $('#BtnImportProduct').prop('disabled', true).text('Importation en cours...');
    
    // Add a progress message to the UI
    if (!$('.import-progress').length) {
        $('<div class="alert alert-info import-progress mt-3">Importation en cours. Veuillez patienter pour les fichiers volumineux...</div>').insertAfter('#FormImportProduct');
    }

    $.ajax({
        type: "POST",
        url: importProduct_url,
        data: formData,
        processData: false,
        contentType: false,
        dataType: "json",
        // Set a longer timeout for larger files
        timeout: 600000, // 5 minutes
        success: function (response) {
            $('#BtnImportProduct').prop('disabled', false).text('Importer');
            $('.import-progress').remove();
            
            if(response.status == 200) {
                // For successful imports, show more details about the results
                let successMessage = response.message;
                if (response.imported > 0) {
                    successMessage += ' Rafraîchissement de la liste...';
                }
                new AWN().success(successMessage, {durations: {success: 10000}}); // Longer duration for large imports
                
                $('#ModalImportProduct').modal('hide');
                $('.TableProducts').DataTable().ajax.reload();
                $('#FormImportProduct')[0].reset();
            } else if(response.status == 400) {
                $('.validationImportProduct').html("");
                $('.validationImportProduct').addClass('alert alert-danger');
                $.each(response.errors, function(key, list_err) {
                    $('.validationImportProduct').append('<li>' + list_err + '</li>');
                });
                
                setTimeout(() => {
                    $('.validationImportProduct').fadeOut('slow', function() {
                        $(this).html("").removeClass('alert alert-danger').show();
                    });
                }, 10000); // Longer duration for errors with large imports
            } else if (response.status == 500) {
                new AWN().alert(response.message, { durations: { alert: 10000 } });
            }
        },
        error: function(xhr, status, error) {
            $('#BtnImportProduct').prop('disabled', false).text('Importer');
            $('.import-progress').remove();
            
            // Better handling of timeout errors
            if (status === 'timeout') {
                new AWN().alert("L'importation a pris trop de temps. Essayez avec un fichier plus petit ou contactez l'administrateur.", { durations: { alert: 10000 } });
                return;
            }
            
            if (xhr.status === 403) {
                new AWN().warning(xhr.responseJSON.message, {durations: {warning: 5000}});
            } else {
                try {
                    var errorResponse = JSON.parse(xhr.responseText);
                    if (errorResponse && errorResponse.message) {
                        new AWN().alert(errorResponse.message, { durations: { alert: 8000 } });
                    } else {
                        new AWN().alert("Une erreur est survenue, veuillez réessayer.", { durations: { alert: 8000 } });
                    }
                } catch (e) {
                    // If the response is too large, it might not parse correctly
                    if (xhr.responseText && xhr.responseText.length > 1000) {
                        new AWN().alert("Erreur lors de l'importation. Le fichier est peut-être trop volumineux.", { durations: { alert: 8000 } });
                    } else {
                        new AWN().alert("Une erreur est survenue, veuillez réessayer.", { durations: { alert: 8000 } });
                    }
                }
            }
        }
    });
});


$('#id_subcategorie').on('change',function(e)
{
    e.preventDefault();
    let id_sub_category = $(this).val();
    let classValue = $('#Class_Categorie').val();
    let category = $('#Categorie_Class').val();

    if (classValue == 0 || classValue === "") {
        alert("Please select a class.");
        return false;
    }

    if (category == 0 || category === "") {
        alert("Please select a category.");
        return false;
    }

    $.ajax({
        type: "get",
        url: GetProductByFamaille,
        data: 
        {
            id_sub_category : id_sub_category
        },
        dataType: "json",
        success: function (response) 
        {
            if(response.status == 200)
            {
                let $dropDownProduct = $('#name');
                $dropDownProduct.empty();
                $dropDownProduct.append('<option value="0">Veuillez sélectionner des produits</option>');
                $.each(response.products, function (index, value) 
                { 
                    $dropDownProduct.append('<option value="' +value.id+ '">' + value.name + '</option>');
                });
            }    
        }
    });
});

$('#name').on('change', function(e) {
    e.preventDefault();
    let product = $(this).val();

    if (product == 0 || product === "") {
        alert("Please select a product.");
        return false;
    }

    $.ajax({
        type: "get",
        url: getUnitebyProduct,
        data: { product: product },
        dataType: "json",
        success: function(response) {
            if (response.status == 200) {
                let $dropDownid_unite = $('#id_unite');
                $dropDownid_unite.empty();
                $dropDownid_unite.append('<option value="0">Veuillez sélectionner une unité</option>');
                $dropDownid_unite.append('<option value="' + response.unite.id + '">' + response.unite.name + '</option>');
            } else {
                alert(response.message);
            }
        }
    });
});




});