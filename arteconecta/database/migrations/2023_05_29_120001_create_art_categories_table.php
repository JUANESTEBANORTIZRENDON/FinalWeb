<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('art_categories', function (Blueprint $table) {
            $table->uuid('id')->primary()->default(DB::raw('public.uuid_generate_v4()'));
            $table->string('name', 100);
            $table->text('description')->nullable();
            $table->timestamps();
        });

        // Insert default categories
        DB::table('art_categories')->insert([
            ['name' => 'Pintura', 'description' => 'Paintings using oil, acrylic, watercolor, etc.', 'created_at' => now(), 'updated_at' => now()],
            ['name' => 'Escultura', 'description' => '3D artworks with various materials.', 'created_at' => now(), 'updated_at' => now()],
            ['name' => 'Fotografía', 'description' => 'Photography captured with a camera.', 'created_at' => now(), 'updated_at' => now()],
            ['name' => 'Digital', 'description' => 'Digitally created artwork.', 'created_at' => now(), 'updated_at' => now()],
            ['name' => 'Ilustración', 'description' => 'Graphic or illustrative art.', 'created_at' => now(), 'updated_at' => now()],
            ['name' => 'Mixta', 'description' => 'Mixed media art.', 'created_at' => now(), 'updated_at' => now()],
        ]);
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('art_categories');
    }
};
