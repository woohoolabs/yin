<?php
declare(strict_types=1);

namespace WoohooLabs\Yin;

use DateTime;
use DateTimeInterface;

trait TransformerTrait
{
    /**
     * Transforms a value to a decimal which precision is $precision.
     */
    public function toDecimal($value, int $precision = 12): float
    {
        if (is_numeric($value)) {
            $value = round($value, $precision);
        }

        return $value;
    }

    /**
     * Transforms a value to an integer.
     */
    public function toInt($value): int
    {
        return (int) $value;
    }

    /**
     * Transforms a value to boolean.
     */
    public function toBool($value): bool
    {
        return (bool) $value;
    }

    /**
     * Transforms a DateTime object to an ISO 8601 compatible date-time string.
     */
    public function toIso8601Date(DateTimeInterface $dateTime): string
    {
        return $dateTime->format("Y-m-d");
    }

    /**
     * Transforms a DateTime object to an ISO 8601 compatible date-time string.
     */
    public function toIso8601DateTime(DateTimeInterface $dateTime): string
    {
        return $dateTime->format(DateTime::ATOM);
    }

    /**
     * Transforms an SQL compatible date-time string to an ISO 8601 compatible date-time string.
     */
    public function fromSqlToIso8601Time(string $string, string $timeZoneName = ""): string
    {
        return DateTime::createFromFormat(
            "Y-m-d H:i:s",
            $string,
            $timeZoneName ? new \DateTimeZone($timeZoneName) : null
        )->format(DateTime::ATOM);
    }

    /**
     * Transforms an SQL compatible date-time string to an ISO 8601 compatible UTC date-time string.
     */
    public function fromSqlToUtcIso8601Time(string $string): string
    {
        return $this->fromSqlToIso8601Time($string, "UTC");
    }
}
