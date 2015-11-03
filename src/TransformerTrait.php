<?php
namespace WoohooLabs\Yin;

use DateTime;

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
            $value= round($value, $precision);
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
        return intval($value);
    }

    /**
     * Transforms a value to boolean.
     *
     * @param mixed $value
     * @return bool
     */
    public function toBool($value)
    {
        return $value == true ? true : false;
    }

    /**
     * Transforms a DateTime object to an ISO 8601 compatible date-time string.
     *
     * @param \DateTime $dateTime
     * @return string
     */
    public function toIso8601Date(DateTime $dateTime)
    {
        return $dateTime->format("Y-m-d");
    }

    /**
     * Transforms a DateTime object to an ISO 8601 compatible date-time string.
     *
     * @param \DateTime $dateTime
     * @return string
     */
    public function toIso8601Time(DateTime $dateTime)
    {
        return $dateTime->format(DateTime::ISO8601);
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
        return DateTime::createFromFormat(
            "Y-m-d H:i:s",
            $string,
            $timeZoneName ? new \DateTimeZone($timeZoneName) : null
        )->format(DateTime::ISO8601);
    }

    /**
     * Transforms an SQL compatible date-time string to an ISO 8601 compatible UTC date-time string.
     *
     * @param string $string
     * @return string
     */
    public function fromSqlToUtcIso8601Time($string)
    {
        return self::fromSqlToIso8601Time($string, "UTC");
    }
}
