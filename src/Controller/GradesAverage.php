<?php

namespace App\Controller;

use App\Repository\GradeRepository;
use Symfony\Component\HttpFoundation\JsonResponse;

class GradesAverage
{
    private GradeRepository $gradeRepository;

    public function __construct(GradeRepository $gradeRepository)
    {
        $this->gradeRepository = $gradeRepository;
    }

    public function __invoke()
    {
        $grades = number_format($this->gradeRepository->getGradeAverage(), 2);

        return new JsonResponse([
            'average' => '0.00' !== $grades ? $grades : 'No grade',
        ]);
    }
}
