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
        Schema::table('tasks', function (Blueprint $table) {
            $table->bigInteger('dataset_id')->nullable();
        });

        Schema::table('datasets', function (Blueprint $table) {
            $table->unsignedBigInteger('zip_id')->nullable();
        });

        Schema::table('files', function (Blueprint $table) {
            $table->renameColumn('task_id', 'target_id');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('datasets');
    }
};
