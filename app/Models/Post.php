<?php

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Casts\Attribute;

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

    protected function image(): Attribute
    {
        return Attribute::make(
             get: function($value){
               return $value == null ? "" : 'storage/'.$value;
            }
        );
    }
}
