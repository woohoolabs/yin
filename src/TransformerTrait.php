<?php
namespace WoohooLabs\Yin;

use DateTime;
use DateTimeInterface;
use DateTimeZone;

trait TransformerTrait
{
    /**
     * Transforms a value to a decimal which precision is $precision.
     *
     * @param mixed $value
     * @param int $precision
     * @return float
     */
    public function toDecimal($value, $precision = 12)
    {
        if (is_numeric($value)) {
            $value = round($value, $precision);
        }

        return $value;
    }

    /**
     * Transforms a value to an integer.
     *
     * @param string $value
     * @return int
     */
    public function toInt($value)
    {
        return (int) $value;
    }

    /**
     * Transforms a value to boolean.
     *
     * @param mixed $value
     * @return bool
     */
    public function toBool($value)
    {
        return (bool) $value;
    }

    /**
     * Transforms a DateTime object to an ISO 8601 compatible date-time string.
     *
     * @param \DateTimeInterface $dateTime
     * @return string
     */
    public function toIso8601Date(DateTimeInterface $dateTime)
    {
        return $dateTime->format("Y-m-d");
    }

    /**
     * Transforms a DateTime object to an ISO 8601 compatible date-time string.
     *
     * @param DateTimeInterface $dateTime
     * @return string
     */
    public function toIso8601DateTime(DateTimeInterface $dateTime)
    {
        return $dateTime->format(DateTime::ATOM);
    }

    /**
     * Transforms an SQL compatible date-time string to an ISO 8601 compatible date-time string.
     *
     * @param string $string
     * @param string $timeZoneName
     * @return string
     */
    public function fromSqlToIso8601Time($string, $timeZoneName = "")
    {
        $format = "Y-m-d H:i:s";

        if ($timeZoneName) {
            $date = DateTime::createFromFormat($format, $string, new DateTimeZone($timeZoneName));
        } else {
            $date = DateTime::createFromFormat($format, $string);
        }

        return $date->format(DateTime::ATOM);
    }

    /**
     * Transforms an SQL compatible date-time string to an ISO 8601 compatible UTC date-time string.
     *
     * @param string $string
     * @return string
     */
    public function fromSqlToUtcIso8601Time($string)
    {
        return $this->fromSqlToIso8601Time($string, "UTC");
    }
}
