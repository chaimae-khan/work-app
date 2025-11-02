<!DOCTYPE html>
<html lang="fr">

    <head>

        <meta charset="utf-8" />
        <title>GESTOCK TOUARGA</title>
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <meta name="description" content="Un thème d'administration entièrement fonctionnel qui peut être utilisé pour créer des CRM, CMS, etc."/>
        <meta name="author" content="Zoyothemes"/>
        <meta http-equiv="X-UA-Compatible" content="IE=edge" />

        <!-- App favicon -->
        <link rel="shortcut icon" href="assets/images/favicon.ico">

        <!-- App css -->
        
        <link href="{{asset('css/custom/app.min.css')}}" rel="stylesheet" type="text/css" id="app-style" />

        <link rel="stylesheet" href="{{asset('css/styleNotification.css')}}">
        <link href="https://unpkg.com/filepond@^4/dist/filepond.css" rel="stylesheet" />
        <!-- Icons -->
        <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.7.2/css/all.min.css" integrity="sha512-Evv84Mr4kqVGRNSgIGL/F/aIDqQb7xQ2vcrdIwxfjThSH8CSR7PBEakCr51Ck+w+/U6swU2Im1vVX0SVk9ABhg==" crossorigin="anonymous" referrerpolicy="no-referrer" />
        
        <link href="{{asset('css/custom/icons.min.css')}}" rel="stylesheet" type="text/css" />

        <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/MaterialDesign-Webfont/7.4.47/css/materialdesignicons.min.css" integrity="sha512-/k658G6UsCvbkGRB3vPXpsPHgWeduJwiWGPCGS14IQw3xpr63AEMdA8nMYG2gmYkXitQxDTn6iiK/2fD4T87qA==" crossorigin="anonymous" referrerpolicy="no-referrer" />

        
        <script src="{{asset('js/head.js')}}"></script>

        <!-- jQuery -->
        <!-- jQuery -->
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>

<!-- DataTables core -->
<link rel="stylesheet" href="https://cdn.datatables.net/1.13.4/css/jquery.dataTables.min.css">
<script src="https://cdn.datatables.net/1.13.4/js/jquery.dataTables.min.js"></script>

<!-- Buttons extension -->
<link rel="stylesheet" href="https://cdn.datatables.net/buttons/2.4.1/css/buttons.dataTables.min.css">
<script src="https://cdn.datatables.net/buttons/2.4.1/js/dataTables.buttons.min.js"></script>
<script src="https://cdn.datatables.net/buttons/2.4.1/js/buttons.html5.min.js"></script>
<script src="https://cdn.datatables.net/buttons/2.4.1/js/buttons.print.min.js"></script>
<script src="https://cdn.datatables.net/buttons/2.4.1/js/buttons.colVis.min.js"></script>

<!-- Dependencies for export buttons -->
<script src="https://cdnjs.cloudflare.com/ajax/libs/jszip/3.10.1/jszip.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.2.7/pdfmake.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.2.7/vfs_fonts.js"></script>


        <script src="{{asset('js/notification/index.js')}}"></script>

        <style>
             .dataTables_wrapper .dataTables_paginate .paginate_button {
                border-radius: 50% !important;
                padding: 0.5em 0.9em !important;
                background: rgb(202, 91, 176);
                background: linear-gradient(to bottom, #f9f9f9, #cfe8ff) !important;
                
                }
                /* Scrollbar track */
                ::-webkit-scrollbar {
                width: 12px;
                }

                ::-webkit-scrollbar-track {
                background: #f1f1f1;
                
                }

                /* Scrollbar thumb */
                ::-webkit-scrollbar-thumb {
                background: linear-gradient(to bottom, #f9f9f9, #cfe8ff);
                border-radius: 10px;

                }
               /*  body
                {
                    height: 100vh;
                    
                } */
                /* .logo-box .logo-sm img, 
    .logo-box .logo-lg img {
        height: 150px;
        width: auto;
        width: 300px;
        object-fit: contain;
    } */

    .logo-box .logo-lg {
        margin-top: 0;
        padding: 30px 0;
    }

    .logo-box .logo-sm {
        display: flex;
        align-items: center;
        justify-content: center;
    } 

    .logo-box .logo-lg {
        display: flex;
        align-items: center;
        justify-content: center;
    }
        </style>

    </head>

    <!-- body start -->
    <body data-menu-color="light" data-sidebar="default">
        {{-- <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script> --}}
        <!-- Begin page -->
        <div id="app-layout">
            
            <!-- Topbar Start -->
            <div class="topbar-custom">
                <div class="container-fluid">
                    <div class="d-flex justify-content-between">
                        <ul class="list-unstyled topnav-menu mb-0 d-flex align-items-center">
                            <li>
                                <button class="button-toggle-menu nav-link">
                                    <i data-feather="menu" class="noti-icon"></i>
                                </button>
                            </li>
                            <li class="d-none d-lg-block">
                                <h5 class="mb-0 text-uppercase">Bienvenue, {{Auth::user()->name}}</h5>
                            </li>
                        </ul>
                        {{-- <div class="py-2 w-50 w-md-50 w-lg-25 d-flex justify-content-center">
                            <p class="text-center mt-2 fs-3" style="
                                background: linear-gradient(to right, #cb6ce6, #ff5757);
                                -webkit-background-clip: text;
                                -webkit-text-fill-color: transparent;
                                font-weight: 500;
                                white-space: nowrap; /* يمنع كسر النص */
                            ">
                                Compagnie est active : {{$company}}
                            </p>
                        </div> --}}
                        
                        <ul class="list-unstyled topnav-menu mb-0 d-flex align-items-center">
                            

                            

                            <!-- Button Trigger Customizer Offcanvas -->
                            <li class="d-none d-sm-flex">
                                <button type="button" class="btn nav-link" data-toggle="fullscreen">
                                    <i data-feather="maximize" class="align-middle fullscreen noti-icon"></i>
                                </button>
                            </li>

                            <!-- Light/Dark Mode Button Themes -->
                            <li class="d-none d-sm-flex">
                                <button type="button" class="btn nav-link" id="light-dark-mode">
                                    <i data-feather="moon" class="align-middle dark-mode"></i>
                                    <i data-feather="sun" class="align-middle light-mode"></i>
                                </button>
                            </li>
                           <ul class="list-unstyled topnav-menu mb-0 d-flex align-items-center">
    <!-- Boutons existants -->
    
    <!-- Insérez le composant de notification ICI, avant le menu utilisateur -->
    @include('layouts.partials.notifications')
    
    <!-- User Dropdown -->
    <li class="dropdown notification-list topbar-dropdown">
        <!-- Dropdown utilisateur existant -->
    </li>
</ul>
                         

                            <!-- User Dropdown -->
                            <li class="dropdown notification-list topbar-dropdown">
                                <a class="nav-link dropdown-toggle nav-user me-0" data-bs-toggle="dropdown" href="#" role="button" aria-haspopup="false" aria-expanded="false">
                                    <img src="{{asset('images/user.jpg')}}" alt="user-image" class="rounded-circle" />
                                    <span class="pro-user-name ms-1"> {{ Auth::user()->name }} <i class="mdi mdi-chevron-down"></i></span>
                                </a>
                                <div class="dropdown-menu dropdown-menu-end profile-dropdown">
                                    <!-- item-->
                                    <div class="dropdown-header noti-title">
                                        <h6 class="text-overflow m-0">Bienvenue !</h6>
                                    </div>

                                    <!-- item-->
                                    <a class='dropdown-item notify-item' href="{{url('mon-compte')}}">
                                    <i class="mdi mdi-account-circle-outline fs-16 align-middle"></i>
                                    <span>Mon Compte</span>
                                    </a>

                                    <!-- item-->
                                    <!-- <a class='dropdown-item notify-item' href='/hando/html/auth-lock-screen'>
                                        <i class="mdi mdi-lock-outline fs-16 align-middle"></i>
                                        <span>Écran de verrouillage</span>
                                    </a> -->

                                    <div class="dropdown-divider"></div>

                                    <!-- item-->
                                    <a class='dropdown-item notify-item' href="{{ route('logout') }}"
                                        onclick="event.preventDefault();
                                                document.getElementById('logout-form').submit();">
                                        <i class="mdi mdi-location-exit fs-16 align-middle"></i>
                                        <span>Déconnexion</span>
                                    </a>
                                    <form id="logout-form" action="{{ route('logout') }}" method="POST" class="d-none">
                                        @csrf
                                    </form>
                                </div>
                            </li>
                        </ul>
                    </div>
                </div>
            </div>
            <!-- end Topbar -->

            <!-- Left Sidebar Start -->
            <div class="app-sidebar-menu">
                <div class="h-100" data-simplebar>

                    <!--- Sidemenu -->
                    <div id="sidebar-menu">

                        <div class="logo-box">
                            <a class='logo logo-light' href='/home'>
                                <span class="logo-sm">
                                    <img src="{{asset('images/2.png')}}" alt="" height="22">
                                </span>
                                <span class="logo-lg">
                                    <img src="{{asset('images/2.png')}}" alt="" height="160" width="200" style="margin-top:-68px">
                                </span>
                            </a>
                            <a class='logo logo-dark' href='/home'>
                                <span class="logo-sm">
                                    <img src="{{asset('images/2.png')}}" alt="" height="22">
                                </span>
                                <span class="logo-lg">
                                    <img src="{{asset('images/2.png')}}" alt="" height="160" width="200" style="margin-top:-68px">
                                </span>
                            </a>
                        </div>

                        <ul id="side-menu">
                            <li class="menu-title">Menu</li>
                            <li>
                                <a class='tp-link' href="/home" {{ Request::is('home') || Request::is('Dashboard') ? 'active' : '' }}>
                                    <i data-feather="home"></i>
                                    <span> Page d'accueil </span> 
                                </a>
                            </li>
                            @can('Products')
                            <li class="menu-title">Produits</li>

                            <li>
                             
                                <a class='tp-link' href="{{url('products')}}" >
                                    <i class="fa-solid fa-box"></i>
                                    <span> Produits </span>
                                </a>
                              
                            </li>
                            @endcan 
                            
                            <li>
                               <a class='tp-link' href="{{url('stock')}}">
                               <i class="fa-solid fa-warehouse"></i>
                               <span> Stock </span>
                             </a>
                            </li>
                            <li>
    <a class="tp-link" href="{{ url('stock/low-stock') }}">
        <i class="fa-solid fa-triangle-exclamation"></i>
        <span> Surveillance des stocks faibles </span>
    </a>
</li>

 <li>
    <a class="tp-link" href="{{ url('pertes') }}">
        <i class="fa-solid fa-trash"></i>
        <span>Gestion des Pertes</span>
    </a>
</li>

<li>
    <a class="tp-link" href="{{ url('stock/expiring') }}">
        <i class="fa-solid fa-hourglass-end"></i>
        <span>Produits proches de l’expiration ou expirés</span>
    </a>
</li>


@can('Voir-Stock-Demandeur')
<li>
    <a class='tp-link' href="{{url('formateur-stock')}}">
        <i class="fa-solid fa-boxes"></i>
        <span> Mon Stock </span>
    </a>
</li>
@endcan
             @can('Voir-Consommation')
<li class="menu-title">Consommation</li>

<li>
    <a class='tp-link' href="{{url('inventory')}}">
        <i class="fa-solid fa-boxes-stacked"></i>
        <span> Inventaire </span>
    </a>
</li>           

<li>
    <a class='tp-link' href="{{url('consumption')}}">
        <i class="fa-solid fa-utensils"></i>
        <span>Feuille de Consommation (Reporting)</span>
    </a>
</li>   
@endcan

@can('Voir-Rapport-Mensuel-Consommation')
<li>
    <a class='tp-link' href="{{url('consumption/monthly-breakdown')}}">
        <i class="fa-solid fa-chart-line"></i>
        <span> Analyse Mensuelle des Coûts </span>
    </a>
</li>
@endcan

@can('Voir-Consommation-Complète')
<li>
    <a class='tp-link' href="{{url('consumption/all')}}">
        <i class="fa-solid fa-chart-line"></i>
        <span>FEUILLE DE CONSOMMATION JOURNALIÈRE</span>
    </a>
</li>
@endcan
 @can('Fournisseurs')
                            <li class="menu-title">Fournisseur</li>

                            <li>
                             
                                <a class='tp-link' href="{{url('fournisseur')}}" >
                                    <i class="fa-solid fa-truck-field"></i>
                                    <span> Fournisseur </span>
                                </a>
                               
                            </li>
                            @endcan
                            <!-- @can('Formateurs')
                            <li class="menu-title">Demandeur </li>

                            <li>
                              
                                <a class='tp-link' href="{{url('client')}}">
                                    <i class="fa-solid fa-chalkboard-teacher"></i>
                                     <span> Demandeur  </span>
                                </a>
                             
                            </li>
                            @endcan -->
                            <li class="menu-title mt-2">Catégories</li>
                                            
                            <li>
                                @can('Categories')
                                <a class='tp-link' href='{{url('categories')}}'>
                                    <i class="fa-solid fa-list-check"></i>
                                    <span> Liste de Catégories </span>
                                </a>
                                @endcan
                            </li>
                            @can('Local')                      
                            <li class="menu-title mt-2">Local</li>
                                            
                            <li>
                             
                                <a class='tp-link' href='{{url('local')}}'>
                                    <i class="fa-solid fa-location-dot"></i>
                                    <span> Local </span>
                                </a>
                             
                            </li>
                            @endcan
                            @can('Rayon')
                            <li>
                              
                                <a class='tp-link' href='{{url('rayon')}}'>
                                    <i class="fa-solid fa-table-cells"></i>
                                    <span> Rayon </span>
                                </a>
                              
                            </li>
                            @endcan
                            @can('Famille')
                            <li class="menu-title mt-2">Famille</li>
                                            
                            <li>
                              
                                <a class='tp-link' href='{{url('subcategory')}}'>
                                    <i class="fa-solid fa-sitemap"></i>
                                    <span> Famille </span>
                                </a>
                              
                            </li>
                            @endcan
                            <!-- @can('Achat')
                            <li>
                              
                                <a class='tp-link' href='{{url('Achat')}}'>
                                    <i class="fa-solid fa-shopping-cart"></i>
                                    <span> Achats </span>
                                </a>
                               
                            </li> 
                            @endcan -->
                            @can('Commande')
                            <li>
                               
                                <a class='tp-link' href='{{url('Command')}}'>
                                    <i class="fa-solid fa-cash-register"></i>
                                    <span> commande </span>
                                </a>
                              
                            </li>
                            @endcan
                            @can('Historique')
                            <li>
                         
                                <a class='tp-link' href='{{url('audit')}}'>
                                    <i class="fa-solid fa-clock-rotate-left"></i>
                                    <span> Archive </span>
                                </a>
                               
                            </li>
                            @endcan
                         @can('Transfer')
                           <!-- <li class="menu-title">Transfer</li> -->
                            <li>
                               <a class='tp-link' href='{{url('Transfer')}}'>
                            <i class="fas fa-exchange-alt"></i> 
                              <span> Transfer </span>
                              </a>
                             </li>
                        @endcan
                       @can('retour')
                            <li>
                                <a class='tp-link' href='{{url('Router')}}'>
                                    <i class="fas fa-box-open"></i> 
                                    <span> Retour stock </span>
                                 </a>
                            </li>
                       @endcan
                            <!-- <li class="menu-title mt-2">Stockage</li>
                            <li>
                                <a href="#sidebarIcons" data-bs-toggle="collapse">
                                    <i data-feather="award"></i>
                                    <span> Situation de stockage </span>
                                    <span class="menu-arrow"></span>
                                </a>
                                <div class="collapse" id="sidebarIcons">
                                    <ul class="nav-second-level">
                                        <li>
                                            <a class='tp-link' href='/hando/html/icons-feather'>Sortie de caisses vides</a>
                                        </li>
                                        <li>
                                            <a class='tp-link' href='/hando/html/icons-mdi'>Entrée de marchandises</a>
                                        </li>
                                        <li>
                                            <a class='tp-link' href='/hando/html/icons-mdi'>Sortie de marchandises</a>
                                        </li>
                                        <li>
                                            <a class='tp-link' href='/hando/html/icons-mdi'>Retour de caisses vides</a>
                                        </li>
                                        <li>
                                            <a class='tp-link' href='/hando/html/icons-mdi'>Le bilan général</a>
                                        </li>
                                    </ul>
                                </div>
                            </li> -->
                            @can('utilisateur')
                            <li class="menu-title mt-2">Utilisateurs</li>

                            <li>
                                
                                <a class='tp-link' href="{{route('users.index')}}" >
                                    <span class="mdi mdi-account-group"></span>
                                    <span> Liste des utilisateurs </span>
                                </a>
                             
                            </li>
                            @endcan
                            @can('rôles')
                            <li class="menu-title mt-2">Pouvoirs</li>

                            <li>
                                
                                <a href="{{url('roles')}}" class='tp-link'>
                                    <span class="mdi mdi-account-key-outline"></span>
                                    <span>Pouvoirs utilisateurs</span>
                                </a>
                             
                            </li>
                            @endcan
                          @can('Plats')
    <li class="menu-title">Plats</li>

    <li>
        <a class='tp-link' href="{{url('plats')}}" >
            <i class="fa-solid fa-utensils"></i>
            <span> Plats </span>
        </a>
    </li>
@endcan
@can('Plats')
    <li class="menu-title">Composition des Plats</li>

    <li>
        <a class='tp-link' href="{{url('plat-composition')}}" >
            <i class="fa-solid fa-bowl-food"></i>
            <span> Composition des Plats </span>
        </a>
    </li>
@endcan
                            @can('Taxes')
                            <li class="menu-title mt-2">Divers</li>
                            <li>
                                <a href="#divers" data-bs-toggle="collapse">
                                    <i data-feather="grid"></i>
                                    <span>Divers</span>
                                    <span class="menu-arrow"></span>
                                </a>
                                <div class="collapse" id="divers">
                                    <ul class="nav-second-level">
                                  
                                        <!-- <li>
                                           
                                            <a class='tp-link' href="{{url('tva')}}">
                                                <i class="fa-solid fa-percent"></i>
                                                <span>Taxe</span>
                                            </a>
                                           
                                        </li> -->
                                        @endcan
                                        @can('Unité')
                                        <li>
                                           
                                            <a class='tp-link' href='{{url('unite')}}'>
                                                <i class="fa-solid fa-list-check"></i>
                                                <span>Unité</span>
                                            </a>
                                           
                                        </li>
                                        @endcan
                                    </ul>
                                </div>
                            </li>

                        </ul>
            
                    </div>
                    <!-- End Sidebar -->

                    <div class="clearfix"></div>

                </div>
            </div>
            <!-- Left Sidebar End -->

            <!-- ============================================================== -->
            <!-- Start Page Content here -->
            <!-- ============================================================== -->
            @yield(section: 'dashboard')
            
            <!-- ============================================================== -->
            <!-- End Page content -->
            <!-- ============================================================== -->

        </div>
        <!-- END wrapper -->

        <!-- Vendor -->
        {{-- <script src="https://cdn.datatables.net/2.0.8/js/dataTables.js"></script> --}}
        {{-- <script src="{{asset('js/jquery/jquery.min.js')}}"></script> --}}
        <script src="{{asset("js/bootstrap/js/bootstrap.bundle.min.js")}}"></script>
        <script src="{{asset("js/simplebar/simplebar.min.js")}}"></script>
        <script src="{{asset("js/node-waves/waves.min.js")}}"></script>
        <script src="{{asset("js/waypoint/lib/jquery.waypoints.min.js")}}"></script>
        <script src="{{asset("js/jquery-counterup/jquery.counterup.min.js")}}"></script>
        <script src="{{asset("js/feather-icons/feather.min.js")}}"></script>
        <script src="https://unpkg.com/filepond@^4/dist/filepond.js"></script>

        <!-- Apexcharts JS -->
        
        <script src="{{asset("js/apexcharts/apexcharts.min.js")}}"></script>

        <!-- Widgets Init Js -->
        
        <script src="{{asset("js/pages/crm-dashboard.init.js")}}"></script>

        <!-- App js-->
        
        <script src="{{asset("js/app.js")}}"></script>

    </body>

</html>