<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\ProduitController;
use App\Http\Controllers\ClientController;
use App\Http\Controllers\FournisseurController;
use App\Http\Controllers\VenteController;
use App\Http\Controllers\AchatController;
use App\Http\Controllers\FactureController;
use App\Http\Controllers\StockController;
use App\Http\Controllers\EntrepotController;
use App\Http\Controllers\StatistiqueController;
use App\Http\Controllers\UtilisateurController;
use App\Http\Controllers\ParametreController;
use App\Http\Controllers\MotDePasseController;
use App\Exports\StatistiquesExport;

// ============================================================
// ROUTES PUBLIQUES (sans connexion)
// ============================================================
Route::get('/', function () {
    return redirect()->route('login');
});

Route::get('/login', [AuthController::class, 'showLogin'])->name('login');
Route::post('/login', [AuthController::class, 'login'])->name('login.post');
Route::post('/logout', [AuthController::class, 'logout'])->name('logout');

// ============================================================
// ROUTES PROTÉGÉES (connexion requise)
// ============================================================
Route::middleware(['auth'])->group(function () {

    // Dashboard
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');

    // Modifier mot de passe
Route::get('mot-de-passe', [MotDePasseController::class, 'index'])->name('mot_de_passe.index');
Route::post('mot-de-passe', [MotDePasseController::class, 'update'])->name('mot_de_passe.update');
Route::patch('clients/{id}/categorie', [ClientController::class, 'updateCategorie'])->name('clients.categorie');
    // Produits
    Route::resource('produits', ProduitController::class);
    Route::get('/produits/recherche', [ProduitController::class, 'recherche'])->name('produits.recherche');
// Clients
Route::resource('clients', ClientController::class);
Route::get('/clients/categories/update', [ClientController::class, 'mettreAJourCategories'])->name('clients.categories');

    // Fournisseurs
    Route::resource('fournisseurs', FournisseurController::class);

    // Ventes
    Route::resource('ventes', VenteController::class)->only([
        'index', 'create', 'store', 'show'
    ]);

    // Achats
    Route::resource('achats', AchatController::class)->only([
        'index', 'create', 'store', 'show'
    ]);
    

    // Factures
    Route::resource('factures', FactureController::class)->only([
        'index', 'show'
    ]);
    Route::get('/factures/{facture}/imprimer', [FactureController::class, 'imprimer'])->name('factures.imprimer');
    Route::post('/factures/{facture}/statut', [FactureController::class, 'changerStatut'])->name('factures.statut');
    // Routes Administrateur uniquement
Route::middleware(['auth'])->group(function () {
    
    // Gestion utilisateurs
    Route::resource('utilisateurs', UtilisateurController::class);
    
    // Gestion paramètres
    Route::get('parametres', [ParametreController::class, 'index'])->name('parametres.index');
    Route::post('parametres/sauvegarder', [ParametreController::class, 'sauvegarder'])->name('parametres.sauvegarder');
    Route::post('parametres/restaurer', [ParametreController::class, 'restaurer'])->name('parametres.restaurer');
});
// Export Statistiques
Route::get('statistiques/export/pdf', [StatistiqueController::class, 'exportPdf'])
    ->name('statistiques.export.pdf');

Route::get('statistiques/export/excel', [StatistiqueController::class, 'exportExcel'])
    ->name('statistiques.export.excel');
    // Stock
    Route::get('/stock', [StockController::class, 'index'])->name('stock.index');
    Route::get('/stock/mouvements', [StockController::class, 'mouvements'])->name('stock.mouvements');
    Route::post('/stock/entree', [StockController::class, 'entree'])->name('stock.entree');
    Route::post('/stock/sortie', [StockController::class, 'sortie'])->name('stock.sortie');
    Route::post('/stock/transfert', [StockController::class, 'transfert'])->name('stock.transfert');

    // Entrepôts
    Route::resource('entrepots', EntrepotController::class);

    // Statistiques
    Route::get('/statistiques', [StatistiqueController::class, 'index'])->name('statistiques.index');
    // Export Clients
Route::get('clients/export/excel', function() {
    return Excel::download(new ClientsExport, 'clients_' . date('Y-m-d') . '.xlsx');
})->name('clients.export.excel');

Route::get('clients/export/pdf', function() {
    $clients = \App\Models\Client::all();
    $pdf = Pdf::loadView('exports.clients_pdf', compact('clients'));
    return $pdf->download('clients_' . date('Y-m-d') . '.pdf');
})->name('clients.export.pdf');

// Export Ventes
Route::get('ventes/export/excel', function() {
    return Excel::download(new VentesExport, 'ventes_' . date('Y-m-d') . '.xlsx');
})->name('ventes.export.excel');

Route::get('ventes/export/pdf', function() {
    $ventes = \App\Models\Vente::with(['client', 'utilisateur'])->get();
    $pdf = Pdf::loadView('exports.ventes_pdf', compact('ventes'));
    return $pdf->download('ventes_' . date('Y-m-d') . '.pdf');
})->name('ventes.export.pdf');
});