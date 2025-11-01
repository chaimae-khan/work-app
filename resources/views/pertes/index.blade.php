@extends('dashboard.index')

@section('dashboard')
<!-- External Libraries -->
<script src="https://cdnjs.cloudflare.com/ajax/libs/moment.js/2.29.4/moment.min.js"></script>
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/@mdi/font@6.5.95/css/materialdesignicons.min.css">

<!-- JS Variables -->
<script>
    var csrf_token = "{{ csrf_token() }}";
    var addPerte_url = "{{ url('addPerte') }}";
    var pertes_url = "{{ url('pertes') }}";
    var getSubcategories_url = "{{ url('getSubcategories') }}";
    var getProductsBySubcategory_url = "{{ url('getProductsBySubcategory') }}";
    var GetCategorieByClass = "{{ url('GetCategorieByClass') }}";
    var viewPerte_url = "{{ url('viewPerte') }}";
    var changeStatusPerte_url = "{{ url('changeStatusPerte') }}";
    var deletePerte_url = "{{ url('deletePerte') }}";
</script>
<script src="{{ asset('js/perte/script.js') }}"></script>

<div class="content-page">
    <div class="content">
        <div class="container-fluid">
            <!-- Page Title -->
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

            <!-- Pertes List -->
            <div class="row">
                <div class="col-12">
                    <div class="card">
                        <div class="card-body">
                            <!-- Add Perte Button -->
                            <div class="mb-3">
                                @can('Pertes-ajouter')
                                <button class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#ModalAddPerte">
                                    <i class="fa-solid fa-plus"></i> Déclarer une perte
                                </button>
                                @endcan
                            </div>

                            <!-- Filter Section -->
                            <!-- <div class="card mb-3">
                                <div class="card-body">
                                    <h5 class="card-title">Filtrer les pertes</h5>
                                    <div class="row mb-3">
                                        <div class="col-md-3">
                                            <div class="form-group">
                                                <label for="filter_status">Statut</label>
                                                <select id="filter_status" class="form-control">
                                                    <option value="">Tous les statuts</option>
                                                    <option value="En attente">En attente</option>
                                                    <option value="Validé">Validé</option>
                                                    <option value="Refusé">Refusé</option>
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
                                                <label>&nbsp;</label>
                                                <button id="btn_reset_filter" class="btn btn-secondary w-100">
                                                    <i class="fa-solid fa-rotate"></i> Réinitialiser
                                                </button>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div> -->
                            
                            <!-- Pertes Table -->
                            <div class="table-responsive">
                                <table class="table datatable TablePertes">
                                    <thead class="thead-light">
                                        <tr>
                                            <th>Classe</th>
                                            <th>Catégorie</th>
                                            <th>Famille</th>
                                            <th>Désignation</th>
                                            <th>Quantité</th>
                                            <th>Unité</th>
                                            <th>Nature</th>
                                            <th>Date de perte</th>
                                            <th>Statut</th>
                                            <th>Déclaré par</th>
                                            <th>Actions</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <!-- Data will be loaded by DataTables -->
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Modal Add Perte -->
            @can('Pertes-ajouter')
            <div class="modal fade" id="ModalAddPerte" tabindex="-1" aria-labelledby="ModalAddPerteLabel" aria-hidden="true">
                <div class="modal-dialog modal-lg">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h5 class="modal-title" id="ModalAddPerteLabel">Déclarer une perte</h5>
                            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                        </div>
                        <div class="modal-body">
                            <!-- Validation Errors -->
                            <ul class="validationAddPerte"></ul>

                            <!-- Add Perte Form -->
                            <form id="FormAddPerte">
                                <!-- Classe, Catégorie et Famille -->
                                <div class="row mb-3">
                                    <div class="col-md-4">
                                        <div class="form-group">
                                            <label>Classe <span class="text-danger">*</span></label>
                                            <select name="classe" id="Class_Categorie_Perte" class="form-control" required>
                                                <option value="">Sélectionner une classe</option>
                                                @foreach($class as $item)
                                                <option value="{{ $item->classe }}">{{ $item->classe }}</option>
                                                @endforeach
                                            </select>
                                        </div>
                                    </div>
                                    <div class="col-md-4">
                                        <div class="form-group">
                                            <label>Catégorie <span class="text-danger">*</span></label>
                                            <select name="id_category" id="Categorie_Class_Perte" class="form-control" required>
                                                <option value="">Sélectionner une catégorie</option>
                                            </select>
                                        </div>
                                    </div>
                                    <div class="col-md-4">
                                        <div class="form-group">
                                            <label>Famille <span class="text-danger">*</span></label>
                                            <select name="id_subcategorie" id="id_subcategorie_perte" class="form-control" required>
                                                <option value="">Sélectionner une famille</option>
                                            </select>
                                        </div>
                                    </div>
                                </div>

                                <!-- Produit et Unité -->
                                <div class="row mb-3">
                                    <div class="col-md-8">
                                        <div class="form-group">
                                            <label>Produit <span class="text-danger">*</span></label>
                                            <select name="id_product" id="id_product_perte" class="form-control" required>
                                                <option value="">Sélectionner un produit</option>
                                            </select>
                                        </div>
                                    </div>
                                    <div class="col-md-4">
                                        <div class="form-group">
                                            <label>Unité</label>
                                            <input type="text" id="unite_display_perte" class="form-control" readonly disabled>
                                        </div>
                                    </div>
                                </div>

                                <!-- Quantité et Nature -->
                                <div class="row mb-3">
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label>Quantité perdue <span class="text-danger">*</span></label>
                                            <input type="number" step="0.01" name="quantite" class="form-control" required min="0.01">
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label>Nature de la perte <span class="text-danger">*</span></label>
                                            <select name="nature" class="form-control" required>
                                                <option value="">Sélectionner la nature</option>
                                                <option value="Casse">Casse</option>
                                                <option value="Péremption">Péremption</option>
                                                <option value="Vol">Vol</option>
                                                <option value="Détérioration">Détérioration</option>
                                                <option value="Autre">Autre</option>
                                            </select>
                                        </div>
                                    </div>
                                </div>

                                <!-- Date de perte -->
                                <div class="row mb-3">
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label>Date de la perte <span class="text-danger">*</span></label>
                                            <input type="date" name="date_perte" class="form-control" required max="{{ date('Y-m-d') }}">
                                        </div>
                                    </div>
                                </div>

                                <!-- Cause -->
                                <div class="row mb-3">
                                    <div class="col-md-12">
                                        <div class="form-group">
                                            <label>Cause / Raison <span class="text-danger">*</span></label>
                                            <textarea name="cause" class="form-control" rows="3" required placeholder="Décrivez la raison de la perte..."></textarea>
                                        </div>
                                    </div>
                                </div>

                            </form>
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Fermer</button>
                            <button type="button" class="btn btn-primary" id="BtnAddPerte">Déclarer la perte</button>
                        </div>
                    </div>
                </div>
            </div>
            @endcan

            <!-- Modal Edit Status Perte (Validate/Refuse) -->
            @can('Pertes-valider')
            <div class="modal fade" id="editPerteModal" tabindex="-1" aria-labelledby="editPerteModalLabel" aria-hidden="true">
                <div class="modal-dialog">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h5 class="modal-title" id="editPerteModalLabel">Modifier le statut de la perte</h5>
                            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                        </div>
                        <form id="editPerteForm">
                            <div class="modal-body">
                                <input type="hidden" id="edit_perte_id" name="id">
                                
                                <div class="mb-3">
                                    <label for="edit_perte_status" class="form-label">Statut <span class="text-danger">*</span></label>
                                    <select class="form-select" id="edit_perte_status" name="status" required>
                                        <option value="">-- Sélectionner un statut --</option>
                                        <option value="En attente">En attente</option>
                                        <option value="Validé">Validé</option>
                                        <option value="Refusé">Refusé</option>
                                    </select>
                                    <span id="edit_perte_status_error" class="text-danger"></span>
                                </div>

                                <!-- Refusal Reason Field (Hidden by default) -->
                                <div class="mb-3" id="perte_refusal_reason_group" style="display: none;">
                                    <label for="edit_perte_refusal_reason" class="form-label">Motif de refus <span class="text-danger">*</span></label>
                                    <textarea class="form-control" id="edit_perte_refusal_reason" name="refusal_reason" rows="3" 
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
            @endcan

            <!-- Modal View Perte Details -->
            <div class="modal fade" id="ModalViewPerte" tabindex="-1" aria-labelledby="ModalViewPerteLabel" aria-hidden="true">
                <div class="modal-dialog modal-lg">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h5 class="modal-title" id="ModalViewPerteLabel">Détails de la perte</h5>
                            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                        </div>
                        <div class="modal-body">
                            <div class="row">
                                <div class="col-md-6 mb-3">
                                    <label class="fw-bold">Classe:</label>
                                    <p id="view_classe" class="text-muted"></p>
                                </div>
                                <div class="col-md-6 mb-3">
                                    <label class="fw-bold">Catégorie:</label>
                                    <p id="view_category" class="text-muted"></p>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-md-6 mb-3">
                                    <label class="fw-bold">Famille:</label>
                                    <p id="view_subcategory" class="text-muted"></p>
                                </div>
                                <div class="col-md-6 mb-3">
                                    <label class="fw-bold">Produit:</label>
                                    <p id="view_designation" class="text-muted"></p>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-md-4 mb-3">
                                    <label class="fw-bold">Quantité perdue:</label>
                                    <p id="view_quantite" class="text-muted"></p>
                                </div>
                                <div class="col-md-4 mb-3">
                                    <label class="fw-bold">Unité:</label>
                                    <p id="view_unite" class="text-muted"></p>
                                </div>
                                <div class="col-md-4 mb-3">
                                    <label class="fw-bold">Nature:</label>
                                    <p id="view_nature" class="text-muted"></p>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-md-6 mb-3">
                                    <label class="fw-bold">Date de la perte:</label>
                                    <p id="view_date_perte" class="text-muted"></p>
                                </div>
                                <div class="col-md-6 mb-3">
                                    <label class="fw-bold">Statut:</label>
                                    <p id="view_status"></p>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-md-12 mb-3">
                                    <label class="fw-bold">Cause / Raison:</label>
                                    <p id="view_cause" class="text-muted"></p>
                                </div>
                            </div>
                            <div class="row" id="view_refusal_reason_row" style="display: none;">
                                <div class="col-md-12 mb-3">
                                    <label class="fw-bold text-danger">Motif de refus:</label>
                                    <p id="view_refusal_reason" class="text-danger"></p>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-md-6 mb-3">
                                    <label class="fw-bold">Déclaré par:</label>
                                    <p id="view_user" class="text-muted"></p>
                                </div>
                                <div class="col-md-6 mb-3">
                                    <label class="fw-bold">Date de déclaration:</label>
                                    <p id="view_created_at" class="text-muted"></p>
                                </div>
                            </div>
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Fermer</button>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Modal Refuse Perte -->
            @can('Pertes-valider')
            <div class="modal fade" id="ModalRefusePerte" tabindex="-1" aria-labelledby="ModalRefusePerteLabel" aria-hidden="true">
                <div class="modal-dialog">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h5 class="modal-title" id="ModalRefusePerteLabel">Refuser la perte</h5>
                            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                        </div>
                        <div class="modal-body">
                            <input type="hidden" id="refuse_perte_id">
                            <div class="mb-3">
                                <label for="refusal_reason" class="form-label">Motif de refus <span class="text-danger">*</span></label>
                                <textarea class="form-control" id="refusal_reason" rows="4" required placeholder="Veuillez expliquer la raison du refus..."></textarea>
                                <small class="form-text text-muted">Ce champ est obligatoire</small>
                            </div>
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Annuler</button>
                            <button type="button" class="btn btn-danger" id="BtnConfirmRefuse">Confirmer le refus</button>
                        </div>
                    </div>
                </div>
            </div>
            @endcan

        </div>
    </div>
</div>

@endsection