<?php

namespace App\Repository;

use App\Entity\Comment;
use App\Entity\Figure;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\ORM\OptimisticLockException;
use Doctrine\ORM\ORMException;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<Comment>
 *
 * @method Comment|null find($id, $lockMode = null, $lockVersion = null)
 * @method Comment|null findOneBy(array $criteria, array $orderBy = null)
 * @method Comment[]    findAll()
 * @method Comment[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class CommentRepository extends ServiceEntityRepository
{
    /**
     * @param ManagerRegistry $registry
     */
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Comment::class);
    }

    /**
     * @throws ORMException
     * @throws OptimisticLockException
     */
    public function add(Comment $entity, bool $flush = true): void
    {
        $this->_em->persist($entity);
        if ($flush) {
            $this->_em->flush();
        }
    }

    public function countAllByStatus($status = Comment::STATUS_ACCEPTED): int
    {
        return $this->createQueryBuilder('c')
            ->select('count(c.id)')
            ->andWhere('c.status = :status')
            ->setParameter('status', $status)
            ->getQuery()
            ->getSingleScalarResult()
            ;
    }

    public function countAllByFigureAndStatus(Figure $figure, $status = Comment::STATUS_ACCEPTED): int
    {
        return $this->createQueryBuilder('c')
            ->select('count(c.id)')
            ->andWhere('c.figure = :figure')
            ->andWhere('c.status = :status')
            ->setParameter('figure', $figure)
            ->setParameter('status', $status)
            ->getQuery()
            ->getSingleScalarResult()
            ;
    }

    /**
     * @return Comment[] Returns an array of Comment objects
     */
    public function findAllByStatus($status = Comment::STATUS_PENDING)
    {
        return $this->createQueryBuilder('c')
            ->andWhere('c.status = :status')
            ->setParameter('status', $status)
            ->getQuery()
            ->getResult()
            ;
    }

    /**
     * @return Comment[] Returns an array of Comment objects
     */
    public function findByFigureAndStatus(Figure $figure, $status = Comment::STATUS_ACCEPTED)
    {
        return $this->createQueryBuilder('c')
            ->andWhere('c.figure = :figure')
            ->andWhere('c.status = :status')
            ->setParameter('figure', $figure)
            ->setParameter('status', $status)
            ->orderBy('c.created_at', 'DESC')
            ->getQuery()
            ->getResult()
        ;
    }

    public function findAllByFigureAndStatusLimit(Figure $figure, $status = Comment::STATUS_ACCEPTED, int $max = 10, int $offset = 0)
    {
        return $this->createQueryBuilder('c')
            ->andWhere('c.figure = :figure')
            ->andWhere('c.status = :status')
            ->setParameter('figure', $figure)
            ->setParameter('status', $status)
            ->orderBy('c.created_at', 'DESC')
            ->setMaxResults($max)
            ->setFirstResult($offset)
            ->getQuery()
            ->getResult()
            ;
    }

    /**
     * @return Comment[] Returns an array of Comment objects
     */
    public function findByFigureAndStatusOrderByDateLimit(Figure $figure, $status = Comment::STATUS_ACCEPTED, int $max = 10, int $offset = 0)
    {
        return $this->createQueryBuilder('c')
            ->andWhere('c.figure = :figure')
            ->andWhere('c.status = :status')
            ->setParameter('figure', $figure)
            ->setParameter('status', $status)
            ->orderBy('c.created_at', 'DESC')
            ->setMaxResults($max)
            ->setFirstResult($offset)
            ->getQuery()
            ->getResult()
            ;
    }


    /**
     * @throws ORMException
     * @throws OptimisticLockException
     */
    public function remove(Comment $entity, bool $flush = true): void
    {
        $this->_em->remove($entity);
        if ($flush) {
            $this->_em->flush();
        }
    }
}
