<?php

declare(strict_types=1);

namespace App\GraphQL\Types;

use App\Models\Transaction;
use GraphQL;
use GraphQL\Type\Definition\Type;
use Rebing\GraphQL\Support\Type as GraphQLType;

class TransactionType extends GraphQLType
{
    /**
     * {@inheritDoc}
     * @var array
     */
    protected $attributes = [
        'name' => 'Transaction',
        'model' => Transaction::class
    ];

    /**
     * {@inheritDoc}
     * @return array
     */
    public function fields(): array
    {
        return [
            'id' => [
                'type' => Type::nonNull(Type::int()),
                'description' => 'The id of the transaction'
            ],
            'user_id' => [
                'type' => Type::int(),
                'description' => 'The user id of the user'
            ],
            'type_id' => [
                'type' => Type::int(),
                'description' => 'The id of transaction type, 0 or 1'
            ],
            'amount' => [
                'type' => Type::int(),
                'description' => 'The amount of transaction'
            ],
            'user' => [
                'type' => GraphQL::type('user'),
                'description' => 'Related user',
            ],
        ];
    }
}
