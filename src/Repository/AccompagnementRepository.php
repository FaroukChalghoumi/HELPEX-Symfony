<?php

namespace App\Repository;

use App\Entity\Accompagnement;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<Accompagnement>
 *
 * @method Accompagnement|null find($id, $lockMode = null, $lockVersion = null)
 * @method Accompagnement|null findOneBy(array $criteria, array $orderBy = null)
 * @method Accompagnement[]    findAll()
 * @method Accompagnement[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class AccompagnementRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Accompagnement::class);
    }

    public function save(Accompagnement $entity, bool $flush = false): void
    {
        $this->getEntityManager()->persist($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }

    public function remove(Accompagnement $entity, bool $flush = false): void
    {
        $this->getEntityManager()->remove($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }

    /**
     * @return Accompagnement[] Returns an array of Accompagnement objects
     */
    public function findByAccompagnementEmail($value): array
    {
        return $this->createQueryBuilder('a')
            ->join('a.user', 'u')
                ->where('u.email = :val')
                ->setParameter('val', $value)
                ->getQuery()
                ->getResult();
        ;
    }
    public function findByAccompagnementEmailUserProandStatus($value): array
    {
        return $this->createQueryBuilder('a')
            ->join('a.user_pro', 'u')
            ->where('u.email = :val')
            ->andWhere('a.is_accepted = :vala')
            ->setParameter('val', $value)
            ->setParameter('vala', 1)
            ->getQuery()
            ->getResult();
        ;
    }
    public function findByAccompagnementEmailUserandStatus($value): array
    {
        return $this->createQueryBuilder('a')
            ->join('a.user', 'u')
            ->where('u.email = :val')
            ->andWhere('a.is_accepted = :vala')
            ->setParameter('val', $value)
            ->setParameter('vala', 1)
            ->getQuery()
            ->getResult();
        ;
    }
    public function findByAccompagnementEmailUserPro($pro): array
    {
        return $this->createQueryBuilder('a')
            ->join('a.user_pro', 'p')
            ->andWhere('p.email = :vala')
            ->setParameter('vala', $pro)
            ->getQuery()
            ->getResult();
        ;
    }
   public function findOneByEmailTask($email,$task): ?Accompagnement
  {
      return $this->createQueryBuilder('a')
            ->join('a.user', 'u')
            ->where('u.email = :vale')
            ->andWhere('a.task = :valt')
            ->setParameter('vale', $email)
            ->setParameter('valt', $task)
            ->getQuery()
            ->getOneOrNullResult()
       ;
    }
}
