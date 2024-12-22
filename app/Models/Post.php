<?php

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;

class Post extends Model
{
    protected $guarded = [];

    public function setCreatedAtAttribute($value)
    {
        // Customize the created_at value here
        $this->attributes['created_at'] = Carbon::now();
    }

    protected function casts(): array
    {
        return [
            'created_at'    => 'datetime'
        ];
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function like(): HasOne
    {
        return $this->hasOne(Like::class, 'post_id', 'id')
        ->select('id', 'user_id', 'post_id', 'like_status');
    }

    protected function image(): Attribute
    {
        return Attribute::make(
             get: function($value){
               return $value == null ? "" : 'storage/'.$value;
            }
        );
    }
}
