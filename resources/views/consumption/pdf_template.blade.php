@php
    $totalTTC = 0;
    $totalTVA = 0;
    $productsList = [];
    
    // Combine all products from all consumptions
    if (isset($data['consumptions']) && is_array($data['consumptions']) && !empty($data['consumptions'])) {
        foreach ($data['consumptions'] as $consumption) {
            if (isset($consumption['products']) && is_array($consumption['products'])) {
                foreach ($consumption['products'] as $product) {
                    $existingIndex = -1;
                    foreach ($productsList as $index => $existingProduct) {
                        if ($existingProduct['name'] == $product['name']) {
                            $existingIndex = $index;
                            break;
                        }
                    }
                    
                    if ($existingIndex >= 0) {
                        $productsList[$existingIndex]['quantity'] += $product['quantity'];
                        $productsList[$existingIndex]['total_price'] += $product['total_price'];
                        if (isset($product['tva_amount'])) {
                            $productsList[$existingIndex]['tva_amount'] = ($productsList[$existingIndex]['tva_amount'] ?? 0) + $product['tva_amount'];
                        }
                    } else {
                        $productsList[] = $product;
                    }
                }
            }
        }
    }
    
    // Calculate totals
    foreach ($productsList as $product) {
        $prixTTC = $product['total_price'] ?? 0;
        $tvaRate = $product['tva_rate'] ?? 0;
        $tvaAmount = (!isset($product['tva_amount']) || $product['tva_amount'] == 0 || $tvaRate == 0) 
            ? $prixTTC 
            : $product['tva_amount'];
        
        $totalTVA += $tvaAmount;
        $totalTTC += $prixTTC;
    }
    
    // Sort products by name
    if (!empty($productsList)) {
        usort($productsList, function($a, $b) {
            return ($a['name'] ?? '') <=> ($b['name'] ?? '');
        });
    }
    
    // Calculate pagination
    $itemsPerPage = 12;
    $totalItems = count($productsList);
    $totalPages = max(1, ceil($totalItems / $itemsPerPage));
    
    // Get other data
    $type_commande = isset($data['type_commande']) ? $data['type_commande'] : '';
    $displayMenuType = '';
    $menuType = '';
    $totalEleves = 0;
    $totalPersonnel = 0;
    $totalInvites = 0;
    $totalDivers = 0;
    $grandTotal = 0;
    
    // Get menu attributes
    $menuAttributes = [];
    
    if (isset($data['consumptions']) && is_array($data['consumptions']) && !empty($data['consumptions'])) {
        foreach ($data['consumptions'] as $consumption) {
            if (isset($consumption['type_menu']) && !empty($consumption['type_menu'])) {
                $displayMenuType = $consumption['type_menu'];
                $menuType = $consumption['type_menu'];
            }
            
            if (isset($consumption['entree']) && !empty($consumption['entree'])) {
                $menuAttributes['entree'] = $consumption['entree'];
            }
            if (isset($consumption['plat_principal']) && !empty($consumption['plat_principal'])) {
                $menuAttributes['plat_principal'] = $consumption['plat_principal'];
            }
            if (isset($consumption['accompagnement']) && !empty($consumption['accompagnement'])) {
                $menuAttributes['accompagnement'] = $consumption['accompagnement'];
            }
            if (isset($consumption['dessert']) && !empty($consumption['dessert'])) {
                $menuAttributes['dessert'] = $consumption['dessert'];
            }
            
            if (isset($consumption['eleves'])) {
                $totalEleves += $consumption['eleves'];
            }
            if (isset($consumption['personnel'])) {
                $totalPersonnel += $consumption['personnel'];
            }
            if (isset($consumption['invites'])) {
                $totalInvites += $consumption['invites'];
            }
            if (isset($consumption['divers'])) {
                $totalDivers += $consumption['divers'];
            }
        }
        $grandTotal = $totalEleves + $totalPersonnel + $totalInvites + $totalDivers;
    }
    
    // Use values from controller if provided, otherwise calculate
    $totalCost = isset($data['grand_totals']['total_cost']) ? $data['grand_totals']['total_cost'] : $totalTTC;
    
    // Check if averages were passed from controller
    if (!isset($averagePrice)) {
        $averagePrice = 0;
        if (isset($data['consumptions'][0])) {
            $firstConsumption = $data['consumptions'][0];
            if (isset($firstConsumption['total_people']) && $firstConsumption['total_people'] > 0) {
                $averagePrice = floatval($firstConsumption['total_cost']) / intval($firstConsumption['total_people']);
            }
        }
    }
    
    if (!isset($generalAveragePrice)) {
        $generalAveragePrice = 0;
        $sumAllMenusCost = 0;
        $sumAllMenusPeople = 0;
        
        if (isset($data['consumptions']) && is_array($data['consumptions'])) {
            foreach ($data['consumptions'] as $menu) {
                $sumAllMenusCost += floatval($menu['total_cost'] ?? 0);
                $sumAllMenusPeople += intval($menu['total_people'] ?? 0);
            }
        }
        
        if ($sumAllMenusPeople > 0) {
            $generalAveragePrice = $sumAllMenusCost / $sumAllMenusPeople;
        }
    }
    
    if (!isset($menuCount)) {
        $menuCount = isset($data['consumptions']) && is_array($data['consumptions']) ? count($data['consumptions']) : 0;
    }
    
    if (!isset($sumAllMenusPeople)) {
        $sumAllMenusPeople = 0;
        if (isset($data['consumptions']) && is_array($data['consumptions'])) {
            foreach ($data['consumptions'] as $menu) {
                $sumAllMenusPeople += intval($menu['total_people'] ?? 0);
            }
        }
    }
@endphp
<!DOCTYPE html>
<html>
<head>
    <title>Feuille de Consommation</title>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <style>
        * {
            font-family: DejaVu Sans !important;
        }
       
        @page {
            size: a4;
            margin: 0;
            padding: 0;
        }
        
        .invoice-box table {
            direction: ltr;
            width: 100%;
            text-align: right;
            border: 1px solid;
            font-family: 'DejaVu Sans', 'Roboto', 'Montserrat', 'Open Sans', sans-serif;
        }
        
        .row, .column {
            display: block;
            page-break-before: avoid;
            page-break-after: avoid;
        }
        
        .page-break {
            page-break-after: always;
        }
        
        .invoice-container {
            height: 1060px;
            position: relative;
            border: 1px solid;
            padding: 15px;
            margin-bottom: 15px;
            background-color: #ffffff; 
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1); 
        }
        
        .container {
            width: 98%;
            margin: 15px;
            box-sizing: border-box;
        }
        
        .header-image {
            width: 650px;
            height: auto;
            max-height: 80px;
            object-fit: contain;
            display: block;
            margin: 0 auto;
        }
        
        .header-container {
            text-align: center;
            width: 100%;
        }
        
        #tableDetail {
            width: 100%;
            border-collapse: collapse;
            font-size: 8px;
        }
        
        #tableDetail th,
        #tableDetail td {
            border: 1px solid;
            padding: 3px;
            text-align: left;
            font-size: 7px;
        }
        
        #tableDetail th {
            background-color: #f2f2f2;
            font-weight: bold;
            font-size: 8px;
            white-space: nowrap;
        }
        
        .invoice-footer {
            text-transform: uppercase;
            white-space: nowrap;
            margin-top: 2px;
            bottom: 5px;
            position: absolute;
            width: 100%;
            text-align: center;
        }
        
        .footer-image {
            width: 650px;
            height: auto;
            max-height: 60px;
            object-fit: contain;
            display: block;
            margin: 0 auto;
        }
        
        .title-centered {
            text-align: center;
            font-weight: bold;
            font-size: 14px;
            margin: 10px 0;
        }
        
        .menu-title {
            text-align: center;
            font-weight: normal;
            font-size: 12px;
            margin: 8px 0;
        }
        
        .journee-heading {
            font-weight: bold;
            font-size: 12px;
            margin: 15px 0 8px 0;
        }
        
        .menu-attributes-list {
            margin: 8px 0;
            padding-left: 0;
            list-style: none;
        }
        
        .menu-attributes-list li {
            margin: 3px 0;
            font-size: 12px;
            font-weight: normal;
        }
        
        .menu-content {
            display: table;
            width: 100%;
            margin: 10px 0;
        }
        
        .menu-items-container {
            display: table-cell;
            width: 50%;
            vertical-align: top;
            padding-right: 10px;
        }
        
        .effectifs-container {
            display: table-cell;
            width: 50%;
            vertical-align: top;
            padding-left: 10px;
        }
        
        .effectifs-title {
            font-weight: bold;
            font-size: 12px;
            margin: 0 0 5px 0;
        }
        
        .effectifs-table {
            width: 100%;
            font-size: 11px;
        }
        
        .effectifs-table td {
            padding: 2px 5px;
        }
        
        .effectifs-table td:first-child {
            text-align: left;
        }
        
        .effectifs-table td:last-child {
            text-align: left;
            font-weight: normal;
        }
        
        .clearfix::after {
            content: "";
            display: table;
            clear: both;
        }
        
        .price-summary {
            margin-top: 15px;
            font-size: 12px;
        }
        
        .price-summary p {
            margin: 5px 0;
        }
    </style>
</head>
<body>
    @for ($page = 0; $page < $totalPages; $page++)
        <div class="invoice-container">
            <div class="header-container">
                <img src="data:image/png;base64,{{ $imageData ?? '' }}" alt="" class="header-image">
            </div>
            
            <div class="container">
                <div style="display: flex;justify-content: center;text-align: center;width: 100%;">
                    <h3 class="title-centered">Feuille de Consommation (Reporting)</h3>
                </div>
                
                @if($type_commande == 'Alimentaire')
                    <div style="display: flex;justify-content: center;text-align: center;width: 100%;">
                        <p class="menu-title">{{ $displayMenuType }}</p>
                    </div>
                @endif
            </div>
            
            <div class="container">
                @if($page == 0)
                    <p class="journee-heading">➤ Journée du : {{ $date ?? '' }}</p>
                    
                    @if (!empty($menuAttributes) || ($type_commande == 'Alimentaire' && isset($data['consumptions']) && is_array($data['consumptions']) && !empty($data['consumptions'])))
                        <div class="menu-content clearfix">
                            <div class="menu-items-container">
                                @if (!empty($menuAttributes))
                                    <ul class="menu-attributes-list">
                                        @if (isset($menuAttributes['entree']))
                                            <li>• : {{ $menuAttributes['entree'] }}</li>
                                        @endif
                                        @if (isset($menuAttributes['plat_principal']))
                                            <li>• : {{ $menuAttributes['plat_principal'] }}</li>
                                        @endif
                                        @if (isset($menuAttributes['accompagnement']))
                                            <li>• : {{ $menuAttributes['accompagnement'] }}</li>
                                        @endif
                                        @if (isset($menuAttributes['dessert']))
                                            <li>• : {{ $menuAttributes['dessert'] }}</li>
                                        @endif
                                    </ul>
                                @endif
                            </div>
                            
                            @if ($type_commande == 'Alimentaire' && isset($data['consumptions']) && is_array($data['consumptions']) && !empty($data['consumptions']))
                                <div class="effectifs-container">
                                    <p class="effectifs-title">Effectifs :</p>
                                    <table class="effectifs-table">
                                        <tr>
                                            <td>Élèves Stagiaires</td>
                                            <td>: {{ $totalEleves }}</td>
                                        </tr>
                                        <tr>
                                            <td>Personnel en Service</td>
                                            <td>: {{ $totalPersonnel }}</td>
                                        </tr>
                                        <tr>
                                            <td>Invités</td>
                                            <td>: {{ $totalInvites }}</td>
                                        </tr>
                                        <tr>
                                            <td>Divers</td>
                                            <td>: {{ $totalDivers }}</td>
                                        </tr>
                                        <tr>
                                            <td><strong>Total</strong></td>
                                            <td><strong>: {{ $grandTotal }}</strong></td>
                                        </tr>
                                    </table>
                                </div>
                            @endif
                        </div>
                    @endif
                @endif
                
                <table id="tableDetail" style="margin-top: 15px;">
                    <thead>
                        <tr>
                            <th>Désignations des Prestations</th>
                            <th>Unité de Mesure</th>
                            <th>Qté</th>
                            <th>Prix Unitaire HT</th>
                            <th>Taux TVA</th>
                            <th>TOTAL TVA</th>
                            <th>Prix Total TTC</th>
                        </tr>
                    </thead>
                    <tbody>
                        @php
                            $startIndex = $page * $itemsPerPage;
                            $endIndex = min(($page + 1) * $itemsPerPage, $totalItems);
                        @endphp

                        @if($totalItems > 0)
                            @for ($i = $startIndex; $i < $endIndex; $i++)
                                @php
                                    $product = $productsList[$i];
                                    $tvaRate = $product['tva_rate'] ?? 0;
                                    $tvaLabel = $tvaRate > 0 ? $tvaRate . '%' : 'Pas de TVA';
                                    $prixHT = $product['unit_price'] ?? 0;
                                    $prixTTC = $product['total_price'] ?? 0;
                                    $tvaAmount = (!isset($product['tva_amount']) || $product['tva_amount'] == 0 || $tvaRate == 0) 
                                        ? $prixTTC 
                                        : $product['tva_amount'];
                                    $unite = $product['unite_mesure'] ?? 'kg';
                                @endphp
                                
                                <tr>
                                    <td>{{ $product['name'] ?? '' }}</td>
                                    <td>{{ $unite }}</td>
                                    <td>{{ sprintf("%02d", $product['quantity'] ?? 0) }}</td>
                                    <td>{{ number_format($prixHT, 2) }}</td>
                                    <td>{{ $tvaLabel }}</td>
                                    <td>{{ number_format($tvaAmount, 2) }}</td>
                                    <td>{{ number_format($prixTTC, 2) }}</td>
                                </tr>
                            @endfor
                            
                            @for ($i = $endIndex - $startIndex; $i < $itemsPerPage; $i++)
                                <tr>
                                    <td>&nbsp;</td>
                                    <td>&nbsp;</td>
                                    <td>&nbsp;</td>
                                    <td>&nbsp;</td>
                                    <td>&nbsp;</td>
                                    <td>&nbsp;</td>
                                    <td>&nbsp;</td>
                                </tr>
                            @endfor
                        @else
                            <tr>
                                <td colspan="7" style="text-align: center;">Aucun produit trouvé</td>
                            </tr>
                            
                            @for ($i = 1; $i < $itemsPerPage; $i++)
                                <tr>
                                    <td>&nbsp;</td>
                                    <td>&nbsp;</td>
                                    <td>&nbsp;</td>
                                    <td>&nbsp;</td>
                                    <td>&nbsp;</td>
                                    <td>&nbsp;</td>
                                    <td>&nbsp;</td>
                                </tr>
                            @endfor
                        @endif
                        
                        @if ($page == $totalPages - 1)
                            <tr>
                                <td colspan="6" style="text-align: right;"><strong>TOTAL TTC</strong></td>
                                <td><strong>{{ number_format($totalTTC, 2) }}</strong></td>
                            </tr>
                        @endif
                    </tbody>
                </table>
                
                <!-- Price Summary (only on last page) -->
                
                   @if ($page == $totalPages - 1)
    <div class="price-summary" style="margin-top: 15px;">
        <p>➤ Prix de Revient de La Journée : {{ number_format($totalCost, 2) }}</p>
        
        @if ($type_commande == 'Alimentaire' && $menuType)
            <p>➤ Prix Moyen {{ $menuType }} : {{ number_format($averagePrice, 2) }}</p>
        @endif
        
        {{-- ALWAYS show Prix Moyen Général - calculated from ALL menus on this date --}}
        <p>➤ Prix Moyen Général : {{ number_format($generalAveragePrice, 2) }}</p>
    </div>
                @endif
            </div>
            
            <footer>
                <div class="invoice-footer">
                    <img src="data:image/png;base64,{{ $imageData_bottom ?? '' }}" alt="" class="footer-image">
                </div>
            </footer>
        </div>

        @if ($page < $totalPages - 1)
            <div class="page-break"></div>
        @endif
    @endfor
</body>
</html>