<!-- stock/pdf_export.blade.php -->
<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Stock Export</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            font-size: 10px;
            margin: 20px;
        }
        h1 {
            text-align: center;
            font-size: 16px;
            margin-bottom: 20px;
            color: #333;
        }
        table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 20px;
        }
        th, td {
            padding: 6px 4px;
            text-align: center;
            border: 1px solid #ddd;
            font-size: 9px;
        }
        th {
            background-color: #f2f2f2;
            font-weight: bold;
            font-size: 8px;
        }
        .low-stock {
            color: #ff0000;
            font-weight: bold;
        }
        .badge-danger {
            color: #fff;
            background-color: #dc3545;
            padding: 2px 4px;
            border-radius: 3px;
            font-size: 8px;
        }
        .badge-success {
            color: #fff;
            background-color: #28a745;
            padding: 2px 4px;
            border-radius: 3px;
            font-size: 8px;
        }
        .text-left {
            text-align: left;
        }
        .text-right {
            text-align: right;
        }
        
        /* Column-specific styling */
        .col-name {
            max-width: 120px;
            text-align: left;
            word-wrap: break-word;
        }
        .col-code {
            max-width: 80px;
            font-family: monospace;
            font-size: 8px;
        }
        .col-price {
            text-align: right;
        }
        .col-number {
            text-align: center;
        }
        .col-date {
            font-size: 8px;
        }
        
        @page {
            margin: 15mm;
        }
    </style>
</head>
<body>
    <h1>GESTOCK TOUARGA - Rapport de Stock - Date: {{ $date }}</h1>
    
    <table>
        <thead>
            <tr>
                @foreach($columns as $index => $column)
                <th class="
                    @if(in_array($columnData[$index], ['name'])) col-name
                    @elseif(in_array($columnData[$index], ['code_article', 'code_barre'])) col-code
                    @elseif(in_array($columnData[$index], ['price_achat'])) col-price
                    @elseif(in_array($columnData[$index], ['quantite', 'seuil', 'tva_value'])) col-number
                    @elseif(in_array($columnData[$index], ['date_expiration', 'created_at'])) col-date
                    @endif
                ">{{ $column }}</th>
                @endforeach
            </tr>
        </thead>
        <tbody>
            @foreach($stocks as $stock)
                <tr @if($stock['is_low_stock']) class="low-stock" @endif>
                    @foreach($columnData as $index => $field)
                        <td class="
                            @if($field == 'name') col-name text-left
                            @elseif(in_array($field, ['code_article', 'code_barre'])) col-code
                            @elseif($field == 'price_achat') col-price
                            @elseif(in_array($field, ['quantite', 'seuil', 'tva_value'])) col-number
                            @elseif(in_array($field, ['date_expiration', 'created_at'])) col-date
                            @else text-center
                            @endif
                        ">
                            @if($field == 'status')
                                @if($stock['is_low_stock'])
                                    <span class="badge-danger">Stock Bas</span>
                                @else
                                    <span class="badge-success">Stock Normal</span>
                                @endif
                            @elseif($field == 'photo_display')
                                @if(isset($stock[$field]) && $stock[$field] == 'Oui')
                                    <span style="color: #28a745;">●</span>
                                @else
                                    <span style="color: #dc3545;">○</span>
                                @endif
                            @else
                                {{ $stock[$field] ?? '-' }}
                            @endif
                        </td>
                    @endforeach
                </tr>
            @endforeach
        </tbody>
    </table>
    
    <!-- <div style="margin-top: 30px; font-size: 10px; color: #666;">
        <p><strong>Légende :</strong></p>
        <ul style="margin: 5px 0; padding-left: 20px;">
            <li><span class="badge-danger">Stock Bas</span> : Quantité en stock ≤ Seuil défini</li>
            <li><span class="badge-success">Stock Normal</span> : Quantité en stock > Seuil défini</li>
            <li><span style="color: #28a745;">●</span> : Photo disponible</li>
            <li><span style="color: #dc3545;">○</span> : Pas de photo</li>
        </ul>
        <p style="margin-top: 15px;"><strong>Total produits :</strong> {{ count($stocks) }}</p>
        <p><strong>Produits en stock bas :</strong> {{ collect($stocks)->where('is_low_stock', true)->count() }}</p>
        <p><strong>Date du rapport :</strong> {{ $date }}</p>
    </div> -->
</body>
</html>