$(document).ready(function () 
{
    var idCommandeStatic = 0;
    
    // Define the routes needed for edit, update, and change status
    var EditTransfer = "EditTransfer";
    var UpdateTransfer = "UpdateTransfer";
    var ChangeStatusTransfer = "ChangeStatusTransfer";
    var UpdateQteTmpTransfer = "UpdateQteTmpTransfer";
    var DeleteTransfer = "transfer/delete";
    
   
    let ajaxInProgress = {
        deleteTransfer: false,
        updateTransfer: false,
        changeStatusTransfer: false,
        deleteRowTmp: false,
        updateQteTmp: false,
        saveTransfer: false
    };
    

  $('.TableTransfer').DataTable({
    processing: true,
    serverSide: true,
    ajax: {
        url: window.location.href, 
        dataType: 'json'
    },
    autoWidth: false,
    order: [[7, 'desc']], 
    columns: [
        { 
            data: 'id',
            name: 'st.id',
            render: function(data, type, row) {
                return 'TRA-' + String(data).padStart(6, '0');
            }
        },
       
        { 
            data: 'total_quantity',
            name: 'total_quantity',
            searchable: false
        },
        { 
            data: 'status',
            name: 'st.status',
            render: function(data, type, row) {
                const status = data || 'Création';
                
                let statusHtml = '<span>' + status + '</span>';
                
                if (status === 'Refus' && row.refusal_reason && row.refusal_reason.trim() !== '') {
                    statusHtml += '<br><small class="text-muted mt-1 d-block">' +
                                 '<i class="fa-solid fa-info-circle me-1"></i>' + 
                                 escapeHtml(row.refusal_reason) + 
                                 '</small>';
                }
                
                return statusHtml;
            }
        },
        { 
            data: 'from_name',
            name: 'from_name',
            render: function(data, type, row) {
                return data || '<span class="text-muted">-</span>';
            }
        },
        { 
            data: 'to_name',
            name: 'to_name'
        },
        { 
            data: 'created_by_name',
            name: 'created_by_name'
        },
        { 
            data: 'created_at',
            name: 'st.created_at',
            render: function(data, type, row) {
                if (data) {
                    // Format the date to a readable format
                    var date = new Date(data);
                    return date.toLocaleDateString('fr-FR', {
                        year: 'numeric',
                        month: '2-digit',
                        day: '2-digit',
                        hour: '2-digit',
                        minute: '2-digit'
                    });
                }
                return '';
            }
        },
        { 
            data: 'action',
            name: 'action',
            orderable: false, 
            searchable: false
        }
    ],
    language: {
        "sInfo": "Affichage de l'élément _START_ à _END_ sur _TOTAL_ éléments",
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
        ajaxInProgress.deleteTransfer = false;
        ajaxInProgress.updateTransfer = false;
        ajaxInProgress.changeStatusTransfer = false;
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

    // ========================================================================================
    // DELETE TRANSFER HANDLER
    // ========================================================================================
    $(document).on('click', '.DeleteTransfer', function(e) {
        e.preventDefault();
        
        // If already processing a delete request, ignore this click
        if (ajaxInProgress.deleteTransfer) {
            return;
        }
        
        var transferId = $(this).attr('data-id');
        let deleteButton = $(this);
        
        let notifier = new AWN();
        
        let onOk = () => {
            // Mark delete operation as in progress and disable the button
            ajaxInProgress.deleteTransfer = true;
            deleteButton.prop('disabled', true).html('<i class="fa-solid fa-spinner fa-spin text-danger"></i>');
            
            $.ajax({
                type: "POST",
                url: DeleteTransfer,
                data: {
                    id: transferId,
                    _token: csrf_token,
                    _method: 'DELETE'
                },
                dataType: "json",
                success: function (response) {
                    ajaxInProgress.deleteTransfer = false;
                    
                    if(response.status == 200) {
                        new AWN().success(response.message, {durations: {success: 5000}});
                        $('.TableTransfer').DataTable().ajax.reload(null, false);
                    } else if(response.status == 400) {
                        deleteButton.prop('disabled', false).html('<i class="fa-solid fa-trash text-danger"></i>');
                        new AWN().warning(response.message, {durations: {warning: 5000}});
                    } else if(response.status == 404) {
                        deleteButton.prop('disabled', false).html('<i class="fa-solid fa-trash text-danger"></i>');
                        new AWN().warning(response.message, {durations: {warning: 5000}});
                    } else {
                        deleteButton.prop('disabled', false).html('<i class="fa-solid fa-trash text-danger"></i>');
                        new AWN().alert(response.message || "Une erreur est survenue", {durations: {alert: 5000}});
                    }
                },
                error: function(xhr, status, error) {
                    ajaxInProgress.deleteTransfer = false;
                    deleteButton.prop('disabled', false).html('<i class="fa-solid fa-trash text-danger"></i>');
                    new AWN().alert("Une erreur est survenue, veuillez réessayer.", { durations: { alert: 5000 } });
                }
            });
        };
        
        let onCancel = () => {
            console.log("Suppression annulée");
        };
        
        notifier.confirm(
            "Êtes-vous sûr de vouloir supprimer ce transfert ?",
            onOk,
            onCancel,
            {
                labels: {
                    confirm: "Supprimer ce transfert ?"
                }
            }
        );
    });

    // ========================================================================================
    // EDIT TRANSFER HANDLER
    // ========================================================================================
    $(document).on('click', '.edit-btn', function(e) {
        e.preventDefault();
        
        var transferId = $(this).attr('data-id');
        
        // Fetch transfer data for editing
        $.ajax({
            type: "GET",
            url: EditTransfer + "/" + transferId,
            dataType: "json",
            success: function(response) {
                if (response.status === 403) {
                    new AWN().alert("Vous n'avez pas la permission de modifier ce transfert.", {durations: {alert: 5000}});
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
                
                $('#editTransferModal').modal('show');
            },
            error: function(xhr, status, error) {
                console.error('Error fetching transfer data:', xhr.responseText);
                
                try {
                    var errorResponse = JSON.parse(xhr.responseText);
                    if (errorResponse.status === 403) {
                        new AWN().alert("Vous n'avez pas la permission de modifier ce transfert.", {durations: {alert: 5000}});
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
    $('#editTransferForm').on('submit', function(e) {
        e.preventDefault();
        
        // If already processing an update request, ignore this submission
        if (ajaxInProgress.updateTransfer) {
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
        ajaxInProgress.updateTransfer = true;
        $('#editTransferForm button[type="submit"]').prop('disabled', true).text('Mise à jour...');
        
        $.ajax({
            type: "POST",
            url: UpdateTransfer,
            data: formData,
            dataType: "json",
            success: function(response) {
                // Mark update operation as complete
                ajaxInProgress.updateTransfer = false;
                $('#editTransferForm button[type="submit"]').prop('disabled', false).text('Mettre à jour');
                
                if (response.status == 200) {
                    new AWN().success(response.message, {durations: {success: 5000}});
                    $('#editTransferModal').modal('hide');
                    // Reload the DataTable
                    $('.TableTransfer').DataTable().ajax.reload(null, false);
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
                ajaxInProgress.updateTransfer = false;
                $('#editTransferForm button[type="submit"]').prop('disabled', false).text('Mettre à jour');
                
                console.error('Error updating transfer:', xhr.responseText);
                
                try {
                    var errorResponse = JSON.parse(xhr.responseText);
                    if (errorResponse.status === 403) {
                        new AWN().alert("Vous n'avez pas la permission de modifier ce transfert.", {durations: {alert: 5000}});
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
    $('#editTransferModal').on('hidden.bs.modal', function() {
        $('#editTransferForm')[0].reset();
        $('#edit_status_error').text('');
        $('#refusal_reason_group').hide();
        $('#edit_refusal_reason').attr('required', false);
    });

    // ========================================================================================
    // TEMPORARY TRANSFER TABLE (FOR MODAL)
    // ========================================================================================
    let activeDataTablesTableTmpStockTransfer = {
        tmpStockTransfer: null
    };
    
    function initializeTableTmpStockTransfer(selector, From_Formateur, To_Formateur, idCommande) {
        // Properly destroy DataTable if it exists
        if (activeDataTablesTableTmpStockTransfer.tmpStockTransfer) {
            activeDataTablesTableTmpStockTransfer.tmpStockTransfer.destroy();
            activeDataTablesTableTmpStockTransfer.tmpStockTransfer = null;
        }
    
        // Reinitialize DataTable
        activeDataTablesTableTmpStockTransfer.tmpStockTransfer = $(selector).DataTable({
            select: true,
            processing: true,
            serverSide: false,
            destroy: true,
            autoWidth: false,
            ajax: {
                url: GetTmpStockTransferByTwoFormateur,
                data: { 
                    From_Formateur: From_Formateur,
                    To_Formateur: To_Formateur,
                    IdCommande: idCommande
                },
                dataType: 'json',
                type: 'GET',
                error: function(xhr, error, code) {
                    console.error('Error occurred: ' + error);
                    try {
                        var errorResponse = JSON.parse(xhr.responseText);
                        if(errorResponse.status == 403) {
                            new AWN().alert("Vous n'avez pas la permission", { durations: { alert: 5000 } });
                        }
                    } catch(e) {
                        console.error('Error parsing response:', e);
                    }
                }
            },
            columns: [
                { data: 'name_product', title: 'Produit' },
                { data: 'code_article', title: 'Code article' },
                { data: 'quantite_stock', title: 'Quantité stock' },
                { data: 'quantite_transfer', title: 'Quantité transfer' },
                { data: 'from', title: 'D\'un formateur' },
                { data: 'to', title: 'À formateur' },
                { 
                    data: null,
                    title: 'Action',
                    render: function(data, type, row) {
                        let btn = '';
                        
                        // Edit button
                        btn += '<a href="#" class="btn btn-sm bg-primary-subtle me-1 EditTmp" data-id="' + row.id + '">' +
                              '<i class="fa-solid fa-pen-to-square text-primary"></i>' +
                              '</a>';
                              
                        // Delete button
                        btn += '<a href="#" class="btn btn-sm bg-danger-subtle DeleteTmp" data-id="' + row.id + '" data-bs-toggle="tooltip" ' +
                              'title="Supprimer">' +
                              '<i class="fa-solid fa-trash text-danger"></i>' +
                              '</a>';
                              
                        return btn;
                    },
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
    
        // Add event handler for editing quantity
        $(selector + ' tbody').on('click', 'tr .EditTmp', function(e) {
            e.preventDefault();
            
            let IdTmp = $(this).attr('data-id');
            let Qtetmp = $(this).closest('tr').find('td:eq(3)').text(); // Index 3 is for quantite_transfer
            $('#ModalEditQteTmpTransfer').modal('show');
            $('#BtnUpdateQteTmpTransfer').attr('data-id', IdTmp); 
            $('#QteTmpTransfer').val(Qtetmp);   
        });
    
        $(selector + ' tbody').on('click', 'tr .DeleteTmp', function(e) {
            e.preventDefault();
            
            // If already processing a delete request, ignore this click
            if (ajaxInProgress.deleteRowTmp) {
                return;
            }
            
            let IdTmp = $(this).attr('data-id');
            let eFormateur = $('#E_Formateur').val();
            let aFormateur = $('#R_Formateur').val();
            let deleteButton = $(this);
            
            // Mark delete operation as in progress and disable the button
            ajaxInProgress.deleteRowTmp = true;
            deleteButton.prop('disabled', true).html('<i class="fa-solid fa-spinner fa-spin text-danger"></i>');
            
            $.ajax({
                type: "POST",
                url: DeleteRowsTmpStockTr,
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
                        initializeTableTmpStockTransfer('.TableTmpStockTransfer', eFormateur, aFormateur, idCommande);
                    } else if(response.status == 403) {
                        // Permission denied
                        deleteButton.prop('disabled', false).html('<i class="fa-solid fa-trash text-danger"></i>');
                        new AWN().alert("Vous n'avez pas la permission", {durations: {alert: 5000}});
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
                    try {
                        var errorResponse = JSON.parse(xhr.responseText);
                        if(errorResponse.status == 403) {
                            new AWN().alert("Vous n'avez pas la permission", { durations: { alert: 5000 } });
                        } else {
                            new AWN().alert("Erreur lors de la suppression", {durations: {alert: 5000}});
                        }
                    } catch(e) {
                        new AWN().alert("Erreur lors de la suppression", {durations: {alert: 5000}});
                    }
                }
            });
        });
        
        return activeDataTablesTableTmpStockTransfer.tmpStockTransfer;
    }
    
    // ========================================================================================
    // COMMAND SELECTION TABLE
    // ========================================================================================
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
            let eFormateur = $('#E_Formateur').val();
            let aFormateur = $('#R_Formateur').val();
            
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
                           
                            // Vérifier que les deux sont définies et différentes de 0
                            if (aFormateur != 0 && aFormateur !== '' && aFormateur !== null &&
                                eFormateur != 0 && eFormateur !== '' && eFormateur !== null) {
                                // Appel de la fonction avec les deux valeurs
                                initializeTableTmpStockTransfer('.TableTmpStockTransfer', eFormateur, aFormateur, idCommande);
                            }
                            initializeTableCommandeLigneByFormateurSend('.TableCommandeLigneByformateur', response.data);
                        });
                    } else if(response.status == 403) {
                        new AWN().alert("Vous n'avez pas la permission", {durations: {alert: 5000}});
                    }
                },
                error: function(xhr, status, error) {
                    try {
                        var errorResponse = JSON.parse(xhr.responseText);
                        if(errorResponse.status == 403) {
                            new AWN().alert("Vous n'avez pas la permission", { durations: { alert: 5000 } });
                        }
                    } catch(e) {
                        console.error("Error:", e);
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

    // ========================================================================================
    // COMMAND LINE SELECTION TABLE
    // ========================================================================================
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
            let value_A_formateur = $('#R_Formateur').val();
            let eFormateur = $('#E_Formateur').val();
            
            if(value_A_formateur == 0) {
                new AWN().alert('Je ne peux pas enregistrer le produit car vous n\'avez pas sélectionné le futur formateur.', {durations: {success: 5000}});
                return false;
            }
            
            let data = activeDataTablesCommandeByLigne.productSearchCommandeLigne.row(this).data();
            
            $.ajax({
                type: "post",
                url: StoreProductStockTr,
                data: {
                    data: data,
                    '_token': csrf_token,
                    'from': $('#E_Formateur').val(),
                    'to': $('#R_Formateur').val(),
                    'idcommande': idCommandeStatic
                },
                dataType: "json",
                success: function(response) {
                    if (response.status == 200) {
                        new AWN().success(response.message, {durations: {success: 5000}});
                        
                        // Vérifier que les deux sont définies et différentes de 0
                        if (value_A_formateur != 0 && value_A_formateur !== '' && value_A_formateur !== null &&
                            eFormateur != 0 && eFormateur !== '' && eFormateur !== null) {
                            // Appel de la fonction avec les deux valeurs
                            initializeTableTmpStockTransfer('.TableTmpStockTransfer', eFormateur, value_A_formateur, idCommandeStatic);
                        }
                    } 
                    else if(response.status == 440) {
                        new AWN().alert(response.message, {durations: {success: 5000}});
                    } 
                    else if(response.status == 403) {
                        new AWN().alert("Vous n'avez pas la permission", {durations: {alert: 5000}});
                    }
                    else {
                        new AWN().alert(response.message || 'Une erreur est survenue', {durations: {alert: 5000}});
                    }
                },
                error: function(xhr, status, error) {
                    console.error("Error adding product:", error);
                    try {
                        var errorResponse = JSON.parse(xhr.responseText);
                        if(errorResponse.status == 403) {
                            new AWN().alert("Vous n'avez pas la permission", { durations: { alert: 5000 } });
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
    
    // ========================================================================================
    // FORMATEUR SELECTION HANDLERS
    // ========================================================================================
    
    // Trigger E_Formateur change automatically when modal opens (since user is pre-selected)
    $('#ModalAddAchat').on('shown.bs.modal', function() {
        let eFormateurVal = $('#E_Formateur').val();
        if (eFormateurVal && eFormateurVal != 0) {
            $('#E_Formateur').trigger('change');
        }
    });
    
    $('#E_Formateur').on('change', function(e) {
        e.preventDefault();
        let IdFormateurSend = $(this).val();
        
        $.ajax({
            type: "get",
            url: getFormateurNotSelected,
            data: {
                id: IdFormateurSend
            },
            dataType: "json",
            success: function(response) {
                if(response.status == 200) {
                    // Don't clear R_Formateur if it's already populated from blade
                    // Only update if it's being dynamically loaded
                    
                    initializeTableCommandeByFormateurSend('.TableCommandeByformateur', response.dataCommandeByFormateurSend);
                } else if(response.status == 403) {
                    new AWN().alert("Vous n'avez pas la permission", {durations: {alert: 5000}});
                }
            },
            error: function(xhr, status, error) {
                try {
                    var errorResponse = JSON.parse(xhr.responseText);
                    if(errorResponse.status == 403) {
                        new AWN().alert("Vous n'avez pas la permission", { durations: { alert: 5000 } });
                    }
                } catch(e) {
                    console.error("Error:", e);
                }
            }
        }); 
    });

    // ========================================================================================
    // UPDATE QUANTITY HANDLER
    // ========================================================================================
    $('#BtnUpdateQteTmpTransfer').off('click').on('click', function(e) {
        e.preventDefault();
        
        // If already processing an update request, ignore this click
        if (ajaxInProgress.updateQteTmp) {
            return;
        }
        
        let Qte = $('#QteTmpTransfer').val();
        let id = $(this).attr('data-id');
        
        if(Qte <= 0) {
            new AWN().alert("La quantité doit être supérieure à zéro", {durations: {alert: 5000}});
            return false;
        }
        
        // Mark update operation as in progress and disable the button
        ajaxInProgress.updateQteTmp = true;
        $('#BtnUpdateQteTmpTransfer').prop('disabled', true).text('Enregistrement...');
        
        $.ajax({
            type: "POST",
            url: UpdateQteTmpTransfer,
            data: {
                '_token': csrf_token,
                'qte': Qte,
                'id': id,
            },
            dataType: "json",
            success: function(response) {
                // Mark update operation as complete
                ajaxInProgress.updateQteTmp = false;
                $('#BtnUpdateQteTmpTransfer').prop('disabled', false).text('Sauvegarder');
                
                if(response.status == 200) {
                    new AWN().success(response.message, {durations: {success: 5000}});
                    let eFormateur = $('#E_Formateur').val();
                    let aFormateur = $('#R_Formateur').val();
                    initializeTableTmpStockTransfer('.TableTmpStockTransfer', eFormateur, aFormateur, idCommandeStatic);
                    $('#ModalEditQteTmpTransfer').modal('hide');
                }
                else if(response.status == 400) {
                    $('.validationUpdateQteTmpTransfer').html("");
                    $('.validationUpdateQteTmpTransfer').addClass('alert alert-danger');
                    $.each(response.errors, function(key, list_err) {
                        $('.validationUpdateQteTmpTransfer').append('<li>' + list_err + '</li>');
                    });
                }
                else if(response.status == 403) {
                    new AWN().alert("Vous n'avez pas la permission", {durations: {alert: 5000}});
                }
                else {
                    new AWN().alert("Impossible de modifier la quantité", {durations: {alert: 5000}});
                }
            },
            error: function(xhr, status, error) {
                // Mark update operation as complete and re-enable button
                ajaxInProgress.updateQteTmp = false;
                $('#BtnUpdateQteTmpTransfer').prop('disabled', false).text('Sauvegarder');
               
                console.error("Error:", xhr.responseText);
                try {
                    var errorResponse = JSON.parse(xhr.responseText);
                    if(errorResponse.status == 403) {
                        new AWN().alert("Vous n'avez pas la permission", { durations: { alert: 5000 } });
                    } else {
                        new AWN().alert("Impossible de modifier la quantité", {durations: {alert: 5000}});
                    }
                } catch(e) {
                    new AWN().alert("Impossible de modifier la quantité", {durations: {alert: 5000}});
                }
            }
        });
    });

    // ========================================================================================
    // SAVE TRANSFER HANDLER
    // ========================================================================================
    $('#BtnSaveTransfer').on('click', function(e) {
        e.preventDefault();
        
        // If already processing a save request, ignore this click
        if (ajaxInProgress.saveTransfer) {
            return;
        }
        
        // Check if there are items to transfer
        if ($('.TableTmpStockTransfer tbody tr').length === 0 || 
            $('.TableTmpStockTransfer tbody tr').find('td.dataTables_empty').length > 0) {
            new AWN().alert('Aucun produit à transférer. Veuillez ajouter des produits.', {durations: {alert: 5000}});
            return;
        }
        
        // Check if both formatters are selected
        if ($('#E_Formateur').val() == 0 || $('#R_Formateur').val() == 0) {
            new AWN().alert('Veuillez sélectionner les deux formateurs.', {durations: {alert: 5000}});
            return;
        }
        
        // Mark save operation as in progress and disable the button
        ajaxInProgress.saveTransfer = true;
        $('#BtnSaveTransfer').prop('disabled', true).text('Enregistrement...');
        
        $.ajax({
            type: "POST",
            url: StoreTransfer,
            data: {
                '_token': csrf_token,
                'from': $('#E_Formateur').val(),
                'to': $('#R_Formateur').val(),
                'idcommande': idCommandeStatic
            },
            dataType: "json",
            success: function(response) {
                // Mark save operation as complete and re-enable the button
                ajaxInProgress.saveTransfer = false;
                $('#BtnSaveTransfer').prop('disabled', false).text('Sauvegarder');
                
                if (response.status == 200) {
                    new AWN().success(response.message, {durations: {success: 5000}});
                    // Close the modal and reset the form
                    $('#ModalAddAchat').modal('hide');
                    
                    // Refresh the main transfer list using DataTables AJAX reload
                    $('.TableTransfer').DataTable().ajax.reload(null, false);
                } else if(response.status == 403) {
                    new AWN().alert("Vous n'avez pas la permission", {durations: {alert: 5000}});
                } else {
                    new AWN().alert(response.message || 'Une erreur est survenue', {durations: {alert: 5000}});
                }
            },
            error: function(xhr, status, error) {
                // Mark save operation as complete and re-enable the button
                ajaxInProgress.saveTransfer = false;
                $('#BtnSaveTransfer').prop('disabled', false).text('Sauvegarder');
                
                console.error("Error saving transfer:", error);
                try {
                    var errorResponse = JSON.parse(xhr.responseText);
                    if(errorResponse.status == 403) {
                        new AWN().alert("Vous n'avez pas la permission", { durations: { alert: 5000 } });
                    } else {
                        new AWN().alert("Une erreur est survenue lors de l'enregistrement du transfert", {durations: {alert: 5000}});
                    }
                } catch(e) {
                    new AWN().alert("Une erreur est survenue lors de l'enregistrement du transfert", {durations: {alert: 5000}});
                }
            }
        });
    });

});