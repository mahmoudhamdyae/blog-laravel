<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Post extends Model
{

    use HasFactory;
    protected $fillable = ['title', 'description', 'user_id'];

    public function user() { // SAME: return foreign key
        return $this->belongsTo(User::class);
    }

    // We had to put foreign key when  name is not the same as foreign key column
    // public function postCreator() {
    //     return $this->belongsTo(User::class, 'user_id');
    // }
}
