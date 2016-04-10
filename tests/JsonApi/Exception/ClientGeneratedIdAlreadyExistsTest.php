<?php
namespace WoohooLabsTest\Yin\JsonApi\Exception;

use PHPUnit_Framework_TestCase;
use WoohooLabs\Yin\JsonApi\Exception\ClientGeneratedIdAlreadyExists;

class ClientGeneratedIdAlreadyExistsTest extends PHPUnit_Framework_TestCase
{
    /**
     * @test
     */
    public function getClientGeneratedId()
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
