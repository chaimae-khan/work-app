@extends('dashboard.index')

@section('dashboard')

<script src="{{asset('js/plat_composition/script.js')}}"></script>
{{-- <script src="{{asset('js/vente/script.js')}}"></script> --}}
<script>
    var csrf_token = "{{csrf_token()}}";
    var getPlatsByTypeForComposition = "{{url('getPlatsByTypeForComposition')}}";
    var getProductForPlat = "{{url('getProductForPlat')}}";
    var PostInTmpPlat = "{{url('PostInTmpPlat')}}";
    var GetTmpPlatByPlatId = "{{url('GetTmpPlatByPlatId')}}";
    var StorePlatComposition = "{{url('StorePlatComposition')}}";
    var PlatComposition = "{{url('plat-composition')}}";
    var UpdateQteTmpPlat = "{{url('UpdateQteTmpPlat')}}";
    var DeleteRowsTmpPlat = "{{url('DeleteRowsTmpPlat')}}";
    var EditPlatComposition = "{{url('EditPlatComposition')}}";
    var UpdatePlatComposition = "{{url('UpdatePlatComposition')}}";
    var DeletePlatComposition = "{{url('DeletePlatComposition')}}";
    var getcategorybytypemenu = "{{ url('getcategorybytypemenu') }}"
    var getProduct = "{{url('getProduct')}}";
</script>

<style>
    .TableProductPlat tbody tr:hover {
        cursor: pointer; 
    }
</style>

<div class="content-page"> 
    <div class="content">
        <div class="container-fluid">
            <div class="py-3 d-flex align-items-sm-center flex-sm-row flex-column">
                <div class="flex-grow-1">
                    <h4 class="fs-18 fw-semibold m-0">Composition des plats</h4>
                </div>
                
                <div class="text-end">
                    <ol class="breadcrumb m-0 py-0">
                        <li class="breadcrumb-item"><a href="javascript: void(0);">Applications</a></li>
                        <li class="breadcrumb-item active">Composition Plats</li>
                    </ol>
                </div>
            </div>

            <div class="row">
                <div class="col-12">
                    <div class="card">
                        <div class="card-body">
                            <div class="mb-3">
                                @can('Plats-ajoute')
                                <button class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#ModalAddPlatComposition">
                                    <i class="fa-solid fa-plus"></i> Composer un plat
                                </button>
                                @endcan
                            </div>
                            
                            <div class="table-responsive">
                                <table class="table datatable TablePlatComposition">
                                    <thead class="thead-light">
                                        <tr>
                                            <th scope="col">Nom du plat</th>
                                            <th scope="col">Ingrédients</th>
                                            <th scope="col">Quantité</th>
                                            <th scope="col">Unite</th>
                                            <th scope="col">Nombre de couvert</th>
                                            <th scope="col">Créé le</th>
                                            <th scope="col">Action</th>    
                                        </tr>
                                    </thead>
                                    <tbody></tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Modal Add Plat Composition -->
        @can('Plats-ajoute')
        <div class="modal fade" id="ModalAddPlatComposition" tabindex="-1" aria-hidden="true">
            <div class="modal-dialog modal-fullscreen">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title">Composer un plat</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                    </div>
                    <div class="modal-body">
                        <div class="row">
                            <div class="col-xl-6">
                                <div class="card bg-light shadow">
                                    <div class="card-body">
                                        <div class="form-group">
                                            <label>Type de plat</label>
                                            <select class="form-select" id="DropDown_type_plat">
                                                <option value="0">Sélectionner un type</option>
                                                <option value="Entrée">Entrée</option>
                                                <option value="Plat Principal">Plat Principal</option>
                                                <option value="Dessert">Dessert</option>
                                            </select>
                                        </div>
                                        
                                        <div class="form-group mt-3">
                                            <label>Plat</label>
                                            <select class="form-select" id="DropDown_plat">
                                                <option value="0">Sélectionner un plat</option>
                                            </select>
                                        </div>

                                        <div class="form-group mt-3">
                                            <label>Nombre de couverts</label>
                                            <input type="number" min="1" class="form-control" id="nombre_couvert" value="1">
                                        </div>
                                        
                                       {{--  <div class="form-group mt-3">
                                            <label>Rechercher un produit</label>
                                            <input type="text" class="form-control input_products" placeholder="Entrez le nom du produit">
                                        </div> --}}
                                        <div class="row">
                                            <div class="col-sm-12 col-md-12 col-xl-3">
                                                <div class="form-group">
                                                    <label for="">Class</label>
                                                    <select name="type_commande" class="form-select" id="type_commande">
                                                        <option value="0" selected>Please selected type order</option>
                                                        <option value="Alimentaire" >Alimentaire</option>
                                                       
                                                    </select>
                                                </div>
                                                
                                            </div>

                                            <div class="col-sm-12 col-md-12 col-xl-3">
                                                <div class="form-group">
                                                    <label for="">Catégorie</label>
                                                    <select class="form-select" id="filter_categorie" name="filter_categorie">
                                                        <option value="">Toutes les catégories</option>
                                                        
                                                    </select>
                                                </div>
                                                
                                            </div>

                                            <div class="col-sm-12 col-md-12 col-xl-3">
                                                <div class="form-group">
                                                    <label for="">Famille</label>
                                                    <select class="form-select" id="filter_subcategorie" name="filter_subcategorie">
                                                        <option value="">Toutes les familles</option>
                                                    </select>
                                                </div>
                                                
                                            </div>

                                            <div class="col-sm-12 col-md-12 col-xl-3">
                                                <div class="form-group">
                                                    <label for="">Desgination</label>
                                                    <input type="text" class="form-control input_products" placeholder="Entrez le nom du produit">
                                                </div>
                                                
                                            </div>
                                        </div>
                                        
                                        <div class="form-group mt-3">
                                            <div class="card">
                                                <div class="card-body">
                                                    <div class="table-responsive">
                                                        <table class="table table-striped TableProductPlat">
                                                            <thead>
                                                                <tr>
                                                                    <th>Produit</th>
                                                                    <th>Quantité</th>
                                                                    <th>Seuil</th>
                                                                    <th>Local</th>
                                                                    <th>Unité</th>
                                                                </tr>
                                                            </thead>
                                                            <tbody></tbody>
                                                        </table>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            
                            <div class="col-xl-6">
                                <div class="card shadow bg-light">
                                    <div class="card-body">
                                        <h5>Composition du plat</h5>
                                        <div class="form-group mt-3">
                                            <div class="table-responsive">
                                                <table class="table table-striped TableTmpPlat">
                                                    <thead>
                                                        <tr>
                                                            <th>Produit</th>
                                                            <th>Plat</th>
                                                            <th>Quantité</th>
                                                            <th>Unité</th>
                                                            <th>Couverts</th>
                                                            <th>Action</th>
                                                        </tr>
                                                    </thead>
                                                    <tbody></tbody>
                                                </table>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Fermer</button>
                        <button type="button" class="btn btn-primary" id="BtnSavePlatComposition">Sauvegarder</button>
                    </div>
                </div>
            </div>
        </div>
        @endcan

        <!-- Modal Edit Quantity -->
        <div class="modal fade" id="ModalEditQteTmpPlat" tabindex="-1">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title">Modifier la quantité</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                    </div>
                    <div class="modal-body">
                        <ul class="validationUpdateQteTmpPlat"></ul>
                        <div class="form-group">
                            <label>Quantité</label>
                            <input type="number" step="0.01" min="0.01" class="form-control" id="QteTmpPlat">
                        </div>
                        <div class="form-group mt-3">
                            <label>Nombre de couverts</label>
                            <input type="number" min="1" class="form-control" id="NombreCouvertTmp">
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Fermer</button>
                        <button type="button" class="btn btn-primary" id="BtnUpdateQteTmpPlat">Sauvegarder</button>
                    </div>
                </div>
            </div>
        </div>

        <!-- Modal Edit Plat Composition -->
        @can('Plats-modifier')
        <div class="modal fade" id="ModalEditPlatComposition" tabindex="-1">
            <div class="modal-dialog modal-fullscreen">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title">Modifier la composition du plat</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                    </div>
                    <div class="modal-body">
                        <div class="row">
                            <div class="col-xl-6">
                                <div class="card bg-light shadow">
                                    <div class="card-body">
                                        <input type="hidden" id="edit_id_plat">
                                        
                                        <div class="form-group">
                                            <label>Plat</label>
                                            <input type="text" class="form-control" id="edit_plat_name" readonly>
                                        </div>

                                        <div class="form-group mt-3">
                                            <label>Nombre de couverts</label>
                                            <input type="number" min="1" class="form-control" id="edit_nombre_couvert" value="1">
                                        </div>
                                        
                                        <div class="form-group mt-3">
                                            <label>Rechercher un produit</label>
                                            <input type="text" class="form-control input_products_edit" placeholder="Entrez le nom du produit">
                                        </div>
                                        
                                        <div class="form-group mt-3">
                                            <div class="card">
                                                <div class="card-body">
                                                    <div class="table-responsive">
                                                        <table class="table table-striped TableProductPlatEdit">
                                                            <thead>
                                                                <tr>
                                                                    <th>Produit</th>
                                                                    <th>Quantité</th>
                                                                    <th>Seuil</th>
                                                                    <th>Local</th>
                                                                    <th>Unité</th>
                                                                </tr>
                                                            </thead>
                                                            <tbody></tbody>
                                                        </table>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            
                            <div class="col-xl-6">
                                <div class="card shadow bg-light">
                                    <div class="card-body">
                                        <h5>Composition du plat</h5>
                                        <div class="form-group mt-3">
                                            <div class="table-responsive">
                                                <table class="table table-striped TableTmpPlatEdit">
                                                    <thead>
                                                        <tr>
                                                            <th>Produit</th>
                                                            <th>Plat</th>
                                                            <th>Quantité</th>
                                                            <th>Unité</th>
                                                            <th>Couverts</th>
                                                            <th>Action</th>
                                                        </tr>
                                                    </thead>
                                                    <tbody></tbody>
                                                </table>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Fermer</button>
                        <button type="button" class="btn btn-primary" id="BtnUpdatePlatComposition">Mettre à jour</button>
                    </div>
                </div>
            </div>
        </div>
        @endcan
    </div>
</div>
@endsection