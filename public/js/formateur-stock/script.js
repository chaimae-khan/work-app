$(document).ready(function () {
    
    // Initialize DataTable
    if ($.fn.DataTable.isDataTable('.TableFormateurStock')) {
        $('.TableFormateurStock').DataTable().destroy();
    }
    
    var tableFormateurStock = $('.TableFormateurStock').DataTable({
        // COMMENTED OUT: Remove buttons from DOM
        // dom: 'Bfrtip', // Important for buttons to show
        // buttons: [
        //     {
        //         extend: 'copyHtml5',
        //         text: 'Copier',
        //         exportOptions: {
        //             columns: [0, ':visible']
        //         }
        //     },
        //     {
        //         extend: 'excelHtml5',
        //         text: 'Exporter Excel',
        //         title: function() {
        //             return 'GESTOCK TOUARGA - Mon Stock - Date: ' + new Date().toLocaleDateString('fr-FR');
        //         },
        //         exportOptions: {
        //             columns: ':visible'
        //         },
        //         customize: function(xlsx) {
        //             var sheet = xlsx.xl.worksheets['sheet1.xml'];
        //             
        //             // Center align all cells
        //             $('row c', sheet).attr('s', '51'); // Apply center alignment style
        //             
        //             // Style headers (first row)
        //             $('row:first c', sheet).attr('s', '2'); // Make headers bold
        //         }
        //     },
        //     {
        //         extend: 'pdfHtml5',
        //         text: 'Exporter PDF',
        //         title: function() {
        //             return 'GESTOCK TOUARGA - Mon Stock - Date: ' + new Date().toLocaleDateString('fr-FR');
        //         },
        //         exportOptions: {
        //             columns: ':visible'
        //         },
        //         customize: function(doc) {
        //             // Center the table
        //             doc.content[1].table.widths = Array(doc.content[1].table.body[0].length).fill('*');
        //             
        //             // Style the title
        //             doc.styles.title.alignment = 'center';
        //             doc.styles.title.fontSize = 14;
        //             doc.styles.title.bold = true;
        //             
        //             doc.styles.tableHeader.alignment = 'center';
        //             doc.styles.tableBodyEven.alignment = 'center';
        //             doc.styles.tableBodyOdd.alignment = 'center';
        //         }
        //     },
        //     {
        //         extend: 'colvis',
        //         text: 'Colonnes'
        //     }
        // ],
        processing: true,
        serverSide: false,
        ajax: {
            url: formateurStockUrl,
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
            { data: 'quantite', name: 'quantite' },
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
        }
    });
});