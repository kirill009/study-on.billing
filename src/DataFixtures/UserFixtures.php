<?php

namespace App\DataFixtures;

use App\Entity\User;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;

class UserFixtures extends Fixture
{
    /**
     * @var UserPasswordHasherInterface
     */
    private $passwordHasher;
    
    private static $data = [
        0 => [
            "email" => "keting404@yandex.ru",
            "roles" => ["ROLE_USER"],
            "password" => "123456a",
            "balance" => "22.5",
        ],
        1 => [
            "email" => "admin@keting404.ru",
            "roles" => ["ROLE_SUPER_ADMIN"],
            "password" => "123456a",
            "balance" => "743",
        ]
    ];
    
    public function __construct(UserPasswordHasherInterface $passwordHasher)
    {
        $this->passwordHasher = $passwordHasher;
    }
    
    public function load(ObjectManager $manager): void
    {
        foreach (self::$data as $datum)
        {
            $user = new User();
            $user->setEmail($datum["email"]);
            $user->setRoles($datum["roles"]);
            $user->setBalance($datum["balance"]);
    
            $hashedPassword = $this->passwordHasher->hashPassword(
                $user,
                $datum["password"]
            );
            $user->setPassword($hashedPassword);

            $manager->persist($user);
        }

        $manager->flush();
    }
}
