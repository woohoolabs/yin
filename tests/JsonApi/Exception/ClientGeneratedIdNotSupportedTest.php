<?php
namespace WoohooLabsTest\Yin\JsonApi\Schema;

use PHPUnit_Framework_TestCase;
use WoohooLabs\Yin\JsonApi\Exception\ClientGeneratedIdNotSupported;

class ClientGeneratedIdNotSupportedTest extends PHPUnit_Framework_TestCase
{
    public function testGetClientGeneratedId()
    {
        $id = "1";

        $exception = $this->createException($id);
        $this->assertEquals($id, $exception->getClientGeneratedId());
    }

    public function testGetReason()
    {
        $reason = "Just because.";

        $exception = $this->createException("", $reason);
        $this->assertEquals($reason, $exception->getReason());
    }

    private function createException($id, $reason = "")
    {
        return new ClientGeneratedIdNotSupported($id, $reason);
    }
}
