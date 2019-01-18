<?php
declare(strict_types=1);

namespace WoohooLabs\Yin\Tests\JsonApi\Schema;

use DateTime;
use DateTimeZone;
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

        $this->assertEquals(0, $transformerTrait->toDecimal("abc"));
        $this->assertEquals(1.1, $transformerTrait->toDecimal("1.1", 1));
        $this->assertEquals(999.31, $transformerTrait->toDecimal("999.313", 2));
        $this->assertEquals(999.35, $transformerTrait->toDecimal("999.35321", 2));
        $this->assertEquals(999.36, $transformerTrait->toDecimal("999.35621", 2));
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
    public function transformToIso8601DateWithTimeZone()
    {
        $transformerTrait = $this->createTransformerTrait();

        $this->assertEquals(
            "2015-07-01",
            $transformerTrait->toIso8601Date(
                $this->createDateTime("2015-06-30 23:00:00"),
                new DateTimeZone("Europe/Budapest")
            )
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
    public function transformToIso8601TimeWithTimeZone()
    {
        $transformerTrait = $this->createTransformerTrait();

        $this->assertEquals(
            "2015-06-30T18:00:00+02:00",
            $transformerTrait->toIso8601DateTime(
                $this->createDateTime("2015-06-30 16:00:00"),
                new DateTimeZone("Europe/Budapest")
            )
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
            $transformerTrait->fromSqlToIso8601Time("2015-06-30 16:00:00", new DateTimeZone("Europe/Budapest"))
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
     * @return TransformerTrait
     */
    private function createTransformerTrait()
    {
        return $this->getObjectForTrait(TransformerTrait::class);
    }

    private function createDateTime($string, $timeZone = "UTC")
    {
        return DateTime::createFromFormat("Y-m-d H:i:s", $string, new DateTimeZone($timeZone));
    }
}
