<?php

declare(strict_types=1);

namespace App\GraphQL\Mutations;

use App\User;
use Arr;
use Closure;
use GraphQL;
use GraphQL\Type\Definition\ResolveInfo;
use GraphQL\Type\Definition\Type;
use Rebing\GraphQL\Support\Mutation;

class UserCreateMutation extends Mutation
{
    /**
     * {@inheritDoc}
     * @var array
     */
    protected $attributes = [
        'name' => 'Create new user',
        'model' => User::class
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
            'name' => [
                'type' => Type::string(),
                'rules' => ['required']
            ],
            'email' => [
                'type' => Type::string(),
                'rules' => ['required', 'email']
            ],
            'password' => [
                'type' => Type::string(),
                'rules' => ['required', 'min:6', 'max:32']
            ],
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
        $user = User::create(Arr::only($args, ['name', 'email', 'password']));
        return [
            $user
        ];
    }
}
