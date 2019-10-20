<?php

declare(strict_types=1);

namespace WoohooLabs\Yin\Tests\JsonApi\Schema\Error;

use PHPUnit\Framework\TestCase;
use WoohooLabs\Yin\JsonApi\Schema\Error\Error;
use WoohooLabs\Yin\JsonApi\Schema\Error\ErrorSource;
use WoohooLabs\Yin\JsonApi\Schema\Link\ErrorLinks;

class ErrorTest extends TestCase
{
    /**
     * @test
     */
    public function getId(): void
    {
        $error = $this->createError()->setId("123456789");

        $id = $error->getId();

        $this->assertEquals("123456789", $id);
    }

    public function testGetStatus(): void
    {
        $error = $this->createError()->setStatus("500");

        $status = $error->getStatus();

        $this->assertEquals("500", $status);
    }

    /**
     * @test
     */
    public function getCode(): void
    {
        $error = $this->createError()->setCode("UNKNOWN_ERROR");

        $code = $error->getCode();

        $this->assertEquals("UNKNOWN_ERROR", $code);
    }

    /**
     * @test
     */
    public function getLinksWhenNull(): void
    {
        $error = $this->createError();

        $links = $error->getLinks();

        $this->assertNull($links);
    }

    /**
     * @test
     */
    public function getLinks(): void
    {
        $links = new ErrorLinks();

        $error = $this->createError()->setLinks($links);

        $this->assertEquals($links, $error->getLinks());
    }

    /**
     * @test
     */
    public function getTitle(): void
    {
        $error = $this->createError()->setTitle("Unknown error!");

        $title = $error->getTitle();

        $this->assertEquals("Unknown error!", $title);
    }

    /**
     * @test
     */
    public function getDetail(): void
    {
        $error = $this->createError()->setDetail("An unknown error has happened and no solution exists.");

        $detail = $error->getDetail();

        $this->assertEquals("An unknown error has happened and no solution exists.", $detail);
    }

    /**
     * @test
     */
    public function getSource(): void
    {
        $source = new ErrorSource("/data/attributes/name", "name");

        $error = $this->createError()->setSource($source);

        $this->assertEquals($source, $error->getSource());
    }

    /**
     * @test
     */
    public function getSourceWhenEmpty(): void
    {
        $error = $this->createError();

        $source = $error->getSource();

        $this->assertNull($source);
    }

    /**
     * @test
     */
    public function transformWithEmptyFields(): void
    {
        $id = "123456789";
        $status = "500";
        $code = "UNKNOWN_ERROR";
        $title = "Unknown error!";
        $detail = "An unknown error has happened and no solution exists.";

        $error = $this->createError()
            ->setId($id)
            ->setStatus($status)
            ->setCode($code)
            ->setTitle($title)
            ->setDetail($detail);

        $this->assertEquals(
            [
                "id" => $id,
                "status" => $status,
                "code" => $code,
                "title" => $title,
                "detail" => $detail,
            ],
            $error->transform()
        );
    }

    /**
     * @test
     */
    public function transform(): void
    {
        $error = $this->createError()
            ->setId("123456789")
            ->setMeta(["abc" => "def"])
            ->setLinks(new ErrorLinks())
            ->setStatus("500")
            ->setCode("UNKNOWN_ERROR")
            ->setTitle("Unknown error!")
            ->setDetail("An unknown error has happened and no solution exists.")
            ->setSource(new ErrorSource("", ""));

        $this->assertEquals(
            [
                "id" => "123456789",
                "meta" => ["abc" => "def"],
                "links" => [],
                "status" => "500",
                "code" => "UNKNOWN_ERROR",
                "title" => "Unknown error!",
                "detail" => "An unknown error has happened and no solution exists.",
                "source" => [],
            ],
            $error->transform()
        );
    }

    private function createError(): Error
    {
        return new Error();
    }
}
