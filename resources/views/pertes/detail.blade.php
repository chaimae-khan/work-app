@extends('dashboard.index')

@section('dashboard')

<style>
    .info-card {
        border-left: 4px solid #007bff;
    }
    .table-responsive {
        overflow-x: auto;
    }
    .badge-lg {
        font-size: 1rem;
        padding: 0.5rem 1rem;
    }
    .detail-label {
        font-weight: 600;
        color: #6c757d;
    }
    .detail-value {
        font-weight: 500;
        color: #212529;
    }
    .perte-info-section {
        background: #f8f9fa;
        border-radius: 8px;
        padding: 20px;
        margin-bottom: 20px;
    }
    .action-buttons {
        display: flex;
        gap: 10px;
        flex-wrap: wrap;
    }
</style>

<div class="content-page"> 
    <div class="content">
        <!-- Début du contenu -->
        <div class="container-fluid">

            <div class="py-3 d-flex align-items-sm-center flex-sm-row flex-column">
                <div class="flex-grow-1">
                    <h4 class="fs-18 fw-semibold m-0">Détails de la Perte #PRT-{{ str_pad($perte->id, 6, '0', STR_PAD_LEFT) }}</h4>
                </div>
                
                <div class="text-end">
                    <ol class="breadcrumb m-0 py-0">
                        <li class="breadcrumb-item"><a href="{{ route('pertes.index') }}">Pertes</a></li>
                        <li class="breadcrumb-item active">Détails</li>
                    </ol>
                </div>
            </div>

            <!-- Back Button -->
            <div class="row mb-3">
                <div class="col-12">
                    <a href="{{ route('pertes.index') }}" class="btn btn-secondary">
                        <i class="fa-solid fa-arrow-left me-1"></i> Retour à la liste
                    </a>
                </div>
            </div>

            <!-- Status Card -->
            <div class="row mb-3">
                <div class="col-12">
                    <div class="perte-info-section">
                        <div class="row align-items-center">
                            <div class="col-md-8">
                                <div class="detail-label mb-2">Statut de la perte</div>
                                <div>
                                    @if($perte->status == 'En attente')
                                        <span class="badge bg-warning text-dark badge-lg">
                                            <i class="fa-solid fa-clock me-1"></i> En attente
                                        </span>
                                    @elseif($perte->status == 'Validé')
                                        <span class="badge bg-success badge-lg">
                                            <i class="fa-solid fa-check me-1"></i> Validé
                                        </span>
                                    @elseif($perte->status == 'Refusé')
                                        <span class="badge bg-danger badge-lg">
                                            <i class="fa-solid fa-times me-1"></i> Refusé
                                        </span>
                                    @endif
                                </div>
                            </div>
                            <div class="col-md-4 text-end">
                                <div class="detail-label mb-2">Date de la perte</div>
                                <div class="detail-value">
                                    <i class="fa-solid fa-calendar-xmark me-1"></i>
                                    {{ \Carbon\Carbon::parse($perte->date_perte)->format('d/m/Y') }}
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
               <!-- Declaration Information Card -->
            <div class="row mt-3">
                <div class="col-12">
                    <div class="card info-card">
                        <div class="card-header bg-info text-white">
                            <h5 class="mb-0">
                                <i class="fa-solid fa-info-circle me-2"></i>Informations de Déclaration
                            </h5>
                        </div>
                        <div class="card-body">
                            <div class="row mb-3">
                                <div class="col-md-6 mb-2">
                                    <span class="detail-label">Déclaré par:</span>
                                    <div class="detail-value">
                                        <i class="fa-solid fa-user me-1"></i>{{ $perte->user->prenom ?? '' }} {{ $perte->user->nom ?? 'N/A' }}
                                    </div>
                                </div>
                                <div class="col-md-6 mb-2">
                                    <span class="detail-label">Date de déclaration:</span>
                                    <div class="detail-value">
                                        <i class="fa-solid fa-calendar-plus me-1"></i>{{ \Carbon\Carbon::parse($perte->created_at)->format('d/m/Y H:i') }}
                                    </div>
                                </div>
                            </div>

                            @if($perte->status == 'Refusé' && $perte->refusal_reason)
                            <hr>
                            <div class="row">
                                <div class="col-12">
                                    <div class="alert alert-danger">
                                        <h6 class="alert-heading">
                                            <i class="fa-solid fa-exclamation-triangle me-2"></i>Motif de refus:
                                        </h6>
                                        <p class="mb-0">{{ $perte->refusal_reason }}</p>
                                    </div>
                                </div>
                            </div>
                            @endif
                        </div>
                    </div>
                </div>
            </div>

            <!-- Product Information Card -->
            <div class="row">
                <div class="col-12">
                    <div class="card info-card">
                        <div class="card-header bg-primary text-white">
                            <h5 class="mb-0">
                                <i class="fa-solid fa-box me-2"></i>Informations du Produit
                            </h5>
                        </div>
                        <div class="card-body">
                            <div class="row mb-3">
                                <div class="col-md-6 mb-3">
                                    <span class="detail-label">Classe:</span>
                                    <div class="detail-value">
                                        <i class="fa-solid fa-layer-group me-1"></i>{{ $perte->classe }}
                                    </div>
                                </div>
                                <div class="col-md-6 mb-3">
                                    <span class="detail-label">Catégorie:</span>
                                    <div class="detail-value">
                                        <i class="fa-solid fa-folder me-1"></i>{{ $perte->category->name ?? 'N/A' }}
                                    </div>
                                </div>
                            </div>

                            <div class="row mb-3">
                                <div class="col-md-6 mb-3">
                                    <span class="detail-label">Famille:</span>
                                    <div class="detail-value">
                                        <i class="fa-solid fa-folder-open me-1"></i>{{ $perte->subcategory->name ?? 'N/A' }}
                                    </div>
                                </div>
                                <div class="col-md-6 mb-3">
                                    <span class="detail-label">Produit:</span>
                                    <div class="detail-value">
                                        <i class="fa-solid fa-tag me-1"></i><strong>{{ $perte->designation }}</strong>
                                    </div>
                                </div>
                            </div>

                            <hr>

                            <div class="row mb-3">
                                <div class="col-md-4 mb-3">
                                    <span class="detail-label">Quantité perdue:</span>
                                    <div class="detail-value mt-1">
                                        <span class="badge bg-danger badge-lg">{{ $perte->quantite }}</span>
                                    </div>
                                </div>
                                <div class="col-md-4 mb-3">
                                    <span class="detail-label">Unité:</span>
                                    <div class="detail-value">
                                        <i class="fa-solid fa-balance-scale me-1"></i>{{ $perte->unite->name ?? 'N/A' }}
                                    </div>
                                </div>
                                <div class="col-md-4 mb-3">
                                    <span class="detail-label">Nature:</span>
                                    <div class="detail-value">
                                        <i class="fa-solid fa-clipboard-list me-1"></i>{{ $perte->nature }}
                                    </div>
                                </div>
                            </div>

                            <hr>

                            <div class="row">
                                <div class="col-12 mb-3">
                                    <span class="detail-label">Cause / Raison:</span>
                                    <div class="detail-value mt-2">
                                        <div class="alert alert-light border">
                                            <i class="fa-solid fa-comment-dots me-1"></i>{{ $perte->cause }}
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

         

            <!-- Action Buttons -->
            <!-- @if(auth()->user()->can('Pertes-valider') || auth()->user()->can('Pertes-supprimer'))
            <div class="row mt-3">
                <div class="col-12">
                    <div class="card">
                        <div class="card-header bg-light">
                            <h5 class="mb-0">
                                <i class="fa-solid fa-cog me-2"></i>Actions
                            </h5>
                        </div>
                        <div class="card-body">
                            <div class="action-buttons">
                                @can('Pertes-valider')
                                    @if($perte->status == 'En attente')
                                        <button type="button" class="btn btn-success" id="validateBtn">
                                            <i class="fa-solid fa-check me-1"></i> Valider la perte
                                        </button>
                                        <button type="button" class="btn btn-danger" id="refuseBtn">
                                            <i class="fa-solid fa-times me-1"></i> Refuser la perte
                                        </button>
                                    @endif
                                @endcan
                                
                                @can('Pertes-supprimer')
                                    @if($perte->status !== 'Validé')
                                        <button type="button" class="btn btn-danger" id="deleteBtn">
                                            <i class="fa-solid fa-trash me-1"></i> Supprimer
                                        </button>
                                    @endif
                                @endcan
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            @endif -->

        </div>
    </div>
</div>

<!-- Refusal Reason Modal -->
<div class="modal fade" id="refusalModal" tabindex="-1" aria-labelledby="refusalModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header bg-danger text-white">
                <h5 class="modal-title" id="refusalModalLabel">
                    <i class="fa-solid fa-ban me-2"></i>Motif de refus
                </h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <div class="mb-3">
                    <label for="refusal_reason" class="form-label fw-bold">
                        Veuillez indiquer le motif de refus: <span class="text-danger">*</span>
                    </label>
                    <textarea class="form-control" id="refusal_reason" rows="4" required placeholder="Expliquez la raison du refus..."></textarea>
                    <div class="invalid-feedback">Le motif de refus est requis.</div>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">
                    <i class="fa-solid fa-times me-1"></i>Annuler
                </button>
                <button type="button" class="btn btn-danger" id="confirmRefuseBtn">
                    <i class="fa-solid fa-check me-1"></i>Confirmer le refus
                </button>
            </div>
        </div>
    </div>
</div>

@endsection

@section('scripts')
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script>
$(document).ready(function() {
    const perteId = {{ $perte->id }};
    
    // Validate button
    $('#validateBtn').on('click', function() {
        Swal.fire({
            title: 'Confirmer la validation?',
            text: "Cette action va réduire le stock du produit.",
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#28a745',
            cancelButtonColor: '#6c757d',
            confirmButtonText: '<i class="fa-solid fa-check me-1"></i> Oui, valider!',
            cancelButtonText: '<i class="fa-solid fa-times me-1"></i> Annuler'
        }).then((result) => {
            if (result.isConfirmed) {
                // Show loading
                Swal.fire({
                    title: 'Validation en cours...',
                    allowOutsideClick: false,
                    didOpen: () => {
                        Swal.showLoading();
                    }
                });

                $.ajax({
                    url: "{{ route('pertes.changeStatus') }}",
                    type: 'POST',
                    data: {
                        _token: "{{ csrf_token() }}",
                        id: perteId,
                        status: 'Validé'
                    },
                    success: function(response) {
                        Swal.fire({
                            icon: 'success',
                            title: 'Succès',
                            text: response.message,
                            confirmButtonColor: '#28a745',
                            timer: 2000
                        }).then(() => {
                            window.location.href = "{{ route('pertes.index') }}";
                        });
                    },
                    error: function(xhr) {
                        Swal.fire({
                            icon: 'error',
                            title: 'Erreur',
                            text: xhr.responseJSON?.message || 'Une erreur est survenue',
                            confirmButtonColor: '#dc3545'
                        });
                    }
                });
            }
        });
    });
    
    // Refuse button - show modal
    $('#refuseBtn').on('click', function() {
        $('#refusalModal').modal('show');
        $('#refusal_reason').val('').removeClass('is-invalid');
    });
    
    // Confirm refuse button
    $('#confirmRefuseBtn').on('click', function() {
        const refusalReason = $('#refusal_reason').val().trim();
        
        if (!refusalReason) {
            $('#refusal_reason').addClass('is-invalid');
            return;
        }
        
        $('#refusalModal').modal('hide');
        
        // Show loading
        Swal.fire({
            title: 'Refus en cours...',
            allowOutsideClick: false,
            didOpen: () => {
                Swal.showLoading();
            }
        });
        
        $.ajax({
            url: "{{ route('pertes.changeStatus') }}",
            type: 'POST',
            data: {
                _token: "{{ csrf_token() }}",
                id: perteId,
                status: 'Refusé',
                refusal_reason: refusalReason
            },
            success: function(response) {
                Swal.fire({
                    icon: 'success',
                    title: 'Succès',
                    text: response.message,
                    confirmButtonColor: '#28a745',
                    timer: 2000
                }).then(() => {
                    window.location.href = "{{ route('pertes.index') }}";
                });
            },
            error: function(xhr) {
                Swal.fire({
                    icon: 'error',
                    title: 'Erreur',
                    text: xhr.responseJSON?.message || 'Une erreur est survenue',
                    confirmButtonColor: '#dc3545'
                });
            }
        });
    });
    
 

});
</script>
@endsection