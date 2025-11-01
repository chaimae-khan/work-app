@extends('dashboard.index')

@section('dashboard')
<script src="{{asset('js/Plats/script.js')}}"></script>
<script>
    var csrf_token          = "{{csrf_token()}}";
    var AddPlat             = "{{url('addPlat')}}";
    var plats               = "{{url('plats')}}";
    var UpdatePlat          = "{{url('updatePlat')}}";
    var DeletePlat          = "{{url('DeletePlat')}}";
    var editPlat            = "{{url('editPlat')}}";
    var ImportPlat          = "{{url('importPlat')}}";
</script>
<div class="content-page">
    <div class="content">
        <div class="container-fluid">
            <div class="py-3 d-flex align-items-sm-center flex-sm-row flex-column">
                <div class="flex-grow-1">
                    <h4 class="fs-18 fw-semibold m-0">Liste des plats</h4>
                </div>
                
                <div class="text-end">
                    <ol class="breadcrumb m-0 py-0">
                        <li class="breadcrumb-item"><a href="javascript: void(0);">Applications</a></li>
                        <li class="breadcrumb-item active">Plats</li>
                    </ol>
                </div>
            </div>

            <div class="row">
                <div class="col-12">
                    <div class="card">
                        <div class="card-body">
                            <div class="mb-3">
                                @can('Plats-ajoute')
                                <button class="btn btn-primary" style="margin-right: 5px" data-bs-toggle="modal" data-bs-target="#ModalAddPlat">
                                    <i class="fa-solid fa-plus"></i> Ajouter un plat
                                </button>
                                
                                <button class="btn btn-success" style="margin-right: 5px" data-bs-toggle="modal" data-bs-target="#ModalImportPlat">
                                    <i class="fa-solid fa-file-import"></i> Importer des plats
                                </button>
                                @endcan
                            </div>
                            
                            <div class="table-responsive">
                                <div id="DataTables_Table_0_wrapper" class="dataTables_wrapper dt-bootstrap5 no-footer table-responsive">
                                    <table class="table datatable dataTable no-footer TablePlats" id="DataTables_Table_0" aria-describedby="DataTables_Table_0_info">
                                        <thead class="thead-light">
                                            <tr>
                                                <th scope="col">Nom</th>
                                                <th scope="col">Type</th>
                                                <th scope="col">Créé par</th>
                                                <th scope="col">Créé le</th>
                                                <th scope="col">Action</th>    
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

        @can('Plats-ajoute')
        <!-- Modal Ajouter un Plat -->
        <div class="modal fade" id="ModalAddPlat" tabindex="-1" aria-labelledby="ModalAddPlatLabel" aria-hidden="true">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="ModalAddPlatLabel">Ajouter un nouveau plat</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Fermer"></button>
                    </div>
                    <div class="modal-body">
                        <div class="row">
                            <ul class="validationAddPlat"></ul>
                            <form action="{{ url('addPlat') }}" id="FormAddPlat">
                                <div class="row">
                                    <div class="col-md-12">
                                        <div class="form-group mb-3">
                                            <label>Nom du plat</label>
                                            <input type="text" name="name" class="form-control @error('name') is-invalid @enderror" value="{{ old('name') }}">
                                            @error('name')
                                                <span class="text-danger">{{ $message }}</span>
                                            @enderror
                                        </div>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-md-12">
                                        <div class="form-group">
                                            <label>Type du plat</label>
                                            <select name="type" class="form-control @error('type') is-invalid @enderror">
                                                <option value="">Sélectionner un type</option>
                                                <option value="Entrée" {{ old('type') == 'Entrée' ? 'selected' : '' }}>Entrée</option>
                                                <option value="Plat Principal" {{ old('type') == 'Plat Principal' ? 'selected' : '' }}>Plat Principal</option>
                                                <option value="Dessert" {{ old('type') == 'Dessert' ? 'selected' : '' }}>Dessert</option>
                                            </select>
                                            @error('type')
                                                <span class="text-danger">{{ $message }}</span>
                                            @enderror
                                        </div>
                                    </div>
                                </div>
                            </form>
                        </div>
                    </div>
                    <div class="modal-footer text-end">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Fermer</button>
                        <button type="button" class="btn btn-primary" id="BtnAddPlat">Sauvegarder</button>
                    </div>
                </div>
            </div>
        </div>
        @endcan

        @can('Plats-modifier')
        <!-- Modal Modifier le Plat -->
        <div class="modal fade" id="ModalEditPlat" tabindex="-1" aria-labelledby="ModalEditPlatLabel" aria-hidden="true">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="ModalEditPlatLabel">Modifier le plat</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Fermer"></button>
                    </div>
                    <div class="modal-body">
                        <div class="row">
                            <ul class="validationEditPlat"></ul>
                            <form action="{{ url('updatePlat') }}" id="FormUpdatePlat">
                                <input type="hidden" id="id" name="id">
                                <div class="row">
                                    <div class="col-md-12">
                                        <div class="form-group mb-3">
                                            <label>Nom du plat</label>
                                            <input type="text" id="name" name="name" class="form-control @error('name') is-invalid @enderror" value="{{ old('name') }}">
                                            @error('name')
                                                <span class="text-danger">{{ $message }}</span>
                                            @enderror
                                        </div>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-md-12">
                                        <div class="form-group">
                                            <label>Type du plat</label>
                                            <select id="type" name="type" class="form-control @error('type') is-invalid @enderror">
                                                <option value="">Sélectionner un type</option>
                                                <option value="Entrée">Entrée</option>
                                                <option value="Plat Principal">Plat Principal</option>
                                                <option value="Dessert">Dessert</option>
                                            </select>
                                            @error('type')
                                                <span class="text-danger">{{ $message }}</span>
                                            @enderror
                                        </div>
                                    </div>
                                </div>
                            </form>
                        </div>
                    </div>
                    <div class="modal-footer text-end">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Fermer</button>
                        <button type="button" class="btn btn-primary" id="BtnUpdatePlat">Mettre à jour</button>
                    </div>
                </div>
            </div>
        </div>
        @endcan

        @can('Plats-ajoute')
        <!-- Modal Importer des Plats -->
        <div class="modal fade" id="ModalImportPlat" tabindex="-1" aria-labelledby="ModalImportPlatLabel" aria-hidden="true">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="ModalImportPlatLabel">Importer des plats</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Fermer"></button>
                    </div>
                    <div class="modal-body">
                        <div class="row">
                            <ul class="validationImportPlat"></ul>
                            <form action="{{ url('importPlat') }}" id="FormImportPlat" enctype="multipart/form-data">
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
                                            <p><strong>Note:</strong> Le fichier Excel doit contenir deux colonnes:</p>
                                            <ul>
                                                <li><strong>"nom"</strong>: le nom du plat</li>
                                                <li><strong>"type"</strong>: Entrée, Plat Principal ou Dessert</li>
                                            </ul>
                                            <p>Les doublons, les lignes vides et les types invalides seront ignorés automatiquement.</p>
                                        </div>
                                    </div>
                                </div>
                            </form>
                        </div>
                    </div>
                    <div class="modal-footer text-end">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Fermer</button>
                        <button type="button" class="btn btn-success" id="BtnImportPlat">Importer</button>
                    </div>
                </div>
            </div>
        </div>
        @endcan
    </div>
</div>
@endsection