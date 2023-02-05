<?php

namespace App\Models;

use Illuminate\Support\Str;
use Illuminate\Support\Carbon;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Book extends Model
{
    use HasFactory;

    protected $fillable = [
        'title',
        'author_id',
        'genre_id',
        'description',
        'isbn',
        'image',
        'published',
        'publisher_id'
    ];

    protected $casts = ['published' => 'date'];

    protected $appends = ['author_name', 'genre_name', 'publisher_name', 'short_description'];

    protected $with = ['author', 'genre', 'publisher'];

    public function author()
    {
        return $this->belongsTo(Author::class);
    }

    public function genre()
    {
        return $this->belongsTo(Genre::class);
    }

    public function publisher()
    {
        return $this->belongsTo(Publisher::class);
    }

    protected function authorName(): Attribute
    {
        return Attribute::make(
            get: fn () => $this->author->name
        );
    }

    protected function genreName(): Attribute
    {
        return Attribute::make(
            get: fn () => $this->genre->name
        );
    }

    protected function publisherName(): Attribute
    {
        return Attribute::make(
            get: fn () => $this->publisher->name
        );
    }

    protected function shortDescription(): Attribute
    {
        return Attribute::make(
            get: fn () => Str::limit($this->description, 60, $end = '...')
        );
    }

    protected function published(): Attribute
    {
        return Attribute::make(
            get: fn ($value) => Carbon::parse($value)->format('d-m-Y'),
            set: fn ($value) => Carbon::parse($value)->format('Y-m-d')
        );
    }

    protected function isbn(): Attribute
    {
        return Attribute::make(
            set: fn ($value) => str_replace("-", "", $value),
        );
    }
}
