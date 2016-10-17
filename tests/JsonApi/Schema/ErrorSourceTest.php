<?php
namespace WoohooLabsTest\Yin\JsonApi\Schema;

use PHPUnit\Framework\TestCase;
use WoohooLabs\Yin\JsonApi\Schema\ErrorSource;

class ErrorSourceTest extends TestCase
{
    /**
     * @test
     */
    public function createFromPointer()
    {
        $pointer = "/data/attributes/name";

        $errorSource = $this->createErrorSource($pointer, "");
        $this->assertEquals($errorSource, ErrorSource::fromPointer($pointer));
    }

    /**
     * @test
     */
    public function createFromParameter()
    {
        $parameter = "name";

        $errorSource = $this->createErrorSource("", $parameter);
        $this->assertEquals($errorSource, ErrorSource::fromParameter($parameter));
    }

    /**
     * @test
     */
    public function getPointer()
    {
        $pointer = "/data/attributes/name";

        $errorSource = $this->createErrorSource($pointer, "");
        $this->assertEquals($pointer, $errorSource->getPointer());
    }

    /**
     * @test
     */
    public function getParameter()
    {
        $parameter = "name";

        $errorSource = $this->createErrorSource("", $parameter);
        $this->assertEquals($parameter, $errorSource->getParameter());
    }

    /**
     * @test
     */
    public function transformWithPointer()
    {
        $pointer = "/data/attributes/name";

        $errorSource = $this->createErrorSource($pointer, "");

        $transformedErrorSource = [
            "pointer" => "/data/attributes/name",
        ];
        $this->assertEquals($transformedErrorSource, $errorSource->transform());
    }

    /**
     * @test
     */
    public function transformWithParameter()
    {
        $parameter = "name";

        $errorSource = $this->createErrorSource("", $parameter);

        $transformedErrorSource = [
            "parameter" => "name"
        ];
        $this->assertEquals($transformedErrorSource, $errorSource->transform());
    }

    /**
     * @test
     */
    public function transformWithBothAttributes()
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
