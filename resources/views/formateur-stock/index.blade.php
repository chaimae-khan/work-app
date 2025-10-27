@extends('dashboard.index')

@section('dashboard')
<script src="{{ asset('js/formateur-stock/script.js') }}"></script>
<script>
    var csrf_token = "{{ csrf_token() }}";
    var formateurStockUrl = "{{ url('formateur-stock') }}";
</script>
<div class="content-page">
    <div class="content">

        <!-- Start Content-->
        <div class="container-fluid">

            <div class="py-3 d-flex align-items-sm-center flex-sm-row flex-column">
                <div class="flex-grow-1">
                    <h4 class="fs-18 fw-semibold m-0">Mon Stock</h4>
                </div>
                
                <div class="text-end">
                    <ol class="breadcrumb m-0 py-0">
                        <li class="breadcrumb-item"><a href="javascript: void(0);">Applications</a></li>
                        <li class="breadcrumb-item active">Mon Stock</li>
                    </ol>
                </div>
            </div>

            <div class="row">
                <div class="col-12">
                    <div class="card">
                        <div class="card-body">
                                                    
                            <div class="table-responsive">
                                <div class="datatable-wrapper datatable-loading no-footer sortable fixed-height fixed-columns">
                                    
                                    <div class="datatable-container">
                                        <table class="table datatable datatable-table TableFormateurStock">
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
@endsection