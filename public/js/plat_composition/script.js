$(document).ready(function () {
    let selectedPlatId = 0;
    let nombreCouvert = 1;
    let activeDataTables = {
        tmpPlat: null,
        productSearch: null,
        tmpPlatEdit: null,
        productSearchEdit: null
    };
    function toggleQuantityFieldsAndMenu() {
        var commandType = $('#type_commande').val();
       
        if(commandType !=0)
        {
            $.ajax({
                type: "get",
                url: getcategorybytypemenu,
                data: 
                {
                    type_commande : commandType,
                },
                dataType: "json",
                success: function (response) 
                {
                    if(response.status == 200)
                    {
                        $('#filter_categorie').empty();
                        $.each(response.data, function (index, value) 
                        { 
                            $('#filter_categorie').append(`<option value=${value.id}>${value.name}</option>`)     
                        });
                    }    
                }
            });
        }
        else
        {
           $('#filter_categorie').empty(); 
        }
        
       
    }
    $('#type_commande').on('change', function() {
        toggleQuantityFieldsAndMenu();
    });

    $('#filter_subcategorie').on('change', function() {
        var subcategoryId = $(this).val();
        var categoryId = $('#filter_categorie').val();
        let name_product = $('.input_products').val().trim();
        
        if (subcategoryId) {
            // Visual feedback
            $('.input_products').prop('disabled', true);
            
            
            $.get(getProduct, { 
                product: name_product,
                filter_subcategorie: subcategoryId,
                category: categoryId 
            }, function(response) {
                if (response.status === 200) {
                    $('.input_products').prop('disabled', false);
                   
                    initializeTableProduct('.TableProductPlat', response.data);
                } else {
                    $('.input_products').prop('disabled', false);
                    
                    new AWN().info("Aucun produit trouvé.", {durations: {info: 5000}});
                }
            }).fail(function(xhr, status, error) {
                $('.input_products').prop('disabled', false);
                
                console.error("Error loading products:", error);
                new AWN().alert("Erreur lors du chargement des produits", {
                    durations: { alert: 5000 }
                });
            });
        }
    });


    $('#filter_categorie').on('change', function() {
        var categoryId = $(this).val();
        let name_product = $('.input_products').val().trim();
        
        // Reset subcategory dropdown FIRST
        $('#filter_subcategorie').empty().append('<option value="">Toutes les familles</option>');
        
        if (categoryId) {
            // Visual feedback during search
            $('.input_products').prop('disabled', true);
           
            
            // Fetch subcategories for selected category
            $.get('/vente/subcategories/' + categoryId, function(response) {
                if (response.status === 200 && response.subcategories.length > 0) {
                    $.each(response.subcategories, function(key, subcategory) {
                        $('#filter_subcategorie').append(
                            '<option value="' + subcategory.id + '">' + subcategory.name + '</option>'
                        );
                    });
                } else {
                    new AWN().info("Aucune famille trouvée pour cette catégorie", {
                        durations: { info: 3000 }
                    });
                }
            }).fail(function(xhr, status, error) {
                console.error("Error loading subcategories:", error);
                new AWN().alert("Erreur lors du chargement des familles", {
                    durations: { alert: 5000 }
                });
            });
            
            // NOW fetch products with ONLY category (no subcategory filter)
            $.get(getProduct, { 
                product: name_product,
                filter_subcategorie: '',  // ← EMPTY! Just category filter
                category: categoryId 
            }, function(secondResponse) {
                if (secondResponse.status === 200) {
                    $('.input_products').prop('disabled', false);
                    
                    initializeTableProduct('.TableProductPlat', secondResponse.data);
                    $('.input_products').val(""); 
                } else {
                    $('.input_products').prop('disabled', false);
                   
                    new AWN().info("Aucun produit trouvé.", {durations: {info: 5000}});
                }
            }).fail(function(xhr, status, error) {
                $('.input_products').prop('disabled', false);
                
                console.error("Error in second request:", error);
                new AWN().alert("Erreur lors du deuxième chargement", {
                    durations: { alert: 5000 }
                });
            });
        }
    });

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
                    { data: 'nom_plat', name: 'nom_plat' },
                    { data: 'name', name: 'name' },
                    { data: 'qte', name: 'qte' },
                    { data: 'unite', name: 'unite' },
                    { data: 'nombre_couvert', name: 'nombre_couvert' },
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
    
    // ✅ VALIDATE DATA FIRST
    if (!data || !Array.isArray(data) || data.length === 0) {
        console.warn('No valid data provided to initializeTableProduct');
        
        // Initialize empty table
        if (activeDataTables[tableKey]) {
            activeDataTables[tableKey].destroy();
            activeDataTables[tableKey] = null;
        }
        
        activeDataTables[tableKey] = $(selector).DataTable({
            data: [],
            destroy: true,
            columns: [
                { data: 'name', title: 'Produit', defaultContent: '' },
                { data: 'quantite', title: 'Quantité', defaultContent: '' },
                { data: 'seuil', title: 'Seuil', defaultContent: '' },
                { data: 'name_local', title: 'Local', defaultContent: '' },
                { data: 'unite_name', title: 'Unité', defaultContent: '' }
            ],
            language: {
                "sInfo": "",
                "sSearch": "Rechercher :",
                "sZeroRecords": "Aucun produit trouvé"
            }
        });
        
        return activeDataTables[tableKey];
    }
    
    // ✅ VALIDATE EACH ROW HAS REQUIRED FIELDS
    const validatedData = data.map(item => {
        if (!item.unite_name) {
            console.warn('Missing unite_name for item:', item);
        }
        return {
            id: item.id || '',
            name: item.name || '',
            quantite: item.quantite || 0,
            seuil: item.seuil || 0,
            name_local: item.name_local || '',
            unite_name: item.unite_name || 'N/A',  // ← Fallback
            id_unite: item.id_unite || ''
        };
    });
    
    if (activeDataTables[tableKey]) {
        activeDataTables[tableKey].destroy();
        activeDataTables[tableKey] = null;
    }

    activeDataTables[tableKey] = $(selector).DataTable({
        data: validatedData,  // ← Use validated data
        destroy: true,
        columns: [
            { data: 'name', title: 'Produit', defaultContent: 'N/A' },
            { data: 'quantite', title: 'Quantité', defaultContent: '0' },
            { data: 'seuil', title: 'Seuil', defaultContent: '0' },
            { data: 'name_local', title: 'Local', defaultContent: 'N/A' },
            { data: 'unite_name', title: 'Unité', defaultContent: 'N/A' }  // ← Add defaultContent
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
    let searchTimeoutt = null;
  $('.input_products').on('input', function (e) {
    e.preventDefault();

    clearTimeout(searchTimeoutt); // Cancel previous timer

    let name_product = $('.input_products').val().trim();
    let category = $("#filter_categorie").val();
    let filter_subcategorie = $('#filter_subcategorie').val();
    let type_commande = $('#type_commande').val();
    let Formateur = $('#DropDown_formateur').val();

    if (Formateur == 0) {
        new AWN().alert('Veuillez sélectionner un demandeur', { durations: { alert: 5000 } });
        return false;
    }

    // If input is empty → send AJAX to load all products
    if (name_product === '') {
        sendAjaxRequest(name_product, category, filter_subcategorie, type_commande);
        return; // stop here (no need debounce)
    }

    // Otherwise → search with debounce
    searchTimeoutt = setTimeout(function () {
        sendAjaxRequest(name_product, category, filter_subcategorie, type_commande);
    }, 400);
});

function sendAjaxRequest(name_product, category, filter_subcategorie, type_commande) {
    // Visual feedback
    $('.input_products').prop('disabled', true);
    

    $.ajax({
        type: "GET",
        url: getProduct,
        data: {
            product: name_product,
            category: category,
            filter_subcategorie: filter_subcategorie,
            type_commande: type_commande,
        },
        dataType: "json",
        success: function (response) {
            $('.input_products').prop('disabled', false);
            $('.TableProductPlat_wrapper').removeClass('opacity-50');

            if (response.status == 200) {
                initializeTableProduct('.TableProductPlat', response.data);
            } else {
                new AWN().info("Aucun produit trouvé.", { durations: { info: 3000 } });
                $('.TableProductPlat').DataTable().clear().draw();
            }
        },
        error: function (xhr, status, error) {
            $('.input_products').prop('disabled', false);
            $('.TableProductPlat_wrapper').removeClass('opacity-50');

            console.error("Error searching for product:", error);
            console.error("Response:", xhr.responseText);

            try {
                const errorData = JSON.parse(xhr.responseText);
                new AWN().alert(errorData.message || "Erreur lors de la recherche", { durations: { alert: 5000 } });
            } catch (e) {
                new AWN().alert("Erreur lors de la recherche", { durations: { alert: 5000 } });
            }
        }
    });
}
    // Product search - Edit mode
    $('.input_products_edit').on('keydown', function(e) {
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
                        $('.input_products_edit').val("");
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