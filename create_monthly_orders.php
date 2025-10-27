<?php
// create_commands_june_2025_one_per_day_fixed.php
// Save this file in your Laravel project root directory
// Run from Tinker: include 'create_commands_june_2025_one_per_day_fixed.php';

use App\Models\Vente;
use App\Models\LigneVente;
use App\Models\User;
use App\Models\Product;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

// Configuration for June 2025
$targetMonth = 6; // June
$targetYear = 2025;
$commandTypes = ['Alimentaire', 'Non Alimentaire', 'Fournitures et matériels'];
$menuTypes = ['Menu eleves', 'Menu specials', 'Menu d\'application'];
$statuses = ['Création', 'Validation', 'Refus', 'Livraison', 'Réception'];

echo "=== CREATING COMMANDS FOR JUNE 2025 (ONE PER DAY WITH SPECIFIC DATES) ===\n";
echo "Target: {$targetMonth}/{$targetYear}\n";
echo "Strategy: ONE command per day, each with its own specific date\n\n";

// Get available users (formateurs and regular users)
$formateurs = User::whereHas('roles')->take(5)->get();
$users = User::take(10)->get();

// Get ONLY the second product with good stock
$secondProduct = DB::table('products as p')
    ->join('stock as s', 'p.id', '=', 's.id_product')
    ->where('s.quantite', '>', 100) // Need good stock for 30 commands
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

if ($formateurs->isEmpty() || $users->isEmpty()) {
    echo "❌ No users found. Please create users first.\n";
    return;
}

echo "🎯 SELECTED PRODUCT (SECOND):\n";
echo "   ID: {$secondProduct->id}\n";
echo "   Name: {$secondProduct->name}\n";
echo "   Price: {$secondProduct->price_achat} MAD\n";
echo "   Stock: {$secondProduct->quantite} units\n\n";

echo "✅ Available formateurs: " . $formateurs->count() . "\n";
echo "✅ Available users: " . $users->count() . "\n\n";

// June 2025 has 30 days
$daysInJune = 30;
echo "Creating ONE command per day for June 2025 with SPECIFIC DATES...\n\n";

$createdCount = 0;
$errorCount = 0;
$totalQuantityUsed = 0;

// Create ONE command for each day of June 2025
for ($day = 1; $day <= $daysInJune; $day++) {
    try {
        // Create SPECIFIC date for this day in June 2025
        $commandDate = Carbon::create($targetYear, $targetMonth, $day, rand(8, 17), rand(0, 59), 0);
        
        echo "🗓️  Processing Day {$day}/06/2025 - Target Date: " . $commandDate->format('Y-m-d H:i:s') . "\n";
        
        // Check if a command already exists for this EXACT day
        $existingCommand = Vente::whereDate('created_at', $commandDate->format('Y-m-d'))->first();
        
        if ($existingCommand) {
            echo "⚠️  Day {$day}/06/2025: Command already exists (ID: {$existingCommand->id}) - SKIPPING\n\n";
            continue;
        }
        
        // Randomly select formateur and user
        $formateur = $formateurs->random();
        $user = $users->random();
        
        // Randomly select command type
        $commandType = $commandTypes[array_rand($commandTypes)];
        
        // Set menu-related fields only for Alimentaire commands
        $menuData = [];
        if ($commandType === 'Alimentaire') {
            $menuData = [
                'type_menu' => $menuTypes[array_rand($menuTypes)],
                'eleves' => rand(5, 50),
                'personnel' => rand(2, 10),
                'invites' => rand(0, 5),
                'divers' => rand(0, 3),
                'entree' => 'Entrée spéciale du ' . $day . ' juin',
                'plat_principal' => 'Plat principal du ' . $day . ' juin 2025',
                'accompagnement' => 'Accompagnement de saison',
                'dessert' => 'Dessert du jour ' . $day,
            ];
        }
        
        // Use ONLY the second product with varying quantities
        $quantity = rand(1, 8); // Random quantity between 1-8
        $total = $quantity * $secondProduct->price_achat;
        $totalQuantityUsed += $quantity;
        
        // Randomly assign status
        $statusWeights = [
            'Création' => 35,      // 35% new orders
            'Validation' => 25,    // 25% validated
            'Livraison' => 20,     // 20% in delivery
            'Réception' => 15,     // 15% received
            'Refus' => 5           // 5% refused
        ];
        
        $randomValue = rand(1, 100);
        $currentWeight = 0;
        $selectedStatus = 'Création';
        
        foreach ($statusWeights as $status => $weight) {
            $currentWeight += $weight;
            if ($randomValue <= $currentWeight) {
                $selectedStatus = $status;
                break;
            }
        }
        
        // Begin transaction
        DB::beginTransaction();
        
        try {
            // IMPORTANT: Explicitly set timestamps to force the specific date
            $vente = new Vente();
            $vente->fill(array_merge([
                'total' => $total,
                'status' => $selectedStatus,
                'type_commande' => $commandType,
                'id_formateur' => $formateur->id,
                'id_user' => $user->id,
            ], $menuData));
            
            // FORCE the timestamps to our specific date
            $vente->created_at = $commandDate;
            $vente->updated_at = $commandDate;
            $vente->save();
            
            // Create SINGLE LigneVente record with same date
            $ligneVente = new LigneVente();
            $ligneVente->fill([
                'id_user' => $user->id,
                'idvente' => $vente->id,
                'idproduit' => $secondProduct->id,
                'qte' => $quantity,
            ]);
            
            // FORCE the timestamps for ligne_vente too
            $ligneVente->created_at = $commandDate;
            $ligneVente->updated_at = $commandDate;
            $ligneVente->save();
            
            DB::commit();
            $createdCount++;
            
            $formateurName = $formateur->prenom . ' ' . $formateur->nom;
            $userName = $user->prenom . ' ' . $user->nom;
            $remainingStock = $secondProduct->quantite - $totalQuantityUsed;
            $stockStatus = $remainingStock >= 0 ? "✅" : "⚠️";
            
            echo "{$stockStatus} SUCCESS - Command #{$vente->id} created for {$commandDate->format('d/m/Y H:i')}\n";
            echo "   📋 Type: {$commandType} | Status: {$selectedStatus}\n";
            echo "   👨‍🏫 Formateur: {$formateurName}\n";
            echo "   👤 User: {$userName}\n";
            echo "   📦 Product: {$secondProduct->name} (Qty: {$quantity})\n";
            echo "   💰 Total: " . number_format($total, 2) . " MAD\n";
            echo "   📊 Stock remaining: " . max(0, $remainingStock) . " units\n";
            
            if ($commandType === 'Alimentaire') {
                echo "   🍽️  Menu: {$menuData['type_menu']}\n";
                echo "   👥 Covers: Élèves({$menuData['eleves']}) Personnel({$menuData['personnel']}) Invités({$menuData['invites']})\n";
            }
            echo "\n";
            
        } catch (\Exception $e) {
            DB::rollBack();
            $errorCount++;
            echo "❌ Day {$day}: Failed to create command - " . $e->getMessage() . "\n\n";
            $totalQuantityUsed -= $quantity; // Rollback quantity count
        }
        
        // Small delay to avoid overwhelming the system
        usleep(100000); // 0.1 second
        
    } catch (\Exception $e) {
        $errorCount++;
        echo "❌ Day {$day}: Error - " . $e->getMessage() . "\n\n";
    }
}

echo "\n" . str_repeat("=", 70) . "\n";
echo "🎯 JUNE 2025 COMMANDS WITH SPECIFIC DATES COMPLETED!\n";
echo str_repeat("=", 70) . "\n";
echo "✅ Successfully created: {$createdCount} commands\n";
echo "❌ Failed to create: {$errorCount} commands\n";
echo "📊 Total quantity used: {$totalQuantityUsed} units\n\n";

// Verify the dates are correctly set
echo "🔍 VERIFICATION - Checking created command dates:\n";
$verificationCommands = Vente::whereBetween('created_at', [
    Carbon::create($targetYear, $targetMonth, 1)->startOfDay(),
    Carbon::create($targetYear, $targetMonth, 30)->endOfDay()
])->orderBy('created_at')->get();

echo "Found " . $verificationCommands->count() . " commands in June 2025:\n\n";

foreach ($verificationCommands as $index => $command) {
    $dayOfMonth = $command->created_at->day;
    $formattedDate = $command->created_at->format('D, d/m/Y H:i:s');
    echo sprintf("%2d. Command #%-4d - Day %2d - %s - %s\n", 
        $index + 1, 
        $command->id, 
        $dayOfMonth,
        $formattedDate,
        $command->status
    );
}

// Show daily distribution
echo "\n📅 DAILY DISTRIBUTION CHECK:\n";
$dailyCount = [];
foreach ($verificationCommands as $command) {
    $day = $command->created_at->day;
    $dailyCount[$day] = ($dailyCount[$day] ?? 0) + 1;
}

for ($day = 1; $day <= 30; $day++) {
    $count = $dailyCount[$day] ?? 0;
    $status = $count === 1 ? "✅" : ($count === 0 ? "❌" : "⚠️ ");
    echo sprintf("   Day %2d: %s %d command(s)\n", $day, $status, $count);
}

echo "\n🚀 SCRIPT COMPLETED!\n";
echo "📋 Each day in June 2025 should now have its own command with correct date\n";
echo "🔍 Use the verification output above to confirm dates are set correctly\n\n";

if ($createdCount === 30) {
    echo "🎉 PERFECT! All 30 days of June 2025 have commands with specific dates!\n";
} else {
    echo "⚠️  Only {$createdCount} out of 30 commands were created. Check for existing commands or errors.\n";
}

echo "\n💡 NEXT STEPS:\n";
echo "   1. Verify in your database that created_at dates span June 1-30, 2025\n";
echo "   2. Check that each day has exactly one command\n";
echo "   3. Run reports filtered by date range to test functionality\n\n";
?>