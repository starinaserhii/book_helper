<?php

namespace App\DataFixtures;

use App\Entity\Author;
use App\Entity\Book;
use App\Entity\Genre;
use App\Entity\Language;
use App\Enum\AgeRating;
use App\Repository\AuthorRepository;
use App\Repository\LanguageRepository;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;

class BookFixtures extends Fixture
{
    public function __construct(
        private readonly LanguageRepository $languageRepository,
        private readonly AuthorRepository $authorRepository
    ) {}

    public function load(ObjectManager $manager): void
    {
        $books = [
            'Проза' =>
                [
                    'Шикін Павло' => [
                        [
                            'book_name' => 'Розриті могили',
                            'book_cost' => 245,
                            'book_age_rating' => AgeRating::Sixteen,
                            'book_img' => 'dug_up_graves.jpg',
                            'book_lang' => 'Українська',
                            'book_page' => 480,
                            'book_year' => 2024
                        ],
                        [
                            'book_name' => 'Жерстяні сурмачі',
                            'book_cost' => 356,
                            'book_age_rating' => AgeRating::Sixteen,
                            'book_img' => 'surmachi.png',
                            'book_lang' => 'Українська',
                            'book_page' => 128,
                            'book_year' => 2024
                        ]
                    ],
                    'Жадан Сергій' => [
                        [
                            'book_name' => 'Інтернат',
                            'book_cost' => 345,
                            'book_age_rating' => AgeRating::Twelve,
                            'book_img' => 'internat.jpg',
                            'book_lang' => 'Українська',
                            'book_page' => 336,
                            'book_year' => 2024
                        ],
                        [
                            'book_name' => 'Арабески',
                            'book_cost' => 237,
                            'book_age_rating' => AgeRating::Six,
                            'book_img' => 'arabesky.jpg',
                            'book_lang' => 'Українська',
                            'book_page' => 136,
                            'book_year' => 2024
                        ],
                        [
                            'book_name' => 'Месопотамія',
                            'book_cost' => 360,
                            'book_age_rating' => AgeRating::Zero,
                            'book_img' => 'mes.jpg',
                            'book_lang' => 'Українська',
                            'book_page' => 456,
                            'book_year' => 2022
                        ],
                        [
                            'book_name' => 'Гімн демократичної молоді',
                            'book_cost' => 270,
                            'book_age_rating' => AgeRating::Zero,
                            'book_img' => 'gimn.jpg',
                            'book_lang' => 'Українська',
                            'book_page' => 240,
                            'book_year' => 2023
                        ],
                        [
                            'book_name' => 'Ворошиловград',
                            'book_cost' => 120,
                            'book_age_rating' => AgeRating::Twelve,
                            'book_img' => 'vorosh.jpg',
                            'book_lang' => 'Українська',
                            'book_page' => 320,
                            'book_year' => 2018
                        ]
                    ]
                ],
            'Містичний детектив' => [
                'Павлюк Ілларіон' => [
                    [
                        'book_name' => 'Білий попіл',
                        'book_cost' => 180,
                        'book_age_rating' => AgeRating::Zero,
                        'book_img' => 'whitep.jpg',
                        'book_lang' => 'Українська',
                        'book_page' => 352,
                        'book_year' => 2018
                    ],
                    [
                        'book_name' => 'Я бачу, Вас цікавить пітьма',
                        'book_cost' => 300,
                        'book_age_rating' => AgeRating::Zero,
                        'book_img' => 'ya_bachu_vas_cikavytj_pitjma_cover_full.jpg',
                        'book_lang' => 'Українська',
                        'book_page' => 664,
                        'book_year' => 2020
                    ],
                    [
                        'book_name' => 'Танець недоумка',
                        'book_cost' => 270,
                        'book_age_rating' => AgeRating::Six,
                        'book_img' => 'cover_dance.jpg',
                        'book_lang' => 'Українська',
                        'book_page' => 680,
                        'book_year' => 2019
                    ]
                ],
                'Netley Rebecca' => [
                    [
                        'book_name' => 'The Black Feathers',
                        'book_cost' => 648,
                        'book_age_rating' => AgeRating::Zero,
                        'book_img' => 'feathers.jpg',
                        'book_lang' => 'English',
                        'book_page' => 400,
                        'book_year' => 2024
                    ],
                    [
                        'book_name' => 'The Whistling',
                        'book_cost' => 648,
                        'book_age_rating' => AgeRating::Twelve,
                        'book_img' => 'whistling.jpg',
                        'book_lang' => 'English',
                        'book_page' => 384,
                        'book_year' => 2022
                    ],
                ],
            ]
        ];

        foreach ($books as $genreName => $authorInfo) {
            $genreEntity = new Genre($genreName);
            $manager->persist($genreEntity);

            foreach ($authorInfo as $authorName => $books) {

                $authorEntity = $this->authorRepository->findOneBy(['name' => $authorName]);

                if (empty($languageEntity)) {
                    $authorEntity = new Author($authorName);
                    $manager->persist($authorEntity);
                }

                foreach ($books as $book) {
                    $languageEntity = $this->languageRepository->findOneBy(['name' => $book['book_lang']]);

                    if (empty($languageEntity)) {
                        $languageEntity = new Language($book['book_lang']);
                    }

                    $bookEntity = new Book(
                        $book['book_name'],
                        $book['book_cost'],
                        $genreEntity,
                        $book['book_age_rating'],
                        $book['book_img'],
                        $authorEntity,
                        $languageEntity,
                        rand(2, 5),
                        $book['book_page'],
                        $book['book_year'],
                    );
                    $manager->persist($bookEntity);
                }
            }
        }

        $manager->flush();
    }
}
