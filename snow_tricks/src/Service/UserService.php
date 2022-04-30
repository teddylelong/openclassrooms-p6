<?php

namespace App\Service;

use App\Entity\User;
use App\Repository\UserRepository;

class UserService
{
    private $userRepository;

    public function __construct(UserRepository $userRepository)
    {
        $this->userRepository = $userRepository;
    }

    /**
     * Record a User in database
     *
     * @param User $user The User to add
     * @throws \Doctrine\ORM\ORMException
     * @throws \Doctrine\ORM\OptimisticLockException
     */
    public function add(User $user)
    {
        $this->userRepository->add($user);
    }

    /**
     * Find all Users in database
     *
     * @return array
     */
    public function findAll(): array
    {
        return $this->userRepository->findAll();
    }
}