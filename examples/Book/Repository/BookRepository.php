<?php
namespace WoohooLabs\Yin\Examples\Book\Repository;

use WoohooLabs\Yin\Examples\Utils\AbstractRepository;

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

    private static $publishers = [
        [
            "id" => "12346",
            "name" => "Addison-Wesley Professional",
            "representative" => "10"
        ]
    ];

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

    /**
     * @param string $id
     * @return array|null
     */
    public static function getBook($id)
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
     * @param string $bookId
     * @return array
     */
    public static function getAuthorsOfBook($bookId)
    {
        $book = self::getItemById($bookId, self::$books);

        if ($book === null) {
            return [];
        }

        return self::getItemsByIds($book["authors"], self::$authors);
    }

    /**
     * @param array $ids
     * @return array
     */
    public static function getAuthors(array $ids)
    {
        return self::getItemsByIds($ids, self::$authors);
    }

    /**
     * @param string $id
     * @return array
     */
    public static function getPublisher($id)
    {
        return self::getItemById($id, self::$publishers);
    }
}
