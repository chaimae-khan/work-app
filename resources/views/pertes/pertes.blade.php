@extends('dashboard.index')

@section('dashboard')
<!-- External Libraries -->
<script src="https://cdnjs.cloudflare.com/ajax/libs/moment.js/2.29.4/moment.min.js"></script>
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/@mdi/font@6.5.95/css/materialdesignicons.min.css">

<!-- JS Personnalisé -->
<script>
    // Variables PHP vers JavaScript
    var csrf_token = "{{ csrf_token() }}";
    var addPerte_url = "{{ url('addPerte') }}";
    var pertes_url = "{{ url('pertes') }}";
    var updatePerte_url = "{{ url('updatePerte') }}";
    var deletePerte_url = "{{ url('deletePerte') }}";
    var editPerte_url = "{{ url('editPerte') }}";
    var getSubcategories_url = "{{ url('getSubcategories') }}";
    var GetCategorieByClass = "{{ url('GetCategorieByClass') }}";
    var searchProductNames_url = "{{ url('searchProductNames') }}";
    var getProductDetails_url = "{{ url('getProductDetails') }}";
    var importPerte_url = "{{ url('importPerte') }}";
    var updateStatusPerte_url = "{{ url('updateStatusPerte') }}";
</script>
<script src="{{ asset('js/perte/script.js') }}"></script>

<div class="content-page">
    <div class="content">
        <!-- Début du contenu -->
        <div class="container-fluid">
            <!-- Titre de la page -->
            <div class="py-3 d-flex align-items-sm-center flex-sm-row flex-column">
                <div class="flex-grow-1">
                    <h4 class="fs-18 fw-semibold m-0">Gestion des Pertes</h4>
                </div>
                
                <div class="text-end">
                    <ol class="breadcrumb m-0 py-0">
                        <li class="breadcrumb-item"><a href="javascript: void(0);">Applications</a></li>
                        <li class="breadcrumb-item active">Pertes</li>
                    </ol>
                </div>
            </div>

            <!-- Liste des pertes -->
            <!-- <div class="row">
                <div class="col-12">
                    <div class="card">
                        <div class="card-body">
                            
                            <!-- Section Filtres -->
                            <!-- <h5 class="card-title mb-3">Filtrer les pertes</h5>
                            <div class="row mb-3">
                                <div class="col-md-3">
                                    <div class="form-group">
                                        <label for="filter_class">Classe</label>
                                        <select id="filter_class" class="form-control">
                                            <option value="">Toutes les classes</option>
                                        </select>
                                    </div>
                                </div>
                                <div class="col-md-3">
                                    <div class="form-group">
                                        <label for="filter_categorie">Catégorie</label>
                                        <select id="filter_categorie" class="form-control">
                                            <option value="">Toutes les catégories</option>
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
                               
                            </div> -->
                            <!-- <div class="row mb-3">
                                <div class="col-md-3">
                                    <div class="form-group">
                                        <label for="filter_designation">Désignation</label>
                                        <input type="text" id="filter_designation" class="form-control" placeholder="Rechercher un produit..." autocomplete="off">
                                        <div id="designation_suggestions" class="list-group position-absolute" style="z-index: 1000; max-height: 200px; overflow-y: auto; display: none;"></div>
                                    </div>
                                </div>
                              
                            <div class="row mb-4">
                                <div class="col-md-12 text-end">
                                    <button id="btn_reset_filter" class="btn btn-secondary">
                                        <i class="fa-solid fa-rotate"></i> Réinitialiser
                                    </button>
                                </div>
                            </div> -->
                            
                            <!-- Boutons d'action -->
                            <div class="mb-3">
                          
                                <button class="btn btn-danger" data-bs-toggle="modal" data-bs-target="#ModalAddPerte">
                                    <i class="fa-solid fa-plus"></i> Déclarer une perte
                                </button>
                                <button class="btn btn-success ms-2" data-bs-toggle="modal" data-bs-target="#ModalImportPerte">
                                    <i class="fa-solid fa-file-import"></i> Importer des pertes
                                </button>
                            
                            </div>
                            
                            <!-- Tableau des pertes -->
                        
                            <div class="table-responsive">
                                <table class="table datatable TablePertes">
                                    <thead class="thead-light">
                                        <tr>
                                            <th>Référence</th>
                                            <th>Code article</th>
                                            <th>Classe</th>
                                            <th>Catégorie</th>
                                            <th>Famille</th>
                                            <th>Désignation</th>
                                            <th>Quantité</th>
                                            <th>Unité</th>
                                            <th>Nature</th>
                                            <th>Cause</th>
                                            <th>Date</th>
                                            <th>Statut</th>
                                            <th>Déclaré par</th>
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
            </div> -->

         
            <div class="modal fade" id="ModalAddPerte" tabindex="-1" aria-labelledby="ModalAddPerteLabel" aria-hidden="true">
                <div class="modal-dialog modal-lg">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h5 class="modal-title" id="ModalAddPerteLabel">Déclarer une nouvelle perte</h5>
                            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                        </div>
                        <div class="modal-body">
                            <!-- Erreurs de validation -->
                            <ul class="validationAddPerte"></ul>

                            <!-- Formulaire d'ajout de perte -->
                            <form id="FormAddPerte">
                               
                                <!-- Classe et Catégorie -->
                                <div class="row mb-3">
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label>Classe</label>
                                            <select name="class" id="Class_Categorie" class="form-control" required>
                                                <option value="">Sélectionner une classe</option>
                                                @foreach($classes as $item)
                                                    <option value="{{ $item->classe }}">{{ $item->classe }}</option>
                                                @endforeach
                                            </select>
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label>Catégorie</label>
                                            <select name="id_category" id="Categorie_Class" class="form-control" required>
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
                                            <select name="id_sub_categories" id="id_subcategorie" class="form-control" required>
                                                <option value="">Sélectionner une famille</option>
                                                 @foreach($SubCategory as $item)
                                                    <option value="{{ $item->id }}">{{ $item->name }}</option>
                                                @endforeach
                                            </select>
                                        </div>
                                    </div>
                                </div>

                                <!-- Désignation et Unité -->
                                <div class="row mb-3">
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label>Désignation</label>
                                            <select name="id_product" id="id_subcategorie" class="form-control" required>
                                                <option value="">Sélectionner une Désignation</option>
                                                 @foreach($Product as $item)
                                                    <option value="{{ $item->id }}">{{ $item->name }}</option>
                                                @endforeach
                                            </select>
                                            <div id="product_suggestions" class="list-group position-absolute" style="z-index: 1050; max-height: 200px; overflow-y: auto; display: none;"></div>
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label>Unité</label>
                                            <select name="id_unite" id="id_unite" class="form-control" required>
                                                <option value="">Sélectionner une unité</option>
                                                
                                            </select>
                                        </div>
                                    </div>
                                </div>

                                <!-- Quantité -->
                                <div class="row mb-3">
                                    <div class="col-md-12">
                                        <div class="form-group">
                                            <label>Quantité</label>
                                            <input type="number" step="0.01" name="qte" id="quantite" class="form-control" required>
                                        </div>
                                    </div>
                                </div>

                                <!-- Nature de perte, Date et Cause -->
                                <div class="row mb-3">
                                    <div class="col-md-4">
                                        <div class="form-group">
                                            <label>Nature</label>
                                            <select name="nature" id="nature" class="form-control" required>
                                                <option value="">Sélectionner une nature</option>
                                                <option value="Produit fini">Produit fini</option>
                                                <option value="Entrée/Suite/Dessert/Accompagnement">Entrée/Suite/Dessert/Accompagnement</option>
                                                <option value="Autres">Autres</option>
                                            </select>
                                        </div>
                                    </div>
                                    <div class="col-md-4">
                                        <div class="form-group">
                                            <label>Date</label>
                                            <input type="date" name="date" id="date_perte" class="form-control" required>
                                        </div>
                                    </div>
                                    <div class="col-md-4">
                                        <div class="form-group">
                                            <label>Cause</label>
                                            <input type="text" name="cause" id="cause" class="form-control" required>
                                        </div>
                                    </div>
                                </div>

                                <!-- Hidden field for product ID -->
                                <input type="hidden" name="id_product" id="id_product">
                            </form>
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Fermer</button>
                            <button type="button" class="btn btn-primary" id="BtnAddPerte">Sauvegarder</button>
                        </div>
                    </div>
                </div>
            </div>
       

        
            <div class="modal fade" id="ModalEditPerte" tabindex="-1" aria-labelledby="ModalEditPerteLabel" aria-hidden="true">
                <div class="modal-dialog modal-lg">
                    <div class="modal-content">
                        <div class="modal-header bg-warning">
                            <h5 class="modal-title" id="ModalEditPerteLabel">
                                <i class="fa-solid fa-edit"></i> Modifier la déclaration de perte
                            </h5>
                            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                        </div>
                        <div class="modal-body">
                            <!-- Erreurs de validation -->
                            <ul class="validationEditPerte"></ul>

                            <!-- Formulaire de modification -->
                            <form id="FormUpdatePerte">
                                <input type="hidden" id="edit_id" name="id">
                                
                                <!-- Classe, Catégorie et Famille -->
                                <div class="row mb-3">
                                    <div class="col-md-4">
                                        <div class="form-group">
                                            <label>Classe <span class="text-danger">*</span></label>
                                            <select name="class" id="edit_Class_Categorie" class="form-control" required>
                                                <option value="">Sélectionner une classe</option>
                                            </select>
                                        </div>
                                    </div>
                                    <div class="col-md-4">
                                        <div class="form-group">
                                            <label>Catégorie <span class="text-danger">*</span></label>
                                            <select name="id_categorie" id="edit_Categorie_Class" class="form-control" required>
                                                <option value="">Sélectionner une catégorie</option>
                                            </select>
                                        </div>
                                    </div>
                                    <div class="col-md-4">
                                        <div class="form-group">
                                            <label>Famille <span class="text-danger">*</span></label>
                                            <select name="id_subcategorie" id="edit_id_subcategorie" class="form-control" required>
                                                <option value="">Sélectionner une famille</option>
                                            </select>
                                        </div>
                                    </div>
                                </div>

                                <!-- Désignation -->
                                <div class="row mb-3">
                                    <div class="col-md-12">
                                        <div class="form-group">
                                            <label>Désignation <span class="text-danger">*</span></label>
                                            <input type="text" id="edit_designation" name="designation" class="form-control" required>
                                        </div>
                                    </div>
                                </div>

                                <!-- Quantité et Unité -->
                                <div class="row mb-3">
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label>Quantité <span class="text-danger">*</span></label>
                                            <input type="number" step="0.01" id="edit_quantite" name="quantite" class="form-control" required min="0.01">
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label>Unité <span class="text-danger">*</span></label>
                                            <select id="edit_id_unite" name="id_unite" class="form-control" required>
                                                <option value="">Sélectionner une unité</option>
                                            </select>
                                        </div>
                                    </div>
                                </div>

                                <!-- Nature et Date -->
                                <div class="row mb-3">
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label>Nature de perte <span class="text-danger">*</span></label>
                                            <select id="edit_nature" name="nature" class="form-control" required>
                                                <option value="">Sélectionner une nature</option>
                                                <option value="Produit fini">Produit fini</option>
                                                <option value="Entrée/Suite/Dessert/Accompagnement">Entrée/Suite/Dessert/Accompagnement</option>
                                                <option value="Autres">Autres</option>
                                            </select>
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label>Date de la perte <span class="text-danger">*</span></label>
                                            <input type="date" id="edit_date_perte" name="date_perte" class="form-control" required>
                                        </div>
                                    </div>
                                </div>

                                <!-- Cause -->
                                <div class="row mb-3">
                                    <div class="col-md-12">
                                        <div class="form-group">
                                            <label>Cause de la perte <span class="text-danger">*</span></label>
                                            <textarea id="edit_cause" name="cause" class="form-control" rows="3" required></textarea>
                                        </div>
                                    </div>
                                </div>

                                <input type="hidden" id="edit_id_product" name="id_product">
                            </form>
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Fermer</button>
                            <button type="button" class="btn btn-primary" id="BtnUpdatePerte">
                                <i class="fa-solid fa-save"></i> Mettre à jour
                            </button>
                        </div>
                    </div>
                </div>
            </div>
   

          
            <div class="modal fade" id="ModalChangeStatusPerte" tabindex="-1" aria-labelledby="ModalChangeStatusPerteLabel" aria-hidden="true">
                <div class="modal-dialog">
                    <div class="modal-content">
                        <div class="modal-header bg-info text-white">
                            <h5 class="modal-title" id="ModalChangeStatusPerteLabel">Modifier le statut de la perte</h5>
                            <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
                        </div>
                        <form id="FormChangeStatusPerte">
                            <div class="modal-body">
                                <input type="hidden" id="status_id" name="id">
                                
                                <!-- Référence de la perte -->
                                <div class="mb-3">
                                    <label class="form-label fw-bold">Référence:</label>
                                    <p id="status_reference" class="text-muted"></p>
                                </div>

                                <!-- Statut -->
                                <div class="mb-3">
                                    <label for="status_statut" class="form-label">Statut <span class="text-danger">*</span></label>
                                    <select class="form-select" id="status_statut" name="status" required>
                                        <option value="">-- Sélectionner un statut --</option>
                                        <option value="En attente">En attente</option>
                                        <option value="Validé">Validé</option>
                                        <option value="Refusé">Refusé</option>
                                    </select>
                                    <span id="status_error" class="text-danger"></span>
                                </div>

                                <!-- Motif de refus (masqué par défaut) -->
                                <div class="mb-3" id="refusal_reason_group" style="display: none;">
                                    <label for="status_refusal_reason" class="form-label">Motif de refus <span class="text-danger">*</span></label>
                                    <textarea class="form-control" id="status_refusal_reason" name="refusal_reason" rows="3" 
                                              placeholder="Veuillez expliquer la raison du refus..."></textarea>
                                    <small class="form-text text-muted">Ce champ est obligatoire lorsque le statut est "Refusé"</small>
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
       

            <!-- Modal Importer des Pertes -->
          
            <div class="modal fade" id="ModalImportPerte" tabindex="-1" aria-labelledby="ModalImportPerteLabel" aria-hidden="true">
                <div class="modal-dialog modal-lg">
                    <div class="modal-content">
                        <div class="modal-header bg-success text-white">
                            <h5 class="modal-title" id="ModalImportPerteLabel">
                                <i class="fa-solid fa-file-import"></i> Importer des pertes
                            </h5>
                            <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Fermer"></button>
                        </div>
                        <div class="modal-body">
                            <ul class="validationImportPerte"></ul>
                            <form id="FormImportPerte" enctype="multipart/form-data">
                                <!-- Fichier -->
                                <div class="row mb-3">
                                    <div class="col-md-12">
                                        <div class="form-group">
                                            <label>Fichier Excel (XLSX, XLS, CSV) <span class="text-danger">*</span></label>
                                            <input type="file" name="file" id="import_file" class="form-control @error('file') is-invalid @enderror" accept=".xlsx,.xls,.csv" required>
                                            @error('file')
                                                <span class="text-danger">{{ $message }}</span>
                                            @enderror
                                        </div>
                                    </div>
                                </div>
                                
                                <!-- Instructions -->
                                <div class="row">
                                    <div class="col-md-12">
                                        <div class="alert alert-info">
                                            <h6><i class="fa-solid fa-info-circle"></i> Format du fichier requis:</h6>
                                            <p>Le fichier Excel doit contenir les colonnes suivantes:</p>
                                            <ul class="mb-0">
                                                <li><strong>CLASSE</strong> - Classe du produit</li>
                                                <li><strong>CATEGORIE</strong> - Nom de la catégorie</li>
                                                <li><strong>FAMILLE</strong> - Nom de la famille/sous-catégorie</li>
                                                <li><strong>DESIGNATION</strong> - Nom du produit perdu</li>
                                                <li><strong>QUANTITE</strong> - Quantité perdue</li>
                                                <li><strong>UNITE</strong> - Unité de mesure (par défaut si vide)</li>
                                                <li><strong>DATE</strong> - Date de la perte (Calendrier)</li>
                                                <li><strong>NATURE</strong> - Nature de la perte</li>
                                                <li><strong>CAUSE</strong> - Cause de la perte</li>
                                            </ul>
                                            <hr>
                                            <p class="mb-0"><strong>Note:</strong> Toutes les pertes importées auront le statut "En attente" par défaut.</p>
                                        </div>
                                    </div>
                                </div>
                            </form>
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Fermer</button>
                            <button type="button" class="btn btn-success" id="BtnImportPerte">
                                <i class="fa-solid fa-upload"></i> Importer
                            </button>
                        </div>
                    </div>
                </div>
            </div>
       
        </div>
    </div>
</div>

@endsection