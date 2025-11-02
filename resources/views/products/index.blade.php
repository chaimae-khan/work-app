@extends('dashboard.index')

@section('dashboard')
<!-- External Libraries -->
<script src="https://cdnjs.cloudflare.com/ajax/libs/moment.js/2.29.4/moment.min.js"></script>
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/@mdi/font@6.5.95/css/materialdesignicons.min.css">



<link href="https://cdn.jsdelivr.net/npm/tom-select@2.0.0-rc.4/dist/css/tom-select.css" rel="stylesheet">
<script src="https://cdn.jsdelivr.net/npm/tom-select@2.0.0-rc.4/dist/js/tom-select.complete.min.js"></script>
<!-- JS Personnalisé - Charge après les bibliothèques externes -->
<script>
    // Variables PHP vers JavaScript
    var csrf_token = "{{ csrf_token() }}";
    var addProduct_url = "{{ url('addProduct') }}";
    var products_url = "{{ url('products') }}";
    var updateProduct_url = "{{ url('updateProduct') }}";
    var deleteProduct_url = "{{ url('deleteProduct') }}";
    var editProduct_url = "{{ url('editProduct') }}";
    var getSubcategories_url = "{{ url('getSubcategories') }}";
    var getRayons_url = "{{ url('getRayons') }}";
    var importProduct_url = "{{ url('importProduct') }}";
    var GetCategorieByClass = "{{url('GetCategorieByClass')}}"; 
    var searchProductNames_url = "{{ url('searchProductNames') }}";
    var getProduct             = "{{ url('getProduct') }}";
    var GetProductByFamaille             = "{{ url('GetProductByFamaille') }}";
    var getUnitebyProduct             = "{{ url('getUnitebyProduct') }}";
</script>
<script src="{{ asset('js/product/script.js') }}"></script>

<div class="content-page">
    <div class="content">
        <!-- Début du contenu -->
        <div class="container-fluid">
            <!-- Titre de la page -->
            <div class="py-3 d-flex align-items-sm-center flex-sm-row flex-column">
                <div class="flex-grow-1">
                    <h4 class="fs-18 fw-semibold m-0">Liste des produits</h4>
                </div>
                
                <div class="text-end">
                    <ol class="breadcrumb m-0 py-0">
                        <li class="breadcrumb-item"><a href="javascript: void(0);">Applications</a></li>
                        <li class="breadcrumb-item active">Produits</li>
                    </ol>
                </div>
            </div>

            <!-- Liste des produits -->
            <div class="row">
                <div class="col-12">
                    <div class="card">
                        <div class="card-body">
                            <!-- Bouton Ajouter Produit -->
                            <div class="mb-3">
    @can('Products-ajoute')
  <!-- Bouton Ajouter Produit -->
<div class="mb-3">
 @can('Products-ajoute')
   <button class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#ModalAddProduct">
       <i class="fa-solid fa-plus"></i> Ajouter un produit
   </button>
   <button class="btn btn-success ms-2" data-bs-toggle="modal" data-bs-target="#ModalImportProduct">
       <i class="fa-solid fa-file-import"></i> Importer des produits
   </button>
   @endcan
</div>


<!-- Filter Section -->
<div class="card mb-3">
    <div class="card-body">
        <h5 class="card-title">Filtrer les produits</h5>
        <div class="row mb-3">
            <div class="col-md-3">
                <div class="form-group">
                    <label for="filter_class">Classe</label>
                    <select id="filter_class" class="form-control">
                        <option value="">Toutes les classes</option>
                        @foreach($class as $item)
                            <option value="{{ $item->classe }}">{{ $item->classe }}</option>
                        @endforeach
                    </select>
                </div>
            </div>
            <div class="col-md-3">
                <div class="form-group">
                    <label for="filter_categorie">Catégorie</label>
                    <select id="filter_categorie" class="form-control">
                        <option value="">Toutes les catégories</option>
                        @foreach($categories as $category)
                            <option value="{{ $category->id }}">{{ $category->name }}</option>
                        @endforeach
                    </select>
                </div>
            </div>
            <div class="col-md-3">
                <div class="form-group">
                    <label for="filter_subcategorie">Famille</label>
                    <select id="filter_subcategorie" class="form-control">
                        <option value="">Toutes les familles</option>
                    </select>
                </div>
            </div>
            <div class="col-md-3">
                <div class="form-group">
                    <label for="filter_designation">Désignation</label>
                    <input type="text" id="filter_designation" class="form-control" placeholder="Rechercher un produit..." autocomplete="off">
                    <div id="designation_suggestions" class="list-group position-absolute" style="z-index: 1000; max-height: 200px; overflow-y: auto; display: none;"></div>
                </div>
            </div>
        </div>
        <div class="row">
            <div class="col-md-12 text-end">
                <button id="btn_reset_filter" class="btn btn-secondary">
                    <i class="fa-solid fa-rotate"></i> Réinitialiser
                </button>
            </div>
        </div>
    </div>
</div>
    @endcan
</div>
                            
                            <!-- Tableau des produits -->
                            <div class="table-responsive">
                                <table class="table datatable TableProducts">
                                    <thead class="thead-light">
                                        <tr>
                                            <th>Code article</th>
                                            <th>Désignation</th>
                                            <th>Unité</th>
                                            <th>Catégorie</th>
                                            <th>Famille</th>
                                            <th>Emplacement</th>
                                            <th>Stock</th>
                                            <th>Prix d'achat</th>
                                            <!-- <th>Taux TVA</th> -->
                                            <th>Seuil</th>
                                            <th>Date d'expiration</th>
                                            <th>Date de réception </th>
                                            <th>Actions</th>
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

          
          <!-- Modal Ajouter Produit -->
@can('Products-ajoute')
<div class="modal fade" id="ModalAddProduct" tabindex="-1" aria-labelledby="ModalAddProductLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="ModalAddProductLabel">Ajouter un nouveau produit</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <!-- Erreurs de validation -->
                <ul class="validationAddProduct"></ul>

                <!-- Formulaire d'ajout de produit -->
                <form id="FormAddProduct" enctype="multipart/form-data">
                    
                    <!-- Classe et Catégorie -->
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
                                <select name="id_categorie" id="Categorie_Class" class="form-control" required>
                                    <option value="">Sélectionner une catégorie</option>
                                </select>
                            </div>
                        </div>
                    </div>

                    <!-- Famille -->
                    <div class="row mb-3">
                        <div class="col-md-12">
                            <div class="form-group">
                                <label>Famille</label>
                                <select name="id_subcategorie" id="id_subcategorie" class="form-control" required>
                                    <option value="">Sélectionner une famille</option>
                                </select>
                            </div>
                        </div>
                    </div>

                    <!-- Désignation et Unité -->
                    <div class="row mb-3">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label>Désignation</label>
                                {{-- <input type="text" name="name" class="form-control" required> --}}
                                <select name="name" id="name" class="form-select" required>

                                </select>
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

                    <!-- Local et Rayon -->
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

                    <!-- Prix d'achat -->
                    <div class="row mb-3">
                        <div class="col-md-12">
                            <div class="form-group">
                                <label>Prix d'achat</label>
                                <input type="number" step="0.01" name="price_achat" class="form-control" required>
                            </div>
                        </div>
                    </div>

                    <!-- Quantité, Seuil et TVA -->
                    <div class="row mb-3">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label>Quantité</label>
                                <input type="number" step="0.01" name="quantite" class="form-control" required>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label>Seuil</label>
                                <input type="number" step="0.01" name="seuil" class="form-control" required>
                            </div>
                        </div>
                        {{--  <div class="col-md-4">
                            <div class="form-group">
                                <label>TVA</label>
                                <select name="id_tva" class="form-control" required>
                                    <option value="">Sélectionner une TVA</option>
                                    @foreach($tvas as $tva)
                                        <option value="{{ $tva->id }}">{{ $tva->value }}%</option>
                                    @endforeach
                                </select>
                            </div>
                        </div> --}}
                    </div>

                    <!-- Date d'expiration -->
                    <div class="row mb-3">
                        <div class="col-md-4">
                            <div class="form-group">
                                <label>Date d'expiration</label>
                                <input type="date" name="date_expiration" class="form-control">
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="form-group">
                                <label>Fournissuer</label>
                                <select name="" id="" class="form-select">
                                    <option value="0">Please selected Fournissuer</option>
                                    @foreach ($Fournisseur as $item)
                                        <option value={{ $item->id }}>{{ $item->entreprise }}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="form-group">
                                
                                <div class="form-group">
                                    <label>Date réception</label>
                                    <input type="date" name="date_reception" class="form-control">
                                </div>
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
@endcan

        
@can('Products-modifier')
<div class="modal fade" id="ModalEditProduct" tabindex="-1" aria-labelledby="ModalEditProductLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="ModalEditProductLabel">Modifier le produit</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <!-- Erreurs de validation -->
                <ul class="validationEditProduct"></ul>

                <!-- Formulaire de modification de produit -->
                <form id="FormUpdateProduct" enctype="multipart/form-data">
                    <input type="hidden" id="edit_id" name="id">
                    
                    <!-- Classe, Catégorie et Famille -->
                    <div class="row mb-3">
                        <div class="col-md-4">
                            <div class="form-group">
                                <label>Classe</label>
                                <select name="class" id="edit_Class_Categorie" class="form-control" required>
                                    <option value="">Sélectionner une classe</option>
                                    @foreach($class as $item)
                                    <option value="{{$item->classe}}">{{$item->classe}}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="form-group">
                                <label>Catégorie</label>
                                <select name="id_categorie" id="edit_Categorie_Class" class="form-control" required>
                                    <option value="">Sélectionner une catégorie</option>
                                </select>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="form-group">
                                <label>Famille</label>
                                <select name="id_subcategorie" id="edit_id_subcategorie" class="form-control" required>
                                    <option value="">Sélectionner une famille</option>
                                </select>
                            </div>
                        </div>
                    </div>

                    <!-- Informations de base du produit -->
                    <div class="row mb-3">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label>Désignation</label>
                                <input type="text" id="edit_name" name="name" class="form-control" required>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label>Code article</label>
                                <input type="text" id="edit_code_article" class="form-control" disabled>
                                <small class="text-muted">Format: catégorie (3) + famille (3) + numéro séquentiel</small>
                            </div>
                        </div>
                    </div>

                    <div class="row mb-3">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label>Unité</label>
                                <select id="edit_id_unite" name="id_unite" class="form-control" required>
                                    <option value="">Sélectionner une unité</option>
                                    @foreach($unites as $unite)
                                        <option value="{{ $unite->id }}">{{ $unite->name }}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <!-- <div class="form-group">
                                <label>Code barre</label>
                                <input type="text" id="edit_code_barre" name="code_barre" class="form-control">
                            </div> -->
                        </div>
                    </div>

                    <!-- Emplacement -->
                    <div class="row mb-3">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label>Local</label>
                                <select id="edit_id_local" name="id_local" class="form-control" required>
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
                                <select id="edit_id_rayon" name="id_rayon" class="form-control" required>
                                    <option value="">Sélectionner un rayon</option>
                                    <!-- Sera rempli dynamiquement -->
                                </select>
                            </div>
                        </div>
                    </div>

                    <!-- Prix et Informations supplémentaires -->
                    <div class="row mb-3">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label>Prix d'achat</label>
                                <input type="number" step="0.01" id="edit_price_achat" name="price_achat" class="form-control" required>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label>Date d'expiration</label>
                                <input type="date" id="edit_date_expiration" name="date_expiration" class="form-control">
                            </div>
                        </div>
                    </div>

                    <!-- Stock et Taxe -->
                    <div class="row mb-3">
                        <div class="col-md-4">
                            <div class="form-group">
                                <label>Quantité</label>
                                <input type="number" step="0.01" id="edit_quantite" name="quantite" class="form-control" required>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="form-group">
                                <label>Seuil</label>
                                <input type="number" step="0.01" id="edit_seuil" name="seuil" class="form-control" required>
                            </div>
                        </div>
                        <!-- <div class="col-md-4">
                            <div class="form-group">
                                <label>TVA</label>
                                <select id="edit_id_tva" name="id_tva" class="form-control" required>
                                    <option value="">Sélectionner une TVA</option>
                                    @foreach($tvas as $tva)
                                        <option value="{{ $tva->id }}">{{ $tva->value }}%</option>
                                    @endforeach
                                </select>
                            </div>
                        </div> -->
                    </div>
                </form>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Fermer</button>
                <button type="button" class="btn btn-primary" id="BtnUpdateProduct">Mettre à jour</button>
            </div>
        </div>
    </div>
</div>
@endcan
<!-- Modal Importer des Produits -->

@can('Products-ajoute')
<div class="modal fade" id="ModalImportProduct" tabindex="-1" aria-labelledby="ModalImportProductLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="ModalImportProductLabel">Importer des produits</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Fermer"></button>
            </div>
            <div class="modal-body">
                <div class="row">
                    <ul class="validationImportProduct"></ul>
                    <form action="{{ url('importProduct') }}" id="FormImportProduct" enctype="multipart/form-data">
                        <!-- Fichier -->
                        <div class="row">
                            <div class="col-md-12">
                                <div class="form-group">
                                    <label>Fichier Excel (XLSX, XLS, CSV)</label>
                                    <input type="file" name="file" id="import_file" class="form-control @error('file') is-invalid @enderror" accept=".xlsx,.xls,.csv">
                                    @error('file')
                                        <span class="text-danger">{{ $message }}</span>
                                    @enderror
                                </div>
                            </div>
                        </div>
                        <div class="row mt-3">
                            <div class="col-md-12">
                                <div class="alert alert-info">
                                    <p><strong>Note:</strong> Le fichier Excel doit contenir les colonnes suivantes:</p>
                                    <ul>
                                        <li><strong>designation/nom</strong> - Nom du produit</li>
                                        <li><strong>prix_achat/prix</strong> - Prix d'achat</li>
                                        <li><strong>categorie</strong> - Nom de la catégorie</li>
                                        <li><strong>famille</strong> - Nom de la famille/sous-catégorie</li>
                                        <li><strong>local</strong> - Nom du local</li>
                                        <li><strong>rayon</strong> - Nom du rayon</li>
                                        <li><strong>quantite/stock</strong> - Quantité en stock</li>
                                        <li><strong>unite</strong> - Unité de mesure</li>
                                        <!-- <li><strong>tva/taux_tva</strong> - Taux de TVA</li> -->
                                        <li><strong>code_article</strong> - Code article (optionnel)</li>
                                        <li><strong>seuil</strong> - Seuil d'alerte (optionnel)</li>
                                        <!-- <li><strong>code_barre</strong> - Code barre (optionnel)</li> -->
                                        <li><strong>date_expiration</strong> - Date d'expiration (optionnel)</li>
                                    </ul>
                                    <p><strong>Important:</strong> Si <code>code_article</code> est fourni, il sera utilisé tel quel. Sinon, il sera généré automatiquement.</p>
                                </div>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
            <div class="modal-footer text-end">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Fermer</button>
                <button type="button" class="btn btn-success" id="BtnImportProduct">Importer</button>
            </div>
        </div>
    </div>
</div>
@endcan
      
        </div>
    </div>
</div>
<script>


    /* const tomselect_entree = new TomSelect("#name", {
        plugins: ['remove_button'],
        create: false,
        render: {
            option: function(data, escape) {
                return '<div>' + escape(data.text) + '</div>';
            },
            item: function(data, escape) {
                return '<div>' + escape(data.text) + '</div>';
            }
        }
    }); */
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
                    let $drodownProduct = $('#name');
                    $drodownProduct.empty();
                    $drodownProduct.append('<option value="0">Veuillez sélectionner des produits</option>');
                    $.each(response.products, function (index, value) 
                    { 
                         $drodownProduct.append('<option value="' +value.id+ '">' + value.name + '</option>');
                    });
                }
            }
        });
    });

    /* $('#') */
</script>
@endsection