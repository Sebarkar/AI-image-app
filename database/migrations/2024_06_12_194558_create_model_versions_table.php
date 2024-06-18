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
        Schema::create('model_versions', function (Blueprint $table) {
            $table->id();
            $table->string('cog_version')->nullable();
            $table->string('created_at');
            $table->string('schema')->default('openapi');
            $table->string('schema_version')->nullable();
            $table->bigInteger('user_id')->nullable();
            $table->bigInteger('model_id');
            $table->jsonb('schemas');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('model_versions');
    }
};
