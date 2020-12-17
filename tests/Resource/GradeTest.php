<?php

namespace App\Tests\Resource;

use ApiPlatform\Core\Bridge\Symfony\Bundle\Test\ApiTestCase;
use App\Entity\Student;
use Hautelook\AliceBundle\PhpUnit\RefreshDatabaseTrait;

class GradeTest extends ApiTestCase
{
    use RefreshDatabaseTrait;

    public function testCreateGradeValueIsMissing()
    {
        $client = self::createClient();
        $em = self::$kernel->getContainer()->get('doctrine')->getManager();
        $student = $em->getRepository(Student::class)->findOneByLastName('blanc');
        assert($student instanceof Student);
        $client->request('POST', '/api/grades', [
            'headers' => [
                'Content-Type' => 'application/json',
                'accept' => 'application/json',
            ],
            'json' => [
                'subject' => 'english',
                'student' => "/api/students/{$student->getId()}",
            ],
        ]);

        $this->assertResponseStatusCodeSame(400);
        $this->assertJsonContains([
            'violations' => [
                [
                    'propertyPath' => 'value',
                    'message' => 'This value should not be blank.',
                ],
            ],
        ]);
    }

    public function testCreateGradeValueIsLessThanZero()
    {
        $client = self::createClient();
        $em = self::$kernel->getContainer()->get('doctrine')->getManager();
        $student = $em->getRepository(Student::class)->findOneByLastName('blanc');
        assert($student instanceof Student);
        $client->request('POST', '/api/grades', [
            'headers' => [
                'Content-Type' => 'application/json',
                'accept' => 'application/json',
            ],
            'json' => [
                'value' => -1,
                'subject' => 'english',
                'student' => "/api/students/{$student->getId()}",
            ],
        ]);

        $this->assertResponseStatusCodeSame(400);
        $this->assertJsonContains([
            'violations' => [
                [
                    'propertyPath' => 'value',
                    'message' => 'The rating must be between 0 and 20',
                ],
            ],
        ]);
    }

    public function testCreateGradeValueIsMoreThanTwenty()
    {
        $client = self::createClient();
        $em = self::$kernel->getContainer()->get('doctrine')->getManager();
        $student = $em->getRepository(Student::class)->findOneByLastName('blanc');
        assert($student instanceof Student);
        $client->request('POST', '/api/grades', [
            'headers' => [
                'Content-Type' => 'application/json',
                'accept' => 'application/json',
            ],
            'json' => [
                'value' => 25,
                'subject' => 'english',
                'student' => "/api/students/{$student->getId()}",
            ],
        ]);

        $this->assertResponseStatusCodeSame(400);
        $this->assertJsonContains([
            'violations' => [
                [
                    'propertyPath' => 'value',
                    'message' => 'The rating must be between 0 and 20',
                ],
            ],
        ]);
    }

    public function testCreateGradeSubjectIsMissing()
    {
        $client = self::createClient();
        $em = self::$kernel->getContainer()->get('doctrine')->getManager();
        $student = $em->getRepository(Student::class)->findOneByLastName('blanc');
        assert($student instanceof Student);
        $client->request('POST', '/api/grades', [
            'headers' => [
                'Content-Type' => 'application/json',
                'accept' => 'application/json',
            ],
            'json' => [
                'value' => 14,
                'student' => "/api/students/{$student->getId()}",
            ],
        ]);

        $this->assertResponseStatusCodeSame(400);
        $this->assertJsonContains([
            'violations' => [
                [
                    'propertyPath' => 'subject',
                    'message' => 'This value should not be blank.',
                ],
            ],
        ]);
    }

    public function testCreateGradeStudentIsMissing()
    {
        $client = self::createClient();
        $client->request('POST', '/api/grades', [
            'headers' => [
                'Content-Type' => 'application/json',
                'accept' => 'application/json',
            ],
            'json' => [
                'value' => 14,
                'subject' => 'english',
            ],
        ]);

        $this->assertResponseStatusCodeSame(400);
        $this->assertJsonContains([
            'violations' => [
                [
                    'propertyPath' => 'student',
                    'message' => 'This value should not be blank.',
                ],
            ],
        ]);
    }

    public function testCreateGradeSuccessfully()
    {
        $client = self::createClient();
        $em = self::$kernel->getContainer()->get('doctrine')->getManager();
        $student = $em->getRepository(Student::class)->findOneByLastName('blanc');
        assert($student instanceof Student);
        $client->request('POST', '/api/grades', [
            'headers' => [
                'Content-Type' => 'application/json',
                'accept' => 'application/json',
            ],
            'json' => [
                'value' => 14,
                'subject' => 'english',
                'student' => "/api/students/{$student->getId()}",
            ],
        ]);

        $this->assertResponseStatusCodeSame(201);
        $this->assertJsonContains([
            'value' => 14,
            'subject' => 'english',
            'student' => "/api/students/{$student->getId()}",
        ]);
    }

    public function testGetGlobalAverage()
    {
        $client = self::createClient();
        $client->request('GET', '/api/grades/average', [
            'headers' => [
                'accept' => 'application/json',
            ],
        ]);

        $this->assertResponseStatusCodeSame(200);
        $this->assertJsonContains([
            'average' => '12.75',
        ]);
    }
}
