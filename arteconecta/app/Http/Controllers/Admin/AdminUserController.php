<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;
use Illuminate\Validation\Rule;

class AdminUserController extends Controller
{
    /**
     * Display a listing of users with filters.
     */
    public function index(Request $request)
    {
        $search = $request->query('search');
        $role = $request->query('role');
        $status = $request->query('status');

        $usersQuery = User::query();

        if ($search) {
            $usersQuery->where(function ($query) use ($search) {
                $query->where('name', 'ILIKE', '%' . $search . '%')
                    ->orWhere('email', 'ILIKE', '%' . $search . '%');
            });
        }

        if ($role && in_array($role, ['admin', 'artist', 'visitor'], true)) {
            $usersQuery->where('user_type', $role);
        }

        if ($status === 'active') {
            $usersQuery->where('is_active', 'true');
        } elseif ($status === 'inactive') {
            $usersQuery->where('is_active', 'false');
        }

        $users = $usersQuery
            ->orderBy('created_at', 'desc')
            ->paginate(10)
            ->withQueryString();

        return view('admin.users.index', compact('users', 'search', 'role', 'status'));
    }

    /**
     * Display the specified user.
     */
    public function show(string $id)
    {
        $user = User::findOrFail($id);

        return view('admin.users.show', compact('user'));
    }

    /**
     * Show the form for editing the specified user.
     */
    public function edit(string $id)
    {
        $user = User::findOrFail($id);

        return view('admin.users.edit', compact('user'));
    }

    /**
     * Update the specified user in storage.
     */
    public function update(Request $request, string $id)
    {
        $user = User::findOrFail($id);

        $validated = $request->validate([
            'name' => ['required', 'string', 'max:100'],
            'email' => ['required', 'string', 'lowercase', 'email', 'max:100', Rule::unique('users')->ignore($user->id)],
            'bio' => ['nullable', 'string'],
            'website_url' => ['nullable', 'url', 'max:255'],
            'social_media.instagram' => ['nullable', 'string', 'max:255'],
            'social_media.twitter' => ['nullable', 'string', 'max:255'],
            'social_media.facebook' => ['nullable', 'string', 'max:255'],
            'user_type' => ['required', 'string', Rule::in(['admin', 'artist', 'visitor'])],
            'is_active' => ['required', Rule::in(['0', '1'])],
            'avatar' => ['nullable', 'image', 'max:12288'],
            'password' => ['nullable', 'confirmed', 'min:8'],
        ]);

        if ($user->id === Auth::id()) {
            if ($validated['user_type'] !== 'admin') {
                return back()->with('error', 'No puedes quitarte el rol de administrador.')->withInput();
            }
            if ($validated['is_active'] === '0') {
                return back()->with('error', 'No puedes desactivar tu propio usuario.')->withInput();
            }
        }

        $user->name = $validated['name'];
        $user->email = $validated['email'];
        $user->bio = $validated['bio'] ?? null;
        $user->website_url = $validated['website_url'] ?? null;
        $user->social_media = [
            'instagram' => $request->input('social_media.instagram'),
            'twitter' => $request->input('social_media.twitter'),
            'facebook' => $request->input('social_media.facebook'),
        ];
        $user->user_type = $validated['user_type'];
        $user->is_active = $validated['is_active'] === '1' ? 'true' : 'false';

        if (!empty($validated['password'])) {
            $user->password = Hash::make($validated['password']);
        }

        if ($request->hasFile('avatar') && $request->file('avatar')->isValid()) {
            if ($user->avatar_path && Storage::disk('public')->exists($user->avatar_path)) {
                Storage::disk('public')->delete($user->avatar_path);
            }

            $path = $request->file('avatar')->store('avatars', 'public');
            $user->avatar_path = $path;
        }

        $user->save();

        return redirect()->route('admin.users.edit', $user->id)->with('status', 'Usuario actualizado correctamente.');
    }

    /**
     * Toggle active status for a user.
     */
    public function toggleActive(string $id)
    {
        $user = User::findOrFail($id);

        if ($user->id === Auth::id()) {
            return back()->with('error', 'No puedes desactivar tu propio usuario.');
        }

        $user->is_active = $user->is_active ? 'false' : 'true';
        $user->save();

        return back()->with('status', 'Estado del usuario actualizado correctamente.');
    }

    /**
     * Deactivate a user (soft behavior).
     */
    public function destroy(string $id)
    {
        $user = User::findOrFail($id);

        if ($user->id === Auth::id()) {
            return back()->with('error', 'No puedes desactivar tu propio usuario.');
        }

        $user->is_active = 'false';
        $user->save();

        return redirect()->route('admin.users.index')->with('status', 'Usuario desactivado correctamente.');
    }
}
