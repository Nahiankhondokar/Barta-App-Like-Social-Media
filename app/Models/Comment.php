<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;

class Comment extends Model
{
    protected $guarded = [];

    
    public function posts(): HasOne
    {
        return $this->hasOne(Post::class, 'id', 'post_id');
    }

    public function users(): HasOne
    {
        return $this->hasOne(User::class, 'id', 'user_id');
    }
}
