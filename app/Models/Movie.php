<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\MorphToMany;
use Spatie\Sluggable\HasSlug;
use Spatie\Sluggable\SlugOptions;

class Movie extends Model
{
    use HasFactory, HasSlug;

    protected $guarded = [];

    public function getSlugOptions(): SlugOptions
    {
        return SlugOptions::create()
            ->generateSlugsFrom('title')
            ->saveSlugsTo('slug');
    }

    public function genres(): BelongsToMany
    {
        return $this->belongsToMany(Genre::class, 'movie_genres', 'movie_id', 'genre_id');
    }

    public function tag(): BelongsTo
    {
        return $this->belongsTo(Tag::class);
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }
    public function country(): BelongsTo
    {
        return $this->belongsTo(Country::class);
    }

    public function status(): BelongsTo
    {
        return $this->belongsTo(Status::class);
    }

    public function movie_casts(): BelongsToMany
    {
        return $this->belongsToMany(Cast::class, 'movie_casts', 'movie_id', 'cast_id');
    }

    public function medias(): MorphToMany
    {
        return $this->morphToMany(Media::class, 'mediabble');
    }

    public function getGenres()
    {
        $name = '';

        foreach ($this->genres as $genre) {
            $name .= $name ? ', ' : '';
            $name .= $genre->name;
        }

        return $name;
    }

    public function scopePublished($q)
    {
        $q->where('is_published', 1);
    }

    public function scopeFilterOn($q)
    {
        if (request('search')) {
            $q->where('title', 'like', "%" . request('search') . "%");
        }

        if (request('q') == 'This Week') {
            $q->whereBetween('created_at', [now()->subDays(6), now()]);
        } else {
            $q->orderBy('created_at', 'desc');
        }
    }
}
