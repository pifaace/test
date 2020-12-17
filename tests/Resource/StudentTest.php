<?php

declare(strict_types=1);

namespace App\Tests\Resource;

use ApiPlatform\Core\Bridge\Symfony\Bundle\Test\ApiTestCase;
use App\Entity\Student;
use Hautelook\AliceBundle\PhpUnit\RefreshDatabaseTrait;

class StudentTest extends ApiTestCase
{
    use RefreshDatabaseTrait;

    public function testCreateStudentFirstNameIsMissing()
    {
        static::createClient()->request('POST', '/api/students', [
            'headers' => [
                'Content-Type' => 'application/json',
                'accept' => 'application/json'
            ],
            'json' => [
                'lastName' => 'Bon',
                'birthday' => '2010-01-15',
            ],
        ]);

        $this->assertResponseStatusCodeSame(400);
        $this->assertJsonContains([
            'violations' => [
                [
                    'propertyPath' => 'firstName',
                    'message' => 'This value should not be blank.',
                ]
            ]
        ]);
    }

    public function testCreateStudentLastNameIsMissing()
    {
        static::createClient()->request('POST', '/api/students', [
            'headers' => [
                'Content-Type' => 'application/json',
                'accept' => 'application/json'
            ],
            'json' => [
                'firstName' => 'Jean',
                'birthday' => '2010-01-15',
            ],
        ]);

        $this->assertResponseStatusCodeSame(400);
        $this->assertJsonContains([
            'violations' => [
                [
                    'propertyPath' => 'lastName',
                    'message' => 'This value should not be blank.',
                ]
            ]
        ]);
    }

    public function testCreateStudentBirthdayIsMissing()
    {
        static::createClient()->request('POST', '/api/students', [
            'headers' => [
                'Content-Type' => 'application/json',
                'accept' => 'application/json'
            ],
            'json' => [
                'firstName' => 'Jean',
                'lastName' => 'Bon',
            ],
        ]);

        $this->assertResponseStatusCodeSame(400);
        $this->assertJsonContains([
            'violations' => [
                [
                    'propertyPath' => 'birthday',
                    'message' => 'This value should not be blank.',
                ]
            ]
        ]);
    }

    public function testCreateStudentSuccessfully()
    {
        static::createClient()->request('POST', '/api/students', [
            'headers' => [
                'Content-Type' => 'application/json',
                'accept' => 'application/json'
            ],
            'json' => [
                'firstName' => 'Jean',
                'lastName' => 'Bon',
                'birthday' => '2010-01-15',
            ],
        ]);

        $this->assertResponseStatusCodeSame(201);
        $this->assertJsonContains([
            'firstName' => 'Jean',
            'lastName' => 'Bon',
            'birthday' => '2010-01-15T00:00:00+01:00'
        ]);
    }

    public function testEditStudent()
    {
        $client = self::createClient();
        $em = self::$kernel->getContainer()->get('doctrine')->getManager();
        $student = $em->getRepository(Student::class)->findOneByLastName('blanc');
        assert($student instanceof Student);
        $client->request('PUT', '/api/students/'.$student->getId(), [
            'headers' => [
                'Content-Type' => 'application/json',
                'accept' => 'application/json'
            ],
            'json' => [
                'lastName' => 'Bonbon',
            ],
        ]);

        $this->assertResponseStatusCodeSame(200);
        $this->assertJsonContains([
            'firstName' => 'michel',
            'lastName' => 'Bonbon',
            'birthday' => '2010-02-13T00:00:00+01:00',
        ]);
    }

    public function testRemoveStudent()
    {
        $client = self::createClient();
        $em = self::$kernel->getContainer()->get('doctrine')->getManager();
        $student = $em->getRepository(Student::class)->findOneByLastName('blanc');
        assert($student instanceof Student);
        $client->request('DELETE', '/api/students/'.$student->getId(), [
            'headers' => [
                'Content-Type' => 'application/json',
                'accept' => 'application/json'
            ],
        ]);

        $this->assertResponseStatusCodeSame(204);
    }

}
