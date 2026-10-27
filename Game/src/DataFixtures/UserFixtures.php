<?php

namespace App\DataFixtures;

use App\Entity\User;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Bundle\FixturesBundle\FixtureGroupInterface;
use Doctrine\Persistence\ObjectManager;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;

class UserFixtures extends Fixture implements FixtureGroupInterface
{
    public function __construct(private UserPasswordHasherInterface $passwordHasher)
    {
    }

    public function load(ObjectManager $manager): void
    {
        $users = [
            ['email' => 'admin@decorgame.com', 'password' => 'admin123', 'roles' => ['ROLE_ADMIN']],
            ['email' => 'user@decorgame.com',  'password' => 'user123',  'roles' => []],
        ];

        foreach ($users as $data) {
            $user = new User();
            $user->setEmail($data['email']);
            $user->setRoles($data['roles']);
            $user->setPassword($this->passwordHasher->hashPassword($user, $data['password']));
            $manager->persist($user);
        }

        $manager->flush();
    }

    // Permite carregar só os usuários: doctrine:fixtures:load --group=user --append
    public static function getGroups(): array
    {
        return ['user'];
    }
}
