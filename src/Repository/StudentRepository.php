<?php

namespace App\Repository;

<<<<<<<< HEAD:src/Repository/StudentRepository.php
use App\Entity\Student;
========
use App\Entity\Filiere;
>>>>>>>> ParaShop:src/Repository/FiliereRepository.php
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
<<<<<<<< HEAD:src/Repository/StudentRepository.php
 * @extends ServiceEntityRepository<Student>
 *
 * @method Student|null find($id, $lockMode = null, $lockVersion = null)
 * @method Student|null findOneBy(array $criteria, array $orderBy = null)
 * @method Student[]    findAll()
 * @method Student[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class StudentRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Student::class);
    }

    public function save(Student $entity, bool $flush = false): void
========
 * @extends ServiceEntityRepository<Filiere>
 *
 * @method Filiere|null find($id, $lockMode = null, $lockVersion = null)
 * @method Filiere|null findOneBy(array $criteria, array $orderBy = null)
 * @method Filiere[]    findAll()
 * @method Filiere[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class FiliereRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Filiere::class);
    }

    public function save(Filiere $entity, bool $flush = false): void
>>>>>>>> ParaShop:src/Repository/FiliereRepository.php
    {
        $this->getEntityManager()->persist($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }

<<<<<<<< HEAD:src/Repository/StudentRepository.php
    public function remove(Student $entity, bool $flush = false): void
========
    public function remove(Filiere $entity, bool $flush = false): void
>>>>>>>> ParaShop:src/Repository/FiliereRepository.php
    {
        $this->getEntityManager()->remove($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }

//    /**
<<<<<<<< HEAD:src/Repository/StudentRepository.php
//     * @return Student[] Returns an array of Student objects
========
//     * @return Filiere[] Returns an array of Filiere objects
>>>>>>>> ParaShop:src/Repository/FiliereRepository.php
//     */
//    public function findByExampleField($value): array
//    {
//        return $this->createQueryBuilder('f')
//            ->andWhere('f.exampleField = :val')
//            ->setParameter('val', $value)
//            ->orderBy('f.id', 'ASC')
//            ->setMaxResults(10)
//            ->getQuery()
//            ->getResult()
//        ;
//    }

<<<<<<<< HEAD:src/Repository/StudentRepository.php
//    public function findOneBySomeField($value): ?Student
========
//    public function findOneBySomeField($value): ?Filiere
>>>>>>>> ParaShop:src/Repository/FiliereRepository.php
//    {
//        return $this->createQueryBuilder('f')
//            ->andWhere('f.exampleField = :val')
//            ->setParameter('val', $value)
//            ->getQuery()
//            ->getOneOrNullResult()
//        ;
//    }
}
