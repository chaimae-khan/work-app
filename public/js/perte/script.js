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

    // Track AJAX requests to prevent duplicates
    let ajaxInProgress = {
        delete: false,
        update: false,
        changeStatus: false,
        add: false,
        import: false
    };

    // DataTable Initialization
    function initializeDataTable() {
        try {
            // Destroy existing DataTable if it exists
            if ($.fn.DataTable.isDataTable('.TablePertes')) {
                $('.TablePertes').DataTable().destroy();
            }
            
            // Initialize DataTable
            var tablePertes = $('.TablePertes').DataTable({
                processing: true,
                serverSide: true,
                ajax: {
                    url: pertes_url,
                    data: function(d) {
                        // Add filter parameters
                        d.filter_class = $('#filter_class').val();
                        d.filter_categorie = $('#filter_categorie').val();
                        d.filter_subcategorie = $('#filter_subcategorie').val();
                        d.filter_status = $('#filter_status').val();
                        d.filter_designation = $('#filter_designation').val();
                        d.filter_nature = $('#filter_nature').val();
                        d.filter_cause = $('#filter_cause').val();
                        d.filter_date = $('#filter_date').val();
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
                    { data: 'reference', name: 'reference' },
                    { data: 'code_article', name: 'code_article' },
                    { data: 'class', name: 'class' },
                    { data: 'categorie', name: 'categorie' },
                    { data: 'famille', name: 'famille' },
                    { data: 'designation', name: 'designation' },
                    { data: 'quantite', name: 'quantite' },
                    { data: 'unite', name: 'unite' },
                    { data: 'nature', name: 'nature' },
                    { 
                        data: 'cause', 
                        name: 'cause',
                        render: function(data) {
                            if (data && data.length > 50) {
                                return data.substring(0, 50) + '...';
                            }
                            return data;
                        }
                    },
                    { 
                        data: 'date_perte', 
                        name: 'date_perte',
                        render: function(data) {
                            if (data) {
                                const date = new Date(data);
                                return date.toLocaleDateString('fr-FR');
                            }
                            return '';
                        }
                    },
                    { 
                        data: 'status', 
                        name: 'status',
                        render: function(data) {
                            let badgeClass = 'bg-secondary';
                            if (data === 'Validé') badgeClass = 'bg-success';
                            else if (data === 'Refusé') badgeClass = 'bg-danger';
                            else if (data === 'En attente') badgeClass = 'bg-warning';
                            return '<span class="badge ' + badgeClass + '">' + data + '</span>';
                        }
                    },
                    { data: 'created_by', name: 'created_by' },
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

            // Edit Perte Handler
            $('.TablePertes tbody').on('click', '.editPerte', function(e) {
                e.preventDefault();
                var perteId = $(this).attr('data-id');
                
                // Disable edit button during loading
                $(this).prop('disabled', true);
                
                $.ajax({
                    type: "GET",
                    url: editPerte_url + "/" + perteId,
                    dataType: "json", 
                    success: function(response) {
                        // Enable edit button
                        $('.editPerte').prop('disabled', false);
                        
                        console.log("Données de la perte:", response);
                        
                        // Clear dropdowns
                        $('#edit_Categorie_Class').empty().append('<option value="">Sélectionner une catégorie</option>');
                        $('#edit_id_subcategorie').empty().append('<option value="">Sélectionner une famille</option>');
                        
                        // Show edit modal
                        $('#ModalEditPerte').modal("show");
                        
                        // Clear validation errors
                        $('.validationEditPerte').html("").removeClass('alert alert-danger');
                        
                        // Populate form fields
                        $('#edit_id').val(response.id);
                        $('#edit_designation').val(response.designation);
                        $('#edit_quantite').val(response.quantite);
                        $('#edit_id_unite').val(response.id_unite);
                        $('#edit_nature').val(response.nature);
                        $('#edit_cause').val(response.cause);
                        $('#edit_id_product').val(response.id_product);
                        
                        // Set date
                        if (response.date_perte) {
                            const date = new Date(response.date_perte);
                            const formattedDate = date.toISOString().split('T')[0];
                            $('#edit_date_perte').val(formattedDate);
                        }
                        
                        // Handle class and category
                        if (response.class) {
                            $('#edit_Class_Categorie').val(response.class);
                            
                            // Load categories for this class
                            $.ajax({
                                type: "GET",
                                url: GetCategorieByClass,
                                data: { class: response.class },
                                dataType: "json",
                                success: function (classResponse) {
                                    if(classResponse.status == 200) {
                                        var categorySelect = $('#edit_Categorie_Class');
                                        
                                        $.each(classResponse.data, function(index, item) {
                                            categorySelect.append('<option value="' + item.id + '">' + item.name + '</option>');
                                        });
                                        
                                        // Set category
                                        categorySelect.val(response.id_categorie);
                                        
                                        // Load subcategories
                                        loadSubcategories('#edit_Categorie_Class', '#edit_id_subcategorie', response.id_subcategorie);
                                    }
                                }
                            });
                        }
                    },
                    error: function(xhr, status, error) {
                        $('.editPerte').prop('disabled', false);
                        console.error("Erreur lors de la récupération de la perte:", error);
                        new AWN().alert("Erreur de chargement de la perte", { durations: { alert: 5000 } });
                    }
                });
            });

            // Delete Perte Handler
            $('.TablePertes tbody').on('click', '.deletePerte', function(e) {
                e.preventDefault();
                
                if (ajaxInProgress.delete) {
                    return;
                }
                
                var perteId = $(this).attr('data-id');
                let notifier = new AWN();
                let deleteButton = $(this);

                let onOk = () => {
                    ajaxInProgress.delete = true;
                    deleteButton.prop('disabled', true).html('<i class="fa-solid fa-spinner fa-spin text-danger"></i>');
                    
                    $.ajax({
                        type: "POST",
                        url: deletePerte_url,
                        data: {
                            id: perteId,
                            _token: csrf_token,
                        },
                        dataType: "json",
                        success: function (response) {
                            ajaxInProgress.delete = false;
                            
                            if(response.status == 200) {
                                notifier.success(response.message, {durations: {success: 5000}});
                                $('.TablePertes').DataTable().ajax.reload();
                            } else {
                                deleteButton.prop('disabled', false).html('<i class="fa-solid fa-trash text-danger"></i>');
                                notifier.alert(response.message, {durations: {alert: 5000}});
                            }
                        },
                        error: function(xhr) {
                            ajaxInProgress.delete = false;
                            deleteButton.prop('disabled', false).html('<i class="fa-solid fa-trash text-danger"></i>');
                            notifier.alert("Erreur lors de la suppression", { durations: { alert: 5000 } });
                        }
                    });
                };

                let onCancel = () => {
                    notifier.info('Suppression annulée');
                };

                notifier.confirm(
                    'Voulez-vous vraiment supprimer cette déclaration de perte ?',
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

            // Change Status Handler
            $('.TablePertes tbody').on('click', '.changeStatusPerte', function(e) {
                e.preventDefault();
                var perteId = $(this).attr('data-id');
                var reference = $(this).attr('data-reference');
                
                $.ajax({
                    type: "GET",
                    url: editPerte_url + "/" + perteId,
                    dataType: "json",
                    success: function(response) {
                        $('#status_id').val(response.id);
                        $('#status_reference').text(reference);
                        $('#status_statut').val(response.status);
                        
                        // Show/hide refusal reason
                        toggleRefusalReasonField(response.status);
                        
                        if (response.status === 'Refusé' && response.refusal_reason) {
                            $('#status_refusal_reason').val(response.refusal_reason);
                        }
                        
                        $('#ModalChangeStatusPerte').modal('show');
                    },
                    error: function(xhr) {
                        new AWN().alert("Erreur lors du chargement des données", {durations: {alert: 5000}});
                    }
                });
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
        
        subcategorySelect.empty().append('<option value="">Sélectionner une famille</option>');
        
        if (!categoryId) {
            return;
        }

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
                    
                    if (selectedValue) {
                        subcategorySelect.val(selectedValue);
                    }
                }
            },
            error: function(xhr, status, error) {
                console.error("Erreur de chargement des sous-catégories:", error);
            }
        });
    }

    // Initialize Filters
    function initializeFilters() {
        // Class filter change
        $('#filter_class').on('change', function() {
            var className = $(this).val();
            var categorySelect = $('#filter_categorie');
            var subcategorySelect = $('#filter_subcategorie');
            
            categorySelect.empty().append('<option value="">Toutes les catégories</option>');
            subcategorySelect.empty().append('<option value="">Toutes les familles</option>');
            
            if (className) {
                loadFilterCategoriesByClass(className);
            } else {
                loadAllCategories();
            }
            
            $('.TablePertes').DataTable().ajax.reload();
        });
        
        // Category filter change
        $('#filter_categorie').on('change', function() {
            var categoryId = $(this).val();
            
            $('#filter_subcategorie').empty().append('<option value="">Toutes les familles</option>');
            
            if (categoryId) {
                loadFilterSubcategories(categoryId);
            }
            
            $('.TablePertes').DataTable().ajax.reload();
        });
        
        // Other filters
        $('#filter_subcategorie, #filter_status, #filter_nature, #filter_date').on('change', function() {
            $('.TablePertes').DataTable().ajax.reload();
        });
        
        $('#filter_cause').on('keyup', function() {
            clearTimeout(window.filterTimeout);
            window.filterTimeout = setTimeout(function() {
                $('.TablePertes').DataTable().ajax.reload();
            }, 500);
        });
        
        // Designation autocomplete
        let designationTimeout;
        $('#filter_designation').on('keyup', function() {
            clearTimeout(designationTimeout);
            const query = $(this).val();
            
            if (query.length < 2) {
                $('#designation_suggestions').hide().empty();
                if (query.length === 0) {
                    $('.TablePertes').DataTable().ajax.reload();
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
                                suggestions += '<a href="#" class="list-group-item list-group-item-action designation-filter-item" data-name="' + product.name + '">' + product.name + '</a>';
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
        $(document).on('click', '.designation-filter-item', function(e) {
            e.preventDefault();
            const name = $(this).data('name');
            $('#filter_designation').val(name);
            $('#designation_suggestions').hide().empty();
            $('.TablePertes').DataTable().ajax.reload();
        });
        
        // Reset filters
        $('#btn_reset_filter').on('click', function() {
            $('#filter_class').val('');
            $('#filter_status').val('');
            $('#filter_nature').val('');
            $('#filter_cause').val('');
            $('#filter_date').val('');
            $('#filter_designation').val('');
            $('#designation_suggestions').hide().empty();
            
            $('#filter_categorie').empty().append('<option value="">Toutes les catégories</option>');
            loadAllCategories();
            
            $('#filter_subcategorie').empty().append('<option value="">Toutes les familles</option>');
            
            $('.TablePertes').DataTable().ajax.reload();
        });
    }

    // Load all categories
    function loadAllCategories() {
        var categorySelect = $('#filter_categorie');
        
        $.ajax({
            type: "GET",
            url: pertes_url + '/categories',
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

    // Initialize Dropdowns
    function initializeDropdowns() {
        // Class change - ADD form
        $('#Class_Categorie').on('change', function() {
            let className = $(this).val();
            let categorySelect = $('#Categorie_Class');
            let subcategorySelect = $('#id_subcategorie');
            
            categorySelect.empty().append('<option value="">Sélectionner une catégorie</option>');
            subcategorySelect.empty().append('<option value="">Sélectionner une famille</option>');
            
            if (className) {
                loadCategoriesByClass(className, categorySelect);
            }
        });
        
        // Class change - EDIT form
        $('#edit_Class_Categorie').on('change', function() {
            let className = $(this).val();
            let categorySelect = $('#edit_Categorie_Class');
            let subcategorySelect = $('#edit_id_subcategorie');
            
            categorySelect.empty().append('<option value="">Sélectionner une catégorie</option>');
            subcategorySelect.empty().append('<option value="">Sélectionner une famille</option>');
            
            if (className) {
                loadCategoriesByClass(className, categorySelect);
            }
        });
        
        // Category change - ADD form
        $('#Categorie_Class').on('change', function() {
            loadSubcategories('#Categorie_Class', '#id_subcategorie');
        });
        
        // Category change - EDIT form
        $('#edit_Categorie_Class').on('change', function() {
            loadSubcategories('#edit_Categorie_Class', '#edit_id_subcategorie');
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
                    categorySelect.empty().append('<option value="">Sélectionner une catégorie</option>');

                    $.each(response.data, function(index, item) {
                        categorySelect.append('<option value="' + item.id + '">' + item.name + '</option>');
                    });
                }
            }
        });
    }

    // Product autocomplete for ADD form
    let productTimeout;
    $('#designation').on('keyup', function() {
        clearTimeout(productTimeout);
        const query = $(this).val();
        
        if (query.length < 2) {
            $('#product_suggestions').hide().empty();
            return;
        }
        
        productTimeout = setTimeout(function() {
            $.ajax({
                url: searchProductNames_url,
                type: 'GET',
                data: { query: query },
                success: function(response) {
                    if (response.status === 200 && response.products.length > 0) {
                        let suggestions = '';
                        $.each(response.products, function(key, product) {
                            suggestions += '<a href="#" class="list-group-item list-group-item-action product-suggestion-item" data-id="' + product.id + '" data-name="' + product.name + '">' + product.name + ' (' + product.code_article + ')</a>';
                        });
                        $('#product_suggestions').html(suggestions).show();
                    } else {
                        $('#product_suggestions').hide().empty();
                    }
                }
            });
        }, 300);
    });
    
    // Click on product suggestion
    $(document).on('click', '.product-suggestion-item', function(e) {
        e.preventDefault();
        const productId = $(this).data('id');
        const productName = $(this).data('name');
        
        $('#designation').val(productName);
        $('#id_product').val(productId);
        $('#product_suggestions').hide().empty();
        
        // Optionally load product details
        $.ajax({
            url: getProductDetails_url + "/" + productId,
            type: 'GET',
            success: function(response) {
                if (response.status === 200) {
                    // Auto-fill fields if needed
                    if (response.product.id_unite) {
                        $('#id_unite').val(response.product.id_unite);
                    }
                    if (response.product.class) {
                        $('#Class_Categorie').val(response.product.class).trigger('change');
                    }
                }
            }
        });
    });
    
    // Hide suggestions when clicking outside
    $(document).on('click', function(e) {
        if (!$(e.target).closest('#designation, #product_suggestions, #filter_designation, #designation_suggestions').length) {
            $('#product_suggestions, #designation_suggestions').hide();
        }
    });

    // Add Perte Handler
    $('#BtnAddPerte').on('click', function(e) {
        e.preventDefault();
        
        if (ajaxInProgress.add) {
            return;
        }
        
        let formData = new FormData($('#FormAddPerte')[0]);
        formData.append('_token', csrf_token);

        ajaxInProgress.add = true;
        $('#BtnAddPerte').prop('disabled', true).text('Enregistrement...');

        $.ajax({
            type: "POST",
            url: addPerte_url,
            data: formData,
            processData: false,
            contentType: false,
            dataType: "json",
            success: function (response) {
                ajaxInProgress.add = false;
                $('#BtnAddPerte').prop('disabled', false).html('<i class="fa-solid fa-save"></i> Déclarer la perte');
                
                if(response.status == 200) {
                    new AWN().success(response.message, {durations: {success: 5000}});
                    $('#ModalAddPerte').modal('hide');
                    $('.TablePertes').DataTable().ajax.reload();
                    $('#FormAddPerte')[0].reset();
                    $('#product_suggestions').hide().empty();
                } else if(response.status == 400) {
                    $('.validationAddPerte').html("");
                    $('.validationAddPerte').addClass('alert alert-danger');
                    $.each(response.errors, function(key, list_err) {
                        $('.validationAddPerte').append('<li>' + list_err + '</li>');
                    });
                    
                    setTimeout(() => {
                        $('.validationAddPerte').fadeOut('slow', function() {
                            $(this).html("").removeClass('alert alert-danger').show();
                        });
                    }, 5000);
                }
            },
            error: function(xhr, status, error) {
                ajaxInProgress.add = false;
                $('#BtnAddPerte').prop('disabled', false).html('<i class="fa-solid fa-save"></i> Déclarer la perte');
                
                if (xhr.status === 400 && xhr.responseJSON && xhr.responseJSON.errors) {
                    let errorMessages = [];
                    $.each(xhr.responseJSON.errors, function(key, list_err) {
                        errorMessages.push(list_err);
                    });
                    new AWN().alert(errorMessages.join('<br>'), { durations: { alert: 8000 } });
                } else {
                    new AWN().alert("Une erreur est survenue", { durations: { alert: 5000 } });
                }
            }
        });
    });

    // Update Perte Handler
    $('#BtnUpdatePerte').on('click', function(e) {
        e.preventDefault();
        
        if (ajaxInProgress.update) {
            return;
        }
        
        let formData = new FormData($('#FormUpdatePerte')[0]);
        formData.append('_token', csrf_token);
        formData.append('id', $('#edit_id').val());
        
        ajaxInProgress.update = true;
        $('#BtnUpdatePerte').prop('disabled', true).text('Mise à jour...');
        
        $.ajax({
            type: "POST",
            url: updatePerte_url,
            data: formData,
            processData: false,
            contentType: false,
            dataType: "json",
            success: function(response) {
                ajaxInProgress.update = false;
                $('#BtnUpdatePerte').prop('disabled', false).html('<i class="fa-solid fa-save"></i> Mettre à jour');
                
                if (response.status == 200) {
                    new AWN().success(response.message, {durations: {success: 5000}});
                    $('#ModalEditPerte').modal('hide');
                    $('.TablePertes').DataTable().ajax.reload();
                } else if (response.status == 400) {
                    $('.validationEditPerte').html("");
                    $('.validationEditPerte').addClass('alert alert-danger');
                    $.each(response.errors, function(key, list_err) {
                        $('.validationEditPerte').append('<li>' + list_err + '</li>');
                    });
                    
                    setTimeout(() => {
                        $('.validationEditPerte').fadeOut('slow', function() {
                            $(this).html("").removeClass('alert alert-danger').show();
                        });
                    }, 5000);
                }
            },
            error: function(xhr) {
                ajaxInProgress.update = false;
                $('#BtnUpdatePerte').prop('disabled', false).html('<i class="fa-solid fa-save"></i> Mettre à jour');
                
                if (xhr.status === 400 && xhr.responseJSON && xhr.responseJSON.errors) {
                    let errorMessages = [];
                    $.each(xhr.responseJSON.errors, function(key, list_err) {
                        errorMessages.push(list_err);
                    });
                    new AWN().alert(errorMessages.join('<br>'), { durations: { alert: 8000 } });
                } else {
                    new AWN().alert("Une erreur est survenue", { durations: { alert: 5000 } });
                }
            }
        });
    });

    // Handle status change in modal
    $('#status_statut').on('change', function() {
        var selectedStatus = $(this).val();
        toggleRefusalReasonField(selectedStatus);
    });

    // Toggle refusal reason field
    function toggleRefusalReasonField(status) {
        if (status === 'Refusé') {
            $('#refusal_reason_group').show();
            $('#status_refusal_reason').attr('required', true);
        } else {
            $('#refusal_reason_group').hide();
            $('#status_refusal_reason').attr('required', false);
            $('#status_refusal_reason').val('');
        }
    }

    // Change Status Form Submit
    $('#FormChangeStatusPerte').on('submit', function(e) {
        e.preventDefault();
        
        if (ajaxInProgress.changeStatus) {
            return;
        }
        
        var formData = {
            id: $('#status_id').val(),
            status: $('#status_statut').val(),
            refusal_reason: $('#status_refusal_reason').val(),
            _token: csrf_token
        };
        
        // Validate refusal reason
        if (formData.status === 'Refusé' && !formData.refusal_reason.trim()) {
            new AWN().alert("Le motif de refus est requis", {durations: {alert: 5000}});
            return;
        }
        
        ajaxInProgress.changeStatus = true;
        $('#FormChangeStatusPerte button[type="submit"]').prop('disabled', true).text('Mise à jour...');
        
        $.ajax({
            type: "POST",
            url: updateStatusPerte_url,
            data: formData,
            dataType: "json",
            success: function(response) {
                ajaxInProgress.changeStatus = false;
                $('#FormChangeStatusPerte button[type="submit"]').prop('disabled', false).text('Mettre à jour');
                
                if (response.status == 200) {
                    new AWN().success(response.message, {durations: {success: 5000}});
                    $('#ModalChangeStatusPerte').modal('hide');
                    $('.TablePertes').DataTable().ajax.reload();
                } else {
                    new AWN().alert(response.message || "Une erreur est survenue", {durations: {alert: 5000}});
                }
            },
            error: function(xhr) {
                ajaxInProgress.changeStatus = false;
                $('#FormChangeStatusPerte button[type="submit"]').prop('disabled', false).text('Mettre à jour');
                new AWN().alert("Erreur lors de la mise à jour", {durations: {alert: 5000}});
            }
        });
    });

    // Reset modal when hidden
    $('#ModalChangeStatusPerte').on('hidden.bs.modal', function() {
        $('#FormChangeStatusPerte')[0].reset();
        $('#status_error').text('');
        $('#refusal_reason_group').hide();
        $('#status_refusal_reason').attr('required', false);
    });

    // Import Perte Handler
    $('#BtnImportPerte').on('click', function(e) {
        e.preventDefault();
        
        if (ajaxInProgress.import) {
            return;
        }
        
        let formData = new FormData($('#FormImportPerte')[0]);
        formData.append('_token', csrf_token);

        if ($('#import_file').val() == '') {
            new AWN().warning('Veuillez sélectionner un fichier.', {durations: {warning: 5000}});
            return;
        }

        ajaxInProgress.import = true;
        $('#BtnImportPerte').prop('disabled', true).text('Importation en cours...');
        
        if (!$('.import-progress').length) {
            $('<div class="alert alert-info import-progress mt-3">Importation en cours. Veuillez patienter...</div>').insertAfter('#FormImportPerte');
        }

        $.ajax({
            type: "POST",
            url: importPerte_url,
            data: formData,
            processData: false,
            contentType: false,
            dataType: "json",
            timeout: 600000,
            success: function (response) {
                ajaxInProgress.import = false;
                $('#BtnImportPerte').prop('disabled', false).html('<i class="fa-solid fa-upload"></i> Importer');
                $('.import-progress').remove();
                
                if(response.status == 200) {
                    new AWN().success(response.message, {durations: {success: 10000}});
                    $('#ModalImportPerte').modal('hide');
                    $('.TablePertes').DataTable().ajax.reload();
                    $('#FormImportPerte')[0].reset();
                } else if(response.status == 400) {
                    $('.validationImportPerte').html("");
                    $('.validationImportPerte').addClass('alert alert-danger');
                    $.each(response.errors, function(key, list_err) {
                        $('.validationImportPerte').append('<li>' + list_err + '</li>');
                    });
                }
            },
            error: function(xhr, status, error) {
                ajaxInProgress.import = false;
                $('#BtnImportPerte').prop('disabled', false).html('<i class="fa-solid fa-upload"></i> Importer');
                $('.import-progress').remove();
                
                if (status === 'timeout') {
                    new AWN().alert("L'importation a pris trop de temps.", { durations: { alert: 10000 } });
                } else {
                    new AWN().alert("Une erreur est survenue", { durations: { alert: 8000 } });
                }
            }
        });
    });
});