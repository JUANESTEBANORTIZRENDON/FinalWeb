<?php

use App\Http\Controllers\ArtistProfileController;
use App\Http\Controllers\ArtworkController;
use App\Http\Controllers\BookRecommendationController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\FollowerController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\ReportController;
use App\Http\Controllers\VisitorProfileController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Make something great!
|
*/

Route::get('/', function () {
    return view('welcome');
});

Route::get('/dashboard', function () {
    if (auth()->user()->isArtist()) {
        return redirect()->route('artist.profile');
    }
    return view('dashboard');
})->middleware(['auth'])->name('dashboard');

// Rutas públicas para obras de arte (accesibles sin login)
Route::get('/artworks', [ArtworkController::class, 'index'])->name('artworks.index');
Route::get('/artworks/{artwork}', [ArtworkController::class, 'show'])->name('artworks.show');

// Rutas públicas para artistas
Route::get('/artists', [ArtistProfileController::class, 'index'])->name('artists.index');
Route::get('/artists/view/{id}', [ArtistProfileController::class, 'showPublic'])->where('id', '[0-9a-f]{8}-[0-9a-f]{4}-[0-9a-f]{4}-[0-9a-f]{4}-[0-9a-f]{12}')->name('artist.profile.public');

Route::middleware('auth')->group(function () {
    // Dashboard para todos los usuarios autenticados
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');
    
    // Rutas accesibles para todos los usuarios autenticados (independiente del rol)
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
    
    // Ruta para recomendaciones de libros
    Route::get('/biblioteca-arte', [BookRecommendationController::class, 'index'])->name('books.recommendations');
    
    // Rutas para el perfil de visitante
    Route::get('/visitor/profile/edit', [VisitorProfileController::class, 'edit'])->name('visitor.profile.edit');
    Route::put('/visitor/profile/update', [VisitorProfileController::class, 'update'])->name('visitor.profile.update');
    Route::post('/visitor/convert-to-artist', [VisitorProfileController::class, 'convertToArtist'])->name('visitor.convert.artist');
    
    // Rutas para el perfil de artista (protegidas por el middleware 'artist')
    Route::middleware('artist')->group(function () {
        // Perfil de artista - cambiamos a /my-profile para evitar conflictos
        Route::get('/my-profile', [ArtistProfileController::class, 'show'])->name('artist.profile');
        Route::get('/my-profile/edit', [ArtistProfileController::class, 'edit'])->name('artist.profile.edit');
        Route::patch('/my-profile', [ArtistProfileController::class, 'update'])->name('artist.profile.update');
        
        // Ruta para generar reporte PDF de publicaciones
        Route::get('/my-publications-report', [ReportController::class, 'generatePublicationsReport'])->name('artist.publications.report');
        
        // Gestión de obras (solo artistas)
        Route::get('/my-artworks', [ArtworkController::class, 'myArtworks'])->name('artist.artworks');
        Route::get('/my-artworks/create', [ArtworkController::class, 'create'])->name('artworks.create');
        Route::post('/artworks', [ArtworkController::class, 'store'])->name('artworks.store');
        Route::get('/artworks/{artwork}/edit', [ArtworkController::class, 'edit'])->name('artworks.edit');
        Route::patch('/artworks/{artwork}', [ArtworkController::class, 'update'])->name('artworks.update');
        Route::delete('/artworks/{artwork}', [ArtworkController::class, 'destroy'])->name('artworks.destroy');
    });
    
    // Rutas de interacción (requieren login)
    Route::post('/artworks/{artwork}/like', [ArtworkController::class, 'toggleLike'])->name('artworks.like');
    Route::post('/artworks/{artwork}/comments', [ArtworkController::class, 'storeComment'])->name('artworks.comments.store');
    Route::post('/artists/{artistId}/follow', [FollowerController::class, 'toggleFollow'])->name('artists.follow');
});

require __DIR__.'/auth.php';
