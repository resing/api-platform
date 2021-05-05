<?php


namespace App\Tests;


use App\Factory\UserFactory;

class UserResourceTest extends CustomApiTestCase
{
    public function testGetUser()
    {
        $client = self::createClient();
        $user = UserFactory::new()->create();
        UserFactory::new()->create();
        $userAdmin = UserFactory::new()->create([
            'roles' => ['ROLE_ADMIN']
        ]);
        $this->createClientWithCredentials($client, $userAdmin)
            ->request('GET', '/api/users');
        $this->assertResponseIsSuccessful();
        $this->assertJsonContains(['hydra:totalItems' => 3]);
        $this->createClientWithCredentials($client, $user)
            ->request('GET', '/api/users');
        $this->assertResponseIsSuccessful();
        $this->assertJsonContains(['hydra:totalItems' => 1]);
        $this->assertJsonContains(['hydra:member' => [
            0 => [
                '@id' => '/api/users/' . $user->getId(),
                "email"=> $user->getEmail(),
            ]
        ]]);
    }
}
