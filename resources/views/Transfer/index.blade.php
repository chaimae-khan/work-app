@extends('dashboard.index')

@section('dashboard')

<!-- Scripts personnalisés -->
<script src="{{asset('js/Transfer/script.js')}}"></script>
<script>
    var getFormateurNotSelected   = "{{url('getFormateurNotSelected')}}";
    var csrf_token                = "{{csrf_token()}}";
    var getProduct                = "{{url('getProduct')}}";
    var GetLigneCommandeByCommand = "{{url('GetLigneCommandeByCommand')}}";
    var StoreProductStockTr       = "{{url('StoreProductStockTr')}}";
    var GetTmpStockTransferByTwoFormateur = "{{url('GetTmpStockTransferByTwoFormateur')}}";
    var DeleteRowsTmpStockTr      = "{{url('DeleteRowsTmpStockTr')}}";
    var StoreTransfer             = "{{url('StoreTransfer')}}";
    var EditTransfer              = "{{url('EditTransfer')}}";
    var UpdateTransfer            = "{{url('UpdateTransfer')}}";
    var ChangeStatusTransfer      = "{{ url('ChangeStatusTransfer') }}";
    var UpdateQteTmpTransfer      = "{{url('UpdateQteTmpTransfer')}}";
    var DeleteTransfer            = "{{url('transfer/delete')}}";
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
                    <h4 class="fs-18 fw-semibold m-0">Liste des transferts</h4>
                </div>
                
                <div class="text-end">
                    <ol class="breadcrumb m-0 py-0">
                        <li class="breadcrumb-item"><a href="javascript: void(0);">Applications</a></li>
                        <li class="breadcrumb-item active">Transferts</li>
                    </ol>
                </div>
            </div>

            <div class="row">
                <div class="col-12">
                    <div class="card">

                        <div class="card-body">
                            <div class="mb-3">
                            @can('Transfer-ajoute')
                                <button class="btn btn-primary" style="margin-right: 5px" data-bs-toggle="modal" data-bs-target="#ModalAddAchat">
                                    <i class="fa-solid fa-plus"></i> Ajouter un transfert
                                </button>
                            @endcan
                            </div>
                            
                            <!-- Liste des transferts -->
                            @can('Transfer')
                            <div class="table-responsive">
                                <table class="table datatable TableTransfer">
                                    <thead class="thead-light">
                                        <tr>
                                            <th scope="col">Référence</th>
                                            <!-- <th scope="col">Nombre produits</th> -->
                                            <th scope="col">Quantité totale</th>
                                            <th scope="col">Statut</th>
                                            <th scope="col">D'un formateur</th>
                                            <th scope="col">À formateur</th>
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

        <!-- Modal Ajouter un Transfert -->
        @can('Transfer-ajoute')
        <div class="modal fade" id="ModalAddAchat" tabindex="-1" aria-labelledby="ModalAddAchat" aria-hidden="true">
            <div class="modal-dialog modal-fullscreen">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="ModalAddLocalLabel">Ajouter un nouveau transfert</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Fermer"></button>
                    </div>
                    <div class="modal-body">
                        <div class="row">
                           <div class="col-sm-12 col-md-12 col-xl-6">
                                <div class="card bg-light shadow">
                                    <div class="card-body">
                                        <div class="row">
                                            <div class="col-sm-12 col-md-12 col-xl-6">
                                                <div class="form-group">
                                                    <label for="" class="label-form">D'un formateur</label>
                                                    <select name="fournisseur" class="form-select" id="E_Formateur">
                                                        @foreach ($Formateur as $item)
                                                            <option value="{{$item->id}}" selected>{{$item->prenom}} {{$item->nom}}</option>
                                                        @endforeach
                                                    </select>
                                                    <small class="text-muted d-block mt-1">Vous êtes automatiquement sélectionné comme expéditeur</small>
                                                </div>
                                            </div>
                                            <div class="col-sm-12 col-md-12 col-xl-6">
                                                <div class="form-group">
                                                    <label for="" class="label-form">À formateur</label>
                                                    <select name="fournisseur" class="form-select" id="R_Formateur">
                                                        <option value="0">Veuillez sélectionner le formateur qui va recevoir le produit.</option>
                                                        @foreach ($ToFormateurs as $item)
                                                            <option value="{{$item->id}}">{{$item->prenom}} {{$item->nom}}</option>
                                                        @endforeach
                                                    </select>
                                                </div>
                                            </div>
                                        </div>

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
                                                    <a href="#" class="linkListCommand btn btn-primary" style="display: none">
                                                        <i class="fa-solid fa-arrow-left"></i> Retour à la liste des commandes
                                                    </a>
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
                                                    <table class="table table-striped datatable TableTmpStockTransfer">
                                                        <thead class="thead-light">
                                                            <tr>
                                                                <th scope="col">Produit</th>
                                                                <th scope="col">Code article</th>
                                                                <th scope="col">Quantité stock</th>
                                                                <th scope="col">Quantité transfert</th>
                                                                <th scope="col">D'un formateur</th>      
                                                                <th scope="col">À formateur</th>   
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
                        <button type="button" class="btn btn-primary" id="BtnSaveTransfer">Sauvegarder</button>
                    </div>
                </div>
            </div>
        </div>
        @endcan

        <!-- Modal Modifier Statut Transfert -->
        @can('Transfer-modifier')
        <div class="modal fade" id="editTransferModal" tabindex="-1" aria-labelledby="editTransferModalLabel" aria-hidden="true">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="editTransferModalLabel">Modifier le statut du transfert</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <form id="editTransferForm">
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
    
    <!-- Modal Modifier Quantité Transfer -->
    <div class="modal fade" id="ModalEditQteTmpTransfer" tabindex="-1" role="dialog" aria-labelledby="modalTitleId" aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content" style="background-color: #dee8f0 !important">
                <div class="modal-header">
                    <h5 class="modal-title text-uppercase" id="modalTitleId">
                        Modifier quantité de transfert
                    </h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <div class="form-group">
                        <ul class="validationUpdateQteTmpTransfer"></ul>
                        <label for="">Quantité :</label>
                        <input type="number" min="1" class="form-control" id="QteTmpTransfer">
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Fermer</button>
                    <button type="button" class="btn btn-primary" id="BtnUpdateQteTmpTransfer">Sauvegarder</button>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection