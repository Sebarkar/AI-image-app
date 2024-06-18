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
        Schema::create('user_models', function (Blueprint $table) {
            $table->id();
            $table->timestamps();
            $table->string('cover_image_url')->nullable();
            $table->text('description')->nullable();
            $table->boolean('user_fine_tune')->default(true);
            $table->string('type')->nullable();
            $table->bigInteger('user_id');
            $table->string('provider');
            $table->string('name');
            $table->string('owner');
            $table->bigInteger('trained_on_model_id');
            $table->bigInteger('run_count')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('user_models');
    }
};
