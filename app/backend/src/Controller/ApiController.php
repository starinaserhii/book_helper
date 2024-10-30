<?php

namespace App\Controller;

use App\Entity\Genre;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Routing\Attribute\Route;

class ApiController extends AbstractController
{
    #[Route('/api/get_info', name: 'app_api')]
    public function index(EntityManagerInterface $entityManager): JsonResponse
    {
        return new JsonResponse($this->prepare($entityManager));
    }

    private function prepare(EntityManagerInterface $entityManager): array
    {
        $repository = $entityManager->getRepository(Genre::class);
        $genres = $repository->findAll();
        $info = [];

        /** @var Genre $genre */
        foreach ($genres as $genre) {
            $info['genre'][$genre->getId()] = $genre->getName();
        }

        return $info;
    }
}
