<?php
namespace WoohooLabsTest\Yin\JsonApi\Exception;

use PHPUnit_Framework_TestCase;
use WoohooLabs\Yin\JsonApi\Exception\InclusionNotSupported;

class InclusionNotSupportedTest extends PHPUnit_Framework_TestCase
{
    public function testGetMessage()
    {
        $exception = $this->createException();
        $this->assertEquals("Inclusion is not supported!", $exception->getMessage());
    }

    private function createException()
    {
        return new InclusionNotSupported();
    }
}
