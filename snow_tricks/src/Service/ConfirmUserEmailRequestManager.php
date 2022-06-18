<?php

namespace App\Service;

use App\Entity\ConfirmUserEmailRequest;
use App\Repository\ConfirmUserEmailRequestRepository;

class ConfirmUserEmailRequestManager
{
    private $repository;

    public function __construct(ConfirmUserEmailRequestRepository $confirmUserEmailRequestRepository)
    {
        $this->repository = $confirmUserEmailRequestRepository;
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