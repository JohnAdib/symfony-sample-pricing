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


        // add filter - storage type
        if (isset($filters['storagetype'])) {
            // get list of storagetype and filter them to remove null and empty values
            $storageTypes = array_filter($filters['storagetype']);
            $storageTypesIn = implode(',', $storageTypes);
            $qb->where('p.storagetype IN ( ' . $storageTypesIn . ' )');
        }

        // var_dump($qb);
        // var_dump($filters);
        // exit();

        // $newerThan = '';
        // $qb->where('p.access > :newerThan')->setParameter('newerThan', $newerThan);


        // set sort mode - price acs by default
        $qb->orderBy('p.price', 'ASC');

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