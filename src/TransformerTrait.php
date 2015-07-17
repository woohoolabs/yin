<?php
namespace WoohooLabs\Yin;

trait TransformerTrait
{
    /**
     * @param mixed $value
     * @param int $length
     * @return float
     */
    public static function toDecimal($value, $length = 12)
    {
        if (is_numeric($value)) {
            $value= round($value, $length);
        }

        return $value;
    }

    /**
     * @param string $value
     * @return int
     */
    public static function toInt($value)
    {
        return intval($value);
    }

    /**
     * @param \DateTime $dateTime
     * @return string
     */
    public static function toISO8601(\DateTime $dateTime)
    {
        return $dateTime->format(\DateTime::ISO8601);
    }
}
