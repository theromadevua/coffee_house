<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

/**
 * @property int $id
 * @property int $user_id
 *
 * @property-read User $user
 * @property-read \Illuminate\Database\Eloquent\Collection|Dish[] $items
 */
class Cart extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    /**
     * @return BelongsToMany<Dish, Cart, \Illuminate\Database\Eloquent\Relations\Pivot>
     */
    public function items(): BelongsToMany
    {
        return $this->belongsToMany(Dish::class, 'cart_items')
            ->withPivot('quantity') 
            ->withTimestamps();
    }
}