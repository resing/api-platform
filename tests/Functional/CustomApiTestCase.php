<?php

declare(strict_types=1);

namespace App\Tests\Functional;

use ApiPlatform\Core\Bridge\Symfony\Bundle\Test\ApiTestCase;
use ApiPlatform\Core\Bridge\Symfony\Bundle\Test\Client;
use App\Entity\User;
use App\Factory\CategoryFactory;
use App\Factory\ProductFactory;
use App\Factory\UserFactory;
use Zenstruck\Foundry\Proxy;
use Zenstruck\Foundry\Test\Factories;
use Zenstruck\Foundry\Test\ResetDatabase;

abstract class CustomApiTestCase extends ApiTestCase
{
    use Factories;
    use ResetDatabase;

    protected function getToken(
        Client $client,
        $userOrUsername,
        string $password = UserFactory::DEFAULT_PASSWORD
    ): string {
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

    protected function loginUserWithCredentials(
        Client $client,
        $username,
        string $password = UserFactory::DEFAULT_PASSWORD
    ): Client {
        $token = $this->getToken($client, $username, $password);
        $client->setDefaultOptions(['headers' => ['authorization' => 'Bearer ' . $token]]);

        return $client;
    }

    /**
     * @return \App\Entity\User|object|\Zenstruck\Foundry\Proxy
     */
    protected function createUserWithProduct()
    {
        $user = UserFactory::new()->create();
        $category = CategoryFactory::new()->create();
        ProductFactory::new()->create([
            'category' => $category,
            'owner' => $user
        ]);

        return $user;
    }

    /**
     * @return \App\Entity\User|object|\Zenstruck\Foundry\Proxy
     */
    protected function createUserAdmin()
    {
        $userAdmin = UserFactory::new()->create([
            'roles' => ['ROLE_ADMIN']
        ]);

        return $userAdmin;
    }

    protected function createAdminWithProduct()
    {
        $userAdmin = $this->createUserAdmin();
        $category = CategoryFactory::new()->create();
        ProductFactory::new()->create([
            'category' => $category,
            'owner' => $userAdmin
        ]);

        return $userAdmin;
    }


    /**
     * @return array
     */
    public function createUserCategoryProduct(): array
    {
        $user = UserFactory::new()->create();
        $category = CategoryFactory::new()->create();
        $product = ProductFactory::new()->create([
            'category' => $category,
            'owner' => $user
        ]);
        return [$user, $product];
    }
}
