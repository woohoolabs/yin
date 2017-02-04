<?php
declare(strict_types=1);

namespace WoohooLabs\Yin\Tests\JsonApi\Exception;

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
