<?php

namespace App\Controller;

use App\Entity\Author;
use App\Entity\Book;
use App\Entity\Genre;
use App\Entity\Language;
use App\Enum\AgeRating;
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
        $genre = $repository->findOneBy(['id' => $params['genre_id']]);

        if (empty($genre)) {
            return new JsonResponse([
                'success' => false,
                'error' => 'Genre id does not exist',
            ]);
        }

        $languageRepository = $entityManager->getRepository(Language::class);
        $languageEntity = $languageRepository->findOneBy(['name' => $params['language_id']]);

        if (empty($languageEntity)) {
            return new JsonResponse([
                'success' => false,
                'error' => 'Language id does not exist',
            ]);
        }

        $authorRepository = $entityManager->getRepository(Author::class);
        $authorEntity = $authorRepository->findOneBy(['id' => $params['author_id']]);

        if (empty($authorEntity)) {
            return new JsonResponse([
                'success' => false,
                'error' => 'Language id does not exist',
            ]);
        }


        $entityManager->persist(
            new Book(
                $params['name'],
                (float)$params['cost'],
                $genre,
                AgeRating::from($params['age_rating']),
                null,
                $authorEntity,
                $languageEntity,
                $params['rating'],
                $params['number_of_pages'],
                $params['year'],
            )
        );
        $entityManager->flush();


        return new JsonResponse(['success' => true]);
    }
}
