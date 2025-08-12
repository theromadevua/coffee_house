<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use OpenApi\Attributes as OA;

/**
 * @OA\Schema(
 *      schema="Category",
 *      title="Category Model",
 *      description="Represents a category in the system",
 *      type="object",
 *      @OA\Property(
 *          property="id",
 *          description="The unique identifier of the category",
 *          type="integer",
 *          format="int64",
 *          readOnly=true,
 *          example=1
 *      ),
 *      @OA\Property(
 *          property="name",
 *          description="The name of the category",
 *          type="string",
 *          example="Main Courses"
 *      ),
 *      @OA\Property(
 *          property="description",
 *          description="A brief description of the category",
 *          type="string",
 *          nullable=true,
 *          example="Hearty and satisfying main dishes."
 *      ),
 *      @OA\Property(
 *          property="created_at",
 *          description="Timestamp of when the category was created",
 *          type="string",
 *          format="date-time",
 *          readOnly=true,
 *          example="2023-01-01T12:00:00.000000Z"
 *      ),
 *      @OA\Property(
 *          property="updated_at",
 *          description="Timestamp of the last update to the category",
 *          type="string",
 *          format="date-time",
 *          readOnly=true,
 *          example="2023-01-01T12:30:00.000000Z"
 *      )
 * )
 *
 * @property int $id
 * @property string $name
 * @property string|null $description
 */
class Category extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'description',
    ];

    public function dishes(): HasMany
    {
        return $this->hasMany(Dish::class);
    }
}
