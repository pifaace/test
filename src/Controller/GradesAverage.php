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
        $grades = $this->gradeRepository->findAll();
        $average = null;
        $sum = 0;

        foreach ($grades as $grade) {
            $sum += $grade->getValue();
            $average = $sum / count($grades);
        }

        return new JsonResponse([
            'average' => $average !== null ? (string) $average : 'No grade'
        ]);
    }
}
