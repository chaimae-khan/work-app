@extends('dashboard.index')

@section('dashboard')
<script src="{{asset('js/fournisseur/script.js')}}"></script>
<script>
    var csrf_token                = "{{csrf_token()}}";
    var AddFournisseur            = "{{url('addFournisseur')}}";
    var fournisseurs              = "{{url('fournisseur')}}";
    var EditFournisseur           = "{{url('editFournisseur')}}";
    var UpdateFournisseur         = "{{url('updateFournisseur')}}";
    var DeleteFournisseur         = "{{url('DeleteFournisseur')}}";
    var ImportFournisseur         = "{{url('importFournisseur')}}";
</script> 
<div class="content-page">
    <div class="content">
        <!-- Start Content-->
        <div class="container-fluid">
            <div class="py-3 d-flex align-items-sm-center flex-sm-row flex-column">
                <div class="flex-grow-1">
                    <h4 class="fs-18 fw-semibold m-0">Liste des fournisseurs</h4>
                </div>
                
                <div class="text-end">
                    <ol class="breadcrumb m-0 py-0">
                        <li class="breadcrumb-item"><a href="javascript: void(0);">Applications</a></li>
                        <li class="breadcrumb-item active">Fournisseurs</li>
                    </ol>
                </div>
            </div>

            <div class="row">
                <div class="col-12">
                    <div class="card">
                        <div class="card-body">
                        <div class="mb-3">
                               @can('Fournisseurs-ajoute')
                                 <button class="btn btn-primary" style="margin-right: 5px" data-bs-toggle="modal" data-bs-target="#ModalAddFournisseur">
                                 <i class="fa-solid fa-plus"></i> Ajouter fournisseur
                                 </button>
    
                                 <button class="btn btn-success" style="margin-right: 5px" data-bs-toggle="modal" data-bs-target="#ModalImportFournisseur">
        <i class="fa-solid fa-file-import"></i> Importer des fournisseurs
    </button>
    @endcan
</div>
                            <div class="table-responsive">
                                <div class="datatable-wrapper datatable-loading no-footer sortable fixed-height fixed-columns">
                                    <div class="datatable-container" style="height: 665.531px;">
                                        <table class="table datatable datatable-table TableFournisseurs">
                                        <thead>
    <tr>
        <th data-sortable="true">Entreprise</th>
        <th data-sortable="true">Téléphone</th>
        <th data-sortable="true">Email</th>
        <th data-sortable="true">ICE</th>
        <th data-sortable="true">RC</th>
        <th data-sortable="true">Patente</th>
        <th data-sortable="true">Siège Social</th>
        <th data-sortable="true">IF</th>
        <th data-sortable="true">CNSS</th>
        <th data-sortable="true">Créé par</th>
        <th data-sortable="true">Créé le</th>
        <th data-sortable="true">Action</th>  
    </tr>
</thead>
                                            <tbody>
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

        @can('Fournisseurs-ajoute')
        <!-- Add Fournisseur Modal -->
        <div class="modal fade" id="ModalAddFournisseur" tabindex="-1" aria-labelledby="ModalAddFournisseurLabel" aria-hidden="true">
            <div class="modal-dialog modal-lg">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="ModalAddFournisseurLabel">Ajouter un nouveau fournisseur</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                        <div class="row">
                            <ul class="validationAddFournisseur"></ul>
                            <form action="{{ url('addFournisseur') }}" id="FormAddFournisseur">
                                <!-- Entreprise -->
                                <div class="row">
                                    <div class="col-md-12">
                                        <div class="form-group mb-3">
                                            <label>Entreprise *</label>
                                            <input type="text" name="entreprise" class="form-control @error('entreprise') is-invalid @enderror" value="{{ old('entreprise') }}" required>
                                            @error('entreprise')
                                                <span class="text-danger">{{ $message }}</span>
                                            @enderror
                                        </div>
                                    </div>
                                </div>

                                <!-- Telephone et Email -->
                                <div class="row">
                                    <div class="col-md-6">
                                        <div class="form-group mb-3">
                                            <label>Téléphone *</label>
                                            <input type="text" name="Telephone" id="phone_fournisseur" class="form-control @error('Telephone') is-invalid @enderror" value="{{ old('Telephone') }}" required>
                                            @error('Telephone')
                                                <span class="text-danger">{{ $message }}</span>
                                            @enderror
                                        </div>
                                    </div>
                        
                                    <div class="col-md-6">
                                        <div class="form-group mb-3">
                                            <label>Email *</label>
                                            <input type="email" name="Email" class="form-control @error('Email') is-invalid @enderror" value="{{ old('Email') }}" required>
                                            @error('Email')
                                                <span class="text-danger">{{ $message }}</span>
                                            @enderror
                                        </div>
                                    </div>
                                </div>

                                <!-- Nouveaux champs -->
                                <div class="row">
                                    <div class="col-md-6">
                                        <div class="form-group mb-3">
                                            <label>ICE</label>
                                            <input type="text" name="ICE" class="form-control">
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="form-group mb-3">
                                            <label>RC</label>
                                            <input type="text" name="RC" class="form-control">
                                        </div>
                                    </div>
                                </div>

                                <div class="row">
                                    <div class="col-md-6">
                                        <div class="form-group mb-3">
                                            <label>Siège Social</label>
                                            <input type="text" name="siege_social" class="form-control">
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="form-group mb-3">
                                            <label>Patente</label>
                                            <input type="text" name="Patente" class="form-control">
                                        </div>
                                    </div>
                                </div>

                                <div class="row">
                                    <div class="col-md-6">
                                        <div class="form-group mb-3">
                                            <label>IF</label>
                                            <input type="text" name="IF" class="form-control">
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="form-group mb-3">
                                            <label>CNSS</label>
                                            <input type="text" name="CNSS" class="form-control">
                                        </div>
                                    </div>
                                </div>
                            </form>
                        </div>
                    </div>
                    <div class="modal-footer text-end">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Fermer</button>
                        <button type="button" class="btn btn-primary" id="BtnAddFournisseur">Sauvegarder</button>
                    </div>
                </div>
            </div>
        </div>
        <!-- Import Fournisseur Modal -->
<div class="modal fade" id="ModalImportFournisseur" tabindex="-1" aria-labelledby="ModalImportFournisseurLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="ModalImportFournisseurLabel">Importer des fournisseurs</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <div class="row">
                    <ul class="validationImportFournisseur"></ul>
                    <form action="{{ url('importFournisseur') }}" id="FormImportFournisseur" enctype="multipart/form-data">
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
                                    <p><strong>Note:</strong> Le fichier Excel doit contenir au minimum:</p>
                                    <p>- Une colonne "entreprise" avec les noms des entreprises</p>
                                    <p>- Une colonne "telephone" avec les numéros de téléphone</p>
                                    <p>- Une colonne "email" avec les adresses email</p>
                                    <p>Les colonnes optionnelles: ice, siege_social, rc, patente, identifiant_fiscal, cnss</p>
                                    <p>Les doublons et lignes avec des champs requis vides seront ignorés automatiquement.</p>
                                </div>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
            <div class="modal-footer text-end">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Fermer</button>
                <button type="button" class="btn btn-success" id="BtnImportFournisseur">Importer</button>
            </div>
        </div>
    </div>
</div>
        @endcan

        @can('Fournisseurs-modifier')
        <!-- Edit Fournisseur Modal -->
        <div class="modal fade" id="ModalEditFournisseur" tabindex="-1" aria-labelledby="ModalEditFournisseurLabel" aria-hidden="true">
            <div class="modal-dialog modal-lg">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="ModalEditFournisseurLabel">Modifier fournisseur</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                        <div class="row">
                            <ul class="validationEditFournisseur"></ul>
                            <form action="{{ url('updateFournisseur') }}" id="FormUpdateFournisseur">
                                <input type="hidden" id="id" name="id">
                                <!-- Entreprise -->
                                <div class="row">
                                    <div class="col-md-12">
                                        <div class="form-group mb-3">
                                            <label>Entreprise *</label>
                                            <input type="text" id="entreprise" name="entreprise" class="form-control @error('entreprise') is-invalid @enderror" value="{{ old('entreprise') }}" required>
                                            @error('entreprise')
                                                <span class="text-danger">{{ $message }}</span>
                                            @enderror
                                        </div>
                                    </div>
                                </div>

                                <!-- Telephone et Email -->
                                <div class="row">
                                    <div class="col-md-6">
                                        <div class="form-group mb-3">
                                            <label>Téléphone *</label>
                                            <input type="text" id="Telephone" name="Telephone" class="form-control phone_fournisseur_edit @error('Telephone') is-invalid @enderror" value="{{ old('Telephone') }}" required>
                                            @error('Telephone')
                                                <span class="text-danger">{{ $message }}</span>
                                            @enderror
                                        </div>
                                    </div>
                        
                                    <div class="col-md-6">
                                        <div class="form-group mb-3">
                                            <label>Email *</label>
                                            <input type="email" id="Email" name="Email" class="form-control @error('Email') is-invalid @enderror" value="{{ old('Email') }}" required>
                                            @error('Email')
                                                <span class="text-danger">{{ $message }}</span>
                                            @enderror
                                        </div>
                                    </div>
                                </div>

                                <!-- Nouveaux champs -->
                                <div class="row">
                                    <div class="col-md-6">
                                        <div class="form-group mb-3">
                                            <label>ICE</label>
                                            <input type="text" id="ICE" name="ICE" class="form-control">
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="form-group mb-3">
                                            <label>RC</label>
                                            <input type="text" id="RC" name="RC" class="form-control">
                                        </div>
                                    </div>
                                </div>

                                <div class="row">
                                    <div class="col-md-6">
                                        <div class="form-group mb-3">
                                            <label>Siège Social</label>
                                            <input type="text" id="siege_social" name="siege_social" class="form-control">
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="form-group mb-3">
                                            <label>Patente</label>
                                            <input type="text" id="Patente" name="Patente" class="form-control">
                                        </div>
                                    </div>
                                </div>

                                <div class="row">
                                    <div class="col-md-6">
                                        <div class="form-group mb-3">
                                            <label>IF</label>
                                            <input type="text" id="IF" name="IF" class="form-control">
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="form-group mb-3">
                                            <label>CNSS</label>
                                            <input type="text" id="CNSS" name="CNSS" class="form-control">
                                        </div>
                                    </div>
                                </div>
                            </form>
                        </div>
                    </div>
                    <div class="modal-footer text-end">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Fermer</button>
                        <button type="button" class="btn btn-primary" id="BtnUpdateFournisseur">Mettre à jour</button>
                    </div>
                </div>
            </div>
        </div>
        @endcan
    </div>
</div>
@endsection