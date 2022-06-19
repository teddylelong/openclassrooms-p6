<?php

namespace App\Service;

use App\Entity\ResetUserPasswordRequest;
use App\Repository\ResetUserPasswordRequestRepository;

class ResetUserPasswordRequestManager
{
    private $repository;

    public function __construct(ResetUserPasswordRequestRepository $repository)
    {
        $this->repository = $repository;
    }

    /**
     * @param string $uuid
     * @return ResetUserPasswordRequest|null
     * @throws \Doctrine\ORM\NonUniqueResultException
     */
    public function findOneByUuid(string $uuid): ?ResetUserPasswordRequest
    {
        return $this->repository->findOneByUuid($uuid);
    }

    /**
     * @param ResetUserPasswordRequest $entity
     * @param bool $flush
     * @return void
     * @throws \Doctrine\ORM\ORMException
     * @throws \Doctrine\ORM\OptimisticLockException
     */
    public function add(ResetUserPasswordRequest $entity, bool $flush = true): void
    {
        $this->repository->add($entity, $flush);
    }

    /**
     * @param ResetUserPasswordRequest $entity
     * @param bool $flush
     * @return void
     * @throws \Doctrine\ORM\ORMException
     * @throws \Doctrine\ORM\OptimisticLockException
     */
    public function delete(ResetUserPasswordRequest $entity, bool $flush = true): void
    {
        $this->repository->remove($entity, $flush);
    }
}