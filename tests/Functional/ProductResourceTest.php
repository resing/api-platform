<?php

declare(strict_types=1);

namespace App\Tests\Functional;

use App\Factory\ProductFactory;

class ProductResourceTest extends CustomApiTestCase
{
    public function testGetProductByUser()
    {
        $client = self::createClient();
        $user = $this->createUserWithProduct();
        ProductFactory::new()::createMany(10);
        $this->loginUserWithCredentials($client, $user)
            ->request('GET', '/api/products');
        $this->assertJsonContains(['hydra:totalItems' => 1]);
        $userAdmin = $this->createUserAdmin();
        $this->loginUserWithCredentials($client, $userAdmin)
            ->request('GET', '/api/products');
        $this->assertJsonContains(['hydra:totalItems' => 11]);
    }

    public function testIsPublishedByUser()
    {
        $client = self::createClient();
        $user = $this->createUserWithProduct();
        $data = $this->loginUserWithCredentials($client, $user)
            ->request('GET', '/api/products')->toArray();
        $this->assertArrayNotHasKey('isPublished', $data['hydra:member'][0]);
        $userAdmin = $this->createUserAdmin();
        $data = $this->loginUserWithCredentials($client, $userAdmin)
            ->request('GET', '/api/products')->toArray();
        $this->assertArrayHasKey('isPublished', $data['hydra:member'][0]);
    }

    public function testIsMe()
    {
        $client = self::createClient();
        $userAdmin = $this->createAdminWithProduct();
        $this->loginUserWithCredentials($client, $userAdmin)
            ->request('GET', '/api/products');
        $this->assertJsonContains(['hydra:member' => [
            0 => [
                'owner' => [
                    'isMe' => true
                ]
            ]
        ]]);
        $user = $this->createUserWithProduct();
        $data = $this->loginUserWithCredentials($client, $user)
            ->request('GET', '/api/products')->toArray();
        $this->assertArrayNotHasKey('isMe', $data['hydra:member'][0]['owner']);
    }
}
