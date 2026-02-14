<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\View\View;

class ArtistProfileController extends Controller
{
    /**
     * Create a new controller instance.
     */
    public function __construct()
    {
        // Middleware auth solo para edición
        $this->middleware('auth')->except(['showPublic', 'index']);
        
        // Middleware artist solo para métodos de artista
        $this->middleware('artist')->except(['showPublic', 'index']);
    }
    
    /**
     * Display a listing of all artists.
     */
    public function index()
    {
        // Obtener todos los usuarios que son artistas con el conteo de sus obras
        $artists = User::where('user_type', 'artist')
                      ->withCount('artworks')
                      ->get();
        
        return view('artists.index', compact('artists'));
    }
    
    /**
     * Show the artist profile.
     */
    public function show()
    {
        $artist = Auth::user();
        
        // Intenta cargar las obras del artista, manejar posibles errores
        try {
            $artworks = $artist->artworks()->with('category')->latest()->get();
        } catch (\Exception $e) {
            // Si hay un error, establecer obras como colección vacía
            $artworks = collect([]);
        }
        
        // Imprimir información para depuración
        // dd($artist, $artworks);
        
        return view('artist.profile', [
            'artist' => $artist,
            'artworks' => $artworks
        ]);
    }
    
    /**
     * Show the artist profile edit form.
     */
    public function edit(): View
    {
        return view('artist.profile-edit', [
            'artist' => Auth::user()
        ]);
    }
    
    /**
     * Update the artist profile.
     */
    public function update(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'bio' => 'nullable|string',
            'website_url' => 'nullable|url',
            'social_media.instagram' => 'nullable|string',
            'social_media.twitter' => 'nullable|string',
            'social_media.facebook' => 'nullable|string',
        ]);
        
        $user = Auth::user();
        
        $user->update([
            'name' => $request->name,
            'bio' => $request->bio,
            'website_url' => $request->website_url,
            'social_media' => [
                'instagram' => $request->input('social_media.instagram'),
                'twitter' => $request->input('social_media.twitter'),
                'facebook' => $request->input('social_media.facebook'),
            ],
        ]);
        
        // Handle avatar upload if present
        if ($request->hasFile('avatar') && $request->file('avatar')->isValid()) {
            // Validar que sea una imagen y no exceda 12MB
            $request->validate([
                'avatar' => 'image|max:12288', // 12MB = 12 * 1024
            ]);
            
            // Almacenar la imagen con un nombre único basado en timestamp
            $fileName = time() . '.' . $request->avatar->extension();
            $path = $request->file('avatar')->storeAs('avatars', $fileName, 'public');
            
            // Actualizar el path en la base de datos
            $user->avatar_path = $path;
            $user->save();
        }
        
        return redirect()->route('artist.profile')->with('status', 'Perfil actualizado correctamente');
    }
    
    /**
     * Show the public profile of an artist.
     * 
     * @param string $id The ID of the artist to show
     * @return \Illuminate\View\View
     */
    public function showPublic($id)
    {
        // Buscar el artista por ID
        $artist = User::findOrFail($id);
        
        // Verificar que sea un artista
        if (!$artist->isArtist()) {
            abort(404, 'Este usuario no es un artista.');
        }
        
        // Cargar solo las obras públicas del artista
        $artworks = $artist->artworks()
                          // Ver comentario en ArtworkController: PDO emulando prepares en Neon pooler
                          // puede convertir boolean a 1/0.
                          ->where('is_public', 'true')
                          ->with('category')
                          ->latest()
                          ->get();
        
        return view('artist.profile-public', [
            'artist' => $artist,
            'artworks' => $artworks
        ]);
    }
}
