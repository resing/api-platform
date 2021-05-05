<?php

namespace App\Tests;

use ApiPlatform\Core\Bridge\Symfony\Bundle\Test\ApiTestCase;
use ApiPlatform\Core\Bridge\Symfony\Bundle\Test\Client;
use App\Entity\User;
use App\Factory\UserFactory;
use Zenstruck\Foundry\Proxy;
use Zenstruck\Foundry\Test\Factories;
use Zenstruck\Foundry\Test\ResetDatabase;

class CustomApiTestCase extends ApiTestCase
{
    use Factories;
    use ResetDatabase;

    protected function createUser(string $email, string $password)
    {
        $user = new User();
        $user->setEmail($email);
        $user->setUsername(substr($email, 0, strpos($email, '@')));
        $encoded = self::$container->get('security.password_encoder')
            ->encodePassword($user, $password);
        $user->setPassword($encoded);

        $em = self::$container->get('doctrine')->getManager();
        $em->persist($user);
        $em->flush();

        return $user;

    }


    protected function getToken(Client $client, $userOrUsername, string $password = UserFactory::DEFAULT_PASSWORD): string
    {
        if ($userOrUsername instanceof User || $userOrUsername instanceof Proxy) {
            $username = $userOrUsername->getUsername();
        } elseif (is_string($userOrUsername)) {
            $username = $userOrUsername;
        } else {
            throw new \InvalidArgumentException(sprintf(
                'Argument 2 to "%s" should be a User, Foundry Proxy or string email, "%s" given',
                __METHOD__,
                is_object($userOrUsername) ? get_class($userOrUsername) : gettype($userOrUsername)
            ));
        }

        $response = $client
            ->request('POST', '/api/login_check',
                [
                    'json' => [
                        'username' => $username,
                        'password' => $password,
                    ]
                ]);
        $this->assertResponseIsSuccessful();
        $data = \json_decode($response->getContent());

        return $data->token;
    }

    protected function createClientWithCredentials(Client $client, $username, string $password = UserFactory::DEFAULT_PASSWORD): Client
    {
        $token = $this->getToken($client, $username, $password);
        $client->setDefaultOptions(['headers' => ['authorization' => 'Bearer ' . $token]]);

        return $client;
    }
}
