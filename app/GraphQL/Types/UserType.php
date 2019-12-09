<?php

declare(strict_types=1);

namespace App\GraphQL\Types;

use App\User;
use GraphQL\Type\Definition\Type;
use Rebing\GraphQL\Support\Type as GraphQLType;

class UserType extends GraphQLType
{
    /**
     * {@inheritDoc}
     * @var array
     */
    protected $attributes = [
        'name' => 'User',
        'model' => User::class
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
                'description' => 'The id of the user'
            ],
            'email' => [
                'type' => Type::string(),
                'description' => 'The email of user',
                'resolve' => function ($root, $args) {
                    return strtolower($root->email);
                }
            ],
            'name' => [
                'type' => Type::string(),
                'description' => 'The name of the user'
            ],
            'role_id' => [
                'type' => Type::int(),
                'description' => 'The role id of the user'
            ],
            'isAdmin' => [
                'type' => Type::boolean(),
                'description' => 'True, if the queried user is admin',
                'selectable' => false,
            ],
            'debit' => [
                'type' => Type::float(),
                'description' => 'User debit sum',
                'selectable' => false,
            ]
        ];
    }

}
