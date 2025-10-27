@extends('dashboard.index')

@section('dashboard')
<!-- ============================================================== -->
<!-- Start Page Content here -->
<!-- ============================================================== -->
<div class="content-page">
    <div class="content">

        <!-- Start Content-->
        <div class="container-fluid">

            <!-- start page title -->
            <div class="row">
                <div class="col-12">
                    <div class="page-title-box">
                        <div class="page-title-right">
                            <ol class="breadcrumb m-0">
                                <li class="breadcrumb-item"><a href="/home">Accueil</a></li>
                                <li class="breadcrumb-item active">Coûts par Catégorie</li>
                            </ol>
                        </div>
                        <h4 class="page-title">Coûts par Catégorie (DENRÉES ALIMENTAIRES)</h4>
                    </div>
                </div>
            </div>
            <!-- end page title -->

            <div class="row">
                <div class="col-12">
                    <div class="card">
                        <div class="card-body">
                            <h4 class="header-title">Filtres</h4>
                            
                            <form id="categoryFilterForm" class="row g-3">
                                <div class="col-md-4">
                                    <label for="consumption_date" class="form-label">Date</label>
                                    <input type="date" class="form-control" id="consumption_date" name="date" required>
                                </div>
                                
                                <div class="col-md-4">
                                    <label for="type_menu" class="form-label">Type de Menu</label>
                                    <select class="form-select" id="type_menu" name="type_menu" required>
                                        <option value="all">Tous les Menus</option>
                                        <option value="Menu eleves">Menu eleves</option>
                                        <option value="Menu specials">Menu specials</option>
                                        <option value="Menu d'application">Menu d'application</option>
                                    </select>
                                </div>
                                
                                <div class="col-md-4 d-flex align-items-end">
                                    <button type="submit" class="btn btn-primary" id="btnSearch">
                                        <i class="fa fa-search"></i> Rechercher
                                    </button>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Results card, initially hidden -->
            <div class="row" id="resultsCard" style="display: none;">
                <div class="col-12">
                    <div class="card">
                        <div class="card-body">
                            <div class="d-flex justify-content-between align-items-center mb-3">
                                <h4 class="header-title">Résultats - <span id="resultDate"></span> - <span id="resultMenu"></span></h4>
                                <button type="button" class="btn btn-sm btn-outline-success" id="btnExportPDF">
                                    <i class="fas fa-file-pdf"></i> Exporter PDF
                                </button>
                            </div>
                            
                            <div class="table-responsive">
                                <table class="table table-bordered table-striped table-centered">
                                    <thead>
                                        <tr>
                                            <th>Catégorie</th>
                                            <th>Coût Total</th>
                                            <th>% du Coût Total</th>
                                        </tr>
                                    </thead>
                                    <tbody id="categoryCostsTableBody">
                                        <!-- Data will be populated here -->
                                    </tbody>
                                    <tfoot>
                                        <tr class="table-secondary">
                                            <th>Total</th>
                                            <th id="totalCost">0.00</th>
                                            <th>100%</th>
                                        </tr>
                                    </tfoot>
                                </table>
                            </div>
                            
                            <div class="row mt-3">
                                <div class="col-md-6">
                                    <div class="card bg-light">
                                        <div class="card-body">
                                            <h5 class="card-title">Effectif Total</h5>
                                            <h3 class="card-text" id="totalPeople">0</h3>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="card bg-light">
                                        <div class="card-body">
                                            <h5 class="card-title">Prix Moyen par Personne</h5>
                                            <h3 class="card-text" id="prixMoyen">0.00</h3>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            
                            <div class="row mt-3">
                                <div class="col-12">
                                    <div class="card border border-primary">
                                        <div class="card-body">
                                            <h5 class="card-title text-primary">Prix de Revient de la Journée</h5>
                                            <h2 class="card-text" id="prixRevient">0.00</h2>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- No data message, initially hidden -->
            <div class="row" id="noDataMessage" style="display: none;">
                <div class="col-12">
                    <div class="card">
                        <div class="card-body text-center">
                            <i class="fas fa-exclamation-circle fa-3x text-warning mb-3"></i>
                            <h4>Aucune donnée alimentaire trouvée pour cette date et ce menu.</h4>
                            <p>Veuillez essayer une autre date ou un autre type de menu.</p>
                        </div>
                    </div>
                </div>
            </div>

        </div>
        <!-- container -->

    </div>
    <!-- content -->

    <footer class="footer">
        <div class="container-fluid">
            <div class="row">
                <div class="col-md-6">© TOUARGA</div>
                <div class="col-md-6">
                    <div class="text-md-end footer-links d-none d-sm-block">
                        <a href="javascript:void(0);">À propos</a>
                        <a href="javascript:void(0);">Aide</a>
                        <a href="javascript:void(0);">Contactez-nous</a>
                    </div>
                </div>
            </div>
        </div>
    </footer>

</div>
<!-- ============================================================== -->
<!-- End Page content -->
<!-- ============================================================== -->

<script type="text/javascript">
    // Define URLs for API endpoints
    const getCategoryCostsData_url = "{{ route('get.category.costs') }}";
    const exportCategoryCostsPDF_url = "{{ route('export.category.costs.pdf') }}";
    
    $(document).ready(function() {
        // Initialize datepicker to current date
        const today = new Date().toISOString().split('T')[0];
        $('#consumption_date').val(today);
        
        // Handle form submission
        $('#categoryFilterForm').on('submit', function(e) {
            e.preventDefault();
            
            const date = $('#consumption_date').val();
            const typeMenu = $('#type_menu').val();
            
            if (!date) {
                new AWN().alert('Veuillez sélectionner une date');
                return;
            }
            
            $.ajax({
                type: "GET",
                url: getCategoryCostsData_url,
                data: {
                    date: date,
                    type_menu: typeMenu
                },
                dataType: "json",
                beforeSend: function() {
                    $('#btnSearch').prop('disabled', true);
                    $('#btnSearch').html('<i class="fa fa-spinner fa-spin"></i> Chargement...');
                    $('#resultsCard, #noDataMessage').hide();
                },
                success: function(response) {
                    if (response.status == 200) {
                        displayCategoryCostsData(response.data);
                        $('#resultsCard').show();
                        $('#noDataMessage').hide();
                    } else {
                        $('#resultsCard').hide();
                        $('#noDataMessage').show();
                    }
                },
                error: function(xhr, status, error) {
                    console.error("Error:", error);
                    console.error("XHR:", xhr);
                    new AWN().alert("Erreur lors de la récupération des données");
                    $('#resultsCard, #noDataMessage').hide();
                },
                complete: function() {
                    $('#btnSearch').prop('disabled', false);
                    $('#btnSearch').html('<i class="fa fa-search"></i> Rechercher');
                }
            });
        });
        
        // PDF Export functionality
        $('#btnExportPDF').on('click', function(e) {
            e.preventDefault();
            
            const date = $('#consumption_date').val();
            const typeMenu = $('#type_menu').val();
            
            if (!date) {
                new AWN().alert('Veuillez sélectionner une date');
                return;
            }
            
            // Create a form and submit it for PDF download
            const form = document.createElement('form');
            form.method = 'GET';
            form.action = exportCategoryCostsPDF_url;
            
            // Create hidden fields
            const fields = {
                date: date,
                type_menu: typeMenu
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
        
        // Function to display category costs data
        function displayCategoryCostsData(data) {
            // Update header info
            $('#resultDate').text(data.date);
            $('#resultMenu').text(data.type_menu === 'all' ? 'Tous les Menus' : data.type_menu);
            
            // Update summary values
            $('#totalCost').text(parseFloat(data.total_cost).toFixed(2));
            $('#totalPeople').text(data.total_people);
            $('#prixMoyen').text(parseFloat(data.prix_moyen).toFixed(2));
            $('#prixRevient').text(parseFloat(data.total_cost).toFixed(2));
            
            // Build table rows
            let tableHtml = '';
            
            if (data.category_costs && data.category_costs.length > 0) {
                data.category_costs.forEach(category => {
                    const percentage = (data.total_cost > 0) ? 
                        ((category.total_cost / data.total_cost) * 100).toFixed(2) : 0;
                    
                    tableHtml += `
                        <tr>
                            <td>${category.name}</td>
                            <td>${parseFloat(category.total_cost).toFixed(2)}</td>
                            <td>${percentage}%</td>
                        </tr>
                    `;
                });
            } else {
                tableHtml = `
                    <tr>
                        <td colspan="3" class="text-center">Aucune donnée de catégorie disponible</td>
                    </tr>
                `;
            }
            
            $('#categoryCostsTableBody').html(tableHtml);
        }
    });
</script>

@endsection