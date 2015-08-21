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
    public static function toDecimal($value, $precision = 12)
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
    public static function toInt($value)
    {
        return intval($value);
    }

    /**
     * Transforms a DateTime object to an ISO 8601 format date time string.
     *
     * @param \DateTime $dateTime
     * @return string
     */
    public static function toISO8601(DateTime $dateTime)
    {
        return $dateTime->format(DateTime::ISO8601);
    }
}
