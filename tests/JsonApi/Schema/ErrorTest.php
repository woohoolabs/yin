<?php
namespace WoohooLabsTest\Yin\JsonApi\Schema;

use PHPUnit_Framework_TestCase;
use WoohooLabs\Yin\JsonApi\Schema\Error;
use WoohooLabs\Yin\JsonApi\Schema\ErrorSource;
use WoohooLabs\Yin\JsonApi\Schema\Links;

class ErrorTest extends PHPUnit_Framework_TestCase
{
    public function testGetId()
    {
        $id = "123456789";

        $error = $this->createError()->setId($id);
        $this->assertEquals($id, $error->getId());
    }

    public function testGetStatus()
    {
        $status = 500;

        $error = $this->createError()->setStatus($status);
        $this->assertEquals($status, $error->getStatus());
    }

    public function testGetCode()
    {
        $code = "UNKNOWN_ERROR";

        $error = $this->createError()->setCode($code);
        $this->assertEquals($code, $error->getCode());
    }

    public function testGetTitle()
    {
        $title = "Unknown error!";

        $error = $this->createError()->setTitle($title);
        $this->assertEquals($title, $error->getTitle());
    }

    public function testGetDetail()
    {
        $detail = "An unknown error has happened and no solution exists.";

        $error = $this->createError()->setDetail($detail);
        $this->assertEquals($detail, $error->getDetail());
    }

    public function testGetSource()
    {
        $source = new ErrorSource("/data/attributes/name", "name");

        $error = $this->createError()->setSource($source);
        $this->assertEquals($source, $error->getSource());
    }

    public function testTransformWithEmptyFields()
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

    public function testTransform()
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
