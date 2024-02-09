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
        Schema::create('casts', function (Blueprint $table) {
            $table->id();
            $table->bigInteger('tmdb_id')->unique()->nullable();
            $table->string('name');
            $table->string('slug');
            $table->string('profile')->nullable();
            $table->date('birthday')->nullable();
            $table->string('gender');
            $table->longText('biography')->nullable();
            $table->timestamps();
        });

        Schema::create('movie_casts', function (Blueprint $table) {
            $table->foreignId('cast_id')->constrained()->cascadeOnDelete();
            $table->foreignId('movie_id')->constrained()->cascadeOnDelete();

            $table->primary(['cast_id', 'movie_id'], 'cast_movie_key');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('casts');
        Schema::dropIfExists('movie_casts');
    }
};
