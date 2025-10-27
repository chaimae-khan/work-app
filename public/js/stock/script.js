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
            { data: 'tva_value', name: 't.value' },
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