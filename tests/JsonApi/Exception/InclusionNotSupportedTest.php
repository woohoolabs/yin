<?php
namespace WoohooLabs\Yin\Tests\JsonApi\Exception;

use PHPUnit\Framework\TestCase;
use WoohooLabs\Yin\JsonApi\Exception\InclusionUnsupported;

class InclusionNotSupportedTest extends TestCase
{
    /**
     * @test
     */
    public function getMessage()
    {
        $exception = $this->createException();
        $this->assertEquals("Inclusion is not supported!", $exception->getMessage());
    }

    private function createException()
    {
        return new InclusionUnsupported();
    }
}
