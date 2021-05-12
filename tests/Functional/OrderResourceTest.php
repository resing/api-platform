<?php


namespace App\Tests\Functional;

use App\Factory\UserFactory;

class OrderResourceTest extends CustomApiTestCase
{
    public function testOrderCustomer()
    {
        $client = static::createClient();
        /** @var \App\Entity\User|object|\Zenstruck\Foundry\Proxy $user */
        list($user, $product) = $this->createUserCategoryProduct();
        $user->refresh();
        $user->setRoles(['ROLE_PROVIDER']);
        $user->save();
        $customer = UserFactory::new()->create();
        $this->loginUserWithCredentials($client, $customer)
            ->request('POST', '/api/orders', [
                'json' => [
                    'quantity' => 3,
                    'product' => "/api/products/" . $product->getId(),
                ]
            ]);
        $this->assertResponseStatusCodeSame(201);
        // send quantity not respected
        $this->loginUserWithCredentials($client, $customer)
            ->request('POST', '/api/orders', [
                'json' => [
                    'quantity' => 3000,
                    'product' => "/api/products/" . $product->getId(),
                ]
            ]);
        $this->assertResponseStatusCodeSame(422, 'quantity: Quantity not available');
    }
}
