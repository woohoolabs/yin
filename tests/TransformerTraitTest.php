<?php
namespace WoohooLabs\Yin\Tests\JsonApi\Schema;

use PHPUnit\Framework\TestCase;
use WoohooLabs\Yin\TransformerTrait;

class TransformerTraitTest extends TestCase
{
    /**
     * @test
     */
    public function transformToDecimal()
    {
        $transformerTrait = $this->createTransformerTrait();

        $this->assertEquals(1.1, $transformerTrait->toDecimal("1.1", 1));
        $this->assertEquals(999.31, $transformerTrait->toDecimal("999.313", 2));
        $this->assertEquals(999.35, $transformerTrait->toDecimal("999.35321", 2));
        $this->assertEquals(999.36, $transformerTrait->toDecimal("999.35621", 2));
    }

    /**
     * @test
     */
    public function transformToInt()
    {
        $transformerTrait = $this->createTransformerTrait();

        $this->assertEquals(100, $transformerTrait->toInt("100"));
        $this->assertEquals(100000000, $transformerTrait->toInt("100000000"));
        $this->assertEquals(100, $transformerTrait->toInt("100.34532"));
        $this->assertEquals(100, $transformerTrait->toInt("100.999"));
    }

    /**
     * @test
     */
    public function transformToBool()
    {
        $transformerTrait = $this->createTransformerTrait();

        $this->assertTrue($transformerTrait->toBool("1"));
        $this->assertTrue($transformerTrait->toBool(1));
        $this->assertTrue($transformerTrait->toBool(true));
        $this->assertFalse($transformerTrait->toBool("0"));
        $this->assertFalse($transformerTrait->toBool(0));
        $this->assertFalse($transformerTrait->toBool(false));
    }

    /**
     * @test
     */
    public function transformToIso8601Date()
    {
        $transformerTrait = $this->createTransformerTrait();

        $this->assertEquals(
            "2015-06-30",
            $transformerTrait->toIso8601Date($this->createDateTime("2015-06-30 16:00:00"))
        );
    }

    /**
     * @test
     */
    public function transformToIso8601Time()
    {
        $transformerTrait = $this->createTransformerTrait();

        $this->assertEquals(
            "2015-06-30T16:00:00+00:00",
            $transformerTrait->toIso8601DateTime($this->createDateTime("2015-06-30 16:00:00"))
        );
    }

    /**
     * @test
     */
    public function transformFromSqlToIso8601Time()
    {
        $transformerTrait = $this->createTransformerTrait();

        $this->assertEquals(
            "2015-06-30T16:00:00+02:00",
            $transformerTrait->fromSqlToIso8601Time("2015-06-30 16:00:00", "Europe/Budapest")
        );
    }

    /**
     * @test
     */
    public function transformFromSqlToUtcIso8601Time()
    {
        $transformerTrait = $this->createTransformerTrait();

        $this->assertEquals(
            "2015-06-30T16:00:00+00:00",
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
