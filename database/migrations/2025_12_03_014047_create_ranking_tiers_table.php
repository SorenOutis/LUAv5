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
        Schema::create('ranking_tiers', function (Blueprint $table) {
            $table->id();
            $table->string('name')->unique();
            $table->string('icon')->default('📊');
            $table->string('color')->default('#999999');
            $table->integer('min_rank');
            $table->integer('max_rank')->nullable();
            $table->text('description')->nullable();
            $table->integer('sort_order')->default(0);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('ranking_tiers');
    }
};
