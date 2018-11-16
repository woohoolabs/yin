<?php
declare(strict_types=1);

namespace WoohooLabs\Yin;

class Utils
{
    public static function getIntegerFromQueryParam(array $queryParams, string $key, int $default): int
    {
        return isset($queryParams[$key]) && is_numeric($queryParams[$key]) ? (int) $queryParams[$key] : $default;
    }
}
