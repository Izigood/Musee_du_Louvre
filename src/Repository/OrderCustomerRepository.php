<?php

namespace App\Repository;

use App\Entity\OrderCustomer;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Symfony\Bridge\Doctrine\RegistryInterface;

/**
 * @method OrderCustomer|null find($id, $lockMode = null, $lockVersion = null)
 * @method OrderCustomer|null findOneBy(array $criteria, array $orderBy = null)
 * @method OrderCustomer[]    findAll()
 * @method OrderCustomer[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class OrderCustomerRepository extends ServiceEntityRepository
{
    public function __construct(RegistryInterface $registry)
    {
        parent::__construct($registry, OrderCustomer::class);
    }

    /**
    * @return OrderCustomer[] Returns an array of OrderCustomer objects
    */
    
    public function findAllTicketsByDateOfVisit($visit)
    {
        return $this->createQueryBuilder('p')
            ->select('SUM(p.numberOfTickets)')
            ->where('p.dateOfVisit = :val')
            ->setParameter('val', $visit)
            ->getQuery()
            ->getSingleScalarResult()
        ;
    }
    
    public function findAllTicketsById($id)
    {
        return $this->createQueryBuilder('p')
            ->select('SUM(p.numberOfTickets)')
            ->where('p.id = :val')
            ->setParameter('val', $id)
            ->getQuery()
            ->getSingleScalarResult()
        ;
    }

    public function findId()
    {
        return $this->createQueryBuilder('p')
            
            ->select('p.id')
            ->orderBy('p.id', 'DESC')
            ->setMaxResults(1)
            ->getQuery()
            ->getSingleScalarResult()
        ;
    }
}
