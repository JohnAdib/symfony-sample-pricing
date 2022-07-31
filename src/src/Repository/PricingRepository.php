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
    public function advanceSearch(array $filters, bool $needArrayOfArrays = true): array
    {
        $entityManager = $this->getEntityManager();
        // create query builder on pricing table
        $qb = $this->createQueryBuilder('p');

        // add filter - price-min
        if (isset($filters['price-min']) && is_numeric($filters['price-min'])) {
            // @todo change price text to int
            $qb->andWhere('p.price > :pricemin')->setParameter('pricemin', $filters['price-min']);
        }
        // add filter - price-max
        if (isset($filters['price-max']) && is_numeric($filters['price-max'])) {
            // @todo change price text to int
            $qb->andWhere('p.price < :pricemax')->setParameter('pricemax', $filters['price-max']);
        }

        // add filter - storage-min
        if (isset($filters['storage-min'])) {
            $storageMin = $filters['storage-min'];
            $storageMin = $this->convertValToGb($storageMin);
            if (is_numeric($storageMin)) {
                $qb->andWhere('p.storage > :storagemin')->setParameter('storagemin', $storageMin);
            }
        }
        // add filter - storage-max
        if (isset($filters['storage-max'])) {
            $storageMax = $filters['storage-max'];
            $storageMax = $this->convertValToGb($storageMax);
            if (is_numeric($storageMax)) {
                $qb->andWhere('p.storage < :storagemax')->setParameter('storagemax', $storageMax);
            }
        }

        // add filter - ram
        // ram can filter in 3 mode, array, range and single value
        if (isset($filters['ram'])) {
            // array passed - like ram[]=96
            if (is_array($filters['ram'])) {
                // array mode
                // get list of ram and filter them to remove null and empty values
                $rams = array_filter($filters['ram']);
                $rams = array_map(array($this, 'convertValToGb'), $rams);
                if (count($rams) >= 1) {
                    $qb->andWhere('p.ram IN ( :rams )');
                    $qb->setParameter('rams', $rams);
                }
            } else {
                // single mode
                $qb->andWhere('p.ram = :ram')->setParameter('ram', $this->convertValToGb($filters['ram']));
            }
        } else {
            // range mode

            // add filter - ram-min
            if (isset($filters['ram-min'])) {
                $ramMin = $filters['ram-min'];
                $ramMin = $this->convertValToGb($ramMin);
                if (is_numeric($ramMin)) {
                    $qb->andWhere('p.ram > :rammin')->setParameter('rammin', $ramMin);
                }
            }

            // add filter - ram-max
            if (isset($filters['ram-max'])) {
                $ramMax = $filters['ram-max'];
                $ramMax = $this->convertValToGb($ramMax);
                if (is_numeric($ramMax)) {
                    $qb->andWhere('p.ram < :rammax')->setParameter('rammax', $ramMax);
                }
            }
        }

        // add filter - storage type
        if (isset($filters['storagetype'])) {
            if (is_array($filters['storagetype'])) {
                // array mode
                // get list of storagetype and filter them to remove null and empty values
                $storageTypes = array_filter($filters['storagetype']);
                // if after filter count of array is 1 or more, apply filter
                if (count($storageTypes) >= 1) {
                    $qb->andWhere('p.storagetype IN ( :storagetypes )');
                    $qb->setParameter('storagetypes', $storageTypes);
                    // $qb->setParameter('storagetypes', $storageTypes, \Doctrine\DBAL\Connection::PARAM_STR_ARRAY);
                }
            } else {
                // single mode
                $qb->andWhere('p.storagetype = :storagetype')->setParameter('storagetype', $filters['storagetype']);
            }
        }

        // add filter - location
        if (isset($filters['location'])) {
            if (is_array($filters['location'])) {
                // array mode
                // get list of location and filter them to remove null and empty values
                $locations = array_filter($filters['location']);
                // if after filter count of array is 1 or more, apply filter
                if (count($locations) >= 1) {
                    $qb->andWhere('p.location IN ( :locations )');
                    $qb->setParameter('locations', $locations);
                }
            } else {
                // single mode
                $qb->andWhere('p.location = :location')->setParameter('location', $filters['location']);
            }
        }


        // add filter - brand
        if (isset($filters['brand'])) {
            if (is_array($filters['brand'])) {
                // array mode
                // get list of brand and filter them to remove null and empty values
                $brands = array_filter($filters['brand']);
                // if after filter count of array is 1 or more, apply filter
                if (count($brands) >= 1) {
                    $qb->andWhere('p.brand IN ( :brands )');
                    $qb->setParameter('brands', $brands);
                }
            } else {
                // single mode
                $qb->andWhere('p.brand = :brand')->setParameter('brand', $filters['brand']);
            }
        }


        // set sort mode - price acs by default
        $orderby = 'asc';
        if (isset($filters['orderby'])) {
            $orderby = $filters['orderby'];
        }

        // set order of result
        switch ($orderby) {
            case 'asc':
            case 'price-asc':
                $qb->orderBy('p.price', 'ASC');
                break;

            case 'desc':
            case 'price-desc':
                $qb->orderBy('p.price', 'DESC');
                break;

            case 'ram-asc':
                $qb->orderBy('p.ram', 'ASC');
                break;

            case 'ram-desc':
                $qb->orderBy('p.ram', 'DESC');
                break;

            case 'storage-asc':
                $qb->orderBy('p.storage', 'ASC');
                break;

            case 'storage-desc':
                $qb->orderBy('p.storage', 'DESC');
                break;

            default:
                $qb->orderBy('p.price', 'ASC');
                break;
        }

        $qb->setMaxResults(1000);

        // save myQuery obj
        $myQuery = $qb->getQuery();
        // returan array of array
        if ($needArrayOfArrays) {
            // get result of query
            return $myQuery->getResult(\Doctrine\ORM\Query::HYDRATE_ARRAY);
        }

        // return array of objects
        return $myQuery->getResult();
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

    /**
     * clear user input value to conver to int or change to lowercase
     *
     * @param  string $val
     * @return mixed
     */
    private function convertValToGb(string $val): int
    {
        $convertedVal = 0;
        if (is_numeric($val)) {
            $convertedVal = intval($val);
        } else {
            $convertedVal = mb_strtolower($val);

            // remove GB from value - for ram and storage or everything
            if (substr($convertedVal, -2) === 'gb') {
                $convertedVal = substr($convertedVal, 0, -2);
                $convertedVal = intval($convertedVal);
            }
            // if we are TB in value
            else if (substr($convertedVal, -2) === 'tb') {
                $convertedVal = substr($convertedVal, 0, -2);
                $convertedVal = intval($convertedVal) * 1000;
            } else {
                $convertedVal = 0;
            }
        }

        return $convertedVal;
    }
}