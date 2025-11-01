$(document).ready(function () {
    let selectedPlatId = 0;
    let nombreCouvert = 1;
    let activeDataTables = {
        tmpPlat: null,
        productSearch: null,
        tmpPlatEdit: null,
        productSearchEdit: null
    };

    // Initialize main datatable
    function initializeTablePlatComposition() {
        try {
            if ($.fn.DataTable.isDataTable('.TablePlatComposition')) {
                $('.TablePlatComposition').DataTable().destroy();
            }
            
            var TablePlatComposition = $('.TablePlatComposition').DataTable({
                processing: true,
                serverSide: true,
                ajax: {
                    url: PlatComposition,
                    error: function(xhr, error, thrown) {
                        console.error('DataTables error:', error, thrown);
                    }
                },
                columns: [
                    { data: 'name', name: 'name' },
                    { data: 'type', name: 'type' },
                    { data: 'created_by', name: 'created_by' },
                    { data: 'created_at', name: 'created_at' },
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

            // Edit handler
            $('.TablePlatComposition tbody').on('click', '.editPlatComposition', function(e) {
                e.preventDefault();
                var platId = $(this).attr('data-id');
                
                $.ajax({
                    type: "GET",
                    url: EditPlatComposition + "/" + platId,
                    dataType: "json",
                    success: function(response) {
                        if (response.status === 200) {
                            $('#ModalEditPlatComposition').modal("show");
                            $('#edit_id_plat').val(response.plat.id);
                            $('#edit_plat_name').val(response.plat.name);
                            selectedPlatId = response.plat.id;
                            
                            // Load temp data
                            initializeTableTmpPlat('.TableTmpPlatEdit', selectedPlatId, true);
                        }
                    },
                    error: function(xhr) {
                        new AWN().alert("Erreur lors du chargement", { durations: { alert: 5000 } });
                    }
                });
            });

            // Delete handler
            $('.TablePlatComposition tbody').on('click', '.deletePlatComposition', function(e) {
                e.preventDefault();
                var platId = $(this).attr('data-id');
                let notifier = new AWN();

                let onOk = () => {
                    $.ajax({
                        type: "POST",
                        url: DeletePlatComposition,
                        data: {
                            id: platId,
                            _token: csrf_token,
                        },
                        dataType: "json",
                        success: function (response) {
                            if(response.status == 200) {
                                notifier.success(response.message, {durations: {success: 5000}});
                                $('.TablePlatComposition').DataTable().ajax.reload();
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
                    'Voulez-vous vraiment supprimer cette composition ?',
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
            console.error("Error initializing DataTable:", error);
        }
    }

    initializeTablePlatComposition();

    // Type plat dropdown change
    $('#DropDown_type_plat').on('change', function() {
        let type = $(this).val();
        
        if (type == 0) {
            new AWN().alert('Veuillez sélectionner un type', {durations: {alert: 5000}});
            return false;
        }

        $.ajax({
            type: "GET",
            url: getPlatsByTypeForComposition,
            data: { type: type },
            dataType: "json",
            success: function(response) {
                if (response.status == 200) {
                    let $dropdown = $('#DropDown_plat');
                    $dropdown.empty();
                    $dropdown.append('<option value="0">Sélectionner un plat</option>');
                    
                    $.each(response.data, function(index, item) {
                        $dropdown.append('<option value="' + item.id + '">' + item.name + '</option>');
                    });
                }
            }
        });
    });

    // Plat dropdown change
    $('#DropDown_plat').on('change', function() {
        selectedPlatId = $('#DropDown_plat').val();
        if (selectedPlatId == 0) {
            new AWN().alert('Veuillez sélectionner un plat', {durations: {alert: 5000}});
            return false;
        }

        initializeTableTmpPlat('.TableTmpPlat', selectedPlatId, false);
    });

    // Nombre couvert change
    $('#nombre_couvert, #edit_nombre_couvert').on('change', function() {
        nombreCouvert = $(this).val();
    });

    // Initialize temp plat table
    function initializeTableTmpPlat(selector, platId, isEdit) {
        let tableKey = isEdit ? 'tmpPlatEdit' : 'tmpPlat';
        
        if (activeDataTables[tableKey]) {
            activeDataTables[tableKey].destroy();
            activeDataTables[tableKey] = null;
        }

        activeDataTables[tableKey] = $(selector).DataTable({
            processing: true,
            serverSide: false,
            destroy: true,
            ajax: {
                url: GetTmpPlatByPlatId,
                data: { id_plat: platId },
                type: 'GET'
            },
            columns: [
                { data: 'product_name', title: 'Produit' },
                { data: 'plat_name', title: 'Plat' },
                { data: 'qte', title: 'Quantité' },
                { data: 'unite_name', title: 'Unité' },
                { data: 'nombre_couvert', title: 'Couverts' },
                { data: 'action', title: 'Action', orderable: false, searchable: false }
            ],
            language: {
                "sInfo": "",
                "sInfoEmpty": "Aucune composition",
                "sLoadingRecords": "Chargement...",
                "sProcessing": "Traitement...",
                "sSearch": "Rechercher :",
                "sZeroRecords": "Aucun produit ajouté",
                "oPaginate": {
                    "sFirst": "Premier",
                    "sLast": "Dernier",
                    "sNext": "Suivant",
                    "sPrevious": "Précédent"
                }
            }
        });

        // Edit temp handler
        $(selector + ' tbody').off('click', '.EditTmpPlat');
        $(selector + ' tbody').on('click', '.EditTmpPlat', function(e) {
            e.preventDefault();
            
            let IdTmp = $(this).attr('data-id');
            let Qtetmp = $(this).closest('tr').find('td:eq(2)').text();
            let CouvertTmp = $(this).closest('tr').find('td:eq(4)').text();
            
            $('#ModalEditQteTmpPlat').modal('show');
            $('#BtnUpdateQteTmpPlat').attr('data-id', IdTmp); 
            $('#QteTmpPlat').val(Qtetmp);
            $('#NombreCouvertTmp').val(CouvertTmp);
        });

        // Delete temp handler
        $(selector + ' tbody').on('click', '.DeleteTmpPlat', function(e) {
            e.preventDefault();
            
            let IdTmp = $(this).attr('data-id');
            $.ajax({
                type: "POST",
                url: DeleteRowsTmpPlat,
                data: {
                    '_token': csrf_token,
                    'id': IdTmp,
                },
                dataType: "json",
                success: function (response) {
                    if(response.status == 200) {
                        new AWN().success(response.message, {durations: {success: 5000}});
                        initializeTableTmpPlat(selector, selectedPlatId, isEdit);
                    }
                }
            });
        });

        return activeDataTables[tableKey];
    }

    // Initialize product table
    function initializeTableProduct(selector, data, isEdit) {
        let tableKey = isEdit ? 'productSearchEdit' : 'productSearch';
        
        if (activeDataTables[tableKey]) {
            activeDataTables[tableKey].destroy();
            activeDataTables[tableKey] = null;
        }

        activeDataTables[tableKey] = $(selector).DataTable({
            data: data,
            destroy: true,
            columns: [
                { data: 'name', title: 'Produit' },
                { data: 'quantite', title: 'Quantité' },
                { data: 'seuil', title: 'Seuil' },
                { data: 'name_local', title: 'Local' },
                { data: 'unite_name', title: 'Unité' }
            ],
            rowCallback: function(row, data) {
                $(row).attr('id', data.id);
                $(row).attr('data-unite', data.id_unite);
            },
            language: {
                "sInfo": "",
                "sSearch": "Rechercher :",
                "sZeroRecords": "Aucun produit trouvé"
            }
        });

        // Row click handler
        $(selector + ' tbody').off('click', 'tr');
        $(selector + ' tbody').on('click', 'tr', function(e) {
            e.preventDefault();
            
            let id = $(this).attr('id');
            let id_unite = $(this).attr('data-unite');
            let currentPlatId = isEdit ? $('#edit_id_plat').val() : selectedPlatId;
            let currentNombreCouvert = isEdit ? $('#edit_nombre_couvert').val() : nombreCouvert;
            
            if (!id || currentPlatId == 0) {
                new AWN().alert('Veuillez sélectionner un plat', {durations: {alert: 5000}});
                return false;
            }

            // Show quantity input modal
            let qte = prompt("Entrez la quantité:");
            if (qte === null || qte === "" || parseFloat(qte) <= 0) {
                new AWN().warning('Quantité invalide', {durations: {warning: 3000}});
                return;
            }

            $.ajax({
                type: "POST",
                url: PostInTmpPlat,
                data: {
                    '_token': csrf_token,
                    'idproduit': id,
                    'id_plat': currentPlatId,
                    'id_unite': id_unite,
                    'qte': qte,
                    'nombre_couvert': currentNombreCouvert
                },
                dataType: "json",
                success: function(response) {
                    if (response.status == 200) {
                        new AWN().success(response.message, {durations: {success: 5000}});
                        let tableSelector = isEdit ? '.TableTmpPlatEdit' : '.TableTmpPlat';
                        initializeTableTmpPlat(tableSelector, currentPlatId, isEdit);
                    }
                },
                error: function(xhr) {
                    if (xhr.responseJSON && xhr.responseJSON.errors) {
                        let errorMessages = [];
                        $.each(xhr.responseJSON.errors, function(key, value) {
                            errorMessages.push(value);
                        });
                        new AWN().alert(errorMessages.join('<br>'), {durations: {alert: 5000}});
                    }
                }
            });
        });

        return activeDataTables[tableKey];
    }

    // Product search - Add mode
    $('.input_products_plat').on('keydown', function(e) {
        if (e.keyCode === 13) {
            e.preventDefault();
            
            let name_product = $(this).val().trim();
            if (name_product === '') {
                new AWN().warning('Veuillez saisir un nom de produit', {durations: {warning: 5000}});
                return false;
            }
            
            if (selectedPlatId == 0) {
                new AWN().alert('Veuillez sélectionner un plat', {durations: {alert: 5000}});
                return false;
            }
            
            $.ajax({
                type: "GET",
                url: getProductForPlat,
                data: { product: name_product },
                dataType: "json",
                success: function(response) {
                    if (response.status == 200) {
                        initializeTableProduct('.TableProductPlat', response.data, false);
                        $('.input_products_plat').val("");
                    } else {
                        new AWN().info("Aucun produit trouvé", {durations: {info: 5000}});
                    }
                }
            });
        }
    });

    // Product search - Edit mode
    $('.input_products_plat_edit').on('keydown', function(e) {
        if (e.keyCode === 13) {
            e.preventDefault();
            
            let name_product = $(this).val().trim();
            if (name_product === '') {
                new AWN().warning('Veuillez saisir un nom de produit', {durations: {warning: 5000}});
                return false;
            }
            
            let platId = $('#edit_id_plat').val();
            if (!platId || platId == 0) {
                new AWN().alert('Erreur: plat non sélectionné', {durations: {alert: 5000}});
                return false;
            }
            
            $.ajax({
                type: "GET",
                url: getProductForPlat,
                data: { product: name_product },
                dataType: "json",
                success: function(response) {
                    if (response.status == 200) {
                        initializeTableProduct('.TableProductPlatEdit', response.data, true);
                        $('.input_products_plat_edit').val("");
                    } else {
                        new AWN().info("Aucun produit trouvé", {durations: {info: 5000}});
                    }
                }
            });
        }
    });

    // Save plat composition
    $('#BtnSavePlatComposition').on('click', function(e) {
        e.preventDefault();
        
        if (selectedPlatId == 0) {
            new AWN().alert('Veuillez sélectionner un plat', {durations: {alert: 5000}});
            return false;
        }

        $.ajax({
            type: "POST",
            url: StorePlatComposition,
            data: {
                '_token': csrf_token,
                'id_plat': selectedPlatId,
            },
            dataType: "json",
            success: function(response) {
                if(response.status == 200) {
                    new AWN().success(response.message, {durations: {success: 5000}});
                    
                    // Clear tables
                    if ($.fn.DataTable.isDataTable('.TableProductPlat')) {
                        $('.TableProductPlat').DataTable().clear().draw();
                    }
                    if ($.fn.DataTable.isDataTable('.TableTmpPlat')) {
                        $('.TableTmpPlat').DataTable().clear().draw();
                    }
                    
                    // Reset form
                    $('#DropDown_type_plat').val('0');
                    $('#DropDown_plat').val('0');
                    $('#nombre_couvert').val('1');
                    selectedPlatId = 0;
                    
                    initializeTablePlatComposition();
                    $('#ModalAddPlatComposition').modal("hide");
                }
            },
            error: function(xhr) {
                new AWN().alert("Une erreur est survenue", {durations: {alert: 5000}});
            }
        });
    });

    // Update quantity in temp table
    $('#BtnUpdateQteTmpPlat').on('click', function(e) {
        e.preventDefault();
        
        let Qte = $('#QteTmpPlat').val();
        let NombreCouvert = $('#NombreCouvertTmp').val();
        let id = $(this).attr('data-id');
        
        if(Qte <= 0) {
            new AWN().alert("La quantité doit être supérieure à zéro", {durations: {alert: 5000}});
            return false;
        }
        
        if(NombreCouvert < 1) {
            new AWN().alert("Le nombre de couverts doit être au moins 1", {durations: {alert: 5000}});
            return false;
        }
        
        $('#BtnUpdateQteTmpPlat').prop('disabled', true).text('Enregistrement...');
        
        $.ajax({
            type: "POST",
            url: UpdateQteTmpPlat,
            data: {
                '_token': csrf_token,
                'qte': Qte,
                'nombre_couvert': NombreCouvert,
                'id': id,
            },
            dataType: "json",
            success: function (response) {
                $('#BtnUpdateQteTmpPlat').prop('disabled', false).text('Sauvegarder');
                
                if(response.status == 200) {
                    new AWN().success(response.message, {durations: {success: 5000}});
                    
                    // Determine which table to refresh
                    let isEditMode = $('#ModalEditPlatComposition').hasClass('show');
                    let tableSelector = isEditMode ? '.TableTmpPlatEdit' : '.TableTmpPlat';
                    let platId = isEditMode ? $('#edit_id_plat').val() : selectedPlatId;
                    
                    initializeTableTmpPlat(tableSelector, platId, isEditMode);
                    $('#ModalEditQteTmpPlat').modal('hide');
                } else if(response.status == 400) {
                    $('.validationUpdateQteTmpPlat').html("");
                    $('.validationUpdateQteTmpPlat').addClass('alert alert-danger');
                    $.each(response.errors, function(key, list_err) {
                        $('.validationUpdateQteTmpPlat').append('<li>' + list_err + '</li>');
                    });
                }
            },
            error: function(xhr) {
                $('#BtnUpdateQteTmpPlat').prop('disabled', false).text('Sauvegarder');
                new AWN().alert("Impossible de modifier la quantité", {durations: {alert: 5000}});
            }
        });
    });

    // Update plat composition
    $('#BtnUpdatePlatComposition').on('click', function(e) {
        e.preventDefault();
        
        let platId = $('#edit_id_plat').val();
        
        if (!platId || platId == 0) {
            new AWN().alert('Erreur de plat', {durations: {alert: 5000}});
            return false;
        }

        $('#BtnUpdatePlatComposition').prop('disabled', true).text('Mise à jour...');

        $.ajax({
            type: "POST",
            url: UpdatePlatComposition,
            data: {
                '_token': csrf_token,
                'id_plat': platId,
            },
            dataType: "json",
            success: function(response) {
                $('#BtnUpdatePlatComposition').prop('disabled', false).text('Mettre à jour');
                
                if(response.status == 200) {
                    new AWN().success(response.message, {durations: {success: 5000}});
                    
                    // Clear tables
                    if ($.fn.DataTable.isDataTable('.TableProductPlatEdit')) {
                        $('.TableProductPlatEdit').DataTable().clear().draw();
                    }
                    if ($.fn.DataTable.isDataTable('.TableTmpPlatEdit')) {
                        $('.TableTmpPlatEdit').DataTable().clear().draw();
                    }
                    
                    initializeTablePlatComposition();
                    $('#ModalEditPlatComposition').modal("hide");
                } else {
                    new AWN().alert(response.message || "Une erreur est survenue", {durations: {alert: 5000}});
                }
            },
            error: function(xhr) {
                $('#BtnUpdatePlatComposition').prop('disabled', false).text('Mettre à jour');
                
                if (xhr.responseJSON && xhr.responseJSON.message) {
                    new AWN().alert(xhr.responseJSON.message, {durations: {alert: 5000}});
                } else {
                    new AWN().alert("Une erreur est survenue", {durations: {alert: 5000}});
                }
            }
        });
    });
});