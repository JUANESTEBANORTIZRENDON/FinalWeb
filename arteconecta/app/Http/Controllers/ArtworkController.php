<?php

namespace App\Http\Controllers;

use App\Models\Artwork;
use App\Models\ArtCategory;
use App\Models\ArtworkLike;
use App\Models\Comment;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\DB;

class ArtworkController extends Controller
{
    /**
     * Create a new controller instance.
     */
    public function __construct()
    {
        // Solo middleware auth para los métodos que lo requieren
        $this->middleware('auth')->except(['index', 'show']);
        
        // Middleware artist solo para métodos de creación/edición
        $this->middleware('artist')->only(['create', 'store', 'edit', 'update', 'destroy']);
        
        // Middleware para verificar que el artista solo pueda editar/eliminar sus propias obras
        $this->middleware(function ($request, $next) {
            $artwork = $request->route('artwork');
            if ($artwork && Auth::id() !== $artwork->artist_id) {
                abort(403, 'No tienes permiso para modificar esta obra.');
            }
            return $next($request);
        })->only(['edit', 'update', 'destroy']);
    }
    
    /**
     * Display a listing of all public artworks.
     */
    public function index()
    {
        // Obtener todas las obras públicas con información del artista y categoría
        $artworks = Artwork::with(['artist', 'category'])
                        // Con PDO::ATTR_EMULATE_PREPARES (Neon pooler) los booleanos pueden
                        // serializarse como 1/0 y PostgreSQL rechaza boolean = integer.
                        ->where('is_public', 'true')
                        ->latest()
                        ->paginate(12);
        
        return view('artworks.index', compact('artworks'));
    }
    
    /**
     * Display a listing of the authenticated artist's artworks.
     */
    public function myArtworks()
    {
        // Verificar que el usuario es un artista
        if (!Auth::user()->isArtist()) {
            abort(403, 'No tienes permiso para acceder a esta página.');
        }
        
        // Obtener todas las obras del artista autenticado
        $artworks = Artwork::with(['category', 'likes', 'comments'])
                        ->where('artist_id', Auth::id())
                        ->latest()
                        ->get();
        
        return view('artist.artworks.my-artworks', compact('artworks'));
    }
    
    /**
     * Show the form for creating a new artwork.
     */
    public function create()
    {
        // Obtener categorías para el dropdown
        $categories = ArtCategory::all();
        
        return view('artist.artworks.create', compact('categories'));
    }
    
    /**
     * Store a newly created artwork in storage.
     */
    public function store(Request $request)
    {
        // Activar logging detallado para depuración
        \Log::info('Iniciando proceso de guardar obra');
        \Log::info('Datos recibidos:', $request->all());
        
        try {
            // Validar los datos del formulario
            $validatedData = $request->validate([
                'title' => 'required|string|max:255',
                'description' => 'nullable|string',
                'category_id' => 'required|exists:art_categories,id',
                'image' => 'required|image|mimes:jpeg,png,jpg|max:12288', // 12MB
                'technique' => 'nullable|string|max:255',
                'dimensions' => 'nullable|string|max:255',
                'creation_date' => 'nullable|date',
                // Eliminamos la validación del campo is_public ya que lo manejaremos manualmente
            ]);
            
            \Log::info('Validación pasada correctamente');
        } catch (\Illuminate\Validation\ValidationException $e) {
            \Log::error('Error de validación: ' . json_encode($e->errors()));
            return back()->withErrors($e->errors())->withInput();
        }
        
        // Manejar la subida de la imagen
        if ($request->hasFile('image')) {
            \Log::info('Archivo de imagen presente en la solicitud');
            
            if ($request->file('image')->isValid()) {
                \Log::info('El archivo de imagen es válido');
                
                try {
                    // Crear un nombre de archivo único basado en timestamp y título
                    $title_slug = Str::slug($request->title);
                    $extension = $request->file('image')->getClientOriginalExtension();
                    $fileName = time() . '_' . $title_slug . '.' . $extension;
                    
                    \Log::info('Nombre de archivo generado: ' . $fileName);
                    
                    // Verificar que el directorio exista y tenga permisos
                    $directory = storage_path('app/public/artworks');
                    if (!file_exists($directory)) {
                        \Log::info('Creando directorio: ' . $directory);
                        mkdir($directory, 0755, true);
                    }
                    
                    // Almacenar la imagen - método modificado para mejor depuración
                    $path = $request->file('image')->storeAs('artworks', $fileName, 'public');
                    \Log::info('Imagen almacenada en: ' . $path);
            
                    // Crear el nuevo registro de artwork
                    $artwork = new Artwork();
                    $artwork->id = (string) Str::uuid(); // Generar UUID para el ID
                    \Log::info('UUID generado: ' . $artwork->id);
                    
                    $artwork->artist_id = Auth::id();
                    $artwork->title = $request->title;
                    $artwork->description = $request->description;
                    $artwork->category_id = $request->category_id;
                    $artwork->image_path = $path;
                    $artwork->technique = $request->technique;
                    $artwork->dimensions = $request->dimensions;
                    $artwork->creation_date = $request->creation_date;
                    
                    // Convertir el valor 'on' del checkbox a booleano true/false
                    // Los checkboxes HTML envían 'on' cuando están marcados y nada cuando no lo están
                    // Con Neon pooler + emulación de prepares, Laravel puede enviar booleanos como 1/0
                    // y PostgreSQL los rechaza. Guardamos 'true'/'false' explícitamente.
                    $artwork->is_public = $request->has('is_public') ? 'true' : 'false';
                    \Log::info('Valor de is_public: ' . ($request->has('is_public') ? 'true' : 'false'));
                    
                    try {
                        \Log::info('Intentando guardar en la base de datos...');
                        $saved = $artwork->save();
                        \Log::info('Guardado exitoso: ' . ($saved ? 'true' : 'false'));
                        
                        // Redirigir al perfil del artista con mensaje de éxito
                        return redirect()->route('artist.profile')
                                ->with('status', 'Obra "' . $artwork->title . '" creada exitosamente.');
                    } catch (\Exception $e) {
                        \Log::error('Error al guardar en la base de datos: ' . $e->getMessage());
                        \Log::error('Trace: ' . $e->getTraceAsString());
                        return back()->withInput()->with('error', 'Error al guardar la obra: ' . $e->getMessage());
                    }
                } catch (\Exception $e) {
                    \Log::error('Error procesando la imagen: ' . $e->getMessage());
                    return back()->withInput()->with('error', 'Error procesando la imagen: ' . $e->getMessage());
                }
            } else {
                \Log::error('El archivo de imagen no es válido');
                return back()->withInput()->with('error', 'La imagen subida no es válida. Por favor, selecciona otra imagen.');
            }
            
        } else {
            \Log::error('No hay archivo de imagen en la solicitud');
            return back()->withInput()->with('error', 'No se ha seleccionado ninguna imagen. Por favor, selecciona una imagen para tu obra.');
        }
    }
    
    /**
     * Display the specified artwork.
     */
    public function show(Artwork $artwork)
    {
        // Verificar si la obra es privada y si el usuario actual no es el artista
        if (!$artwork->is_public && (!Auth::check() || Auth::id() !== $artwork->artist_id)) {
            abort(403, 'Esta obra no está disponible públicamente.');
        }
        
        // Cargar relaciones necesarias
        $artwork->load(['artist', 'category', 'comments.user', 'likes']);
        
        // Verificar si el usuario autenticado (si existe) ha dado like
        $userHasLiked = false;
        if (Auth::check()) {
            $userHasLiked = $artwork->likes->contains('user_id', Auth::id());
        }
        
        return view('artworks.show', compact('artwork', 'userHasLiked'));
    }
    
    /**
     * Toggle like status for an artwork.
     */
    public function toggleLike(Artwork $artwork)
    {
        // Verificar que el usuario esté autenticado
        if (!Auth::check()) {
            return response()->json(['error' => 'Debes iniciar sesión para dar like'], 401);
        }
        
        // No permitir que un artista dé like a su propia obra
        if (Auth::id() === $artwork->artist_id) {
            return response()->json(['error' => 'No puedes dar like a tu propia obra'], 403);
        }
        
        // Buscar si ya existe un like
        $like = ArtworkLike::where('artwork_id', $artwork->id)
                    ->where('user_id', Auth::id())
                    ->first();
        
        $isLiked = false;
        
        if ($like) {
            // Si existe, eliminarlo (unlike)
            $like->delete();
        } else {
            // Si no existe, crearlo (like)
            $like = new ArtworkLike();
            $like->id = (string) Str::uuid();
            $like->artwork_id = $artwork->id;
            $like->user_id = Auth::id();
            $like->save();
            $isLiked = true;
        }
        
        // Obtener conteo actualizado de likes
        $likesCount = ArtworkLike::where('artwork_id', $artwork->id)->count();
        
        return response()->json([
            'liked' => $isLiked,
            'likesCount' => $likesCount
        ]);
    }
    
    /**
     * Store a new comment for an artwork.
     */
    public function storeComment(Request $request, Artwork $artwork)
    {
        // Validar datos del comentario
        $request->validate([
            'content' => 'required|string|max:500',
        ]);
        
        // Crear nuevo comentario
        $comment = new Comment();
        $comment->id = (string) Str::uuid();
        $comment->artwork_id = $artwork->id;
        $comment->user_id = Auth::id();
        $comment->content = $request->content;
        $comment->save();
        
        return redirect()->back()->with('status', 'Comentario publicado correctamente.');
    }
    
    /**
     * Show the form for editing the specified artwork.
     */
    public function edit(Artwork $artwork)
    {
        // Verificar que el usuario sea el artista propietario
        if (Auth::id() !== $artwork->artist_id) {
            abort(403, 'No tienes permiso para editar esta obra.');
        }
        
        // Obtener categorías para el dropdown
        $categories = ArtCategory::all();
        
        return view('artist.artworks.edit', compact('artwork', 'categories'));
    }
    
    /**
     * Update the specified artwork in storage.
     */
    public function update(Request $request, Artwork $artwork)
    {
        // Activar logging detallado para depuración
        \Log::info('Iniciando proceso de actualizar obra: ' . $artwork->id);
        \Log::info('Datos recibidos:', $request->all());
        
        try {
            // Validar los datos del formulario
            $validatedData = $request->validate([
                'title' => 'required|string|max:255',
                'description' => 'nullable|string',
                'category_id' => 'required|exists:art_categories,id',
                'image' => 'nullable|image|mimes:jpeg,png,jpg|max:12288', // 12MB
                'technique' => 'nullable|string|max:255',
                'dimensions' => 'nullable|string|max:255',
                'creation_date' => 'nullable|date',
                // Manejamos is_public manualmente
            ]);
            
            \Log::info('Validación pasada correctamente para actualización');
        } catch (\Illuminate\Validation\ValidationException $e) {
            \Log::error('Error de validación en actualización: ' . json_encode($e->errors()));
            return back()->withErrors($e->errors())->withInput();
        }
        
        try {
            // Actualizar datos básicos
            $artwork->title = $request->title;
            $artwork->description = $request->description;
            $artwork->category_id = $request->category_id;
            $artwork->technique = $request->technique;
            $artwork->dimensions = $request->dimensions;
            $artwork->creation_date = $request->creation_date;
            // Con Neon pooler + emulación de prepares, Laravel puede enviar booleanos como 1/0
            // y PostgreSQL los rechaza. Guardamos 'true'/'false' explícitamente.
            $artwork->is_public = $request->has('is_public') ? 'true' : 'false';
            
            // Solo actualizar la imagen si se proporciona una nueva
            if ($request->hasFile('image') && $request->file('image')->isValid()) {
                \Log::info('Nueva imagen proporcionada para actualización');
                
                // Eliminar imagen anterior si existe
                if ($artwork->image_path && Storage::disk('public')->exists($artwork->image_path)) {
                    \Log::info('Eliminando imagen anterior: ' . $artwork->image_path);
                    Storage::disk('public')->delete($artwork->image_path);
                }
                
                // Crear un nombre de archivo único
                $title_slug = Str::slug($request->title);
                $extension = $request->file('image')->getClientOriginalExtension();
                $fileName = time() . '_' . $title_slug . '.' . $extension;
                
                // Guardar nueva imagen
                $path = $request->file('image')->storeAs('artworks', $fileName, 'public');
                \Log::info('Nueva imagen guardada en: ' . $path);
                
                // Actualizar ruta en la obra
                $artwork->image_path = $path;
            }
            
            // Guardar cambios
            $artwork->save();
            \Log::info('Obra actualizada correctamente: ' . $artwork->id);
            
            return redirect()->route('artist.profile')->with('status', 'Obra actualizada correctamente.');
            
        } catch (\Exception $e) {
            \Log::error('Error al actualizar la obra: ' . $e->getMessage());
            \Log::error('Trace: ' . $e->getTraceAsString());
            return back()->withInput()->with('error', 'Error al actualizar la obra: ' . $e->getMessage());
        }
    }
    
    /**
     * Remove the specified artwork from storage.
     */
    public function destroy(Artwork $artwork)
    {
        try {
            \Log::info('Iniciando eliminación de obra: ' . $artwork->id);
            
            // Verificar que el usuario autenticado es el propietario de la obra
            if (auth()->id() !== $artwork->artist_id) {
                \Log::warning('Intento de eliminación no autorizado para la obra: ' . $artwork->id);
                return back()->with('error', 'No tienes permiso para eliminar esta obra.');
            }
            
            // Obtener información para validación posterior
            $artwork_id = $artwork->id;
            $image_path = $artwork->image_path;
            
            // Eliminar la imagen del almacenamiento si existe (fuera de la transacción)
            if ($image_path && Storage::disk('public')->exists($image_path)) {
                try {
                    Storage::disk('public')->delete($image_path);
                    \Log::info('Imagen eliminada: ' . $image_path);
                } catch (\Exception $imageEx) {
                    \Log::warning('Error al eliminar imagen: ' . $imageEx->getMessage());
                    // Continuamos aunque falle la eliminación de la imagen
                }
            }
            
            // Eliminar todo con Eloquent y dejar que las restricciones de la base de datos manejen la eliminación en cascada
            try {
                // Para evitar el problema con PostgreSQL, usamos consultas separadas sin transacción
                \Log::info('Eliminando obra ID: ' . $artwork_id);
                
                // 1. Eliminar los likes primero
                ArtworkLike::where('artwork_id', $artwork_id)->delete();
                \Log::info('Likes eliminados');
                
                // 2. Eliminar los comentarios
                Comment::where('artwork_id', $artwork_id)->delete();
                \Log::info('Comentarios eliminados');
                
                // 3. Finalmente eliminar la obra
                $artwork->delete();
                \Log::info('Obra eliminada completamente');
                
                return redirect()->route('artist.profile')->with('status', 'Obra eliminada correctamente.');
                
            } catch (\Exception $dbEx) {
                \Log::error('Error en operación de DB: ' . $dbEx->getMessage());
                throw $dbEx; // Relanzar para el manejador principal
            }
            
        } catch (\Exception $e) {
            \Log::error('Error al eliminar la obra: ' . $e->getMessage());
            \Log::error('Trace: ' . $e->getTraceAsString());
            
            return back()->with('error', 'Error al eliminar la obra: ' . $e->getMessage());
        }
    }
}
