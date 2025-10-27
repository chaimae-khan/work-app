@extends('dashboard.index')

@section('dashboard')
<div class="content-page">
    <div class="content">
        <div class="container-fluid">
            <!-- start page title -->
            <div class="row">
                <div class="col-12">
                    <div class="page-title-box">
                        <div class="page-title-right">
                            <ol class="breadcrumb m-0">
                                <li class="breadcrumb-item"><a href="/home">Accueil</a></li>
                                <li class="breadcrumb-item active">Analyse Mensuelle des Coûts</li>
                            </ol>
                        </div>
                        <h4 class="page-title">Analyse Mensuelle des Coûts par Jour et par Catégorie</h4>
                    </div>
                </div>
            </div>

            <!-- Filters card -->
            <div class="row">
                <div class="col-12">
                    <div class="card">
                        <div class="card-body">
                            <h4 class="header-title">Filtres</h4>
                            
                            <form id="monthlyBreakdownForm" class="row g-3">
                                <div class="col-md-6">
                                    <label for="month" class="form-label">Mois</label>
                                    <input type="month" class="form-control" id="month" name="month" required>
                                </div>
                                
                                <div class="col-md-6">
                                    <label for="type_menu" class="form-label">Type de Menu</label>
                                    <select class="form-select" id="type_menu" name="type_menu" required>
                                        <option value="Menu eleves" selected>Menu standard</option>
                                        <option value="Menu specials">Menu specials</option>
                                        <option value="Menu d'application">Menu d'application</option>
                                    </select>
                                </div>
                                
                                <!-- Hidden Type de Commande input -->
                                <input type="hidden" id="type_commande" name="type_commande" value="Alimentaire">
                                
                                <div class="col-12 mt-3">
                                    <button type="submit" class="btn btn-primary" id="btnSearch">
                                        <i class="fa fa-search"></i> Rechercher
                                    </button>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>

            <!-- No data message -->
            <div class="row" id="noDataMessage" style="display: none;">
                <div class="col-12">
                    <div class="card">
                        <div class="card-body text-center">
                            <i class="fas fa-exclamation-circle fa-3x text-warning mb-3"></i>
                            <h4>Aucune donnée trouvée pour ce mois et ces critères.</h4>
                            <p>Veuillez essayer un autre mois ou d'autres filtres.</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Modal for Monthly Report -->
<div class="modal fade" id="ModalMonthlyReport" tabindex="-1" aria-labelledby="ModalMonthlyReportLabel" aria-hidden="true">
    <div class="modal-dialog modal-xl">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="ModalMonthlyReportLabel">Consommation du mois</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Fermer"></button>
            </div>
            <div class="modal-body">
                <div id="printSection" class="table-responsive">
                    <div id="monthlyReportContent">
                        <table class="table table-bordered" id="monthlyReportTable">
                            <thead>
                                <tr>
                                    <th>Journée du</th>
                                    <th>Coût unitaire par stagiaire</th>
                                    <th>Légumes et Fruits</th>
                                    <th>Volailles et Œufs</th>
                                    <th>Poisson Frais</th>
                                    <th>Épicerie et Produits Laitiers</th>
                                    <th>Viandes</th>
                                    <th>Coût total de la journée</th>
                                    <th>Effectif</th>
                                </tr>
                            </thead>
                            <tbody id="monthlyReportBody">
                                <!-- Data will be populated here -->
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary no-print" data-bs-dismiss="modal">Fermer</button>
                <!-- Removed Print button -->
                <button type="button" class="btn btn-primary no-print" id="btnExportPDF">
                    <i class="fa fa-file-pdf"></i> Exporter PDF
                </button>
            </div>
        </div>
    </div>
</div>

<style>
    /* Styling to match the image */
    #monthlyReportTable {
        border-collapse: collapse;
        width: 100%;
    }
    
    #monthlyReportTable th, 
    #monthlyReportTable td {
        border: 1px solid #000;
        padding: 8px;
        text-align: center;
    }
    
    #monthlyReportTable th {
        background-color: #f2f2f2;
    }
    
    .week-header {
        background-color: #f8f9fa;
        font-weight: bold;
    }
    
    /* Add print styles */
    @media print {
        body * {
            visibility: hidden;
        }
        #printSection, #printSection * {
            visibility: visible;
        }
        #printSection {
            position: absolute;
            left: 0;
            top: 0;
            width: 100%;
        }
        .no-print {
            display: none !important;
        }
    }
    
    .table-responsive {
        overflow-x: auto;
    }
    .fa-spinner {
        animation: spin 1s linear infinite;
    }
    @keyframes spin {
        0% { transform: rotate(0deg); }
        100% { transform: rotate(360deg); }
    }
    
    /* Make the modal wider */
    @media (min-width: 992px) {
        .modal-xl {
            max-width: 95%;
        }
    }
</style>

<script type="text/javascript">
    // Define URLs for API endpoints
    const getMonthlyBreakdownData_url = "{{ route('get.monthly.breakdown') }}";
    const exportMonthlyBreakdownPDF_url = "{{ route('export.monthly.breakdown.pdf') }}";
    
    $(document).ready(function() {
        // Initialize month input to current month
        const today = new Date();
        const currentMonth = today.getFullYear() + '-' + String(today.getMonth() + 1).padStart(2, '0');
        $('#month').val(currentMonth);
        
        // Handle form submission
        $('#monthlyBreakdownForm').on('submit', function(e) {
            e.preventDefault();
            
            const month = $('#month').val();
            const typeMenu = $('#type_menu').val();
            const typeCommande = $('#type_commande').val();
            
            if (!month) {
                new AWN().alert('Veuillez sélectionner un mois');
                return;
            }
            
            $.ajax({
                type: "GET",
                url: getMonthlyBreakdownData_url,
                data: {
                    month: month,
                    type_menu: typeMenu,
                    type_commande: typeCommande
                },
                dataType: "json",
                beforeSend: function() {
                    $('#btnSearch').prop('disabled', true);
                    $('#btnSearch').html('<i class="fa fa-spinner fa-spin"></i> Chargement...');
                    $('#noDataMessage').hide();
                },
                success: function(response) {
                    if (response.status == 200 && response.data.days_data.length > 0) {
                        displayMonthlyReport(response.data);
                        // Show the modal
                        $('#ModalMonthlyReport').modal('show');
                        $('#noDataMessage').hide();
                    } else {
                        $('#noDataMessage').show();
                    }
                },
                error: function(xhr, status, error) {
                    console.error("Error:", error);
                    new AWN().alert("Erreur lors de la récupération des données");
                    $('#noDataMessage').hide();
                },
                complete: function() {
                    $('#btnSearch').prop('disabled', false);
                    $('#btnSearch').html('<i class="fa fa-search"></i> Rechercher');
                }
            });
        });

        // Function to display monthly report data in modal
        function displayMonthlyReport(data) {
            // Set report title in modal
            $('#ModalMonthlyReportLabel').text('Consommation du mois ' + data.month);
            
            // Group days by week
            const groupedDays = groupDaysByWeek(data.days_data);
            
            // Generate table content
            const tbody = $('#monthlyReportBody');
            tbody.empty();
            
            Object.keys(groupedDays).forEach(weekKey => {
                const days = groupedDays[weekKey];
                
                // Add week header
                tbody.append(`
                    <tr>
                        <td colspan="9" class="text-center week-header">la Semaine du ${weekKey}</td>
                    </tr>
                `);
                
                // Add each day in week
                days.forEach(day => {
                    const row = buildDayRow(day);
                    tbody.append(row);
                });
            });
        }
        
        // Function to group days by week
        function groupDaysByWeek(days) {
            const weeks = {};
            
            days.forEach(day => {
                // Parse date
                const dateParts = day.date.split('/');
                const dayDate = new Date(dateParts[2], parseInt(dateParts[1])-1, parseInt(dateParts[0]));
                
                // Get week boundaries (Monday to Sunday)
                const firstDayOfWeek = new Date(dayDate);
                const day_of_week = dayDate.getDay() || 7; // Convert Sunday (0) to 7
                if (day_of_week !== 1) // If not Monday
                    firstDayOfWeek.setDate(dayDate.getDate() - (day_of_week - 1));
                
                const lastDayOfWeek = new Date(firstDayOfWeek);
                lastDayOfWeek.setDate(firstDayOfWeek.getDate() + 6);
                
                // Create week key
                const weekKey = `${formatDateFr(firstDayOfWeek)} au ${formatDateFr(lastDayOfWeek)}`;
                
                if (!weeks[weekKey]) {
                    weeks[weekKey] = [];
                }
                
                weeks[weekKey].push(day);
            });
            
            // Sort days within each week
            Object.keys(weeks).forEach(weekKey => {
                weeks[weekKey].sort((a, b) => {
                    const dateA = parseFrDate(a.date);
                    const dateB = parseFrDate(b.date);
                    return dateA - dateB;
                });
            });
            
            return weeks;
        }
        
        // Build a table row for a day
        function buildDayRow(day) {
            // Get day name
            const dateParts = day.date.split('/');
            const dayDate = new Date(dateParts[2], parseInt(dateParts[1])-1, parseInt(dateParts[0]));
            const dayNames = ['Dimanche', 'Lundi', 'Mardi', 'Mercredi', 'Jeudi', 'Vendredi', 'Samedi'];
            const dayName = dayNames[dayDate.getDay()];
            
            // Define category mappings - adjust these based on your actual category names
            const categoryMappings = {
                'Légumes et Fruits': ['légumes', 'fruits', 'légume', 'fruit'],
                'Volailles et Œufs': ['volaille', 'oeuf', 'œuf', 'poulet', 'poule'],
                'Poisson Frais': ['poisson'],
                'Épicerie et Produits Laitiers': ['épicerie', 'lait', 'laitier', 'fromage', 'yaourt'],
                'Viandes': ['viande', 'boeuf', 'bœuf', 'agneau', 'veau']
            };
            
            // Extract costs for each category
            const costs = {};
            for (const displayName in categoryMappings) {
                costs[displayName] = 0;
                const keywords = categoryMappings[displayName];
                
                // Find matching categories
                for (const category of day.category_costs) {
                    if (keywords.some(keyword => 
                        category.name.toLowerCase().includes(keyword.toLowerCase()) ||
                        displayName.toLowerCase().includes(category.name.toLowerCase())
                    )) {
                        costs[displayName] += parseFloat(category.total_cost);
                    }
                }
            }
            
            // Format the row
            return `
                <tr>
                    <td>${dayName} ${day.date}</td>
                    <td>${formatCost(day.prix_moyen)}</td>
                    <td>${formatCost(costs['Légumes et Fruits'])}</td>
                    <td>${formatCost(costs['Volailles et Œufs'])}</td>
                    <td>${formatCost(costs['Poisson Frais'])}</td>
                    <td>${formatCost(costs['Épicerie et Produits Laitiers'])}</td>
                    <td>${formatCost(costs['Viandes'])}</td>
                    <td>${formatCost(day.total_cost)}</td>
                    <td>${day.total_people}</td>
                </tr>
            `;
        }
        
        // Helper function to format a date as DD Month YYYY in French
        function formatDateFr(date) {
            const day = date.getDate();
            const monthNames = [
                'Janvier', 'Février', 'Mars', 'Avril', 'Mai', 'Juin',
                'Juillet', 'Août', 'Septembre', 'Octobre', 'Novembre', 'Décembre'
            ];
            const month = monthNames[date.getMonth()];
            const year = date.getFullYear();
            
            return `${day} ${month} ${year}`;
        }
        
        // Helper function to parse a date string in DD/MM/YYYY format
        function parseFrDate(dateStr) {
            const parts = dateStr.split('/');
            return new Date(parts[2], parts[1] - 1, parts[0]);
        }
        
        // Helper function to format cost values
        function formatCost(value) {
            if (!value || parseFloat(value) === 0) {
                return '-';
            }
            return parseFloat(value).toFixed(2);
        }
        
        // PDF Export functionality
        $('#btnExportPDF').on('click', function(e) {
            e.preventDefault();
            
            const month = $('#month').val();
            const typeMenu = $('#type_menu').val();
            const typeCommande = $('#type_commande').val();
            
            if (!month) {
                new AWN().alert('Veuillez sélectionner un mois');
                return;
            }
            
            const form = document.createElement('form');
            form.method = 'GET';
            form.action = exportMonthlyBreakdownPDF_url;
            
            const fields = {
                month: month,
                type_menu: typeMenu,
                type_commande: typeCommande
            };
            
            for (const [key, value] of Object.entries(fields)) {
                const hidden = document.createElement('input');
                hidden.type = 'hidden';
                hidden.name = key;
                hidden.value = value;
                form.appendChild(hidden);
            }
            
            document.body.appendChild(form);
            form.submit();
            document.body.removeChild(form);
        });
    });
</script>

@endsection