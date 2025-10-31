<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Consommation Journalière (Vue Côte à Côte) - {{ $date }}</title>
    
    <style>
@page {
            /* Custom page size: A4 width but taller to accommodate content */
            size: 210mm 400mm;
            margin: 5mm 5mm 20mm 5mm;
        }

        html, body {
            margin: 0;
            padding: 0;
            width: 100%;
            height: auto;
        }

        body {
            font-family: Arial, sans-serif;
            font-size: 24px;
            background-color: white;
            position: relative;
        }

        /* Page wrapper to contain each page */
        .page-wrapper {
            width: 100%;
            min-height: 330mm;
            position: relative;
            padding-bottom: 250px; /* Space for footer (unchanged) */
            padding-top: 320px; /* INCREASED: Space for header (was 280px) */
            page-break-after: always;
        }

        .page-wrapper:last-child {
            page-break-after: auto;
        }

        .content-wrapper {
            width: 100%;
            position: relative;
        }

        .header {
            position: absolute;
            top: 0;
            left: 0;
            width: 100%;
            text-align: center;
            margin-bottom: 8px;
            padding-bottom: 4px;
            page-break-inside: avoid;
            height: 500px; /* INCREASED: Header height (was 450px) */
            box-sizing: border-box;
        }

        .header-title {
            font-size: 60px;
            font-weight: bold;
            text-align: center;
            margin: 10px 0 20px 0; /* ADJUSTED: Less bottom margin for logo spacing */
            font-family: 'Arial Black', Arial, sans-serif;
            letter-spacing: 3px;
            position: relative;
            top: 20px; /* MOVED: Slightly lower to make room for logo below */
        }

        .ministry-info {
            position: absolute;
            top: 120px; /* MOVED: Below the title (was 25px) */
            left: 40px; /* BACK TO LEFT: Same as before (was 50%) */
            text-align: left; /* BACK TO LEFT: Left alignment (was center) */
            font-size: 36px;
            line-height: 2.2;
            font-weight: bold;
        }

        .ministry-info img {
            max-width: 800px;
            height: auto;
            max-height: 320px;
            transform: scale(1.3);
            transform-origin: left top; /* BACK TO LEFT: Scale from left-top (was center top) */
        }

        .date-title {
            font-size: 36px; /* INCREASED: Font size (was 28px) */
            font-weight: bold;
            margin: 15px 0; /* INCREASED: Margin (was 12px 0) */
            text-align: right;
            padding-right: 40px; /* INCREASED: Padding (was 30px) */
            clear: both;
            position: relative;
            width: 100%;
            box-sizing: border-box;
            display: block;
            overflow: hidden;
        }

        .footer {
            position: absolute;
            bottom: 0;
            left: 0;
            width: 100%;
            padding: 120px 0 250px 0; /* INCREASED: More padding (was 40px 0 100px 0) */
            height: 350px; /* INCREASED: Footer height (was 150px) */
            box-sizing: border-box;
            page-break-inside: avoid;
            background-color: white;
        }

        .footer .signature-title {
            font-weight: bold;
            text-transform: uppercase;
            font-size: 26px;
            position: absolute;
            bottom: 320px; /* ADJUSTED: Position from bottom (was 100px) */
            line-height: 1.5;
        }

        .footer .left-signature {
            left: 20px;
        }

        .footer .center-signature {
            left: 50%;
            transform: translateX(-50%);
        }

        .footer .right-signature {
            right: 20px;
        }

        .table-container {
            display: table;
            width: 100%;
            table-layout: fixed;
            margin-bottom: 8px;
        }

        .table-half {
            display: table-cell;
            width: 50%;
            padding: 0 2px;
            vertical-align: top;
        }

        .table-spacer {
            display: table-cell;
            width: 1%;
        }

        table {
            width: 100%;
            border-collapse: collapse;
        }

        table, th, td {
            border: 1px solid #000;
        }

        th, td {
            padding: 2px;
            text-align: center;
            font-size: 30px;
            min-height: 25px;
            line-height: 1.2;
        }

        th {
            background-color: #f2f2f2;
            font-weight: bold;
            font-size: 30px;
        }

        .category-header {
            background-color: #e9e9e9;
            font-weight: bold;
            text-align: left;
            page-break-after: avoid;
        }

        .table-totals {
            background-color: #e9e9e9;
            font-weight: bold;
        }

        .text-left {
            text-align: left;
            padding-left: 4px;
        }

        .text-right {
            text-align: right;
        }

        .section-title {
            background-color: #f2f2f2;
            padding: 6px;
            margin: 8px 0;
            font-weight: bold;
            text-align: center;
            font-size: 26px;
            page-break-before: auto;
            page-break-inside: avoid;
        }

        .effectif-section,
        .prix-section {
            page-break-inside: avoid;
            margin-top: 12px;
            margin-bottom: 20px;
        }

        .right-half-content {
            width: 100%;
            page-break-inside: avoid;
        }

        .right-half-content table {
            font-size: 26px;
        }

        .right-half-content table th,
        .right-half-content table td {
            padding: 3px;
            font-size: 26px;
        }

        .prix-section p {
            margin: 3px 0;
            font-size: 23px;
        }

        /* Specific styling for product name column */
        .product-name {
            max-width: 120px;
            word-wrap: break-word;
            text-align: left;
            padding-left: 4px;
            font-size: 26px;
        }

        /* Ensure tables fill available height */
        .main-table {
            height: auto;
            min-height: 600px;
        }

        /* Menu section styling */
        .menu-section {
            margin: 20px 0;
            page-break-inside: avoid;
        }

        .menu-section table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 25px;
        }

        .menu-section th,
        .menu-section td {
            border: 1px solid #333;
            padding: 8px;
            text-align: center;
        }

        .menu-section th {
            background-color: #f8f9fa;
            font-weight: bold;
            font-size: 24px;
        }

        .menu-section td {
            font-size: 28px;
            font-weight: bold;
            padding: 12px;
        }

        .menu-section .dotted-line {
            border-bottom: 1px dotted #999;
            height: 20px;
            width: 100%;
        }

        .menu-section h4 {
            text-align: center;
            font-size: 26px;
            margin: 15px 0 10px 0;
            font-weight: bold;
        }

        .menu-section h4:first-child {
            font-size: 28px;
            text-decoration: underline;
            margin-bottom: 15px;
            font-weight: bold;
        }

        /* Observations section styling */
        .observations-section {
            margin-top: 20px;
            page-break-inside: avoid;
            width: 100%;
        }

        .observations-title {
            text-align: center;
            font-size: 26px;
            font-weight: bold;
            margin-bottom: 15px;
            text-transform: uppercase;
            background-color: #f2f2f2;
            padding: 6px;
        }

        .observations-content {
            border: 1px solid #000;
            height: 120px;
            padding: 10px;
            background-color: white;
        }

        .observations-line {
            border-bottom: 1px solid #ccc;
            height: 25px;
            margin-bottom: 3px;
        }

        .observations-line:last-child {
            border-bottom: none;
        }
    </style>
</head>
<body>
    @php
        // Define maps for entrées
        $entriesMap = [];
        if (isset($data['entrees']) && isset($data['entrees']['consumptions'])) {
            foreach ($data['entrees']['consumptions'] as $consumption) {
                if (isset($consumption['products']) && is_array($consumption['products'])) {
                    foreach ($consumption['products'] as $product) {
                        $entriesMap[$product['product_id']] = $product;
                    }
                }
            }
        }
        
        // Define maps for sorties by menu type
        $sortiesMenus = [
            'Menu Élèves' => [],
            'Menu d\'application' => [],
            'Menu Spécial' => []
        ];
        
        if (isset($data['sorties']) && isset($data['sorties']['consumptions'])) {
            foreach ($data['sorties']['consumptions'] as $menu) {
                $menuType = $menu['type_menu'] ?? '';
                
                $targetMenu = null;
                if ($menuType === 'Menu specials' || $menuType === 'Menu Spécial') {
                    $targetMenu = 'Menu Spécial';
                } elseif ($menuType === "Menu d'application" || $menuType === 'Menu d\'application') {
                    $targetMenu = 'Menu d\'application';
                } elseif ($menuType === 'Menu eleves' || $menuType === 'Menu Élèves') {
                    $targetMenu = 'Menu Élèves';
                }
                
                if ($targetMenu && isset($menu['products']) && is_array($menu['products'])) {
                    foreach ($menu['products'] as $product) {
                        $sortiesMenus[$targetMenu][$product['product_id']] = $product;
                    }
                }
            }
        }
        
        // Collect all unique products
        $allProducts = [];
        foreach (array_merge(array_keys($entriesMap), 
                           array_keys($sortiesMenus['Menu Élèves']),
                           array_keys($sortiesMenus['Menu d\'application']),
                           array_keys($sortiesMenus['Menu Spécial'])) as $productId) {
            $product = null;
            if (isset($entriesMap[$productId])) {
                $product = $entriesMap[$productId];
            } elseif (isset($sortiesMenus['Menu Élèves'][$productId])) {
                $product = $sortiesMenus['Menu Élèves'][$productId];
            } elseif (isset($sortiesMenus['Menu d\'application'][$productId])) {
                $product = $sortiesMenus['Menu d\'application'][$productId];
            } elseif (isset($sortiesMenus['Menu Spécial'][$productId])) {
                $product = $sortiesMenus['Menu Spécial'][$productId];
            }
            
            if ($product && !isset($allProducts[$productId])) {
                $allProducts[$productId] = $product;
            }
        }
        
        if (isset($data['all_products']) && is_array($data['all_products'])) {
            foreach ($data['all_products'] as $product) {
                $productId = $product['product_id'] ?? null;
                if ($productId && !isset($allProducts[$productId])) {
                    $allProducts[$productId] = $product;
                }
            }
        }
        
        // Group products by category
        $categoryGroups = [];
        foreach ($allProducts as $product) {
            $categoryName = $product['category_name'] ?? 'Non catégorisé';
            if (!isset($categoryGroups[$categoryName])) {
                $categoryGroups[$categoryName] = [];
            }
            $categoryGroups[$categoryName][] = $product;
        }
        
        ksort($categoryGroups);
        
        $flatProducts = [];
        foreach ($categoryGroups as $categoryName => $products) {
            usort($products, function($a, $b) {
                return strcmp($a['name'] ?? '', $b['name'] ?? '');
            });
            
            foreach ($products as $product) {
                $flatProducts[] = [
                    'product' => $product,
                    'category' => $categoryName
                ];
            }
        }
        
        // Split products: 64 on left, 34 on right for page 1
        $page1LeftProducts = array_slice($flatProducts, 0, 64);
        $page1RightProducts = array_slice($flatProducts, 64, 34);
        
        // Remaining products for page 2
        $page2Products = array_slice($flatProducts, 98);
        
        // Split page 2 products evenly
        $page2LeftCount = ceil(count($page2Products) / 2);
        $page2LeftProducts = array_slice($page2Products, 0, $page2LeftCount);
        $page2RightProducts = array_slice($page2Products, $page2LeftCount);
        
        $globalProductCounter = 1;
        
        // Menu display name mapping function
        function getMenuDisplayName($menuType) {
            if ($menuType === 'Menu Élèves' || $menuType === 'Menu eleves') {
                return 'Menu Standard';
            }
            return $menuType;
        }
    @endphp
    
    <!-- PAGE 1 -->
    <div class="page-wrapper">
        <!-- PAGE 1 HEADER -->
        <div class="header">
            <div class="ministry-info">
                @if(isset($imageData) && $imageData)
                    <img src="data:image/png;base64,{{ $imageData }}" alt="Ministry Logo">
                @else
                    <!--Royaume du Maroc<br>-->
                    <!--MINISTÈRE DU TOURISME<br>-->
                    <!--Centre de qualification Professionnelle<br>-->
                    <!--Hôtelière et Touristique<br>-->
                    <!--de Touargas-->
                         Institut Spécialisé <br>
                        de Technologie Appliquée<br>
                        Hotelière et Touristique de Touarga - Rabat<br>
                @endif
            </div>
            
            <div class="header-title">FEUILLE DE CONSOMMATION</div>
        </div>
        
        <!-- PAGE 1 FOOTER -->
        <div class="footer">
            <div class="signature-title left-signature">L'ÉCONOME</div>
            <div class="signature-title center-signature">LE MAGASINIER</div>
            <div class="signature-title right-signature">LE DIRECTEUR</div>
        </div>
        
        <div class="content-wrapper">
            <!-- Date title -->
            <div class="date-title">JOURNALIÈRE DU {{ $date }} - Page 1</div>
            
            <!-- Small spacer -->
            <div style="height: 5px; clear: both;"></div>
            
            <div class="table-container">
                <!-- LEFT TABLE - 64 products -->
                <div class="table-half">
                    <table class="main-table">
                        <thead>
                            <tr>
                                <th rowspan="2" style="width: 25%;">Désignation des Articles</th>
                                <th colspan="3" style="width: 15%;">ENTRÉES</th>
                                <th colspan="9" style="width: 60%;">SORTIES</th>
                            </tr>
                            <tr>
                                <th>Qté</th>
                                <th>P.U</th>
                                <th>P.T</th>
                                <th colspan="3">MENU STANDARD</th>
                                <th colspan="3">MENU D'APP.</th>
                                <th colspan="3">MENU SPÉCIAL</th>
                            </tr>
                            <tr>
                                <th></th>
                                <th></th>
                                <th></th>
                                <th></th>
                                <th>Qté</th>
                                <th>P.U</th>
                                <th>P.T</th>
                                <th>Qté</th>
                                <th>P.U</th>
                                <th>P.T</th>
                                <th>Qté</th>
                                <th>P.U</th>
                                <th>P.T</th>
                            </tr>
                        </thead>
                        <tbody>
                            @php 
                                $currentCategory = null;
                                // Initialize table-specific totals for left table on page 1
                                $leftTableEntreeTotal = 0;
                                $leftTableElevesTotal = 0;
                                $leftTableApplicationTotal = 0;
                                $leftTableSpecialTotal = 0;
                            @endphp
                            @foreach ($page1LeftProducts as $item)
                                @php
                                    $product = $item['product'];
                                    $categoryName = $item['category'];
                                    
                                    if ($currentCategory !== $categoryName) {
                                        $currentCategory = $categoryName;
                                        echo '<tr class="category-header"><td colspan="13"><strong>' . strtoupper($categoryName) . '</strong></td></tr>';
                                    }
                                    
                                    $productId = $product['product_id'];
                                    $entreeData = $entriesMap[$productId] ?? ['quantity' => 0, 'unit_price' => 0, 'total_price' => 0];
                                    $elevesData = $sortiesMenus['Menu Élèves'][$productId] ?? ['quantity' => 0, 'unit_price' => 0, 'total_price' => 0];
                                    $applicationData = $sortiesMenus['Menu d\'application'][$productId] ?? ['quantity' => 0, 'unit_price' => 0, 'total_price' => 0];
                                    $specialData = $sortiesMenus['Menu Spécial'][$productId] ?? ['quantity' => 0, 'unit_price' => 0, 'total_price' => 0];
                                    
                                    // Add to table-specific totals only if there's actual data
                                    if ($entreeData['total_price'] > 0) {
                                        $leftTableEntreeTotal += $entreeData['total_price'];
                                    }
                                    if ($elevesData['total_price'] > 0) {
                                        $leftTableElevesTotal += $elevesData['total_price'];
                                    }
                                    if ($applicationData['total_price'] > 0) {
                                        $leftTableApplicationTotal += $applicationData['total_price'];
                                    }
                                    if ($specialData['total_price'] > 0) {
                                        $leftTableSpecialTotal += $specialData['total_price'];
                                    }
                                @endphp
                                <tr>
                                    <td class="product-name">{{ $globalProductCounter }}- {{ $product['name'] }}</td>
                                    <td>{{ $entreeData['quantity'] > 0 ? $entreeData['quantity'] : '' }}</td>
                                    <td>{{ $entreeData['unit_price'] > 0 ? number_format($entreeData['unit_price'], 2) : '' }}</td>
                                    <td>{{ $entreeData['total_price'] > 0 ? number_format($entreeData['total_price'], 2) : '' }}</td>
                                    <td>{{ $elevesData['quantity'] > 0 ? $elevesData['quantity'] : '' }}</td>
                                    <td>{{ $elevesData['unit_price'] > 0 ? number_format($elevesData['unit_price'], 2) : '' }}</td>
                                    <td>{{ $elevesData['total_price'] > 0 ? number_format($elevesData['total_price'], 2) : '' }}</td>
                                    <td>{{ $applicationData['quantity'] > 0 ? $applicationData['quantity'] : '' }}</td>
                                    <td>{{ $applicationData['unit_price'] > 0 ? number_format($applicationData['unit_price'], 2) : '' }}</td>
                                    <td>{{ $applicationData['total_price'] > 0 ? number_format($applicationData['total_price'], 2) : '' }}</td>
                                    <td>{{ $specialData['quantity'] > 0 ? $specialData['quantity'] : '' }}</td>
                                    <td>{{ $specialData['unit_price'] > 0 ? number_format($specialData['unit_price'], 2) : '' }}</td>
                                    <td>{{ $specialData['total_price'] > 0 ? number_format($specialData['total_price'], 2) : '' }}</td>
                                </tr>
                                @php $globalProductCounter++; @endphp
                            @endforeach
                            
                            <!-- Show table-specific totals at bottom of left table -->
                            <tr class="table-totals">
                                <td class="text-left"><strong>TOTAL</strong></td>
                                <td colspan="2"></td>
                                <td><strong>{{ $leftTableEntreeTotal > 0 ? number_format($leftTableEntreeTotal, 2) : '' }}</strong></td>
                                <td colspan="2"></td>
                                <td><strong>{{ $leftTableElevesTotal > 0 ? number_format($leftTableElevesTotal, 2) : '' }}</strong></td>
                                <td colspan="2"></td>
                                <td><strong>{{ $leftTableApplicationTotal > 0 ? number_format($leftTableApplicationTotal, 2) : '' }}</strong></td>
                                <td colspan="2"></td>
                                <td><strong>{{ $leftTableSpecialTotal > 0 ? number_format($leftTableSpecialTotal, 2) : '' }}</strong></td>
                            </tr>
                        </tbody>
                    </table>
                </div>
                
                <!-- Spacer between tables -->
                <div class="table-spacer"></div>
                
                <!-- RIGHT TABLE - 34 products + Menu Tables + Effectif & Prix -->
                <div class="table-half">
                    <table class="main-table">
                        <thead>
                            <tr>
                                <th rowspan="2" style="width: 25%;">Désignation des Articles</th>
                                <th colspan="3" style="width: 15%;">ENTRÉES</th>
                                <th colspan="9" style="width: 60%;">SORTIES</th>
                            </tr>
                            <tr>
                                <th>Qté</th>
                                <th>P.U</th>
                                <th>P.T</th>
                                <th colspan="3">MENU STANDARD</th>
                                <th colspan="3">MENU D'APP.</th>
                                <th colspan="3">MENU SPÉCIAL</th>
                            </tr>
                            <tr>
                                <th></th>
                                <th></th>
                                <th></th>
                                <th></th>
                                <th>Qté</th>
                                <th>P.U</th>
                                <th>P.T</th>
                                <th>Qté</th>
                                <th>P.U</th>
                                <th>P.T</th>
                                <th>Qté</th>
                                <th>P.U</th>
                                <th>P.T</th>
                            </tr>
                        </thead>
                        <tbody>
                            @php 
                                $currentCategory = null;
                                // Initialize table-specific totals for right table on page 1
                                $rightTableEntreeTotal = 0;
                                $rightTableElevesTotal = 0;
                                $rightTableApplicationTotal = 0;
                                $rightTableSpecialTotal = 0;
                            @endphp
                            @foreach ($page1RightProducts as $item)
                                @php
                                    $product = $item['product'];
                                    $categoryName = $item['category'];
                                    
                                    if ($currentCategory !== $categoryName) {
                                        $currentCategory = $categoryName;
                                        echo '<tr class="category-header"><td colspan="13"><strong>' . strtoupper($categoryName) . '</strong></td></tr>';
                                    }
                                    
                                    $productId = $product['product_id'];
                                    $entreeData = $entriesMap[$productId] ?? ['quantity' => 0, 'unit_price' => 0, 'total_price' => 0];
                                    $elevesData = $sortiesMenus['Menu Élèves'][$productId] ?? ['quantity' => 0, 'unit_price' => 0, 'total_price' => 0];
                                    $applicationData = $sortiesMenus['Menu d\'application'][$productId] ?? ['quantity' => 0, 'unit_price' => 0, 'total_price' => 0];
                                    $specialData = $sortiesMenus['Menu Spécial'][$productId] ?? ['quantity' => 0, 'unit_price' => 0, 'total_price' => 0];
                                    
                                    // Add to table-specific totals only if there's actual data
                                    if ($entreeData['total_price'] > 0) {
                                        $rightTableEntreeTotal += $entreeData['total_price'];
                                    }
                                    if ($elevesData['total_price'] > 0) {
                                        $rightTableElevesTotal += $elevesData['total_price'];
                                    }
                                    if ($applicationData['total_price'] > 0) {
                                        $rightTableApplicationTotal += $applicationData['total_price'];
                                    }
                                    if ($specialData['total_price'] > 0) {
                                        $rightTableSpecialTotal += $specialData['total_price'];
                                    }
                                @endphp
                                <tr>
                                    <td class="product-name">{{ $globalProductCounter }}- {{ $product['name'] }}</td>
                                    <td>{{ $entreeData['quantity'] > 0 ? $entreeData['quantity'] : '' }}</td>
                                    <td>{{ $entreeData['unit_price'] > 0 ? number_format($entreeData['unit_price'], 2) : '' }}</td>
                                    <td>{{ $entreeData['total_price'] > 0 ? number_format($entreeData['total_price'], 2) : '' }}</td>
                                    <td>{{ $elevesData['quantity'] > 0 ? $elevesData['quantity'] : '' }}</td>
                                    <td>{{ $elevesData['unit_price'] > 0 ? number_format($elevesData['unit_price'], 2) : '' }}</td>
                                    <td>{{ $elevesData['total_price'] > 0 ? number_format($elevesData['total_price'], 2) : '' }}</td>
                                    <td>{{ $applicationData['quantity'] > 0 ? $applicationData['quantity'] : '' }}</td>
                                    <td>{{ $applicationData['unit_price'] > 0 ? number_format($applicationData['unit_price'], 2) : '' }}</td>
                                    <td>{{ $applicationData['total_price'] > 0 ? number_format($applicationData['total_price'], 2) : '' }}</td>
                                    <td>{{ $specialData['quantity'] > 0 ? $specialData['quantity'] : '' }}</td>
                                    <td>{{ $specialData['unit_price'] > 0 ? number_format($specialData['unit_price'], 2) : '' }}</td>
                                    <td>{{ $specialData['total_price'] > 0 ? number_format($specialData['total_price'], 2) : '' }}</td>
                                </tr>
                                @php $globalProductCounter++; @endphp
                            @endforeach
                            
                            <!-- Show table-specific totals at bottom of right table -->
                            <tr class="table-totals">
                                <td class="text-left"><strong>TOTAL</strong></td>
                                <td colspan="2"></td>
                                <td><strong>{{ $rightTableEntreeTotal > 0 ? number_format($rightTableEntreeTotal, 2) : '' }}</strong></td>
                                <td colspan="2"></td>
                                <td><strong>{{ $rightTableElevesTotal > 0 ? number_format($rightTableElevesTotal, 2) : '' }}</strong></td>
                                <td colspan="2"></td>
                                <td><strong>{{ $rightTableApplicationTotal > 0 ? number_format($rightTableApplicationTotal, 2) : '' }}</strong></td>
                                <td colspan="2"></td>
                                <td><strong>{{ $rightTableSpecialTotal > 0 ? number_format($rightTableSpecialTotal, 2) : '' }}</strong></td>
                            </tr>
                        </tbody>
                    </table>
                    
                    <!-- Menu Tables before Effectif -->
                    @php
                        // Extract menu attributes for the 3 menu types
                        $menuAttributes = [
                            'Menu eleves' => ['entree' => '', 'plat_principal' => '', 'accompagnement' => '', 'dessert' => ''],
                            'Menu d\'application' => ['entree' => '', 'plat_principal' => '', 'accompagnement' => '', 'dessert' => ''],
                            'Menu specials' => ['entree' => '', 'plat_principal' => '', 'accompagnement' => '', 'dessert' => '']];
                        
                        // Extract attributes from sorties data
                        if (isset($data['sorties']) && isset($data['sorties']['consumptions'])) {
                            foreach ($data['sorties']['consumptions'] as $consumption) {
                                $menuType = $consumption['type_menu'] ?? '';
                                
                                if ($menuType && isset($menuAttributes[$menuType])) {
                                    $menuAttributes[$menuType]['entree'] = $consumption['entree'] ?? '';
                                    $menuAttributes[$menuType]['plat_principal'] = $consumption['plat_principal'] ?? '';
                                    $menuAttributes[$menuType]['accompagnement'] = $consumption['accompagnement'] ?? '';
                                    $menuAttributes[$menuType]['dessert'] = $consumption['dessert'] ?? '';
                                }
                            }
                        }
                    @endphp

                    <div class="menu-section">
                        <!-- Main MENU Table -->
                        <h4>MENU</h4>
                        <table>
                            <thead>
                                <tr>
                                    <th style="text-decoration: underline; width: 33.33%;">Petit Déjeuner</th>
                                    <th style="text-decoration: underline; width: 33.33%;">Lunch</th>
                                    <th style="text-decoration: underline; width: 33.33%;">Dîner</th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr>
                                    <td style="height: 50px; vertical-align: top; font-size: 28px; font-weight: bold; padding: 12px;">
                                        <div class="dotted-line"></div>
                                    </td>
                                    <td style="height: 50px; vertical-align: top; font-size: 28px; font-weight: bold; padding: 12px;">
                                        {{ $menuAttributes['Menu eleves']['entree'] }}
                                    </td>
                                    <td style="height: 50px; vertical-align: top; font-size: 28px; font-weight: bold; padding: 12px;">
                                        <div class="dotted-line"></div>
                                    </td>
                                </tr>
                                <tr>
                                    <td style="height: 50px; vertical-align: top; font-size: 28px; font-weight: bold; padding: 12px;">
                                        <div class="dotted-line"></div>
                                    </td>
                                    <td style="height: 50px; vertical-align: top; font-size: 28px; font-weight: bold; padding: 12px;">
                                        {{ $menuAttributes['Menu eleves']['plat_principal'] }}
                                    </td>
                                    <td style="height: 50px; vertical-align: top; font-size: 28px; font-weight: bold; padding: 12px;">
                                        <div class="dotted-line"></div>
                                    </td>
                                </tr>
                                <tr>
                                    <td style="height: 50px; vertical-align: top; font-size: 28px; font-weight: bold; padding: 12px;">
                                        <div class="dotted-line"></div>
                                    </td>
                                    <td style="height: 50px; vertical-align: top; font-size: 28px; font-weight: bold; padding: 12px;">
                                        {{ $menuAttributes['Menu eleves']['accompagnement'] }}
                                    </td>
                                    <td style="height: 50px; vertical-align: top; font-size: 28px; font-weight: bold; padding: 12px;">
                                        <div class="dotted-line"></div>
                                    </td>
                                </tr>
                                <tr>
                                    <td style="height: 50px; vertical-align: top; font-size: 28px; font-weight: bold; padding: 12px;">
                                        <div class="dotted-line"></div>
                                    </td>
                                    <td style="height: 50px; vertical-align: top; font-size: 28px; font-weight: bold; padding: 12px;">
                                        {{ $menuAttributes['Menu eleves']['dessert'] }}
                                    </td>
                                    <td style="height: 50px; vertical-align: top; font-size: 28px; font-weight: bold; padding: 12px;">
                                        <div class="dotted-line"></div>
                                    </td>
                                </tr>
                            </tbody>
                        </table>
                        
                        <!-- MENU APPLICATION Table -->
                        <h4>MENU APPLICATION</h4>
                        <table>
                            <thead>
                                <tr>
                                    <th style="text-decoration: underline; width: 33.33%;">Petit Déjeuner</th>
                                    <th style="text-decoration: underline; width: 33.33%;">Lunch</th>
                                    <th style="text-decoration: underline; width: 33.33%;">Dîner</th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr>
                                    <td style="height: 50px; vertical-align: top; font-size: 28px; font-weight: bold; padding: 12px;">
                                        <div class="dotted-line"></div>
                                    </td>
                                    <td style="height: 50px; vertical-align: top; font-size: 28px; font-weight: bold; padding: 12px;">
                                        {{ $menuAttributes['Menu d\'application']['entree'] }}
                                    </td>
                                    <td style="height: 50px; vertical-align: top; font-size: 28px; font-weight: bold; padding: 12px;">
                                        <div class="dotted-line"></div>
                                    </td>
                                </tr>
                                <tr>
                                    <td style="height: 50px; vertical-align: top; font-size: 28px; font-weight: bold; padding: 12px;">
                                        <div class="dotted-line"></div>
                                    </td>
                                    <td style="height: 50px; vertical-align: top; font-size: 28px; font-weight: bold; padding: 12px;">
                                        {{ $menuAttributes['Menu d\'application']['plat_principal'] }}
                                    </td>
                                    <td style="height: 50px; vertical-align: top; font-size: 28px; font-weight: bold; padding: 12px;">
                                        <div class="dotted-line"></div>
                                    </td>
                                </tr>
                                <tr>
                                    <td style="height: 50px; vertical-align: top; font-size: 28px; font-weight: bold; padding: 12px;">
                                        <div class="dotted-line"></div>
                                    </td>
                                    <td style="height: 50px; vertical-align: top; font-size: 28px; font-weight: bold; padding: 12px;">
                                        {{ $menuAttributes['Menu d\'application']['accompagnement'] }}
                                    </td>
                                    <td style="height: 50px; vertical-align: top; font-size: 28px; font-weight: bold; padding: 12px;">
                                        <div class="dotted-line"></div>
                                    </td>
                                </tr>
                                <tr>
                                    <td style="height: 50px; vertical-align: top; font-size: 28px; font-weight: bold; padding: 12px;">
                                        <div class="dotted-line"></div>
                                    </td>
                                    <td style="height: 50px; vertical-align: top; font-size: 28px; font-weight: bold; padding: 12px;">
                                        {{ $menuAttributes['Menu d\'application']['dessert'] }}
                                    </td>
                                    <td style="height: 50px; vertical-align: top; font-size: 28px; font-weight: bold; padding: 12px;">
                                        <div class="dotted-line"></div>
                                    </td>
                                </tr>
                            </tbody>
                        </table>
                        
                        <!-- MENU SPÉCIAL Table -->
                        <h4>MENU SPÉCIAL</h4>
                        <table>
                            <thead>
                                <tr>
                                    <th style="text-decoration: underline; width: 33.33%;">Petit Déjeuner</th>
                                    <th style="text-decoration: underline; width: 33.33%;">Lunch</th>
                                    <th style="text-decoration: underline; width: 33.33%;">Dîner</th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr>
                                    <td style="height: 50px; vertical-align: top; font-size: 28px; font-weight: bold; padding: 12px;">
                                        <div class="dotted-line"></div>
                                    </td>
                                    <td style="height: 50px; vertical-align: top; font-size: 28px; font-weight: bold; padding: 12px;">
                                        {{ $menuAttributes['Menu specials']['entree'] }}
                                    </td>
                                    <td style="height: 50px; vertical-align: top; font-size: 28px; font-weight: bold; padding: 12px;">
                                        <div class="dotted-line"></div>
                                    </td>
                                </tr>
                                <tr>
                                    <td style="height: 50px; vertical-align: top; font-size: 28px; font-weight: bold; padding: 12px;">
                                        <div class="dotted-line"></div>
                                    </td>
                                    <td style="height: 50px; vertical-align: top; font-size: 28px; font-weight: bold; padding: 12px;">
                                        {{ $menuAttributes['Menu specials']['plat_principal'] }}
                                    </td>
                                    <td style="height: 50px; vertical-align: top; font-size: 28px; font-weight: bold; padding: 12px;">
                                        <div class="dotted-line"></div>
                                    </td>
                                </tr>
                                <tr>
                                    <td style="height: 50px; vertical-align: top; font-size: 28px; font-weight: bold; padding: 12px;">
                                        <div class="dotted-line"></div>
                                    </td>
                                    <td style="height: 50px; vertical-align: top; font-size: 28px; font-weight: bold; padding: 12px;">
                                        {{ $menuAttributes['Menu specials']['accompagnement'] }}
                                    </td>
                                    <td style="height: 50px; vertical-align: top; font-size: 28px; font-weight: bold; padding: 12px;">
                                        <div class="dotted-line"></div>
                                    </td>
                                </tr>
                                <tr>
                                    <td style="height: 50px; vertical-align: top; font-size: 28px; font-weight: bold; padding: 12px;">
                                        <div class="dotted-line"></div>
                                    </td>
                                    <td style="height: 50px; vertical-align: top; font-size: 28px; font-weight: bold; padding: 12px;">
                                        {{ $menuAttributes['Menu specials']['dessert'] }}
                                    </td>
                                    <td style="height: 50px; vertical-align: top; font-size: 28px; font-weight: bold; padding: 12px;">
                                        <div class="dotted-line"></div>
                                    </td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                    
                    <!-- Effectif section -->
                    @if (isset($data['sorties']) && isset($data['sorties']['grand_totals']) && $data['sorties']['grand_totals']['total_people'] > 0)
                        <div class="right-half-content">
                            <div class="effectif-section">
                                <div class="section-title">EFFECTIF</div>
                                <table>
                                    <thead>
                                        <tr>
                                            <th rowspan="2"></th>
                                            <th>Petit déjeuner</th>
                                            <th colspan="3">Lunch</th>
                                            <th>Dîner</th>
                                        </tr>
                                        <tr>
                                            <th></th>
                                            <th>Menu standard</th>
                                            <th>Menu d'application</th>
                                            <th>Menu spécial</th>
                                            <th></th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @php
                                            $menuEleves = collect($data['sorties']['consumptions'] ?? [])->firstWhere('type_menu', 'Menu eleves');
                                            $menuApplication = collect($data['sorties']['consumptions'] ?? [])->firstWhere('type_menu', "Menu d'application");
                                            $menuSpeciaux = collect($data['sorties']['consumptions'] ?? [])->firstWhere('type_menu', 'Menu specials');
                                            
                                            $categories = [
                                                'Élèves' => 'eleves',
                                                'Personnel' => 'personnel',
                                                'Invités' => 'invites',
                                                'Divers' => 'divers'
                                            ];
                                        @endphp
                                        
                                        @foreach ($categories as $displayName => $key)
                                        <tr>
                                            <td>{{ $displayName }}</td>
                                            <td></td>
                                            <td>{{ $menuEleves[$key] ?? 0 }}</td>
                                            <td>{{ $menuApplication[$key] ?? 0 }}</td>
                                            <td>{{ $menuSpeciaux[$key] ?? 0 }}</td>
                                            <td></td>
                                        </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>
                            
                            <!-- Prix de revient section -->
                            <div class="prix-section">
                                @php
                                    $calculableTotalCost = 0;
                                    $calculableTotalPeople = 0;
                                @endphp
                                
                                @foreach ($data['sorties']['consumptions'] ?? [] as $menu)
                                    @if (($menu['total_cost'] ?? 0) > 0)
                                        @php
                                            $menuType = $menu['type_menu'] ?? 'Sans Menu';
                                            $skipDisplay = in_array($menuType, ['Fournitures et matériels', 'Non Alimentaire']);
                                        @endphp
                                        
                                        @if (!$skipDisplay)
                                            @php
                                                $displayName = Str::startsWith($menuType, 'Menu') ? getMenuDisplayName($menuType) : "Menu {$menuType}";
                                            @endphp
                                            
                                            <p>Prix de Revient {{ $displayName }} : <strong>{{ number_format($menu['total_cost'], 2) }}</strong></p>
                                            
                                            @if (($menu['total_people'] ?? 0) > 0)
                                                <p>Prix Moyen {{ $displayName }} : <strong>{{ number_format($menu['total_cost'] / $menu['total_people'], 2) }}</strong></p>
                                                
                                                @php
                                                    $isCalculableMenu = in_array($menuType, [
                                                        'Menu Spécial', 'Menu specials', 
                                                        'Menu Élèves', 'Menu eleves', 
                                                        "Menu d'application", 'Menu d\'application'
                                                    ]);
                                                    
                                                    if ($isCalculableMenu) {
                                                        $calculableTotalCost += $menu['total_cost'];
                                                        $calculableTotalPeople += $menu['total_people'];
                                                    }
                                                @endphp
                                            @endif
                                        @endif
                                    @endif
                                @endforeach
                                
                                @if ($calculableTotalPeople > 0)
                                   <p>Prix Moyen Général : <strong>{{ number_format($calculableTotalCost / $calculableTotalPeople, 2) }}</strong></p>
                                @endif
                            </div>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>

    <!-- PAGE 2 - Only if there are remaining products -->
    @if(count($page2Products) > 0)
        <div class="page-wrapper">
            <!-- PAGE 2 HEADER -->
            <div class="header">
                <div class="ministry-info">
                    @if(isset($imageData) && $imageData)
                        <img src="data:image/png;base64,{{ $imageData }}" alt="Ministry Logo">
                    @else
                        <!--Royaume du Maroc<br>-->
                        <!--MINISTÈRE DU TOURISME<br>-->
                        <!--Centre de qualification Professionnelle<br>-->
                        <!--Hôtelière et Touristique<br>-->
                        <!--de Touargas-->
                        Institut Spécialisé <br>
                        de Technologie Appliquée<br>
                        Hotelière et Touristique de Touarga - Rabat<br>
                    @endif
                </div>
                
                <div class="header-title">FEUILLE DE CONSOMMATION</div>
            </div>
            
            <!-- PAGE 2 FOOTER -->
            <div class="footer">
                <div class="signature-title left-signature">L'ÉCONOME</div>
                <div class="signature-title center-signature">LE MAGASINIER</div>
                <div class="signature-title right-signature">LE DIRECTEUR</div>
            </div>
            
            <div class="content-wrapper">
                <!-- Date title -->
                <div class="date-title">JOURNALIÈRE DU {{ $date }} - Page 2</div>
                
                <!-- Small spacer -->
                <div style="height: 5px; clear: both;"></div>
                
                <div class="table-container">
                    <!-- LEFT TABLE PAGE 2 -->
                    <div class="table-half">
                        <table class="main-table">
                            <thead>
                                <tr>
                                    <th rowspan="2" style="width: 25%;">Désignation des Articles</th>
                                    <th colspan="3" style="width: 15%;">ENTRÉES</th>
                                    <th colspan="9" style="width: 60%;">SORTIES</th>
                                </tr>
                                <tr>
                                    <th>Qté</th>
                                    <th>P.U</th>
                                    <th>P.T</th>
                                    <th colspan="3">MENU STANDARD</th>
                                    <th colspan="3">MENU D'APP.</th>
                                    <th colspan="3">MENU SPÉCIAL</th>
                                </tr>
                                <tr>
                                    <th></th>
                                    <th></th>
                                    <th></th>
                                    <th></th>
                                    <th>Qté</th>
                                    <th>P.U</th>
                                    <th>P.T</th>
                                    <th>Qté</th>
                                    <th>P.U</th>
                                    <th>P.T</th>
                                    <th>Qté</th>
                                    <th>P.U</th>
                                    <th>P.T</th>
                                </tr>
                            </thead>
                            <tbody>
                                @php 
                                    $currentCategory = null;
                                    // Initialize table-specific totals for left table on page 2
                                    $page2LeftTableEntreeTotal = 0;
                                    $page2LeftTableElevesTotal = 0;
                                    $page2LeftTableApplicationTotal = 0;
                                    $page2LeftTableSpecialTotal = 0;
                                @endphp
                                @foreach ($page2LeftProducts as $item)
                                    @php
                                        $product = $item['product'];
                                        $categoryName = $item['category'];
                                        
                                        if ($currentCategory !== $categoryName) {
                                            $currentCategory = $categoryName;
                                            echo '<tr class="category-header"><td colspan="13"><strong>' . strtoupper($categoryName) . '</strong></td></tr>';
                                        }
                                        
                                        $productId = $product['product_id'];
                                        $entreeData = $entriesMap[$productId] ?? ['quantity' => 0, 'unit_price' => 0, 'total_price' => 0];
                                        $elevesData = $sortiesMenus['Menu Élèves'][$productId] ?? ['quantity' => 0, 'unit_price' => 0, 'total_price' => 0];
                                        $applicationData = $sortiesMenus['Menu d\'application'][$productId] ?? ['quantity' => 0, 'unit_price' => 0, 'total_price' => 0];
                                        $specialData = $sortiesMenus['Menu Spécial'][$productId] ?? ['quantity' => 0, 'unit_price' => 0, 'total_price' => 0];
                                        
                                        // Add to table-specific totals only if there's actual data
                                        if ($entreeData['total_price'] > 0) {
                                            $page2LeftTableEntreeTotal += $entreeData['total_price'];
                                        }
                                        if ($elevesData['total_price'] > 0) {
                                            $page2LeftTableElevesTotal += $elevesData['total_price'];
                                        }
                                        if ($applicationData['total_price'] > 0) {
                                            $page2LeftTableApplicationTotal += $applicationData['total_price'];
                                        }
                                        if ($specialData['total_price'] > 0) {
                                            $page2LeftTableSpecialTotal += $specialData['total_price'];
                                        }
                                    @endphp
                                    <tr>
                                        <td class="product-name">{{ $globalProductCounter }}- {{ $product['name'] }}</td>
                                        <td>{{ $entreeData['quantity'] > 0 ? $entreeData['quantity'] : '' }}</td>
                                        <td>{{ $entreeData['unit_price'] > 0 ? number_format($entreeData['unit_price'], 2) : '' }}</td>
                                        <td>{{ $entreeData['total_price'] > 0 ? number_format($entreeData['total_price'], 2) : '' }}</td>
                                        <td>{{ $elevesData['quantity'] > 0 ? $elevesData['quantity'] : '' }}</td>
                                        <td>{{ $elevesData['unit_price'] > 0 ? number_format($elevesData['unit_price'], 2) : '' }}</td>
                                        <td>{{ $elevesData['total_price'] > 0 ? number_format($elevesData['total_price'], 2) : '' }}</td>
                                        <td>{{ $applicationData['quantity'] > 0 ? $applicationData['quantity'] : '' }}</td>
                                        <td>{{ $applicationData['unit_price'] > 0 ? number_format($applicationData['unit_price'], 2) : '' }}</td>
                                        <td>{{ $applicationData['total_price'] > 0 ? number_format($applicationData['total_price'], 2) : '' }}</td>
                                        <td>{{ $specialData['quantity'] > 0 ? $specialData['quantity'] : '' }}</td>
                                        <td>{{ $specialData['unit_price'] > 0 ? number_format($specialData['unit_price'], 2) : '' }}</td>
                                        <td>{{ $specialData['total_price'] > 0 ? number_format($specialData['total_price'], 2) : '' }}</td>
                                    </tr>
                                    @php $globalProductCounter++; @endphp
                                @endforeach
                                
                                <!-- Show table-specific totals at bottom of page 2 left table -->
                                <tr class="table-totals">
                                    <td class="text-left"><strong>TOTAL</strong></td>
                                    <td colspan="2"></td>
                                    <td><strong>{{ $page2LeftTableEntreeTotal > 0 ? number_format($page2LeftTableEntreeTotal, 2) : '' }}</strong></td>
                                    <td colspan="2"></td>
                                    <td><strong>{{ $page2LeftTableElevesTotal > 0 ? number_format($page2LeftTableElevesTotal, 2) : '' }}</strong></td>
                                    <td colspan="2"></td>
                                    <td><strong>{{ $page2LeftTableApplicationTotal > 0 ? number_format($page2LeftTableApplicationTotal, 2) : '' }}</strong></td>
                                    <td colspan="2"></td>
                                    <td><strong>{{ $page2LeftTableSpecialTotal > 0 ? number_format($page2LeftTableSpecialTotal, 2) : '' }}</strong></td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                    
                    <!-- Spacer between tables -->
                    <div class="table-spacer"></div>
                    
                    <!-- RIGHT TABLE PAGE 2 -->
                    <div class="table-half">
                        <table class="main-table">
                            <thead>
                                <tr>
                                    <th rowspan="2" style="width: 25%;">Désignation des Articles</th>
                                    <th colspan="3" style="width: 15%;">ENTRÉES</th>
                                    <th colspan="9" style="width: 60%;">SORTIES</th>
                                </tr>
                                <tr>
                                    <th>Qté</th>
                                    <th>P.U</th>
                                    <th>P.T</th>
                                    <th colspan="3">MENU STANDARD</th>
                                    <th colspan="3">MENU D'APP.</th>
                                    <th colspan="3">MENU SPÉCIAL</th>
                                </tr>
                                <tr>
                                    <th></th>
                                    <th></th>
                                    <th></th>
                                    <th></th>
                                    <th>Qté</th>
                                    <th>P.U</th>
                                    <th>P.T</th>
                                    <th>Qté</th>
                                    <th>P.U</th>
                                    <th>P.T</th>
                                    <th>Qté</th>
                                    <th>P.U</th>
                                    <th>P.T</th>
                                </tr>
                            </thead>
                            <tbody>
                                @php 
                                    $currentCategory = null;
                                    // Initialize table-specific totals for right table on page 2
                                    $page2RightTableEntreeTotal = 0;
                                    $page2RightTableElevesTotal = 0;
                                    $page2RightTableApplicationTotal = 0;
                                    $page2RightTableSpecialTotal = 0;
                                @endphp
                                @foreach ($page2RightProducts as $item)
                                    @php
                                        $product = $item['product'];
                                        $categoryName = $item['category'];
                                        
                                        if ($currentCategory !== $categoryName) {
                                            $currentCategory = $categoryName;
                                            echo '<tr class="category-header"><td colspan="13"><strong>' . strtoupper($categoryName) . '</strong></td></tr>';
                                        }
                                        
                                        $productId = $product['product_id'];
                                        $entreeData = $entriesMap[$productId] ?? ['quantity' => 0, 'unit_price' => 0, 'total_price' => 0];
                                        $elevesData = $sortiesMenus['Menu Élèves'][$productId] ?? ['quantity' => 0, 'unit_price' => 0, 'total_price' => 0];
                                        $applicationData = $sortiesMenus['Menu d\'application'][$productId] ?? ['quantity' => 0, 'unit_price' => 0, 'total_price' => 0];
                                        $specialData = $sortiesMenus['Menu Spécial'][$productId] ?? ['quantity' => 0, 'unit_price' => 0, 'total_price' => 0];
                                        
                                        // Add to table-specific totals only if there's actual data
                                        if ($entreeData['total_price'] > 0) {
                                            $page2RightTableEntreeTotal += $entreeData['total_price'];
                                        }
                                        if ($elevesData['total_price'] > 0) {
                                            $page2RightTableElevesTotal += $elevesData['total_price'];
                                        }
                                        if ($applicationData['total_price'] > 0) {
                                            $page2RightTableApplicationTotal += $applicationData['total_price'];
                                        }
                                        if ($specialData['total_price'] > 0) {
                                            $page2RightTableSpecialTotal += $specialData['total_price'];
                                        }
                                    @endphp
                                    <tr>
                                        <td class="product-name">{{ $globalProductCounter }}- {{ $product['name'] }}</td>
                                        <td>{{ $entreeData['quantity'] > 0 ? $entreeData['quantity'] : '' }}</td>
                                        <td>{{ $entreeData['unit_price'] > 0 ? number_format($entreeData['unit_price'], 2) : '' }}</td>
                                        <td>{{ $entreeData['total_price'] > 0 ? number_format($entreeData['total_price'], 2) : '' }}</td>
                                        <td>{{ $elevesData['quantity'] > 0 ? $elevesData['quantity'] : '' }}</td>
                                        <td>{{ $elevesData['unit_price'] > 0 ? number_format($elevesData['unit_price'], 2) : '' }}</td>
                                        <td>{{ $elevesData['total_price'] > 0 ? number_format($elevesData['total_price'], 2) : '' }}</td>
                                        <td>{{ $applicationData['quantity'] > 0 ? $applicationData['quantity'] : '' }}</td>
                                        <td>{{ $applicationData['unit_price'] > 0 ? number_format($applicationData['unit_price'], 2) : '' }}</td>
                                        <td>{{ $applicationData['total_price'] > 0 ? number_format($applicationData['total_price'], 2) : '' }}</td>
                                        <td>{{ $specialData['quantity'] > 0 ? $specialData['quantity'] : '' }}</td>
                                        <td>{{ $specialData['unit_price'] > 0 ? number_format($specialData['unit_price'], 2) : '' }}</td>
                                        <td>{{ $specialData['total_price'] > 0 ? number_format($specialData['total_price'], 2) : '' }}</td>
                                    </tr>
                                    @php $globalProductCounter++; @endphp
                                @endforeach
                                
                                <!-- Show table-specific totals at bottom of page 2 right table -->
                                <tr class="table-totals">
                                    <td class="text-left"><strong>TOTAL</strong></td>
                                    <td colspan="2"></td>
                                    <td><strong>{{ $page2RightTableEntreeTotal > 0 ? number_format($page2RightTableEntreeTotal, 2) : '' }}</strong></td>
                                    <td colspan="2"></td>
                                    <td><strong>{{ $page2RightTableElevesTotal > 0 ? number_format($page2RightTableElevesTotal, 2) : '' }}</strong></td>
                                    <td colspan="2"></td>
                                    <td><strong>{{ $page2RightTableApplicationTotal > 0 ? number_format($page2RightTableApplicationTotal, 2) : '' }}</strong></td>
                                    <td colspan="2"></td>
                                    <td><strong>{{ $page2RightTableSpecialTotal > 0 ? number_format($page2RightTableSpecialTotal, 2) : '' }}</strong></td>
                                </tr>
                            </tbody>
                        </table>
                        
                        <!-- OBSERVATIONS Section - Only on Page 2 Right Side -->
                        <div class="observations-section">
                            <div class="observations-title">OBSERVATIONS</div>
                            <div class="observations-content">
                                <div class="observations-line"></div>
                                <div class="observations-line"></div>
                                <div class="observations-line"></div>
                                <div class="observations-line"></div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    @endif

    /* Print-friendly styles for menu section */
    @media print {
        .menu-section th {
            background-color: #f8f9fa !important;
            -webkit-print-color-adjust: exact;
            color-adjust: exact;
        }
    }
</body>
</html>