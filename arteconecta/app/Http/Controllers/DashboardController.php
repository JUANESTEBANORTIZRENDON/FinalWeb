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

        // Redirigir administradores a su panel y evitar consultas de feed de visitante.
        if ($user->isAdmin()) {
            return redirect()->route('admin.dashboard');
        }
        
        // Para visitantes, mostrar contenido relevante con conteos agregados para evitar N+1.
        $recentArtworks = Artwork::query()
            ->select(['id', 'artist_id', 'category_id', 'title', 'description', 'image_path', 'created_at'])
            ->with(['artist:id,name', 'category:id,name'])
            ->withCount(['likes', 'comments'])
            ->where('is_public', 'true')
            ->latest()
            ->take(6)
            ->get();

        // Obras de artistas seguidos usando subconsulta en lugar de pluck previo.
        $followedArtistsArtworks = Artwork::query()
            ->select(['id', 'artist_id', 'category_id', 'title', 'description', 'image_path', 'created_at'])
            ->with(['artist:id,name', 'category:id,name'])
            ->withCount(['likes', 'comments'])
            ->where('is_public', 'true')
            ->whereIn('artist_id', function ($query) use ($user) {
                $query->select('artist_id')
                    ->from('followers')
                    ->where('follower_id', $user->id);
            })
            ->latest()
            ->take(6)
            ->get();

        // Obras favoritas del usuario con subconsulta.
        $likedArtworks = Artwork::query()
            ->select(['id', 'artist_id', 'category_id', 'title', 'description', 'image_path', 'created_at'])
            ->with(['artist:id,name', 'category:id,name'])
            ->withCount(['likes', 'comments'])
            ->where('is_public', 'true')
            ->whereIn('id', function ($query) use ($user) {
                $query->select('artwork_id')
                    ->from('artwork_likes')
                    ->where('user_id', $user->id);
            })
            ->latest()
            ->take(6)
            ->get();

        return view('visitor.dashboard', compact('recentArtworks', 'followedArtistsArtworks', 'likedArtworks', 'user'));
    }
}
