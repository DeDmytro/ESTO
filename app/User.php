<?php

namespace App;

use App\Models\Transaction;
use App\Services\UserTransactionsService;
use Eloquent;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Query\JoinClause;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\DatabaseNotification;
use Illuminate\Notifications\DatabaseNotificationCollection;
use Illuminate\Notifications\Notifiable;
use Illuminate\Support\Carbon;

/**
 * App\User
 *
 * @property int $id
 * @property string $name
 * @property string $email
 * @property Carbon|null $email_verified_at
 * @property int $role_id
 * @property string $password
 * @property string|null $remember_token
 * @property Carbon|null $created_at
 * @property Carbon|null $updated_at
 * @property-read DatabaseNotificationCollection|DatabaseNotification[] $notifications
 * @property-read int|null $notifications_count
 * @property-read Collection|Transaction[] $transactions
 * @property-read int|null $transactions_count
 * @method static Builder|User newModelQuery()
 * @method static Builder|User newQuery()
 * @method static Builder|User query()
 * @method static Builder|User whereCreatedAt($value)
 * @method static Builder|User whereEmail($value)
 * @method static Builder|User whereEmailVerifiedAt($value)
 * @method static Builder|User whereId($value)
 * @method static Builder|User whereName($value)
 * @method static Builder|User wherePassword($value)
 * @method static Builder|User whereRememberToken($value)
 * @method static Builder|User whereRoleId($value)
 * @method static Builder|User whereUpdatedAt($value)
 * @mixin Eloquent
 * @property-read bool $is_admin
 * @property-read UserTransactionsService $wallet
 * @method static Builder|User withDebit()
 */
class User extends Authenticatable
{
    use Notifiable;

    /**
     * The attributes that are mass assignable.
     * @var array
     */
    protected $fillable = [
        'name',
        'email',
        'password',
        'role_id'
    ];

    /**
     * The attributes that should be hidden for arrays.
     * @var array
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * The attributes that should be cast to native types.
     * @var array
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
        'debit' => 'float',
    ];

    /**
     * User roles const
     * @var int
     */
    const ROLE_USER = 0;
    const ROLE_ADMIN = 1;

    /**
     * Return latest transactions
     * @return HasMany
     */
    public function transactions()
    {
        return $this->hasMany(Transaction::class)->latest();
    }

    /**
     * Return whether current user is admin
     * @return bool
     */
    public function getIsAdminAttribute()
    {
        return $this->role_id == self::ROLE_ADMIN;
    }

    /**
     * Return user wallet
     * @return UserTransactionsService
     */
    public function getWalletAttribute()
    {
        return app(UserTransactionsService::class, ['user' => $this]);
    }

    /**
     * Return users with balance attribute (sum of all debit transactions)
     * @param Builder $query
     * @return Builder|\Illuminate\Database\Query\Builder
     */
    public function scopeWithDebit(Builder $query)
    {
        return $query->latest()->leftJoin('transactions', function (JoinClause $join) {
            $join->on('transactions.user_id', '=', 'users.id')->where('transactions.type_id', Transaction::TYPE_DEBIT);
        })->groupBy('users.id')->selectRaw('users.*, SUM(transactions.amount) AS debit');
    }
}
