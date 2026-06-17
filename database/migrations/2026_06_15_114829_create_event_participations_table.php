<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('event_participations', function (Blueprint $table) {
            $table->id();
            $table->foreignId('event_id')->constrained('events')->onDelete('cascade');
            $table->foreignId('member_id')->constrained('members')->onDelete('cascade');
            $table->enum('attendance_status', ['confirmed', 'present', 'absent', 'late', 'excused'])->default('confirmed');
            $table->time('arrival_time')->nullable();
            $table->text('notes')->nullable();
            $table->boolean('transport_provided')->default(false);
            $table->boolean('meal_provided')->default(false);
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('event_participations');
    }
};