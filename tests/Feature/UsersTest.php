<?php

namespace Tests\Feature;

use App\User;
use Illuminate\Http\Response;
use Str;
use Tests\TestCase;

class UsersTest extends TestCase
{
    /**
     * Url base for test
     * @var string
     */
    protected $base = '/graphql/users?query=';

    /**
     * Test index and create endpoints for:
     * - not logged
     * - common user (not admin)
     * - admin
     * @return void
     */
    public function testIndexAndCreateEndpoints()
    {
        $usersIndexQuery = '{ index {id name email isAdmin}}';
        $indexQuery = $this->base . $usersIndexQuery;

        $userName = $this->faker->name;
        $userEmail = $this->faker->safeEmail;
        $userPassword = Str::random(12);

        $usersCreateQuery = sprintf('mutation{create(name: "%s", email: "%s", password: "%s"){name,email}}',
            $userName,
            $userEmail,
            $userPassword
        );
        $createQuery = $this->base . $usersCreateQuery;

        //Without logged user
        $response = $this->get($indexQuery);
        $response->assertStatus(Response::HTTP_UNAUTHORIZED);
        $response = $this->post($createQuery);
        $response->assertStatus(Response::HTTP_UNAUTHORIZED);

        //With logged user(not admin)
        $this->be(factory(User::class)->create());
        $response = $this->get($indexQuery);
        $response->assertStatus(Response::HTTP_UNAUTHORIZED);
        $response = $this->post($createQuery);
        $response->assertStatus(Response::HTTP_UNAUTHORIZED);

        //With logged admin
        $this->be(User::whereRoleId(User::ROLE_ADMIN)->first());
        $response = $this->get($indexQuery);
        $response->assertStatus(Response::HTTP_OK);
        $response->assertJsonStructure([
            'data' => [
                'index'
            ]
        ]);

        $response = $this->post($createQuery);
        $response->assertStatus(Response::HTTP_OK);
        $response->assertJson([
            'data' => [
                'create' => [
                    'name' => $userName,
                    'email' => $userEmail,
                ]
            ]
        ]);
    }
}
