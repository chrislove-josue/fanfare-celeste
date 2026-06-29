<?php
// database/migrations/xxxx_xx_xx_xxxxxx_create_galleries_table.php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('galleries', function (Blueprint $table) {
            $table->id();
            
            // Informations principales
            $table->string('title');
            $table->text('description')->nullable();
            
            // Catégorie et type
            $table->enum('category', [
                'concerts', 
                'rehearsals', 
                'trophies', 
                'events', 
                'members', 
                'ceremonies',
                'group'
            ])->default('events');
            
            $table->enum('type', ['photo', 'video'])->default('photo');
            
            // Fichier
            $table->string('file_path');
            $table->string('file_name')->nullable();
            $table->string('file_size')->nullable();
            $table->string('mime_type')->nullable();
            
            // Statistiques
            $table->integer('views_count')->default(0);
            $table->integer('likes_count')->default(0);
            $table->integer('downloads_count')->default(0);
            
            // Publication
            $table->boolean('is_published')->default(true);
            $table->timestamp('published_at')->nullable();
            $table->integer('order')->default(0);
            
            // Membre associé (optionnel)
            $table->foreignId('member_id')->nullable()->constrained('members')->onDelete('set null');
            
            // Métadonnées
            $table->string('alt_text')->nullable();
            $table->json('metadata')->nullable(); // Pour stocker des infos supplémentaires
            
            $table->timestamps();
            $table->softDeletes();
            
            // Index pour améliorer les performances
            $table->index(['category', 'is_published', 'order']);
            $table->index('type');
            $table->index('created_at');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('galleries');
    }
};