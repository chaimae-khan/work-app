$(document).ready(function () 
{
    var idCommandeStatic = 0;
    
    // Define the routes needed for edit, update, and change status
    var EditRouter = "router/edit";
    var UpdateRouter = "router/update-status";
    var ChangeStatusRouter = "router/change-status";
    var UpdateQteRouterTmp = "UpdateQteRouterTmp";
    var DeleteRouter = "router/delete";
    
    // Keep track of AJAX requests in progress to prevent duplicate submissions
    let ajaxInProgress = {
        deleteRouter: false,
        updateRouter: false,
        changeStatusRouter: false,
        deleteRowTmp: false,
        updateQteTmp: false,
        saveRouter: false
    };
    
    // Initialize the main table with AJAX
    $('.TableRouter').DataTable({
        processing: true,
        serverSide: true,
        autoWidth: false,
        ajax: {
            url: window.location.href,
            type: 'GET'
        },
        columns: [
            { 
                data: 'reference',
                name: 'reference',
                orderable: false
            },
            { 
                data: 'product_count',
                name: 'product_count',
                orderable: true,
                render: function(data) {
                    return data || '0';
                }
            },
            { 
                data: 'total_quantity',
                name: 'total_quantity',
                orderable: true,
                render: function(data) {
                    return data || '0';
                }
            },
            { 
                data: 'status',
                name: 'status',
                orderable: true,
                searchable: false
            },
            { 
                data: 'to_name',
                name: 'to_name',
                orderable: true
            },
            { 
                data: 'created_by_name',
                name: 'created_by_name',
                orderable: true
            },
            { 
                data: 'created_at',
                name: 'created_at',
                orderable: true
            },
            { 
                data: 'action',
                name: 'action',
                orderable: false,
                searchable: false
            }
        ],
        order: [[6, 'desc']], // Order by created_at descending
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
        drawCallback: function() {
            // Reset AJAX in progress flags when table is redrawn
            ajaxInProgress.deleteRouter = false;
            ajaxInProgress.updateRouter = false;
            ajaxInProgress.changeStatusRouter = false;
        }
    });

    // Helper function to escape HTML
    function escapeHtml(text) {
        var map = {
            '&': '&amp;',
            '<': '&lt;',
            '>': '&gt;',
            '"': '&quot;',
            "'": '&#039;'
        };
        return text.replace(/[&<>"']/g, function(m) { return map[m]; });
    }

    // Delete Router functionality
    $('.TableRouter tbody').on('click', '.DeleteRouter', function(e) {
        e.preventDefault();

        // If already processing a delete request, ignore this click
        if (ajaxInProgress.deleteRouter) {
            return;
        }

        var routerId = $(this).attr('data-id');
        let notifier = new AWN();
        let deleteButton = $(this);

        // Show confirmation dialog directly
        let onOk = () => {
            // Mark delete operation as in progress and disable the button
            ajaxInProgress.deleteRouter = true;
            deleteButton.prop('disabled', true).html('<i class="fa-solid fa-spinner fa-spin text-danger"></i>');

            // Perform delete AJAX
            $.ajax({
                type: "POST",
                url: DeleteRouter,
                data: {
                    id: routerId,
                    _token: csrf_token,
                    _method: 'DELETE'
                },
                dataType: "json",
                success: function(response) {
                    // Mark delete operation as complete
                    ajaxInProgress.deleteRouter = false;

                    if (response.status == 200) {
                        new AWN().success(response.message, { durations: { success: 5000 } });
                        // Reload the DataTable
                        $('.TableRouter').DataTable().ajax.reload();
                    } else if (response.status == 400 || response.status == 404) {
                        deleteButton.prop('disabled', false).html('<i class="fa-solid fa-trash text-danger"></i>');
                        new AWN().warning(response.message, { durations: { warning: 5000 } });
                    } else if (response.status == 403) {
                        deleteButton.prop('disabled', false).html('<i class="fa-solid fa-trash text-danger"></i>');
                        new AWN().alert("Vous n'avez pas la permission de supprimer ce retour.", { durations: { alert: 5000 } });
                    } else {
                        deleteButton.prop('disabled', false).html('<i class="fa-solid fa-trash text-danger"></i>');
                        new AWN().alert(response.message || "Une erreur est survenue", { durations: { alert: 5000 } });
                    }
                },
                error: function(xhr, status, error) {
                    // Mark delete operation as complete and re-enable button
                    ajaxInProgress.deleteRouter = false;
                    deleteButton.prop('disabled', false).html('<i class="fa-solid fa-trash text-danger"></i>');

                    console.error('AJAX error:', status, error);
                    console.error('Response:', xhr.responseText);

                    try {
                        var errorResponse = JSON.parse(xhr.responseText);
                        new AWN().alert(errorResponse.message || "Une erreur est survenue", { durations: { alert: 5000 } });
                    } catch(e) {
                        new AWN().alert("Une erreur est survenue, veuillez réessayer.", { durations: { alert: 5000 } });
                    }
                }
            });
        };

        let onCancel = () => {
            notifier.info('Annulation de la suppression');
        };

        notifier.confirm(
            'Êtes-vous sûr de vouloir supprimer ce retour ?',
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
    
    // Rest of the code remains the same (TableTmpRouter functions, modal handlers, etc.)
    let activeDataTablesTableTmpStockTransfer = {
        tmpStockTransfer: null
    };
    
    function initializeTableTmpStockTransfer(selector, Formateur, idCommande) {
        // Properly destroy DataTable if it exists
        if (activeDataTablesTableTmpStockTransfer.tmpStockTransfer) {
            activeDataTablesTableTmpStockTransfer.tmpStockTransfer.destroy();
            activeDataTablesTableTmpStockTransfer.tmpStockTransfer = null;
        }
    
        // Reinitialize DataTable
        activeDataTablesTableTmpStockTransfer.tmpStockTransfer = $(selector).DataTable({
            select: true,
            processing: true,
            serverSide: true,
            destroy: true,
            autoWidth: false,
            ajax: {
                url: GetTmpStockTransferByFormateur,
                data: { 
                    Formateur: Formateur,
                    IdCommande: idCommande
                },
                dataType: 'json',
                type: 'GET',
                error: function(xhr, error, code) {
                    console.error('Error occurred: ' + error);
                }
            },
            columns: [
                { data: 'name_product', title: 'Produit' },
                { data: 'code_article', title: 'Code article' },
                { data: 'quantite_stock', title: 'Quantité stock' },
                { data: 'quantite_transfer', title: 'Quantité à retourner' },
                { data: 'to_name', title: 'Demandeur' },
                { 
                    data: null, 
                    render: function(data, type, row) {
                        let btn = '';
                        // Add edit button
                        btn += '<a href="#" class="btn btn-sm bg-primary-subtle me-1 EditTmp" data-id="' + row.id + '">' +
                               '<i class="fa-solid fa-pen-to-square text-primary"></i></a>';
                        // Add delete button
                        btn += '<a href="#" class="btn btn-sm bg-danger-subtle DeleteTmp" data-id="' + row.id + 
                               '" data-bs-toggle="tooltip" title="Supprimer"><i class="fa-solid fa-trash text-danger"></i></a>';
                        return btn;
                    },
                    title: 'Action', 
                    orderable: false, 
                    searchable: false 
                }
            ],
            rowCallback: function(row, data, index) {
                $(row).attr('id', data.id);
            },
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

        // Handle edit quantity
        $(selector + ' tbody').off('click', '.EditTmp');
        $(selector + ' tbody').on('click', '.EditTmp', function(e) {
            e.preventDefault();
            
            let IdTmp = $(this).attr('data-id');
            let Qtetmp = $(this).closest('tr').find('td:eq(3)').text();
            $('#ModalEditQteRouterTmp').modal('show');
            $('#BtnUpdateQteRouterTmp').attr('data-id', IdTmp); 
            $('#QteRouterTmp').val(Qtetmp);   
        });

        // Handle delete with AJAX protection
        $(selector + ' tbody').off('click', '.DeleteTmp');
        $(selector + ' tbody').on('click', '.DeleteTmp', function(e) {
            e.preventDefault();
            
            // If already processing a delete request, ignore this click
            if (ajaxInProgress.deleteRowTmp) {
                return;
            }
            
            let IdTmp = $(this).attr('data-id');
            let formateur = $('#Formateur').val();
            let deleteButton = $(this);
            
            // Mark delete operation as in progress and disable the button
            ajaxInProgress.deleteRowTmp = true;
            deleteButton.prop('disabled', true).html('<i class="fa-solid fa-spinner fa-spin text-danger"></i>');
            
            $.ajax({
                type: "POST",
                url: DeleteRowsTmpStockTransfer,
                data: {
                    '_token': csrf_token,
                    'id': IdTmp
                },
                dataType: "json",
                success: function(response) {
                    // Mark delete operation as complete
                    ajaxInProgress.deleteRowTmp = false;
                    
                    if(response.status == 200) {
                        new AWN().success(response.message, {durations: {success: 5000}});
                        initializeTableTmpStockTransfer('.TableTmpRouter', formateur, idCommandeStatic);
                    } else {
                        // Re-enable button for errors
                        deleteButton.prop('disabled', false).html('<i class="fa-solid fa-trash text-danger"></i>');
                        new AWN().alert(response.message || "Une erreur est survenue", {durations: {alert: 5000}});
                    }
                },
                error: function(xhr, status, error) {
                    // Mark delete operation as complete and restore button
                    ajaxInProgress.deleteRowTmp = false;
                    deleteButton.prop('disabled', false).html('<i class="fa-solid fa-trash text-danger"></i>');
                    
                    console.error("Error deleting tmp item:", error);
                    new AWN().alert("Erreur lors de la suppression", {durations: {alert: 5000}});
                }
            });
        });
        
        return activeDataTablesTableTmpStockTransfer.tmpStockTransfer;
    }
    
    let activeDataTables = {
        tmpAchat: null,
        productSearch: null
    };
   
    function initializeTableCommandeByFormateurSend(selector, data) {
        // Properly destroy DataTable if it exists
        if (activeDataTables.productSearch) {
            activeDataTables.productSearch.destroy();
            activeDataTables.productSearch = null;
        }
    
        // Initialize DataTable
        activeDataTables.productSearch = $(selector).DataTable({
            select: true,
            data: data,
            destroy: true,
            processing: true,
            serverSide: false,
            autoWidth: false, 
            columns: [
                { data: 'matricule', title: 'Numéro commande' },
                { data: 'type_commande', title: 'Type commande' },
                { data: 'status', title: 'Statut' },
                { data: 'name', title: 'Créer par' },
                { data: 'created_at', title: 'Créer le' }
            ],
            rowCallback: function(row, data, index) {
                $(row).attr('id', data.id); 
            },
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
    
        // Remove any existing event handlers before adding new ones
        $(selector + ' tbody').off('click', 'tr');
        $(selector + ' tbody').on('click', 'tr', function(e) {
            e.preventDefault();
            
            let data = activeDataTables.productSearch.row(this).data();
            let idCommande = data.id;
            idCommandeStatic = idCommande;
            let formateur = $('#Formateur').val();
            
            $.ajax({
                type: "get",
                url: GetLigneCommandeByCommand,
                data: {
                    id: idCommande
                },
                dataType: "json",
                success: function(response) {
                    if(response.status == 200) {
                        $('.DivContentForCommande').fadeOut(300, function() {
                            $('.DivContentForCommandeLigne').fadeIn(300);
                            $('.linkListCommand').fadeIn(300);
                           
                            // Ensure formateur is selected
                            if (formateur != 0 && formateur !== '' && formateur !== null) {
                                // Initialize tables
                                initializeTableTmpStockTransfer('.TableTmpRouter', formateur, idCommande);
                            }
                            initializeTableCommandeLigneByFormateurSend('.TableCommandeLigneByformateur', response.data);
                        });
                    }
                }
            });
        });
        
        return activeDataTables.productSearch;
    }
    
    $('.linkListCommand').on('click', function(e) {
        e.preventDefault();
       
        $('.DivContentForCommandeLigne').fadeOut(300, function() {
            $('.DivContentForCommande').fadeIn(300);
        });
        $(this).attr('style', 'display: none !important');
    });

    let activeDataTablesCommandeByLigne = {
        productSearchCommandeLigne: null
    };
    
    function initializeTableCommandeLigneByFormateurSend(selector, data) {
        // Properly destroy DataTable if it exists
        if (activeDataTablesCommandeByLigne.productSearchCommandeLigne) {
            activeDataTablesCommandeByLigne.productSearchCommandeLigne.destroy();
            activeDataTablesCommandeByLigne.productSearchCommandeLigne = null;
        }
    
        // Initialize DataTable
        activeDataTablesCommandeByLigne.productSearchCommandeLigne = $(selector).DataTable({
            select: true,
            data: data,
            destroy: true,
            processing: true,
            serverSide: false,
            autoWidth: false, 
            columns: [
                { data: 'id', title: 'ID' },
                { data: 'idvente', title: 'idvente' },
                { data: 'name', title: 'Produit' },
                { data: 'code_article', title: 'Code article' },
                { data: 'contete_formateur', title: 'Quantité' },
                { data: 'seuil', title: 'Seuil' }
            ],
            columnDefs: [
                {
                    targets: 0,
                    visible: false,
                    searchable: false
                },
                {
                    targets: 1,
                    visible: false,
                    searchable: false
                }
            ],
            rowCallback: function(row, data, index) {
                $(row).attr('id', data.id); 
            },
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
    
        // Remove any existing event handlers before adding new ones
        $(selector + ' tbody').off('click', 'tr');
        $(selector + ' tbody').on('click', 'tr', function(e) {
            e.preventDefault();
            let formateur = $('#Formateur').val();
            
            if(formateur == 0) {
                new AWN().alert('Je ne peux pas enregistrer le produit car vous n\'avez pas sélectionné le formateur.', {durations: {success: 5000}});
                return false;
            }
            
            let data = activeDataTablesCommandeByLigne.productSearchCommandeLigne.row(this).data();
            
            $.ajax({
                type: "post",
                url: StoreProductStockTransfer,
                data: {
                    data: data,
                    '_token': csrf_token,
                    'to': formateur,
                    'idcommande': idCommandeStatic
                },
                dataType: "json",
                success: function(response) {
                    if (response.status == 200) {
                        new AWN().success(response.message, {durations: {success: 5000}});
                        
                        if (formateur != 0 && formateur !== '' && formateur !== null) {
                            initializeTableTmpStockTransfer('.TableTmpRouter', formateur, idCommandeStatic);
                        }
                    } 
                    else if(response.status == 403) {
                        new AWN().alert("Vous n'avez pas la permission d'ajouter un retour.", {durations: {alert: 5000}});
                    }
                    else if(response.status == 440) {
                        new AWN().alert(response.message, {durations: {alert: 5000}});
                    }
                    else {
                        new AWN().alert(response.message || 'Une erreur est survenue', {durations: {alert: 5000}});
                    }
                },
                error: function(xhr, status, error) {
                    console.error("Error adding product:", error);
                    
                    try {
                        var errorResponse = JSON.parse(xhr.responseText);
                        if (errorResponse.status === 403) {
                            new AWN().alert("Vous n'avez pas la permission d'ajouter un retour.", {durations: {alert: 5000}});
                        } else {
                            new AWN().alert("Impossible d'ajouter le produit", {durations: {alert: 5000}});
                        }
                    } catch(e) {
                        new AWN().alert("Impossible d'ajouter le produit", {durations: {alert: 5000}});
                    }
                }
            });
        });
        
        return activeDataTablesCommandeByLigne.productSearchCommandeLigne;
    }
    
    $('#Formateur').on('change', function(e) {
        e.preventDefault();
        let IdFormateurSend = $(this).val();
        
        $.ajax({
            type: "get",
            url: getFormateurCommands,
            data: {
                id: IdFormateurSend
            },
            dataType: "json",
            success: function(response) {
                if(response.status == 200) {
                    initializeTableCommandeByFormateurSend('.TableCommandeByformateur', response.dataCommandeByFormateurSend);
                }
            }
        }); 
    });

    // Update quantity in router tmp with AJAX protection
    $('#BtnUpdateQteRouterTmp').off('click').on('click', function(e) {
        e.preventDefault();
        
        // If already processing an update request, ignore this click
        if (ajaxInProgress.updateQteTmp) {
            return;
        }
        
        let Qte = $('#QteRouterTmp').val();
        let id = $(this).attr('data-id');
        
        if(Qte <= 0) {
            new AWN().alert("La quantité doit être supérieure à zéro", {durations: {alert: 5000}});
            return false;
        }
        
        // Mark update operation as in progress and disable the button
        ajaxInProgress.updateQteTmp = true;
        $('#BtnUpdateQteRouterTmp').prop('disabled', true).text('Enregistrement...');
        
        $.ajax({
            type: "POST",
            url: UpdateQteRouterTmp,
            data: {
                '_token': csrf_token,
                'qte': Qte,
                'id': id,
            },
            dataType: "json",
            success: function(response) {
                // Mark update operation as complete
                ajaxInProgress.updateQteTmp = false;
                $('#BtnUpdateQteRouterTmp').prop('disabled', false).text('Sauvegarder');
                
                if(response.status == 200) {
                    new AWN().success(response.message, {durations: {success: 5000}});
                    let formateur = $('#Formateur').val();
                    initializeTableTmpStockTransfer('.TableTmpRouter', formateur, idCommandeStatic);
                    $('#ModalEditQteRouterTmp').modal('hide');
                }
                else if(response.status == 400) {
                    $('.validationUpdateQteRouterTmp').html("");
                    $('.validationUpdateQteRouterTmp').addClass('alert alert-danger');
                    $.each(response.errors, function(key, list_err) {
                        $('.validationUpdateQteRouterTmp').append('<li>' + list_err + '</li>');
                    });
                } else {
                    new AWN().alert("Impossible de modifier la quantité", {durations: {alert: 5000}});
                }
            },
            error: function(xhr, status, error) {
                // Mark update operation as complete and re-enable button
                ajaxInProgress.updateQteTmp = false;
                $('#BtnUpdateQteRouterTmp').prop('disabled', false).text('Sauvegarder');
                
                console.error("Error:", xhr.responseText);
                new AWN().alert("Impossible de modifier la quantité", {durations: {alert: 5000}});
            }
        });
    });

    $('#BtnRouteToStock').on('click', function(e) {
        e.preventDefault();
        
        // If already processing a save request, ignore this click
        if (ajaxInProgress.saveRouter) {
            return;
        }
        
        // Check if there are items to transfer
        if ($('.TableTmpRouter tbody tr').length === 0) {
            new AWN().alert('Aucun produit à router. Veuillez ajouter des produits.', {durations: {alert: 5000}});
            return;
        }
        
        // Check if formateur is selected
        if ($('#Formateur').val() == 0) {
            new AWN().alert('Veuillez sélectionner le formateur.', {durations: {alert: 5000}});
            return;
        }
        
        // Mark save operation as in progress and disable the button
        ajaxInProgress.saveRouter = true;
        $('#BtnRouteToStock').prop('disabled', true).text('Enregistrement...');
        
        $.ajax({
            type: "POST",
            url: StoreRouter,
            data: {
                '_token': csrf_token,
                'to': $('#Formateur').val(),
                'idcommande': idCommandeStatic
            },
            dataType: "json",
            success: function(response) {
                // Mark save operation as complete and re-enable the button
                ajaxInProgress.saveRouter = false;
                $('#BtnRouteToStock').prop('disabled', false).text('Router vers le stock');
                
                if (response.status == 200) {
                    new AWN().success(response.message, {durations: {success: 5000}});
                    // Close the modal and reset the form
                    $('#ModalAddRouter').modal('hide');
                    
                    // Refresh the main transfer list
                    $('.TableRouter').DataTable().ajax.reload();
                } else if (response.status == 403) {
                    new AWN().alert("Vous n'avez pas la permission d'ajouter un retour.", {durations: {alert: 5000}});
                } else {
                   new AWN().alert(response.message || 'Une erreur est survenue', {durations: {alert: 5000}});
                }
            },
            error: function(xhr, status, error) {
                // Mark save operation as complete and re-enable the button
                ajaxInProgress.saveRouter = false;
                $('#BtnRouteToStock').prop('disabled', false).text('Router vers le stock');
                
                console.error("Error saving router:", error);
                
                try {
                    var errorResponse = JSON.parse(xhr.responseText);
                    if (errorResponse.status === 403) {
                        new AWN().alert("Vous n'avez pas la permission d'ajouter un retour.", {durations: {alert: 5000}});
                    } else {
                        new AWN().alert("Impossible de sauvegarder le retour", {durations: {alert: 5000}});
                    }
                } catch(e) {
                    new AWN().alert("Impossible de sauvegarder le retour", {durations: {alert: 5000}});
                }
            }
        });
    });

    // Handle edit button click
    $('.TableRouter tbody').on('click', '.edit-btn', function(e) {
        e.preventDefault();
        
        var routerId = $(this).attr('data-id');
        
        // Fetch router data for editing
        $.ajax({
            type: "GET",
            url: EditRouter + "/" + routerId,
            dataType: "json",
            success: function(response) {
                if (response.status === 403) {
                    new AWN().alert("Vous n'avez pas la permission de modifier ce retour.", {durations: {alert: 5000}});
                    return;
                }
                
                // Populate the edit modal
                $('#edit_id').val(response.id);
                $('#edit_status').val(response.status);
                
                // Show/hide refusal reason field based on current status
                toggleRefusalReasonField(response.status);
                
                // If status is already 'Refus', populate the refusal reason
                if (response.status === 'Refus' && response.refusal_reason) {
                    $('#edit_refusal_reason').val(response.refusal_reason);
                }
                
                $('#editRouterModal').modal('show');
            },
            error: function(xhr, status, error) {
                console.error('Error fetching router data:', xhr.responseText);
                
                try {
                    var errorResponse = JSON.parse(xhr.responseText);
                    if (errorResponse.status === 403) {
                        new AWN().alert("Vous n'avez pas la permission de modifier ce retour.", {durations: {alert: 5000}});
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
    $('#edit_status').on('change', function() {
        var selectedStatus = $(this).val();
        toggleRefusalReasonField(selectedStatus);
    });

    // Function to show/hide refusal reason field
    function toggleRefusalReasonField(status) {
        if (status === 'Refus') {
            $('#refusal_reason_group').show();
            $('#edit_refusal_reason').attr('required', true);
        } else {
            $('#refusal_reason_group').hide();
            $('#edit_refusal_reason').attr('required', false);
            $('#edit_refusal_reason').val(''); // Clear the field
        }
    }

    // Handle edit form submission
    $('#editRouterForm').on('submit', function(e) {
        e.preventDefault();
        
        // If already processing an update request, ignore this submission
        if (ajaxInProgress.updateRouter) {
            return;
        }
        
        var formData = {
            id: $('#edit_id').val(),
            status: $('#edit_status').val(),
            refusal_reason: $('#edit_refusal_reason').val(),
            _token: csrf_token
        };
        
        // Validate refusal reason if status is 'Refus'
        if (formData.status === 'Refus' && !formData.refusal_reason.trim()) {
            new AWN().alert("Le motif de refus est requis pour le statut 'Refus'", {durations: {alert: 5000}});
            return;
        }
        
        // Mark update operation as in progress
        ajaxInProgress.updateRouter = true;
        $('#editRouterForm button[type="submit"]').prop('disabled', true).text('Mise à jour...');
        
        $.ajax({
            type: "POST",
            url: UpdateRouter,
            data: formData,
            dataType: "json",
            success: function(response) {
                // Mark update operation as complete
                ajaxInProgress.updateRouter = false;
                $('#editRouterForm button[type="submit"]').prop('disabled', false).text('Mettre à jour');
                
                if (response.status == 200) {
                    new AWN().success(response.message, {durations: {success: 5000}});
                    $('#editRouterModal').modal('hide');
                    // Reload the DataTable
                    $('.TableRouter').DataTable().ajax.reload();
                } else if (response.status == 400) {
                    // Handle validation errors
                    $('#edit_status_error').text('');
                    if (response.errors && response.errors.status) {
                        $('#edit_status_error').text(response.errors.status[0]);
                    }
                    new AWN().alert("Erreur de validation", {durations: {alert: 5000}});
                } else {
                    new AWN().alert(response.message || "Une erreur est survenue", {durations: {alert: 5000}});
                }
            },
            error: function(xhr, status, error) {
                // Mark update operation as complete
                ajaxInProgress.updateRouter = false;
                $('#editRouterForm button[type="submit"]').prop('disabled', false).text('Mettre à jour');
                
                console.error('Error updating router:', xhr.responseText);
                
                try {
                    var errorResponse = JSON.parse(xhr.responseText);
                    if (errorResponse.status === 403) {
                        new AWN().alert("Vous n'avez pas la permission de modifier ce retour.", {durations: {alert: 5000}});
                    } else {
                        new AWN().alert("Erreur lors de la mise à jour", {durations: {alert: 5000}});
                    }
                } catch(e) {
                    new AWN().alert("Erreur lors de la mise à jour", {durations: {alert: 5000}});
                }
            }
        });
    });
    
    // Reset modal when it's hidden
    $('#editRouterModal').on('hidden.bs.modal', function() {
        $('#editRouterForm')[0].reset();
        $('#edit_status_error').text('');
        $('#refusal_reason_group').hide();
        $('#edit_refusal_reason').attr('required', false);
    });

});