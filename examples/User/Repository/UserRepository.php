<?php
declare(strict_types=1);

namespace WoohooLabs\Yin\Examples\User\Repository;

use WoohooLabs\Yin\Examples\Utils\Collection;

class UserRepository
{
    /**
     * @var array
     */
    private static $users = [
        [
            "id" => "1",
            "firstname" => "John",
            "lastname" => "Doe",
            "contacts" => [
                [
                    "id" => "100",
                    "type" => "phone",
                    "value" => "+123456789"
                ],
                [
                    "id" => "101",
                    "type" => "email",
                    "value" => "john.doe@example.com"
                ],
                [
                    "id" => "102",
                    "type" => "email",
                    "value" => "secret.doe@example.com"
                ]
            ]
        ],
        [
            "id" => "2",
            "firstname" => "Jane",
            "lastname" => "Doe",
            "contacts" => [
                [
                    "id" => "103",
                    "type" => "email",
                    "value" => "jane.doe@example.com"
                ]
            ]
        ]
    ];

    public static function getUsers(int $page = null, int $size = null): Collection
    {
        if ($page === null) {
            $page = 1;
        }

        if ($size === null) {
            $size = 10;
        }

        $users = array_slice(self::$users, ($page - 1) * $size, $size);
        return new Collection($users, count(self::$users), $page, $size);
    }

    public static function getUser(string $id): ?array
    {
        foreach (self::$users as $user) {
            if ($user["id"] === $id) {
                return $user;
            }
        }

        return null;
    }
}
