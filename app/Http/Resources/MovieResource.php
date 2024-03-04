<?php

namespace App\Http\Resources;

use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class MovieResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'slug' => $this->slug,
            'title' => $this->title,
            'casts' => $this->movie_casts,
            'genres' => $this->getGenres(),
            'views' => $this->views,
            'release_date' => Carbon::parse($this->release_date)->format('d/M/Y'),
            'release_year' => Carbon::parse($this->release_date)->format('Y'),
            'created_at' => $this->created_at->diffForHumans(),
            'thumbnail' => $this->thumbnail,
            'rating' => $this->rating,
            'excerpt' => $this->excerpt,
            'description' => $this->description,
            'video_quality' => $this->video_quality,
            'views' => $this->views,
            'running_time' => $this->running_time,
            'director' => $this->director,
            'country' => $this->country ? $this->country->name : null,
            'trailer_video' => $this->trailer_video,
            'photos' => $this->medias ? $this->medias : [],
        ];
    }
}
