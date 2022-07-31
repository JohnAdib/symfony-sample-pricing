<?php

namespace App\Repository;

use App\Entity\Pricing;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<Pricing>
 *
 * @method Pricing|null find($id, $lockMode = null, $lockVersion = null)
 * @method Pricing|null findOneBy(array $criteria, array $orderBy = null)
 * @method Pricing[]    findAll()
 * @method Pricing[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class PricingRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Pricing::class);
    }

    public function add(Pricing $entity, bool $flush = false): void
    {
        $this->getEntityManager()->persist($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }


    public function remove(Pricing $entity, bool $flush = false): void
    {
        $this->getEntityManager()->remove($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }

    /**
     * @return Pricing[]
     */
    public function advanceSearch(array $filters): array
    {
        $entityManager = $this->getEntityManager();
        // create query builder on pricing table
        $qb = $this->createQueryBuilder('p');

        // add filter - price-min
        if (isset($filters['price-min']) && is_numeric($filters['price-min'])) {
            // @todo change price text to int
            $qb->where('p.price > :pricemin')->setParameter('pricemin', $filters['price-min']);
        }
        // add filter - price-max
        if (isset($filters['price-max']) && is_numeric($filters['price-max'])) {
            // @todo change price text to int
            $qb->where('p.price < :pricemax')->setParameter('pricemax', $filters['price-max']);
        }

        // add filter - storage-min
        if (isset($filters['storage-min']) && is_numeric($filters['storage-min'])) {
            // @todo change storage text to int
            $qb->where('p.storage > :storagemin')->setParameter('storagemin', $filters['storage-min']);
        }
        // add filter - storage-max
        if (isset($filters['storage-max']) && is_numeric($filters['storage-max'])) {
            // @todo change storage text to int
            $qb->where('p.storage < :storagemax')->setParameter('storagemax', $filters['storage-max']);
        }

        // add filter - ram
        if (isset($filters['ram']) && is_array($filters['ram'])) {
            // get list of ram and filter them to remove null and empty values
            $ram = array_filter($filters['ram']);
            if (count($ram) >= 1) {
                $qb->where('p.ram IN ( ' . implode(',', $ram) . ' )');
            }
        } else {
            // add filter - ram-min
            if (isset($filters['ram-min']) && is_numeric($filters['ram-min'])) {
                // @todo change ram text to int
                $qb->where('p.ram > :rammin')->setParameter('rammin', $filters['ram-min']);
            }
            // add filter - ram-max
            if (isset($filters['ram-max']) && is_numeric($filters['ram-max'])) {
                // @todo change ram text to int
                $qb->where('p.ram < :rammax')->setParameter('rammax', $filters['ram-max']);
            }
        }

        // add filter - storage type
        if (isset($filters['storagetype']) && is_array($filters['storagetype'])) {
            // get list of storagetype and filter them to remove null and empty values
            $storageTypes = array_filter($filters['storagetype']);
            // if after filter count of array is 1 or more, apply filter
            if (count($storageTypes) >= 1) {
                $qb->where('p.storagetype IN ( ' . implode(',', $storageTypes) . ' )');
            }
        }

        // add filter - location
        if (isset($filters['location'])) {
            $qb->where('p.location = :location')->setParameter('location', $filters['location']);
        }

        // add filter - brand
        if (isset($filters['brand'])) {
            $qb->where('p.brand = :brand')->setParameter('brand', $filters['brand']);
        }

        // set sort mode - price acs by default
        $qb->orderBy('p.price', 'ASC');

        // // debug
        // $myQuery = $qb->getQuery();
        // var_dump($myQuery->getSQL);
        // var_dump($myQuery->getParameters);
        // exit();

        // get result of query
        return $qb->getQuery()->getResult();
    }


    //    /**
    //     * @return Pricing[] Returns an array of Pricing objects
    //     */
    //    public function findByExampleField($value): array
    //    {
    //        return $this->createQueryBuilder('p')
    //            ->andWhere('p.exampleField = :val')
    //            ->setParameter('val', $value)
    //            ->orderBy('p.id', 'ASC')
    //            ->setMaxResults(10)
    //            ->getQuery()
    //            ->getResult()
    //        ;
    //    }

    //    public function findOneBySomeField($value): ?Pricing
    //    {
    //        return $this->createQueryBuilder('p')
    //            ->andWhere('p.exampleField = :val')
    //            ->setParameter('val', $value)
    //            ->getQuery()
    //            ->getOneOrNullResult()
    //        ;
    //    }
}