<?php
namespace WoohooLabsTest\Yin\JsonApi\Schema;

use PHPUnit_Framework_TestCase;
use WoohooLabs\Yin\JsonApi\Schema\ErrorSource;

class ErrorSourceTest extends PHPUnit_Framework_TestCase
{
    public function testCreateFromPointer()
    {
        $pointer = "/data/attributes/name";

        $errorSource = $this->createErrorSource($pointer, "");
        $this->assertEquals($errorSource, ErrorSource::fromPointer($pointer));
    }

    public function testCreateFromParameter()
    {
        $parameter = "name";

        $errorSource = $this->createErrorSource("", $parameter);
        $this->assertEquals($errorSource, ErrorSource::fromParameter($parameter));
    }

    public function testGetPointer()
    {
        $pointer = "/data/attributes/name";

        $errorSource = $this->createErrorSource($pointer, "");
        $this->assertEquals($pointer, $errorSource->getPointer());
    }

    public function testGetParameter()
    {
        $parameter = "name";

        $errorSource = $this->createErrorSource("", $parameter);
        $this->assertEquals($parameter, $errorSource->getParameter());
    }

    public function testTransformWithPointer()
    {
        $pointer = "/data/attributes/name";

        $errorSource = $this->createErrorSource($pointer, "");

        $transformedErrorSource = [
            "pointer" => "/data/attributes/name",
        ];
        $this->assertEquals($transformedErrorSource, $errorSource->transform());
    }

    public function testTransformWithParameter()
    {
        $parameter = "name";

        $errorSource = $this->createErrorSource("", $parameter);

        $transformedErrorSource = [
            "parameter" => "name"
        ];
        $this->assertEquals($transformedErrorSource, $errorSource->transform());
    }

    public function testTransformWithBothAttributes()
    {
        $pointer = "/data/attributes/name";
        $parameter = "name";

        $errorSource = $this->createErrorSource($pointer, $parameter);

        $transformedErrorSource = [
            "pointer" => "/data/attributes/name",
            "parameter" => "name"
        ];
        $this->assertEquals($transformedErrorSource, $errorSource->transform());
    }

    private function createErrorSource($pointer, $parameter)
    {
        return new ErrorSource($pointer, $parameter);
    }
}
