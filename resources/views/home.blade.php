@extends('dashboard.index')

@section('dashboard')
<div class="content-page">
    <div class="content">
        <!-- Début du contenu -->
        <div class="container-fluid">
            <div class="py-4 d-flex align-items-sm-center flex-sm-row flex-column">
                <div class="flex-grow-1">
                    <h4 class="fs-20 fw-semibold m-0">Tableau de bord</h4>
                    <p class="text-muted mb-0">Bienvenue sur votre tableau de bord</p>
                </div>
                <div class="text-end">
                    <span class="badge bg-primary-subtle text-primary fs-13 px-3 py-2">
                        <i class="fa-solid fa-calendar-days me-1"></i>
                        {{ \Carbon\Carbon::now()->locale('fr')->isoFormat('dddd, D MMMM YYYY') }}
                    </span>
                </div>
            </div>

            <!-- Première ligne - Statistiques principales -->
            <div class="row g-3 mb-4">
                <!-- Total utilisateurs -->
                <div class="col-sm-6 col-xl-3">
                    <div class="card shadow-sm border-0 h-100 hover-lift">
                        <div class="card-body">
                            <div class="d-flex align-items-center">
                                <div class="flex-shrink-0">
                                    <div class="avatar-sm rounded-circle bg-primary-subtle">
                                        <span class="avatar-title text-primary rounded-circle fs-3">
                                            <i class="fa-solid fa-users"></i>
                                        </span>
                                    </div>
                                </div>
                                <div class="flex-grow-1 ms-3">
                                    <p class="text-muted mb-1 fs-13">Total utilisateurs</p>
                                    <h4 class="mb-0 fw-bold">{{ number_format($totalUtilisateurs) }}</h4>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Total fournisseurs -->
                <div class="col-sm-6 col-xl-3">
                    <div class="card shadow-sm border-0 h-100 hover-lift">
                        <div class="card-body">
                            <div class="d-flex align-items-center">
                                <div class="flex-shrink-0">
                                    <div class="avatar-sm rounded-circle bg-info-subtle">
                                        <span class="avatar-title text-info rounded-circle fs-3">
                                            <i class="fa-solid fa-building"></i>
                                        </span>
                                    </div>
                                </div>
                                <div class="flex-grow-1 ms-3">
                                    <p class="text-muted mb-1 fs-13">Total fournisseurs</p>
                                    <h4 class="mb-0 fw-bold">{{ number_format($totalFournisseurs) }}</h4>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Commandes en attente -->
                <div class="col-sm-6 col-xl-3">
                    <div class="card shadow-sm border-0 h-100 hover-lift">
                        <div class="card-body">
                            <div class="d-flex align-items-center">
                                <div class="flex-shrink-0">
                                    <div class="avatar-sm rounded-circle bg-warning-subtle">
                                        <span class="avatar-title text-warning rounded-circle fs-3">
                                            <i class="fa-solid fa-clock"></i>
                                        </span>
                                    </div>
                                </div>
                                <div class="flex-grow-1 ms-3">
                                    <p class="text-muted mb-1 fs-13">Commandes en attente</p>
                                    <h4 class="mb-0 fw-bold">{{ number_format($commandesEnAttente) }}</h4>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Commandes validées -->
                <div class="col-sm-6 col-xl-3">
                    <div class="card shadow-sm border-0 h-100 hover-lift">
                        <div class="card-body">
                            <div class="d-flex align-items-center">
                                <div class="flex-shrink-0">
                                    <div class="avatar-sm rounded-circle bg-success-subtle">
                                        <span class="avatar-title text-success rounded-circle fs-3">
                                            <i class="fa-solid fa-check-circle"></i>
                                        </span>
                                    </div>
                                </div>
                                <div class="flex-grow-1 ms-3">
                                    <p class="text-muted mb-1 fs-13">Commandes validées</p>
                                    <h4 class="mb-0 fw-bold">{{ number_format($commandesValidees) }}</h4>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Section Alertes Critiques -->
            <div class="row g-3 mb-4">
                <div class="col-12">
                    <h5 class="fs-16 fw-semibold mb-3">
                        <i class="fa-solid fa-bell text-danger me-2"></i>Alertes Critiques
                    </h5>
                </div>

                <!-- Stocks presque épuisés -->
                <div class="col-md-6 col-xl-4">
                    <div class="card shadow-sm border-0 h-100 hover-lift border-start border-danger border-4">
                        <div class="card-body">
                            <div class="d-flex align-items-start">
                                <div class="flex-shrink-0">
                                    <div class="avatar-md rounded bg-danger-subtle">
                                        <span class="avatar-title text-danger fs-2">
                                            <i class="fa-solid fa-box-open"></i>
                                        </span>
                                    </div>
                                </div>
                                <div class="flex-grow-1 ms-3">
                                    <h5 class="fs-15 mb-1">Stocks presque épuisés</h5>
                                    <p class="text-muted mb-3 fs-13">Produits nécessitant un réapprovisionnement urgent</p>
                                    <div class="d-flex align-items-center justify-content-between">
                                        <h3 class="mb-0 fw-bold text-danger">{{ number_format($stocksAlertes) }}</h3>
                                        @if($stocksAlertes > 0)
                                            <a href="{{ route('stock.lowstock') }}" class="btn btn-sm btn-danger">
                                                Voir détails <i class="fa-solid fa-arrow-right ms-1"></i>
                                            </a>
                                        @else
                                            <span class="badge bg-success-subtle text-success fs-12">Tous les stocks OK</span>
                                        @endif
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Produits en expiration -->
                <div class="col-md-6 col-xl-4">
                    <div class="card shadow-sm border-0 h-100 hover-lift border-start border-warning border-4">
                        <div class="card-body">
                            <div class="d-flex align-items-start">
                                <div class="flex-shrink-0">
                                    <div class="avatar-md rounded bg-warning-subtle">
                                        <span class="avatar-title text-warning fs-2">
                                            <i class="fa-solid fa-calendar-xmark"></i>
                                        </span>
                                    </div>
                                </div>
                                <div class="flex-grow-1 ms-3">
                                    <h5 class="fs-15 mb-1">Produits en expiration</h5>
                                    <p class="text-muted mb-3 fs-13">Produits expirant dans les 30 prochains jours</p>
                                    <div class="d-flex align-items-center justify-content-between">
                                        <h3 class="mb-0 fw-bold text-warning">{{ number_format($Product_Exepration->count()) }}</h3>
                                        @if($Product_Exepration->count() > 0)
                                            <a href="{{ route('stock.expiring') }}" class="btn btn-sm btn-warning">
                                                Voir détails <i class="fa-solid fa-arrow-right ms-1"></i>
                                            </a>
                                        @else
                                            <span class="badge bg-success-subtle text-success fs-12">Aucune expiration proche</span>
                                        @endif
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

             
            <!-- Pertes de produits -->
<div class="col-md-6 col-xl-4">
    <div class="card shadow-sm border-0 h-100 hover-lift border-start border-info border-4">
        <div class="card-body">
            <div class="d-flex align-items-start">
                <div class="flex-shrink-0">
                    <div class="avatar-md rounded bg-info-subtle">
                        <span class="avatar-title text-info fs-2">
                            <i class="fa-solid fa-triangle-exclamation"></i>
                        </span>
                    </div>
                </div>
                <div class="flex-grow-1 ms-3">
                    <h5 class="fs-15 mb-1">Produits endommagés</h5>
                    <p class="text-muted mb-3 fs-13">Quantité totale de produits perdus (validés)</p>
                    <div class="d-flex align-items-center justify-content-between">
                        <h3 class="mb-0 fw-bold text-info">{{ number_format($totalQuantitePertesValidees ?? 0, 2) }}</h3>
                        @if(($pertesEnAttente ?? 0) > 0)
                            <a href="{{ route('pertes.index') }}" class="btn btn-sm btn-info">
                                {{ $pertesEnAttente }} en attente <i class="fa-solid fa-arrow-right ms-1"></i>
                            </a>
                        @else
                            <a href="{{ route('pertes.index') }}" class="btn btn-sm btn-outline-info">
                                Voir tout <i class="fa-solid fa-arrow-right ms-1"></i>
                            </a>
                        @endif
                    </div>
                    @if($produitsAvecPertes > 0)
                    <small class="text-muted mt-2 d-block">
                        <i class="fa-solid fa-box"></i> {{ $produitsAvecPertes }} produit(s) affecté(s)
                    </small>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>
            </div>

            <!-- Section Aperçu des produits en expiration -->
            <!-- @if($Product_Exepration->count() > 0)
            <div class="row g-3 mb-4">
                <div class="col-12">
                    <div class="card shadow-sm border-0">
                        <div class="card-header bg-white border-bottom">
                            <div class="d-flex align-items-center justify-content-between">
                                <h5 class="card-title mb-0 fs-16 fw-semibold">
                                    <i class="fa-solid fa-calendar-xmark text-warning me-2"></i>
                                    Produits en Expiration Prochaine
                                </h5>
                                <a href="{{ route('stock.expiring') }}" class="btn btn-sm btn-outline-warning">
                                    Voir tout <i class="fa-solid fa-arrow-right ms-1"></i>
                                </a>
                            </div>
                        </div>
                        <div class="card-body">
                            <div class="table-responsive">
                                <table class="table table-hover table-nowrap align-middle mb-0">
                                    <thead class="table-light">
                                        <tr>
                                            <th scope="col" class="text-muted fs-13">Code Article</th>
                                            <th scope="col" class="text-muted fs-13">Nom du Produit</th>
                                            <th scope="col" class="text-muted fs-13">Date d'Expiration</th>
                                            <th scope="col" class="text-muted fs-13">Jours Restants</th>
                                            <th scope="col" class="text-muted fs-13">Statut</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach ($Product_Exepration->take(5) as $item)
                                        @php
                                            $expirationDate = \Carbon\Carbon::parse($item->date_expiration);
                                            $daysUntilExpiry = \Carbon\Carbon::now()->diffInDays($expirationDate, false);
                                        @endphp
                                        <tr>
                                            <td class="fw-medium">{{ $item->code_article }}</td>
                                            <td>{{ $item->name }}</td>
                                            <td>{{ $expirationDate->format('d/m/Y') }}</td>
                                            <td>
                                                @if($daysUntilExpiry < 0)
                                                    <span class="text-dark fw-medium">Expiré</span>
                                                @elseif($daysUntilExpiry == 0)
                                                    <span class="text-danger fw-medium">Aujourd'hui</span>
                                                @else
                                                    <span class="text-warning fw-medium">{{ $daysUntilExpiry }} jour(s)</span>
                                                @endif
                                            </td>
                                            <td>
                                                @if($daysUntilExpiry < 0)
                                                    <span class="badge bg-dark-subtle text-dark">Expiré</span>
                                                @elseif($daysUntilExpiry == 0)
                                                    <span class="badge bg-danger">Expire aujourd'hui</span>
                                                @elseif($daysUntilExpiry <= 3)
                                                    <span class="badge bg-danger">Critique</span>
                                                @else
                                                    <span class="badge bg-warning">Attention</span>
                                                @endif
                                            </td>
                                        </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>
                            @if($Product_Exepration->count() > 5)
                            <div class="text-center mt-3">
                                <p class="text-muted fs-13 mb-0">
                                    Affichage de 5 sur {{ $Product_Exepration->count() }} produits
                                </p>
                            </div>
                            @endif
                        </div>
                    </div>
                </div>
            </div>
            @endif -->

            <!-- Section Aperçu des pertes récentes (OPTIONAL - NEW) -->
            <!-- @if(isset($recentPertes) && $recentPertes->count() > 0) -->
            <!-- <div class="row g-3 mb-4">
                <div class="col-12">
                    <div class="card shadow-sm border-0">
                        <div class="card-header bg-white border-bottom">
                            <div class="d-flex align-items-center justify-content-between">
                                <h5 class="card-title mb-0 fs-16 fw-semibold">
                                    <i class="fa-solid fa-triangle-exclamation text-info me-2"></i>
                                    Pertes Récentes
                                </h5>
                                <a href="{{ route('pertes.index') }}" class="btn btn-sm btn-outline-info">
                                    Voir tout <i class="fa-solid fa-arrow-right ms-1"></i>
                                </a>
                            </div>
                        </div>
                        <div class="card-body">
                            <div class="table-responsive">
                                <table class="table table-hover table-nowrap align-middle mb-0">
                                    <thead class="table-light">
                                        <tr>
                                            <th scope="col" class="text-muted fs-13">Produit</th>
                                            <th scope="col" class="text-muted fs-13">Catégorie</th>
                                            <th scope="col" class="text-muted fs-13">Quantité</th>
                                            <th scope="col" class="text-muted fs-13">Nature</th>
                                            <th scope="col" class="text-muted fs-13">Date de perte</th>
                                            <th scope="col" class="text-muted fs-13">Statut</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach ($recentPertes as $perte)
                                        <tr>
                                            <td class="fw-medium">{{ $perte->designation }}</td>
                                            <td>{{ $perte->category->name ?? '-' }}</td>
                                            <td>{{ number_format($perte->quantite, 2) }} {{ $perte->unite->name ?? '' }}</td>
                                            <td>
                                                <span class="badge bg-secondary-subtle text-secondary">{{ $perte->nature }}</span>
                                            </td>
                                            <td>{{ \Carbon\Carbon::parse($perte->date_perte)->format('d/m/Y') }}</td>
                                            <td>
                                                @if($perte->status === 'En attente')
                                                    <span class="badge bg-warning text-dark">
                                                        <i class="fa-solid fa-clock"></i> En attente
                                                    </span>
                                                @elseif($perte->status === 'Validé')
                                                    <span class="badge bg-success">
                                                        <i class="fa-solid fa-check"></i> Validé
                                                    </span>
                                                @else
                                                    <span class="badge bg-danger">
                                                        <i class="fa-solid fa-times"></i> Refusé
                                                    </span>
                                                @endif
                                            </td>
                                        </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>
                            @if($recentPertes->count() >= 5)
                            <div class="text-center mt-3">
                                <p class="text-muted fs-13 mb-0">
                                    Affichage des 5 pertes les plus récentes
                                </p>
                            </div>
                            @endif
                        </div>
                    </div>
                </div>
            </div> -->
            <!-- @endif -->

            <!-- Section Activités Récentes -->
        

        </div>
    </div>
    
    <footer class="footer">
        <div class="container-fluid">
            <div class="row">
                <div class="col fs-13 text-muted text-center">
                    &copy; <script>document.write(new Date().getFullYear())</script> - GESTOCK TOUARGA - Plateforme de gestion de stock
                </div>
            </div>
        </div>
    </footer>
</div>

<style>
/* Custom Styles for Dashboard */
.hover-lift {
    transition: all 0.3s ease;
}

.hover-lift:hover {
    transform: translateY(-5px);
    box-shadow: 0 0.5rem 1rem rgba(0, 0, 0, 0.15) !important;
}

.avatar-sm {
    width: 3rem;
    height: 3rem;
    display: flex;
    align-items: center;
    justify-content: center;
}

.avatar-md {
    width: 4rem;
    height: 4rem;
    display: flex;
    align-items: center;
    justify-content: center;
}

.avatar-title {
    display: flex;
    align-items: center;
    justify-content: center;
    width: 100%;
    height: 100%;
}

.card {
    border-radius: 0.5rem;
}

.table > :not(caption) > * > * {
    padding: 0.75rem 0.75rem;
}

@media (max-width: 767.98px) {
    .fs-20 {
        font-size: 1.125rem !important;
    }
    
    .avatar-sm {
        width: 2.5rem;
        height: 2.5rem;
    }
    
    .avatar-md {
        width: 3rem;
        height: 3rem;
    }
}
</style>

<!-- Scripts pour les graphiques (si nécessaire plus tard) -->
<script src="https://cdn.jsdelivr.net/npm/apexcharts"></script>
<script>
    // Placeholder pour futurs graphiques
    console.log('Dashboard loaded successfully');
</script>
@endsection