<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('assignments', function (Blueprint $table) {
            if (!Schema::hasColumn('assignments', 'category_id')) {
                $table->foreignId('category_id')->nullable()->constrained('categories')->onDelete('set null');
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('assignments', function (Blueprint $table) {
            // Drop foreign key constraint first
            $table->dropForeign(['category_id']);
            if (Schema::hasColumn('assignments', 'category_id')) {
                $table->dropColumn('category_id');
            }
        });
    }
};
