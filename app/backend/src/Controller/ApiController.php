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
use Symfony\Component\Routing\Attribute\Route;

class ApiController extends AbstractController
{
    #[Route('/api/get_info', name: 'info_api')]
    public function index(EntityManagerInterface $entityManager): JsonResponse
    {
        return new JsonResponse($this->prepare($entityManager));
    }

    private function prepare(EntityManagerInterface $entityManager): array
    {
        $repositoryGenre = $entityManager->getRepository(Genre::class);
        $genres = $repositoryGenre->findAll();
        $info = [];

        /** @var Genre $genre */
        foreach ($genres as $genre) {
            $info['genre'][$genre->getId()] = $genre->getName();
        }

        $info['age_rating'] = AgeRating::cases();

        $repositoryAuthor = $entityManager->getRepository(Author::class);
        $authors = $repositoryAuthor->findAll();

        /** @var Author $author */
        foreach ($authors as $author) {
            $info['author'][$author->getId()] = $author->getName();
        }

        $repositoryBook = $entityManager->getRepository(Book::class);
        $costRange = $repositoryBook->getRangeCost();
        $info['cost'] = [
            'min' => $costRange['min_cost'],
            'max' => $costRange['max_cost'],
        ];

        $pageRange = $repositoryBook->getRangePageCount();
        $info['number_of_pages'] = [
            'min' => $pageRange['min_number_of_pages'],
            'max' => $pageRange['max_number_of_pages'],
        ];

        $repositoryLang = $entityManager->getRepository(Language::class);
        $langs = $repositoryLang->findAll();

        /** @var Language $lang */
        foreach ($langs as $lang) {
            $info['lang'][$lang->getId()] = $lang->getName();
        }

        return $info;
    }

    #[Route('/api/get_books', name: 'book_api')]
    public function getBooks(EntityManagerInterface $entityManager, Request $request): JsonResponse
    {
        $params = json_decode($request->getContent(), true) ?? [];;

        return new JsonResponse($this->prepareBook($entityManager, $params));
    }

    private function prepareBook($entityManager, array $params): array
    {
        $info = [];
        $searchIds = [];

        $repositoryBook = $entityManager->getRepository(Book::class);

        $result = $repositoryBook->findByParams($params);

        /** @var Book $book */
        foreach ($result as $book) {
            $authors = [];
            $genres = [];

            foreach ($book->getAuthors() as $author) {
                $authors[] = $author->getName();
            }

            foreach ($book->getGenre() as $genre) {
                $genres[] = $genre->getName();
            }

            $searchIds[] = $book->getId();
            $info['search'][] = $this->parseInfo($book, $authors, $genres);
        }

        $resultRecommend = $repositoryBook->findRecommend($searchIds, $params);

        /** @var Book $book */
        foreach ($resultRecommend as $book) {
            $authors = [];
            $genres = [];

            foreach ($book->getAuthors() as $author) {
                $authors[] = $author->getName();
            }

            foreach ($book->getGenre() as $genre) {
                $genres[] = $genre->getName();
            }

            $info['recommend'][] = $this->parseInfo($book, $authors, $genres);
        }

        return $info;
    }

    private function parseInfo(Book $book, array $authors, array $genres): array
    {
        return [
            'id' => $book->getId(),
            'name' => $book->getName(),
            'lang' => $book->getLanguage()->getName(),
            'age_rating' => $book->getAgeRating()->value,
            'rating' => $book->getRating(),
            'cost' => $book->getCost(),
            'number_of_page' => $book->getNumberOfPages(),
            'year' => $book->getYear(),
            'genres' => $genres,
            'img' => 'images/'.$book->getImg(),
            'authors' => $authors,
        ];
    }
}
