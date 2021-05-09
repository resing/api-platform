<?php

declare(strict_types=1);

namespace App\Tests\Functional;

use App\Factory\CategoryFactory;
use App\Factory\ProductFactory;
use App\Factory\UserFactory;

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
        /** @var \App\Entity\User|object|\Zenstruck\Foundry\Proxy $user  */
        list($user, $product) = $this->createUserCategoryProduct();
        /** @var \App\Entity\Product|object|\Zenstruck\Foundry\Proxy $product  */
        $data = $this->loginUserWithCredentials($client, $user)
            ->request('GET', '/api/products/' . $product->getId())
            ->toArray();
        $this->assertArrayNotHasKey('isMe', $data['owner']);
        $user->refresh();
        $user->setRoles(['ROLE_ADMIN']);
        $user->save();
        $data = $this->loginUserWithCredentials($client, $user)
            ->request('GET', '/api/products/' . $product->getId())
            ->toArray();
        $this->assertArrayHasKey('isMe', $data['owner']);
        $this->assertTrue($data['owner']['isMe']);
    }

    public function testIsUnlimitedProduct()
    {
        $client = self::createClient();
        /** @var \App\Entity\User|object|\Zenstruck\Foundry\Proxy $user  */
        list($user, $product) = $this->createUserCategoryProduct();
        /** @var \App\Entity\Product|object|\Zenstruck\Foundry\Proxy $product  */
        $product->refresh();
        $product->setQuantity(30);
        $product->save();
        $this->loginUserWithCredentials($client, $user)
            ->request('GET', '/api/products/' . $product->getId());
        $this->assertJsonContains([
            'unlimited' => false,
        ]);
        $product->refresh();
        $product->setQuantity(400);
        $product->save();
        $this->loginUserWithCredentials($client, $user)
            ->request('GET', '/api/products/' . $product->getId());
        $this->assertJsonContains([
            'unlimited' => true,
        ]);
    }
}
