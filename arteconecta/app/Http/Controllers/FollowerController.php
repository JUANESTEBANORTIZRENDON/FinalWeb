<?php

namespace App\Http\Controllers;

use App\Models\Follower;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;

class FollowerController extends Controller
{
    /**
     * Toggle follow status for an artist.
     *
     * @param  Request  $request
     * @param  string  $artistId
     * @return \Illuminate\Http\JsonResponse
     */
    public function toggleFollow(Request $request, $artistId)
    {
        // Verificar que el usuario está autenticado
        if (!Auth::check()) {
            return response()->json(['error' => 'Usuario no autenticado'], 401);
        }

        try {
            // Verificar que el artista existe
            $artist = User::where('id', $artistId)->where('user_type', 'artist')->first();
            if (!$artist) {
                return response()->json(['error' => 'Artista no encontrado'], 404);
            }

            // Evitar que un usuario se siga a sí mismo
            if (Auth::id() === $artistId) {
                return response()->json(['error' => 'No puedes seguirte a ti mismo'], 400);
            }

            // Comprobar si ya sigue al artista
            $existingFollow = Follower::where('artist_id', $artistId)
                ->where('follower_id', Auth::id())
                ->first();

            $isFollowing = false;

            if ($existingFollow) {
                // Si ya lo sigue, eliminar el seguimiento
                $existingFollow->delete();
            } else {
                // Si no lo sigue, crear nuevo seguimiento
                $follow = new Follower();
                $follow->id = (string) Str::uuid();
                $follow->artist_id = $artistId;
                $follow->follower_id = Auth::id();
                $follow->created_at = now();
                $follow->save();
                $isFollowing = true;
            }

            // Obtener el número actualizado de seguidores
            $followersCount = Follower::where('artist_id', $artistId)->count();

            return response()->json([
                'following' => $isFollowing,
                'followersCount' => $followersCount
            ]);
        } catch (\Exception $e) {
            return response()->json(['error' => 'Error al procesar la solicitud: ' . $e->getMessage()], 500);
        }
    }
}
