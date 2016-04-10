<?php
namespace WoohooLabsTest\Yin\JsonApi\Exception;

use PHPUnit_Framework_TestCase;
use WoohooLabs\Yin\JsonApi\Exception\ResourceIdInvalid;

class ResourceIdInvalidTest extends PHPUnit_Framework_TestCase
{
    /**
     * @test
     */
    public function getId()
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
