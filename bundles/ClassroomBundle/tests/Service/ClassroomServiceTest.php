<?php

declare(strict_types=1);

namespace Inner\ClassroomBundle\Tests\Service;

use Doctrine\ORM\EntityManager;
use Inner\ClassroomBundle\Entity\Classroom;
use Inner\ClassroomBundle\Exception\NotFoundException;
use Inner\ClassroomBundle\Exception\ValidationException;
use Inner\ClassroomBundle\Repository\ClassroomRepository;
use Inner\ClassroomBundle\Service\ClassroomService;
use PHPUnit\Framework\TestCase;
use Symfony\Component\Validator\ConstraintViolation;
use Symfony\Component\Validator\ConstraintViolationList;
use Symfony\Component\Validator\Validator\ValidatorInterface;

class ClassroomTest extends TestCase
{
    public function testGetClassrooms()
    {
        $classroomMocks = $this->getClassroomMocks();

        $classroomService = new ClassroomService($this->getEmMock($classroomMocks), $this->getValidatorMock());

        $classrooms = $classroomService->getAll();

        $this->assertCount(2, $classrooms);
        $this->assertContains($classroomMocks[0], $classrooms);
        $this->assertContains($classroomMocks[1], $classrooms);
    }

    public function testGetClassroom()
    {
        $classroomMocks = $this->getClassroomMocks();
        $classroomService = new ClassroomService($this->getEmMock($classroomMocks), $this->getValidatorMock());
        $classroomMock = $classroomMocks[0];

        $classroom = $classroomService->get($classroomMock->getId());

        $this->assertEquals($classroomMock->getId(), $classroom->getId());
        $this->assertEquals($classroomMock->getName(), $classroom->getName());
        $this->assertEquals($classroomMock->getCreatedAt(), $classroom->getCreatedAt());
        $this->assertEquals($classroomMock->isActive(), $classroom->isActive());
    }

    public function testGetClassroomNotFoundException()
    {
        $this->expectException(NotFoundException::class);

        $classroomMocks = $this->getClassroomMocks();

        $classroomService = new ClassroomService($this->getEmMock($classroomMocks), $this->getValidatorMock());

        $classroomService->get(999);
    }

    public function testCreateClassroom()
    {
        $classroomIdMock = 1;
        $classroomMock = [
            'name' => 'Class name 1',
            'isActive' => true,
        ];

        $em = $this->createMock(EntityManager::class);
        $em->expects($this->any())
            ->method('persist')
            ->will(
                $this->returnCallback(
                    function ($object) use ($classroomIdMock) {
                        if ($object instanceof Classroom) {
                            $object->hydrate(['id' => $classroomIdMock], true);
                        }
                    }
                )
            );

        $em->expects($this->any())->method('flush');

        $classroomService = new ClassroomService($em, $this->getValidatorMock());

        $classroom = $classroomService->create($classroomMock);

        $this->assertEquals($classroomIdMock, $classroom->getId());
        $this->assertEquals($classroomMock['name'], $classroom->getName());
        $this->assertEquals($classroomMock['isActive'], $classroom->isActive());
        $this->assertInstanceOf(\DateTimeImmutable::class, $classroom->getCreatedAt());
    }

    public function testCreateClassroomValidationException()
    {
        $this->expectException(ValidationException::class);

        $classroomIdMock = 1;
        $invalidClassroomMock1 = ['name' => 'Class Name'];

        $em = $this->createMock(EntityManager::class);
        $em->expects($this->any())
            ->method('persist')
            ->will(
                $this->returnCallback(
                    function ($object) use ($classroomIdMock) {
                        if ($object instanceof Classroom) {
                            $object->hydrate(['id' => $classroomIdMock], true);
                        }
                    }
                )
            );

        $em->expects($this->any())->method('flush');

        $classroomService = new ClassroomService($em, $this->getValidatorMock(false));

        $classroomService->create($invalidClassroomMock1);
    }

    public function testUpdateClassroom()
    {
        $newData = [
            'name' => 'Class name 5',
            'isActive' => false,
        ];

        $classroomMocks = $this->getClassroomMocks();

        $em = $this->getEmMock($classroomMocks);
        $em->expects($this->any())->method('persist');
        $em->expects($this->any())->method('flush');

        $classroomService = new ClassroomService($em, $this->getValidatorMock());

        $updatedClassroom = $classroomService->update($classroomMocks[0]->getId(), $newData);

        $this->assertEquals($classroomMocks[0]->getId(), $updatedClassroom->getId());
        $this->assertEquals($newData['name'], $updatedClassroom->getName());
        $this->assertEquals($newData['isActive'], $updatedClassroom->isActive());
        $this->assertInstanceOf(\DateTimeImmutable::class, $updatedClassroom->getCreatedAt());
    }

    public function testUpdateClassroomNotFoundException()
    {
        $this->expectException(NotFoundException::class);

        $classroomMocks = $this->getClassroomMocks();

        $classroomService = new ClassroomService($this->getEmMock($classroomMocks), $this->getValidatorMock());

        $classroomService->update(999, ['name' => 'Classroom2']);
    }

    public function testUpdateClassroomValidationException()
    {
        $this->expectException(ValidationException::class);

        $classroomIdMock = 1;
        $invalidClassroomMock1 = ['name' => 1];

        $classroomMocks = $this->getClassroomMocks();

        $em = $this->getEmMock($classroomMocks);

        $em->expects($this->any())
            ->method('persist')
            ->will(
                $this->returnCallback(
                    function ($object) use ($classroomIdMock) {
                        if ($object instanceof Classroom) {
                            $object->hydrate(['id' => $classroomIdMock], true);
                        }
                    }
                )
            );

        $em->expects($this->any())->method('flush');

        $classroomService = new ClassroomService($em, $this->getValidatorMock(false));

        $classroomService->update($classroomIdMock, $invalidClassroomMock1);
    }

    /**
     * @return Classroom[]
     */
    private function getClassroomMocks(): array
    {
        $classroom = new Classroom();
        $classroom->hydrate(
            [
                'id' => 1,
                'name' => 'Class Name 1',
                'createdAt' => new \DateTimeImmutable(),
                'isActive' => true,
            ],
            true
        );

        $classroom2 = new Classroom();
        $classroom2->hydrate(
            [
                'id' => 2,
                'name' => 'Class Name 2',
                'createdAt' => new \DateTimeImmutable(),
                'isActive' => false,
            ],
            true
        );

        return [$classroom, $classroom2];
    }

    /**
     * Bad validator, find way to make real validation of data
     *
     * @param bool $isValid
     *
     * @return ValidatorInterface
     */
    private function getValidatorMock(bool $isValid = true): ValidatorInterface
    {
        $constraints = [];

        if (!$isValid) {
            $constraints = [new ConstraintViolation('', null, [], null, null, null)];
        }

        $validator = $this->createMock(ValidatorInterface::class);
        $validator->method('validate')->will($this->returnValue(new ConstraintViolationList($constraints)));

        return $validator;
    }

    private function getEmMock(array $mockedClassrooms)
    {
        $emMock = $this->createMock(EntityManager::class);
        $emMock->expects($this->any())
            ->method('getRepository')
            ->willReturn($this->getRepositoryMock($mockedClassrooms));

        return $emMock;
    }

    private function getRepositoryMock(array $classroomMocks)
    {
        $classroomRepositoryMock = $this->createMock(ClassroomRepository::class);

        $classroomRepositoryMock->expects($this->any())
            ->method('find')
            ->willReturnCallback(
                function ($id) use ($classroomMocks) {
                    foreach ($classroomMocks as $classroom) {
                        if ($classroom->getId() === $id) {
                            return $classroom;
                        }
                    }

                    return null;
                }
            );

        $classroomRepositoryMock->expects($this->any())
            ->method('findAll')
            ->willReturn($classroomMocks);

        return $classroomRepositoryMock;
    }
}
