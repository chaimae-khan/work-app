@extends('dashboard.index')

@section('dashboard')
<div class="content-page">
    <div class="content">
        <!-- Début du contenu -->
        <div class="container-fluid">
            <div class="py-4 d-flex align-items-sm-center flex-sm-row flex-column">
                <div class="flex-grow-1">
                    <h4 class="fs-20 fw-semibold m-0">Tableau de bord</h4>
                </div>
            </div>

            <!-- Première ligne de widgets avec animations subtiles -->
            <div class="row">
                <!-- Total utilisateurs -->
                <div class="col-md-6 col-lg-3 mb-4">
                    <div class="card shadow-sm border-0 h-100 transition-all">
                        <div class="card-body d-flex flex-column">
                            <div class="d-flex align-items-center mb-3">
                                <div class="p-3 bg-primary-subtle rounded-circle me-3">
                                    <div class="text-center">
                                        <i class="fa-solid fa-users fs-4 text-primary"></i>
                                    </div>
                                </div>
                                <div>
                                    <p class="mb-0 text-muted fs-13">Total utilisateurs</p>
                                    <h3 class="mb-0 fs-24 text-dark fw-bold mt-1">{{ number_format($totalUtilisateurs) }}</h3>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Total fournisseurs -->
                <div class="col-md-6 col-lg-3 mb-4">
                    <div class="card shadow-sm border-0 h-100 transition-all">
                        <div class="card-body d-flex flex-column">
                            <div class="d-flex align-items-center mb-3">
                                <div class="p-3 bg-info-subtle rounded-circle me-3">
                                    <div class="text-center">
                                        <i class="fa-solid fa-building fs-4 text-info"></i>
                                    </div>
                                </div>
                                <div>
                                    <p class="mb-0 text-muted fs-13">Total fournisseurs</p>
                                    <h3 class="mb-0 fs-24 text-dark fw-bold mt-1">{{ number_format($totalFournisseurs) }}</h3>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Commandes en attente -->
                <div class="col-md-6 col-lg-3 mb-4">
                    <div class="card shadow-sm border-0 h-100 transition-all">
                        <div class="card-body d-flex flex-column">
                            <div class="d-flex align-items-center mb-3">
                                <div class="p-3 bg-warning-subtle rounded-circle me-3">
                                    <div class="text-center">
                                        <i class="fa-solid fa-clock fs-4 text-warning"></i>
                                    </div>
                                </div>
                                <div>
                                    <p class="mb-0 text-muted fs-13">Commandes en attente</p>
                                    <h3 class="mb-0 fs-24 text-dark fw-bold mt-1">{{ number_format($commandesEnAttente) }}</h3>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Commandes validées -->
                <div class="col-md-6 col-lg-3 mb-4">
                    <div class="card shadow-sm border-0 h-100 transition-all">
                        <div class="card-body d-flex flex-column">
                            <div class="d-flex align-items-center mb-3">
                                <div class="p-3 bg-success-subtle rounded-circle me-3">
                                    <div class="text-center">
                                        <i class="fa-solid fa-check fs-4 text-success"></i>
                                    </div>
                                </div>
                                <div>
                                    <p class="mb-0 text-muted fs-13">Commandes validées</p>
                                    <h3 class="mb-0 fs-24 text-dark fw-bold mt-1">{{ number_format($commandesValidees) }}</h3>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Deuxième ligne de widgets -->
            <div class="row">
                <!-- Achats en attente -->
                <div class="col-md-4 mb-4">
                    <div class="card shadow-sm border-0 h-100 transition-all">
                        <div class="card-body d-flex flex-column">
                            <div class="d-flex align-items-center mb-3">
                                <div class="p-3 bg-danger-subtle rounded-circle me-3">
                                    <div class="text-center">
                                        <i class="fa-solid fa-shopping-cart fs-4 text-danger"></i>
                                    </div>
                                </div>
                                <div>
                                    <p class="mb-0 text-muted fs-13">Achats en attente</p>
                                    <h3 class="mb-0 fs-24 text-dark fw-bold mt-1">{{ number_format($achatsEnAttente) }}</h3>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Achats validés -->
                <div class="col-md-4 mb-4">
                    <div class="card shadow-sm border-0 h-100 transition-all">
                        <div class="card-body d-flex flex-column">
                            <div class="d-flex align-items-center mb-3">
                                <div class="p-3 bg-primary-subtle rounded-circle me-3">
                                    <div class="text-center">
                                        <i class="fa-solid fa-check-circle fs-4 text-primary"></i>
                                    </div>
                                </div>
                                <div>
                                    <p class="mb-0 text-muted fs-13">Achats validés</p>
                                    <h3 class="mb-0 fs-24 text-dark fw-bold mt-1">{{ number_format($achatsValides) }}</h3>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Nouveau widget: Stocks en alerte -->
                <div class="col-md-4 mb-4">
                    <div class="card shadow-sm border-0 h-100 transition-all">
                        <div class="card-body d-flex flex-column">
                            <div class="d-flex align-items-center mb-3">
                                <div class="p-3 bg-danger-subtle rounded-circle me-3">
                                    <div class="text-center">
                                        <i class="fa-solid fa-exclamation-triangle fs-4 text-danger"></i>
                                    </div>
                                </div>
                                <div>
                                    <p class="mb-0 text-muted fs-13">Stocks presque épuisés</p>
                                    <h3 class="mb-0 fs-24 text-dark fw-bold mt-1">{{ number_format($stocksAlertes) }}</h3>
                                </div>
                            </div>
                            @if($stocksAlertes > 0)
                                <div class="mt-auto text-end">
                                    <a href="{{ url('stock') }}" class="btn btn-sm btn-link text-danger p-0">
                                        Voir les stocks <i class="fa-solid fa-arrow-right ms-1"></i>
                                    </a>
                                </div>
                            @endif
                        </div>
                    </div>
                </div>
            </div>

            <!-- Troisième ligne avec les graphiques -->
            <!-- <div class="row"> -->
                <!-- Statut des commandes -->
                <!-- <div class="col-md-6 mb-4">
                    <div class="card shadow-sm border-0 transition-all">
                        <div class="card-header bg-white py-3">
                            <h5 class="card-title mb-0 fs-16 fw-semibold">Statut des commandes</h5>
                        </div>
                        <div class="card-body">
                            <div id="vente-status-chart" class="apex-charts" style="min-height: 250px;"></div>
                            <div class="row mt-4">
                                <div class="col-6">
                                    <div class="d-flex align-items-center mb-2">
                                        <span class="d-inline-block rounded-circle me-2" style="width: 10px; height: 10px; background-color: #FBBF24;"></span>
                                        <span class="fs-13 text-dark">Création</span>
                                    </div>
                                </div>
                                <div class="col-6">
                                    <div class="d-flex align-items-center mb-2">
                                        <span class="d-inline-block rounded-circle me-2" style="width: 10px; height: 10px; background-color: #4F46E5;"></span>
                                        <span class="fs-13 text-dark">Validation</span>
                                    </div>
                                </div>
                                <div class="col-6">
                                    <div class="d-flex align-items-center mb-2">
                                        <span class="d-inline-block rounded-circle me-2" style="width: 10px; height: 10px; background-color: #10B981;"></span>
                                        <span class="fs-13 text-dark">Livraison</span>
                                    </div>
                                </div>
                                <div class="col-6">
                                    <div class="d-flex align-items-center mb-2">
                                        <span class="d-inline-block rounded-circle me-2" style="width: 10px; height: 10px; background-color: #06B6D4;"></span>
                                        <span class="fs-13 text-dark">Réception</span>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div> -->

                <!-- Statut des achats -->
                <!-- <div class="col-md-6 mb-4">
                    <div class="card shadow-sm border-0 transition-all">
                        <div class="card-header bg-white py-3">
                            <h5 class="card-title mb-0 fs-16 fw-semibold">Statut des achats</h5>
                        </div>
                        <div class="card-body">
                            <div id="achat-status-chart" class="apex-charts" style="min-height: 250px;"></div>
                            <div class="row mt-4">
                                <div class="col-6">
                                    <div class="d-flex align-items-center mb-2">
                                        <span class="d-inline-block rounded-circle me-2" style="width: 10px; height: 10px; background-color: #FBBF24;"></span>
                                        <span class="fs-13 text-dark">Création</span>
                                    </div>
                                </div>
                                <div class="col-6">
                                    <div class="d-flex align-items-center mb-2">
                                        <span class="d-inline-block rounded-circle me-2" style="width: 10px; height: 10px; background-color: #4F46E5;"></span>
                                        <span class="fs-13 text-dark">Validation</span>
                                    </div>
                                </div>
                                <div class="col-6">
                                    <div class="d-flex align-items-center mb-2">
                                        <span class="d-inline-block rounded-circle me-2" style="width: 10px; height: 10px; background-color: #10B981;"></span>
                                        <span class="fs-13 text-dark">Livraison</span>
                                    </div>
                                </div>
                                <div class="col-6">
                                    <div class="d-flex align-items-center mb-2">
                                        <span class="d-inline-block rounded-circle me-2" style="width: 10px; height: 10px; background-color: #06B6D4;"></span>
                                        <span class="fs-13 text-dark">Réception</span>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div> -->
            <!-- </div>
        </div>
    </div> -->
    
    <footer class="footer">
        <div class="container-fluid">
            <div class="row">
                <div class="col fs-13 text-muted text-center">
                    &copy; <script>document.write(new Date().getFullYear())</script> - Plateforme de gestion
                </div>
            </div>
        </div>
    </footer>
</div>



<!-- Scripts pour les graphiques -->
<script src="https://cdn.jsdelivr.net/npm/apexcharts"></script>
<script src="{{ asset('js/home.js') }}"></script>
@endsection