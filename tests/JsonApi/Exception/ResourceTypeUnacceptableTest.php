<?php
namespace WoohooLabsTest\Yin\JsonApi\Exception;

use PHPUnit\Framework\TestCase;
use WoohooLabs\Yin\JsonApi\Exception\ResourceTypeUnacceptable;

class ResourceTypeUnacceptableTest extends TestCase
{
    /**
     * @test
     */
    public function getType()
    {
        $type = "book";

        $exception = $this->createException($type, []);
        $this->assertEquals($type, $exception->getCurrentType());
    }

    /**
     * @test
     */
    public function getAcceptedTypes()
    {
        $acceptedTypes = ["book"];

        $exception = $this->createException("", $acceptedTypes);
        $this->assertEquals(["book"], $exception->getAcceptedTypes());
    }

    private function createException($type, array $acceptedTypes)
    {
        return new ResourceTypeUnacceptable($type, $acceptedTypes);
    }
}
