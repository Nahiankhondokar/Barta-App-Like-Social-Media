<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Comment extends Model
{
    protected $guarded = [];

    
    public function posts(): HasMany
    {
        return $this->hasMany(Post::class, 'id', 'post_id');
    }

    public function users(): HasMany
    {
        return $this->hasMany(User::class, 'id', 'user_id');
    }
}
