<!DOCTYPE html>
<html lang="en">
    <head>
        <meta charset="utf-8" />
        <title>Error | GESTOCK TOUARGA</title>
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <meta name="description" content="A fully featured admin theme which can be used to build CRM, CMS, etc."/>
        <meta name="author" content="Zoyothemes"/>
        <meta http-equiv="X-UA-Compatible" content="IE=edge" />

        <!-- App favicon -->
        <link rel="shortcut icon" href="assets/images/favicon.ico">

        <!-- App css -->
        <link href="{{asset('css/custom/app.min.css')}}" rel="stylesheet" type="text/css" id="app-style" />
        <link href="{{asset('css/custom/icons.min.css')}}" rel="stylesheet" type="text/css" />

        <script src="{{asset('js/head.js')}}"></script>
    </head>

    <body class="maintenance-bg-image">
        <!-- Begin page -->
        <div class="maintenance-pages">
            <div class="container-fluid p-0">
                <div class="row">
                    <div class="col-xl-12 align-self-center">
                        <div class="row">
                            <div class="col-md-5 mx-auto">
                                <div class="text-center">
                                    <div class="mb-0">
                                        <h3 class="fw-semibold text-dark text-capitalize">
                                            @if ($errors->any())
                                                <div class="">
                                                    {{ $errors->first() }}
                                                </div>
                                            @endif
                                        </h3>
                                        
                                        @php
    $errorMessage = $errors->first() ?? '';
    $instructionMessage = '';
    
    // Set specific instruction based on error message
    if (strpos($errorMessage, "n'as pas de fournisseur") !== false) {
        $instructionMessage = "Veuillez accéder à la section Fournisseurs et ajouter un nouveau fournisseur pour continuer.";
    } 
    elseif (strpos($errorMessage, "n'as pas de formateur") !== false) {
        $instructionMessage = "Veuillez accéder à la section Formateurs et ajouter un nouveau formateur pour continuer.";
    }
    elseif (strpos($errorMessage, "n'as pas de produits") !== false) {
        $instructionMessage = "Veuillez accéder à la section Produits et ajouter un nouveau produit pour continuer.";
    }
    // Add new conditions for categories, subcategories, etc.
    elseif (strpos($errorMessage, "n'as pas de catégories") !== false) {
        $instructionMessage = "Veuillez accéder à la section Catégories et ajouter une nouvelle catégorie pour continuer.";
    }
    elseif (strpos($errorMessage, "n'as pas de famille") !== false) {
        $instructionMessage = "Veuillez accéder à la section famille et ajouter une nouvelle sous-catégorie pour continuer.";
    }
    elseif (strpos($errorMessage, "n'as pas de locaux") !== false) {
        $instructionMessage = "Veuillez accéder à la section Locaux et ajouter un nouveau local pour continuer.";
    }
    elseif (strpos($errorMessage, "n'as pas de rayons") !== false) {
        $instructionMessage = "Veuillez accéder à la section Rayons et ajouter un nouveau rayon pour continuer.";
    }
    elseif (strpos($errorMessage, "n'as pas de TVAs") !== false) {
        $instructionMessage = "Veuillez accéder à la section TVAs et ajouter une nouvelle TVA pour continuer.";
    }
    elseif (strpos($errorMessage, "n'as pas d'unités") !== false) {
        $instructionMessage = "Veuillez accéder à la section Unités et ajouter une nouvelle unité pour continuer.";
    }
    elseif (strpos($errorMessage, "n'as pas de catégories") !== false) {
    $instructionMessage = "Veuillez accéder à la section Catégories et ajouter une nouvelle catégorie pour continuer.";
}
elseif (strpos($errorMessage, "n'as pas de locaux") !== false) {
    $instructionMessage = "Veuillez accéder à la section Locaux et ajouter un nouveau local pour continuer.";
}
    else {
        $instructionMessage = "Veuillez résoudre le problème indiqué ci-dessus pour continuer.";
    }
@endphp
                                        
                                        <p class="text-muted">{{ $instructionMessage }}</p>
                                    </div>

                                    <a class='btn btn-primary mt-3 me-1' href='{{ url()->previous() }}'>Retour à l'accueil</a>

                                    <div class="maintenance-img mt-4">
                                        <img src="{{asset('images/500-error.svg')}}" class="img-fluid" alt="error">
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <!-- END wrapper -->

        <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
        <script src="{{asset("js/bootstrap/js/bootstrap.bundle.min.js")}}"></script>
        <script src="{{asset("js/simplebar/simplebar.min.js")}}"></script>
        <script src="{{asset("js/node-waves/waves.min.js")}}"></script>
        <script src="{{asset("js/waypoint/lib/jquery.waypoints.min.js")}}"></script>
        <script src="{{asset("js/jquery-counterup/jquery.counterup.min.js")}}"></script>
        <script src="{{asset("js/feather-icons/feather.min.js")}}"></script>
        <script src="{{asset("js/app.js")}}"></script>
    </body>
</html>