<?php

namespace App\Repository;

use App\Entity\Sortie;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\ORM\OptimisticLockException;
use Doctrine\ORM\ORMException;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method Sortie|null find($id, $lockMode = null, $lockVersion = null)
 * @method Sortie|null findOneBy(array $criteria, array $orderBy = null)
 * @method Sortie[]    findAll()
 * @method Sortie[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class SortieRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Sortie::class);
    }

    /**
     * @throws ORMException
     * @throws OptimisticLockException
     */
    public function add(Sortie $entity, bool $flush = true): void
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
    public function remove(Sortie $entity, bool $flush = true): void
    {
        $this->_em->remove($entity);
        if ($flush) {
            $this->_em->flush();
        }
    }

    public function sortieFiltre($nom, $idCampus, $datedebut, $datefin, $organisateur, $inscrit)
    {
        $queryBuilder = $this->createQueryBuilder('sortie');

        $queryBuilder
            ->andWhere('sortie.nom like :nom')
            ->setParameter(':nom', '%'.$nom.'%');

        if ($idCampus) {
            $queryBuilder -> andWhere('sortie.campus = :idCampus')
                ->setParameter(':idCampus', $idCampus);
        }

        if ($datedebut){
            $queryBuilder ->andWhere('sortie.dateHeureDebut >= :datedebut')
                ->setParameter(':datedebut', $datedebut);
        }
        if ($datefin){
            $queryBuilder ->andWhere('sortie.dateHeureDebut <= :datefin')
                ->setParameter(':datefin', $datefin);
        }
        if ($organisateur){
            $queryBuilder ->andWhere('sortie.organisateur = :organisateur')
                ->setParameter(':organisateur', $organisateur);

        }
        if ($inscrit){
            $queryBuilder ->join('sortie.participants', 'p')
                ->andWhere('p = :inscrit')
                ->setParameter(':inscrit', $inscrit );
        }

        $query = $queryBuilder->getQuery();
        return $query->getResult();
    }

    // /**
    //  * @return Sortie[] Returns an array of Sortie objects
    //  */
    /*
    public function findByExampleField($value)
    {
        return $this->createQueryBuilder('s')
            ->andWhere('s.exampleField = :val')
            ->setParameter('val', $value)
            ->orderBy('s.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    */

    /*
    public function findOneBySomeField($value): ?Sortie
    {
        return $this->createQueryBuilder('s')
            ->andWhere('s.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */
}
