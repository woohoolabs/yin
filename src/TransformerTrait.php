<?php
declare(strict_types=1);

namespace WoohooLabs\Yin;

use DateTime;
use DateTimeImmutable;
use DateTimeInterface;
use DateTimeZone;

trait TransformerTrait
{
    /**
     * Transforms a value to a decimal with a precision of $precision.
     *
     * @param mixed $value
     */
    public function toDecimal($value, int $precision = 12): float
    {
        if (is_numeric($value) === false) {
            return 0;
        }

        return round($value, $precision);
    }

    /**
     * Transforms a DateTime object to an ISO 8601 compatible date-time string.
     *
     * If the $displayedTimeZone parameter is present then $dateTime will be converted to that time zone.
     */
    public function toIso8601Date(DateTimeInterface $dateTime, ?DateTimeZone $displayedTimeZone = null): string
    {
        if ($displayedTimeZone && ($dateTime instanceof DateTime || $dateTime instanceof DateTimeImmutable)) {
            $dateTime = $dateTime->setTimeZone($displayedTimeZone);
        }

        return $dateTime->format("Y-m-d");
    }

    /**
     * Transforms a DateTime object to an ISO 8601 compatible date-time string.
     *
     * If the $displayedTimeZone parameter is present then $dateTime will be converted to that time zone.
     */
    public function toIso8601DateTime(DateTimeInterface $dateTime, ?DateTimeZone $displayedTimeZone = null): string
    {
        if ($displayedTimeZone && ($dateTime instanceof DateTime || $dateTime instanceof DateTimeImmutable)) {
            $dateTime = $dateTime->setTimeZone($displayedTimeZone);
        }

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
            $timeZoneName ? new DateTimeZone($timeZoneName) : null
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
