<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Carbon\Carbon;
use Cviebrock\EloquentSluggable\Sluggable;

class Post extends Model
{

    use HasFactory, SoftDeletes, Sluggable;
    protected $fillable = ['title', 'description', 'user_id', 'image'];

    public function user() { // SAME: return foreign key
        return $this->belongsTo(User::class);
    }

    // We had to put foreign key when  name is not the same as foreign key column
    // public function postCreator() {
    //     return $this->belongsTo(User::class, 'user_id');
    // }
    public function comments()
    {
        return $this->morphMany(Comment::class, 'commentable');
    }

    protected static function booted() {
        static::deleting(function ($post) {
            // سيتم تنفيذ هذا الكود تلقائياً قبل حذف أي بوست
            $post->comments()->delete();
        });
    }

    protected function humanReadableDate(): Attribute
    {
        return Attribute::make(
            get: fn (mixed $value, array $attributes) =>
                Carbon::parse($attributes['created_at'])->diffForHumans(),
        );
    }

    /**
     * Return the sluggable configuration array for this model.
     *
     * @return array
     */
    public function sluggable(): array
    {
        return [
            'slug' => [
                'source' => 'title'
            ]
        ];
    }
}

