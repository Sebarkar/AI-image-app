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
        Schema::create('bots', function (Blueprint $table) {
            $table->id();
            $table->timestamps();
            $table->string('key');
            $table->string('target');
            $table->string('provider');
            $table->string('queue')->default('basic_queue');
            $table->integer('max_connections')->default(3);
            $table->integer('connections')->default(0);
            $table->integer('connection_left')
                ->virtualAs('max_connections - connections');
            $table->boolean('status')->default(true);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('bots');
    }
};
