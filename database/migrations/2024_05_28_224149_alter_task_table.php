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
            $table->string('provider');
            $table->string('status')->default('started');
            $table->string('model')->nullable();
            $table->string('version')->nullable();
            $table->string('provider_id');
            $table->string('error')->nullable();
            $table->jsonb('last_response')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        //
    }
};
