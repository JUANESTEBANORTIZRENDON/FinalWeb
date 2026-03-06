<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        DB::statement('CREATE INDEX IF NOT EXISTS users_type_active_created_idx ON users (user_type, is_active, created_at)');

        DB::statement('CREATE INDEX IF NOT EXISTS artworks_public_created_idx ON artworks (is_public, created_at)');
        DB::statement('CREATE INDEX IF NOT EXISTS artworks_artist_public_created_idx ON artworks (artist_id, is_public, created_at)');
        DB::statement('CREATE INDEX IF NOT EXISTS artworks_category_idx ON artworks (category_id)');

        DB::statement('CREATE INDEX IF NOT EXISTS comments_artwork_created_idx ON comments (artwork_id, created_at)');
        DB::statement('CREATE INDEX IF NOT EXISTS comments_user_created_idx ON comments (user_id, created_at)');

        DB::statement('CREATE INDEX IF NOT EXISTS artwork_likes_user_created_idx ON artwork_likes (user_id, created_at)');
        DB::statement('CREATE INDEX IF NOT EXISTS followers_follower_created_idx ON followers (follower_id, created_at)');

        DB::statement('CREATE INDEX IF NOT EXISTS sessions_user_last_activity_idx ON sessions (user_id, last_activity)');
        DB::statement('CREATE INDEX IF NOT EXISTS sessions_last_activity_idx ON sessions (last_activity)');
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        DB::statement('DROP INDEX IF EXISTS users_type_active_created_idx');

        DB::statement('DROP INDEX IF EXISTS artworks_public_created_idx');
        DB::statement('DROP INDEX IF EXISTS artworks_artist_public_created_idx');
        DB::statement('DROP INDEX IF EXISTS artworks_category_idx');

        DB::statement('DROP INDEX IF EXISTS comments_artwork_created_idx');
        DB::statement('DROP INDEX IF EXISTS comments_user_created_idx');

        DB::statement('DROP INDEX IF EXISTS artwork_likes_user_created_idx');
        DB::statement('DROP INDEX IF EXISTS followers_follower_created_idx');

        DB::statement('DROP INDEX IF EXISTS sessions_user_last_activity_idx');
        DB::statement('DROP INDEX IF EXISTS sessions_last_activity_idx');
    }
};
