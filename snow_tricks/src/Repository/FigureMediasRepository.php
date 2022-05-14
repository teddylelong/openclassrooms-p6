<?php

namespace App\Repository;

use App\Entity\FigureMedias;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\ORM\OptimisticLockException;
use Doctrine\ORM\ORMException;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<FigureMedias>
 *
 * @method FigureMedias|null find($id, $lockMode = null, $lockVersion = null)
 * @method FigureMedias|null findOneBy(array $criteria, array $orderBy = null)
 * @method FigureMedias[]    findAll()
 * @method FigureMedias[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class FigureMediasRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, FigureMedias::class);
    }

    /**
     * @throws ORMException
     * @throws OptimisticLockException
     */
    public function add(FigureMedias $entity, bool $flush = true): void
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
    public function remove(FigureMedias $entity, bool $flush = true): void
    {
        $this->_em->remove($entity);
        if ($flush) {
            $this->_em->flush();
        }
    }

    // /**
    //  * @return FigureMedias[] Returns an array of FigureMedias objects
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
    public function findOneBySomeField($value): ?FigureMedias
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
