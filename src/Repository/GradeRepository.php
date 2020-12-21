<?php

namespace App\Repository;

use App\Entity\Grade;
use App\Entity\Student;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method Grade|null find($id, $lockMode = null, $lockVersion = null)
 * @method Grade|null findOneBy(array $criteria, array $orderBy = null)
 * @method Grade[]    findAll()
 * @method Grade[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class GradeRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Grade::class);
    }

    public function getGradeAverage()
    {
        return $this->createQueryBuilder('g')
            ->select('AVG(g.value) as average')
            ->getQuery()
            ->getSingleScalarResult();
    }

    public function getStudentAverageBySubject(Student $student)
    {
        $result = $this->createQueryBuilder('g')
            ->select('g.subject, AVG(g.value) as average')
            ->indexBy('g', 'g.subject')
            ->where('g.student = :s')
            ->setParameter(':s', $student)
            ->groupBy('g.subject')
            ->getQuery()
            ->getResult();

        $formatted = [];

        foreach ($result as $index => $item) {
            $formatted[$index] = number_format($item['average'], 1);
        }

        return $formatted;
    }
}
