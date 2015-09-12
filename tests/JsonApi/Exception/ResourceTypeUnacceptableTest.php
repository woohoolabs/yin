<?php
namespace WoohooLabsTest\Yin\JsonApi\Schema;

use PHPUnit_Framework_TestCase;
use WoohooLabs\Yin\JsonApi\Exception\ResourceTypeUnacceptable;

class ResourceTypeUnacceptableTest extends PHPUnit_Framework_TestCase
{
    public function testGetType()
    {
        $type = "book";

        $exception = $this->createException($type);
        $this->assertEquals($type, $exception->getType());
    }

    private function createException($type)
    {
        return new ResourceTypeUnacceptable($type);
    }
}
