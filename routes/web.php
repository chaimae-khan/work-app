<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Auth;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\RoleController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\TvaController;
use App\Http\Controllers\CategoriesController;
use App\Http\Controllers\SubCategoryController;
use App\Http\Controllers\LocalController;
use App\Http\Controllers\RayonController;
use App\Http\Controllers\UniteController;
use App\Http\Controllers\FournisseurController;
use App\Http\Controllers\AchatController;
use App\Http\Controllers\ClientController;
use App\Http\Controllers\VenteController;
use App\Http\Controllers\AuditController;
use App\Http\Controllers\CompteController;
use App\Http\Controllers\StockController;
use App\Http\Controllers\AdminController;
use App\Http\Controllers\NotificationController;
use App\Http\Controllers\InventoryController;
use App\Http\Controllers\TransferStockController;
use App\Http\Controllers\RouterStockController;
use App\Http\Controllers\ConsumptionController;
use App\Http\Controllers\FormateurStockController;

Auth::routes();

Route::get('/', function () {
    return redirect()->route('login');
});

Route::middleware(['auth'])->group(function () {
  
    Route::get('/home', [HomeController::class, 'index'])->name('home');

    Route::resources([
        'roles' => RoleController::class,
        'users' => UserController::class,
        'products' => ProductController::class,
    ]);
    Route::post('importUsers', [UserController::class, 'import'])->name('importUsers');
    // Notification routes
    Route::get('/notifications', [NotificationController::class, 'index'])->name('notifications.index');
    Route::post('/notifications/{id}/mark-as-read', [NotificationController::class, 'markAsRead'])->name('notifications.markAsRead');
    Route::post('/notifications/mark-all-as-read', [NotificationController::class, 'markAllAsRead'])->name('notifications.markAllAsRead');
    
    // Admin routes for approval/rejection
    Route::middleware(['role:Administrateur'])->prefix('admin')->name('admin.')->group(function () {
        Route::post('/achat/{id}/approve', [AdminController::class, 'approveAchat'])->name('achat.approve');
        Route::post('/achat/{id}/reject', [AdminController::class, 'rejectAchat'])->name('achat.reject');
        Route::post('/vente/{id}/approve', [AdminController::class, 'approveVente'])->name('vente.approve');
        Route::post('/vente/{id}/reject', [AdminController::class, 'rejectVente'])->name('vente.reject');
    });

    // TVA Routes
    Route::get('tva', [TvaController::class, 'index']);
    Route::post('addTva', [TvaController::class, 'store']);
    Route::get('tva/{id}/edit', [TvaController::class, 'edit']);
    Route::post('updateTva', [TvaController::class, 'update']);
    Route::post('DeleteTva', [TvaController::class, 'destroy']);
    Route::post('/importTva', [TvaController::class, 'import'])->name('importTva');
    
    // Category Routes
    Route::get('categories', [CategoriesController::class, 'index']);
    Route::post('addCategory', [CategoriesController::class, 'store']);
    Route::get('editCategory/{id}', [CategoriesController::class, 'edit']);
    Route::post('updateCategory', [CategoriesController::class, 'update']);
    Route::post('DeleteCategory', [CategoriesController::class, 'destroy']);
    Route::post('importCategory', [CategoriesController::class, 'import']);
    Route::get('GetCategorieByClass' ,[CategoriesController::class,'GetCategorieByClass']);
    
    // sub Category Routes
    Route::get('subcategory', [SubCategoryController::class, 'index']);
    Route::post('addSubCategory', [SubCategoryController::class, 'store']);
    Route::post('updateSubCategory', [SubCategoryController::class, 'update']);
    Route::post('DeleteSubCategory', [SubCategoryController::class, 'destroy']);
    Route::get('editSubCategory/{id}', [SubCategoryController::class, 'edit']);
    Route::post('importSubCategory', [SubCategoryController::class, 'import']);
    //consomation
  // Existing routes
Route::get('/consumption', [ConsumptionController::class, 'index'])->name('consumption.index');
Route::get('/getConsumptionData', [ConsumptionController::class, 'getConsumptionData']);
Route::post('/syncAllConsumption', [ConsumptionController::class, 'syncAllConsumption'])->name('syncAllConsumption');
Route::get('/consumption/all', [ConsumptionController::class, 'allConsumption'])->name('consumption.all');
Route::get('getAllConsumptionData', [ConsumptionController::class, 'getAllConsumptionData']);
Route::get('/exportAllConsumptionPDF', [ConsumptionController::class, 'exportAllConsumptionPDF'])->name('exportAllConsumptionPDF');

Route::get('/consumption/category-costs', function () {
    return view('consumption.category-costs');
})->name('consumption.category.costs');

// API routes
Route::get('/consumption/get-category-costs', [ConsumptionController::class, 'getCategoryCostsData'])->name('get.category.costs');
Route::get('/consumption/export-category-costs-pdf', [ConsumptionController::class, 'exportCategoryCostsPDF'])->name('export.category.costs.pdf');
  
// Route::get('/export-consumption-excel', [ConsumptionController::class, 'exportExcel'])->name('exportConsumptionExcel');
Route::get('/exportPDF', [ConsumptionController::class, 'exportPDF'])->name('exportPDF');

// Monthly category costs routes
Route::get('/consumption/monthly-category-costs', function () {
    return view('consumption.monthly-category-costs');
})->name('consumption.monthly.category.costs');

Route::get('/consumption/get-monthly-category-costs', [ConsumptionController::class, 'getMonthlyCategoryCostsData'])->name('get.monthly.category.costs');

Route::get('/consumption/export-monthly-category-costs-pdf', [ConsumptionController::class, 'exportMonthlyCategoryCostsPDF'])->name('export.monthly.category.costs.pdf');

// New monthly breakdown routes
Route::get('/consumption/monthly-breakdown', [ConsumptionController::class, 'monthlyBreakdownView'])->name('consumption.monthly.breakdown');
Route::get('/consumption/get-monthly-breakdown', [ConsumptionController::class, 'getMonthlyBreakdownData'])->name('get.monthly.breakdown');
Route::get('/consumption/export-monthly-breakdown-pdf', [ConsumptionController::class, 'exportMonthlyBreakdownPDF'])->name('export.monthly.breakdown.pdf');
    
    // Local Routes
    Route::get('local', [LocalController::class, 'index']);
    Route::post('addLocal', [LocalController::class, 'store']);
    Route::post('updateLocal', [LocalController::class, 'update']);
    Route::post('DeleteLocal', [LocalController::class, 'destroy']);
    Route::get('editLocal/{id}', [LocalController::class, 'edit']);
    Route::post('importLocal', [LocalController::class, 'import'])->name('local.import');
    //stock formateur
    Route::get('formateur-stock', [FormateurStockController::class, 'index'])->name('formateur-stock.index');
    Route::get('formateur-stock/alert-count', [FormateurStockController::class, 'getAlertCount'])->name('formateur-stock.alert-count');
    Route::get('stock/export-excel', [StockController::class, 'exportExcel'])->name('stock.export-excel');
    Route::get('stock/export-pdf', [StockController::class, 'exportPdf'])->name('stock.export-pdf');
    
    // Rayon Routes
    Route::get('rayon',          [RayonController::class, 'index']);
    Route::post('addRayon',      [RayonController::class, 'store']);
    Route::post('updateRayon',   [RayonController::class, 'update']);
    Route::post('DeleteRayon',   [RayonController::class, 'destroy']);
    Route::get('editRayon/{id}', [RayonController::class, 'edit']);
    Route::post('importRayon',   [RayonController::class, 'import'])->name('rayon.import'); 
    
    // Product Routes
    Route::get('products', [ProductController::class, 'index']);
    Route::post('addProduct', [ProductController::class, 'store']);
    Route::get('editProduct/{id}', [ProductController::class, 'edit']);
    Route::post('updateProduct', [ProductController::class, 'update']);
    Route::post('deleteProduct', [ProductController::class, 'destroy']);
    Route::post('/importProduct', [ProductController::class, 'import'])->name('importProduct');
    

    // Vente routes
    Route::get('/Command', [VenteController::class, 'index'])->name('vente.index');
    Route::post('/PostInTmpVente', [VenteController::class, 'PostInTmpVente']);
    Route::get('/GetTmpVenteByClient', [VenteController::class, 'GetTmpVenteByClient']);
    Route::post('/StoreVente', [VenteController::class, 'store']);
    Route::post('/UpdateQteTmpVente', [VenteController::class, 'UpdateQteTmpVente']);
    Route::post('/DeleteRowsTmpVente', [VenteController::class, 'DeleteRowsTmpVente']);
    // Route::get('/GetTotalTmpByClientAndUser', [VenteController::class, 'GetTotalTmpByClientAndUser']);
    Route::get('/GetTmpVenteByFormateur', [VenteController::class, 'GetTmpVenteByFormateur']);
    Route::get('ShowBonVente/{id}', [VenteController::class, 'ShowBonVente'])->name('ShowBonVente');
    Route::get('FactureVente/{id}', [VenteController::class, 'FactureVente']);
    Route::get('EditVente/{id}', [VenteController::class, 'edit']);
    Route::post('UpdateVente', [VenteController::class, 'update']);
    Route::post('DeleteVente', [VenteController::class, 'deleteVente']);
    Route::post('ChangeStatusVente', [VenteController::class, 'ChangeStatusVente']);
    Route::get('/GetTotalTmpByFormateurAndUser', [VenteController::class, 'GetTotalTmpByFormateurAndUser']);

    //profile 
    Route::get('/mon-compte', [CompteController::class, 'index'])->name('compte.index');
    Route::post('/updateProfile', [CompteController::class, 'update'])->name('compte.update');
    Route::post('/updatePassword', [CompteController::class, 'updatePassword'])->name('compte.updatePassword');
    Route::post('/verifyPassword', [CompteController::class, 'verifyPassword'])->name('compte.verifyPassword');

    // Fournisseur routes
    Route::get('/fournisseur', [FournisseurController::class, 'index'])->name('fournisseur.index');
    Route::post('/addFournisseur', [FournisseurController::class, 'store']);
    Route::get('/editFournisseur/{id}', [FournisseurController::class, 'edit']);
    Route::post('/updateFournisseur', [FournisseurController::class, 'update']);
    Route::post('/DeleteFournisseur', [FournisseurController::class, 'destroy']);
    Route::post('/importFournisseur', [FournisseurController::class, 'import']);
    
    // Audit routes
    Route::get('/audit', [AuditController::class, 'index']);
    Route::get('/audit/details/{id}', [AuditController::class, 'details']);
    
    // stock
    Route::get('/stock', [StockController::class, 'index']);
    Route::get('/stock/alert-count', [StockController::class, 'getAlertCount']);

    // Dependent dropdown routes
    Route::get('getSubcategories/{id}', [ProductController::class, 'getSubcategories']);
    Route::get('getRayons/{id}', [ProductController::class, 'getRayons']);

    // home
    Route::get('/api/dashboard/chart-data', [HomeController::class, 'getChartData']);
    Route::get('/api/dashboard/status-data', [HomeController::class, 'getStatusData']);

    // Unite routes
    Route::get('unite', [UniteController::class, 'index']);
    Route::post('addUnite', [UniteController::class, 'store']);
    Route::get('editUnite/{id}', [UniteController::class, 'edit']);
    Route::post('updateUnite', [UniteController::class, 'update']);
    Route::post('deleteUnite', [UniteController::class, 'destroy']);
    Route::post('/importUnite', [UniteController::class, 'import'])->name('importUnite');

    //  Achat
    Route::get('Achat', [AchatController::class, 'index']); 
    Route::get('getProduct', [AchatController::class, 'getProduct']); 
    Route::post('PostInTmpAchat', [AchatController::class, 'PostInTmpAchat']); 
    Route::post('StoreAchat', [AchatController::class, 'Store']); 
    Route::post('UpdateQteTmp', [AchatController::class, 'UpdateQteTmp']); 
    Route::post('DeleteRowsTmpAchat', [AchatController::class, 'DeleteRowsTmpAchat']); 
    Route::get('GetTmpAchatByFournisseur', [AchatController::class, 'GetTmpAchatByFournisseur']); 
    Route::get('GetTotalTmpByForunisseurAndUser', [AchatController::class, 'GetTotalTmpByForunisseurAndUser']); 
    Route::get('ShowBonReception/{id}', [AchatController::class, 'ShowBonReception'])->name('ShowBonReception');
    Route::get('Invoice/{id}', [AchatController::class, 'Invoice']);
    Route::post('DeleteAchat', [AchatController::class, 'DeleteAchat']);
    Route::get('EditAchat/{id}', [AchatController::class, 'edit']);
    Route::post('UpdateAchat', [AchatController::class, 'update']);
    Route::post('ChangeStatusAchat', [AchatController::class, 'ChangeStatusAchat']);

    // Client routes
    Route::get('client', [ClientController::class, 'index']);
    Route::post('addClient', [ClientController::class, 'store']);
    Route::get('editClient/{id}', [ClientController::class, 'edit']);
    Route::post('updateClient', [ClientController::class, 'update']);
    Route::post('DeleteClient', [ClientController::class, 'destroy']);
    Route::get('client/fonctions', [ClientController::class, 'getFonctions'])->name('client.getFonctions');

    Route::get('/productlist', function () {
        return view('template.productlist');
    });
// Routes d'inventaire
 // Inventory routes
    Route::get('inventory', [InventoryController::class, 'index']);
    Route::get('getProductInventory', [InventoryController::class, 'getInventoryData']);
    Route::get('getMonthlyReport', [InventoryController::class, 'getMonthlyReport']);
    Route::get('getYearlyReport', [InventoryController::class, 'getYearlyReport']);
    Route::get('generateInventoryPdf', [InventoryController::class, 'exportInventoryPDF']);
    Route::get('generateMonthlyPdf', [InventoryController::class, 'exportMonthlyPDF']);
    Route::get('generateYearlyPdf', [InventoryController::class, 'exportYearlyPDF']);
    Route::post('/inventory/process-return', [InventoryController::class, 'processReturn']);
    Route::get('/exportCardexExcel', [InventoryController::class, 'exportCardexExcel'])->name('exportCardexExcel');

    

    Route::post('adduser', [UserController::class, 'store']);
    Route::post('updateUser', [UserController::class, 'update']);
    Route::post('DeleteUser', [UserController::class, 'destroy']);
    Route::get('users/{id}', [UserController::class, 'getUser'])->name('users.getUser');
    Route::post('updateRole', [RoleController::class, 'update']);
    Route::post('DeleteRole', [RoleController::class, 'destroy']);
    
   

// Multi-month inventory view
    Route::get('/inventory/multi-month', [InventoryController::class, 'multiMonthView'])->name('inventory.multi_month');
    Route::get('/generateMultiMonthPdf', [InventoryController::class, 'exportMultiMonthPDF']);
    Route::get('/getProductAveragePrice', [InventoryController::class, 'getProductAveragePrice']);
    Route::get('/cardex', [InventoryController::class, 'cardex'])->name('cardex');

// Route transfer stock
Route::get('Transfer', [TransferStockController::class, 'index']);
Route::get('getFormateurNotSelected', [TransferStockController::class, 'getFormateurNotSelected']);
Route::get('GetLigneCommandeByCommand', [TransferStockController::class, 'GetLigneCommandeByCommand']);
Route::post('StoreProductStockTr', [TransferStockController::class, 'StoreProductStockTr']);
Route::get('GetTmpStockTransferByTwoFormateur', [TransferStockController::class, 'GetTmpStockTransferByTwoFormateur']);
Route::post('DeleteRowsTmpStockTr', [TransferStockController::class, 'DeleteRowsTmpStockTr']);
Route::post('StoreTransfer', [TransferStockController::class, 'StoreTransfer']);
Route::post('UpdateQteTmpTransfer', [TransferStockController::class, 'UpdateQteTmpTransfer']);
Route::get('transfer/{id}', [TransferStockController::class, 'showTransferDetail'])->name('transfer.detail');

Route::get('EditTransfer/{id}', [TransferStockController::class, 'edit'])->name('EditTransfer');
Route::post('UpdateTransfer', [TransferStockController::class, 'update'])->name('UpdateTransfer');
Route::post('ChangeStatusTransfer', [TransferStockController::class, 'ChangeStatusTransfer'])->name('ChangeStatusTransfer');
Route::delete('/transfer/delete', [TransferStockController::class, 'deleteTransfer'])->name('transfer.delete');
// router routes 
Route::get('Router', [RouterStockController::class, 'index']);
Route::get('getFormateurCommands', [RouterStockController::class, 'getFormateurCommands']);
Route::get('GetLigneCommandeByCommand', [RouterStockController::class, 'GetLigneCommandeByCommand']);
Route::post('StoreProductStockTransfer', [RouterStockController::class, 'StoreProductStockTransfer']);
Route::get('GetTmpStockTransferByFormateur', [RouterStockController::class, 'GetTmpStockTransferByFormateur']);
Route::post('DeleteRowsTmpStockTransfer', [RouterStockController::class, 'DeleteRowsTmpStockTransfer']);
Route::post('StoreRouter', [RouterStockController::class, 'StoreRouter']);
Route::post('completeRouting', [RouterStockController::class, 'completeRouting']);
Route::post('/UpdateQteRouterTmp', [RouterStockController::class, 'UpdateQteRouterTmp'])->name('UpdateQteRouterTmp');
Route::delete('router/delete', [RouterStockController::class, 'deleteRouter'])->name('router.delete');
Route::get('router/{id}', [RouterStockController::class, 'show'])->name('router.show');
// New routes for edit functionality
Route::get('router/edit/{id}', [RouterStockController::class, 'edit'])->name('router.edit');
Route::post('router/update-status', [RouterStockController::class, 'update'])->name('router.update');
Route::post('router/change-status', [RouterStockController::class, 'ChangeStatusRouter'])->name('router.change-status');
});