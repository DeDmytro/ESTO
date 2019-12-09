<?php

namespace App\Services;

use App\Models\Transaction;
use App\User;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;

class UserTransactionsService
{
    /**
     * Current user
     * @var User
     */
    protected $user;

    /**
     * Transaction query
     * @var Builder
     */
    protected $query;

    /**
     * UserTransactionsService constructor.
     * @param User $user
     */
    public function __construct(User $user)
    {
        $this->user = $user;
        $this->query = $this->user->transactions();
    }

    /**
     * Create new debit transaction and return result model
     * @param float $amount
     * @return Model|Transaction
     */
    public function debit(float $amount)
    {
        return $this->query->create(['amount' => $amount, 'type_id' => Transaction::TYPE_DEBIT]);
    }

    /**
     *  Create new credit transaction and return result model
     * @param float $amount
     * @return Model|Transaction
     */
    public function credit(float $amount)
    {
        return $this->query->create(['amount' => $amount, 'type_id' => Transaction::TYPE_CREDIT]);
    }

    /**
     * Return balance (credit - debit)
     * @return float
     */
    public function balance()
    {
        $credit = $this->query->where('type_id', Transaction::TYPE_CREDIT)->sum('amount');
        $debit = $this->query->where('type_id', Transaction::TYPE_DEBIT)->sum('amount');
        return $credit - $debit;
    }
}
