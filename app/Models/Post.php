<?php

namespace App\Models;

use Carbon\Carbon;
use Cviebrock\EloquentSluggable\Sluggable;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Spatie\Tags\HasTags;

class Post extends Model
{
    use HasFactory, HasTags, Sluggable, SoftDeletes;

    protected $fillable = ['title', 'title_ar', 'description', 'description_ar', 'user_id', 'image'];

    protected function title(): Attribute
    {
        return Attribute::make(
            get: function (?string $value, array $attributes) {
                if (app()->getLocale() === 'ar' && ! empty($attributes['title_ar'])) {
                    return $attributes['title_ar'];
                }

                return $value;
            }
        );
    }

    protected function description(): Attribute
    {
        return Attribute::make(
            get: function (?string $value, array $attributes) {
                if (app()->getLocale() === 'ar' && ! empty($attributes['description_ar'])) {
                    return $attributes['description_ar'];
                }

                return $value;
            }
        );
    }

    public function user() // SAME: return foreign key
    {
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

    protected static function booted()
    {
        static::deleting(function ($post) {
            // سيتم تنفيذ هذا الكود تلقائياً قبل حذف أي بوست
            $post->comments()->delete();
        });
    }

    protected function humanReadableDate(): Attribute
    {
        return Attribute::make(
            get: fn (mixed $value, array $attributes) => Carbon::parse($attributes['created_at'])->diffForHumans(),
        );
    }

    /**
     * Return the sluggable configuration array for this model.
     */
    public function sluggable(): array
    {
        return [
            'slug' => [
                'source' => 'title',
            ],
        ];
    }
}
