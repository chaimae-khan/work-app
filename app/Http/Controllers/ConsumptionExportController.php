<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\DailyConsumption;
use App\Models\Product;
use Carbon\Carbon;
use Illuminate\Support\Facades\Log;
use Barryvdh\DomPDF\Facade\Pdf;
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;

class ConsumptionExportController extends Controller
{
    /**
     * Export consumption data as PDF
     */
    public function exportPDF(Request $request)
    {
        $request->validate([
            'date' => 'required|date'
        ]);

        $date = Carbon::parse($request->date);
        
        try {
            // Process consumption data first
            $consumptionController = new ConsumptionController();
            $consumptionController->processConsumptionForDate($date);
            
            // Get all consumption data
            $responseData = $consumptionController->getAllConsumptionData($date)->original;
            
            if ($responseData['status'] != 200) {
                return back()->with('error', $responseData['message'] ?? 'Aucune donnée trouvée pour cette date');
            }
            
            // Generate PDF
            $pdf = Pdf::loadView('consumption.complete_pdf', [
                'data' => $responseData['data'],
                'date' => $date->format('d/m/Y')
            ]);
            
            // Set page size and orientation
            $pdf->setPaper('a4', 'portrait');
            
            // Generate filename
            $filename = 'consommation_' . $date->format('Ymd') . '.pdf';
            
            // Return PDF for download
            return $pdf->download($filename);
            
        } catch (\Exception $e) {
            Log::error('Error exporting PDF: ' . $e->getMessage());
            return response()->json([
                'status' => 500,
                'message' => 'Erreur lors de l\'exportation du PDF: ' . $e->getMessage()
            ], 500);
        }
    }
    
    /**
     * Export consumption data as Excel
     */
    public function exportExcel(Request $request)
    {
        $request->validate([
            'date' => 'required|date'
        ]);

        $date = Carbon::parse($request->date);
        
        try {
            // Process consumption data first
            $consumptionController = new ConsumptionController();
            $consumptionController->processConsumptionForDate($date);
            
            // Get all consumption data
            $responseData = $consumptionController->getAllConsumptionData($date)->original;
            
            if ($responseData['status'] != 200) {
                return back()->with('error', $responseData['message'] ?? 'Aucune donnée trouvée pour cette date');
            }
            
            $data = $responseData['data'];
            
            // Create a new spreadsheet
            $spreadsheet = new Spreadsheet();
            $sheet = $spreadsheet->getActiveSheet();
            
            // Set spreadsheet metadata
            $spreadsheet->getProperties()
                ->setCreator('GESTOCK TOUARGA')
                ->setLastModifiedBy('GESTOCK TOUARGA')
                ->setTitle('Consommation ' . $date->format('d/m/Y'))
                ->setSubject('Feuille de Consommation')
                ->setDescription('Feuille de Consommation pour la date du ' . $date->format('d/m/Y'));
            
            // Set the sheet name
            $sheet->setTitle('Consommation ' . $date->format('d-m-Y'));
            
            // Add header
            $sheet->setCellValue('A1', 'MINISTÈRE DU TOURISME');
            $sheet->setCellValue('A2', 'Royaume du Maroc');
            $sheet->setCellValue('A3', 'Centre de qualification Professionnelle');
            $sheet->setCellValue('A4', 'Hôtelière et Touristique de Touargas');
            $sheet->setCellValue('D5', 'FEUILLE DE CONSOMMATION');
            $sheet->setCellValue('D6', 'JOURNÉE DU: ' . $date->format('d/m/Y'));
            
            // Start row for data
            $row = 8;
            
            // Add ENTRÉES section if data exists
            if (isset($data['entrees']['consumptions']) && count($data['entrees']['consumptions']) > 0) {
                $sheet->setCellValue('A' . $row, 'ENTRÉES');
                $row += 2;
                
                // Add header row
                $sheet->setCellValue('A' . $row, 'Désignation des Articles');
                $sheet->setCellValue('B' . $row, 'Qté');
                $sheet->setCellValue('C' . $row, 'P.U');
                $sheet->setCellValue('D' . $row, 'P.T');
                $row++;
                
                // Add data rows
                foreach ($data['entrees']['consumptions'] as $achat) {
                    foreach ($achat['products'] as $product) {
                        $sheet->setCellValue('A' . $row, $product['name']);
                        $sheet->setCellValue('B' . $row, $product['quantity']);
                        $sheet->setCellValue('C' . $row, $product['unit_price']);
                        $sheet->setCellValue('D' . $row, $product['total_price']);
                        $row++;
                    }
                }
                
                // Add total row
                $sheet->setCellValue('A' . $row, 'Total:');
                $sheet->setCellValue('D' . $row, $data['entrees']['grand_totals']['total_cost'] ?? 0);
                $row += 2;
            }
            
            // Add SORTIES section if data exists
            if (isset($data['sorties']['consumptions']) && count($data['sorties']['consumptions']) > 0) {
                $sheet->setCellValue('A' . $row, 'SORTIES');
                $row += 2;
                
                // Check for Fournitures et matériels
                foreach ($data['sorties']['consumptions'] as $menu) {
                    if (isset($menu['type_menu'])) {
                        $sheet->setCellValue('A' . $row, strtoupper($menu['type_menu']));
                        $row++;
                        
                        // Add header row
                        $sheet->setCellValue('A' . $row, 'Désignation des Articles');
                        $sheet->setCellValue('B' . $row, 'Qté');
                        $sheet->setCellValue('C' . $row, 'P.U');
                        $sheet->setCellValue('D' . $row, 'P.T');
                        $row++;
                        
                        // Add product rows
                        foreach ($menu['products'] as $product) {
                            $sheet->setCellValue('A' . $row, $product['name']);
                            $sheet->setCellValue('B' . $row, $product['quantity']);
                            $sheet->setCellValue('C' . $row, $product['unit_price']);
                            $sheet->setCellValue('D' . $row, $product['total_price']);
                            $row++;
                        }
                        
                        // Add total row
                        $sheet->setCellValue('A' . $row, 'Total:');
                        $sheet->setCellValue('D' . $row, $menu['total_cost'] ?? 0);
                        $row += 2;
                    }
                }
                
                // Add EFFECTIF section
                $totalEleves = 0;
                $totalPersonnel = 0;
                $totalInvites = 0;
                $totalDivers = 0;
                
                // Calculate totals
                foreach ($data['sorties']['consumptions'] as $menu) {
                    $totalEleves += $menu['eleves'] ?? 0;
                    $totalPersonnel += $menu['personnel'] ?? 0;
                    $totalInvites += $menu['invites'] ?? 0;
                    $totalDivers += $menu['divers'] ?? 0;
                }
                
                // Only add EFFECTIF section if there are people
                if ($totalEleves > 0 || $totalPersonnel > 0 || $totalInvites > 0 || $totalDivers > 0) {
                    $sheet->setCellValue('A' . $row, 'EFFECTIF');
                    $row++;
                    
                    // Headers
                    $sheet->setCellValue('A' . $row, '');
                    $sheet->setCellValue('B' . $row, 'Petit déjeuner');
                    $sheet->setCellValue('C' . $row, 'Lunch');
                    $sheet->setCellValue('D' . $row, 'Dîner');
                    $row++;
                    
                    // Data rows
                    $sheet->setCellValue('A' . $row, 'Élèves');
                    $sheet->setCellValue('C' . $row, $totalEleves);
                    $row++;
                    
                    $sheet->setCellValue('A' . $row, 'Personnel');
                    $sheet->setCellValue('C' . $row, $totalPersonnel);
                    $row++;
                    
                    $sheet->setCellValue('A' . $row, 'Invités');
                    $sheet->setCellValue('C' . $row, $totalInvites);
                    $row++;
                    
                    $sheet->setCellValue('A' . $row, 'Divers');
                    $sheet->setCellValue('C' . $row, $totalDivers);
                    $row += 2;
                }
                
                // Add pricing information
                $sheet->setCellValue('A' . $row, 'Prix de Revient de la journée:');
                $sheet->setCellValue('D' . $row, $data['sorties']['grand_totals']['total_cost'] ?? 0);
                $row++;
                
                // Add average prices for each menu
                $totalMoyen = 0;
                $countMenus = 0;
                
                foreach ($data['sorties']['consumptions'] as $menu) {
                    if (!isset($menu['type_menu']) || $menu['type_menu'] !== 'Fournitures et matériels') {
                        if (isset($menu['total_cost']) && isset($menu['total_people']) && $menu['total_people'] > 0) {
                            $avgPrice = $menu['total_cost'] / $menu['total_people'];
                            $sheet->setCellValue('A' . $row, 'Prix Moyen ' . ($menu['type_menu'] ?? 'Menu') . ':');
                            $sheet->setCellValue('D' . $row, $avgPrice);
                            $row++;
                            
                            $totalMoyen += $avgPrice;
                            $countMenus++;
                        }
                    }
                }
                
                // Add general average price
                if ($countMenus > 0) {
                    $sheet->setCellValue('A' . $row, 'Prix Moyen Général:');
                    $sheet->setCellValue('D' . $row, $totalMoyen / $countMenus);
                    $row++;
                }
            }
            
            // Add signature lines
            $row += 2;
            $sheet->setCellValue('A' . $row, 'L\'ECONOME');
            $sheet->setCellValue('C' . $row, 'LE MAGASINIER');
            $sheet->setCellValue('E' . $row, 'LE DIRECTEUR');
            
            // Set column widths
            $sheet->getColumnDimension('A')->setWidth(40);
            $sheet->getColumnDimension('B')->setWidth(10);
            $sheet->getColumnDimension('C')->setWidth(10);
            $sheet->getColumnDimension('D')->setWidth(15);
            
            // Save to a temporary file
            $tempFile = tempnam(sys_get_temp_dir(), 'consumption_excel_');
            $writer = new Xlsx($spreadsheet);
            $writer->save($tempFile);
            
            // Return the file for download
            return response()->download(
                $tempFile,
                'consommation_' . $date->format('Ymd') . '.xlsx'
            )->deleteFileAfterSend(true);
            
        } catch (\Exception $e) {
            Log::error('Error exporting Excel: ' . $e->getMessage());
            return response()->json([
                'status' => 500,
                'message' => 'Erreur lors de l\'exportation du fichier Excel: ' . $e->getMessage()
            ], 500);
        }
    }
}