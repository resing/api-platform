<?php

namespace App\DataFixtures;

use App\Entity\User;
use App\Factory\CategoryFactory;
use App\Factory\ProductFactory;
use App\Factory\UserFactory;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;

class AppFixtures extends Fixture
{
    private $passwordEncoder;

    public function __construct(UserPasswordEncoderInterface  $passwordEncoder)
    {
        $this->passwordEncoder = $passwordEncoder;
    }
    public function load(ObjectManager $manager)
    {
        $userAdmin = UserFactory::new()->create([
            'email' => 'admin@example.com',
            'username' => 'admin',
            'roles' => ['ROLE_ADMIN'],
            'password' => $this->passwordEncoder->encodePassword(new User(), 'admin'),
        ]);

        $user2 = UserFactory::new()->create([
            'email' => 'simple@example.com',
            'username' => 'simple',
            'password' => $this->passwordEncoder->encodePassword(new User(), 'simple'),
        ]);


        $categoryFactory2 = CategoryFactory::new()->create([
            'name' => 'the first category',
            'description' => 'Origin date: unknown. Actual origin... also unknown.',
        ]);

        $productFactory = ProductFactory::new();
        $productFactory->create([
            'name' => 'verre',
            'description' => 'this article add by admin',
            'price' => 500,
            'owner' => $userAdmin,
            'category' => $categoryFactory2,
        ]);

        $productFactory->create([
            'name' => 'chemise',
            'description' => 'When I drive it to your house, it will sit in the passenger seat of my car.',
            'price' => 300,
            'owner' => $user2,
            'category' => $categoryFactory2,
        ]);
        $productFactory->create([
            'name' => 'pantalon',
            'description' => 'In that case, all the public methods of the provider become',
            'price' => 300,
            'owner' => $user2,
            'category' => $categoryFactory2,
        ]);
        ProductFactory::new()::createMany(10);
    }
}
