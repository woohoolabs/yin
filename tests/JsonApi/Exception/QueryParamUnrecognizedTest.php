<?php
namespace WoohooLabsTest\Yin\JsonApi\Exception;

use PHPUnit_Framework_TestCase;
use WoohooLabs\Yin\JsonApi\Exception\QueryParamUnrecognized;

class QueryParamUnrecognizedTest extends PHPUnit_Framework_TestCase
{
    public function testGetQueryParam()
    {
        $queryParam = "id";

        $exception = $this->createException($queryParam);
        $this->assertEquals($queryParam, $exception->getQueryParam());
    }

    private function createException($queryParam)
    {
        return new QueryParamUnrecognized($queryParam);
    }
}
