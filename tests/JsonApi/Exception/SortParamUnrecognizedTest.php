<?php
namespace WoohooLabsTest\Yin\JsonApi\Schema;

use PHPUnit_Framework_TestCase;
use WoohooLabs\Yin\JsonApi\Exception\SortParamUnrecognized;

class SortParamUnrecognizedTest extends PHPUnit_Framework_TestCase
{
    public function testGetSortParam()
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
