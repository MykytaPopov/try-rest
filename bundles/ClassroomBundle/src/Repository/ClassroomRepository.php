<?php

declare(strict_types=1);

namespace Inner\ClassroomBundle\Repository;

use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;
use Inner\ClassroomBundle\Entity\Classroom;

/**
 * @method Classroom|null find($id, $lockMode = null, $lockVersion = null)
 * @method Classroom|null findOneBy(array $criteria, array $orderBy = null)
 * @method Classroom[]    findAll()
 * @method Classroom[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class ClassroomRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Classroom::class);
    }
}
