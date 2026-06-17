<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('events', function (Blueprint $table) {
            $table->id();
            $table->string('title');
            $table->text('description');
            $table->enum('type', ['concert', 'entertainment', 'official_outing', 'rehearsal', 'evangelical_campaign', 'special', 'wedding', 'funeral', 'festival']);
            $table->dateTime('start_date');
            $table->dateTime('end_date');
            $table->string('location');
            $table->string('location_url')->nullable();
            $table->foreignId('responsible_id')->constrained('users');
            $table->decimal('budget', 12, 2)->nullable();
            $table->decimal('actual_cost', 12, 2)->nullable();
            $table->enum('status', ['planned', 'upcoming', 'ongoing', 'completed', 'cancelled'])->default('planned');
            $table->text('notes')->nullable();
            $table->string('poster_path')->nullable();
            $table->timestamps();
            
            $table->index('start_date');
            $table->index('status');
            $table->index('type');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('events');
    }
};