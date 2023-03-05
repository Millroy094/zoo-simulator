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
        Schema::create('animals', function (Blueprint $table) {
            $table->id();
            $table->string("type");
            $table->string("name");
            $table->float("current_health")->default(100);
            $table->float("previous_health")->default(100);
            $table->string("status")->default("Alive");
            $table->dateTime("zoo_creation_time");
            $table->integer("life_span")->default(0);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('animals');
    }
};
