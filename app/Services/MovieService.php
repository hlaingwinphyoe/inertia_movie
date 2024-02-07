<?php

namespace App\Services;

use App\Models\Movie;
use App\Models\Status;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class MovieService
{
    public function store(array $paramData = [])
    {
        $movie = new Movie();

        $movie->title = $paramData['title'];
        $movie->description = $paramData['description'] ?? '';
        $movie->excerpt = Str::excerpt($paramData['description']);
        $movie->casts = $paramData['casts'] ?? '';
        $movie->director = $paramData['director'] ?? '';
        $movie->release_year = $paramData['release_year'] ?? '';
        $movie->running_time = $paramData['running_time'] ?? '';
        $movie->video_quality = $paramData['video_quality'] ?? '';
        $movie->country_id = $paramData['country_id'] ?? '';
        $movie->type_id = $paramData['type_id'] ?? '';
        $movie->rating = $paramData['rating'] ?? 0;
        $movie->is_published = true;
        $movie->user_id = Auth::id();

        $movie->save();

        $movie->categories()->sync($paramData['categories']);

        return $movie;
    }

    public function update(object $model, array $paramData = [])
    {
        $model->title = $paramData['title'];
        $model->description = $paramData['description'] ?? '';
        $model->excerpt = Str::excerpt($paramData['description']);
        $model->casts = $paramData['casts'] ?? '';
        $model->director = $paramData['director'] ?? '';
        $model->release_year = $paramData['release_year'] ?? '';
        $model->running_time = $paramData['running_time'] ?? '';
        $model->video_quality = $paramData['video_quality'] ?? '';
        $model->country_id = $paramData['country_id'] ?? '';
        $model->rating = $paramData['rating'] ?? 0;
        $model->type_id = $paramData['type_id'] ?? '';

        $model->update();

        $model->categories()->sync($paramData['categories']);

        return $model;
    }

    public function destroyImage(object $model)
    {
        //image
        if (public_path($model->thumbnail)) {
            File::delete(public_path($model->thumbnail));
        }
    }

    public function destroyVideo(object $model)
    {
        //video
        if (public_path($model->video)) {
            File::delete(public_path($model->video));
        }
    }
}
