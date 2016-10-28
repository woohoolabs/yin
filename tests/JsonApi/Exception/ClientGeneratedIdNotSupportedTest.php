<?php
namespace WoohooLabs\Yin\Tests\JsonApi\Exception;

use PHPUnit\Framework\TestCase;
use WoohooLabs\Yin\JsonApi\Exception\ClientGeneratedIdNotSupported;

class ClientGeneratedIdNotSupportedTest extends TestCase
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
        return new ClientGeneratedIdNotSupported($id);
    }
}
