<?php
namespace WoohooLabsTest\Yin\JsonApi\Schema;

use PHPUnit_Framework_TestCase;
use WoohooLabs\Yin\TransformerTrait;

class TransformerTraitTest extends PHPUnit_Framework_TestCase
{
    public function testTransformToDecimal()
    {
        $transformerTrait = $this->createTransformerTrait();

        $this->assertEquals(1.1, $transformerTrait->toDecimal("1.1", 1));
        $this->assertEquals(999.31, $transformerTrait->toDecimal("999.313", 2));
        $this->assertEquals(999.35, $transformerTrait->toDecimal("999.35321", 2));
        $this->assertEquals(999.36, $transformerTrait->toDecimal("999.35621", 2));
    }

    public function testTransformToInt()
    {
        $transformerTrait = $this->createTransformerTrait();

        $this->assertEquals(100, $transformerTrait->toInt("100"));
        $this->assertEquals(100000000, $transformerTrait->toInt("100000000"));
        $this->assertEquals(100, $transformerTrait->toInt("100.34532"));
        $this->assertEquals(100, $transformerTrait->toInt("100.999"));
    }

    public function testTransformToIso8601Date()
    {
        $transformerTrait = $this->createTransformerTrait();

        $this->assertEquals(
            "2015-06-30",
            $transformerTrait->toIso8601Date($this->createDateTime("2015-06-30 16:00:00"))
        );
    }

    public function testTransformToIso8601Time()
    {
        $transformerTrait = $this->createTransformerTrait();

        $this->assertEquals(
            "2015-06-30T16:00:00+0000",
            $transformerTrait->toIso8601Time($this->createDateTime("2015-06-30 16:00:00"))
        );
    }

    public function testTransformFromSqlToIso8601Time()
    {
        $transformerTrait = $this->createTransformerTrait();

        $this->assertEquals(
            "2015-06-30T16:00:00+0200",
            $transformerTrait->fromSqlToIso8601Time("2015-06-30 16:00:00", "Europe/Budapest")
        );
    }

    public function testTransformFromSqlToUtcIso8601Time()
    {
        $transformerTrait = $this->createTransformerTrait();

        $this->assertEquals(
            "2015-06-30T16:00:00+0000",
            $transformerTrait->fromSqlToUtcIso8601Time("2015-06-30 16:00:00")
        );
    }

    /**
     * @return \WoohooLabs\Yin\TransformerTrait
     */
    private function createTransformerTrait()
    {
        return $this->getObjectForTrait(TransformerTrait::class);
    }

    private function createDateTime($string, $timeZone = "UTC")
    {
        return \DateTime::createFromFormat("Y-m-d H:i:s", $string, new \DateTimeZone($timeZone));
    }
}
