<?php

namespace App\Controller;

use App\Entity\Author;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Attribute\Route;

class AuthorController extends AbstractController
{
    #[Route('/api/author/create', name: 'app_author')]
    public function create(Request $request, EntityManagerInterface $entityManager): JsonResponse
    {
        $name = $request->request->get('name');
        $repository = $entityManager->getRepository(Author::class);
        $author = $repository->findOneBy(['name' => $name]);

        if (empty($author)) {
            $entityManager->persist( new Author($name));
            $entityManager->flush();
        }

        return new JsonResponse(['success' => true]);
    }
}
