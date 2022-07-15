<?php

namespace App\Controller;

use App\Entity\Circle;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Routing\Annotation\Route;

class CircleController extends AbstractController
{
    #[Route('/circle/{radius}', name: 'app_circle')]
    public function index(float $radius): JsonResponse
    {
        $circle = new Circle();
        $circle->setRadius($radius);
        return $this->json($circle);
    }
}
