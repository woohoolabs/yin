<?php
namespace WoohooLabs\Yin\Examples\Book\Repository;

class BookRepository
{
    private static $book = [
        "id" => "1",
        "title" => "Example Book",
        "pages" => "200",
        "authors" => [
            [
                "id" => "11111",
                "name" => "John Doe"
            ],
            [
                "id" => "11112",
                "name" => "Jane Doe"
            ]
        ],
        "publisher" => [
            "id" => "12346",
            "name" => "Example Publisher"
        ]
    ];

    /**
     * @param $id
     * @return array|null
     */
    public static function getBook($id)
    {
        if (self::$book["id"] == $id) {
            return self::$book;
        }

        return null;
    }
}
