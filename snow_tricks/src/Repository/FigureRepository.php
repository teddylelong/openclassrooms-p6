<?php

namespace App\Repository;

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
     * @return Figure[] Returns an array of Figure objects
     */
    public function findByStatusOrderByDate($value = Figure::STATUS_ACCEPTED)
    {
        return $this->createQueryBuilder('f')
            ->andWhere('f.status = :val')
            ->setParameter('val', $value)
            ->orderBy('f.created_at', 'DESC')
            // ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }

    /**
     * @return Figure[] Returns an array of Figure objects
     */
    public function findAllByCategoryOrderByDate($categoryId)
    {
        return $this->createQueryBuilder('f')
            ->andWhere('f.category = :cat')
            ->orderBy('f.created_at', 'DESC')
            ->setParameter('cat', $categoryId)
            ->getQuery()
            ->getResult()
            ;
    }

    /**
     * @throws ORMException
     * @throws OptimisticLockException
     */
    public function remove(Figure $entity, bool $flush = true): void
    {
        $this->_em->remove($entity);
        if ($flush) {
            $this->_em->flush();
        }
    }

    // /**
    //  * @return Figure[] Returns an array of Figure objects
    //  */
    /*
    public function findByExampleField($value)
    {
        return $this->createQueryBuilder('f')
            ->andWhere('f.exampleField = :val')
            ->setParameter('val', $value)
            ->orderBy('f.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    */

    /*
    public function findOneBySomeField($value): ?Figure
    {
        return $this->createQueryBuilder('f')
            ->andWhere('f.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */
}
