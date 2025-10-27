@extends('dashboard.index')

@section('dashboard')

<!-- Scripts personnalisés -->
<script src="{{asset('js/router/script.js')}}"></script>
<script>
    var getFormateurCommands       = "{{url('getFormateurCommands')}}";
    var GetLigneCommandeByCommand  = "{{url('GetLigneCommandeByCommand')}}";
    var StoreProductStockTransfer  = "{{url('StoreProductStockTransfer')}}";
    var GetTmpStockTransferByFormateur = "{{url('GetTmpStockTransferByFormateur')}}";
    var DeleteRowsTmpStockTransfer = "{{url('DeleteRowsTmpStockTransfer')}}";
    var StoreRouter               = "{{url('StoreRouter')}}";
    var EditRouter                = "{{url('router/edit')}}";
    var UpdateRouter              = "{{url('router/update-status')}}";
    var ChangeStatusRouter        = "{{url('router/change-status')}}";
    var csrf_token                = "{{csrf_token()}}";
    var UpdateQteRouterTmp        = "{{url('UpdateQteRouterTmp')}}";
    var DeleteRouter              = "{{url('router/delete')}}";
</script>
<style>
    .table-responsive {
        overflow-x: hidden;
    }
    .TableProductAchat tbody tr:hover {
        cursor: pointer; 
    }
    .dataTables-custom-controls {
        margin-bottom: 15px;
    }
    .dataTables-custom-controls label {
        margin-bottom: 0;
    }
    .dataTables-custom-controls .form-control {
        display: inline-block;
        width: auto;
        vertical-align: middle;
    }
    .dataTables-custom-controls .form-select {
        display: inline-block;
        width: auto;
        vertical-align: middle;
    }
    .table tbody tr:last-child td {
        cursor: pointer;
    }
</style>

<div class="content-page"> 
    <div class="content">

        <!-- Début du contenu -->
        <div class="container-fluid">

            <div class="py-3 d-flex align-items-sm-center flex-sm-row flex-column">
                <div class="flex-grow-1">
                    <h4 class="fs-18 fw-semibold m-0">Retour vers stock</h4>
                </div>
                
                <div class="text-end">
                    <ol class="breadcrumb m-0 py-0">
                        <li class="breadcrumb-item"><a href="javascript: void(0);">Applications</a></li>
                        <li class="breadcrumb-item active">Retour</li>
                    </ol>
                </div>
            </div>

            <div class="row">
                <div class="col-12">
                    <div class="card">

                        <div class="card-body">
                            <div class="mb-3">
                            @can('retour-ajouter')
                                <button class="btn btn-primary" style="margin-right: 5px" data-bs-toggle="modal" data-bs-target="#ModalAddRouter">
                                    <i class="fa-solid fa-plus"></i> Ajouter un retour
                                </button>
                            @endcan
                            </div>
                            
                            <!-- Liste des retours -->
                            @can('retour')
                            <div class="table-responsive">
                                <table class="table datatable TableRouter">
                                    <thead class="thead-light">
                                        <tr>
                                            <th scope="col">Référence</th>
                                            <th scope="col">Nombre produits</th>
                                            <th scope="col">Quantité totale</th>
                                            <th scope="col">Statut</th>
                                            <th scope="col">Demandeur</th>
                                            <th scope="col">Créé par</th>
                                            <th scope="col">Créé le</th>
                                            <th scope="col">Action</th>  
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <!-- Les données seront chargées par DataTables via AJAX -->
                                    </tbody>
                                </table>
                            </div>
                            @endcan
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Modal Ajouter un Retour -->
        @can('retour-ajouter')
        <div class="modal fade" id="ModalAddRouter" tabindex="-1" aria-labelledby="ModalAddRouter" aria-hidden="true">
            <div class="modal-dialog modal-fullscreen">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="ModalAddRouterLabel">Ajouter un nouveau retour</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Fermer"></button>
                    </div>
                    <div class="modal-body">
                        <div class="row">
                           <div class="col-sm-12 col-md-12 col-xl-6">
                                <div class="card bg-light shadow">
                                    <div class="card-body">
                                        <div class="row">
                                            <div class="col-sm-12 col-md-12 col-xl-12">
                                                <div class="form-group">
                                                    <label for="" class="label-form">Demandeur</label>
                                                    <select name="formateur" class="form-select" id="Formateur">
                                                        <option value="0">Veuillez sélectionner le demandeur.</option>
                                                        @foreach ($Formateur as $item)
                                                            <option value="{{$item->id}}">{{$item->name}}</option>
                                                        @endforeach
                                                    </select>
                                                </div>
                                            </div>
                                        </div>
                                        
                                        <a href="#" class="fs-5 mt-2 d-inline-block linkListCommand" style="border-bottom: 2px solid #007bff; display: none !important;">
                                            <i class="fa-solid fa-arrow-right" style="margin-right: 5px;"></i>Retour à la liste de Commande
                                        </a>
                                        
                                        <div class="form-group mt-2 DivContentForCommande">
                                            <div class="card text-start"> 
                                                <div class="card-body"> 
                                                    <div class="table-responsive">
                                                        <table class="table table-striped datatable TableCommandeByformateur">
                                                            <thead class="thead-light">
                                                                <tr>
                                                                    <th scope="col">Numéro commande</th>
                                                                    <th scope="col">Type commande</th>
                                                                    <th scope="col">Statut</th>
                                                                    <th scope="col">Créer par</th>
                                                                    <th scope="col">Créer le</th>
                                                                </tr>
                                                            </thead>
                                                            <tbody>
                                                                <!-- Les données seront chargées par DataTables -->
                                                            </tbody>
                                                        </table>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        
                                        <div class="form-group mt-2 DivContentForCommandeLigne" style="display: none">
                                            <div class="card text-start"> 
                                                <div class="card-body"> 
                                                    <div class="table-responsive">
                                                        <table class="table table-striped datatable TableCommandeLigneByformateur">
                                                            <thead class="thead-light">
                                                                <tr>
                                                                    <th></th>
                                                                    <th></th>
                                                                    <th scope="col">Produit</th>
                                                                    <th scope="col">Code article</th>
                                                                    <th scope="col">Quantité</th>
                                                                    <th scope="col">Seuil</th> 
                                                                </tr>
                                                            </thead>
                                                            <tbody>
                                                                <!-- Les données seront chargées par DataTables -->
                                                            </tbody>
                                                        </table>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                           </div>
                           <div class="col-sm-12 col-md-12 col-xl-6">
                                <div class="card shadow bg-light">
                                    <div class="card-body">
                                        <div class="form-group mt-3">
                                            <div class="card-body">
                                                <div class="table-responsive">
                                                    <table class="table table-striped datatable TableTmpRouter">
                                                        <thead class="thead-light">
                                                            <tr>
                                                                <th scope="col">Produit</th>
                                                                <th scope="col">Code article</th>
                                                                <th scope="col">Quantité disponible</th>
                                                                <th scope="col">Quantité à retourner</th>
                                                                <th scope="col">Demandeur</th>   
                                                                <th scope="col">Action</th>   
                                                            </tr>
                                                        </thead>
                                                        <tbody>
                                                           <!-- Les données seront chargées par DataTables -->
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
                    <div class="modal-footer text-end">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Fermer</button>
                        <button type="button" class="btn btn-primary" id="BtnRouteToStock">Retour vers stock</button>
                    </div>
                </div>
            </div>
        </div>
        @endcan

        <!-- Modal Modifier Statut Retour -->
        @can('retour-modifier')
        <div class="modal fade" id="editRouterModal" tabindex="-1" aria-labelledby="editRouterModalLabel" aria-hidden="true">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="editRouterModalLabel">Modifier le statut du retour</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <form id="editRouterForm">
                        <div class="modal-body">
                            <input type="hidden" id="edit_id" name="id">
                            
                            <div class="mb-3">
                                <label for="edit_status" class="form-label">Statut <span class="text-danger">*</span></label>
                                <select class="form-select" id="edit_status" name="status" required>
                                    <option value="">-- Sélectionner un statut --</option>
                                    <option value="Création">Création</option>
                                    <option value="Validation">Validation</option>
                                    <option value="Refus">Refus</option>
                                </select>
                                <span id="edit_status_error" class="text-danger"></span>
                            </div>

                            <!-- Refusal Reason Field (Hidden by default) -->
                            <div class="mb-3" id="refusal_reason_group" style="display: none;">
                                <label for="edit_refusal_reason" class="form-label">Motif de refus <span class="text-danger">*</span></label>
                                <textarea class="form-control" id="edit_refusal_reason" name="refusal_reason" rows="3" 
                                          placeholder="Veuillez expliquer la raison du refus..."></textarea>
                                <small class="form-text text-muted">Ce champ est obligatoire lorsque le statut est "Refus"</small>
                            </div>
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Fermer</button>
                            <button type="submit" class="btn btn-primary">Mettre à jour</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
        @endcan
    </div>
    
    <!-- Modal Modifier Quantité Router -->
    <div class="modal fade" id="ModalEditQteRouterTmp" tabindex="-1" role="dialog" aria-labelledby="modalTitleId" aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content" style="background-color: #dee8f0 !important">
                <div class="modal-header">
                    <h5 class="modal-title text-uppercase" id="modalTitleId">
                        Modifier quantité de retour
                    </h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <div class="form-group">
                        <ul class="validationUpdateQteRouterTmp"></ul>
                        <label for="">Quantité :</label>
                        <input type="number" min="1" class="form-control" id="QteRouterTmp">
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Fermer</button>
                    <button type="button" class="btn btn-primary" id="BtnUpdateQteRouterTmp">Sauvegarder</button>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection