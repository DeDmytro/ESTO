<?php

declare(strict_types=1);

namespace App\GraphQL\Queries;

use App\User;
use Closure;
use GraphQL;
use GraphQL\Type\Definition\ResolveInfo;
use GraphQL\Type\Definition\Type;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Collection;
use Rebing\GraphQL\Support\Query;

class UsersQuery extends Query
{
    /**
     * {@inheritDoc}
     * @var array
     */
    protected $attributes = [
        'name' => 'Users query',
    ];

    /**
     * {@inheritDoc}
     * @return Type
     */
    public function type(): Type
    {
        return Type::listOf(GraphQL::type('user'));
    }

    /**
     * {@inheritDoc}
     * @return array
     */
    public function args(): array
    {
        return [
            'id' => [
                'name' => 'id',
                'type' => Type::int()
            ],
            'email' => [
                'name' => 'email',
                'type' => Type::string()
            ],
            'limit' => [
                'name' => 'limit',
                'type' => Type::int()
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
     * @return User[]|Builder[]|Collection
     */
    public function resolve($root, $args, $context, ResolveInfo $resolveInfo, Closure $getSelectFields)
    {
        $query = User::withDebit();

        if (isset($args['id'])) {
            $query->where('id', $args['id']);
        }

        if (isset($args['email'])) {
            $query->where('email', $args['email']);
        }

        if (isset($args['limit'])) {
            $query->take($args['limit']);
        }

        return $query->get();
    }
}
