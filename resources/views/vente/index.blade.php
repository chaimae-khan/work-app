@extends('dashboard.index')

@section('dashboard')

<!-- Scripts personnalisés -->
<script src="{{asset('js/vente/script.js')}}"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/moment.js/2.29.4/moment.min.js"></script>
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/@mdi/font@6.5.95/css/materialdesignicons.min.css">
<script>
    var getSubcategories_url = "{{ url('getSubcategories') }}";
    var getRayons_url = "{{ url('getRayons') }}";
    var csrf_token = "{{csrf_token()}}";
    var getProduct = "{{url('getProduct')}}";
    var PostInTmpVente = "{{url('PostInTmpVente')}}";
    var GetTmpVenteByFormateur = "{{url('GetTmpVenteByFormateur')}}";
    var GetVenteList = "{{url('getVenteList')}}";
    var StoreVente = "{{url('StoreVente')}}";
    var Vente = "{{url('Command')}}";
    var UpdateQteTmpVente = "{{url('UpdateQteTmpVente')}}";
    var DeleteRowsTmpVente = "{{url('DeleteRowsTmpVente')}}";
    var GetTotalTmpByFormateurAndUser = "{{url('GetTotalTmpByFormateurAndUser')}}";
    var ShowBonVente = "{{url('ShowBonVente')}}";
    var EditVente = "{{url('EditVente')}}";
    var UpdateVente = "{{url('UpdateVente')}}";
    var DeleteVente = "{{url('DeleteVente')}}";
    var AddProduct = "{{url('addProduct')}}";
    var authId = {{ Auth::id() }};
    var ChangeStatusVente = "{{ url('ChangeStatusVente') }}";
    var GetCategorieByClass = "{{url('GetCategorieByClass')}}";
    var getCategoriesByClass_url = "{{ route('vente.categories.by.class') }}";
    var getVenteSubcategories_url = "{{ route('vente.subcategories', ':id') }}";
    var searchVenteProducts_url = "{{ route('vente.search.products') }}";
</script>

<style>
    .table-responsive {
        overflow-x: hidden;
    }
    .TableProductVente tbody tr:hover {
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
</style>

<div class="content-page">
    <div class="content">
        <div class="container-fluid">
            <div class="py-3 d-flex align-items-sm-center flex-sm-row flex-column">
                <div class="flex-grow-1">
                    <h4 class="fs-18 fw-semibold m-0">Liste des commandes</h4>
                </div>
                
                <div class="text-end">
                    <ol class="breadcrumb m-0 py-0">
                        <li class="breadcrumb-item"><a href="javascript: void(0);">Applications</a></li>
                        <li class="breadcrumb-item active">Commandes</li>
                    </ol>
                </div>
            </div>

            <div class="row">
                <div class="col-12">
                    <div class="card">
                        <div class="card-body">
                            <div class="mb-3">
                                @can('Commande-ajoute')
                                <button class="btn btn-primary" style="margin-right: 5px" data-bs-toggle="modal" data-bs-target="#ModalAddVente">
                                    <i class="fa-solid fa-plus"></i> Ajouter une nouvelle commande
                                </button>
                                @endcan
                            </div>
                            
                            <div class="table-responsive">
                                <table class="table datatable TableVente">
                                    <thead class="thead-light">
                                        <tr>
                                        <th scope="col">Demandeur</th>
                                        <th scope="col">Total</th>
                                        <th scope="col">Statut</th>
                                        <th scope="col">Type Commande</th>
                                        <th scope="col">Type Menu</th>
                                        <th scope="col">Élèves</th>        
                                        <th scope="col">Personnel</th>     
                                        <th scope="col">Invités</th>      
                                        <th scope="col">Divers</th> 
                                        <th scope="col">Date d’utilisation</th>       
                                        <th scope="col">Créé par</th>
                                        <th scope="col">Créé le</th>
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

        <!-- Modal Ajouter une Vente -->
        @can('Commande-ajoute')
<div class="modal fade" id="ModalAddVente" tabindex="-1" aria-labelledby="ModalAddVente" aria-hidden="true">
    <div class="modal-dialog modal-fullscreen">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="ModalAddVenteLabel">Ajouter une nouvelle commande</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Fermer"></button>
            </div>
            <div class="modal-body">
                <div class="row">
                   <div class="col-sm-12 col-md-12 col-xl-6">
                        <div class="card bg-light shadow">
                            <div class="card-body">
                                <div class="form-group">
                                    <label for="" class="label-form">Demandeur</label>
                                    <select name="formateur" class="form-select" id="DropDown_formateur">
                                        {{-- <option value={{ Auth::user()->id }}>{{Auth::user()->nom." ".Auth::user()->prenom}}</option> --}}
                                        {{-- <option value="0">Veuillez sélectionner un Demandeur</option> --}}
                                        @foreach ($formateurs as $formateur)
                                            
                                            <option value="{{$formateur->id}}">{{$formateur->name}}</option>
                                        @endforeach
                                    </select>
                                </div>
                                
                                <!-- Type de commande -->
                                <div class="form-group mt-2">
                                    <label for="type_commande" class="form-label">Type de commande</label>
                                    <select name="type_commande" class="form-select" id="type_commande">
                                        <option value="Alimentaire" selected>Alimentaire</option>
                                        <option value="Non Alimentaire">Non Alimentaire</option>
                                        <!-- <option value="Fournitures et matériels">Fournitures et matériels</option> -->
                                    </select>
                                </div>
                                <div class="form-group mt-2">
                                          <label for="date_usage" class="form-label">Date d’utilisation</label>
                                          <input type="date" class="form-control" id="date_usage" name="date_usage">
                                </div>

                                <!-- Type de menu -->
                                <div class="form-group mt-2" id="menu_container">
                                    <label for="type_menu" class="form-label">Type de menu</label>
                                    <select name="type_menu" class="form-select" id="type_menu">
                                        <option value="Menu eleves" selected>Menu standard</option>
                                        <option value="Menu specials">Menu spécial</option>
                                        <option value="Menu d'application">Menu d'application</option>
                                        <!-- Hidden null option for non-food commands -->
                                        <option value="" style="display:none;">Aucun menu</option>
                                    </select>
                                </div>

                                <!-- Quantity Fields in a container for easy toggling -->
                                <div id="quantity_fields_container">
                                    <div class="row mt-3">
                                        <div class="col-6">
                                            <div class="form-group">
                                                <label for="eleves" class="form-label">Nombre d'élèves</label>
                                                <input type="number" class="form-control" id="eleves" name="eleves" min="0" value="0">
                                            </div>
                                        </div>
                                        <div class="col-6">
                                            <div class="form-group">
                                                <label for="personnel" class="form-label">Nombre de personnel</label>
                                                <input type="number" class="form-control" id="personnel" name="personnel" min="0" value="0">
                                            </div>
                                        </div>
                                    </div>
                                    <div class="row mt-2">
                                        <div class="col-6">
                                            <div class="form-group">
                                                <label for="invites" class="form-label">Nombre d'invités</label>
                                                <input type="number" class="form-control" id="invites" name="invites" min="0" value="0">
                                            </div>
                                        </div>
                                        <!-- <div class="col-6"> -->
                                            <!-- <div class="form-group">
                                                <label for="divers" class="form-label">Nombre divers</label>
                                                <input type="number" class="form-control" id="divers" name="divers" min="0" value="3">
                                            </div> -->
                                             <div class="col-6">
                                            <div class="form-group">
                                                <label for="divers" class="form-label">divers</label>
                                                <input type="number" class="form-control divers" id="divers" name="divers" min="0" value="0">
                                            </div>
                                        <!-- </div> -->
                                        </div>
                                    </div>
                                </div>

                                <!-- Menu Attributes Container (show only for Alimentaire) -->
                                <div id="menu_attributes_container" >
                                    <div class="card mt-3 bg-info-subtle">
                                        <div class="card-header">
                                            <h6 class="mb-0"><i class="mdi mdi-silverware-fork-knife"></i> Composition du Menu</h6>
                                        </div>
                                        <div class="card-body">
                                            <div class="row">
                                                <div class="col-md-6">
                                                    <div class="form-group mb-3">
                                                        <label for="entree" class="form-label"><i class="mdi mdi-food-fork-drink"></i> Entrée</label>
                                                        <input type="text" class="form-control" id="entree" name="entree" 
                                                               placeholder="Ex: Salade de Tomates et Concombre">
                                                    </div>
                                                </div>
                                                <div class="col-md-6">
                                                    <div class="form-group mb-3">
                                                        <label for="plat_principal" class="form-label"><i class="mdi mdi-food"></i> Plat Principal</label>
                                                        <input type="text" class="form-control" id="plat_principal" name="plat_principal" 
                                                               placeholder="Ex: Tajine de Viande">
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="row">
                                                <div class="col-md-6">
                                                    <div class="form-group mb-3">
                                                        <label for="accompagnement" class="form-label"><i class="mdi mdi-bread-slice"></i> Accompagnement</label>
                                                        <input type="text" class="form-control" id="accompagnement" name="accompagnement" 
                                                               placeholder="Ex: Pain, Riz">
                                                    </div>
                                                </div>
                                                <div class="col-md-6">
                                                    <div class="form-group mb-3">
                                                        <label for="dessert" class="form-label"><i class="mdi mdi-cupcake"></i> Dessert</label>
                                                        <input type="text" class="form-control" id="dessert" name="dessert" 
                                                               placeholder="Ex: Jawhara">
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <!-- Validation container -->
                                <div class="form-group mt-2">
                                    <ul class="validationVente"></ul>
                                </div>

                                <!-- <div class="form-group mt-2">
                                    <div class="row">
                                        <div class="col-6">
                                            <label for="" class="form-label">Produit</label>
                                            <input type="text" class="form-control input_products" placeholder="Entrez votre produit">
                                        </div>
                                        <div class="col-6">
                                            <label for="" class="form-label">Class</label>
                                            <input type="text" class="form-control input_products" placeholder="Entrez votre produit">
                                        </div>
                                       
                                    </div>
                                    
                                    
                                </div> -->
                                <div class="row mb-3">
                                    {{-- <div class="col-md-3">
                                        <label for="filter_class" class="form-label">Classe</label>
                                        <select class="form-select" id="filter_class" name="filter_class">
                                            <option value="">Toutes les classes</option>
                                            @foreach($class as $cl)
                                                <option value="{{ $cl->classe }}">{{ $cl->classe }}</option>
                                            @endforeach
                                        </select>
                                    </div> --}}
                                    
                                    <div class="col-md-4">
                                        <label for="filter_categorie" class="form-label">Catégorie</label>
                                        <select class="form-select" id="filter_categorie" name="filter_categorie">
                                            <option value="">Toutes les catégories</option>
                                            @foreach ($categories as $item)
                                                <option value="{{ $item->id }}">{{ $item->name }}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                    
                                    <div class="col-md-4">
                                        <label for="filter_subcategorie" class="form-label">Famille</label>
                                        <select class="form-select" id="filter_subcategorie" name="filter_subcategorie">
                                            <option value="">Toutes les familles</option>
                                        </select>
                                    </div>
    
                                    <div class="col-md-4">
                                        <label for="filter_designation" class="form-label">Désignation</label>
                                        <div class="position-relative">
                                            <input type="text" class="form-control input_products"  placeholder="Rechercher un produit...">
                                           
                                        </div>
                                    </div>
                                </div>
                                
                                <div class="form-group mt-2">
                                    <div class="card text-start">
                                        <div class="card-body">
                                            <div class="table-responsive">
                                                <table class="table table-striped datatable TableProductVente">
                                                    <thead class="thead-light">
                                                        <tr>
                                                            <th scope="col">Produit</th>
                                                            <th scope="col">Quantité</th>
                                                            <th scope="col">Seuil</th>
                                                            <th scope="col">Local</th> 
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
                                <div class="form-group mt-3" style="min-height: 123px;">
                                    <div class="card text-start">
                                        <div class="card-body">
                                            <p class="card-text">Total : <span class="TotalByFormateurAndUser">0.00 DH</span> </p>
                                        </div>
                                    </div>
                                </div>
                                <div class="form-group mt-3">
                                    <div class="card-body">
                                        <div class="table-responsive">
                                            <table class="table table-striped datatable TableTmpVente">
                                                <thead class="thead-light">
                                                    <tr>
                                                        <th scope="col">Produit</th>
                                                        <th scope="col">Quantité</th>
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
                <button type="button" class="btn btn-primary" id="BtnSaveVente">Sauvegarder</button>
            </div>
        </div>
    </div>
</div>
        @endcan

        <!-- Modal Ajouter un Produit -->
        <div class="modal fade" id="ModalAddProduct" tabindex="-1" aria-labelledby="ModalAddProductLabel" aria-hidden="true">
            <div class="modal-dialog modal-lg">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="ModalAddProductLabel">Ajouter un nouveau produit</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Fermer"></button>
                    </div>
                    <div class="modal-body">
                        <!-- Erreurs de validation -->
                        <ul class="validationAddProduct"></ul>

                        <!-- Formulaire d'ajout de produit -->
                        <form id="FormAddProduct" enctype="multipart/form-data">
                            @csrf
                            <!-- Informations de base du produit -->
                               <div class="row mb-3">
                                <div class="col-md-6">
                                  <div class="form-group">
                                    <label>Classe</label>
                                   <select name="class" id="Class_Categorie" class="form-control" required>
                                   <option value="">Sélectionner une classe</option>
                                   @foreach($class as $item)
                                    <option value="{{$item->classe}}">{{$item->classe}}</option>
                                  @endforeach
                                   </select>
                                 </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label>Catégorie</label>
                                        <select name="id_categorie" id="id_categorie" class="form-control" required>
                                            <option value="">Sélectionner une catégorie</option>
                                            @foreach($categories as $category)
                                                <option value="{{ $category->id }}">{{ $category->name }}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>
                                <div class="col-md-12">
                                    <div class="form-group">
                                        <label>Famille</label>
                                        <select name="id_subcategorie" id="id_subcategorie" class="form-control" required>
                                            <option value="">Sélectionner une famille</option>
                                            <!-- Sera rempli dynamiquement -->
                                        </select>
                                    </div>
                                </div>
                            </div>
                            <div class="row mb-3">
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label>Désignation</label>
                                        <input type="text" name="name" class="form-control" required>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label>Unité</label>
                                        <select name="id_unite" id="id_unite" class="form-control" required>
                                            <option value="">Sélectionner une unité</option>
                                            @foreach($unites as $unite)
                                                <option value="{{ $unite->id }}">{{ $unite->name }}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>
                            </div>

                            <!-- Catégorie et Sous-catégorie -->
                          

                            <!-- Emplacement -->
                            <div class="row mb-3">
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label>Local</label>
                                        <select name="id_local" id="id_local" class="form-control" required>
                                            <option value="">Sélectionner un local</option>
                                            @foreach($locals as $local)
                                                <option value="{{ $local->id }}">{{ $local->name }}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label>Rayon</label>
                                        <select name="id_rayon" id="id_rayon" class="form-control" required>
                                            <option value="">Sélectionner un rayon</option>
                                            <!-- Sera rempli dynamiquement -->
                                        </select>
                                    </div>
                                </div>
                            </div>

                            <!-- Prix -->
                            <div class="row mb-3">
                            <div class="col-md-12">
                                <div class="form-group">
                                    <label>Prix d'achat</label>
                                    <input type="number" step="0.01" name="price_achat" class="form-control" required>
                                </div>
                            </div>
                        </div>

                        <!-- Stock et Taxe -->
                        <div class="row mb-3">
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label>Quantité</label>
                                    <input type="number" step="0.01" name="quantite" class="form-control" required>
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label>Seuil</label>
                                    <input type="number" step="0.01" name="seuil" class="form-control" required>
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label>TVA</label>
                                    <select name="id_tva" class="form-control" required>
                                        <option value="">Sélectionner une TVA</option>
                                        @foreach($tvas as $tva)
                                            <option value="{{ $tva->id }}">{{ $tva->value }}%</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                        </div>

                        <!-- Informations supplémentaires -->
                        <div class="row mb-3">
                            <!-- <div class="col-md-4">
                                <div class="form-group">
                                    <label>Code barre</label>
                                    <input type="text" name="code_barre" class="form-control">
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label>Photo</label>
                                    <input type="file" name="photo" id="photo" class="form-control" accept="image/*">
                                </div>
                                <div id="photo_preview" class="mt-2" style="display: none;"></div>
                            </div> -->
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label>Date d'expiration</label>
                                    <input type="date" name="date_expiration" class="form-control">
                                </div>
                            </div>
                        </div>
                    </form>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Fermer</button>
                    <button type="button" class="btn btn-primary" id="BtnAddProduct">Sauvegarder</button>
                </div>
            </div>
        </div>
         </div>

    <!-- Modal pour modifier la quantité -->
 
    <div class="modal fade" id="ModalEditQteTmp" tabindex="-1" role="dialog" aria-labelledby="modalTitleId" aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content" style="background-color: ##dee8f0 !important">
                <div class="modal-header">
                    <h5 class="modal-title text-uppercase" id="modalTitleId">
                        Modifier quantité
                    </h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <div class="form-group">
                        <ul class="validationUpdateQteTmp"></ul>
                        <label for="">Quantité :</label>
                        <input type="number" min="1" class="form-control" id="QteTmp">
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Fermer</button>
                    <button type="button" class="btn btn-primary" id="BtnUpdateQteTmp">Sauvegarder</button>
                </div>
            </div>
        </div>
    </div>
  
    
    <!-- Modal Modifier une Vente -->
    @can('Commande-modifier')
    <div class="modal fade" id="ModalEditVente" tabindex="-1" aria-labelledby="ModalEditVenteLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="ModalEditVenteLabel">Modifier le statut de la commande</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <div class="validationEditVente"></div>
                    <div class="mb-3">
                        <label for="StatusVente" class="form-label">Statut</label>
                        <select class="form-select" id="StatusVente" name="status">
                            <option value="0" selected>Veuillez sélectionner le statut</option>
                            
                            @if (Auth::user()->getRoleNames()->contains('Magasinier'))
                                <option value="Livraison">Livraison</option>
                            @endif
                            
                            @if (Auth::user()->getRoleNames()->contains('Administrateur'))
                                <option value="Refus">Refus</option>
                                <option value="Validation">Réception</option>
                                <option value="Visé">Visé</option>
                                <option value="Réception">Validation</option>
                                 <option value="Livraison">Livraison</option>
                            @endif
                            @if (Auth::user()->getRoleNames()->contains('Économe'))
                            <option value="Visé">Visé</option>
                            <option value="Réception">Validation</option>
                            @endif    
                             @if (Auth::user()->getRoleNames()->contains('Formateur'))
                            <option value="Visé">Visé</option>
                            <option value="Validation">Réception</option>
                            @endif    
                            
                            

                            <!-- <option value="Réception">Réception</option> -->
                        </select>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Fermer</button>
                    <button type="button" class="btn btn-primary" id="BtnUpdateVente">Mettre à jour</button>
                </div>
            </div>
        </div>
    </div>
    @endcan
    <script>
    $('#Class_Categorie').on('change',function()
{
    let name = $(this).val();
    
    $.ajax({
        type: "GET",
        url: GetCategorieByClass,
        data: 
        {
            class : name,
        },
        dataType: "json",
        success: function (response) {
            if(response.status == 200)
            {
                let $dropdown =$('#Categorie_Class');
                $dropdown.empty();
                
                $.each(response.data,function(index, item){
                    $dropdown.append('<option value="' +item.id+ '">' + item.name + '</option>');
                });
            }
        }
    });
});
</script>
</div>@endsection