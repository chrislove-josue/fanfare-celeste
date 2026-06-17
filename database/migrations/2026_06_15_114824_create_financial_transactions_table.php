<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('financial_transactions', function (Blueprint $table) {
            $table->id();
            $table->foreignId('member_id')->nullable()->constrained('members')->nullOnDelete();
            $table->enum('type', ['income', 'expense']);
            $table->string('category');
            $table->decimal('amount', 12, 2);
            $table->text('description');
            $table->date('transaction_date');
            $table->enum('status', ['pending', 'approved', 'rejected'])->default('pending');
            $table->foreignId('approved_by')->nullable()->constrained('users');
            $table->timestamp('approved_at')->nullable();
            $table->string('receipt_path')->nullable();
            $table->string('reference')->nullable();
            $table->text('rejection_reason')->nullable();
            $table->timestamps();
            
            $table->index('transaction_date');
            $table->index('status');
            $table->index('type');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('financial_transactions');
    }
};