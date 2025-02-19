<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Blog extends Model
{  protected $fillable = ['posts','user_id'];


    public function comments()
    {
        return $this->hasMany(Comment::class);
    }

    // A Blog belongs to a User
    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
