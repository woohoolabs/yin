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
            "id" => "11111",
            "name" => "John Doe"
        ],
        [
            "id" => "11112",
            "name" => "Jane Doe"
        ]
    ];

    private static $publishers = [
        [
            "id" => "12346",
            "name" => "Example Publisher"
        ]
    ];

    /**
     * @var array
     */
    private static $books = [
        [
            "id" => "1",
            "title" => "Example Book",
            "pages" => "200",
            "authors" => ["11111", "11112"],
            "publisher" => ["12346"]
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
        }

        return $book;
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
