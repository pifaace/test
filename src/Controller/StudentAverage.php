<?php

namespace App\Controller;

use App\Entity\Student;
use Symfony\Component\HttpFoundation\JsonResponse;

class StudentAverage
{
    public function __invoke(Student $data)
    {
        assert($data instanceof Student);
        $sum = 0;
        $average = null;

        foreach ($data->getGrades() as $grade) {
            $sum += $grade->getValue();
            $average = $sum / $data->getGrades()->count();
        }

        return new JsonResponse([
            'average' => null !== $average ? (string) $average : 'No grade',
        ]);
    }
}
