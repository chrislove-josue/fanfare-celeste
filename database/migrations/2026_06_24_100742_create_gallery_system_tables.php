<?php
// database/migrations/xxxx_xx_xx_xxxxxx_create_gallery_system_tables.php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        // ============================================
        // 1. TABLE DES GALERIES (Albums)
        // ============================================
        Schema::create('galleries', function (Blueprint $table) {
            $table->id();
            
            // Informations principales
            $table->string('title');
            $table->string('slug')->unique();
            $table->text('description')->nullable();
            $table->string('cover_image')->nullable();
            
            // Catégorie
            $table->enum('category', [
                'concerts', 
                'rehearsals', 
                'trophies', 
                'events', 
                'members', 
                'ceremonies',
                'group'
            ])->default('events');
            
            // Publication
            $table->boolean('is_published')->default(true);
            $table->date('event_date')->nullable();
            $table->string('location')->nullable();
            $table->integer('order')->default(0);
            
            // Statistiques
            $table->integer('views_count')->default(0);
            $table->integer('likes_count')->default(0);
            
            // Utilisateur créateur
            $table->foreignId('created_by')->nullable()->constrained('users')->onDelete('set null');
            
            $table->timestamps();
            $table->softDeletes();

            // Index
            $table->index(['category', 'is_published', 'order']);
            $table->index('slug');
        });

        // ============================================
        // 2. TABLE DES MÉDIAS (Photos & Vidéos)
        // ============================================
        Schema::create('gallery_media', function (Blueprint $table) {
            $table->id();
            
            // Relation avec la galerie
            $table->foreignId('gallery_id')
                  ->constrained('galleries')
                  ->onDelete('cascade')
                  ->onUpdate('cascade');
            
            // Informations du média
            $table->string('title')->nullable();
            $table->text('description')->nullable();
            $table->enum('type', ['photo', 'video'])->default('photo');
            
            // Fichier
            $table->string('file_path');
            $table->string('file_name');
            $table->string('file_size')->nullable();
            $table->string('mime_type')->nullable();
            $table->string('alt_text')->nullable();
            
            // Organisation
            $table->integer('order')->default(0);
            $table->boolean('is_cover')->default(false);
            $table->boolean('is_published')->default(true);
            
            // Statistiques
            $table->integer('views_count')->default(0);
            $table->integer('likes_count')->default(0);
            
            // Métadonnées supplémentaires (JSON)
            $table->json('metadata')->nullable();
            
            $table->timestamps();
            $table->softDeletes();

            // Index
            $table->index(['gallery_id', 'order']);
            $table->index('type');
            $table->index('is_published');
        });

        // ============================================
        // 3. TABLE DES TAGS (Optionnelle)
        // ============================================
        Schema::create('gallery_tags', function (Blueprint $table) {
            $table->id();
            $table->string('name')->unique();
            $table->string('slug')->unique();
            $table->timestamps();
        });

        // ============================================
        // 4. TABLE DE LIAISON MÉDIAS-TAGS (Optionnelle)
        // ============================================
        Schema::create('gallery_media_tags', function (Blueprint $table) {
            $table->id();
            $table->foreignId('media_id')->constrained('gallery_media')->onDelete('cascade');
            $table->foreignId('tag_id')->constrained('gallery_tags')->onDelete('cascade');
            $table->timestamps();
            
            $table->unique(['media_id', 'tag_id']);
        });
    }

    public function down(): void
    {
        // Supprimer dans l'ordre inverse des dépendances
        Schema::dropIfExists('gallery_media_tags');
        Schema::dropIfExists('gallery_tags');
        Schema::dropIfExists('gallery_media');
        Schema::dropIfExists('galleries');
    }
};