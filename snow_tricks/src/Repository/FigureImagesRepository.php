<?php

namespace App\Repository;

use App\Entity\FigureImages;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\ORM\OptimisticLockException;
use Doctrine\ORM\ORMException;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<FigureImages>
 *
 * @method FigureImages|null find($id, $lockMode = null, $lockVersion = null)
 * @method FigureImages|null findOneBy(array $criteria, array $orderBy = null)
 * @method FigureImages[]    findAll()
 * @method FigureImages[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class FigureImagesRepository extends ServiceEntityRepository
{
    /**
     * @param ManagerRegistry $registry
     */
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, FigureImages::class);
    }

    /**
     * @throws ORMException
     * @throws OptimisticLockException
     */
    public function add(FigureImages $entity, bool $flush = true): void
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
    public function remove(FigureImages $entity, bool $flush = true): void
    {
        $this->_em->remove($entity);
        if ($flush) {
            $this->_em->flush();
        }
    }
}
