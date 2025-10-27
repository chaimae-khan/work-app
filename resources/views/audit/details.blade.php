@extends('dashboard.index')

@section('dashboard')
<div class="content-page">
    <div class="content">
        <!-- Start Content-->
        <div class="container-fluid">
            <div class="py-3 d-flex align-items-sm-center flex-sm-row flex-column">
                <div class="flex-grow-1">
                    <h4 class="fs-18 fw-semibold m-0">Détails de l'audit</h4>
                </div>
                
                <div class="text-end">
                    <ol class="breadcrumb m-0 py-0">
                        <li class="breadcrumb-item"><a href="{{ url('audit') }}">Historique</a></li>
                        <li class="breadcrumb-item active">Détails</li>
                    </ol>
                </div>
            </div>

            <div class="row">
                <div class="col-12">
                    <div class="card">
                        <div class="card-body">
                            <!-- Info du modèle modifié -->
                            <div class="row mb-4">
                                <div class="col-md-6">
                                    <h5 class="card-title">Informations générales</h5>
                                    <div class="table-responsive">
                                        <table class="table table-bordered">
                                            <tr>
                                                <th width="30%">Type</th>
                                                <td>{{ $modelType }}</td>
                                            </tr>
                                            <tr>
                                                <!-- <th>Élément</th>
                                                <td>{{ $modelName }}</td> -->
                                            </tr>
                                            <tr>
                                                <th>Action</th>
                                                <td>
                                                    @if($audit->event == 'created')
                                                        <span class="badge bg-success">Création</span>
                                                    @elseif($audit->event == 'updated')
                                                        <span class="badge bg-info">Modification</span>
                                                    @elseif($audit->event == 'deleted')
                                                        <span class="badge bg-danger">Suppression</span>
                                                    @elseif($audit->event == 'restored')
                                                        <span class="badge bg-warning">Restauration</span>
                                                    @else
                                                        <span class="badge bg-secondary">{{ $audit->event }}</span>
                                                    @endif
                                                </td>
                                            </tr>
                                            <tr>
                                                <th>Utilisateur</th>
                                                <td>{{ $userName }}</td>
                                            </tr>
                                            <tr>
                                                <th>Date</th>
                                                <td>{{ \Carbon\Carbon::parse($audit->created_at)->format('d/m/Y H:i:s') }}</td>
                                            </tr>
                                        </table>
                                    </div>
                                </div>
                            </div>
                            
                            <!-- Table des modifications -->
                            <div class="row">
                                <div class="col-12">
                                    <h5 class="card-title">Modifications</h5>
                                    <div class="table-responsive">
                                        <table class="table table-bordered">
                                            <thead>
                                                <tr>
                                                    <th width="30%">Champ</th>
                                                    <th>Ancienne valeur</th>
                                                    <th>Nouvelle valeur</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                @if($audit->event === 'created')
                                                    @foreach($newValues as $key => $value)
                                                        <tr>
                                                            <td>{{ $fieldNames[$key] ?? $key }}</td>
                                                            <td class="text-muted">-</td>
                                                            <td class="text-success">{{ $formattedNewValues[$key] ?? $value }}</td>
                                                        </tr>
                                                    @endforeach
                                                @elseif($audit->event === 'updated')
                                                    @foreach($newValues as $key => $value)
                                                        @if(isset($oldValues[$key]))
                                                            <tr>
                                                                <td>{{ $fieldNames[$key] ?? $key }}</td>
                                                                <td class="text-danger">{{ $formattedOldValues[$key] ?? $oldValues[$key] }}</td>
                                                                <td class="text-success">{{ $formattedNewValues[$key] ?? $value }}</td>
                                                            </tr>
                                                        @endif
                                                    @endforeach
                                                @elseif($audit->event === 'deleted')
                                                    <tr>
                                                        <td colspan="3" class="text-center text-danger">Cet élément a été supprimé</td>
                                                    </tr>
                                                    @if(isset($newValues['deleted_at']))
                                                        <tr>
                                                            <td>Date de suppression</td>
                                                            <td class="text-muted">-</td>
                                                            <td class="text-danger">{{ $formattedNewValues['deleted_at'] ?? $newValues['deleted_at'] }}</td>
                                                        </tr>
                                                    @endif
                                                @elseif($audit->event === 'restored')
                                                    <tr>
                                                        <td colspan="3" class="text-center text-success">Cet élément a été restauré</td>
                                                    </tr>
                                                    @if(isset($oldValues['deleted_at']))
                                                        <tr>
                                                            <td>Date de suppression</td>
                                                            <td class="text-danger">{{ $formattedOldValues['deleted_at'] ?? $oldValues['deleted_at'] }}</td>
                                                            <td class="text-success">-</td>
                                                        </tr>
                                                    @endif
                                                @endif
                                            </tbody>
                                        </table>
                                    </div>
                                </div>
                            </div>

                            {{-- Special section for Achat audits --}}
                            @if($audit->auditable_type === 'App\\Models\\Achat' && isset($ligneAchatDetails) && count($ligneAchatDetails) > 0)
                                <!-- Achat Information Section -->
                                <div class="row mt-4">
                                    <div class="col-12">
                                        <h5 class="card-title">Informations de l'achat</h5>
                                        @if(isset($achatInfo) && $achatInfo)
                                            <div class="table-responsive">
                                                <table class="table table-bordered">
                                                    <tr>
                                                        <!-- <th width="20%">Numéro</th>
                                                        <td>#{{ $achatInfo->id }}</td> -->
                                                        <th width="20%">Statut</th>
                                                        <td>
                                                            @php
                                                                $badges = [
                                                                    'Création' => 'bg-secondary',
                                                                    'Validation' => 'bg-success',
                                                                    'Refus' => 'bg-danger',
                                                                    'Livraison' => 'bg-info',
                                                                    'Réception' => 'bg-primary'
                                                                ];
                                                                $badgeClass = $badges[$achatInfo->status] ?? 'bg-light';
                                                            @endphp
                                                            <span class="badge {{ $badgeClass }}">{{ $achatInfo->status }}</span>
                                                        </td>
                                                    </tr>
                                                    <tr>
                                                        <th>Fournisseur</th>
                                                        <td>{{ $achatInfo->fournisseur_name }}</td>
                                                        <th>Total</th>
                                                        <td><strong>{{ number_format($achatInfo->total, 2) }} DH</strong></td>
                                                    </tr>
                                                    <tr>
                                                        <th>Créé par</th>
                                                        <td>{{ $achatInfo->created_by_name }}</td>
                                                        <th>Date de création</th>
                                                        <td>{{ \Carbon\Carbon::parse($achatInfo->created_at)->format('d/m/Y H:i:s') }}</td>
                                                    </tr>
                                                </table>
                                            </div>
                                        @endif
                                    </div>
                                </div>

                                <!-- Products/Line Items Section -->
                                <div class="row mt-4">
                                    <div class="col-12">
                                        <h5 class="card-title">Articles de l'achat ({{ count($ligneAchatDetails) }} articles)</h5>
                                        <div class="table-responsive">
                                            <table class="table table-bordered table-striped">
                                                <thead class="table-dark">
                                                    <tr>
                                                        <th>Produit</th>
                                                        <th>Code</th>
                                                        <th>Prix Unit.</th>
                                                        <th>Quantité</th>
                                                        <th>Total</th>
                                                        <th>Ajouté par</th>
                                                    </tr>
                                                </thead>
                                                <tbody>
                                                    @php $grandTotal = 0; @endphp
                                                    @foreach($ligneAchatDetails as $line)
                                                        @php $grandTotal += $line->total_line; @endphp
                                                        <tr>
                                                            <td><strong>{{ $line->product_name }}</strong></td>
                                                            <td><code>{{ $line->code_article }}</code></td>
                                                            <td>{{ number_format($line->price_achat, 2) }} DH</td>
                                                            <td><span class="badge bg-primary">{{ $line->qte }}</span></td>
                                                            <td><strong>{{ number_format($line->total_line, 2) }} DH</strong></td>
                                                            <td><small>{{ $line->created_by ?: 'Système' }}</small></td>
                                                        </tr>
                                                    @endforeach
                                                </tbody>
                                                <tfoot class="table-light">
                                                    <tr>
                                                        <th colspan="4" class="text-end">Total de l'achat:</th>
                                                        <th><strong>{{ number_format($grandTotal, 2) }} DH</strong></th>
                                                        <th></th>
                                                    </tr>
                                                </tfoot>
                                            </table>
                                        </div>
                                    </div>
                                </div>
                            @endif

                            {{-- Special section for Vente (Commande) audits --}}
                            @if($audit->auditable_type === 'App\\Models\\Vente' && isset($ligneVenteDetails) && count($ligneVenteDetails) > 0)
                                <!-- Vente Information Section -->
                                <div class="row mt-4">
                                    <div class="col-12">
                                        <h5 class="card-title">Informations de la commande</h5>
                                        @if(isset($venteInfo) && $venteInfo)
                                            <div class="table-responsive">
                                                <table class="table table-bordered">
                                                    <tr>
                                                        <!-- <th width="20%">Numéro</th>
                                                        <td>#{{ $venteInfo->id }}</td> -->
                                                        <th width="20%">Statut</th>
                                                        <td>
                                                            @php
                                                                $badges = [
                                                                    'Création' => 'bg-secondary',
                                                                    'Validation' => 'bg-success',
                                                                    'Refus' => 'bg-danger',
                                                                    'Livraison' => 'bg-info',
                                                                    'Réception' => 'bg-primary'
                                                                ];
                                                                $badgeClass = $badges[$venteInfo->status] ?? 'bg-light';
                                                            @endphp
                                                            <span class="badge {{ $badgeClass }}">{{ $venteInfo->status }}</span>
                                                        </td>
                                                    </tr>
                                                    <tr>
                                                        <th>Formateur</th>
                                                        <td>{{ $venteInfo->formateur_name }}</td>
                                                        <th>Total</th>
                                                        <td><strong>{{ number_format($venteInfo->total, 2) }} DH</strong></td>
                                                    </tr>
                                                    <tr>
                                                        <th>Type</th>
                                                        <td>{{ $venteInfo->type_commande }}</td>
                                                        <th>Menu</th>
                                                        <td>{{ $venteInfo->type_menu ?? '-' }}</td>
                                                    </tr>
                                                    @if($venteInfo->type_commande === 'Alimentaire')
                                                        <tr>
                                                            <th>Élèves</th>
                                                            <td>{{ $venteInfo->eleves ?? 0 }}</td>
                                                            <th>Personnel</th>
                                                            <td>{{ $venteInfo->personnel ?? 0 }}</td>
                                                        </tr>
                                                        <tr>
                                                            <th>Invités</th>
                                                            <td>{{ $venteInfo->invites ?? 0 }}</td>
                                                            <th>Divers</th>
                                                            <td>{{ $venteInfo->divers ?? 0 }}</td>
                                                        </tr>
                                                        @if($venteInfo->entree || $venteInfo->plat_principal || $venteInfo->accompagnement || $venteInfo->dessert)
                                                            <tr>
                                                                <th>Entrée</th>
                                                                <td>{{ $venteInfo->entree ?? '-' }}</td>
                                                                <th>Plat principal</th>
                                                                <td>{{ $venteInfo->plat_principal ?? '-' }}</td>
                                                            </tr>
                                                            <tr>
                                                                <th>Accompagnement</th>
                                                                <td>{{ $venteInfo->accompagnement ?? '-' }}</td>
                                                                <th>Dessert</th>
                                                                <td>{{ $venteInfo->dessert ?? '-' }}</td>
                                                            </tr>
                                                        @endif
                                                    @endif
                                                </table>
                                            </div>
                                        @endif
                                    </div>
                                </div>

                                <!-- Products/Line Items Section -->
                                <div class="row mt-4">
                                    <div class="col-12">
                                        <h5 class="card-title">Articles de la commande ({{ count($ligneVenteDetails) }} articles)</h5>
                                        <div class="table-responsive">
                                            <table class="table table-bordered table-striped">
                                                <thead class="table-dark">
                                                    <tr>
                                                        <th>Produit</th>
                                                        <th>Code</th>
                                                        <th>Prix Unit.</th>
                                                        <th>Quantité</th>
                                                        <th>Total</th>
                                                        <th>Ajouté par</th>
                                                    </tr>
                                                </thead>
                                                <tbody>
                                                    @php $grandTotal = 0; @endphp
                                                    @foreach($ligneVenteDetails as $line)
                                                        @php $grandTotal += $line->total_line; @endphp
                                                        <tr>
                                                            <td><strong>{{ $line->product_name }}</strong></td>
                                                            <td><code>{{ $line->code_article }}</code></td>
                                                            <td>{{ number_format($line->price_achat, 2) }} DH</td>
                                                            <td><span class="badge bg-primary">{{ $line->qte }}</span></td>
                                                            <td><strong>{{ number_format($line->total_line, 2) }} DH</strong></td>
                                                            <td><small>{{ $line->created_by ?: 'Système' }}</small></td>
                                                        </tr>
                                                    @endforeach
                                                </tbody>
                                                <tfoot class="table-light">
                                                    <tr>
                                                        <th colspan="4" class="text-end">Total de la commande:</th>
                                                        <th><strong>{{ number_format($grandTotal, 2) }} DH</strong></th>
                                                        <th></th>
                                                    </tr>
                                                </tfoot>
                                            </table>
                                        </div>
                                    </div>
                                </div>
                            @endif
                            
                            <!-- Boutons d'action -->
                            <div class="row mt-4">
                                <div class="col-12 text-end">
                                    {{-- Quick Actions for Vente --}}
                                    @if($audit->auditable_type === 'App\\Models\\Vente')
                                        @can('Commande')
                                            @php
                                                $hashids = new \Hashids\Hashids();
                                                $encodedId = $hashids->encode($audit->auditable_id);
                                            @endphp
                                            <a href="{{ url('ShowBonVente/' . $encodedId) }}" 
                                               class="btn btn-primary me-2" 
                                               target="_blank">
                                                <i class="fa fa-eye"></i> Voir le bon
                                            </a>
                                            <a href="{{ url('FactureVente/' . $encodedId) }}" 
                                               class="btn btn-info me-2" 
                                               target="_blank">
                                                <i class="fa fa-print"></i> Facture
                                            </a>
                                        @endcan
                                    @endif

                                    {{-- Quick Actions for Achat (without buttons as requested) --}}
                                    @if($audit->auditable_type === 'App\\Models\\Achat')
                                        @can('Achat')
                                            @php
                                                $hashids = new \Hashids\Hashids();
                                                $encodedId = $hashids->encode($audit->auditable_id);
                                            @endphp
                                            <a href="{{ url('ShowBonReception/' . $encodedId) }}" 
                                               class="btn btn-primary me-2" 
                                               target="_blank">
                                                <i class="fa fa-eye"></i> Voir le bon de réception
                                            </a>
                                        @endcan
                                    @endif
                                    
                                    <a href="{{ url('audit') }}" class="btn btn-secondary">
                                        <i class="fa fa-arrow-left"></i> Retour à la liste
                                    </a>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection