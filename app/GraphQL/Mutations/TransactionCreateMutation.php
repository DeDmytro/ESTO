<?php

declare(strict_types=1);

namespace App\GraphQL\Mutations;

use App\Models\Transaction;
use Closure;
use GraphQL;
use GraphQL\Type\Definition\ResolveInfo;
use GraphQL\Type\Definition\Type;
use Rebing\GraphQL\Support\Mutation;

class TransactionCreateMutation extends Mutation
{
    /**
     * {@inheritDoc}
     * @var array
     */
    protected $attributes = [
        'name' => 'Create new transaction',
        'model' => Transaction::class
    ];

    /**
     * {@inheritDoc}
     * @return Type
     */
    public function type(): Type
    {
        return GraphQL::type('transaction');
    }

    /**
     * {@inheritDoc}
     * @return array
     */
    public function args(): array
    {
        return [
            'amount' => [
                'type' => Type::nonNull(Type::int()),
                'rules' => ['required', 'integer']
            ],
            'type_id' => [
                'type' => Type::nonNull(Type::int()),
                'rules' => ['required', 'between:0,1']
            ]
        ];
    }

    /**
     * {@inheritDoc}
     * @param $root
     * @param $args
     * @param $context
     * @param ResolveInfo $resolveInfo
     * @param Closure $getSelectFields
     * @return array
     */
    public function resolve($root, $args, $context, ResolveInfo $resolveInfo, Closure $getSelectFields)
    {
        $amount = intval($args['amount']) / 100;
        $typeId = $args['type_id'];
        $wallet = auth()->user()->wallet;

        return $typeId == Transaction::TYPE_DEBIT ? $wallet->debit($amount) : $wallet->credit($amount);
    }
}
