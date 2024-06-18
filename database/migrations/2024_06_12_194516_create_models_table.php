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
        Schema::create('models', function (Blueprint $table) {
            $table->id();
            $table->timestamps();
            $table->string('cover_image_url')->nullable();
            $table->text('description')->nullable();
            $table->string('github_url')->nullable();
            $table->string('license_url')->nullable();
            $table->boolean('user_fine_tune')->default(false);
            $table->string('type')->nullable();
            $table->bigInteger('user_id')->nullable();
            $table->string('provider');
            $table->string('name');
            $table->string('owner');
            $table->string('paper_url')->nullable();
            $table->bigInteger('run_count')->nullable();
            $table->string('url')->nullable();
            $table->string('visibility')->default('public');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('models');
    }
};
