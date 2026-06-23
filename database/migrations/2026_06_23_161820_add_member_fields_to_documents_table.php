<?php
// database/migrations/xxxx_xx_xx_xxxxxx_add_member_fields_to_documents_table.php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('documents', function (Blueprint $table) {
            $table->foreignId('member_id')->nullable()->after('uploaded_by')->constrained('members')->onDelete('cascade');
            $table->enum('submission_type', ['admin', 'member', 'applicant'])->default('admin')->after('member_id');
            $table->enum('status', ['pending', 'approved', 'rejected'])->default('pending')->after('submission_type');
            $table->text('rejection_reason')->nullable()->after('status');
            $table->timestamp('approved_at')->nullable()->after('rejection_reason');
        });
    }

    public function down(): void
    {
        Schema::table('documents', function (Blueprint $table) {
            $table->dropForeign(['member_id']);
            $table->dropColumn(['member_id', 'submission_type', 'status', 'rejection_reason', 'approved_at']);
        });
    }
};