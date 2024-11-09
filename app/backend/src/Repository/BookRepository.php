<?php

namespace App\Repository;

use App\Entity\Book;
use App\Enum\AgeRating;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<Book>
 */
class BookRepository extends ServiceEntityRepository
{
    const PAGINATE = 10;

    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Book::class);
    }

    public function getRangeCost(): array
    {
        return $this->createQueryBuilder('b')
            ->select('MIN(b.cost) as min_cost, MAX(b.cost) as max_cost')
            ->getQuery()
            ->getOneOrNullResult();
    }

    public function getRangePageCount(): array
    {
        return $this->createQueryBuilder('b')
            ->select('MIN(b.numberOfPages) as min_number_of_pages, MAX(b.numberOfPages) as max_number_of_pages')
            ->getQuery()
            ->getOneOrNullResult();
    }

    /**
     * @return Book[] Returns an array of Book objects
     */
    public function findByParams(array $params): array
    {
        $queryBuilder = $this->createQueryBuilder('b')
            ->orderBy('b.rating', 'DESC')
            ->setMaxResults(self::PAGINATE);

        if (!empty($params['genre_id'])) {
            $queryBuilder->leftJoin('b.genre', 'g', 'b.genre_id = g.id')
                ->andWhere('g.id = :genre_id')
                ->setParameter('genre_id', $params['genre_id']);
        }

        if (!empty($params['cost']['min']) && !empty($params['cost']['max'])) {
            $queryBuilder
                ->andWhere('b.cost BETWEEN :min AND :max')
                ->setParameter('min', $params['cost']['min'])
                ->setParameter('max', $params['cost']['max']);
        }

        if (!empty($params['author_id'])) {
            $queryBuilder->leftJoin('b.authors', 'a', 'b.author_id = a.id')
                ->andWhere('a.id = :author_id')
                ->setParameter('author_id', $params['author_id']);
        }

        if (!empty($params['language_id'])) {
            $queryBuilder
                ->andWhere('b.language = :language_id')
                ->setParameter('language_id', $params['language_id']);
        }

        if (!empty($params['year'])) {
            $queryBuilder
                ->andWhere('b.year = :year')
                ->setParameter('year', $params['year']);
        }

        if (!empty($params['age_rating']) && !empty(AgeRating::getRatingForSearch($params['age_rating']))) {
            $queryBuilder
                ->andWhere('b.ageRating in (:age_rating)')
                ->setParameter('age_rating', AgeRating::getRatingForSearch($params['age_rating']));
        }

        if (!empty($params['sort'])) {
            $queryBuilder
                ->addOrderBy('b.year', $params['sort']);
        }

        if (!empty($params['number_of_page']['min']) && !empty($params['number_of_page']['max'])) {
            $queryBuilder
                ->andWhere('b.numberOfPages BETWEEN :min_number AND :max_number')
                ->setParameter('min_number', $params['number_of_page']['min'])
                ->setParameter('max_number', $params['number_of_page']['max']);
        }

        $result = $queryBuilder
            ->getQuery()
            ->getResult();

//        dd($result);

        return $result;
    }
    /**
     * @return Book[] Returns an array of Book objects
     */
    public function findRecommend(array $ids): array
    {
        $queryBuilder = $this->createQueryBuilder('b')
           ->andWhere("b.id NOT IN (:ids)")
            ->setParameter('ids', $ids)
            ->orderBy('b.rating', 'DESC')
            ->setMaxResults(self::PAGINATE);

        if (!empty($params['genre_id'])) {
            $queryBuilder->leftJoin('b.genre', 'g', 'b.genre_id = g.id')
                ->orWhere('g.id = :genre_id')
                ->setParameter('genre_id', $params['genre_id']);
        }

        if (!empty($params['cost']['min']) && !empty($params['cost']['max'])) {
            $queryBuilder
                ->orWhere('b.cost BETWEEN :min AND :max')
                ->setParameter('min', $params['cost']['min'])
                ->setParameter('max', $params['cost']['max']);
        }

        if (!empty($params['author_id'])) {
            $queryBuilder->leftJoin('b.authors', 'a', 'b.author_id = a.id')
                ->orWhere('a.id = :author_id')
                ->setParameter('author_id', $params['author_id']);
        }

        if (!empty($params['language_id'])) {
            $queryBuilder
                ->orWhere('b.language = :language_id')
                ->setParameter('language_id', $params['language_id']);
        }

        if (!empty($params['year'])) {
            $queryBuilder
                ->orWhere('b.year = :year')
                ->setParameter('year', $params['year']);
        }

        if (!empty($params['age_rating']) && !empty(AgeRating::getRatingForSearch($params['age_rating']))) {
            $queryBuilder
                ->orWhere('b.ageRating in (:age_rating)')
                ->setParameter('age_rating', AgeRating::getRatingForSearch($params['age_rating']));
        }

        if (!empty($params['sort'])) {
            $queryBuilder
                ->addOrderBy('b.year', $params['sort']);
        }

        if (!empty($params['number_of_page']['min']) && !empty($params['number_of_page']['max'])) {
            $queryBuilder
                ->orWhere('b.numberOfPages BETWEEN :min_number AND :max_number')
                ->setParameter('min_number', $params['number_of_page']['min'])
                ->setParameter('max_number', $params['number_of_page']['max']);
        }

        return $queryBuilder
            ->getQuery()
            ->getResult();
    }
}
