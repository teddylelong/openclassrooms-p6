<?php

namespace App\Service;

use App\Entity\User;
use App\Repository\UserRepository;

class UserManager
{
    private $userRepository;

    /**
     * @param UserRepository $userRepository
     */
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
    public function add(User $user): void
    {
        $this->userRepository->add($user);
    }

    /**
     * @param $id
     * @return User|null
     */
    public function find($id)
    {
        return $this->userRepository->find($id);
    }

    /**
     * Find all Users in database
     *
     * @return array|null
     */
    public function findAll(): ?array
    {
        return $this->userRepository->findAll();
    }

    /**
     * Find a User by one given criteria
     *
     * @param array $criteria
     * @param array|null $orderBy
     * @return User|null
     */
    public function findOneBy(array $criteria, array $orderBy = null)
    {
        return $this->userRepository->findOneBy($criteria, $orderBy);
    }

    /**
     * Find a user by given email. Null on failure, User on success
     *
     * @param string $value
     * @return User|null
     * @throws \Doctrine\ORM\NonUniqueResultException
     */
    public function findOneByMail(string $value): ?User
    {
        return $this->userRepository->findOneByMail($value);
    }

    /**
     * Delete a User in database
     *
     * @param User $user
     * @return void
     */
    public function delete(User $user)
    {
        $this->userRepository->remove($user);
    }
}