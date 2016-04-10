<?php
namespace WoohooLabsTest\Yin\JsonApi\Exception;

use PHPUnit_Framework_TestCase;
use WoohooLabs\Yin\JsonApi\Exception\SortingUnsupported;

class SortingNotSupportedTest extends PHPUnit_Framework_TestCase
{
    /**
     * @test
     */
    public function getMessage()
    {
        $exception = $this->createException();

        $this->assertEquals("Sorting is not supported!", $exception->getMessage());
    }

    private function createException()
    {
        return new SortingUnsupported();
    }
}
