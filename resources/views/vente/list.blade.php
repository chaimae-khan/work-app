@extends('dashboard.index')
@section('dashboard')
<!-- detail of command page  -->
<div class="content-page">
    <div class="content">

        <!-- Début du contenu -->
        <div class="container-fluid ">
            <div class="card card-body py-3 mt-3">
                <div class="row align-items-center">
                    <div class="col-12">
                        <div class="d-sm-flex align-items-center justify-space-between">
                            <h4 class="mb-4 mb-sm-0 card-title">Gestion de Production</h4>
                            <nav aria-label="breadcrumb" class="ms-auto">
                                <ol class="breadcrumb">
                                   
                                    <li class="breadcrumb-item" aria-current="page">
                                        <span class="badge fw-medium fs-6 bg-primary-subtle text-primary">
                                            Détail commande
                                        </span>
                                    </li>
                                </ol>
                            </nav>
                        </div>
                    </div>
                </div>
            </div>
        
            <div class="widget-content searchable-container list">
                <div class="card card-body">
                    <h5 class="card-title border p-2 bg-light rounded-2 mb-4">Information Demandeur par commande N° {{$bonVente->id}}</h5>
                    <div class="row">
                        <div class="col-md-12 col-xl-6">
                            <div class="form-group">
                                <div class="mb-4">
                                    <label for="" style="min-width: 115px">Nom Demandeur :</label>
                                    <span class="border p-2 bg-light rounded-2">{{$Formateur->prenom}} {{$Formateur->nom}}</span>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-12 col-xl-6">
                            <div class="form-group">
                                <div class="mb-4">
                                    <label for="" style="min-width: 115px">Téléphone Demandeur :</label>
                                    <span class="border p-2 bg-light rounded-2">{{$Formateur->telephone ?? 'Non spécifié'}}</span>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
         

<!-- Status History Section -->
@if(isset($statusHistory) && count($statusHistory) > 0)
<div class="card card-body">
    <h5 class="card-title border p-2 bg-light rounded-2">
        <i class="mdi mdi-history"></i> Historique des changements de statut
    </h5>
    <div class="table-responsive">
        <table class="table table-bordered table-striped">
            <thead class="table-light">
                <tr>
                    <th width="30%">Statut</th>
                    <th width="40%">Date de changement</th>
                    <th width="30%">Modifié par</th>
                </tr>
            </thead>
            <tbody>
                @foreach($statusHistory as $change)
                <tr>
                    <td>
                        @if ($change->status == 'Création')
                            <span class="badge bg-info">{{$change->status}}</span>
                        @elseif ($change->status == 'Validation')
                            <span class="badge bg-success">Réception</span>
                        @elseif ($change->status == 'Refus')
                            <span class="badge bg-danger">{{$change->status}}</span>
                        @elseif ($change->status == 'Livraison')
                            <span class="badge bg-primary">{{$change->status}}</span>
                        @elseif ($change->status == 'Réception')
                            <span class="badge bg-warning">Validation</span>
                        @elseif ($change->status == 'Visé')
                            <span class="badge bg-secondary">{{$change->status}}</span>
                        @else
                            <span class="badge bg-secondary">{{$change->status}}</span>
                        @endif
                    </td>
                    <td>
                        <i class="mdi mdi-calendar-clock"></i>
                        {{ \Carbon\Carbon::parse($change->date)->format('d/m/Y H:i:s') }}
                    </td>
                    <td>
                        <i class="mdi mdi-account"></i>
                        {{ $change->user_name }}
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>
</div>
@endif
         
        
            <div class="card card-body">
                <h5 class="card-title border p-2 bg-light rounded-2">Fiche détail Commande</h5>
                <div class="table-responsive">
                    <table class="table table-bordered table-striped TableLineOrder">
                        <thead>
                            <tr>
                                <th>Produit</th>
                                <th>Quantité</th>
                                <th>Quantité Transférée</th>
                                <th>Prix</th>
                                <th>Total</th>
                                <th>Détails Transferts</th>
                                <th>Détails Retours</th>
                            </tr>
                        </thead>
                        <tbody>
                            @php
                                $SumTotal = 0;
                            @endphp
                            @foreach ($Data_Vente as $value)
                                @php
                                    $SumTotal += $value->total;
                                @endphp
                                <tr>
                                    <td>{{$value->name}}</td>
                                    <td>{{$value->qte}}</td>
                                    <td>
                                        @if(isset($value->contente_transfert) && $value->contente_transfert > 0)
                                            <span class="badge bg-info">{{$value->contente_transfert}}</span>
                                        @else
                                            <span class="badge bg-secondary">0</span>
                                        @endif
                                    </td>
                                    <td class="text-end">{{ number_format($value->price_achat, 2, ',', ' ') }}</td>
                                    <td class="text-end">{{ number_format($value->total, 2, ',', ' ') }}</td>
                                    <td>
                                        @if(isset($transferDetails[$value->idproduit]))
                                            <button class="btn btn-sm btn-info" 
                                                    type="button" 
                                                    data-bs-toggle="collapse" 
                                                    data-bs-target="#transferDetails{{$value->idproduit}}" 
                                                    aria-expanded="false">
                                                <i class="fa-solid fa-eye"></i> Voir
                                            </button>
                                            
                                            <div class="collapse mt-2" id="transferDetails{{$value->idproduit}}">
                                                <div class="card card-body">
                                                    <h6 class="card-subtitle mb-2 text-muted">Détails des transferts</h6>
                                                    <table class="table table-sm">
                                                        <thead>
                                                            <tr>
                                                                <th>Destinataire</th>
                                                                <th>Quantité</th>
                                                                <th>Date</th>
                                                            </tr>
                                                        </thead>
                                                        <tbody>
                                                            @foreach($transferDetails[$value->idproduit] as $transfer)
                                                            <tr>
                                                                <td>{{$transfer->recipient_name}}</td>
                                                                <td>{{$transfer->quantite}}</td>
                                                                <td>{{date('d/m/Y H:i', strtotime($transfer->transfer_date))}}</td>
                                                            </tr>
                                                            @endforeach
                                                        </tbody>
                                                    </table>
                                                </div>
                                            </div>
                                        @else
                                            <span class="badge bg-secondary">Aucun transfert</span>
                                        @endif
                                    </td>
                                    <td>
                                        @if(isset($returnDetails[$value->idproduit]))
                                            <button class="btn btn-sm btn-warning" 
                                                    type="button" 
                                                    data-bs-toggle="collapse" 
                                                    data-bs-target="#returnDetails{{$value->idproduit}}" 
                                                    aria-expanded="false">
                                                <i class="fa-solid fa-eye"></i> Voir
                                            </button>
                                            
                                            <div class="collapse mt-2" id="returnDetails{{$value->idproduit}}">
                                                <div class="card card-body">
                                                    <h6 class="card-subtitle mb-2 text-muted">Détails des retours</h6>
                                                    <table class="table table-sm">
                                                        <thead>
                                                            <tr>
                                                                <th>Demandeur</th>
                                                                <th>Quantité</th>
                                                                <th>Date</th>
                                                            </tr>
                                                        </thead>
                                                        <tbody>
                                                            @foreach($returnDetails[$value->idproduit] as $return)
                                                            <tr>
                                                                <td>{{$return->recipient_name}}</td>
                                                                <td>{{$return->quantite}}</td>
                                                                <td>{{date('d/m/Y H:i', strtotime($return->return_date))}}</td>
                                                            </tr>
                                                            @endforeach
                                                        </tbody>
                                                    </table>
                                                </div>
                                            </div>
                                        @else
                                            <span class="badge bg-secondary">Aucun retour</span>
                                        @endif
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                    <div class="d-flex">
                        <div class="flex-fill"></div>
                        <div class="flex-fill">
                            <table class="table table-striped table-bordered">
                                <tbody>
                                    <tr>
                                        <th>Total HT</th>
                                        <th class="text-end">{{number_format($SumTotal, 2, ',', ' ')}} DH</th>
                                    </tr>
                                    <tr>
                                        <th>Statut</th>
                                        <th class="text-end">
                                            @if ($bonVente->status == 'Création')
                                                <span class="badge bg-info">{{$bonVente->status}}</span>
                                            @elseif ($bonVente->status == 'Validation')
                                                <span class="badge bg-success">{{$bonVente->status}}</span>
                                            @elseif ($bonVente->status == 'Refus')
                                                <span class="badge bg-danger">{{$bonVente->status}}</span>
                                            @elseif ($bonVente->status == 'Livraison')
                                                <span class="badge bg-primary">{{$bonVente->status}}</span>
                                            @else
                                                <span class="badge bg-secondary">{{$bonVente->status}}</span>
                                            @endif
                                        </th>
                                    </tr>
                                    <tr>
                                        <th>Type Commande</th>
                                        <th class="text-end">{{$bonVente->type_commande ?? 'Alimentaire'}}</th>
                                    </tr>
                                    
                                    @if($bonVente->type_commande === 'Alimentaire')
                                        <tr>
                                            <th>Type Menu</th>
                                            <th class="text-end">
                                                @php
                                                    $menuType = $bonVente->type_menu ?? 'Menu eleves';
                                                    $displayMenuType = $menuType === 'Menu eleves' ? 'Menu standard' : $menuType;
                                                @endphp
                                                {{$displayMenuType}}
                                            </th>
                                        </tr>
                                        
                                        <!-- Menu Composition Section - CORRECTED VERSION -->
                                        @if($bonVente->entree_names || $bonVente->plat_principal_names || $bonVente->accompagnement || $bonVente->dessert_names)
                                        <tr>
                                            <th colspan="2" class="text-center bg-info-subtle">
                                                <i class="mdi mdi-silverware-fork-knife"></i> Composition du Menu
                                            </th>
                                        </tr>
                                        
                                        @if($bonVente->entree_names)
                                        <tr>
                                            <th><i class="mdi mdi-food-fork-drink"></i> Entrée</th>
                                            <th class="text-end">{{$bonVente->entree_names}}</th>
                                        </tr>
                                        @endif
                                        
                                        @if($bonVente->plat_principal_names)
                                        <tr>
                                            <th><i class="mdi mdi-food"></i> Plat Principal</th>
                                            <th class="text-end">{{$bonVente->plat_principal_names}}</th>
                                        </tr>
                                        @endif
                                        
                                        @if($bonVente->accompagnement)
                                        <tr>
                                            <th><i class="mdi mdi-bread-slice"></i> Accompagnement</th>
                                            <th class="text-end">{{$bonVente->accompagnement}}</th>
                                        </tr>
                                        @endif
                                        
                                        @if($bonVente->dessert_names)
                                        <tr>
                                            <th><i class="mdi mdi-cupcake"></i> Dessert</th>
                                            <th class="text-end">{{$bonVente->dessert_names}}</th>
                                        </tr>
                                        @endif
                                        @endif
                                        
                                        <!-- Quantity attributes section -->
                                        <tr>
                                            <th colspan="2" class="text-center bg-warning-subtle">
                                                <i class="mdi mdi-account-group"></i> Effectifs
                                            </th>
                                        </tr>
                                        <tr>
                                            <th>Nombre d'élèves</th>
                                            <th class="text-end">{{$bonVente->eleves ?? 0}}</th>
                                        </tr>
                                        <tr>
                                            <th>Nombre de personnel</th>
                                            <th class="text-end">{{$bonVente->personnel ?? 0}}</th>
                                        </tr>
                                        <tr>
                                            <th>Nombre d'invités</th>
                                            <th class="text-end">{{$bonVente->invites ?? 0}}</th>
                                        </tr>
                                        <tr>
                                            <th>Nombre divers</th>
                                            <th class="text-end">{{$bonVente->divers ?? 0}}</th>
                                        </tr>
                                    @endif
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
            
            <div class="text-center mt-4">
                <a href="{{ url('FactureVente/' . app('Hashids\Hashids')->encode($bonVente->id)) }}" 
                   class="btn btn-info" target="_blank">
                    <i class="fa-solid fa-print"></i> Imprimer Bon de Commande
                </a>
                <a href="{{ url('Command') }}" class="btn btn-secondary">
                    <i class="fa-solid fa-arrow-left"></i> Retour
                </a>
            </div>
        </div>
    </div>
</div>
@endsection