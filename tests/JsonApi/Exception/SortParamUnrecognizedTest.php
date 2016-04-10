<?php
namespace WoohooLabsTest\Yin\JsonApi\Exception;

use PHPUnit_Framework_TestCase;
use WoohooLabs\Yin\JsonApi\Exception\SortParamUnrecognized;

class SortParamUnrecognizedTest extends PHPUnit_Framework_TestCase
{
    /**
     * @test
     */
    public function getSortParam()
    {
        $sortParam = "id";
        $exception = $this->createException($sortParam);

        $this->assertEquals($sortParam, $exception->getSortParam());
    }

    private function createException($sortParam)
    {
        return new SortParamUnrecognized($sortParam);
    }
}
