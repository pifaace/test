<?php

namespace App\Controller;

use App\Entity\Student;
use App\Repository\GradeRepository;
use Symfony\Component\HttpFoundation\JsonResponse;

class StudentAverage
{
    private GradeRepository $gradeRepository;

    public function __construct(GradeRepository $gradeRepository)
    {
        $this->gradeRepository = $gradeRepository;
    }

    public function __invoke(Student $data)
    {
        assert($data instanceof Student);
        $averageBySubject = $this->gradeRepository->getStudentAverageBySubject($data);
        $sum = 0;
        $average = null;

        foreach ($averageBySubject as $average) {
            $sum += $average;
            $average = number_format($sum / count($averageBySubject), 1);
        }

        return new JsonResponse(array_merge([
            'global_average' => null !== $average ? (string) $average : 'No grade',
        ], $averageBySubject));
    }
}
