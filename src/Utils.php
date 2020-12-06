<?php

declare(strict_types=1);

namespace WoohooLabs\Yin;

use function array_merge;
use function http_build_query;
use function is_numeric;
use function parse_str;
use function strpos;
use function substr;
use function urldecode;

class Utils
{
    public static function getIntegerFromQueryParam(array $queryParams, string $key, int $default): int
    {
        return isset($queryParams[$key]) && is_numeric($queryParams[$key]) ? (int) $queryParams[$key] : $default;
    }

    public static function getUri(string $uri, string $queryString, array $additionalQueryParams): string
    {
        $uriQueryStringSeparator = strpos($uri, "?");
        if ($uriQueryStringSeparator === false) {
            $uriWithoutQueryString = $uri;
            $uriQueryString = "";
        } else {
            $uriWithoutQueryString = substr($uri, 0, $uriQueryStringSeparator);
            $uriQueryString = substr($uri, $uriQueryStringSeparator + 1);
        }

        $parsedUriQueryString = [];
        parse_str(urldecode($uriQueryString), $parsedUriQueryString);

        $parsedQueryString = [];
        parse_str(urldecode($queryString), $parsedQueryString);

        $parsedFinalQueryString = array_merge($parsedUriQueryString, $parsedQueryString, $additionalQueryParams);

        $finalQueryString = http_build_query($parsedFinalQueryString);

        return $uriWithoutQueryString . ($finalQueryString === "" ? "" : "?" . $finalQueryString);
    }
}
