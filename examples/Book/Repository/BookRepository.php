<?php
declare(strict_types=1);

namespace WoohooLabs\Yin\Examples\Book\Repository;

use WoohooLabs\Yin\Examples\Utils\AbstractRepository;
use WoohooLabs\Yin\Examples\Utils\Collection;

class BookRepository extends AbstractRepository
{
    /**
     * @var array
     */
    private static $authors = [
        [
            "id" => "100",
            "name" => "Jez Humble"
        ],
        [
            "id" => "101",
            "name" => "David Farley"
        ]
    ];

    /**
     * @var array
     */
    private static $publishers = [
        [
            "id" => "12346",
            "name" => "Addison-Wesley Professional",
            "representative" => "10"
        ]
    ];

    /**
     * @var array
     */
    private static $representatives = [
        [
            "id" => "10",
            "name" => "Johnny Cash",
            "email" => "cash@addison-wesley.com"
        ]
    ];

    /**
     * @var array
     */
    private static $books = [
        [
            "id" => "1",
            "title" => "Continuous Delivery: Reliable Software Releases through Build, Test, and Deployment Automation",
            "pages" => "512",
            "authors" => ["100", "101"],
            "publisher" => "12346"
        ]
    ];

    public static function getBooks(int $page = null, int $size = null): Collection
    {
        if ($page === null) {
            $page = 1;
        }

        if ($size === null) {
            $size = 10;
        }

        $books = array_slice(self::$books, ($page - 1) * $size, $size);

        foreach ($books as $key => $book) {
            $books[$key]["authors"] = self::getItemsByIds($book["authors"], self::$authors);
        }

        return new Collection($books, count(self::$books), $page, $size);
    }

    /**
     * @return array|null
     */
    public static function getBook(string $id)
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

    /**
     * @return array
     */
    public static function getAuthorsOfBook(string $bookId): array
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

    public static function getPublisher(string $id): array
    {
        return self::getItemById($id, self::$publishers);
    }
}
