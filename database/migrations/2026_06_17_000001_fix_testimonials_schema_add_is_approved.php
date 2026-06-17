<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('testimonials', function (Blueprint $table) {
            if (!Schema::hasColumn('testimonials', 'is_approved')) {
                $table->boolean('is_approved')->default(false)->after('id');
            }

            // Some queries order by created_at; ensure timestamps exist.
        });
    }

    public function down(): void
    {
        Schema::table('testimonials', function (Blueprint $table) {
            if (Schema::hasColumn('testimonials', 'is_approved')) {
                $table->dropColumn('is_approved');
            }
        });
    }
};

