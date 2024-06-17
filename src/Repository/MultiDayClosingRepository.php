<?php

namespace App\Repository;

use App\Entity\MultiDayClosing;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<MultiDayClosing>
 *
 * @method MultiDayClosing|null find($id, $lockMode = null, $lockVersion = null)
 * @method MultiDayClosing|null findOneBy(array $criteria, array $orderBy = null)
 * @method MultiDayClosing[]    findAll()
 * @method MultiDayClosing[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class MultiDayClosingRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, MultiDayClosing::class);
    }

    public function add(MultiDayClosing $entity, bool $flush = false): void
    {
        $this->getEntityManager()->persist($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }

    public function remove(MultiDayClosing $entity, bool $flush = false): void
    {
        $this->getEntityManager()->remove($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }

    public function findMultiDayClosingByDate($date): ?MultiDayClosing
    {
        return $this->createQueryBuilder('m')
            ->andWhere('m.beginClosing <= :date')
            ->setParameter('date', $date)
            ->andWhere('m.finisgClosing >= :date')
            ->setParameter('date', $date)
            ->getQuery()
            ->getOneOrNullResult();
    }

    //    /**
    //     * @return MultiDayClosing[] Returns an array of MultiDayClosing objects
    //     */
    //    public function findByExampleField($value): array
    //    {
    //        return $this->createQueryBuilder('m')
    //            ->andWhere('m.exampleField = :val')
    //            ->setParameter('val', $value)
    //            ->orderBy('m.id', 'ASC')
    //            ->setMaxResults(10)
    //            ->getQuery()
    //            ->getResult()
    //        ;
    //    }

}
