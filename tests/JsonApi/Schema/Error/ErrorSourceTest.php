<?php

declare(strict_types=1);

namespace WoohooLabs\Yin\Tests\JsonApi\Schema\Error;

use PHPUnit\Framework\TestCase;
use WoohooLabs\Yin\JsonApi\Schema\Error\ErrorSource;

class ErrorSourceTest extends TestCase
{
    /**
     * @test
     */
    public function createFromPointer(): void
    {
        $pointer = "/data/attributes/name";

        $errorSource = $this->createErrorSource($pointer, "");
        $this->assertEquals($errorSource, ErrorSource::fromPointer($pointer));
    }

    /**
     * @test
     */
    public function createFromParameter(): void
    {
        $parameter = "name";

        $errorSource = $this->createErrorSource("", $parameter);
        $this->assertEquals($errorSource, ErrorSource::fromParameter($parameter));
    }

    /**
     * @test
     */
    public function getPointer(): void
    {
        $pointer = "/data/attributes/name";

        $errorSource = $this->createErrorSource($pointer, "");
        $this->assertEquals($pointer, $errorSource->getPointer());
    }

    /**
     * @test
     */
    public function getParameter(): void
    {
        $parameter = "name";

        $errorSource = $this->createErrorSource("", $parameter);
        $this->assertEquals($parameter, $errorSource->getParameter());
    }

    /**
     * @test
     */
    public function transformWithPointer(): void
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
    public function transformWithParameter(): void
    {
        $parameter = "name";

        $errorSource = $this->createErrorSource("", $parameter);

        $transformedErrorSource = [
            "parameter" => "name",
        ];
        $this->assertEquals($transformedErrorSource, $errorSource->transform());
    }

    /**
     * @test
     */
    public function transformWithBothAttributes(): void
    {
        $pointer = "/data/attributes/name";
        $parameter = "name";

        $errorSource = $this->createErrorSource($pointer, $parameter);

        $transformedErrorSource = [
            "pointer" => "/data/attributes/name",
            "parameter" => "name",
        ];
        $this->assertEquals($transformedErrorSource, $errorSource->transform());
    }

    private function createErrorSource(string $pointer, string $parameter): ErrorSource
    {
        return new ErrorSource($pointer, $parameter);
    }
}
