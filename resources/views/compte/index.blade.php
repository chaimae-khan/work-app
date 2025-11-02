@extends('dashboard.index')

@section('dashboard')
<script src="{{asset('js/compte/compte.js')}}"></script>
<script>
    var csrf_token = "{{csrf_token()}}";
    var UpdateProfileUrl = "{{url('updateProfile')}}";
    var VerifyPasswordUrl = "{{url('verifyPassword')}}";
</script>
<script src="https://cdn.jsdelivr.net/npm/signature_pad@4.0.0/dist/signature_pad.umd.min.js"></script>

<div class="content-page">
    <div class="content">
        <!-- Start Content-->
        <div class="container-fluid">
            <div class="py-3 d-flex align-items-sm-center flex-sm-row flex-column">
                <div class="flex-grow-1">
                    <h4 class="fs-18 fw-semibold m-0">Mon Compte</h4>
                </div>
                <div class="text-end">
                    <ol class="breadcrumb m-0 py-0">
                        <li class="breadcrumb-item"><a href="javascript: void(0);">Dashboard</a></li>
                        <li class="breadcrumb-item active">Mon Compte</li>
                    </ol>
                </div>
            </div>

            <div class="row">
                <div class="col-xl-4 col-lg-5">
                    <!-- Carte profil -->
                    <div class="card text-center">
                        <div class="card-body">
                            <div class="pt-2 pb-2">
                                <div class="avatar-xl mx-auto">
                                    <span class="avatar-title rounded-circle bg-primary-subtle text-primary">
                                        {{ strtoupper(substr($user->prenom, 0, 1)) }}
                                    </span>
                                </div>
                                <h4 class="mt-3 mb-1" id="user-name">{{ $user->prenom }} {{ $user->nom }}</h4>
                                <p class="text-muted">{{ $userRoles }}</p>
                                
                                <button id="BtnEditProfile" class="btn btn-primary mt-2">
                                    <i class="fas fa-user-edit me-1"></i> Modifier mon profil
                                </button>
                            </div>
                        </div>
                    </div>
                    <!-- Fin carte profil -->

                    <!-- Carte informations -->
                    <div class="card">
                        <div class="card-header">
                            <h5 class="card-title mb-0">Informations du compte</h5>
                        </div>
                        <div class="card-body">
                            <div class="mb-3">
                                <h6 class="text-uppercase fs-13 mb-2">Adresse email</h6>
                                <p id="user-email">{{ $user->email }}</p>
                            </div>
                            <div class="mb-3">
                                <h6 class="text-uppercase fs-13 mb-2">Date de création</h6>
                                <p>{{ $user->created_at->format('d/m/Y') }}</p>
                            </div>
                            @if($user->updated_at)
                            <div class="mb-3">
                                <h6 class="text-uppercase fs-13 mb-2">Dernière mise à jour</h6>
                                <p>{{ $user->updated_at->format('d/m/Y à H:i') }}</p>
                            </div>
                            @endif
                        </div>
                    </div>
                    <!-- Fin carte informations -->
                </div>

                <div class="col-xl-8 col-lg-7">
                    <!-- Carte sécurité -->
                    <div class="card">
                        <div class="card-header">
                            <h5 class="card-title mb-0">Sécurité</h5>
                        </div>
                        <div class="card-body">
                            <div class="mb-3">
                                <h6 class="fs-15 mb-2">Mot de passe</h6>
                                <p class="text-muted mb-2">
                                    Pour des raisons de sécurité, il est recommandé de changer régulièrement 
                                    votre mot de passe et d'utiliser un mot de passe fort.
                                </p>
                                <button id="BtnChangePassword" class="btn btn-sm btn-primary">
                                    <i class="fas fa-key me-1"></i> Changer mon mot de passe
                                </button>
                            </div>
                            
                            <hr class="my-4">
                            
                            <div class="mb-3">
                                <h6 class="fs-15 mb-2">Permissions</h6>
                                <p class="text-muted">Votre compte dispose des permissions suivantes :</p>
                                <div class="mt-2">
                                    @foreach(explode(', ', $userRoles) as $role)
                                        <span class="badge bg-primary me-1 mb-1">{{ $role }}</span>
                                    @endforeach
                                </div>
                            </div>
                            
                            <hr class="my-4">
                            
                            <div class="mb-3">
                                <h6 class="fs-15 mb-2">Votre signature</h6>
                                <div class="mt-2">
                                    @if(Auth::user()->signature)
                                        <!-- Display existing signature -->
                                        <div class="border border-primary p-3 text-center">
                                            <img src="{{ asset(Auth::user()->signature) }}" 
                                                 alt="Signature" 
                                                 style="max-width: 100%; height: auto; max-height: 200px;">
                                        </div>
                                    @else
                                        <!-- Message when no signature exists -->
                                        <div class="alert alert-info">
                                            <i class="fas fa-info-circle me-2"></i>
                                            Veuillez ajouter votre signature s'il vous plaît.
                                        </div>
                                    @endif
                                </div>
                            </div>
                        </div>
                    </div>
                    <!-- Fin carte sécurité -->
                </div>
            </div>
        </div>
    </div>
    
    <!-- Modal d'édition du profil -->
    <div class="modal fade" id="ModalEditProfile" tabindex="-1" aria-labelledby="ModalEditProfileLabel" aria-hidden="true">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="ModalEditProfileLabel">Modifier mon profil</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <div class="row">
                        <ul class="validationEditProfile"></ul>
                        <form action="{{ url('updateProfile') }}" id="FormUpdateProfile">
                            <!-- Informations personnelles -->
                            <div class="row mb-3 profile-info-section">
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label>Prénom</label>
                                        <input type="text" id="prenom" name="prenom" class="form-control" value="{{ $user->prenom }}">
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label>Nom</label>
                                        <input type="text" id="nom" name="nom" class="form-control" value="{{ $user->nom }}">
                                    </div>
                                </div>
                            </div>
                            
                            <div class="row mb-3 profile-info-section">
                                <div class="col-md-12">
                                    <div class="form-group">
                                        <label>Adresse email</label>
                                        <input type="email" id="email" name="email" class="form-control" value="{{ $user->email }}">
                                    </div>
                                </div>
                            </div>
                            
                            <hr class="my-4 profile-info-section">
                            
                            <!-- Signature Section in Modal -->
                            <div class="row mb-3 profile-info-section">
                                <div class="col-md-12">
                                    <div class="form-group">
                                        <label>Signature</label>
                                        @if(Auth::user()->signature)
                                            <div id="signature-display" class="mb-2">
                                                <div class="border border-secondary p-2 text-center" style="background-color: #f8f9fa;">
                                                    <img src="{{ asset(Auth::user()->signature) }}" 
                                                         alt="Signature actuelle" 
                                                         style="max-width: 100%; height: auto; max-height: 150px;">
                                                </div>
                                                <button type="button" class="btn btn-sm btn-warning mt-2" id="BtnChangeSignature">
                                                    <i class="fas fa-edit"></i> Modifier la signature
                                                </button>
                                            </div>
                                        @endif
                                        
                                        <div id="signature-canvas-container" @if(Auth::user()->signature) style="display: none;" @endif>
                                            <canvas id="signature-pad" class="border border-secondary" width="600" height="200" style="width: 100%; max-width: 600px;"></canvas>
                                            <div class="mt-2">
                                                <button type="button" class="btn btn-sm btn-secondary" id="BtnClearSignature">
                                                    <i class="fas fa-eraser"></i> Effacer
                                                </button>
                                                @if(Auth::user()->signature)
                                                    <button type="button" class="btn btn-sm btn-outline-secondary" id="BtnCancelSignature">
                                                        <i class="fas fa-times"></i> Annuler
                                                    </button>
                                                @endif
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            
                            <hr class="my-4 profile-info-section">
                            
                            <!-- Changement de mot de passe (optionnel) -->
                            <div class="row mb-3 password-section">
                                <div class="col-12 profile-info-section">
                                    <h6 class="mb-2">Changement de mot de passe (optionnel)</h6>
                                </div>
                                <div class="col-12 password-only-section" style="display: none;">
                                    <h6 class="mb-2">Changement de mot de passe</h6>
                                </div>
                                <div class="col-md-12">
                                    <div class="form-group mb-3">
                                        <label>Mot de passe actuel</label>
                                        <div class="input-group">
                                            <input type="password" id="current_password" name="current_password" class="form-control">
                                            <span class="input-group-text">
                                                <i class="fas fa-eye toggle-password" data-toggle="#current_password" style="cursor: pointer;"></i>
                                            </span>
                                        </div>
                                        <small class="form-text text-muted profile-info-section">Requis uniquement pour changer de mot de passe</small>
                                    </div>
                                </div>
                                <div class="col-md-12">
                                    <div class="form-group mb-3">
                                        <label>Nouveau mot de passe</label>
                                        <div class="input-group">
                                            <input type="password" id="password" name="password" class="form-control">
                                            <span class="input-group-text">
                                                <i class="fas fa-eye toggle-password" data-toggle="#password" style="cursor: pointer;"></i>
                                            </span>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-12">
                                    <div class="form-group">
                                        <label>Confirmer le nouveau mot de passe</label>
                                        <div class="input-group">
                                            <input type="password" id="password_confirmation" name="password_confirmation" class="form-control">
                                            <span class="input-group-text">
                                                <i class="fas fa-eye toggle-password" data-toggle="#password_confirmation" style="cursor: pointer;"></i>
                                            </span>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
                <div class="modal-footer text-end">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Annuler</button>
                    <button type="button" class="btn btn-primary" id="BtnUpdateProfile">Mettre à jour</button>
                </div>
            </div>
        </div>
    </div>
    <!-- Fin Modal d'édition du profil -->
</div>
@endsection