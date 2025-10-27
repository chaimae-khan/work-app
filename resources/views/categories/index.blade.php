@extends('dashboard.index')

@section('dashboard')
<!-- Scripts personnalisés -->
<script src="{{asset('js/Categories/script.js')}}"></script>
<script>
    var csrf_token          = "{{csrf_token()}}";
    var AddCategory         = "{{url('addCategory')}}";
    var categories          = "{{url('categories')}}";
    var UpdateCategory      = "{{url('updateCategory')}}";
    var DeleteCategory      = "{{url('DeleteCategory')}}";
    var editCategory        = "{{url('editCategory')}}";
    var ImportCategory      = "{{url('importCategory')}}";
</script>
<div class="content-page">
    <div class="content">

        <!-- Début du contenu -->
        <div class="container-fluid">

            <div class="py-3 d-flex align-items-sm-center flex-sm-row flex-column">
                <div class="flex-grow-1">
                    <h4 class="fs-18 fw-semibold m-0">Liste des catégories</h4>
                </div>
                
                <div class="text-end">
                    <ol class="breadcrumb m-0 py-0">
                        <li class="breadcrumb-item"><a href="javascript: void(0);">Applications</a></li>
                        <li class="breadcrumb-item active">Catégories</li>
                    </ol>
                </div>
            </div>

            <div class="row">
                <div class="col-12">
                    <div class="card">

                        <div class="card-body">
                        <div class="mb-3">
    @can('Categories-ajoute')
    <button class="btn btn-primary" style="margin-right: 5px" data-bs-toggle="modal" data-bs-target="#ModalAddCategory">
        <i class="fa-solid fa-plus"></i> Ajouter une catégorie
    </button>
    
    <button class="btn btn-success" style="margin-right: 5px" data-bs-toggle="modal" data-bs-target="#ModalImportCategory">
        <i class="fa-solid fa-file-import"></i> Importer des catégories
    </button>
    @endcan
</div>
                            
                            <!-- Liste des catégories -->
                            <div class="table-responsive">
                                <div id="DataTables_Table_0_wrapper" class="dataTables_wrapper dt-bootstrap5 no-footer table-responsive">
                                    <table class="table datatable dataTable no-footer TableCategories" id="DataTables_Table_0" aria-describedby="DataTables_Table_0_info">
                                        <thead class="thead-light">
                                            <tr>
                                                <th scope="col">Nom</th>
                                                <th scope="col">Classe</th> <!-- Added classe column -->
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
        </div>

        @can('Categories-ajoute')
        <!-- Modal Ajouter une Catégorie -->
        <div class="modal fade" id="ModalAddCategory" tabindex="-1" aria-labelledby="ModalAddCategoryLabel" aria-hidden="true">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="ModalAddCategoryLabel">Ajouter une nouvelle catégorie</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Fermer"></button>
                    </div>
                    <div class="modal-body">
                        <div class="row">
                            <ul class="validationAddCategory"></ul>
                            <form action="{{ url('addCategory') }}" id="FormAddCategory">
                                <!-- Nom -->
                                <div class="row">
                                    <div class="col-md-12">
                                        <div class="form-group mb-3">
                                            <label>Nom de la catégorie</label>
                                            <input type="text" name="name" class="form-control @error('name') is-invalid @enderror" value="{{ old('name') }}">
                                            @error('name')
                                                <span class="text-danger">{{ $message }}</span>
                                            @enderror
                                        </div>
                                    </div>
                                </div>
                                <!-- Classe -->
                                <div class="row">
                                    <div class="col-md-12">
                                        <div class="form-group">
                                            <label>Classe de la catégorie</label>
                                            <input type="text" name="classe" class="form-control @error('classe') is-invalid @enderror" value="{{ old('classe') }}">
                                            @error('classe')
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
                        <button type="button" class="btn btn-primary" id="BtnAddCategory">Sauvegarder</button>
                    </div>
                </div>
            </div>
        </div>
        @endcan

        @can('Categories-modifier')
        <!-- Modal Modifier la Catégorie -->
        <div class="modal fade" id="ModalEditCategory" tabindex="-1" aria-labelledby="ModalEditCategoryLabel" aria-hidden="true">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="ModalEditCategoryLabel">Modifier la catégorie</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Fermer"></button>
                    </div>
                    <div class="modal-body">
                        <div class="row">
                            <ul class="validationEditCategory"></ul>
                            <form action="{{ url('updateCategory') }}" id="FormUpdateCategory">
                                <input type="hidden" id="id" name="id">
                                <!-- Nom -->
                                <div class="row">
                                    <div class="col-md-12">
                                        <div class="form-group mb-3">
                                            <label>Nom de la catégorie</label>
                                            <input type="text" id="name" name="name" class="form-control @error('name') is-invalid @enderror" value="{{ old('name') }}">
                                            @error('name')
                                                <span class="text-danger">{{ $message }}</span>
                                            @enderror
                                        </div>
                                    </div>
                                </div>
                                <!-- Classe -->
                                <div class="row">
                                    <div class="col-md-12">
                                        <div class="form-group">
                                            <label>Classe de la catégorie</label>
                                            <input type="text" id="classe" name="classe" class="form-control @error('classe') is-invalid @enderror" value="{{ old('classe') }}">
                                            @error('classe')
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
                        <button type="button" class="btn btn-primary" id="BtnUpdateCategory">Mettre à jour</button>
                    </div>
                </div>
            </div>
        </div>
        @endcan
    </div>
    @can('Categories-ajoute')

<!-- Modal Importer des Catégories -->
<div class="modal fade" id="ModalImportCategory" tabindex="-1" aria-labelledby="ModalImportCategoryLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="ModalImportCategoryLabel">Importer des catégories</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Fermer"></button>
            </div>
            <div class="modal-body">
                <div class="row">
                    <ul class="validationImportCategory"></ul>
                    <form action="{{ url('importCategory') }}" id="FormImportCategory" enctype="multipart/form-data">
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
                                    <p><strong>Note:</strong> Le fichier Excel doit contenir une colonne "nom" avec les noms des catégories et une colonne "classe" avec les classes correspondantes.</p>
                                    <p>Les doublons et les lignes vides seront ignorés automatiquement.</p>
                                </div>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
            <div class="modal-footer text-end">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Fermer</button>
                <button type="button" class="btn btn-success" id="BtnImportCategory">Importer</button>
            </div>
        </div>
    </div>
</div>
@endcan

</div>
@endsection