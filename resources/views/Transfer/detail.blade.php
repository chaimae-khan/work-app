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
    .transfer-direction {
        background: #f8f9fa;
        border-radius: 8px;
        padding: 15px;
        margin-bottom: 20px;
    }
</style>

<div class="content-page"> 
    <div class="content">
        <!-- Début du contenu -->
        <div class="container-fluid">

            <div class="py-3 d-flex align-items-sm-center flex-sm-row flex-column">
                <div class="flex-grow-1">
                    <h4 class="fs-18 fw-semibold m-0">Détails du Transfert #TRF-{{ str_pad($stockTransfer->id, 6, '0', STR_PAD_LEFT) }}</h4>
                </div>
                
                <div class="text-end">
                    <ol class="breadcrumb m-0 py-0">
                        <li class="breadcrumb-item"><a href="{{ url('transfer') }}">Transferts</a></li>
                        <li class="breadcrumb-item active">Détails</li>
                    </ol>
                </div>
            </div>

            <!-- Back Button -->
            <div class="row mb-3">
                <div class="col-12">
                    <a href="{{ url('Transfer') }}" class="btn btn-secondary">
                        <i class="fa-solid fa-arrow-left me-1"></i> Retour à la liste
                    </a>
                </div>
            </div>

            <!-- Transfer Direction Card -->
            <!-- <div class="row mb-3">
                <div class="col-12">
                    <div class="transfer-direction">
                        <div class="row align-items-center">
                            <div class="col-md-5 text-center">
                                <div class="detail-label">De (From)</div>
                                <div class="detail-value mt-2">
                                    <i class="fa-solid fa-user me-2"></i>
                                    <strong>{{ $fromUser->prenom ?? '' }} {{ $fromUser->nom ?? 'N/A' }}</strong>
                                </div>
                            </div>
                            <div class="col-md-2 text-center">
                                <i class="fa-solid fa-arrow-right fa-2x text-primary"></i>
                            </div>
                            <div class="col-md-5 text-center">
                                <div class="detail-label">Vers (To)</div>
                                <div class="detail-value mt-2">
                                    <i class="fa-solid fa-user me-2"></i>
                                    <strong>{{ $toUser->prenom ?? '' }} {{ $toUser->nom ?? 'N/A' }}</strong>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div> -->

            <!-- Transfer Information Card -->
            <div class="row">
                <div class="col-12">
                    <div class="card info-card">
                        <div class="card-header bg-primary text-white">
                            <h5 class="mb-0">
                                <i class="fa-solid fa-info-circle me-2"></i>Informations du Transfert
                            </h5>
                        </div>
                        <div class="card-body">
                            <!-- Creator Information (Always visible) -->
                            <div class="row mb-3">
                                <div class="col-md-6 mb-2">
                                    <span class="detail-label">Créé par:</span>
                                    <div class="detail-value">
                                        <i class="fa-solid fa-user-plus me-1"></i>{{ $stockTransfer->user->prenom ?? '' }} {{ $stockTransfer->user->nom ?? 'N/A' }}
                                    </div>
                                </div>
                                <div class="col-md-6 mb-2">
                                    <span class="detail-label">Date de création:</span>
                                    <div class="detail-value">
                                        <i class="fa-solid fa-calendar me-1"></i>{{ \Carbon\Carbon::parse($stockTransfer->created_at)->format('d/m/Y H:i') }}
                                    </div>
                                </div>
                            </div>

                            @if($statusChanger && in_array($stockTransfer->status, ['Validation', 'Refus']))
                            <hr>
                            <!-- Status Change Information (Only for Validation or Refus) -->
                            <div class="row mb-3">
                                <div class="col-md-4 mb-2">
                                    <span class="detail-label">Statut modifié par:</span>
                                    <div class="detail-value">
                                        <i class="fa-solid fa-user-edit me-1"></i>{{ $statusChanger }}
                                    </div>
                                </div>
                                <div class="col-md-4 mb-2">
                                    <span class="detail-label">Date de modification:</span>
                                    <div class="detail-value">
                                        <i class="fa-solid fa-clock me-1"></i>{{ $statusChangeDate }}
                                    </div>
                                </div>
                                <div class="col-md-4 mb-2">
                                    <span class="detail-label">Statut:</span>
                                    <div class="mt-1">
                                        @php
                                            $statusColors = [
                                                'Validation' => 'bg-success',
                                                'Refus' => 'bg-danger',
                                            ];
                                            $color = $statusColors[$stockTransfer->status] ?? 'bg-secondary';
                                        @endphp
                                        <span class="badge {{ $color }} badge-lg">{{ $stockTransfer->status }}</span>
                                    </div>
                                </div>
                            </div>
                            @endif

                            @if($stockTransfer->status === 'Refus' && $stockTransfer->refusal_reason)
                            <hr>
                            <div class="row">
                                <div class="col-12">
                                    <div class="alert alert-danger">
                                        <h6 class="alert-heading">
                                            <i class="fa-solid fa-exclamation-triangle me-2"></i>Motif de refus:
                                        </h6>
                                        <p class="mb-0">{{ $stockTransfer->refusal_reason }}</p>
                                    </div>
                                </div>
                            </div>
                            @endif
                        </div>
                    </div>
                </div>
            </div>

            <!-- Products Table -->
            <div class="row mt-3">
                <div class="col-12">
                    <div class="card">
                        <div class="card-header bg-light">
                            <h5 class="mb-0">
                                <i class="fa-solid fa-box me-2"></i>Liste des Produits Transférés
                            </h5>
                        </div>
                        <div class="card-body">
                            <div class="table-responsive">
                                <table class="table table-striped table-hover datatable" id="productsTable">
                                    <thead class="thead-light">
                                        <tr>
                                            <th scope="col">#</th>
                                            <th scope="col">Produit</th>
                                            <th scope="col">Code Article</th>
                                            <th scope="col">Quantité</th>
                                            <th scope="col">Unité</th>
                                            <th scope="col">TVA</th>
                                            <th scope="col">N° Commande</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach($lineTransfers as $index => $line)
                                        <tr>
                                            <td>{{ $index + 1 }}</td>
                                            <td><strong>{{ $line->product->name }}</strong></td>
                                            <td><span class="badge bg-info">{{ $line->product->code_article }}</span></td>
                                            <td><span class="badge bg-primary">{{ $line->quantite }}</span></td>
                                            <td>{{ $line->unite->name ?? '-' }}</td>
                                            <td>
                                                @if($line->tva)
                                                    {{ $line->tva->name }} ({{ $line->tva->taux }}%)
                                                @else
                                                    -
                                                @endif
                                            </td>
                                            <td>
                                                @if($line->vente)
                                                    {{ $line->vente->id }}
                                                @else
                                                    -
                                                @endif
                                            </td>
                                        </tr>
                                        @endforeach
                                    </tbody>
                                    <tfoot>
                                        <tr class="table-active">
                                            <th colspan="3" class="text-end">Total:</th>
                                            <th><span class="badge bg-primary badge-lg">{{ $totalQuantity }}</span></th>
                                            <th colspan="3"></th>
                                        </tr>
                                    </tfoot>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

        </div>
    </div>
</div>

<script>
$(document).ready(function() {
    // Initialize DataTable for products
    $('#productsTable').DataTable({
        processing: false,
        serverSide: false,
        autoWidth: false,
        pageLength: 25,
        language: {
            "sInfo": "Affichage de _START_ à _END_ sur _TOTAL_ produits",
            "sInfoEmpty": "Affichage de l'élément 0 à 0 sur 0 élément",
            "sInfoFiltered": "(filtré à partir de _MAX_ éléments au total)",
            "sLengthMenu": "Afficher _MENU_ éléments",
            "sLoadingRecords": "Chargement...",
            "sProcessing": "Traitement...",
            "sSearch": "Rechercher :",
            "sZeroRecords": "Aucun élément correspondant trouvé",
            "oPaginate": {
                "sFirst": "Premier",
                "sLast": "Dernier",
                "sNext": "Suivant",
                "sPrevious": "Précédent"
            }
        },
        order: [[0, 'asc']]
    });
});
</script>

@endsection