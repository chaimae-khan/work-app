<!DOCTYPE html>
<html>
<head>
    <title>Bon de Commande</title>
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
            padding: 20px;
            margin-bottom: 20px;
            background-color: #ffffff; 
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1); 
        }
        .left {
            width: 50%;
            text-align: center;
            padding: 10px;
            box-sizing: border-box;
        }
        .container {
            display: flex;
            width: 98%;
            margin: 20px;
            box-sizing: border-box;
        }
        #tableDetail {
            width: 100%;
            border-collapse: collapse;
            font-size: 12px;
        }
        #tableDetail th,
        #tableDetail td {
            border: 1px solid;
            padding: 8px;
            text-align: left;
            font-size: 10px;
        }
        #tableDetail th {
            background-color: #f2f2f2;
            font-weight: bold;
            font-size: 11px;
            white-space: nowrap;
        }
        .invoice-footer {
            text-transform: uppercase;
            white-space: nowrap;
            margin-top: 5px;
            bottom: 12px;
            position: absolute;
        }
        .watermark {
            position: absolute;
            top: 50%;
            left: 48%;
            transform: translate(-50%, -50%) rotate(-45deg); 
            font-size: 200px;
            opacity: 0.1;
            pointer-events: none;
            text-transform: uppercase;
        }
        .title-centered {
            text-align: center;
            font-weight: bold;
            font-size: 16px;
            margin: 15px 0;
        }
    </style>
</head>
<body>
    @php
        // Calculate how many pages we need based on 10 items per page
        $itemsPerPage = 10;
        $totalItems = count($Data_Vente);
        $totalPages = ceil($totalItems / $itemsPerPage);
    @endphp

    @for ($page = 0; $page < $totalPages; $page++)
        <div class="invoice-container">
            <img src="data:image/png;base64,{{ $imageData }}" alt="" width="750px">
           
            <div class="container">
                <div style="display: flex;justify-content: center;text-align: center;width: 100%;">
                    <h3 class="title-centered">Bon de Commande</h3>
                </div>
            </div>
            <div>
                <div class="container">
                    <table id="tableDetail">
                        <tr>
                            <th>   
                                Nature de Commande :
                            </th>
                            <td>
                                {{ $bonVente->type_commande ?? 'Alimentaire' }}
                            </td>
                            <th>
                                Bénéficiaire :
                            </th>
                            <td>
                            {{ $Formateur->prenom . ' ' . $Formateur->nom ?? 'Non spécifié' }}
                            </td>
                        </tr>
                        <tr>
                            <th>   
                                Entité:
                            </th>
                            <td>
                                <!-- {{ $Formateur->fonction ?? 'Non spécifié' }} -->
                            </td>
                            <th>
                                Date : 
                            </th>
                            <td>
                                {{ \Carbon\Carbon::parse($Data_Vente[0]->created_at)->format('d/m/Y') }}
                            </td>
                        </tr>
                    </table>

                    <table id="tableDetail" style="margin-top: 30px">
                        <thead>
                            <tr>
                                <td style="text-align: center"><strong>Désignations</strong></td>
                                <td style="text-align: center"><strong>Quantité Commandée</strong></td>
                                <td style="text-align: center"><strong>Quantité Livrée</strong></td>
                                <td style="text-align: center"><strong>Prix Unitaire</strong></td>
                                <td style="text-align: center"><strong>Observations</strong></td>
                            </tr>
                        </thead>
                        <tbody>
                            @php
                                // Calculate start and end indices for this page
                                $startIndex = $page * $itemsPerPage;
                                $endIndex = min(($page + 1) * $itemsPerPage, $totalItems);
                            @endphp

                            @for ($i = $startIndex; $i < $endIndex; $i++)
                                <tr>
                                    <td style="text-align: center">{{ $Data_Vente[$i]->name }}</td>
                                    <td style="text-align: center">{{ $Data_Vente[$i]->qte }}</td>
                                    <td style="text-align: center"></td>
                                    <td style="text-align: center">{{ $Data_Vente[$i]->price_achat }} DH</td>
                                    <td style="text-align: center"></td>
                                </tr>
                            @endfor
                            
                            <!-- Add empty rows to fill up to 10 rows per page -->
                            @for ($i = $endIndex - $startIndex; $i < $itemsPerPage; $i++)
                                <tr>
                                    <td style="text-align: center">&nbsp;</td>
                                    <td style="text-align: center">&nbsp;</td>
                                    <td style="text-align: center">&nbsp;</td>
                                    <td style="text-align: center">&nbsp;</td>
                                    <td style="text-align: center">&nbsp;</td>
                                </tr>
                            @endfor
                        </tbody>
                    </table>

                    <!-- Only show signature table on the last page -->
                    @if ($page == $totalPages - 1)
                        <table id="tableDetail" style="margin-top: 30px; width: 100%; border-collapse: collapse;">
                            <thead>
                                <tr>
                                    <td style="text-align: center; border: 1px solid black; padding: 10px;"><strong>A la Commande (Date + Signature)</strong></td>
                                    <td style="text-align: center; border: 1px solid black; padding: 10px;"><strong>Validation</strong></td>
                                    <td style="text-align: center; border: 1px solid black; padding: 10px;"><strong>A la Livraison</strong></td>
                                    <td style="text-align: center; border: 1px solid black; padding: 10px;"><strong>A la Réception (Date + Signature)</strong></td>
                                </tr>
                            </thead>
                            <tbody>
                                <tr>
                                    @php
                                        // Initialize variables for each status
                                        $creation = null;
                                        $validation = null;
                                        $livraison = null;
                                        $reception = null;
                                        
                                        // Group signatures by status
                                        foreach ($getHistorique_sig as $item) {
                                            switch($item->status) {
                                                case 'Création':
                                                    $creation = $item;
                                                    break;
                                                case 'Validation':
                                                    $validation = $item;
                                                    break;
                                                case 'Livraison':
                                                    $livraison = $item;
                                                    break;
                                                case 'Réception':
                                                    $reception = $item;
                                                    break;
                                            }
                                        }
                                    @endphp
                                    
                        <td style="text-align: center; border: 1px solid black; height: 100px; vertical-align: top; padding: 10px;">
    @if($creation)
        <img src="data:image/png;base64,{{ $creation->signature }}" alt="" style="max-height: 50px; max-width: 100px;">
        <br>{{ $creation->name }}
        <br>{{ \Carbon\Carbon::parse($creation->created_at)->format('Y-m-d H:i:s') }}
    @endif
</td>
<td style="text-align: center; border: 1px solid black; height: 100px; vertical-align: top; padding: 10px;">
   @if($reception)
        <img src="data:image/png;base64,{{ $reception->signature }}" alt="" style="max-height: 50px; max-width: 100px;">
        <br>{{ $reception->name }}
        <br>{{ \Carbon\Carbon::parse($reception->created_at)->format('Y-m-d H:i:s') }}
    @endif
</td>
<td style="text-align: center; border: 1px solid black; height: 100px; vertical-align: top; padding: 10px;">
    @if($livraison)
        <img src="data:image/png;base64,{{ $livraison->signature }}" alt="" style="max-height: 50px; max-width: 100px;">
        <br>{{ $livraison->name }}
        <br>{{ \Carbon\Carbon::parse($livraison->created_at)->format('Y-m-d H:i:s') }}
    @endif
</td>
<td style="text-align: center; border: 1px solid black; height: 100px; vertical-align: top; padding: 10px;">
  
    @if($validation)
        <img src="data:image/png;base64,{{ $validation->signature}}" alt="" style="max-height: 50px; max-width: 100px;">
        <br>{{ $validation->name }}
        <br>{{ \Carbon\Carbon::parse($validation->created_at)->format('Y-m-d H:i:s') }}
    @endif
</td>
                                </tr>
                            </tbody>
                        </table>
                    @endif
                </div>
            </div>
            
            <footer>
                <div class="invoice-footer">
                    <img src="data:image/png;base64,{{ $imageData_bottom }}" alt="" width="750px">
                </div>
            </footer>
        </div>

        @if ($page < $totalPages - 1)
            <div class="page-break"></div>
        @endif
    @endfor
</body>
</html>