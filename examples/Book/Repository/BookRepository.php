<?php

declare(strict_types=1);

namespace WoohooLabs\Yin\Examples\Book\Repository;

use WoohooLabs\Yin\Examples\Utils\AbstractRepository;
use WoohooLabs\Yin\Examples\Utils\Collection;

use function array_slice;
use function count;

class BookRepository extends AbstractRepository
{
    private static array $authors = [
        [
            "id" => 100,
            "name" => "Jez Humble",
        ],
        [
            "id" => 101,
            "name" => "David Farley",
        ],
        [
            "id" => 102,
            "name" => "Sam Newman",
        ],
        [
            "id" => 103,
            "name" => "Jay Fields",
        ],
    ];

    private static array $publishers = [
        [
            "id" => 12346,
            "name" => "Addison-Wesley Professional",
            "representative" => 10,
        ],
        [
            "id" => 12347,
            "name" => "O'Reilly Media",
            "representative" => 11,
        ],
        [
            "id" => 12348,
            "name" => "CreateSpace Independent Publishing Platform",
            "representative" => null,
        ],
    ];

    private static array $representatives = [
        [
            "id" => 10,
            "name" => "Melbourne Wesley Cummings",
            "email" => "melbourne@addison-wesley.com",
        ],
        [
            "id" => 11,
            "name" => "Tim O'Reilly",
            "email" => "tim@oreilly.com",
        ],
    ];

    private static array $books = [
        [
            "id" => 1,
            "title" => "Building Microservices",
            "isbn13" => "978-1491950357",
            "release_date" => "2015-02-20",
            "hard_cover" => false,
            "pages" => 282,
            "authors" => [102],
            "publisher" => 12347,
        ],
        [
            "id" => 2,
            "title" => "Continuous Delivery: Reliable Software Releases through Build, Test, and Deployment Automation",
            "isbn13" => "978-0321601919",
            "release_date" => "2010-08-06",
            "hard_cover" => true,
            "pages" => 512,
            "authors" => [100, 101],
            "publisher" => 12346,
        ],
        [
            "id" => 3,
            "title" => "Working Effectively with Unit Tests",
            "isbn13" => "978-1503242708",
            "release_date" => "2014-12-09",
            "hard_cover" => true,
            "pages" => 354,
            "authors" => [103],
            "publisher" => 12348,
        ],
    ];

    public static function getBooks(?int $page = null, ?int $size = null): Collection
    {
        if ($page === null) {
            $page = 1;
        }

        if ($size === null) {
            $size = 10;
        }

        $books = array_slice(self::$books, ($page - 1) * $size, $size);

        foreach ($books as $key => &$book) {
            $book = self::getBook($book["id"]);
        }

        return new Collection($books, count(self::$books), $page, $size);
    }

    public static function getBook(int $id): ?array
    {
        $book = self::getItemById($id, self::$books);

        if ($book !== null) {
            $book["authors"] = self::getItemsByIds($book["authors"], self::$authors);
            $book["publisher"] = self::getItemById($book["publisher"], self::$publishers);
            $book["publisher"]["representative"] = self::getItemById(
                $book["publisher"]["representative"],
                self::$representatives
            );
        }

        return $book;
    }

    public static function getAuthorsOfBook(int $bookId): array
    {
        $book = self::getItemById($bookId, self::$books);

        if ($book === null) {
            return [];
        }

        return self::getItemsByIds($book["authors"], self::$authors);
    }

    public static function getAuthors(array $ids): array
    {
        return self::getItemsByIds($ids, self::$authors);
    }

    public static function getPublisher(int $id): array
    {
        return self::getItemById($id, self::$publishers);
    }
}
