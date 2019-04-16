<?php

namespace App\Repository;

use App\Entity\Customer;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Symfony\Bridge\Doctrine\RegistryInterface;

/**
 * @method Customer|null find($id, $lockMode = null, $lockVersion = null)
 * @method Customer|null findOneBy(array $criteria, array $orderBy = null)
 * @method Customer[]    findAll()
 * @method Customer[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class CustomerRepository extends ServiceEntityRepository
{
    public function __construct(RegistryInterface $registry)
    {
        parent::__construct($registry, Customer::class);
    }

    /**
     * @return Customer[] Returns an array of Customer objects
     */

    public function findAllCustomers($id)
    {
        return $this->createQueryBuilder('p')
            ->select('COUNT(p.orderCustomer)')
            ->where('p.orderCustomer = :val')
            ->setParameter('val', $id)
            ->getQuery()
            ->getSingleScalarResult();
        ;
    }

    public function findAllPrices($id)
    {
        return $this->createQueryBuilder('p')
            ->select('SUM(p.ticketPrice)')
            ->where('p.orderCustomer = :val')
            ->setParameter('val', $id)
            ->getQuery()
            ->getSingleScalarResult();
        ;
    }

    /*
    public function findOneBySomeField($value): ?Customer
    {
        return $this->createQueryBuilder('c')
            ->andWhere('c.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */
}
