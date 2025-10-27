<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title></title>
    
    <style>
        @page {
            size: A4 portrait;
            margin: 1cm;
        }
        body {
            font-family: Arial, sans-serif;
            margin: 0;
            padding: 0;
            font-size: 10px;
        }
        /* Hide browser's default headers and footers */
        @media print {
            @page {
                margin: 1cm;
            }
            body {
                margin: 0;
            }
            /* This hides the header and footer added by the browser */
            html {
                margin: 0;
                padding: 0;
            }
        }
        /* Top border line */
        .top-border {
            width: 100%;
            height: 1px;
            background-color: black;
            margin-bottom: 10px;
        }
        /* Bottom border line */
        .bottom-border {
            width: 100%;
            height: 1px;
            background-color: black;
            margin-top: 10px;
        }
        .header {
            display: flex;
            justify-content: space-between;
            margin-bottom: 15px;
        }
        .left-header {
            text-align: left;
            float: left;
            width: 33%;
            font-size: 11px;
        }
        .center-header {
            text-align: center;
            float: left;
            width: 33%;
            font-size: 14px;
            font-weight: bold;
        }
        .right-header {
            text-align: right;
            float: right;
            width: 33%;
            font-size: 11px;
        }
        .product-box {
            border: 1px solid #000;
            padding: 5px;
            margin-top: 5px;
            width: 250px;
            height: 25px;
            display: inline-block;
            text-align: center;
            margin-left: 10px;
        }
        .cardex-table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 10px;
            table-layout: fixed;
        }
        .cardex-table th, .cardex-table td {
            border: 1px solid #000;
            padding: 1px;
            text-align: center;
            height: 14px;
            font-size: 8.5px;
            overflow: hidden;
        }
        .cardex-table th {
            background-color: #f8f9fa;
            font-weight: bold;
            text-transform: uppercase;
            font-size: 5px; 
        }
        
        /* Style for the totals row */
        .totals-row {
            background-color: #f1f1f1;
        }
        .totals-row th {
            font-weight: bold;
        }
        /* Make the TOTAUX value cells bold and slightly larger */
        .totals-row th:not(:first-child) {
            font-weight: bold;
        }
        .annual-balance {
            width: 100%;
            margin-top: 20px;
        }
        .annual-balance-title {
            text-align: center;
            font-weight: bold;
            margin-bottom: 5px;
            font-size: 12px;
        }
        .annual-table {
            width: 100%;
            border-collapse: collapse;
        }
        .annual-table th, .annual-table td {
            border: 1px solid #000;
            padding: 3px;
            text-align: center;
            font-size: 9px;
        }
        .page-break {
            page-break-before: always;
        }
        /* Clear float */
        .clearfix::after {
            content: "";
            clear: both;
            display: table;
        }
        .first-page-container {
            width: 100%;
        }
        .table-container {
            width: 100%;
            display: table;
        }
        .table-cell-months {
            width: 65%;
            display: table-cell;
            vertical-align: top;
        }
        .table-cell-balance {
            width: 35%;
            display: table-cell;
            vertical-align: top;
            padding-left: 10px;
        }
        .cardex-table-small {
            width: 100%;
            margin-top: 10px;
        }
        /* Make dates column narrower */
        .cardex-table td:first-child,
        .cardex-table th:first-child {
            width: 20px;
        }
    </style>
</head>
<body>
    <!-- Page 1: January-July -->
    <div class="page">
        <!-- Top border line -->
        <div class="top-border"></div>
        
        <!-- Header section matching the image exactly -->
        <div class="clearfix">
        <div class="left-header">
    <img src="data:image/png;base64,{{ $imageData }}" alt="" style="max-width: 100%; height: auto;">
</div>
            
            <div class="center-header">
                <div>CARDEX :</div>
                <div style="margin-top: 20px;">ARTICLES :</div>
            </div>
            
            <div class="right-header">
                <div>Prix Unitaire</div>
                <div style="margin-top: 5px; font-weight: bold;">{{ number_format($averagePrice, 2) }} DH</div>
            </div>
        </div>
        
        <!-- Product box under ARTICLES -->
        <div style="text-align: center; margin-top: -20px;">
            <div class="product-box">
                {{ $product->name }}
            </div>
        </div>
        
        <!-- Bottom border line -->
        <div class="bottom-border"></div>
        
        <div class="first-page-container">
            <table class="cardex-table">
                <thead>
                    <tr>
                        <th rowspan="2">Dates</th>
                        <th colspan="3">JANVIER</th>
                        <th colspan="3">FEVRIER</th>
                        <th colspan="3">MARS</th>
                        <th colspan="3">AVRIL</th>
                        <th colspan="3">MAI</th>
                        <th colspan="3">JUIN</th>
                        <th colspan="3">JUILLET</th>
                    </tr>
                    <tr>
                        <th>Entrées</th>
                        <th>Sorties</th>
                        <th>Reste en magasin</th>
                        <th>Entrées</th>
                        <th>Sorties</th>
                        <th>Reste en magasin</th>
                        <th>Entrées</th>
                        <th>Sorties</th>
                        <th>Reste en magasin</th>
                        <th>Entrées</th>
                        <th>Sorties</th>
                        <th>Reste en magasin</th>
                        <th>Entrées</th>
                        <th>Sorties</th>
                        <th>Reste en magasin</th>
                        <th>Entrées</th>
                        <th>Sorties</th>
                        <th>Reste en magasin</th>
                        <th>Entrées</th>
                        <th>Sorties</th>
                        <th>Reste en magasin</th>
                    </tr>
                </thead>
                <tbody>
                    @for ($day = 1; $day <= 31; $day++)
                    <tr>
                        <td>{{ $day }}</td>
                        @for ($month = 1; $month <= 7; $month++)
                            @php
                                $dayData = isset($monthsData[$month]['days'][$day]) ? $monthsData[$month]['days'][$day] : null;
                                $hasEntree = $dayData && floatval($dayData['entree']) > 0;
                                $hasSortie = $dayData && floatval($dayData['sortie']) > 0;
                                $hasActivity = $hasEntree || $hasSortie;
                                
                                // Check if this day exists in this month
                                $daysInMonth = cal_days_in_month(CAL_GREGORIAN, $month, $year);
                                $dayExists = $day <= $daysInMonth;
                            @endphp
                            
                            @if ($dayExists)
                                <td>{{ $hasEntree ? number_format($dayData['entree'], 2) : '' }}</td>
                                <td>{{ $hasSortie ? number_format($dayData['sortie'], 2) : '' }}</td>
                                <td>{{ $hasActivity ? number_format($dayData['reste'], 2) : '' }}</td>
                            @else
                                <td></td>
                                <td></td>
                                <td></td>
                            @endif
                        @endfor
                    </tr>
                    @endfor
                </tbody>
                <tfoot>
                    <tr class="totals-row">
                        <th>TOTAUX</th>
                        @for ($month = 1; $month <= 7; $month++)
                            @php
                                $monthTotal = isset($monthsData[$month]['month_total']) ? $monthsData[$month]['month_total'] : null;
                                $totalEntrees = $monthTotal ? floatval($monthTotal['total_entrees']) : 0;
                                $totalSorties = $monthTotal ? floatval($monthTotal['total_sorties']) : 0;
                                $endStock = $monthTotal ? floatval($monthTotal['end_stock']) : 0;
                                
                                // Only show totals if there's any activity in the month (entrées or sorties)
                                $hasActivity = $totalEntrees > 0 || $totalSorties > 0;
                            @endphp
                            <th style="font-size: 10px;">{{ $hasActivity && $totalEntrees > 0 ? number_format($totalEntrees, 2) : '' }}</th>
                            <th style="font-size: 10px;">{{ $hasActivity && $totalSorties > 0 ? number_format($totalSorties, 2) : '' }}</th>
                            <th style="font-size: 10px;">{{ $hasActivity ? number_format($endStock, 2) : '' }}</th>
                        @endfor
                    </tr>
                </tfoot>
            </table>
        </div>
    </div>
    
    <!-- Page 2: August-December with Annual Balance -->
    <div class="page-break"></div>
    <div class="page">
        <!-- Top border line -->
        <div class="top-border"></div>
        
        <!-- Header section matching the image exactly -->
        <div class="clearfix">
        <div class="left-header">
    <img src="data:image/png;base64,{{ $imageData }}" alt="" style="max-width: 100%; height: auto;">
</div>
            
            <div class="center-header">
                <div>CARDEX :</div>
                <div style="margin-top: 20px;">ARTICLES :</div>
            </div>
            
            <div class="right-header">
                <div>Prix Unitaire</div>
                <div style="margin-top: 5px; font-weight: bold;">{{ number_format($averagePrice, 2) }} DH</div>
            </div>
        </div>
        
        <!-- Product box under ARTICLES -->
        <div style="text-align: center; margin-top: -20px;">
            <div class="product-box">
                {{ $product->name }}
            </div>
        </div>
        
        <!-- Bottom border line -->
        <div class="bottom-border"></div>
        
        <div style="display: table; width: 100%;">
            <div style="display: table-cell; width: 65%; vertical-align: top;">
                <table class="cardex-table">
                    <thead>
                        <tr>
                            <th rowspan="2">Dates</th>
                            <th colspan="3">AOUT</th>
                            <th colspan="3">SEPTEMBRE</th>
                            <th colspan="3">OCTOBRE</th>
                            <th colspan="3">NOVEMBRE</th>
                            <th colspan="3">DECEMBRE</th>
                        </tr>
                        <tr>
                            <th>Entrées</th>
                            <th>Sorties</th>
                            <th>Reste en magasin</th>
                            <th>Entrées</th>
                            <th>Sorties</th>
                            <th>Reste en magasin</th>
                            <th>Entrées</th>
                            <th>Sorties</th>
                            <th>Reste en magasin</th>
                            <th>Entrées</th>
                            <th>Sorties</th>
                            <th>Reste en magasin</th>
                            <th>Entrées</th>
                            <th>Sorties</th>
                            <th>Reste en magasin</th>
                        </tr>
                    </thead>
                    <tbody>
                        @for ($day = 1; $day <= 31; $day++)
                        <tr>
                            <td>{{ $day }}</td>
                            @for ($month = 8; $month <= 12; $month++)
                                @php
                                    $dayData = isset($monthsData[$month]['days'][$day]) ? $monthsData[$month]['days'][$day] : null;
                                    $hasEntree = $dayData && floatval($dayData['entree']) > 0;
                                    $hasSortie = $dayData && floatval($dayData['sortie']) > 0;
                                    $hasActivity = $hasEntree || $hasSortie;
                                    
                                    // Check if this day exists in this month
                                    $daysInMonth = cal_days_in_month(CAL_GREGORIAN, $month, $year);
                                    $dayExists = $day <= $daysInMonth;
                                @endphp
                                
                                @if ($dayExists)
                                    <td>{{ $hasEntree ? number_format($dayData['entree'], 2) : '' }}</td>
                                    <td>{{ $hasSortie ? number_format($dayData['sortie'], 2) : '' }}</td>
                                    <td>{{ $hasActivity ? number_format($dayData['reste'], 2) : '' }}</td>
                                @else
                                    <td></td>
                                    <td></td>
                                    <td></td>
                                @endif
                            @endfor
                        </tr>
                        @endfor
                    </tbody>
                    <tfoot>
                        <tr class="totals-row">
                            <th>TOTAUX</th>
                            @for ($month = 8; $month <= 12; $month++)
                                @php
                                    $monthTotal = isset($monthsData[$month]['month_total']) ? $monthsData[$month]['month_total'] : null;
                                    $totalEntrees = $monthTotal ? floatval($monthTotal['total_entrees']) : 0;
                                    $totalSorties = $monthTotal ? floatval($monthTotal['total_sorties']) : 0;
                                    $endStock = $monthTotal ? floatval($monthTotal['end_stock']) : 0;
                                    
                                    // Only show totals if there's any activity in the month (entrées or sorties)
                                    $hasActivity = $totalEntrees > 0 || $totalSorties > 0;
                                @endphp
                                <th style="font-size: 10px;">{{ $hasActivity && $totalEntrees > 0 ? number_format($totalEntrees, 2) : '' }}</th>
                                <th style="font-size: 10px;">{{ $hasActivity && $totalSorties > 0 ? number_format($totalSorties, 2) : '' }}</th>
                                <th style="font-size: 10px;">{{ $hasActivity ? number_format($endStock, 2) : '' }}</th>
                            @endfor
                        </tr>
                    </tfoot>
                </table>
            </div>
            <div style="display: table-cell; width: 35%; vertical-align: top; padding-left: 10px;">
                <div style="font-weight: bold; font-size: 12px; text-align: center; margin-bottom: 5px;">BALANCE ANNUELLE</div>
                <table style="width: 100%; border-collapse: collapse;">
                    <thead>
                        <tr>
                            <th style="border: 1px solid #000; text-align: center; padding: 3px; font-size: 9px;">Mois</th>
                            <th style="border: 1px solid #000; text-align: center; padding: 3px; font-size: 9px;">Entrées</th>
                            <th style="border: 1px solid #000; text-align: center; padding: 3px; font-size: 9px;">Sorties</th>
                        </tr>
                    </thead>
                    <tbody>
                        @php
                            $annualTotalEntrees = 0;
                            $annualTotalSorties = 0;
                        @endphp
                        
                        @foreach ($months as $monthNum => $monthName)
                            @php
                                $monthData = isset($monthlySummaries[$monthNum]) ? $monthlySummaries[$monthNum] : null;
                                $entrees = $monthData ? floatval($monthData->total_entrees) : 0;
                                $sorties = $monthData ? floatval($monthData->total_sorties) : 0;
                                
                                $annualTotalEntrees += $entrees;
                                $annualTotalSorties += $sorties;
                            @endphp
                            <tr>
                                <td style="border: 1px solid #000; text-align: center; padding: 3px; font-size: 9px;">{{ $monthName }}</td>
                                <td style="border: 1px solid #000; text-align: center; padding: 3px; font-size: 9px;">{{ $entrees > 0 ? number_format($entrees, 2) : '0.00' }}</td>
                                <td style="border: 1px solid #000; text-align: center; padding: 3px; font-size: 9px;">{{ $sorties > 0 ? number_format($sorties, 2) : '0.00' }}</td>
                            </tr>
                        @endforeach
                    </tbody>
                    <tfoot>
                        <tr>
                            <td style="border: 1px solid #000; text-align: center; padding: 3px; font-size: 9px;">Totaux de l'année</td>
                            <td style="border: 1px solid #000; text-align: center; padding: 3px; font-size: 9px;">{{ number_format($annualTotalEntrees, 2) }}</td>
                            <td style="border: 1px solid #000; text-align: center; padding: 3px; font-size: 9px;">{{ number_format($annualTotalSorties, 2) }}</td>
                        </tr>
                    </tfoot>
                </table>
            </div>
        </div>
    </div>
    
    <script>
        // Automatically print when the page loads
        window.onload = function() {
            // Attempt to hide the browser's header and footer
            document.title = " "; // Blank title to avoid showing filename
            
            setTimeout(function() {
                window.print();
            }, 500);
        };
    </script>
</body>
</html>