//command js file 
$(document).ready(function () {
    // Helper function to format menu names for display
    function formatMenuName(menuType) {
        if (menuType === 'Menu eleves') {
            return 'Menu standard';
        }
        return menuType;
    }

    let Formateur = 0;
    Formateur = $('#DropDown_formateur').val();
        
    // Initialize tables for this user
    /* if (Formateur != 0) {
        initializeTableTmpVente('.TableTmpVente', Formateur);
        GetTotalTmpByFormateurAndUserScript(Formateur);
    } */
    
    $('.linkCallModalAddProduct').on('click', function(e) {
        $('#ModalAddProduct').modal("show");
        $('#ModalAddVente').modal("hide");
    });

    function GetTotalTmpByFormateurAndUserScript(Formateur) {
        console.log("Getting total for demandeur ID:", Formateur);
        
        $.ajax({
            type: "GET",
            url: GetTotalTmpByFormateurAndUser,
            data: {
                'id_formateur': Formateur,
            },
            dataType: "json",
            success: function(response) {
                if(response.status == 200) {
                    $('.TotalByFormateurAndUser').text(response.total + " DH");
                    console.log("Total updated successfully:", response.total);
                }
            },
            error: function(xhr, status, error) {
                console.error("Error fetching total:", error);
                console.error("Status:", status);
                console.error("Response:", xhr.responseText);
            }
        });
    }
    
    // Initialize dependent dropdowns
    initializeDropdowns();

    // Keep track of active DataTables to prevent duplication
    let activeDataTables = {
        tmpVente: null,
        productSearch: null
    };

    // Keep track of AJAX requests in progress to prevent duplicate submissions
    let ajaxInProgress = {
        deleteRowTmp: false,
        postInTmpVente: false,
        updateQteTmp: false,
        saveVente: false,
        updateVente: false,
        changeStatusVente: false,
        deleteVente: false,
        addProduct: false
    };

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

    // Initialize Dropdowns
    function initializeDropdowns() {
        // Category change - load subcategories
        $('#id_categorie, #edit_id_categorie').on('change', function() {
            var targetCategory = $(this).attr('id') === 'id_categorie' 
                ? '#id_subcategorie' 
                : '#edit_id_subcategorie';
            
            loadSubcategories(
                '#' + $(this).attr('id'), 
                targetCategory
            );
        });
        
        // Local change - load rayons
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

    function initializeTableTmpVente(selector, IdFormateur) {
        console.log("Initializing tmp vente table for demandeur ID:", IdFormateur);
        
        // Properly destroy DataTable if it exists
        if (activeDataTables.tmpVente) {
            activeDataTables.tmpVente.destroy();
            activeDataTables.tmpVente = null;
        }
        
        // Reinitialize DataTable
        activeDataTables.tmpVente = $(selector).DataTable({
            select: true,
            processing: true,
            serverSide: true,
            destroy: true,
            autoWidth: false,
            ajax: {
                url: GetTmpVenteByFormateur,
                data: function(d) {
                    d.id_formateur = IdFormateur;
                },
                dataType: 'json',
                type: 'GET',
                error: function(xhr, error, code) {
                    console.error('Error occurred while fetching temp vente: ', error);
                    console.error('XHR Response:', xhr.responseText);
                }
            },
            columns: [
                { data: 'name', name: 'name', title: 'Produit' },
                { data: 'qte', name: 'qte', title: 'Quantité' },
                { data: 'formateur_name', name: 'formateur_name', title: 'Formateur' },
                { 
                    data: 'action', 
                    name: 'action',
                    title: 'Action', 
                    orderable: false, 
                    searchable: false
                }
            ],
            rowCallback: function(row, data, index) {
                $(row).attr('id', data.id);
            },
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
                console.log("Table drawn successfully");
            }
        });
        
        // Re-attach event handlers for the edit and delete buttons
        attachTableEventHandlers(selector, IdFormateur);
    
        return activeDataTables.tmpVente;
    }
    
    // Extract event handlers to a separate function for cleaner code
    function attachTableEventHandlers(selector, IdFormateur) {
        // Edit button event
        $(selector).off('click', '.EditTmp').on('click', '.EditTmp', function(e) {
            e.preventDefault();
            
            let IdTmp = $(this).attr('data-id');
            let Qtetmp = $(this).closest('tr').find('td:eq(1)').text();
            
            // Store the current formateur ID in the modal or a data attribute
            $('#ModalEditQteTmp').data('formateur-id', IdFormateur);
            
            $('#ModalEditQteTmp').modal('show');
            $('#BtnUpdateQteTmp').attr('data-id', IdTmp); 
            $('#QteTmp').val(Qtetmp);   
        });
    
        // Delete button event
        $(selector).off('click', '.DeleteTmp').on('click', '.DeleteTmp', function(e) {
            e.preventDefault();
            
            // If already processing a delete request, ignore this click
            if (ajaxInProgress.deleteRowTmp) {
                return;
            }
            
            let IdTmp = $(this).attr('data-id');
            let deleteButton = $(this);
            
            // Mark delete operation as in progress and disable the button
            ajaxInProgress.deleteRowTmp = true;
            deleteButton.prop('disabled', true).html('<i class="fa-solid fa-spinner fa-spin text-danger"></i>');
            
            $.ajax({
                type: "POST",
                url: DeleteRowsTmpVente,
                data: {
                    '_token': csrf_token,
                    'id': IdTmp,
                },
                dataType: "json",
                success: function(response) {
                    // Mark delete operation as complete
                    ajaxInProgress.deleteRowTmp = false;
                    
                    if(response.status == 200) {
                        new AWN().success(response.message, {durations: {success: 5000}});
                        
                        // Use the stored IdFormateur
                        initializeTableTmpVente(selector, IdFormateur);
                        GetTotalTmpByFormateurAndUserScript(IdFormateur);
                    }
                },
                error: function(xhr, status, error) {
                    // Mark delete operation as complete and restore the button
                    ajaxInProgress.deleteRowTmp = false;
                    deleteButton.prop('disabled', false).html('<i class="fa-solid fa-trash text-danger"></i>');
                    
                    console.error("Error deleting item:", error);
                    console.error("Response:", xhr.responseText);
                    new AWN().alert("Erreur lors de la suppression", {durations: {alert: 5000}});
                }
            });
        });
    }

    function initializeTableProduct(selector, data) {
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
                { data: 'name', title: 'Produit' },
                { data: 'quantite', title: 'Quantité' },
                { data: 'seuil', title: 'Seuil' },
                { data: 'name_local', title: 'Local' }
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
    
        $(selector + ' tbody').off('click', 'tr');
        
        $(selector + ' tbody').on('click', 'tr', function(e) {
            e.preventDefault();
            
            // If a request is already in progress, ignore this click
            if (ajaxInProgress.postInTmpVente) {
                return;
            }
            
            let id = $(this).attr('id');
            let Formateur = $('#DropDown_formateur').val();
            let clickedRow = $(this);
            
            if (!id || id === '') {
                console.warn('No ID found for this row');
                return;
            }
            
            if (Formateur == 0) {
                new AWN().alert('Veuillez sélectionner un demandeur', {durations: {success: 5000}});
                return false;
            }
            
            // Mark post operation as in progress and visually indicate processing
            ajaxInProgress.postInTmpVente = true;
            clickedRow.addClass('bg-secondary-subtle');
            
            $.ajax({
                type: "POST",
                url: PostInTmpVente,
                data: {
                    '_token': csrf_token,
                    'idproduit': id,
                    'id_formateur': Formateur,
                },
                dataType: "json",
                success: function(response) {
                    // Mark post operation as complete
                    ajaxInProgress.postInTmpVente = false;
                    clickedRow.removeClass('bg-secondary-subtle');
                    
                    if (response.status == 200) {
                        // Success case - either new format or old format
                        if (response.type === 'success') {
                            // New response format
                            new AWN().success(response.details, {
                                labels: {
                                    success: response.message
                                },
                                durations: {success: 5000}
                            });
                        } else {
                            // Old response format
                            new AWN().success(response.message, {durations: {success: 5000}});
                        }
                        
                        // Refresh the TmpVente table
                        initializeTableTmpVente('.TableTmpVente', Formateur);
                        GetTotalTmpByFormateurAndUserScript(Formateur);
                    } else if (response.status == 400) {
                        // Error case
                        if (response.type === 'error') {
                            // New error format
                            new AWN().warning(response.details, {
                                labels: {
                                    warning: response.message
                                },
                                durations: {warning: 5000}
                            });
                        } else {
                            // Old error format or generic error
                            new AWN().alert(response.message || 'Une erreur est survenue', {durations: {alert: 5000}});
                        }
                    } else {
                        // Other status codes
                        new AWN().alert(response.message || 'Une erreur est survenue', {durations: {alert: 5000}});
                    }
                },
                error: function(xhr, status, error) {
                    // Mark post operation as complete and restore visual state
                    ajaxInProgress.postInTmpVente = false;
                    clickedRow.removeClass('bg-secondary-subtle');
                    
                    console.error("Error adding product:", error);
                    console.error("Response:", xhr.responseText);
                    
                    try {
                        const errorData = JSON.parse(xhr.responseText);
                        if (errorData.type === 'error') {
                            // New error format
                            new AWN().alert(errorData.details, {
                                labels: {
                                    alert: errorData.message
                                },
                                durations: {alert: 5000}
                            });
                        } else {
                            // Old error format
                            new AWN().alert(errorData.message || "Impossible d'ajouter le produit", {durations: {alert: 5000}});
                        }
                    } catch (e) {
                        // Could not parse response as JSON
                        new AWN().alert("Impossible d'ajouter le produit", {durations: {alert: 5000}});
                    }
                }
            });
        });
        
        return activeDataTables.productSearch;
    }
            
    // Product search functionality
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


// 🔹 Reusable AJAX function
function sendAjaxRequest(name_product, category, filter_subcategorie, type_commande) {
    // Visual feedback
    $('.input_products').prop('disabled', true);
    $('.TableProductVente_wrapper').addClass('opacity-50');

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
            $('.TableProductVente_wrapper').removeClass('opacity-50');

            if (response.status == 200) {
                initializeTableProduct('.TableProductVente', response.data);
            } else {
                new AWN().info("Aucun produit trouvé.", { durations: { info: 3000 } });
                $('.TableProductVente').DataTable().clear().draw();
            }
        },
        error: function (xhr, status, error) {
            $('.input_products').prop('disabled', false);
            $('.TableProductVente_wrapper').removeClass('opacity-50');

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



   function initializeTableVenteDataTable() {
    try {
        if ($.fn.DataTable.isDataTable('.TableVente')) {
            $('.TableVente').DataTable().destroy();
        }
        
        var TableVente = $('.TableVente').DataTable({
            order : [[11 , 'desc']],
            processing: true,
            serverSide: true,
            ajax: {
                url: Vente,
                dataSrc: function (json) {
                    setTimeout(() => {
                        if (json.data.length === 0) {
                            $('.paging_full_numbers').hide();
                        }
                    }, 100);
                    return json.data;
                },
                error: function(xhr, error, thrown) {
                    console.log('DataTables error: ' + error + ' ' + thrown);
                    console.log(xhr);
                }
            },
            columns: [
                { data: 'formateur_name', name: 'formateur_name' },
                { data: 'total', name: 'total' },
                {
                    data : 'status',
                    name : 'status',
                    render :function(data , type ,row)
                    {
                        if(data === 'Validation')
                        {
                            return 'Réception';
                        }
                        else if(data === 'Réception')
                        {
                            return 'Validation';
                        }
                        return data;
                    }
                },
                /* { data: 'status', name: 'status' }, */
                { data: 'type_commande', name: 'type_commande' },
                { 
                    data: 'type_menu', 
                    name: 'type_menu',
                    render: function(data, type, row) {
                        // Format the menu name for display
                        return formatMenuName(data);
                    }
                },
                { data: 'eleves', name: 'eleves' },
                { data: 'personnel', name: 'personnel' },
                { data: 'invites', name: 'invites' },
                { data: 'divers', name: 'divers' },
                // ADD THIS LINE - Date Usage column
                { 
                    data: 'date_usage', 
                    name: 'date_usage',
                    render: function(data, type, row) {
                        // Format the date for display or show empty if null
                        if (data && data !== '') {
                            // Format date using moment.js if available, or just return as is
                            return moment(data).format('DD/MM/YYYY');
                        }
                        return '-';
                    }
                },
                { data: 'name', name: 'name' },
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
            },
            drawCallback: function() {
                // Reset all AJAX in progress flags when table is redrawn
                ajaxInProgress = {
                    deleteRowTmp: false,
                    postInTmpVente: false,
                    updateQteTmp: false,
                    saveVente: false,
                    updateVente: false,
                    changeStatusVente: false,
                    deleteVente: false,
                    addProduct: false
                };
            }
        });

    } catch (error) {
        console.error("Error initializing DataTable:", error);
    }
}
    initializeTableVenteDataTable();
    
    // Updated toggleQuantityFieldsAndMenu function with menu attributes support
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
        
        if (commandType === 'Alimentaire') {
            // Show quantity fields, menu and menu attributes for Alimentaire
            $('#quantity_fields_container').show();
            $('#menu_container').show();
            $('#menu_attributes_container').show(); // Show menu attributes
            $('#type_menu').val('Menu eleves').prop('disabled', false);
            
            // Enable menu attribute fields
            $('#entree').prop('disabled', false);
            $('#plat_principal').prop('disabled', false);
            $('#accompagnement').prop('disabled', false);
            $('#dessert').prop('disabled', false);

            
        } else {
            // Hide quantity fields, menu and menu attributes for Non Alimentaire and Fournitures et matériels
            $('#quantity_fields_container').hide();
            $('#menu_container').hide();
            $('#menu_attributes_container').hide(); // Hide menu attributes
            
            // Reset values of quantity fields to 0
            $('#eleves').val(0);
            $('#personnel').val(0);
            $('#invites').val(0);
            $('#divers').val(0);
            
            // Set the menu to empty value
            $('#type_menu').val('').prop('disabled', true);
            
            // Reset and disable menu attribute fields
            $('#entree').val('').prop('disabled', true);
            $('#plat_principal').val('').prop('disabled', true);
            $('#accompagnement').val('').prop('disabled', true);
            $('#dessert').val('').prop('disabled', true);
        }
    }

    $('#type_commande').on('change', function() {
        toggleQuantityFieldsAndMenu();
    });

    // Initialize the fields based on the current selection when page loads
    $(document).ready(function() {
        toggleQuantityFieldsAndMenu();
        $('#type_commande').on('change', function() {
            toggleQuantityFieldsAndMenu();
        });
    });

    // Add event listener for type_commande change
    $('#type_commande').on('change', function() {
        toggleQuantityFieldsAndMenu();
    });

    // Wrap the quantity fields in a container div for easier toggling
    $('#eleves, #personnel, #invites, #divers').closest('.row').wrapAll('<div class="quantity-fields-container"></div>');
    
    // Wrap the menu field in a container div
    $('#type_menu').closest('.form-group').wrapAll('<div class="menu-container"></div>');
    
    // Add a null option to the type_menu dropdown
    if (!$('#type_menu option[value="null"]').length) {
        $('#type_menu').append('<option value="null" style="display:none;">Aucun menu</option>');
    }
    
    // Initialize the fields based on the current selection
    toggleQuantityFieldsAndMenu();
    
$('#BtnSaveVente').on('click', function(e) {
    e.preventDefault();
    
    // If already processing a save request, ignore this click
    if (ajaxInProgress.saveVente) {
        return;
    }
    
    // Validate the form fields
    const Formateur = $('#DropDown_formateur').val();
    if (Formateur == 0) {
        new AWN().alert('Veuillez sélectionner un demandeur', {durations: {alert: 5000}});
        return false;
    }
    
    const commandType = $('#type_commande').val();
    if (!commandType) {
        new AWN().alert('Veuillez sélectionner un type de commande', {durations: {alert: 5000}});
        return false;
    }
    
    // Mark save operation as in progress and disable the button
    ajaxInProgress.saveVente = true;
    $('#BtnSaveVente').prop('disabled', true).text('Enregistrement...');
    
    // Prepare data object with required fields
    const requestData = {
        '_token': csrf_token,
        'id_formateur': Formateur,
        'type_commande': commandType
    };
    
    // ADD DATE_USAGE FIELD - Always include this field regardless of command type
    const dateUsage = $('#date_usage').val();
    requestData.date_usage = dateUsage && dateUsage.trim() !== '' ? dateUsage : null;
    
    // Only include menu and quantity fields if command type is Alimentaire
    if (commandType === 'Alimentaire') {
        
        const typeMenu = $('#type_menu').val();
        requestData.type_menu = typeMenu && typeMenu.trim() !== '' ? typeMenu : null;
        
        requestData.eleves = $('#eleves').val() || 0;
        requestData.personnel = $('#personnel').val() || 0;
        requestData.invites = $('#invites').val() || 0;
        requestData.divers = $('.divers').val() || 0;
       
        // Add menu attributes - convert empty strings to null
        const entree = $('#entree').val();
        const platPrincipal = $('#plat_principal').val();
        const accompagnement = $('#accompagnement').val();
        const dessert = $('#dessert').val();
        
        /* requestData.entree = entree && entree.trim() !== '' ? entree : null; */
        requestData.entree = (Array.isArray(entree) ? entree.join(',') : entree)?.trim() || null;
        requestData.plat_principal = (Array.isArray(platPrincipal) ? platPrincipal.join(',') : platPrincipal)?.trim() || null;
        requestData.accompagnement = (Array.isArray(accompagnement) ? accompagnement.join(',') : accompagnement)?.trim() || null;
        requestData.dessert = (Array.isArray(dessert) ? dessert.join(',') : dessert)?.trim() || null;

        /* requestData.plat_principal = platPrincipal && platPrincipal.trim() !== '' ? platPrincipal : null;
        requestData.accompagnement = accompagnement && accompagnement.trim() !== '' ? accompagnement : null;
        requestData.dessert = dessert && dessert.trim() !== '' ? dessert : null; */
    } else {
       
        
        // For non-food commands, explicitly set null values (not empty strings)
        requestData.type_menu = null;
        requestData.eleves = 0;
        requestData.personnel = 0;
        requestData.invites = 0;
        requestData.divers = 0;
        requestData.entree = null;
        requestData.plat_principal = null;
        requestData.accompagnement = null;
        requestData.dessert = null;
    }
    
    $.ajax({
        type: "POST",
        url: StoreVente,
        data: requestData,
        dataType: "json",
        success: function(response) {
            // Mark save operation as complete and re-enable the button
            ajaxInProgress.saveVente = false;
            $('#BtnSaveVente').prop('disabled', false).text('Enregistrer');
            
            if(response.status == 200) {
                new AWN().success(response.message, {durations: {success: 5000}});
                
                // Clear product search table
                if ($.fn.DataTable.isDataTable('.TableProductVente')) {
                    $('.TableProductVente').DataTable().clear().draw();
                }
                
                // Clear temporary items table
                if ($.fn.DataTable.isDataTable('.TableTmpVente')) {
                    $('.TableTmpVente').DataTable().clear().draw();
                }
                
                // Reset the total display
                $('.TotalByFormateurAndUser').text("0.00 DH");
                
                // Clear all the fields including menu attributes
                $('#eleves').val(0);
                $('#personnel').val(0);
                $('#invites').val(0);
                $('#divers').val(0);
                $('#entree').val('');
                $('#plat_principal').val('');
                $('#accompagnement').val('');
                $('#dessert').val('');
                // ADD THIS LINE - Clear date_usage field
                $('#date_usage').val('');
                
                // Reset type_commande to default
                $('#type_commande').val('Alimentaire');
                
                // Reset type_menu to default
                $('#type_menu').val('Menu eleves');
                
                // Reset the form visibility based on default values
                toggleQuantityFieldsAndMenu();
                
                // Reinitialize the main table
                initializeTableVenteDataTable();
                
                // Hide the modal
                $('#ModalAddVente').modal("hide");
            } else if(response.status == 400) {
                $('.validationVente').html("");
                $('.validationVente').addClass('alert alert-danger');
                $.each(response.errors, function(key, list_err) {
                    $('.validationVente').append('<li>' + list_err + '</li>');
                });
            } else {
                new AWN().alert(response.message || "Une erreur est survenue", {durations: {alert: 5000}});
            }
        },
        error: function(xhr, status, error) {
            // Mark save operation as complete and re-enable the button
            ajaxInProgress.saveVente = false;
            $('#BtnSaveVente').prop('disabled', false).text('Enregistrer');
            
            console.error("Error saving sale:", error);
            
            try {
                const response = JSON.parse(xhr.responseText);
                if (response.errors) {
                    $('.validationVente').html("");
                    $('.validationVente').addClass('alert alert-danger');
                    $.each(response.errors, function(key, list_err) {
                        $('.validationVente').append('<li>' + list_err + '</li>');
                    });
                } else {
                    new AWN().alert(response.message || "Une erreur est survenue lors de l'enregistrement", {durations: {alert: 5000}});
                }
            } catch (e) {
                new AWN().alert("Une erreur est survenue lors de l'enregistrement", {durations: {alert: 5000}});
            }
        }
    });
});
function validateDateUsage() {
    const dateUsage = $('#date_usage').val();
    if (dateUsage) {
        const selectedDate = new Date(dateUsage);
        const today = new Date();
        today.setHours(0, 0, 0, 0); // Reset time to start of day
        
        if (selectedDate < today) {
            new AWN().warning('La date d\'usage ne peut pas être antérieure à aujourd\'hui', {durations: {warning: 5000}});
            return false;
        }
    }
    return true;
}
$(document).ready(function() {
    $('#date_usage').on('change', function() {
        validateDateUsage();
    });
});
    // Update quantity in tmp vente
    $('#BtnUpdateQteTmp').off('click').on('click', function(e) {
        e.preventDefault();
        
        // If already processing an update request, ignore this click
        if (ajaxInProgress.updateQteTmp) {
            return;
        }
        
        let Qte = $('#QteTmp').val();
        let id = $(this).attr('data-id');
        // Get the current formateur value - this is crucial
        let currentFormateur = $('#DropDown_formateur').val();
        
        if(Qte <= 0) {
            new AWN().alert("La quantité doit être supérieure à zéro", {durations: {alert: 5000}});
            return false;
        }
        
        // Mark update operation as in progress and disable the button
        ajaxInProgress.updateQteTmp = true;
        $('#BtnUpdateQteTmp').prop('disabled', true).text('Enregistrement...');
        
        $.ajax({
            type: "POST",
            url: UpdateQteTmpVente,
            data: {
                '_token': csrf_token,
                'qte': Qte,
                'id': id,
            },
            dataType: "json",
            success: function(response) {
                // Mark update operation as complete 
                ajaxInProgress.updateQteTmp = false;
                $('#BtnUpdateQteTmp').prop('disabled', false).text('Sauvegarder');
                
                if(response.status == 200) {
                    // Success case
                    new AWN().success(response.details, {
                        labels: {
                            success: response.message
                        },
                        durations: {success: 5000}
                    });
                    
                    // Add console logs for debugging
                    console.log("Update successful, refreshing table with formateur ID:", currentFormateur);
                    
                    // Explicitly refresh the DataTable with the current formateur
                    initializeTableTmpVente('.TableTmpVente', currentFormateur);
                    
                    // Update the total
                    GetTotalTmpByFormateurAndUserScript(currentFormateur);
                    
                    // Close the modal
                    $('#ModalEditQteTmp').modal('hide');
                }
                else if(response.status == 400) {
                    // Error case for validation errors
                    if(response.message === 'ERROR') {
                        // This is our custom error format with details
                        new AWN().warning(response.details, {
                            labels: {
                                warning: response.message
                            },
                            durations: {warning: 5000}
                        });
                    } else {
                        // This is the old format with validation errors
                        $('.validationUpdateQteTmp').html("");
                        $('.validationUpdateQteTmp').addClass('alert alert-danger');
                        $.each(response.errors, function(key, list_err) {
                            $('.validationUpdateQteTmp').append('<li>' + list_err + '</li>');
                        });
                    }
                }
            },
            error: function(xhr, status, error) {
                // Mark update operation as complete and re-enable button
                ajaxInProgress.updateQteTmp = false;
                $('#BtnUpdateQteTmp').prop('disabled', false).text('Sauvegarder');
                
                console.error("Error updating quantity:", error);
                console.error("Status:", status);
                console.error("Response:", xhr.responseText);
                
                try {
                    const response = JSON.parse(xhr.responseText);
                    if(response.message === 'ERROR') {
                        // Display our formatted error message
                        new AWN().alert(response.details, {
                            labels: {
                                alert: response.message
                            },
                            durations: {alert: 5000}
                        });
                    } else {
                        new AWN().alert("Impossible de modifier la quantité", {durations: {alert: 5000}});
                    }
                } catch (e) {
                    new AWN().alert("Impossible de modifier la quantité", {durations: {alert: 5000}});
                }
            }
        });
    });

    // Edit Vente functionality
    $('.TableVente tbody').on('click', '.bg-primary-subtle', function(e) {
        e.preventDefault();
        $('#ModalEditVente').modal("show");
        var idVente = $(this).attr('data-id');
        var status = $(this).closest('tr').find('td:eq(2)').text();
        $('#BtnChangeStatusVente').attr('data-id', idVente);
        
        // Get vente details from server
        $.ajax({
            type: "GET",
            url: EditVente + '/' + idVente,
            dataType: "json",
            success: function (response) {
                if(response) {
                    $('#StatusVente').val(response.status);
                    $('#BtnUpdateVente').attr('data-id', idVente);
                }
                else {
                    new AWN().warning("Impossible de récupérer les détails de la commande", {durations: {warning: 5000}});
                }
            },
            error: function() {
                new AWN().alert("Une erreur est survenue, veuillez réessayer.", { durations: { alert: 5000 } });
            }
        });
    });

    // Update Vente functionality

$('#BtnUpdateVente').on('click', function(e) {
    e.preventDefault();
    
    // If already processing an update request, ignore this click
    if (ajaxInProgress.updateVente) {
        return;
    }
    
    let id = $(this).attr('data-id');
    let status = $('#StatusVente').val();
    
    // Mark update operation as in progress and disable the button
    ajaxInProgress.updateVente = true;
    $('#BtnUpdateVente').prop('disabled', true).text('Traitement...');
    
    // If this is a status change to Validation or Visé, use the ChangeStatusVente endpoint
    if (status === 'Validation' || status === 'Visé') {
        $.ajax({
            type: "POST",
            url: ChangeStatusVente,
            data: {
                '_token': csrf_token,
                'id': id,
                'status': status
            },
            dataType: "json",
            success: function(response) {
                // Mark update operation as complete and re-enable the button
                ajaxInProgress.updateVente = false;
                $('#BtnUpdateVente').prop('disabled', false).text('Mettre à jour');
                
                if (response.status == 200) {
                    new AWN().success(response.message, {durations: {success: 5000}});
                    $('#ModalEditVente').modal('hide');
                    $('.TableVente').DataTable().ajax.reload();
                } else {
                    new AWN().warning(response.message || "Une erreur est survenue", {durations: {warning: 5000}});
                }
            },
            error: function(xhr, status, error) {
                // Mark update operation as complete and re-enable the button
                ajaxInProgress.updateVente = false;
                $('#BtnUpdateVente').prop('disabled', false).text('Mettre à jour');
                
                console.error('Error:', xhr.responseText);
                new AWN().alert("Une erreur est survenue, veuillez réessayer.", { durations: { alert: 5000 } });
            }
        });
    } 
    // Otherwise use the regular UpdateVente endpoint
    else {
        $.ajax({
            type: "POST",
            url: UpdateVente,
            data: {
                '_token': csrf_token,
                'id': id,
                'status': status
            },
            dataType: "json",
            success: function(response) {
                // Mark update operation as complete and re-enable the button
                ajaxInProgress.updateVente = false;
                $('#BtnUpdateVente').prop('disabled', false).text('Mettre à jour');
                
                if (response.status == 200) {
                    new AWN().success(response.message, {durations: {success: 5000}});
                    $('#ModalEditVente').modal('hide');
                    $('.TableVente').DataTable().ajax.reload();
                } else {
                    new AWN().warning(response.message || "Une erreur est survenue", {durations: {warning: 5000}});
                }
            },
            error: function(xhr, status, error) {
                // Mark update operation as complete and re-enable the button
                ajaxInProgress.updateVente = false;
                $('#BtnUpdateVente').prop('disabled', false).text('Mettre à jour');
                
                try {
                    const response = JSON.parse(xhr.responseText);
                    if (response.status === 400 && response.errors) {
                        const errorMessages = Object.values(response.errors)
                            .flat()
                            .join('<br>');
                        new AWN().warning(errorMessages, {durations: {warning: 5000}});
                    } else if (response.status === 404) {
                        new AWN().warning(response.message, {durations: {warning: 5000}});
                    } else {
                        new AWN().alert("Une erreur est survenue, veuillez réessayer.", { durations: { alert: 5000 } });
                    }
                } catch (e) {
                    console.error("Error parsing response:", e);
                    new AWN().alert("Une erreur est survenue, veuillez réessayer.", { durations: { alert: 5000 } });
                }
            }
        });
    }
});
   // Change Status Vente functionality
   $('#BtnChangeStatusVente').off('click').on('click', function(e) {
       e.preventDefault();
       
       // If already processing a status change request, ignore this click
       if (ajaxInProgress.changeStatusVente) {
           return;
       }
       
       let status = $('#StatusVente').val();
       let IdVente = $(this).attr('data-id');
       
       if(status == 0) {
           new AWN().alert("Erreur: Ne choisissez pas la première option.", { durations: { alert: 5000 } });
           return false;
       }
       
       // Mark change status operation as in progress and disable the button
       ajaxInProgress.changeStatusVente = true;
       $('#BtnChangeStatusVente').prop('disabled', true).text('Traitement...');
       
       $.ajax({
           type: "POST",
           url: ChangeStatusVente,
           data: {
               'id': IdVente,
               'status': status,
               '_token': csrf_token,
           },
           dataType: "json",
           success: function(response) {
               // Mark change status operation as complete and re-enable the button
               ajaxInProgress.changeStatusVente = false;
               $('#BtnChangeStatusVente').prop('disabled', false).text('Changer le statut');
               
               if(response.status == 200) {
                   $('#ModalEditVente').modal("hide");
                   new AWN().success(response.message, { durations: { success: 5000 } });
                   initializeTableVenteDataTable();
               } else if(response.status == 400) {
                   new AWN().warning(response.message, { durations: { warning: 5000 } });
               } else {
                   new AWN().alert(response.message || "Une erreur est survenue", { durations: { alert: 5000 } });
               }
           },
           error: function(xhr, status, error) {
               // Mark change status operation as complete and re-enable the button
               ajaxInProgress.changeStatusVente = false;
               $('#BtnChangeStatusVente').prop('disabled', false).text('Changer le statut');
               
               console.error('AJAX error:', status, error);
               console.error('Response:', xhr.responseText);
               
               try {
                   var response = JSON.parse(xhr.responseText);
                   new AWN().alert(response.message || "Une erreur est survenue", { durations: { alert: 5000 } });
               } catch(e) {
                   new AWN().alert("Une erreur est survenue, veuillez réessayer.", { durations: { alert: 5000 } });
               }
           }
       });
   });

    // Delete Vente functionality
    $('.TableVente tbody').on('click', '.DeleteVente', function(e) {
        e.preventDefault();
        
        // If already processing a delete request, ignore this click
        if (ajaxInProgress.deleteVente) {
            return;
        }
        
        var idVente = $(this).attr('data-id');
        let notifier = new AWN();
        let deleteButton = $(this);
        
        let onOk = () => {
            // Mark delete operation as in progress and disable the button
            ajaxInProgress.deleteVente = true;
            deleteButton.prop('disabled', true).html('<i class="fa-solid fa-spinner fa-spin text-danger"></i>');
            
            $.ajax({
                type: "post",
                url: "DeleteVente",
                data: {
                    id: idVente,
                    _token: csrf_token,
                },
                dataType: "json",
                success: function (response) {
                    // Mark delete operation as complete
                    ajaxInProgress.deleteVente = false;
                    
                    if(response.status == 200) {
                        new AWN().success(response.message, {durations: {success: 5000}});
                        $('.TableVente').DataTable().ajax.reload();
                    }   
                    else if(response.status == 404) {
                        // Re-enable the button if item not found
                        deleteButton.prop('disabled', false).html('<i class="fa-solid fa-trash text-danger"></i>');
                        new AWN().warning(response.message, {durations: {warning: 5000}});
                    }  
                },
                error: function() {
                    // Mark delete operation as complete and re-enable the button
                    ajaxInProgress.deleteVente = false;
                    deleteButton.prop('disabled', false).html('<i class="fa-solid fa-trash text-danger"></i>');
                    
                    new AWN().alert("Une erreur est survenue, veuillez réessayer.", { durations: { alert: 5000 } });
                }
            });
        };
        
        let onCancel = () => {
            notifier.info('Annulation de la suppression');
        };
        
        notifier.confirm(
            'Êtes-vous sûr de vouloir supprimer cette commande ?',
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

    // Add product functionality
    $('#BtnAddProduct').on('click', function(e) {
        e.preventDefault();
        
        // If already processing an add product request, ignore this click
        if (ajaxInProgress.addProduct) {
            return;
        }
        
        // Create a FormData object to handle file uploads
        let formData = new FormData($('#FormAddProduct')[0]);
        
        // Mark add product operation as in progress and disable the button
        ajaxInProgress.addProduct = true;
        $('#BtnAddProduct').prop('disabled', true).text('Enregistrement...');
        
        $.ajax({
            type: "POST",
            url: AddProduct,
            data: formData,
            processData: false,  // Tell jQuery not to process the data
            contentType: false,  // Tell jQuery not to set contentType
            dataType: "json",
            success: function(response) {
                // Mark add product operation as complete and re-enable the button
                ajaxInProgress.addProduct = false;
                $('#BtnAddProduct').prop('disabled', false).text('Sauvegarder');
                
                if(response.status == 200) {
                    new AWN().success(response.message, {durations: {success: 5000}});
                    
                    // Clear the form
                    $('#FormAddProduct')[0].reset();
                    $('#photo_preview').html('').hide(); // Clear photo preview
                    
                    // Close product modal and reopen vente modal
                    $('#ModalAddProduct').modal("hide");
                    setTimeout(function() {
                        $('#ModalAddVente').modal("show");
                    }, 200);
                    
                    // Optional: refresh product search if needed
                    let searchTerm = $('.input_products').val();
                    if (searchTerm && searchTerm.trim() !== '') {
                        $.ajax({
                            type: "GET",
                            url: getProduct,
                            data: { product: searchTerm },
                            dataType: "json",
                            success: function(searchResponse) {
                                if (searchResponse.status == 200) {
                                    initializeTableProduct('.TableProductVente', searchResponse.data);
                                }
                            }
                        });
                    }
                } else if(response.status == 400) {
                    // Handle validation errors
                    $('.validationAddProduct').html("");
                    $('.validationAddProduct').addClass('alert alert-danger');
                    $.each(response.errors, function(key, list_err) {
                        $('.validationAddProduct').append('<li>' + list_err + '</li>');
                    });
                } else {
                    new AWN().alert(response.message || "Une erreur est survenue", {durations: {alert: 5000}});
                }
            },
            error: function(xhr, status, error) {
                // Mark add product operation as complete and re-enable the button
                ajaxInProgress.addProduct = false;
                $('#BtnAddProduct').prop('disabled', false).text('Sauvegarder');
                
                console.error("Error saving product:", error);
                
                try {
                    const errorResponse = JSON.parse(xhr.responseText);
                    if (errorResponse.errors) {
                        $('.validationAddProduct').html("");
                        $('.validationAddProduct').addClass('alert alert-danger');
                        $.each(errorResponse.errors, function(key, list_err) {
                            $('.validationAddProduct').append('<li>' + list_err + '</li>');
                        });
                    } else {
                        new AWN().alert(errorResponse.message || "Une erreur est survenue lors de l'enregistrement", {durations: {alert: 5000}});
                    }
                } catch (e) {
                    new AWN().alert("Une erreur est survenue lors de l'enregistrement", {durations: {alert: 5000}});
                }
            }
        });
    });

    // Photo preview functionality 
    $('#photo').on('change', function() {
        let file = this.files[0];
        if (file) {
            let reader = new FileReader();
            reader.onload = function(e) {
                $('#photo_preview').html('<img src="' + e.target.result + '" class="img-fluid" style="max-height: 150px;">').show();
            }
            reader.readAsDataURL(file);
        } else {
            $('#photo_preview').html('').hide();
        }
    });
    // ============================================
// FILTER FUNCTIONALITY FOR VENTE
// ============================================

// Handle class filter change
$('#filter_class').on('change', function() {
    var selectedClass = $(this).val();
    
    // Reset dependent dropdowns
    $('#filter_categorie').empty().append('<option value="">Toutes les catégories</option>');
    $('#filter_subcategorie').empty().append('<option value="">Toutes les familles</option>');
    
    if (selectedClass) {
        // Fetch categories for selected class
        $.ajax({
            url: '/vente/categories-by-class',
            type: 'GET',
            data: { class: selectedClass },
            dataType: 'json',
            success: function(response) {
                if (response.status === 200 && response.data.length > 0) {
                    $.each(response.data, function(key, category) {
                        $('#filter_categorie').append(
                            '<option value="' + category.id + '">' + category.name + '</option>'
                        );
                    });
                } else {
                    new AWN().info("Aucune catégorie trouvée pour cette classe", {
                        durations: { info: 3000 }
                    });
                }
            },
            error: function(xhr, status, error) {
                console.error("Error loading categories:", error);
                new AWN().alert("Erreur lors du chargement des catégories", {
                    durations: { alert: 5000 }
                });
            }
        });
    }
});

// Handle category filter change
$('#filter_categorie').on('change', function() {
    var categoryId = $(this).val();
    let name_product = $('.input_products').val().trim();
    
    // Reset subcategory dropdown FIRST
    $('#filter_subcategorie').empty().append('<option value="">Toutes les familles</option>');
    
    if (categoryId) {
        // Visual feedback during search
        $('.input_products').prop('disabled', true);
        $('.TableProductVente_wrapper').addClass('opacity-50');
        
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
                $('.TableProductVente_wrapper').removeClass('opacity-50');
                initializeTableProduct('.TableProductVente', secondResponse.data);
                $('.input_products').val(""); 
            } else {
                $('.input_products').prop('disabled', false);
                $('.TableProductVente_wrapper').removeClass('opacity-50');
                new AWN().info("Aucun produit trouvé.", {durations: {info: 5000}});
            }
        }).fail(function(xhr, status, error) {
            $('.input_products').prop('disabled', false);
            $('.TableProductVente_wrapper').removeClass('opacity-50');
            console.error("Error in second request:", error);
            new AWN().alert("Erreur lors du deuxième chargement", {
                durations: { alert: 5000 }
            });
        });
    }
});

// When user selects a subcategory, refine the product search
$('#filter_subcategorie').on('change', function() {
    var subcategoryId = $(this).val();
    var categoryId = $('#filter_categorie').val();
    let name_product = $('.input_products').val().trim();
    
    if (subcategoryId) {
        // Visual feedback
        $('.input_products').prop('disabled', true);
        $('.TableProductVente_wrapper').addClass('opacity-50');
        
        $.get(getProduct, { 
            product: name_product,
            filter_subcategorie: subcategoryId,
            category: categoryId 
        }, function(response) {
            if (response.status === 200) {
                $('.input_products').prop('disabled', false);
                $('.TableProductVente_wrapper').removeClass('opacity-50');
                initializeTableProduct('.TableProductVente', response.data);
            } else {
                $('.input_products').prop('disabled', false);
                $('.TableProductVente_wrapper').removeClass('opacity-50');
                new AWN().info("Aucun produit trouvé.", {durations: {info: 5000}});
            }
        }).fail(function(xhr, status, error) {
            $('.input_products').prop('disabled', false);
            $('.TableProductVente_wrapper').removeClass('opacity-50');
            console.error("Error loading products:", error);
            new AWN().alert("Erreur lors du chargement des produits", {
                durations: { alert: 5000 }
            });
        });
    }
});

// Handle designation (product name) filter with autocomplete
let searchTimeout;
$('#filter_designation').on('keyup', function() {
    clearTimeout(searchTimeout);
    
    var query = $(this).val().trim();
    
    if (query.length < 2) {
        $('#designation_suggestions').hide().empty();
        return;
    }
    
    searchTimeout = setTimeout(function() {
        $.ajax({
            url: '/vente/search-products',
            type: 'GET',
            data: { query: query },
            dataType: 'json',
            success: function(response) {
                if (response.status === 200 && response.products.length > 0) {
                    var suggestionsHtml = '';
                    $.each(response.products, function(key, product) {
                        suggestionsHtml += '<a href="#" class="list-group-item list-group-item-action product-suggestion" data-name="' + product.name + '">' + product.name + '</a>';
                    });
                    $('#designation_suggestions').html(suggestionsHtml).show();
                } else {
                    $('#designation_suggestions').hide().empty();
                }
            },
            error: function(xhr, status, error) {
                console.error("Error searching products:", error);
            }
        });
    }, 300); // Wait 300ms after user stops typing
});

// Handle click on suggestion
$(document).on('click', '.product-suggestion', function(e) {
    e.preventDefault();
    var productName = $(this).data('name');
    $('#filter_designation').val(productName);
    $('#designation_suggestions').hide().empty();
    
    // Trigger search with selected product
    triggerProductSearch();
});

// Hide suggestions when clicking outside
$(document).on('click', function(e) {
    if (!$(e.target).closest('#filter_designation, #designation_suggestions').length) {
        $('#designation_suggestions').hide();
    }
});

// Function to trigger product search with filters
function triggerProductSearch() {
    let Formateur = $('#DropDown_formateur').val();
    if (Formateur == 0) {
        new AWN().alert('Veuillez sélectionner un demandeur', {durations: {alert: 5000}});
        return false;
    }
    
    // Get filter values
    let filterClass = $('#filter_class').val();
    let filterCategorie = $('#filter_categorie').val();
    let filterSubcategorie = $('#filter_subcategorie').val();
    let filterDesignation = $('#filter_designation').val().trim();
    
    // Build search parameters
    let searchParams = {};
    
    if (filterClass) searchParams.filter_class = filterClass;
    if (filterCategorie) searchParams.filter_categorie = filterCategorie;
    if (filterSubcategorie) searchParams.filter_subcategorie = filterSubcategorie;
    if (filterDesignation) searchParams.product = filterDesignation;
    
    // If no filters are set, show warning
    if (Object.keys(searchParams).length === 0) {
        new AWN().warning('Veuillez saisir au moins un critère de recherche', {
            durations: {warning: 5000}
        });
        return false;
    }
    
    // Visual feedback during search
    $('.input_products').prop('disabled', true);
    $('.TableProductVente_wrapper').addClass('opacity-50');
    
    $.ajax({
        type: "GET",
        url: getProduct,
        data: searchParams,
        dataType: "json",
        success: function(response) {
            // Re-enable input and remove visual feedback
            $('.input_products').prop('disabled', false);
            $('.TableProductVente_wrapper').removeClass('opacity-50');
            
            if (response.status == 200) {
                if (response.data.length > 0) {
                    initializeTableProduct('.TableProductVente', response.data);
                    new AWN().success(response.data.length + ' produit(s) trouvé(s)', {
                        durations: {success: 3000}
                    });
                } else {
                    initializeTableProduct('.TableProductVente', []);
                    new AWN().info("Aucun produit trouvé avec ces critères", {
                        durations: {info: 5000}
                    });
                }
            } else {
                new AWN().info("Aucun produit trouvé.", {durations: {info: 5000}});
            }
        },
        error: function(xhr, status, error) {
            // Re-enable input and remove visual feedback
            $('.input_products').prop('disabled', false);
            $('.TableProductVente_wrapper').removeClass('opacity-50');
            
            console.error("Error searching for product:", error);
            console.error("Response:", xhr.responseText);
            
            try {
                const errorData = JSON.parse(xhr.responseText);
                new AWN().alert(errorData.message || "Erreur lors de la recherche", {
                    durations: {alert: 5000}
                });
            } catch (e) {
                new AWN().alert("Erreur lors de la recherche", {
                    durations: {alert: 5000}
                });
            }
        }
    });
}

// Trigger search when filters change
$('#filter_class, #filter_categorie, #filter_subcategorie').on('change', function() {
    // Only trigger search if at least one filter is set or designation has value
    if ($(this).val() || $('#filter_designation').val().trim()) {
        triggerProductSearch();
    }
});

// Trigger search when pressing Enter in designation field
$('#filter_designation').on('keydown', function(e) {
    if (e.keyCode === 13) {
        e.preventDefault();
        $('#designation_suggestions').hide();
        triggerProductSearch();
    }
});







});