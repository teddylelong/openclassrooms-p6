<?php

namespace App\Service;

use App\Entity\ConfirmUserEmailRequest;
use App\Entity\User;
use App\Repository\ConfirmUserEmailRequestRepository;

class ConfirmUserEmailRequestManager
{
    private $repository;

    public function __construct(ConfirmUserEmailRequestRepository $confirmUserEmailRequestRepository)
    {
        $this->repository = $confirmUserEmailRequestRepository;
    }

    /**
     * @param $id
     * @return ConfirmUserEmailRequest|null
     */
    public function find($id): ?ConfirmUserEmailRequest
    {
        return $this->repository->find($id);
    }

    /**
     * @param string $uuid
     * @return ConfirmUserEmailRequest|null
     * @throws \Doctrine\ORM\NonUniqueResultException
     */
    public function findOneByUuid(string $uuid): ?ConfirmUserEmailRequest
    {
        return $this->repository->findOneByUuid($uuid);
    }

    public function findOneByUser(User $user): ?ConfirmUserEmailRequest
    {
        return $this->repository->findOneByUser($user);
    }

    /**
     * @param ConfirmUserEmailRequest $confirmUserEmailRequest
     * @return void
     * @throws \Doctrine\ORM\ORMException
     * @throws \Doctrine\ORM\OptimisticLockException
     */
    public function add(ConfirmUserEmailRequest $confirmUserEmailRequest): void
    {
        $this->repository->add($confirmUserEmailRequest);
    }

    /**
     * @param ConfirmUserEmailRequest $confirmUserEmailRequest
     * @return void
     * @throws \Doctrine\ORM\ORMException
     * @throws \Doctrine\ORM\OptimisticLockException
     */
    public function delete(ConfirmUserEmailRequest $confirmUserEmailRequest): void
    {
        $this->repository->remove($confirmUserEmailRequest);
    }
}