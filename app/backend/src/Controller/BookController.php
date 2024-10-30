<?php

namespace App\Controller;

use App\Entity\Book;
use App\Entity\Genre;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

class BookController extends AbstractController
{
    #[Route('/api/book/create', name: 'app_book_add')]
    public function create(Request $request, EntityManagerInterface $entityManager): Response
    {
        $params = $request->request->all();

        $repository = $entityManager->getRepository(Genre::class);
        $genre = $repository->findOneBy(['id' => $params['genre']]);

        if (empty($genre)) {
            return new JsonResponse([
                'success' => true,
                'error' => 'Genre id does not exist'
            ]);
        }

        $entityManager->persist(new Book($params['name'], $genre));
        $entityManager->flush();


        return new JsonResponse(['success' => true]);
    }
}
