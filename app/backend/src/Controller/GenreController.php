<?php

namespace App\Controller;

use App\Entity\Genre;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\HttpFoundation\Request;

class GenreController extends AbstractController
{
    #[Route('/api/genre/add', name: 'app_genre')]
    public function index(Request $request, EntityManagerInterface $entityManager): JsonResponse
    {
        $name = $request->request->get('name');
        $entity = new Genre();
        $entity->setName($name);

        $repository = $entityManager->getRepository(Genre::class);
        $genres = $repository->findOneBy(['name' => $name]);

        if (empty($genres)) {
            $entityManager->persist($entity);
            $entityManager->flush();
        }

        return new JsonResponse(['success' => true]);
    }
}
