<?php
// create_achats_june_2025_one_per_day_validated.php
// Save this file in your Laravel project root directory
// Run from Tinker: include 'create_achats_june_2025_one_per_day_validated.php';

use App\Models\Achat;
use App\Models\LigneAchat;
use App\Models\User;
use App\Models\Product;
use App\Models\Fournisseur;
use App\Models\Stock;
use App\Services\InventoryService;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

// Configuration for June 2025
$targetMonth = 6; // June
$targetYear = 2025;

echo "=== CREATING VALIDATED ACHATS FOR JUNE 2025 (ONE PER DAY WITH SPECIFIC DATES) ===\n";
echo "Target: {$targetMonth}/{$targetYear}\n";
echo "Strategy: ONE achat per day, all with 'Validation' status\n\n";

// Get available users
$users = User::take(10)->get();

// Get available fournisseurs
$fournisseurs = Fournisseur::take(5)->get();

// Get ONLY the second product (same as in your Vente script)
$secondProduct = DB::table('products as p')
    ->join('stock as s', 'p.id', '=', 's.id_product')
    ->where('s.quantite', '>', 100) // Need good stock for 30 achats
    ->whereNull('p.deleted_at')
    ->select('p.id', 'p.name', 'p.price_achat', 's.quantite')
    ->orderBy('p.id') // Order by ID to get consistent results
    ->skip(1) // Skip the first product
    ->first(); // Get the second product

if (!$secondProduct) {
    echo "❌ No second product found with sufficient stock (>100 units).\n";
    echo "Please ensure you have at least two products with good stock.\n";
    return;
}

if ($users->isEmpty()) {
    echo "❌ No users found. Please create users first.\n";
    return;
}

if ($fournisseurs->isEmpty()) {
    echo "❌ No fournisseurs found. Please create fournisseurs first.\n";
    return;
}

echo "🎯 SELECTED PRODUCT (SECOND):\n";
echo "   ID: {$secondProduct->id}\n";
echo "   Name: {$secondProduct->name}\n";
echo "   Price: {$secondProduct->price_achat} MAD\n";
echo "   Stock: {$secondProduct->quantite} units\n\n";

echo "✅ Available users: " . $users->count() . "\n";
echo "✅ Available fournisseurs: " . $fournisseurs->count() . "\n\n";

// June 2025 has 30 days
$daysInJune = 30;
echo "Creating ONE validated achat per day for June 2025 with SPECIFIC DATES...\n\n";

$createdCount = 0;
$errorCount = 0;
$totalQuantityAdded = 0;

// Get inventory service instance (if needed for stock updates)
$inventoryService = app(InventoryService::class);

// Create ONE achat for each day of June 2025
for ($day = 1; $day <= $daysInJune; $day++) {
    try {
        // Create SPECIFIC date for this day in June 2025
        $achatDate = Carbon::create($targetYear, $targetMonth, $day, rand(8, 17), rand(0, 59), 0);
        
        echo "🗓️  Processing Day {$day}/06/2025 - Target Date: " . $achatDate->format('Y-m-d H:i:s') . "\n";
        
        // Check if an achat already exists for this EXACT day
        $existingAchat = Achat::whereDate('created_at', $achatDate->format('Y-m-d'))->first();
        
        if ($existingAchat) {
            echo "⚠️  Day {$day}/06/2025: Achat already exists (ID: {$existingAchat->id}) - SKIPPING\n\n";
            continue;
        }
        
        // Randomly select user and fournisseur
        $user = $users->random();
        $fournisseur = $fournisseurs->random();
        
        // Use ONLY the second product with varying quantities
        $quantity = rand(5, 20); // Random quantity between 5-20 for purchases
        $total = $quantity * $secondProduct->price_achat;
        $totalQuantityAdded += $quantity;
        
        // Begin transaction
        DB::beginTransaction();
        
        try {
            // IMPORTANT: Explicitly set timestamps to force the specific date
            $achat = new Achat();
            $achat->fill([
                'total' => $total,
                'status' => 'Validation', // All achats will be validated
                'id_Fournisseur' => $fournisseur->id,
                'id_user' => $user->id,
            ]);
            
            // FORCE the timestamps to our specific date
            $achat->created_at = $achatDate;
            $achat->updated_at = $achatDate;
            $achat->save();
            
            // Create SINGLE LigneAchat record with same date
            $ligneAchat = new LigneAchat();
            $ligneAchat->fill([
                'id_user' => $user->id,
                'idachat' => $achat->id,
                'idproduit' => $secondProduct->id,
                'qte' => $quantity,
            ]);
            
            // FORCE the timestamps for ligne_achat too
            $ligneAchat->created_at = $achatDate;
            $ligneAchat->updated_at = $achatDate;
            $ligneAchat->save();
            
            // Since status is 'Validation', update stock accordingly
            // Check if product already exists in stock
            $existingStock = Stock::where('id_product', $secondProduct->id)->first();
            
            if ($existingStock) {
                // Update existing stock
                $oldQuantity = $existingStock->quantite;
                $existingStock->quantite += $quantity;
                $existingStock->save();
                
                echo "   📦 Updated stock: {$oldQuantity} + {$quantity} = {$existingStock->quantite} units\n";
            } else {
                // Get product info for new stock record
                $productInfo = DB::table('products as p')
                    ->join('tvas as t', 't.id', '=', 'p.id_tva')
                    ->join('unite as u', 'u.id', '=', 'p.id_unite')
                    ->whereNull('p.deleted_at')
                    ->select('t.id as id_tva', 'u.id as idunite', 'p.seuil')
                    ->where('p.id', $secondProduct->id)
                    ->first();
                
                if ($productInfo) {
                    // Create new stock record
                    $newStock = Stock::create([
                        'id_product' => $secondProduct->id,
                        'id_tva' => $productInfo->id_tva,
                        'id_unite' => $productInfo->idunite,
                        'quantite' => $quantity,
                        'seuil' => $productInfo->seuil,
                    ]);
                    
                    echo "   📦 Created new stock record with {$quantity} units\n";
                }
            }
            
            // Update inventory using the service (if available)
            try {
                $inventoryService->updateInventoryForPurchase($achat);
                echo "   📊 Inventory updated successfully\n";
            } catch (\Exception $e) {
                echo "   ⚠️  Inventory service update failed: " . $e->getMessage() . "\n";
                // Continue anyway as this is not critical for the script
            }
            
            DB::commit();
            $createdCount++;
            
            $fournisseurName = $fournisseur->entreprise;
            $userName = $user->prenom . ' ' . $user->nom;
            
            echo "✅ SUCCESS - Achat #{$achat->id} created for {$achatDate->format('d/m/Y H:i')}\n";
            echo "   📋 Status: Validation (Validated)\n";
            echo "   🏢 Fournisseur: {$fournisseurName}\n";
            echo "   👤 User: {$userName}\n";
            echo "   📦 Product: {$secondProduct->name} (Qty: {$quantity})\n";
            echo "   💰 Total: " . number_format($total, 2) . " MAD\n";
            echo "   📊 Total quantity added so far: {$totalQuantityAdded} units\n";
            echo "\n";
            
        } catch (\Exception $e) {
            DB::rollBack();
            $errorCount++;
            echo "❌ Day {$day}: Failed to create achat - " . $e->getMessage() . "\n\n";
            $totalQuantityAdded -= $quantity; // Rollback quantity count
        }
        
        // Small delay to avoid overwhelming the system
        usleep(100000); // 0.1 second
        
    } catch (\Exception $e) {
        $errorCount++;
        echo "❌ Day {$day}: Error - " . $e->getMessage() . "\n\n";
    }
}

echo "\n" . str_repeat("=", 70) . "\n";
echo "🎯 JUNE 2025 VALIDATED ACHATS WITH SPECIFIC DATES COMPLETED!\n";
echo str_repeat("=", 70) . "\n";
echo "✅ Successfully created: {$createdCount} achats\n";
echo "❌ Failed to create: {$errorCount} achats\n";
echo "📊 Total quantity added to stock: {$totalQuantityAdded} units\n\n";

// Verify the dates are correctly set
echo "🔍 VERIFICATION - Checking created achat dates:\n";
$verificationAchats = Achat::whereBetween('created_at', [
    Carbon::create($targetYear, $targetMonth, 1)->startOfDay(),
    Carbon::create($targetYear, $targetMonth, 30)->endOfDay()
])->orderBy('created_at')->get();

echo "Found " . $verificationAchats->count() . " achats in June 2025:\n\n";

foreach ($verificationAchats as $index => $achat) {
    $dayOfMonth = $achat->created_at->day;
    $formattedDate = $achat->created_at->format('D, d/m/Y H:i:s');
    $fournisseur = Fournisseur::find($achat->id_Fournisseur);
    $fournisseurName = $fournisseur ? $fournisseur->entreprise : 'Unknown';
    
    echo sprintf("%2d. Achat #%-4d - Day %2d - %s - %s - %s\n", 
        $index + 1, 
        $achat->id, 
        $dayOfMonth,
        $formattedDate,
        $achat->status,
        $fournisseurName
    );
}

// Show daily distribution
echo "\n📅 DAILY DISTRIBUTION CHECK:\n";
$dailyCount = [];
foreach ($verificationAchats as $achat) {
    $day = $achat->created_at->day;
    $dailyCount[$day] = ($dailyCount[$day] ?? 0) + 1;
}

for ($day = 1; $day <= 30; $day++) {
    $count = $dailyCount[$day] ?? 0;
    $status = $count === 1 ? "✅" : ($count === 0 ? "❌" : "⚠️ ");
    echo sprintf("   Day %2d: %s %d achat(s)\n", $day, $status, $count);
}

// Show final stock status
echo "\n📦 FINAL STOCK STATUS:\n";
$finalStock = Stock::where('id_product', $secondProduct->id)->first();
if ($finalStock) {
    echo "   Product: {$secondProduct->name}\n";
    echo "   Final stock quantity: {$finalStock->quantite} units\n";
    echo "   Total added by script: {$totalQuantityAdded} units\n";
} else {
    echo "   ⚠️  No stock record found for product: {$secondProduct->name}\n";
}

echo "\n🚀 SCRIPT COMPLETED!\n";
echo "📋 Each day in June 2025 should now have its own validated achat with correct date\n";
echo "🔍 Use the verification output above to confirm dates are set correctly\n\n";

if ($createdCount === 30) {
    echo "🎉 PERFECT! All 30 days of June 2025 have validated achats with specific dates!\n";
} else {
    echo "⚠️  Only {$createdCount} out of 30 achats were created. Check for existing achats or errors.\n";
}

echo "\n💡 NEXT STEPS:\n";
echo "   1. Verify in your database that created_at dates span June 1-30, 2025\n";
echo "   2. Check that each day has exactly one validated achat\n";
echo "   3. Verify that stock has been updated correctly\n";
echo "   4. Run reports filtered by date range to test functionality\n";
echo "   5. Check that inventory movements have been recorded (if applicable)\n\n";

echo "📝 IMPORTANT NOTES:\n";
echo "   - All achats created with status 'Validation'\n";
echo "   - Stock quantities have been updated automatically\n";
echo "   - Inventory service updates attempted (may vary based on your implementation)\n";
echo "   - Each achat uses the same second product as specified\n";
echo "   - Quantities range from 5-20 units per achat\n\n";
?>