<?php
namespace WoohooLabsTest\Yin\JsonApi\Exception;

use PHPUnit_Framework_TestCase;
use WoohooLabs\Yin\JsonApi\Exception\ResourceIdMissing;

class ResourceIdMissingTest extends PHPUnit_Framework_TestCase
{
    public function testGetMessage()
    {
        $exception = $this->createException();
        $this->assertEquals("A resource ID must be included in the request!", $exception->getMessage());
    }

    private function createException()
    {
        return new ResourceIdMissing();
    }
}
