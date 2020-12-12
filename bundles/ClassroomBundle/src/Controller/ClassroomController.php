<?php

declare(strict_types=1);

namespace Inner\ClassroomBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Routing\Annotation\Route;

class ClassroomController extends AbstractController
{
    /**
     * @Route("", methods={"GET"})
     *
     * @return JsonResponse
     */
    public function create(): JsonResponse
    {
        return $this->json(['hello']);
    }
}
