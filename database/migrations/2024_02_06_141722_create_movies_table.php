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
        Schema::create('movies', function (Blueprint $table) {
            $table->id();
            $table->string('slug')->unique();
            $table->string('title', 1024);
            $table->text('excerpt');
            $table->longText('description');
            $table->text('casts');
            $table->string('director');
            $table->string('release_year');
            $table->text('video')->nullable();
            $table->text('thumbnail')->nullable();
            $table->integer('size')->nullable();
            $table->double('rating', 2, 1)->default(0);
            $table->bigInteger('movie_views')->unsigned()->default(0)->index();
            $table->string('video_quality');
            $table->string('running_time')->nullable();
            $table->boolean('is_published')->default(false);
            $table->foreignId('type_id')->constrained()->cascadeOnDelete();
            $table->foreignId('country_id')->constrained()->cascadeOnDelete();
            $table->foreignId('user_id')->constrained()->cascadeOnDelete();
            $table->timestamps();
        });

        Schema::create('movie_cates', function (Blueprint $table) {
            $table->foreignId('category_id')->constrained()->cascadeOnDelete();
            $table->foreignId('movie_id')->constrained()->cascadeOnDelete();

            $table->primary(['category_id', 'movie_id'], 'category_movie_key');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('movies');
    }
};
