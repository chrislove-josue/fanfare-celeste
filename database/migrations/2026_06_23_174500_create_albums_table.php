<?php
// database/migrations/xxxx_xx_xx_xxxxxx_create_albums_table.php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('albums', function (Blueprint $table) {
            $table->id();
            $table->string('title');
            $table->string('slug')->unique();
            $table->text('description')->nullable();
            $table->string('cover_image')->nullable();
            $table->enum('category', [
                'concerts', 
                'rehearsals', 
                'trophies', 
                'events', 
                'members', 
                'ceremonies',
                'group'
            ])->default('events');
            $table->boolean('is_published')->default(true);
            $table->date('event_date')->nullable();
            $table->string('location')->nullable();
            $table->integer('order')->default(0);
            $table->integer('views_count')->default(0);
            $table->integer('likes_count')->default(0);
            $table->foreignId('created_by')->nullable()->constrained('users')->onDelete('set null');
            $table->timestamps();
            $table->softDeletes();

            // Index
            $table->index(['category', 'is_published', 'order']);
            $table->index('slug');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('albums');
    }
};