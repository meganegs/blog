<?php

namespace App\Repository;


use App\Entity\Articles;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method Articles|null find($id, $lockMode = null, $lockVersion = null)
 * @method Articles|null findOneBy(array $criteria, array $orderBy = null)
 * @method Articles[]    findAll()
 * @method Articles[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class ArticlesRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Articles::class);
    }

    public function findLastArticles(int $nb=5)
    {
        return $this->createQueryBuilder('n')
            ->andWhere('n.status = :status')
            ->setParameter('status', 'PUBLISHED')
            ->orderBy('n.createdAt', 'DESC')
            ->setMaxResults($nb)
            ->getQuery()
            ->getResult()
        ;
    }
    
    public function findArticlesById(int $id)
    {
        return $this->createQueryBuilder('n')
            ->setParameter('id', $id)
            ->setMaxResults(1)
            ->getQuery()
            ->getResult()
        ;
    }
    

    // /**
    //  * @return Articles[] Returns an array of Articles objects
    //  */
    /*
    public function findByExampleField($value)
    {
        return $this->createQueryBuilder('n')
            ->andWhere('n.exampleField = :val')
            ->setParameter('val', $value)
            ->orderBy('n.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    */

    /*
    public function findOneBySomeField($value): ?Articles
    {
        return $this->createQueryBuilder('n')
            ->andWhere('n.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */
}
