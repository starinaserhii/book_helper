<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Routing\Attribute\Route;

class ApiController extends AbstractController
{
    #[Route('/api/get_info', name: 'app_api')]
    public function index(): JsonResponse
    {

        return new JsonResponse(['success' => true]);
    }
}
