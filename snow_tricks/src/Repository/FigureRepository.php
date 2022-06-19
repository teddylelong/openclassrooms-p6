<?php

namespace App\Repository;

use App\Entity\Category;
use App\Entity\Figure;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\ORM\OptimisticLockException;
use Doctrine\ORM\ORMException;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<Figure>
 *
 * @method Figure|null find($id, $lockMode = null, $lockVersion = null)
 * @method Figure|null findOneBy(array $criteria, array $orderBy = null)
 * @method Figure[]    findAll()
 * @method Figure[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class FigureRepository extends ServiceEntityRepository
{
    /**
     * @param ManagerRegistry $registry
     */
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Figure::class);
    }

    /**
     * @throws ORMException
     * @throws OptimisticLockException
     */
    public function add(Figure $entity, bool $flush = true): void
    {
        $this->_em->persist($entity);
        if ($flush) {
            $this->_em->flush();
        }
    }

    /**
     * @param $status
     * @return float|int|mixed|string
     * @throws \Doctrine\ORM\NoResultException
     * @throws \Doctrine\ORM\NonUniqueResultException
     */
    public function countAllByStatus($status = Figure::STATUS_ACCEPTED)
    {
        return $this->createQueryBuilder('f')
            ->select('count(f.id)')
            ->andWhere('f.status = :status')
            ->setParameter('status', $status)
            ->getQuery()
            ->getSingleScalarResult()
            ;
    }

    /**
     * @param Category $category
     * @param $status
     * @return float|int|mixed|string
     * @throws \Doctrine\ORM\NoResultException
     * @throws \Doctrine\ORM\NonUniqueResultException
     */
    public function countAllByStatusAndCategory(Category $category, $status = Figure::STATUS_ACCEPTED)
    {
        return $this->createQueryBuilder('f')
            ->select('count(f.id)')
            ->andWhere('f.status = :status')
            ->andWhere('f.category = :cat')
            ->setParameter('status', $status)
            ->setParameter('cat', $category)
            ->getQuery()
            ->getSingleScalarResult()
            ;
    }

    /**
     * @return Figure[] Returns an array of Figure objects
     */
    public function findAllOrderByDate()
    {
        return $this->createQueryBuilder('f')
            ->orderBy('f.created_at', 'DESC')
            ->getQuery()
            ->getResult()
            ;
    }

    /**
     * @param $status
     * @param int $max
     * @param int $offset
     * @return Figure[] Returns an array of Figure objects
     */
    public function findByStatusOrderByDateLimit($status = Figure::STATUS_ACCEPTED, int $max = 12, int $offset = 0)
    {
        return $this->createQueryBuilder('f')
            ->andWhere('f.status = :status')
            ->setParameter('status', $status)
            ->orderBy('f.created_at', 'DESC')
            ->setMaxResults($max)
            ->setFirstResult($offset)
            ->getQuery()
            ->getResult()
        ;
    }

    /**
     * @param $status
     * @return Figure[] Returns an array of Figure objects
     */
    public function findByStatusOrderByDate($status = Figure::STATUS_ACCEPTED)
    {
        return $this->createQueryBuilder('f')
            ->andWhere('f.status = :status')
            ->setParameter('status', $status)
            ->orderBy('f.created_at', 'DESC')
            ->getQuery()
            ->getResult()
            ;
    }

    /**
     * @param Category $category
     * @return Figure[] Returns an array of Figure objects
     */
    public function findAllByCategoryOrderByDate(Category $category)
    {
        return $this->createQueryBuilder('f')
            ->andWhere('f.category = :cat')
            ->orderBy('f.created_at', 'DESC')
            ->setParameter('cat', $category)
            ->getQuery()
            ->getResult()
            ;
    }

    /**
     * @param Category $category
     * @param $status
     * @param int $max
     * @param int $offset
     * @return Figure[] Returns an array of Figure objects
     */
    public function findAllByStatusAndCategoryOrderByDateLimit(Category $category, $status = Figure::STATUS_ACCEPTED, int $max = 12, int $offset = 0)
    {
        return $this->createQueryBuilder('f')
            ->andWhere('f.category = :cat')
            ->andWhere('f.status = :status')
            ->orderBy('f.created_at', 'DESC')
            ->setParameter('status', $status)
            ->setParameter('cat', $category)
            ->setMaxResults($max)
            ->setFirstResult($offset)
            ->getQuery()
            ->getResult()
            ;
    }

    /**
     * @param Figure $entity
     * @param bool $flush
     * @return void
     */
    public function remove(Figure $entity, bool $flush = true): void
    {
        $this->_em->remove($entity);
        if ($flush) {
            $this->_em->flush();
        }
    }
}
