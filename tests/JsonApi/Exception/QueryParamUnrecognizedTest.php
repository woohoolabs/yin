<?php
namespace WoohooLabsTest\Yin\JsonApi\Exception;

use PHPUnit\Framework\TestCase;
use WoohooLabs\Yin\JsonApi\Exception\QueryParamUnrecognized;

class QueryParamUnrecognizedTest extends TestCase
{
    /**
     * @test
     */
    public function getQueryParam()
    {
        $queryParam = "id";

        $exception = $this->createException($queryParam);
        $this->assertEquals($queryParam, $exception->getUnrecognizedQueryParam());
    }

    private function createException($queryParam)
    {
        return new QueryParamUnrecognized($queryParam);
    }
}
