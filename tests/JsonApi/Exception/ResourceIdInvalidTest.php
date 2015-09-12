<?php
namespace WoohooLabsTest\Yin\JsonApi\Schema;

use PHPUnit_Framework_TestCase;
use WoohooLabs\Yin\JsonApi\Exception\ResourceIdInvalid;

class ResourceIdInvalidTest extends PHPUnit_Framework_TestCase
{
    public function testGetId()
    {
        $id = "1";

        $exception = $this->createException($id);
        $this->assertEquals($id, $exception->getId());
    }

    private function createException($id)
    {
        return new ResourceIdInvalid($id);
    }
}
