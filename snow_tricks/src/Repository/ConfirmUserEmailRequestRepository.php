<?php

namespace App\Repository;

use App\Entity\ConfirmUserEmailRequest;
use App\Entity\User;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\ORM\NonUniqueResultException;
use Doctrine\ORM\OptimisticLockException;
use Doctrine\ORM\ORMException;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<ConfirmUserEmailRequest>
 *
 * @method ConfirmUserEmailRequest|null find($id, $lockMode = null, $lockVersion = null)
 * @method ConfirmUserEmailRequest|null findOneBy(array $criteria, array $orderBy = null)
 * @method ConfirmUserEmailRequest[]    findAll()
 * @method ConfirmUserEmailRequest[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class ConfirmUserEmailRequestRepository extends ServiceEntityRepository
{
    /**
     * @param ManagerRegistry $registry
     */
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, ConfirmUserEmailRequest::class);
    }

    /**
     * @throws ORMException
     * @throws OptimisticLockException
     */
    public function add(ConfirmUserEmailRequest $entity, bool $flush = true): void
    {
        $this->_em->persist($entity);
        if ($flush) {
            $this->_em->flush();
        }
    }

    /**
     * @param string $uuid
     * @return ConfirmUserEmailRequest|null
     * @throws NonUniqueResultException
     */
    public function findOneByUuid(string $uuid): ?ConfirmUserEmailRequest
    {
        return $this->createQueryBuilder('f')
            ->andWhere('f.uuid = :uuid')
            ->setParameter('uuid', $uuid, 'uuid')
            ->getQuery()
            ->getOneOrNullResult()
            ;
    }

    /**
     * @param User $user
     * @return ConfirmUserEmailRequest|null
     * @throws NonUniqueResultException
     */
    public function findOneByUser(User $user): ?ConfirmUserEmailRequest
    {
        return $this->createQueryBuilder('f')
            ->andWhere('f.user = :user')
            ->setParameter('user', $user)
            ->getQuery()
            ->getOneOrNullResult()
            ;
    }

    /**
     * @throws ORMException
     * @throws OptimisticLockException
     */
    public function remove(ConfirmUserEmailRequest $entity, bool $flush = true): void
    {
        $this->_em->remove($entity);
        if ($flush) {
            $this->_em->flush();
        }
    }

}
