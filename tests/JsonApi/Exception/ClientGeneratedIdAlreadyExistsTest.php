<?php
namespace WoohooLabsTest\Yin\JsonApi\Schema;

use PHPUnit_Framework_TestCase;
use WoohooLabs\Yin\JsonApi\Exception\ClientGeneratedIdAlreadyExists;

class ClientGeneratedIdAlreadyExistsTest extends PHPUnit_Framework_TestCase
{
    public function testGetClientGeneratedId()
    {
        $id = "1";

        $exception = $this->createException($id);
        $this->assertEquals($id, $exception->getClientGeneratedId());
    }

    private function createException($id)
    {
        return new ClientGeneratedIdAlreadyExists($id);
    }
}
