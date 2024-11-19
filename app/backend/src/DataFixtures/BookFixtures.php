<?php

namespace App\DataFixtures;

use App\Entity\Author;
use App\Entity\Book;
use App\Entity\Genre;
use App\Entity\Language;
use App\Enum\AgeRating;
use App\Repository\AuthorRepository;
use App\Repository\GenreRepository;
use App\Repository\LanguageRepository;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;

class BookFixtures extends Fixture
{
    public function __construct(
        private readonly LanguageRepository $languageRepository,
        private readonly AuthorRepository $authorRepository,
        private readonly GenreRepository $genreRepository
    ) {
    }

    public function load(ObjectManager $manager): void
    {
        $books = $this->fixtureInfo();

        foreach ($books as $genreName => $authorInfo) {
            $genreEntity = $this->genreRepository->findOneBy(['name' => $genreName]);

            if (empty($genreEntity)) {
                $genreEntity = new Genre($genreName);
                $manager->persist($genreEntity);
                $manager->flush();
            }

            foreach ($authorInfo as $authorName => $books) {
                $authorEntity = $this->authorRepository->findOneBy(['name' => $authorName]);

                if (empty($authorEntity)) {
                    $authorEntity = new Author($authorName);
                    $manager->persist($authorEntity);
                    $manager->flush();
                }

                foreach ($books as $book) {
                    $languageEntity = $this->languageRepository->findOneBy(['name' => $book['book_lang']]);

                    if (empty($languageEntity)) {
                        $languageEntity = new Language($book['book_lang']);
                        $manager->persist($languageEntity);
                        $manager->flush();
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
                    $manager->flush();
                }
            }
        }
    }

    private function fixtureInfo(): array
    {
        return [
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
                            'book_year' => 2024,
                        ],
                        [
                            'book_name' => 'Жерстяні сурмачі',
                            'book_cost' => 356,
                            'book_age_rating' => AgeRating::Sixteen,
                            'book_img' => 'surmachi.png',
                            'book_lang' => 'Українська',
                            'book_page' => 128,
                            'book_year' => 2024,
                        ],
                    ],
                    'Жадан Сергій' => [
                        [
                            'book_name' => 'Інтернат',
                            'book_cost' => 345,
                            'book_age_rating' => AgeRating::Twelve,
                            'book_img' => 'internat.jpg',
                            'book_lang' => 'Українська',
                            'book_page' => 336,
                            'book_year' => 2024,
                        ],
                        [
                            'book_name' => 'Арабески',
                            'book_cost' => 237,
                            'book_age_rating' => AgeRating::Six,
                            'book_img' => 'arabesky.jpg',
                            'book_lang' => 'Українська',
                            'book_page' => 136,
                            'book_year' => 2024,
                        ],
                        [
                            'book_name' => 'Месопотамія',
                            'book_cost' => 360,
                            'book_age_rating' => AgeRating::Six,
                            'book_img' => 'mes.jpg',
                            'book_lang' => 'Українська',
                            'book_page' => 456,
                            'book_year' => 2022,
                        ],
                        [
                            'book_name' => 'Гімн демократичної молоді',
                            'book_cost' => 270,
                            'book_age_rating' => AgeRating::Six,
                            'book_img' => 'gimn.jpg',
                            'book_lang' => 'Українська',
                            'book_page' => 240,
                            'book_year' => 2023,
                        ],
                        [
                            'book_name' => 'Ворошиловград',
                            'book_cost' => 120,
                            'book_age_rating' => AgeRating::Twelve,
                            'book_img' => 'vorosh.jpg',
                            'book_lang' => 'Українська',
                            'book_page' => 320,
                            'book_year' => 2018,
                        ],
                    ],
                    'Забужко Оксана' => [
                        [
                            'book_name' => 'Музей покинутих секретів',
                            'book_cost' => 326,
                            'book_age_rating' => AgeRating::Six,
                            'book_img' => 'secret.jpg',
                            'book_lang' => 'Українська',
                            'book_page' => 832,
                            'book_year' => 2015,
                        ],
                        [
                            'book_name' => 'Найдовша подорож',
                            'book_cost' => 140,
                            'book_age_rating' => AgeRating::Six,
                            'book_img' => 'travel.jpg',
                            'book_lang' => 'Українська',
                            'book_page' => 168,
                            'book_year' => 2022,
                        ],
                        [
                            'book_name' => 'Після третього дзвінка вхід до зали забороняється',
                            'book_cost' => 346,
                            'book_age_rating' => AgeRating::Six,
                            'book_img' => 'zal.jpg',
                            'book_lang' => 'Українська',
                            'book_page' => 416,
                            'book_year' => 2017,
                        ],
                        [
                            'book_name' => 'І знов я влізаю в танк...',
                            'book_cost' => 261,
                            'book_age_rating' => AgeRating::Twelve,
                            'book_img' => 'znov.jpg',
                            'book_lang' => 'Українська',
                            'book_page' => 416,
                            'book_year' => 2016,
                        ],
                        [
                            'book_name' => 'Your Ad Could Go Here: Stories',
                            'book_cost' => 583,
                            'book_age_rating' => AgeRating::Twelve,
                            'book_img' => 'here.jpg',
                            'book_lang' => 'English',
                            'book_page' => 252,
                            'book_year' => 2020,
                        ]
                    ],
                    'Malzieu Mathias' => [
                        [
                            'book_name' => 'La mecanique du coeur',
                            'book_cost' => 545,
                            'book_age_rating' => AgeRating::Sixteen,
                            'book_img' => 'couer.jpg',
                            'book_lang' => 'Français',
                            'book_page' => 280,
                            'book_year' => 2014,
                        ],
                    ],
                ],
            'Містичний детектив' => [
                'Павлюк Ілларіон' => [
                    [
                        'book_name' => 'Білий попіл',
                        'book_cost' => 180,
                        'book_age_rating' => AgeRating::Six,
                        'book_img' => 'whitep.jpg',
                        'book_lang' => 'Українська',
                        'book_page' => 352,
                        'book_year' => 2018,
                    ],
                    [
                        'book_name' => 'Я бачу, Вас цікавить пітьма',
                        'book_cost' => 300,
                        'book_age_rating' => AgeRating::Six,
                        'book_img' => 'ya_bachu_vas_cikavytj_pitjma_cover_full.jpg',
                        'book_lang' => 'Українська',
                        'book_page' => 664,
                        'book_year' => 2020,
                    ],
                ],
                'Netley Rebecca' => [
                    [
                        'book_name' => 'The Black Feathers',
                        'book_cost' => 648,
                        'book_age_rating' => AgeRating::Six,
                        'book_img' => 'feathers.jpg',
                        'book_lang' => 'English',
                        'book_page' => 400,
                        'book_year' => 2024,
                    ],
                    [
                        'book_name' => 'The Whistling',
                        'book_cost' => 648,
                        'book_age_rating' => AgeRating::Twelve,
                        'book_img' => 'whistling.jpg',
                        'book_lang' => 'English',
                        'book_page' => 384,
                        'book_year' => 2022,
                    ],
                ],
            ],
            'Пригоди' => [
                'Павлюк Ілларіон' => [
                    [
                        'book_name' => 'Танець недоумка',
                        'book_cost' => 270,
                        'book_age_rating' => AgeRating::Six,
                        'book_img' => 'cover_dance.jpg',
                        'book_lang' => 'Українська',
                        'book_page' => 680,
                        'book_year' => 2019,
                    ],
                ],
                'Довгопол Наталія' => [
                    [
                        'book_name' => 'Шпигунки з притулку Артемiда. Колапс старого свiту',
                        'book_cost' => 170,
                        'book_age_rating' => AgeRating::Six,
                        'book_img' => 'kolaps.jpg',
                        'book_lang' => 'Українська',
                        'book_page' => 352,
                        'book_year' => 2021,
                    ],
                    [
                        'book_name' => 'Шпигунки з притулку Артемiда. Скарби богині',
                        'book_cost' => 210,
                        'book_age_rating' => AgeRating::Six,
                        'book_img' => 'skarby.jpg',
                        'book_lang' => 'Українська',
                        'book_page' => 368,
                        'book_year' => 2024,
                    ]
                ],
                'Багряний Іван' => [
                    [
                        'book_name' => 'Тигролови',
                        'book_cost' => 170,
                        'book_age_rating' => AgeRating::Twelve,
                        'book_img' => 'tigr.jpg',
                        'book_lang' => 'Українська',
                        'book_page' => 250,
                        'book_year' => 1944,
                    ]
                ]
            ],
            'Фентезі' => [
                'Rowling Joanne' => [
                    [
                        'book_name' => 'Harry Potter and the Philosophers Stone',
                        'book_cost' => 583,
                        'book_age_rating' => AgeRating::Six,
                        'book_img' => 'hp1.jpg',
                        'book_lang' => 'English',
                        'book_page' => 352,
                        'book_year' => 2014,
                    ],
                    [
                        'book_name' => 'Harry Potter and the Chamber of Secrets',
                        'book_cost' => 1926,
                        'book_age_rating' => AgeRating::Six,
                        'book_img' => 'hp2.jpg',
                        'book_lang' => 'English',
                        'book_page' => 400,
                        'book_year' => 2021,
                    ],
                    [
                        'book_name' => 'Harry Potter and the Prisoner of Azkaban',
                        'book_cost' => 458,
                        'book_age_rating' => AgeRating::Six,
                        'book_img' => 'hp3.jpeg',
                        'book_lang' => 'English',
                        'book_page' => 480,
                        'book_year' => 2014,
                    ],
                    [
                        'book_name' => 'Harry Potter and the Goblet of Fire (Ravenclaw Edition)',
                        'book_cost' => 570,
                        'book_age_rating' => AgeRating::Six,
                        'book_img' => 'hp4.jpeg',
                        'book_lang' => 'English',
                        'book_page' => 627,
                        'book_year' => 2020,
                    ],
                    [
                        'book_name' => 'Harry Potter and the Order of the Phoenix. The Illustrated Edition',
                        'book_cost' => 2101,
                        'book_age_rating' => AgeRating::Six,
                        'book_img' => 'hp5.jpeg',
                        'book_lang' => 'English',
                        'book_page' => 576,
                        'book_year' => 2022,
                    ],
                    [
                        'book_name' => 'Harry Potter and the Half-Blood Prince. Hufflepuff Edition',
                        'book_cost' => 697,
                        'book_age_rating' => AgeRating::Six,
                        'book_img' => 'hp6.jpeg',
                        'book_lang' => 'English',
                        'book_page' => 560,
                        'book_year' => 2021,
                    ],
                    [
                        'book_name' => 'Harry Potter and the Deathly Hallows. Slytherin Edition',
                        'book_cost' => 579,
                        'book_age_rating' => AgeRating::Twelve,
                        'book_img' => 'hp7.jpeg',
                        'book_lang' => 'English',
                        'book_page' => 640,
                        'book_year' => 2021,
                    ],
                ],
                'Pullman Philip' => [
                    [
                        'book_name' => 'A LA CROISEE DES MONDES LINTEGRALE',
                        'book_cost' => 3369,
                        'book_age_rating' => AgeRating::Six,
                        'book_img' => 'mondes.jpg',
                        'book_lang' => 'Français',
                        'book_page' => 264,
                        'book_year' => 2017,
                    ],
                ],
                'Payet Adrien' => [
                    [
                        'book_name' => 'Merci! Guide pedagogique 1',
                        'book_cost' => 780,
                        'book_age_rating' => AgeRating::Six,
                        'book_img' => 'mersi.jpg',
                        'book_lang' => 'Français',
                        'book_page' => 160,
                        'book_year' => 2016,
                    ],
                ],
            ]
        ];
    }
}
