<?php
namespace WoohooLabs\Yin\Examples\Repository;

class UserRepository
{
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

    /**
     * @return array
     */
    public static function getUsers()
    {
        return self::$users;
    }

    /**
     * @param $id
     * @return array|null
     */
    public static function getUser($id)
    {
        foreach (self::$users as $user) {
            if ($user["id"] == $id) {
                return $user;
            }
        }

        return null;
    }
}
