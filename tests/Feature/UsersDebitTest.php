<?php

namespace Tests\Feature;

use App\User;
use Tests\TestCase;

class UsersDebitTest extends TestCase
{
    /**
     * Test users list with debit transactions sum
     * @return void
     */
    public function testUsersDebitSum()
    {
        factory(User::class, 20)->create()->each(function (User $user) {
            for ($i = 0; $i <= random_int(2, 10); $i++) {
                $user->wallet->debit(random_int(1, 100));
            }
        });

        $response = $this->get('graphql?query={ users(limit:10) {id name email debit}}');
        $response->assertJsonCount(10, 'data.users');
        dump($response->json('data.users'));
    }
}
