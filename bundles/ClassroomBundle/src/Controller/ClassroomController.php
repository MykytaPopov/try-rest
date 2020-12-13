<?php

declare(strict_types=1);

namespace Inner\ClassroomBundle\Controller;

use Inner\ClassroomBundle\Exception\NotFoundException;
use Inner\ClassroomBundle\Exception\ValidationException;
use Inner\ClassroomBundle\Service\ClassroomService;
use Inner\ClassroomBundle\Service\ClassroomServiceInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Serializer\SerializerInterface;
use Throwable;

/**
 * Class ClassroomController
 *
 * @Route("/classrooms", name="classrooms")
 */
class ClassroomController extends AbstractController
{
    /**
     * @var ClassroomServiceInterface
     */
    private ClassroomServiceInterface $classroomService;

    /**
     * @var SerializerInterface
     */
    private SerializerInterface $serializer;

    public function __construct(ClassroomServiceInterface $classroomService, SerializerInterface $serializer)
    {
        $this->classroomService = $classroomService;
        $this->serializer = $serializer;
    }

    /**
     * @Route("", methods={"GET"}, name="classrooms:getAll")
     *
     * @param ClassroomService $classroomService
     *
     * @return JsonResponse
     */
    public function getClassrooms(ClassroomService $classroomService): JsonResponse
    {
        try {
            $classrooms = $classroomService->getAll();

            return $this->json($classrooms);
        } catch (Throwable $e) {
            return $this->json($e->getMessage(), 500);
        }
    }

    /**
     * @Route("/{id}", methods={"GET"}, name="classroom:get")
     *
     * @param int $id
     *
     * @return JsonResponse
     */
    public function getClassroom(int $id): JsonResponse
    {
        try {
            $classroom = $this->classroomService->get($id);

            return $this->json($classroom);
        } catch (NotFoundException $e) {
            return $this->json($e->getMessage(), 404);
        } catch (Throwable $e) {
            return $this->json($e->getMessage(), 500);
        }
    }

    /**
     * @Route("", methods={"POST"})
     *
     * @param Request $request
     *
     * @return JsonResponse
     */
    public function createClassroom(Request $request): JsonResponse
    {
        try {
            $classroom = $this->classroomService->create(json_decode($request->getContent(), true));

            return $this->json($classroom, 201);
        } catch (ValidationException $e) {
            return $this->json($e->getMessage(), 400);
        } catch (Throwable $e) {
            return $this->json($e->getMessage(), 500);
        }
    }

    /**
     * @Route("/{id}", methods={"PUT"})
     *
     * @param int     $id
     * @param Request $request
     *
     * @return JsonResponse
     */
    public function updateClassroom(int $id, Request $request): JsonResponse
    {
        try {
            $classroom = $this->classroomService->update($id, json_decode($request->getContent(), true));

            return $this->json($classroom);
        } catch (NotFoundException $e) {
            return $this->json($e->getMessage(), 404);
        } catch (ValidationException $e) {
            return $this->json($e->getMessage(), 400);
        } catch (Throwable $e) {
            return $this->json($e->getMessage(), 500);
        }
    }

    /**
     * @Route("/{id}", methods={"DELETE"})
     *
     * @param int $id
     *
     * @return JsonResponse
     */
    public function deleteClassroom(int $id): JsonResponse
    {
        try {
            $this->classroomService->delete($id);

            return $this->json(null, 204);
        } catch (NotFoundException $e) {
            return $this->json($e->getMessage(), 404);
        } catch (Throwable $e) {
            return $this->json($e->getMessage(), 500);
        }
    }

    /**
     * @inheritDoc
     */
    protected function json($data, int $status = 200, array $headers = [], array $context = []): JsonResponse
    {
        $responseKey = $status >= 400 ? 'error' : 'data';

        return parent::json([$responseKey => $data], $status);
    }
}
