@extends('dashboard.index')

@section('dashboard')
<!-- Required CSS for DateRangePicker -->
<link rel="stylesheet" type="text/css" href="https://cdn.jsdelivr.net/npm/daterangepicker/daterangepicker.css" />

<script src="{{asset('js/audit/script.js')}}"></script>
<script type="text/javascript" src="https://cdn.jsdelivr.net/momentjs/latest/moment.min.js"></script>
<script type="text/javascript" src="https://cdn.jsdelivr.net/npm/daterangepicker/daterangepicker.min.js"></script>
<script>
    var csrf_token = "{{csrf_token()}}";
    var auditUrl = "{{url('audit')}}";
</script> 
<div class="content-page">
    <div class="content">

        <!-- Start Content-->
        <div class="container-fluid">

            <div class="py-3 d-flex align-items-sm-center flex-sm-row flex-column">
                <div class="flex-grow-1">
                    <h4 class="fs-18 fw-semibold m-0">Historique des modifications</h4>
                </div>
                
                <div class="text-end">
                    <ol class="breadcrumb m-0 py-0">
                        <li class="breadcrumb-item"><a href="javascript: void(0);">Applications</a></li>
                        <li class="breadcrumb-item active">Historique</li>
                    </ol>
                </div>
            </div>

            <div class="row">
                <div class="col-12">
                    <div class="card">

                        <div class="card-body">
                            <div class="mb-3">
                                <div class="row">
                                    <div class="col-md-3">
                                        <div class="form-group mb-3">
                                            <label>Type</label>
                                            <select id="modelFilter" class="form-control">
                                                <option value="">Tous les types</option>
                                                <!-- <option value="client">Formateur</option> -->
                                                <option value="fournisseur">Fournisseurs</option>
                                                <option value="local">Locaux</option>
                                                <option value="tva">TVA</option>
                                                <option value="rayon">Rayons</option>
                                                <option value="unite">Unités</option>
                                                <option value="category">Catégories</option>
                                                <option value="subcategory">Famille</option>
                                                <option value="product">Produits</option>
                                                <option value="user">Utilisateurs</option>
                                                <option value="commande">Commandes</option>
                                                <option value="achat">Achats</option>
                                                <option value="transfer">Transfers</option>
                                                <option value="retour">Retours</option>
                                                <!-- Add other model types here -->
                                            </select>
                                        </div>
                                    </div>
                                    <div class="col-md-3">
                                        <div class="form-group mb-3">
                                            <label>Utilisateur</label>
                                            <select id="userFilter" class="form-control">
                                                <option value="">Tous les utilisateurs</option>
                                                @foreach(\Illuminate\Support\Facades\DB::table('users')->whereNull('deleted_at')->select('id', \Illuminate\Support\Facades\DB::raw("CONCAT(COALESCE(prenom, ''), ' ', COALESCE(nom, '')) as name"))->orderBy('prenom')->get() as $user)
                                                    <option value="{{ $user->id }}">{{ $user->name }}</option>
                                                @endforeach
                                            </select>
                                        </div>
                                    </div>
                                    <div class="col-md-3">
                                        <div class="form-group mb-3">
                                            <label>Action</label>
                                            <select id="eventFilter" class="form-control">
                                                <option value="">Toutes les actions</option>
                                                <option value="created">Création</option>
                                                <option value="updated">Modification</option>
                                                <option value="deleted">Suppression</option>
                                                <option value="restored">Restauration</option>
                                            </select>
                                        </div>
                                    </div>
                                    <div class="col-md-3">
                                        <div class="form-group mb-3">
                                            <label>Période</label>
                                            <input type="text" id="dateRangeFilter" class="form-control" placeholder="Sélectionner une période" readonly>
                                        </div>
                                    </div>
                                </div>
                                <div class="row mt-2">
                                    <div class="col-md-12">
                                        <!-- Filter Quick Actions -->
                                        <div class="d-flex justify-content-between align-items-center">
                                            <div class="filter-info">
                                                <small class="text-muted">
                                                    <i class="fa fa-info-circle"></i> 
                                                    Filtrez par type, utilisateur, action ou période pour affiner les résultats
                                                </small>
                                            </div>
                                            <div class="filter-actions">
                                                <button id="clearFilters" class="btn btn-secondary btn-sm me-2">
                                                    <i class="fa fa-filter-circle-xmark"></i> Réinitialiser les filtres
                                                </button>
                                                @can('Historique-Export')
                                                <button id="exportAuditCSV" class="btn btn-success btn-sm" data-url="{{ url('audit/export') }}">
                                                    <i class="fa fa-file-csv"></i> Exporter CSV
                                                </button>
                                                @endcan
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            
                            <!-- Quick Stats Row -->
                            <!-- <div class="row mb-3">
                                <div class="col-12">
                                    <div class="alert alert-info border-0 shadow-sm">
                                        <div class="row text-center">
                                            <div class="col-md-3">
                                                <div class="d-flex align-items-center justify-content-center">
                                                    <i class="fa fa-exchange-alt text-info me-2"></i>
                                                    <div>
                                                        <strong>Transfers</strong><br>
                                                        <small class="text-muted">Mouvements entre utilisateurs</small>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="col-md-3">
                                                <div class="d-flex align-items-center justify-content-center">
                                                    <i class="fa fa-undo text-warning me-2"></i>
                                                    <div>
                                                        <strong>Retours</strong><br>
                                                        <small class="text-muted">Retours au stock</small>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="col-md-3">
                                                <div class="d-flex align-items-center justify-content-center">
                                                    <i class="fa fa-shopping-cart text-success me-2"></i>
                                                    <div>
                                                        <strong>Commandes</strong><br>
                                                        <small class="text-muted">Gestion des ventes</small>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="col-md-3">
                                                <div class="d-flex align-items-center justify-content-center">
                                                    <i class="fa fa-truck text-primary me-2"></i>
                                                    <div>
                                                        <strong>Achats</strong><br>
                                                        <small class="text-muted">Approvisionnements</small>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div> -->

                            <div class="table-responsive">
                                <div class="datatable-wrapper datatable-loading no-footer sortable fixed-height fixed-columns">
                                    
                                    <div class="datatable-container" style="height: 665.531px;">
                                        <table class="table datatable datatable-table TableAudits" >
                                            <thead>
                                                <tr>
                                                    <th data-sortable="true">Type</th>
                                                    <!-- <th data-sortable="true">Élément</th> -->
                                                    <th data-sortable="true">Action</th>
                                                    <th data-sortable="true">Utilisateur</th>
                                                    <th data-sortable="true">Détails</th>
                                                    <th data-sortable="true">Date</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                <!-- Data will be loaded here -->
                                            </tbody>
                                        </table>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
// Ajout du script pour le bouton "Réinitialiser les filtres"
$(document).ready(function() {
    // Initialisation du bouton de réinitialisation des filtres
    $('#clearFilters').on('click', function() {
        $('#modelFilter').val('');
        $('#userFilter').val('');
        $('#eventFilter').val('');
        $('#dateRangeFilter').val('');
        
        // Recharger le tableau
        $('.TableAudits').DataTable().ajax.reload();
    });
    
    // Initialisation du bouton d'exportation CSV
    $('#exportAuditCSV').on('click', function() {
        // Préparation des filtres pour l'URL d'exportation
        let queryParams = {};
        
        if ($('#modelFilter').val()) {
            queryParams.model_type = $('#modelFilter').val();
        }
        
        if ($('#userFilter').val()) {
            queryParams.user_id = $('#userFilter').val();
        }
        
        if ($('#eventFilter').val()) {
            queryParams.event = $('#eventFilter').val();
        }
        
        if ($('#dateRangeFilter').val()) {
            const dates = $('#dateRangeFilter').data('daterangepicker');
            queryParams.start_date = dates.startDate.format('YYYY-MM-DD');
            queryParams.end_date = dates.endDate.format('YYYY-MM-DD');
        }
        
        // Construction de la chaîne de requête
        const queryString = Object.keys(queryParams)
            .map(key => encodeURIComponent(key) + '=' + encodeURIComponent(queryParams[key]))
            .join('&');
        
        // Redirection vers l'URL d'exportation
        window.location.href = $(this).data('url') + '?' + queryString;
    });
    
    // Quick filter buttons for common types
    $('.btn-filter-quick').on('click', function() {
        const filterType = $(this).data('filter');
        $('#modelFilter').val(filterType);
        $('.TableAudits').DataTable().ajax.reload();
        
        // Update button states
        $('.btn-filter-quick').removeClass('active');
        $(this).addClass('active');
    });
});
</script>

<!-- Additional CSS for enhanced styling -->
<style>
.filter-info {
    flex: 1;
}

.filter-actions {
    white-space: nowrap;
}

.btn-filter-quick {
    transition: all 0.3s ease;
}

.btn-filter-quick:hover {
    transform: translateY(-1px);
    box-shadow: 0 4px 8px rgba(0,0,0,0.1);
}

.alert-info {
    background: linear-gradient(135deg, #e3f2fd 0%, #bbdefb 100%);
    border: none;
}

.badge {
    font-size: 0.75em;
    padding: 0.375rem 0.75rem;
}

.table thead th {
    border-bottom: 2px solid #dee2e6;
    font-weight: 600;
    text-transform: uppercase;
    font-size: 0.875rem;
    letter-spacing: 0.05em;
}
</style>
@endsection