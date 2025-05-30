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
        // Primero, verificamos si la extensión ya existe para evitar conflictos de transacción
        try {
            // Esto se ejecuta fuera de la transacción principal
            DB::statement('CREATE EXTENSION IF NOT EXISTS "uuid-ossp"');
        } catch (\Exception $e) {
            // La extensión ya podría existir o hay otro problema, pero continuamos
            // Ya la creamos manualmente en el script de conexión
        }
        
        Schema::create('users', function (Blueprint $table) {
            $table->uuid('id')->primary()->default(DB::raw('uuid_generate_v4()'));
            $table->string('name', 100);
            $table->string('email', 100)->unique();
            $table->timestamp('email_verified_at')->nullable();
            $table->string('password');
            $table->string('remember_token', 100)->nullable();
            $table->timestamps();
            $table->string('avatar_path', 255)->nullable();
            $table->text('bio')->nullable();
            $table->string('user_type', 20)->comment('artist or visitor');
            $table->string('website_url', 255)->nullable();
            $table->json('social_media')->nullable();
            $table->string('reset_password_token', 100)->nullable();
            $table->timestamp('reset_password_token_expires_at')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('users');
    }
};
