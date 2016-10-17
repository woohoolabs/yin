<?php
namespace WoohooLabsTest\Yin\JsonApi\Schema;

use PHPUnit\Framework\TestCase;
use WoohooLabs\Yin\JsonApi\Schema\Error;
use WoohooLabs\Yin\JsonApi\Schema\ErrorSource;
use WoohooLabs\Yin\JsonApi\Schema\Links;

class ErrorTest extends TestCase
{
    /**
     * @test
     */
    public function getId()
    {
        $id = "123456789";

        $error = $this->createError()->setId($id);
        $this->assertEquals($id, $error->getId());
    }

    /**
     * @test
     */
    public function getStatus()
    {
        $status = 500;

        $error = $this->createError()->setStatus($status);
        $this->assertEquals($status, $error->getStatus());
    }

    /**
     * @test
     */
    public function getCode()
    {
        $code = "UNKNOWN_ERROR";

        $error = $this->createError()->setCode($code);
        $this->assertEquals($code, $error->getCode());
    }

    /**
     * @test
     */
    public function getTitle()
    {
        $title = "Unknown error!";

        $error = $this->createError()->setTitle($title);
        $this->assertEquals($title, $error->getTitle());
    }

    /**
     * @test
     */
    public function getDetail()
    {
        $detail = "An unknown error has happened and no solution exists.";

        $error = $this->createError()->setDetail($detail);
        $this->assertEquals($detail, $error->getDetail());
    }

    /**
     * @test
     */
    public function getSource()
    {
        $source = new ErrorSource("/data/attributes/name", "name");

        $error = $this->createError()->setSource($source);
        $this->assertEquals($source, $error->getSource());
    }

    /**
     * @test
     */
    public function transformWithEmptyFields()
    {
        $id = "123456789";
        $status = 500;
        $code = "UNKNOWN_ERROR";
        $title = "Unknown error!";
        $detail = "An unknown error has happened and no solution exists.";

        $error = $this->createError()
            ->setId($id)
            ->setStatus($status)
            ->setCode($code)
            ->setTitle($title)
            ->setDetail($detail)
        ;

        $transformedError = [
            "id" => $id,
            "status" => $status,
            "code" => $code,
            "title" => $title,
            "detail" => $detail
        ];
        $this->assertEquals($transformedError, $error->transform());
    }

    /**
     * @test
     */
    public function transform()
    {
        $id = "123456789";
        $meta = ["abc" => "def"];
        $links = new Links();
        $status = 500;
        $code = "UNKNOWN_ERROR";
        $title = "Unknown error!";
        $detail = "An unknown error has happened and no solution exists.";
        $source = new ErrorSource("", "");

        $error = $this->createError()
            ->setId($id)
            ->setMeta($meta)
            ->setLinks($links)
            ->setStatus($status)
            ->setCode($code)
            ->setTitle($title)
            ->setDetail($detail)
            ->setSource($source)
        ;

        $transformedError = [
            "id" => $id,
            "meta" => $meta,
            "links" => [],
            "status" => $status,
            "code" => $code,
            "title" => $title,
            "detail" => $detail,
            "source" => []
        ];
        $this->assertEquals($transformedError, $error->transform());
    }

    private function createError()
    {
        return new Error();
    }
}
