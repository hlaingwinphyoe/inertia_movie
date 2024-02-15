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
        Schema::create('statuses', function (Blueprint $table) {
            $table->id();
            $table->string('slug')->unique();
            $table->string('name');
            $table->string('type')->default('status');
            $table->timestamps();
        });

        Schema::create('countries', function (Blueprint $table) {
            $table->id();
            $table->string('slug')->unique();
            $table->string('country_code');
            $table->string('name');
            $table->integer('priority')->default(999);
            $table->timestamps();
        });

        Schema::create('genres', function (Blueprint $table) {
            $table->id();
            $table->bigInteger('tmdb_id')->unique()->nullable();
            $table->string('slug');
            $table->string('name')->unique();
            $table->integer('priority')->default(999);
            $table->timestamps();
        });

        Schema::create('tags', function (Blueprint $table) {
            $table->id();
            $table->string('name')->unique();
            $table->string('slug');
            $table->integer('priority')->default(999);
            $table->timestamps();
        });

        Schema::create('taggables', function (Blueprint $table) {
            $table->integer("tag_id");
            $table->integer("taggable_id");
            $table->string("taggable_type");
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('genres');
        Schema::dropIfExists('tags');
        Schema::dropIfExists('taggables');
    }
};
