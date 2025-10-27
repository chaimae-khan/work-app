<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Product;
use App\Models\InventoryMonthlySummary;
use App\Models\InventoryYearlySummary;
use Barryvdh\DomPDF\Facade\Pdf;

class CardexController extends Controller
{
    /**
     * Display cardex view for a product
     *
     * @param Request $request
     * @return \Illuminate\Http\Response
     */
    public function show(Request $request)
    {
        // Get product ID and year from request
        $productId = $request->input('product_id');
        $year = $request->input('year', date('Y'));
        
        // Validate requested product
        if (!$productId) {
            return redirect()->route('inventory.index')
                ->with('error', 'Veuillez sélectionner un produit pour afficher le CARDEX.');
        }
        
        // Get the selected product
        $selectedProduct = Product::findOrFail($productId);
        
        // Get the unit price from yearly summary if available
        $unitPrice = $selectedProduct->price_achat;
        $yearlySummary = InventoryYearlySummary::where('product_id', $productId)
            ->where('year', $year)
            ->first();
            
        if ($yearlySummary && $yearlySummary->average_price > 0) {
            $unitPrice = $yearlySummary->average_price;
        }
        
        // Get months list for display
        $months = [
            1 => 'Janvier', 2 => 'Février', 3 => 'Mars', 4 => 'Avril',
            5 => 'Mai', 6 => 'Juin', 7 => 'Juillet', 8 => 'Août',
            9 => 'Septembre', 10 => 'Octobre', 11 => 'Novembre', 12 => 'Décembre'
        ];
        
        return view('inventory.cardex', compact(
            'selectedProduct',
            'productId',
            'year',
            'unitPrice',
            'months'
        ));
    }
    
    /**
     * Generate PDF version of cardex
     *
     * @param Request $request
     * @return \Illuminate\Http\Response
     */
    public function generatePdf(Request $request)
    {
        // Get product ID and year from request
        $productId = $request->input('product_id');
        $year = $request->input('year', date('Y'));
        
        // Validate requested product
        if (!$productId) {
            return redirect()->route('inventory.index')
                ->with('error', 'Veuillez sélectionner un produit pour générer le PDF.');
        }
        
        // Get the selected product
        $selectedProduct = Product::findOrFail($productId);
        
        // Get the unit price from yearly summary if available
        $unitPrice = $selectedProduct->price_achat;
        $yearlySummary = InventoryYearlySummary::where('product_id', $productId)
            ->where('year', $year)
            ->first();
            
        if ($yearlySummary && $yearlySummary->average_price > 0) {
            $unitPrice = $yearlySummary->average_price;
        }
        
        // Get months list for display
        $months = [
            1 => 'Janvier', 2 => 'Février', 3 => 'Mars', 4 => 'Avril',
            5 => 'Mai', 6 => 'Juin', 7 => 'Juillet', 8 => 'Août',
            9 => 'Septembre', 10 => 'Octobre', 11 => 'Novembre', 12 => 'Décembre'
        ];
        
        // Generate PDF with the same view
        $pdf = PDF::loadView('inventory.cardex', compact(
            'selectedProduct',
            'productId',
            'year',
            'unitPrice',
            'months'
        ));
        
        // Set paper to A4 portrait
        $pdf->setPaper('a4', 'portrait');
        
        // Download the PDF
        return $pdf->download("cardex_{$selectedProduct->name}_{$year}.pdf");
    }
}