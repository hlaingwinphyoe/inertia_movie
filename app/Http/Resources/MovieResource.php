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
            'title' => $this->title,
            'casts' => $this->movie_casts,
            'genres' => $this->genres,
            'views' => $this->views,
            'release_date' => $this->release_date,
            'release_year' => Carbon::parse($this->release_date)->format('Y'),
            'created_at' => $this->created_at->diffForHumans(),
            'thumbnail' => $this->thumbnail,
            'rating' => $this->rating,
            'description' => $this->description,
            'video_quality' => $this->video_quality,
            'views' => $this->views,
            'running_time' => $this->running_time,
            'director' => $this->director,
            'country' => $this->country ? $this->country->name : null,
        ];
    }
}
