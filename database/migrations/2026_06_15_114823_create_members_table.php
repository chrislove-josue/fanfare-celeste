<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('members', function (Blueprint $table) {
            $table->id();
            $table->string('member_number')->unique();
            $table->string('first_name');
            $table->string('last_name');
            $table->string('email')->unique();
            $table->string('phone');
            $table->string('whatsapp')->nullable();
            $table->text('address');
            $table->date('birth_date');
            $table->string('birth_place')->nullable();
            $table->string('instrument')->nullable();
            $table->enum('instrument_level', ['debutant', 'intermediaire', 'avance', 'expert'])->nullable();
            $table->date('entry_date');
            $table->string('section')->nullable();
            $table->string('section_role')->nullable();
            $table->string('photo')->nullable();
            $table->enum('status', ['pending', 'active', 'suspended', 'withdrawn', 'deceased', 'training'])->default('pending');
            $table->text('id_document')->nullable();
            $table->string('emergency_contact_name')->nullable();
            $table->string('emergency_contact_phone')->nullable();
            $table->text('medical_notes')->nullable();
            $table->text('skills')->nullable();
            $table->text('notes')->nullable();
            $table->timestamps();
            
            $table->index('member_number');
            $table->index('status');
            $table->index('section');
            $table->index('instrument');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('members');
    }
};