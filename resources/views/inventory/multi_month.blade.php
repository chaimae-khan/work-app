<!DOCTYPE html>
<html>
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>
    <title>Inventaire Multi-mois - {{ $product_name }}</title>
    <style>
        body {
            font-family: DejaVu Sans, sans-serif;
            font-size: 10px;
        }
        h2 {
            text-align: center;
            margin-bottom: 20px;
        }
        table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 20px;
        }
        th, td {
            border: 1px solid #000;
            padding: 4px;
            text-align: center;
        }
        th {
            background-color: #f2f2f2;
        }
        .day-col {
            font-weight: bold;
        }
        .entree-value {
            color: #198754;
        }
        .sortie-value {
            color: #dc3545;
        }
        .reste-value {
            font-weight: bold;
        }
        .totals-row {
            background-color: #f2f2f2;
            font-weight: bold;
        }
        .month-header {
            text-transform: uppercase;
        }
        .footer {
            position: fixed;
            bottom: 0;
            width: 100%;
            text-align: center;
            font-size: 8px;
        }
    </style>
</head>
<body>
    <h2>Inventaire Multi-mois - {{ $product_name }} ({{ $year }})</h2>
    
    <table>
        <thead>
            <tr>
                <th rowspan="2">Dates</th>
                @foreach ($months as $monthNum => $monthName)
                    <th colspan="3" class="month-header">{{ $monthName }}</th>
                @endforeach
            </tr>
            <tr>
                @foreach ($months as $monthNum => $monthName)
                    <th>Entrées</th>
                    <th>Sorties</th>
                    <th>Reste en magasin</th>
                @endforeach
            </tr>
        </thead>
        <tbody>
            @for ($day = 1; $day <= 31; $day++)
                <tr>
                    <td class="day-col">{{ $day }}</td>
                    @foreach ($months as $monthNum => $monthName)
                        @php
                            $dayData = $data[$monthNum]['days'][$day] ?? ['entree' => 0, 'sortie' => 0, 'reste' => 0];
                            
                            // Check if day exists in this month
                            $daysInMonth = cal_days_in_month(CAL_GREGORIAN, $monthNum, $year);
                            $validDay = $day <= $daysInMonth;
                        @endphp
                        
                        <td>{{ $validDay && $dayData['entree'] > 0 ? number_format($dayData['entree'], 2) : '' }}</td>
                        <td>{{ $validDay && $dayData['sortie'] > 0 ? number_format($dayData['sortie'], 2) : '' }}</td>
                        <td>{{ $validDay ? number_format($dayData['reste'], 2) : '' }}</td>
                    @endforeach
                </tr>
            @endfor
        </tbody>
        <tfoot>
            <tr class="totals-row">
                <th>TOTAUX</th>
                @foreach ($months as $monthNum => $monthName)
                    @php
                        $summary = $summaries[$monthNum] ?? null;
                        $totalEntree = $summary ? number_format($summary->total_entrees, 2) : '0.00';
                        $totalSortie = $summary ? number_format($summary->total_sorties, 2) : '0.00';
                        $finalReste = $summary ? number_format($summary->end_stock, 2) : '0.00';
                    @endphp
                    <th>{{ $totalEntree }}</th>
                    <th>{{ $totalSortie }}</th>
                    <th>{{ $finalReste }}</th>
                @endforeach
            </tr>
        </tfoot>
    </table>
    
    <div class="footer">
        Document généré le {{ date('d/m/Y à H:i') }}
    </div>
</body>
</html>