<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

/**
 * @property int $dish_id
 * @property int $user_id
 * @property int $rating
 */
class Rating extends Model
{
    protected $fillable = ['dish_id', 'user_id', 'rating'];

    public function dish(): BelongsTo
    {
        return $this->belongsTo(Dish::class);
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }
}
