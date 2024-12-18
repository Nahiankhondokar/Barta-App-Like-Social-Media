<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Like extends Model
{
    protected $guarded = [];

    public function post(): HasMany
    {
        return $this->hasMany(Post::class);
    }

    public function user(): HasMany
    {
        return $this->hasMany(User::class);
    }
}
