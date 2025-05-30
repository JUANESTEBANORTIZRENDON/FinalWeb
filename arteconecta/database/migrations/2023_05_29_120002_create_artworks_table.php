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
        Schema::create('artworks', function (Blueprint $table) {
            $table->uuid('id')->primary()->default(DB::raw('uuid_generate_v4()'));
            $table->uuid('artist_id');
            $table->string('title', 200);
            $table->text('description')->nullable();
            $table->uuid('category_id');
            $table->string('image_path', 255);
            $table->date('creation_date')->nullable();
            $table->string('technique', 100)->nullable();
            $table->string('dimensions', 50)->nullable();
            $table->boolean('is_public')->default(true);
            $table->timestamps();

            $table->foreign('artist_id')->references('id')->on('users')->onDelete('cascade');
            $table->foreign('category_id')->references('id')->on('art_categories');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('artworks');
    }
};
