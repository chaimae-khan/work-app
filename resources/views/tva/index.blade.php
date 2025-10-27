
@extends('dashboard.index')

@section('dashboard')
<script src="{{asset('js/tva/script.js')}}"></script>
<script>
    var csrf_token                = "{{csrf_token()}}";
    var AddTva                    = "{{url('addTva')}}";
    var tvas                      = "{{url('tva')}}";
    var UpdateTva                 = "{{url('updateTva')}}";
    var DeleteTva                 = "{{url('DeleteTva')}}";
    var ImportTva                 = "{{url('importTva')}}";
</script>
<div class="content-page">
    <div class="content">

        <!-- Start Content-->
        <div class="container-fluid">

            <div class="py-3 d-flex align-items-sm-center flex-sm-row flex-column">
                <div class="flex-grow-1">
                    <h4 class="fs-18 fw-semibold m-0">List de taxe </h4>
                </div>
                
                <div class="text-end">
                    <ol class="breadcrumb m-0 py-0">
                        <li class="breadcrumb-item"><a href="javascript: void(0);">Applications</a></li>
                        <li class="breadcrumb-item active">Tva</li>
                    </ol>
                </div>
            </div>

            <div class="row">
                <div class="col-12">
                    <div class="card">

                        <div class="card-body">
                        <div class="mb-3">
    @can('Taxes-ajoute')
        <button class="btn btn-primary" style="margin-right: 5px" data-bs-toggle="modal" data-bs-target="#ModalAddTva">
            <i class="fa-solid fa-plus"></i> Ajoute tva
        </button>
        
        <button class="btn btn-success" style="margin-right: 5px" data-bs-toggle="modal" data-bs-target="#ModalImportTva">
            <i class="fa-solid fa-file-import"></i> Importer des TVA
        </button>
    @endcan
</div>
                            <div class="table-responsive">
                                <div class="datatable-wrapper datatable-loading no-footer sortable fixed-height fixed-columns">
                                    
                                    <div class="datatable-container" style="height: 665.531px;">
                                        <table class="table datatable datatable-table TableTvas" >
                                            <thead>
                                                <tr>
                                                    <th data-sortable="true">Nom</th>
                                                    <th data-sortable="true">Valeur</th>
                                                    <th data-sortable="true">créer par</th>
                                                    <th data-sortable="true">créer le</th>
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
        </div>


       

        <!-- Add TVA Modal -->
        <div class="modal fade" id="ModalAddTva" tabindex="-1" aria-labelledby="ModalAddTvaLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="ModalAddTvaLabel">Ajouter une nouvelle TVA</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <div class="row">
                        <ul class="validationAddTva"></ul>
                        <form action="{{ url('addTva') }}" id="FormAddTva">
                            
                    
                            <!-- Name & Value -->
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label>Nom de la TVA</label>
                                        <input type="text" name="name" class="form-control @error('name') is-invalid @enderror" value="{{ old('name') }}">
                                        @error('name')
                                            <span class="text-danger">{{ $message }}</span>
                                        @enderror
                                    </div>
                                </div>
                    
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label>Valeur de la TVA (%)</label>
                                        <input type="number" name="value" step="0.01" class="form-control @error('value') is-invalid @enderror" value="{{ old('value') }}">
                                        @error('value')
                                            <span class="text-danger">{{ $message }}</span>
                                        @enderror
                                    </div>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
                <div class="modal-footer text-end">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Ferme</button>
                    <button type="button" class="btn btn-primary" id="BtnAddTva">Sauvegarder</button>
                </div>
            </div>
        </div>
    </div>
    <!-- Import TVA Modal -->
<div class="modal fade" id="ModalImportTva" tabindex="-1" aria-labelledby="ModalImportTvaLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="ModalImportTvaLabel">Importer des TVA</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <div class="row">
                    <ul class="validationImportTva"></ul>
                    <form action="{{ url('importTva') }}" id="FormImportTva" enctype="multipart/form-data">
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
                                    <p><strong>Note:</strong> Le fichier Excel doit contenir:</p>
                                    <p>- Une colonne "nom" avec les noms des TVA</p>
                                    <p>- Une colonne "valeur" avec les taux de TVA en pourcentage</p>
                                    <p>Les doublons, valeurs invalides et lignes vides seront ignorés automatiquement.</p>
                                </div>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
            <div class="modal-footer text-end">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Fermer</button>
                <button type="button" class="btn btn-success" id="BtnImportTva">Importer</button>
            </div>
        </div>
    </div>
</div>



        <!-- Edit TVA Modal -->
    <div class="modal fade" id="ModalEditTva" tabindex="-1" aria-labelledby="ModalEditTvaLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="ModalEditTvaLabel">Edit TVA</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <div class="row">
                        <ul class="validationEditTva"></ul>
                        <form action="{{ url('updateTva') }}" id="FormUpdateTva">
                            
                            <!-- Name & Value -->
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label>Nom de la TVA</label>
                                        <input type="text" id="name" name="name" class="form-control @error('name') is-invalid @enderror" value="{{ old('name') }}">
                                        @error('name')
                                            <span class="text-danger">{{ $message }}</span>
                                        @enderror
                                    </div>
                                </div>
                    
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label>Valeur de la TVA (%)</label>
                                        <input type="number" id="value" name="value" step="0.01" class="form-control @error('value') is-invalid @enderror" value="{{ old('value') }}">
                                        @error('value')
                                            <span class="text-danger">{{ $message }}</span>
                                        @enderror
                                    </div>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
                <div class="modal-footer text-end">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Ferme</button>
                    <button type="button" class="btn btn-primary" id="BtnUpdateTva">Sauvegarder</button>
                </div>
            </div>
        </div>
    </div>
</div>



        
        
        
        
</div>



@endsection