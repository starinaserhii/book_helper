<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\HttpFoundation\Request;

class GenreController extends AbstractController
{
    #[Route('/api/genre/add', name: 'app_genre')]
    public function index(Request $request): JsonResponse
    {
        return new JsonResponse(['success' => true]);
    }
}
