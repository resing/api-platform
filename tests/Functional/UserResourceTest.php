<?php

declare(strict_types=1);

namespace App\Tests\Functional;

use App\Factory\UserFactory;


class UserResourceTest extends CustomApiTestCase
{
    public function testGetUser()
    {
        $client = self::createClient();
        $user1 = UserFactory::new()->create();
        $user2 = UserFactory::new()->create();
        $userAdmin = UserFactory::new()->create([
            'roles' => ['ROLE_ADMIN']
        ]);
        $this->loginUserWithCredentials($client, $userAdmin)
            ->request('GET', '/api/users');
        $this->assertResponseIsSuccessful();
        $this->assertJsonContains(['hydra:totalItems' => 3]);
        $this->loginUserWithCredentials($client, $user1)
            ->request('GET', '/api/users');
        $this->assertResponseIsSuccessful();
        $this->assertJsonContains(['hydra:totalItems' => 1]);
        $this->assertJsonContains(['hydra:member' => [
            0 => [
                '@id' => '/api/users/' . $user1->getId(),
                "email"=> $user1->getEmail(),
            ]
        ]]);
        $this->loginUserWithCredentials($client, $user2)
            ->request('GET', '/api/users');
        $this->assertJsonContains(['hydra:totalItems' => 1]);
        $this->assertJsonContains(['hydra:member' => [
            0 => [
                '@id' => '/api/users/' . $user2->getId(),
                "email"=> $user2->getEmail(),
            ]
        ]]);
    }
}
