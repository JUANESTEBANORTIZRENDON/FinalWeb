<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;
use Illuminate\Validation\Rule;

class VisitorProfileController extends Controller
{
    /**
     * Constructor para asegurar que sólo usuarios autenticados y visitantes puedan acceder
     */
    public function __construct()
    {
        $this->middleware('auth');
        $this->middleware(function ($request, $next) {
            if (!Auth::user()->isVisitor()) {
                return redirect()->route('dashboard');
            }
            return $next($request);
        });
    }

    /**
     * Mostrar formulario de edición de perfil del visitante
     */
    public function edit()
    {
        $user = Auth::user();
        return view('visitor.profile-edit', compact('user'));
    }

    /**
     * Actualizar la información del perfil
     */
    public function update(Request $request)
    {
        $user = Auth::user();
        
        $validated = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'email', 'max:255', Rule::unique('users')->ignore($user->id)],
            'avatar' => ['nullable', 'image', 'max:2048'],
            'current_password' => ['nullable', 'required_with:password', function ($attribute, $value, $fail) use ($user) {
                if (!Hash::check($value, $user->password)) {
                    $fail('La contraseña actual es incorrecta.');
                }
            }],
            'password' => ['nullable', 'confirmed', 'min:8'],
        ]);
        
        // Actualizar datos básicos
        $user->name = $validated['name'];
        $user->email = $validated['email'];
        
        // Actualizar contraseña si se proporcionó
        if (isset($validated['password'])) {
            $user->password = Hash::make($validated['password']);
        }
        
        // Actualizar avatar si se proporcionó
        if ($request->hasFile('avatar')) {
            // Eliminar avatar anterior si existe
            if ($user->avatar_path && Storage::disk('public')->exists($user->avatar_path)) {
                Storage::disk('public')->delete($user->avatar_path);
            }
            
            // Guardar nuevo avatar
            $path = $request->file('avatar')->store('avatars', 'public');
            $user->avatar_path = $path;
        }
        
        $user->save();
        
        return redirect()->route('visitor.profile.edit')->with('status', 'Perfil actualizado correctamente');
    }

    /**
     * Convertir un usuario visitante a artista
     */
    public function convertToArtist(Request $request)
    {
        $user = Auth::user();
        
        // Cambiar el tipo de usuario a artista
        $user->user_type = 'artist';
        $user->save();
        
        // Redirigir al perfil de artista para que complete la información
        return redirect()->route('artist.profile.edit')->with('status', 'Ahora eres un artista en ArteConecta. Completa tu perfil para comenzar a compartir tus obras.');
    }
}
