<?php

namespace Tests\Feature;

use App\Models\Transaction;
use App\User;
use Illuminate\Http\Response;
use Tests\TestCase;

class TransactionsTest extends TestCase
{
    /**
     * Url base for test
     * @var string
     */
    protected $base = '/graphql/transactions?query=';

    /**
     * Test user transactions creation
     * @return void
     */
    public function testTransactions()
    {
        $typeId = Transaction::TYPE_CREDIT;
        $amount = random_int(10, 1000);

        $transactionCreateQuery = sprintf(
            'mutation{create(type_id: %d,amount: %d){type_id,amount,user_id}}',
            $typeId,
            $amount
        );

        $createQuery = $this->base . $transactionCreateQuery;

        //Without logged user
        $response = $this->post($createQuery);
        $response->assertStatus(Response::HTTP_UNAUTHORIZED);

        //With logged user
        $user = factory(User::class)->create();
        $this->be($user);

        $response = $this->post($createQuery);
        $response->assertStatus(Response::HTTP_OK);
        $response->assertJsonStructure([
            'data' => [
                'create' => [
                    'type_id',
                    'amount',
                    'user_id',
                ]
            ]
        ]);
        $response->assertJson([
            'data' => [
                'create' => [
                    'user_id' => $user->id
                ]
            ]
        ]);
    }
}
