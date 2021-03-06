<?php

namespace App\Repository;

use App\Entity\Category;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\ORM\OptimisticLockException;
use Doctrine\ORM\ORMException;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<Category>
 *
 * @method Category|null find($id, $lockMode = null, $lockVersion = null)
 * @method Category|null findOneBy(array $criteria, array $orderBy = null)
 * @method Category[]    findAll()
 * @method Category[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class CategoryRepository extends ServiceEntityRepository
{
    /**
     * @param ManagerRegistry $registry
     */
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Category::class);
    }

    /**
     * @throws ORMException
     * @throws OptimisticLockException
     */
    public function add(Category $entity, bool $flush = true): void
    {
        $this->_em->persist($entity);
        if ($flush) {
            $this->_em->flush();
        }
    }

    /**
     * @return Category|null
     * @throws \Doctrine\ORM\NonUniqueResultException
     */
    public function getDefaultCategory(): ?Category
    {
        return $this->createQueryBuilder('c')
            ->andWhere('c.is_default = :default')
            ->setParameter('default', 1)
            ->getQuery()
            ->getOneOrNullResult()
            ;
    }

    /**
     * @return Category[] Returns an array of Category objects
     */
    public function findAllOrderByName()
    {
        return $this->createQueryBuilder('c')
            ->orderBy('c.name', 'ASC')
            ->getQuery()
            ->getResult()
            ;
    }

    /**
     * @return Category[] Returns an array of Category objects
     */
    public function findAllOrderById()
    {
        return $this->createQueryBuilder('c')
            ->orderBy('c.id', 'ASC')
            ->getQuery()
            ->getResult()
            ;
    }

    /**
     * @throws ORMException
     * @throws OptimisticLockException
     */
    public function remove(Category $entity, bool $flush = true): void
    {
        $this->_em->remove($entity);
        if ($flush) {
            $this->_em->flush();
        }
    }
}
