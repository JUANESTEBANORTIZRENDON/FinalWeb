<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Artwork;
use App\Models\Comment;
use App\Models\User;
use App\Models\ArtworkLike;

class AdminDashboardController extends Controller
{
    /**
     * Display the admin dashboard.
     */
    public function index()
    {
        $totalUsers = User::count();
        $activeUsers = User::where('is_active', 'true')->count();
        $totalArtworks = Artwork::count();
        $publicArtworks = Artwork::where('is_public', 'true')->count();
        $totalLikes = ArtworkLike::count();
        $totalComments = Comment::count();

        return view('admin.dashboard', compact(
            'totalUsers',
            'activeUsers',
            'totalArtworks',
            'publicArtworks',
            'totalLikes',
            'totalComments'
        ));
    }
}
