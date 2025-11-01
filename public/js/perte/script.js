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
                        d.filter_status = $('#filter_status').val();
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
                    { data: 'classe', name: 'pt.classe' },
                    { data: 'categorie', name: 'c.name' },
                    { data: 'famille', name: 'sc.name' },
                    { data: 'designation', name: 'pt.designation' },
                    { 
                        data: 'quantite', 
                        name: 'pt.quantite',
                        render: function(data) {
                            return parseFloat(data).toFixed(2);
                        }
                    },
                    { data: 'unite', name: 'u.name' },
                    { data: 'nature', name: 'pt.nature' },
                    { 
                        data: 'date_perte', 
                        name: 'pt.date_perte',
                        render: function(data) {
                            if (data) {
                                const date = new Date(data);
                                return date.toLocaleDateString('fr-FR');
                            }
                            return '';
                        }
                    },
                    { 
                        data: 'status_badge', 
                        name: 'pt.status',
                        orderable: false,
                        searchable: false
                    },
                    { data: 'username', name: 'us.prenom' },
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
        } catch (error) {
            console.error("Erreur d'initialisation du DataTable:", error);
            new AWN().alert("Erreur d'initialisation du tableau", { durations: { alert: 5000 } });
        }
    }

    // Initialize Filters
    function initializeFilters() {
        // Status filter change
        $('#filter_status').on('change', function() {
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
        
        // Subcategory filter change
        $('#filter_subcategorie').on('change', function() {
            $('.TablePertes').DataTable().ajax.reload();
        });
        
        // Reset filters button
        $('#btn_reset_filter').on('click', function() {
            $('#filter_status').val('');
            $('#filter_categorie').val('');
            $('#filter_subcategorie').empty().append('<option value="">Toutes les familles</option>');
            
            // Reload table
            $('.TablePertes').DataTable().ajax.reload();
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
            },
            error: function(xhr, status, error) {
                console.error("Erreur de chargement des sous-catégories pour le filtre:", error);
            }
        });
    }

    // Initialize Dropdowns
    function initializeDropdowns() {
        // Class change - load categories for ADD form
        $('#Class_Categorie_Perte').on('change', function() {
            let className = $(this).val();
            let categorySelect = $('#Categorie_Class_Perte');
            let subcategorySelect = $('#id_subcategorie_perte');
            let productSelect = $('#id_product_perte');
            
            // Reset dependent dropdowns
            categorySelect.empty().append('<option value="">Sélectionner une catégorie</option>');
            subcategorySelect.empty().append('<option value="">Sélectionner une famille</option>');
            productSelect.empty().append('<option value="">Sélectionner un produit</option>');
            $('#unite_display_perte').val('');
            
            if (className) {
                loadCategoriesByClass(className, categorySelect);
            }
        });
        
        // Category change - load subcategories
        $('#Categorie_Class_Perte').on('change', function() {
            loadSubcategories('#Categorie_Class_Perte', '#id_subcategorie_perte');
            $('#id_product_perte').empty().append('<option value="">Sélectionner un produit</option>');
            $('#unite_display_perte').val('');
        });
        
        // Subcategory change - load products
        $('#id_subcategorie_perte').on('change', function() {
            loadProducts('#id_subcategorie_perte', '#id_product_perte');
            $('#unite_display_perte').val('');
        });
        
        // Product change - display unite
        $('#id_product_perte').on('change', function() {
            var selectedOption = $(this).find('option:selected');
            var uniteName = selectedOption.data('unite');
            $('#unite_display_perte').val(uniteName || '');
        });
    }

    // Load Categories by Class
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
            },
            error: function(xhr, status, error) {
                console.error("Erreur de chargement des catégories:", error);
                new AWN().alert("Impossible de charger les catégories", { durations: { alert: 5000 } });
            }
        });
    }

    // Load Subcategories Function
    function loadSubcategories(categorySelector, subcategorySelector, selectedValue = null) {
        var categoryId = $(categorySelector).val();
        var subcategorySelect = $(subcategorySelector);
        
        // Reset subcategory dropdown
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
                    
                    // Set selected value if provided
                    if (selectedValue) {
                        subcategorySelect.val(selectedValue);
                    }
                } else {
                    new AWN().warning("Aucune famille trouvée pour cette catégorie", { durations: { warning: 5000 } });
                }
            },
            error: function(xhr, status, error) {
                console.error("Erreur de chargement des sous-catégories:", error);
                new AWN().alert("Impossible de charger les familles", { durations: { alert: 5000 } });
            }
        });
    }

    // Load Products Function
    function loadProducts(subcategorySelector, productSelector, selectedValue = null) {
        var subcategoryId = $(subcategorySelector).val();
        var productSelect = $(productSelector);
        
        // Reset product dropdown
        productSelect.empty().append('<option value="">Sélectionner un produit</option>');
        
        if (!subcategoryId) {
            return;
        }

        $.ajax({
            url: getProductsBySubcategory_url + "/" + subcategoryId,
            type: 'GET',
            dataType: 'json',
            success: function(response) {
                if (response.status === 200 && response.products.length > 0) {
                    $.each(response.products, function(key, product) {
                        var uniteName = product.unite ? product.unite.name : '';
                        productSelect.append(
                            `<option value="${product.id}" data-unite="${uniteName}">${product.name}</option>`
                        );
                    });
                    
                    // Set selected value if provided
                    if (selectedValue) {
                        productSelect.val(selectedValue);
                        // Trigger change to update unite display
                        productSelect.trigger('change');
                    }
                } else {
                    new AWN().warning("Aucun produit trouvé pour cette famille", { durations: { warning: 5000 } });
                }
            },
            error: function(xhr, status, error) {
                console.error("Erreur de chargement des produits:", error);
                new AWN().alert("Impossible de charger les produits", { durations: { alert: 5000 } });
            }
        });
    }

    // Add Perte Handler
    $('#BtnAddPerte').on('click', function(e) {
        e.preventDefault();
        
        let formData = new FormData($('#FormAddPerte')[0]);
        formData.append('_token', csrf_token);

        $('#BtnAddPerte').prop('disabled', true).text('Enregistrement...');

        $.ajax({
            type: "POST",
            url: addPerte_url,
            data: formData,
            processData: false,
            contentType: false,
            dataType: "json",
            success: function (response) {
                $('#BtnAddPerte').prop('disabled', false).text('Déclarer la perte');
                
                if(response.status == 200) {
                    new AWN().success(response.message, {durations: {success: 5000}});
                    $('#ModalAddPerte').modal('hide');
                    $('.TablePertes').DataTable().ajax.reload();
                    $('#FormAddPerte')[0].reset();
                    $('#unite_display_perte').val('');
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
                } else {
                    new AWN().alert(response.message, { durations: { alert: 5000 } });
                }
            },
            error: function(xhr, status, error) {
                $('#BtnAddPerte').prop('disabled', false).text('Déclarer la perte');
                
                // Handle validation errors
                if (xhr.status === 400 && xhr.responseJSON && xhr.responseJSON.errors) {
                    let errorMessages = [];
                    $.each(xhr.responseJSON.errors, function(key, list_err) {
                        errorMessages.push(list_err);
                    });
                    new AWN().alert(errorMessages.join('<br>'), { durations: { alert: 8000 } });
                } else {
                    if (xhr.responseJSON && xhr.responseJSON.message) {
                        new AWN().alert(xhr.responseJSON.message, { durations: { alert: 5000 } });
                    } else {
                        new AWN().alert("Une erreur est survenue", { durations: { alert: 5000 } });
                    }
                }
            }
        });
    });

    // View Perte Details Handler
    // $('.TablePertes tbody').on('click', '.viewPerte', function(e) {
    //     e.preventDefault();
    //     var perteId = $(this).attr('data-id');
        
    //     $.ajax({
    //         type: "GET",
    //         url: viewPerte_url + "/" + perteId,
    //         dataType: "json",
    //         success: function(response) {
    //             console.log("Perte data:", response);
                
    //             // Populate modal with perte details
    //             $('#view_classe').text(response.classe || '-');
    //             $('#view_category').text(response.category ? response.category.name : '-');
    //             $('#view_subcategory').text(response.subcategory ? response.subcategory.name : '-');
    //             $('#view_designation').text(response.designation || '-');
    //             $('#view_quantite').text(response.quantite || '0');
    //             $('#view_unite').text(response.unite ? response.unite.name : '-');
    //             $('#view_nature').text(response.nature || '-');
                
    //             // Format date
    //             if (response.date_perte) {
    //                 const date = new Date(response.date_perte);
    //                 $('#view_date_perte').text(date.toLocaleDateString('fr-FR'));
    //             } else {
    //                 $('#view_date_perte').text('-');
    //             }
                
    //             // Status badge
    //             const statusBadges = {
    //                 'En attente': '<span class="badge bg-warning text-dark"><i class="fa-solid fa-clock"></i> En attente</span>',
    //                 'Validé': '<span class="badge bg-success"><i class="fa-solid fa-check"></i> Validé</span>',
    //                 'Refusé': '<span class="badge bg-danger"><i class="fa-solid fa-times"></i> Refusé</span>'
    //             };
    //             $('#view_status').html(statusBadges[response.status] || response.status);
                
    //             $('#view_cause').text(response.cause || '-');
                
    //             // Show/hide refusal reason
    //             if (response.status === 'Refusé' && response.refusal_reason) {
    //                 $('#view_refusal_reason').text(response.refusal_reason);
    //                 $('#view_refusal_reason_row').show();
    //             } else {
    //                 $('#view_refusal_reason_row').hide();
    //             }
                
    //             $('#view_user').text(response.user ? (response.user.prenom + ' ' + response.user.nom) : '-');
                
    //             // Format created_at
    //             if (response.created_at) {
    //                 const createdDate = new Date(response.created_at);
    //                 $('#view_created_at').text(createdDate.toLocaleDateString('fr-FR') + ' ' + createdDate.toLocaleTimeString('fr-FR', {hour: '2-digit', minute:'2-digit'}));
    //             } else {
    //                 $('#view_created_at').text('-');
    //             }
                
    //             $('#ModalViewPerte').modal('show');
    //         },
    //         error: function(xhr, status, error) {
    //             console.error("Error fetching perte details:", error);
                
    //             if (xhr.status === 403) {
    //                 new AWN().alert("Vous n'avez pas la permission de voir cette perte", { durations: { alert: 5000 } });
    //             } else {
    //                 new AWN().alert("Erreur lors du chargement des détails", { durations: { alert: 5000 } });
    //             }
    //         }
    //     });
    // });

    // Edit Perte Status Handler - Show Modal
    $('.TablePertes tbody').on('click', '.edit-perte-btn', function(e) {
        e.preventDefault();
        
        var perteId = $(this).attr('data-id');
        
        // Fetch perte data for editing
        $.ajax({
            type: "GET",
            url: viewPerte_url + "/" + perteId,
            dataType: "json",
            success: function(response) {
                if (response.status === 403) {
                    new AWN().alert("Vous n'avez pas la permission de modifier cette perte.", {durations: {alert: 5000}});
                    return;
                }
                
                // Populate the edit modal
                $('#edit_perte_id').val(response.id);
                $('#edit_perte_status').val(response.status);
                
                // Show/hide refusal reason field based on current status
                togglePerteRefusalReasonField(response.status);
                
                // If status is already 'Refusé', populate the refusal reason
                if (response.status === 'Refusé' && response.refusal_reason) {
                    $('#edit_perte_refusal_reason').val(response.refusal_reason);
                }
                
                $('#editPerteModal').modal('show');
            },
            error: function(xhr, status, error) {
                console.error('Error fetching perte data:', xhr.responseText);
                
                try {
                    var errorResponse = JSON.parse(xhr.responseText);
                    if (errorResponse.status === 403) {
                        new AWN().alert("Vous n'avez pas la permission de modifier cette perte.", {durations: {alert: 5000}});
                    } else {
                        new AWN().alert("Erreur lors du chargement des données", {durations: {alert: 5000}});
                    }
                } catch(e) {
                    new AWN().alert("Erreur lors du chargement des données", {durations: {alert: 5000}});
                }
            }
        });
    });

    // Handle status change in edit modal
    $('#edit_perte_status').on('change', function() {
        var selectedStatus = $(this).val();
        togglePerteRefusalReasonField(selectedStatus);
    });

    // Function to show/hide refusal reason field
    function togglePerteRefusalReasonField(status) {
        if (status === 'Refusé') {
            $('#perte_refusal_reason_group').show();
            $('#edit_perte_refusal_reason').attr('required', true);
        } else {
            $('#perte_refusal_reason_group').hide();
            $('#edit_perte_refusal_reason').attr('required', false);
            $('#edit_perte_refusal_reason').val(''); // Clear the field
        }
    }

    // Handle edit form submission
    $('#editPerteForm').on('submit', function(e) {
        e.preventDefault();
        
        var formData = {
            id: $('#edit_perte_id').val(),
            status: $('#edit_perte_status').val(),
            refusal_reason: $('#edit_perte_refusal_reason').val(),
            _token: csrf_token
        };
        
        // Validate refusal reason if status is 'Refusé'
        if (formData.status === 'Refusé' && !formData.refusal_reason.trim()) {
            new AWN().alert("Le motif de refus est requis pour le statut 'Refusé'", {durations: {alert: 5000}});
            return;
        }
        
        $('#editPerteForm button[type="submit"]').prop('disabled', true).text('Mise à jour...');
        
        $.ajax({
            type: "POST",
            url: changeStatusPerte_url,
            data: formData,
            dataType: "json",
            success: function(response) {
                $('#editPerteForm button[type="submit"]').prop('disabled', false).text('Mettre à jour');
                
                if (response.status == 200) {
                    new AWN().success(response.message, {durations: {success: 5000}});
                    $('#editPerteModal').modal('hide');
                    // Reload the DataTable
                    $('.TablePertes').DataTable().ajax.reload();
                } else if (response.status == 400) {
                    // Handle validation errors
                    $('#edit_perte_status_error').text('');
                    if (response.errors && response.errors.status) {
                        $('#edit_perte_status_error').text(response.errors.status[0]);
                    }
                    new AWN().alert("Erreur de validation", {durations: {alert: 5000}});
                } else {
                    new AWN().alert(response.message || "Une erreur est survenue", {durations: {alert: 5000}});
                }
            },
            error: function(xhr, status, error) {
                $('#editPerteForm button[type="submit"]').prop('disabled', false).text('Mettre à jour');
                
                console.error('Error updating perte:', xhr.responseText);
                
                try {
                    var errorResponse = JSON.parse(xhr.responseText);
                    if (errorResponse.status === 403) {
                        new AWN().alert("Vous n'avez pas la permission de modifier cette perte.", {durations: {alert: 5000}});
                    } else {
                        new AWN().alert(errorResponse.message || "Erreur lors de la mise à jour", {durations: {alert: 5000}});
                    }
                } catch(e) {
                    new AWN().alert("Erreur lors de la mise à jour", {durations: {alert: 5000}});
                }
            }
        });
    });
    
    // Reset modal when it's hidden
    $('#editPerteModal').on('hidden.bs.modal', function() {
        $('#editPerteForm')[0].reset();
        $('#edit_perte_status_error').text('');
        $('#perte_refusal_reason_group').hide();
        $('#edit_perte_refusal_reason').attr('required', false);
    });

    // Delete Perte Handler
    $('.TablePertes tbody').on('click', '.deletePerte', function(e) {
        e.preventDefault();
        var perteId = $(this).attr('data-id');
        let notifier = new AWN();

        let onOk = () => {
            $.ajax({
                type: "POST",
                url: deletePerte_url,
                data: {
                    id: perteId,
                    _token: csrf_token,
                    _method: 'DELETE'
                },
                dataType: "json",
                success: function (response) {
                    if(response.status == 200) {
                        notifier.success(response.message, {durations: {success: 5000}});
                        $('.TablePertes').DataTable().ajax.reload();
                    } else if (response.status == 400) {
                        notifier.alert(response.message, {durations: {alert: 5000}});
                    } else {
                        notifier.alert(response.message || "Une erreur est survenue", {durations: {alert: 5000}});
                    }
                },
                error: function(xhr) {
                    if (xhr.status === 403) {
                        notifier.alert("Vous n'avez pas la permission de supprimer des pertes", { durations: { alert: 5000 } });
                    } else if (xhr.responseJSON && xhr.responseJSON.message) {
                        notifier.alert(xhr.responseJSON.message, { durations: { alert: 5000 } });
                    } else {
                        notifier.alert("Erreur lors de la suppression", { durations: { alert: 5000 } });
                    }
                }
            });
        };

        let onCancel = () => {
            notifier.info('Suppression annulée');
        };

        notifier.confirm(
            'Voulez-vous vraiment supprimer cette perte ?',
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

});