<?php

namespace App\DataFixtures;

use App\Entity\User;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;

class UserFixtures extends Fixture
{
    private $passwordHasher;

    public function __construct(UserPasswordHasherInterface $passwordHasher)
    {
        $this->passwordHasher = $passwordHasher;
    }

    public function load(ObjectManager $manager): void
    {
        $admin = (new User())
            ->setUsername('admin')
            ->setEmail('admin@snowtricks.com')
            ->setRoles(["ROLE_ADMIN"])
            ->setAvatar('admin-avatar.png')
            ->setIsVerified(1)
        ;

        $admin->setPassword($this->passwordHasher->hashPassword($admin,'admin'));

        $manager->persist($admin);

        $modo = (new User())
            ->setUsername('modo')
            ->setEmail('modo@snowtricks.com')
            ->setRoles(["ROLE_MODO"])
            ->setIsVerified(1)
        ;

        $modo->setPassword($this->passwordHasher->hashPassword($modo, 'modo'));

        $manager->persist($modo);

        $user = (new User())
            ->setUsername('user')
            ->setEmail('user@snowtricks.com')
            ->setIsVerified(1)
        ;
        $user->setPassword($this->passwordHasher->hashPassword($user, 'user'));

        $manager->persist($user);

        $manager->flush();
    }
}
