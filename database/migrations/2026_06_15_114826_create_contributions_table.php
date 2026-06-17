<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('contributions', function (Blueprint $table) {
            $table->id();
            $table->foreignId('member_id')->constrained('members')->onDelete('cascade');
            $table->enum('type', ['monthly', 'special', 'donation']);
            $table->decimal('amount', 10, 2);
            $table->integer('month')->nullable();
            $table->integer('year')->nullable();
            $table->date('payment_date');
            $table->string('payment_method')->nullable();
            $table->string('transaction_id')->nullable();
            $table->string('receipt_number')->unique();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('contributions');
    }
};