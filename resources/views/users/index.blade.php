@extends('dashboard.index')

@section('dashboard')
<script src="{{asset('js/Users/script.js')}}"></script>
<script>
    var csrf_token                      = "{{csrf_token()}}";
    var Adduser                         = "{{route('users.store')}}";
    var users                           = "{{route('users.index')}}";
    var UpdateUser                      = "{{url('updateUser')}}";
    var DeleteUser                      = "{{url('DeleteUser')}}";
    var ImportUsers                     = "{{url('importUsers')}}";
    var EditUser                        = "{{url('users')}}";
</script>
<script src="https://cdn.jsdelivr.net/npm/signature_pad@4.0.0/dist/signature_pad.umd.min.js"></script>
<div class="content-page">
    <div class="content">

        <!-- Start Content-->
        <div class="container-fluid">

            <div class="py-3 d-flex align-items-sm-center flex-sm-row flex-column">
                <div class="flex-grow-1">
                    <h4 class="fs-18 fw-semibold m-0">Liste des Utilisateurs</h4>
                </div>
                
                <div class="text-end">
                    <ol class="breadcrumb m-0 py-0">
                        <li class="breadcrumb-item"><a href="javascript: void(0);">Applications</a></li>
                        <li class="breadcrumb-item active">Utilisateurs</li>
                    </ol>
                </div>
            </div>

            <div class="row">
                <div class="col-12">
                    <div class="card">

                        <div class="card-body">
                        <div class="mb-3">
                            @can('utilisateur-ajoute')
                                <button class="btn btn-primary" style="margin-right: 5px" data-bs-toggle="modal" data-bs-target="#ModalAddUser">Ajouter utilisateur</button>
                                
                                <button class="btn btn-success" style="margin-right: 5px" data-bs-toggle="modal" data-bs-target="#ModalImportUsers">
                                    <i class="fa-solid fa-file-import"></i> Importer des utilisateurs
                                </button>
                            @endcan
                        </div>
                            <div class="table-responsive">
                                <div class="datatable-wrapper datatable-loading no-footer sortable fixed-height fixed-columns">
                                    
                                    <div class="datatable-container" style="height: 665.531px;">
                                        <table class="table datatable datatable-table TableUsers" >
                                        <thead>
    <tr>
        <th data-sortable="true">Matricule</th>
        <th data-sortable="true">Prénom</th>
        <th data-sortable="true">Nom</th>
        <th data-sortable="true">Email</th>
        <th data-sortable="true">Téléphone</th>
        <th data-sortable="true">Fonction</th>
        <th data-sortable="true">Rôles</th>
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
        </div>


       

         <!-- Modal -->
<div class="modal fade" id="ModalAddUser" tabindex="-1" aria-labelledby="ModalAddUserLabel" aria-hidden="true">
    <div class="modal-dialog modal-xl">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="ModalAddUserLabel">Ajouter un nouvel utilisateur</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <div class="row">
                    <ul class="validationAddUser"></ul>
                    <form action="{{ route('users.store') }}" id="FormAddUser">
                        <!-- Matricule & Nom & Prénom -->
                        <div class="row">
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label>Matricule</label>
                                    <input type="text" name="matricule" class="form-control @error('matricule') is-invalid @enderror" value="{{ old('matricule') }}">
                                    @error('matricule')
                                        <span class="text-danger">{{ $message }}</span>
                                    @enderror
                                </div>
                            </div>
                            
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label>Nom</label>
                                    <input type="text" name="nom" class="form-control @error('nom') is-invalid @enderror" value="{{ old('nom') }}">
                                    @error('nom')
                                        <span class="text-danger">{{ $message }}</span>
                                    @enderror
                                </div>
                            </div>
                            
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label>Prénom</label>
                                    <input type="text" name="prenom" class="form-control @error('prenom') is-invalid @enderror" value="{{ old('prenom') }}">
                                    @error('prenom')
                                        <span class="text-danger">{{ $message }}</span>
                                    @enderror
                                </div>
                            </div>
                        </div>
                
                        <!-- Email & Rôle -->
                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label>Email</label>
                                    <input type="email" name="email" class="form-control @error('email') is-invalid @enderror" value="{{ old('email') }}">
                                    @error('email')
                                        <span class="text-danger">{{ $message }}</span>
                                    @enderror
                                </div>
                            </div>
                            
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label>Rôle</label>
                                    <select class="select form-select @error('roles') is-invalid @enderror" name="roles">
                                        <option value="">Sélectionner</option>
                                        @forelse ($roles as $role)
                                        @if ($role != 'Super Admin')
                                            <option value="{{ $role }}" {{ (old('roles') == $role) ? 'selected' : '' }}>
                                                {{ $role }}
                                            </option>
                                        @else
                                            @if (Auth::user()->hasRole('Super Admin'))   
                                                <option value="{{ $role }}" {{ (old('roles') == $role) ? 'selected' : '' }}>
                                                    {{ $role }}
                                                </option>
                                            @endif
                                        @endif
                                    @empty
                                    @endforelse
                                    </select>
                                    @error('roles')
                                        <span class="text-danger">{{ $message }}</span>
                                    @enderror
                                </div>
                            </div>
                        </div>

                        <!-- Téléphone & Fonction -->
                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label>Téléphone</label>
                                    <input type="text" id="telephoneAdd" name="telephone" class="form-control @error('telephone') is-invalid @enderror" value="{{ old('telephone') }}">
                                    @error('telephone')
                                        <span class="text-danger">{{ $message }}</span>
                                    @enderror
                                </div>
                            </div>
                            
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label>Fonction</label>
                                    <input type="text" name="fonction" class="form-control @error('fonction') is-invalid @enderror" value="{{ old('fonction') }}">
                                    @error('fonction')
                                        <span class="text-danger">{{ $message }}</span>
                                    @enderror
                                </div>
                            </div>
                        </div>
                
                        <!-- Mot de passe & Confirmation du mot de passe -->
                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label>Mot de passe</label>
                                    <div class="pass-group">
                                        <input type="password" name="password" class="form-control pass-input @error('password') is-invalid @enderror">
                                        <span class="fas toggle-password fa-eye-slash"></span>
                                    </div>
                                    @error('password')
                                        <span class="text-danger">{{ $message }}</span>
                                    @enderror
                                </div>
                            </div>
                
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label>Confirmer le mot de passe</label>
                                    <div class="pass-group">
                                        <input type="password" name="password_confirmation" class="form-control pass-input">
                                        <span class="fas toggle-password fa-eye-slash"></span>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <div class="sug-group">
                                        <label for="">Signature</label>
                                    </div>
                                    
                                    <canvas id="signature-pad" class="border border-red"></canvas><br>
                                    
                                </div>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
            <div class="modal-footer text-end">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Fermer</button>
                <button type="button" class="btn btn-primary" id="BtnADDUser">Sauvegarder</button>
            </div>
        </div>
    </div>
</div>


        
<!-- Modal for editing user -->
<div class="modal fade" id="ModalEditUser" tabindex="-1" aria-labelledby="ModalEditUserLabel" aria-hidden="true">
    <div class="modal-dialog modal-xl">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="ModalEditUserLabel">Modifier l'utilisateur</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <div class="row">
                    <ul class="validationUpdateUser"></ul>
                    <form id="FormUpdateUser" method="POST">
                        @csrf
                        <!-- Hidden ID field for the user - this will be populated via JavaScript -->
                        <!-- <input type="hidden" name="id" id="edit_user_id"> -->
                        
                        <!-- Matricule & Nom & Prénom -->
                        <div class="row">
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label>Matricule</label>
                                    <input type="text" id="matricule" name="matricule" class="form-control">
                                </div>
                            </div>
                            
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label>Nom</label>
                                    <input type="text" id="nom" name="nom" class="form-control">
                                </div>
                            </div>
                            
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label>Prénom</label>
                                    <input type="text" id="prenom" name="prenom" class="form-control">
                                </div>
                            </div>
                        </div>
                
                        <!-- Email & Rôle -->
                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label>Email</label>
                                    <input type="email" id="email" name="email" class="form-control">
                                </div>
                            </div>
                            
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label>Rôle</label>
                                    <select class="form-select" name="roles" id="roles">
                                        <option value="">Sélectionner</option>
                                        @forelse ($roles as $role)
                                            @if ($role != 'Super Admin')
                                                <option value="{{ $role }}">{{ $role }}</option>
                                            @else
                                                @if (Auth::user()->hasRole('Super Admin'))   
                                                    <option value="{{ $role }}">{{ $role }}</option>
                                                @endif
                                            @endif
                                        @empty
                                        @endforelse
                                    </select>
                                </div>
                            </div>
                        </div>

                        <!-- Téléphone & Fonction -->
                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label>Téléphone</label>
                                    <input type="text" id="telephone" name="telephone" class="form-control">
                                </div>
                            </div>
                            
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label>Fonction</label>
                                    <input type="text" id="fonction" name="fonction" class="form-control">
                                </div>
                            </div>
                        </div>
                
                        <!-- Mot de passe & Confirmation du mot de passe -->
                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label>Mot de passe</label>
                                    <div class="pass-group">
                                        <input type="password" id="password" name="password" class="form-control pass-input">
                                        <span class="fas toggle-password fa-eye-slash"></span>
                                    </div>
                                    <small class="form-text text-muted">Laissez vide pour conserver le mot de passe actuel</small>
                                </div>
                            </div>
                
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label>Confirmer le mot de passe</label>
                                    <div class="pass-group">
                                        <input type="password" name="password_confirmation" class="form-control pass-input">
                                        <span class="fas toggle-password fa-eye-slash"></span>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
            <div class="modal-footer text-end">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Fermer</button>
                <button type="button" class="btn btn-primary" id="BtnUpdateUser">Mettre à jour</button>
            </div>
        </div>
    </div>
</div>
<!-- Import Users Modal -->
<div class="modal fade" id="ModalImportUsers" tabindex="-1" aria-labelledby="ModalImportUsersLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="ModalImportUsersLabel">Importer des Utilisateurs</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <div class="row">
                    <ul class="validationImportUsers"></ul>
                    <form action="{{ url('importUsers') }}" id="FormImportUsers" enctype="multipart/form-data">
                        <!-- Fichier -->
                        <div class="row">
                            <div class="col-md-12">
                                <div class="form-group">
                                    <label>Fichier Excel (XLSX, XLS, CSV)</label>
                                    <input type="file" name="file" id="import_file_users" class="form-control @error('file') is-invalid @enderror" accept=".xlsx,.xls,.csv">
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
                                    <p>- Une colonne "matricule" (optionnel)</p>
                                    <p>- Une colonne "nom" avec les noms de famille</p>
                                    <p>- Une colonne "prenom" avec les prénoms</p>
                                    <p>- Une colonne "email" avec les adresses email</p>
                                    <p>- Une colonne "telephone" (optionnel)</p>
                                    <p>- Une colonne "fonction" (optionnel)</p>
                                    <p>- Une colonne "role" avec les rôles des utilisateurs</p>
                                    <p>Les mots de passe seront générés automatiquement.</p>
                                </div>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
            <div class="modal-footer text-end">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Fermer</button>
                <button type="button" class="btn btn-success" id="BtnImportUsers">Importer</button>
            </div>
        </div>
    </div>
</div>
        
</div>

<script>
    
    document.getElementById('clear-signature').addEventListener('click', () => {
        signaturePad.clear();
    });
</script>

@endsection