<?php
// database/migrations/xxxx_xx_xx_xxxxxx_create_media_table.php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('media', function (Blueprint $table) {
            $table->id();
            
            // Utiliser unsignedBigInteger pour la clé étrangère
            $table->unsignedBigInteger('album_id');
            
            $table->string('title')->nullable();
            $table->text('description')->nullable();
            $table->enum('type', ['photo', 'video'])->default('photo');
            $table->string('file_path');
            $table->string('file_name');
            $table->string('file_size')->nullable();
            $table->string('mime_type')->nullable();
            $table->string('alt_text')->nullable();
            $table->integer('order')->default(0);
            $table->integer('views_count')->default(0);
            $table->integer('likes_count')->default(0);
            $table->boolean('is_cover')->default(false);
            $table->boolean('is_published')->default(true);
            $table->json('metadata')->nullable();
            $table->timestamps();
            $table->softDeletes();

            // Index
            $table->index(['album_id', 'order']);
            $table->index('type');
            $table->index('is_published');

            // Clé étrangère
            $table->foreign('album_id')
                  ->references('id')
                  ->on('albums')
                  ->onDelete('cascade');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('media');
    }
};