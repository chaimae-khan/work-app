@extends('dashboard.index')

@section('dashboard')
<script src="{{ asset('js/stock/script.js') }}"></script>
<script>
    var csrf_token = "{{ csrf_token() }}";
    var stockUrl = "{{ url('stock') }}";
    var alertCountUrl = "{{ url('stock/alert-count') }}";
    var stockExportExcelUrl = "{{ url('stock/export-excel') }}";
    var stockExportPdfUrl = "{{ url('stock/export-pdf') }}";
</script>
<div class="content-page">
    <div class="content">

        <!-- Start Content-->
        <div class="container-fluid">

            <div class="py-3 d-flex align-items-sm-center flex-sm-row flex-column">
                <div class="flex-grow-1">
                    <h4 class="fs-18 fw-semibold m-0">Gestion de Stock</h4>
                </div>
                
                <div class="text-end">
                    <ol class="breadcrumb m-0 py-0">
                        <li class="breadcrumb-item"><a href="javascript: void(0);">Applications</a></li>
                        <li class="breadcrumb-item active">Stock</li>
                    </ol>
                </div>
            </div>

            <div class="row">
                <div class="col-12">
                    <div class="card">
                        <div class="card-body">
                            <div class="alert alert-warning alert-dismissible fade" role="alert" id="stock-alert" style="display: none;">
                                <strong><i class="fa-solid fa-triangle-exclamation me-2"></i>Attention!</strong> 
                                la quantité de <span id="alert-count">0</span> produit(s) est presque épuisée.
                                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                            </div>
                                                    
                            <div class="table-responsive">
                                <div class="datatable-wrapper datatable-loading no-footer sortable fixed-height fixed-columns">
                                    
                                    <div class="datatable-container">
                                        <table class="table datatable datatable-table TableStock">
                                            <thead>
                                                <tr>
                                                    <th data-sortable="true">Code article</th>
                                                    <th data-sortable="true">Nom du Produit</th>
                                                    <th data-sortable="true">Unité</th>
                                                    <th data-sortable="true">Catégorie</th>
                                                    <th data-sortable="true">Famille</th>
                                                    <th data-sortable="true">Emplacement</th>
                                                    <th data-sortable="true">Stock</th>
                                                    <th data-sortable="true">Prix d'achat</th>
                                                    <th data-sortable="true">Taux TVA</th>
                                                    <th data-sortable="true">Seuil</th>
                                                    <!-- <th data-sortable="true">Code barre</th>
                                                    <th data-sortable="false">Photo</th> -->
                                                    <th data-sortable="true">Date d'expiration</th>
                                                    <th data-sortable="true">Date de réception</th>
                                                    <th data-sortable="false">Statut</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                <!-- Table content will be dynamically generated -->
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

<style>
    /* Stock table styles */
    .TableStock tr.bg-danger-subtle {
        background-color: rgba(var(--bs-danger-rgb), 0.15) !important;
    }
    
    .TableStock tr.bg-danger-subtle td {
        color: var(--bs-danger) !important;
        font-weight: 500;
    }
    
    .badge.bg-danger {
        white-space: nowrap;
    }
    
    .badge.bg-success {
        white-space: nowrap;
    }
</style>
@endsection