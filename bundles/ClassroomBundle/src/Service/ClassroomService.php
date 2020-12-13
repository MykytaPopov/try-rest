<?php

declare(strict_types=1);

namespace Inner\ClassroomBundle\Service;

use Doctrine\ORM\EntityManagerInterface;
use Inner\ClassroomBundle\Entity\Classroom;
use Inner\ClassroomBundle\Exception\NotFoundException;
use Inner\ClassroomBundle\Exception\ValidationException;
use Symfony\Component\Validator\ConstraintViolationListInterface;
use Symfony\Component\Validator\Validator\ValidatorInterface;

class ClassroomService implements ClassroomServiceInterface
{
    private EntityManagerInterface $em;

    private ValidatorInterface $validator;

    public function __construct(
        EntityManagerInterface $entityManager,
        ValidatorInterface $validator
    ) {
        $this->em = $entityManager;
        $this->validator = $validator;
    }

    /**
     * @inheritDoc
     */
    public function getAll(): array
    {
        $repository = $this->em->getRepository(Classroom::class);

        return $repository->findAll();
    }

    /**
     * @inheritDoc
     */
    public function get(int $id): Classroom
    {
        $classroom = $this->em->getRepository(Classroom::class)->find($id);

        if (!$classroom) {
            throw new NotFoundException($this->getNotFoundMessage($id));
        }

        return $classroom;
    }

    /**
     * @inheritDoc
     */
    public function create(array $data): Classroom
    {
        $classroom = new Classroom();
        $classroom->hydrate($data);

        $violations = $this->validator->validate($classroom);
        if (count($violations) > 0) {
            throw new ValidationException($this->getViolationsString($violations));
        }

        $this->em->persist($classroom);
        $this->em->flush();

        return $classroom;
    }

    /**
     * @inheritDoc
     */
    public function update(int $id, array $data): Classroom
    {
        $classroom = $this->em->getRepository(Classroom::class)->find($id);

        if (!$classroom) {
            throw new NotFoundException($this->getNotFoundMessage($id));
        }

        $classroom->hydrate($data);

        $violations = $this->validator->validate($classroom);
        if (count($violations) > 0) {
            throw new ValidationException($this->getViolationsString($violations));
        }

        $this->em->persist($classroom);
        $this->em->flush();

        return $classroom;
    }

    /**
     * @inheritDoc
     */
    public function delete(int $id): void
    {
        $classroom = $this->em->getRepository(Classroom::class)->find($id);

        if (!$classroom) {
            throw new NotFoundException($this->getNotFoundMessage($id));
        }

        $this->em->remove($classroom);
        $this->em->flush();
    }

    /**
     * Formats validation errors in to human readable string
     *
     * @param ConstraintViolationListInterface $violations The violations to get errors
     *
     * @return string
     */
    private function getViolationsString(ConstraintViolationListInterface $violations): string
    {
        $message = '';
        foreach ($violations as $violation) {
            $message .= $violation->getPropertyPath() . ': ' . $violation->getMessage() . ' ';
        }

        return rtrim($message);
    }

    /**
     * Get error message for not found resources
     *
     * @param int $id The identifier to be put in to the message
     *
     * @return string
     */
    private function getNotFoundMessage(int $id): string
    {
        return 'Classroom not found, id: ' . $id;
    }
}
