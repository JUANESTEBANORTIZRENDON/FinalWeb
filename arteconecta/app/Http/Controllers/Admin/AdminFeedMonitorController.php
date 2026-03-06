<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Artwork;
use App\Models\ArtworkLike;
use App\Models\Comment;

class AdminFeedMonitorController extends Controller
{
    /**
     * Display the feed monitor view.
     */
    public function index()
    {
        $feedArtworks = Artwork::with(['artist', 'category'])
            ->withCount(['likes', 'comments'])
            ->where('is_public', 'true')
            ->latest()
            ->paginate(12);

        $totalPublicArtworks = Artwork::where('is_public', 'true')->count();
        $newPublicArtworks = Artwork::where('is_public', 'true')
            ->where('created_at', '>=', now()->subDays(7))
            ->count();

        $totalLikes = ArtworkLike::join('artworks', 'artwork_likes.artwork_id', '=', 'artworks.id')
            ->where('artworks.is_public', 'true')
            ->count();

        $totalComments = Comment::join('artworks', 'comments.artwork_id', '=', 'artworks.id')
            ->where('artworks.is_public', 'true')
            ->count();

        $topArtworks = Artwork::with('artist')
            ->withCount('likes')
            ->where('is_public', 'true')
            ->orderByDesc('likes_count')
            ->limit(5)
            ->get();

        $recentComments = Comment::with(['user', 'artwork'])
            ->whereHas('artwork', function ($query) {
                $query->where('is_public', 'true');
            })
            ->latest()
            ->limit(5)
            ->get();

        return view('admin.feed-monitor', compact(
            'feedArtworks',
            'totalPublicArtworks',
            'newPublicArtworks',
            'totalLikes',
            'totalComments',
            'topArtworks',
            'recentComments'
        ));
    }
}
