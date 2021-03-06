<?php

namespace App\Repository;

use App\Entity\ResetUserPasswordRequest;
use App\Entity\User;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\ORM\OptimisticLockException;
use Doctrine\ORM\ORMException;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<ResetUserPasswordRequest>
 *
 * @method ResetUserPasswordRequest|null find($id, $lockMode = null, $lockVersion = null)
 * @method ResetUserPasswordRequest|null findOneBy(array $criteria, array $orderBy = null)
 * @method ResetUserPasswordRequest[]    findAll()
 * @method ResetUserPasswordRequest[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class ResetUserPasswordRequestRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, ResetUserPasswordRequest::class);
    }

    /**
     * @param string $uuid
     * @return ResetUserPasswordRequest|null
     * @throws \Doctrine\ORM\NonUniqueResultException
     */
    public function findOneByUuid(string $uuid): ?ResetUserPasswordRequest
    {
        return $this->createQueryBuilder('r')
            ->andWhere('r.uuid = :uuid')
            ->setParameter('uuid', $uuid, 'uuid')
            ->getQuery()
            ->getOneOrNullResult()
            ;
    }

    /**
     * @param User $user
     * @return ResetUserPasswordRequest|null
     * @throws \Doctrine\ORM\NonUniqueResultException
     */
    public function findOneByUser(User $user): ?ResetUserPasswordRequest
    {
        return $this->createQueryBuilder('r')
            ->andWhere('r.user = :user')
            ->setParameter('user', $user)
            ->getQuery()
            ->getOneOrNullResult()
            ;
    }

    /**
     * @throws ORMException
     * @throws OptimisticLockException
     */
    public function add(ResetUserPasswordRequest $entity, bool $flush = true): void
    {
        $this->_em->persist($entity);
        if ($flush) {
            $this->_em->flush();
        }
    }

    /**
     * @throws ORMException
     * @throws OptimisticLockException
     */
    public function remove(ResetUserPasswordRequest $entity, bool $flush = true): void
    {
        $this->_em->remove($entity);
        if ($flush) {
            $this->_em->flush();
        }
    }
}
