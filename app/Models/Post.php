<?php

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

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
}
