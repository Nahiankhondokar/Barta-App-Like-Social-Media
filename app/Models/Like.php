<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Like extends Model
{
    protected $guarded = [];

    const LIKE = 1;
    const UNLIKE = 0;

    public function posts(): HasMany
    {
        return $this->hasMany(Post::class, 'id', 'post_id');
    }

    public function users(): HasMany
    {
        return $this->hasMany(User::class, 'id', 'user_id');
    }
}
