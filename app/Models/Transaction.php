<?php

namespace App\Models;

use App\User;
use Eloquent;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Support\Carbon;

/**
 * App\Models\Transaction
 *
 * @property int $id
 * @property int $user_id
 * @property float $amount
 * @property int $type_id
 * @property Carbon|null $created_at
 * @property Carbon|null $updated_at
 * @property-read User $user
 * @method static Builder|Transaction newModelQuery()
 * @method static Builder|Transaction newQuery()
 * @method static Builder|Transaction query()
 * @method static Builder|Transaction whereAmount($value)
 * @method static Builder|Transaction whereCreatedAt($value)
 * @method static Builder|Transaction whereId($value)
 * @method static Builder|Transaction whereTypeId($value)
 * @method static Builder|Transaction whereUpdatedAt($value)
 * @method static Builder|Transaction whereUserId($value)
 * @mixin Eloquent
 */
class Transaction extends Model
{
    /**
     * {@inheritdoc}
     * @var array
     */
    protected $fillable = [
        'user_id',
        'amount',
        'type_id',
    ];

    /**
     * {@inheritdoc}
     * @var array
     */
    protected $casts = [
        'amount' => 'float'
    ];

    /**
     * Transaction types const
     * @const int
     */
    const TYPE_DEBIT = 0;
    const TYPE_CREDIT = 1;

    /**
     * Return related user
     * @return BelongsTo
     */
    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
