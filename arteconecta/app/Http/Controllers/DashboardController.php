<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Artwork;

class DashboardController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth');
    }

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function index()
    {
        $user = Auth::user();
        
        // Redirigir a los artistas a su perfil específico
        if ($user->isArtist()) {
            return redirect()->route('artist.profile');
        }
        
        // Para visitantes, mostrar contenido relevante
        $recentArtworks = Artwork::with(['artist', 'category', 'likes', 'comments'])
                            ->where('is_public', true)
                            ->latest()
                            ->take(6)
                            ->get();
        
        // Obtener artistas que el usuario sigue
        $followingArtistsIds = $user->following()->pluck('artist_id');
        
        // Obtener obras de los artistas seguidos
        $followedArtistsArtworks = Artwork::with(['artist', 'category', 'likes', 'comments'])
                                    ->whereIn('artist_id', $followingArtistsIds)
                                    ->where('is_public', true)
                                    ->latest()
                                    ->take(6)
                                    ->get();
                                    
        // Obtener artworks que el usuario ha marcado como favoritos (liked)
        $likedArtworksIds = $user->likes()->pluck('artwork_id');
        $likedArtworks = Artwork::with(['artist', 'category', 'likes', 'comments'])
                          ->whereIn('id', $likedArtworksIds)
                          ->where('is_public', true)
                          ->latest()
                          ->take(6)
                          ->get();

        return view('visitor.dashboard', compact('recentArtworks', 'followedArtistsArtworks', 'likedArtworks', 'user'));
    }
}
