<?php
declare(strict_types=1);

namespace WoohooLabs\Yin\Tests\JsonApi\Exception;

use PHPUnit\Framework\TestCase;
use WoohooLabs\Yin\JsonApi\Exception\QueryParamMalformed;

class QueryParamMalformedTest extends TestCase
{
    /**
     * @test
     */
    public function getQueryParam()
    {
        $queryParam = "sort";
        $queryParamValue = ["field" => "asc"];

        $exception = $this->createException($queryParam, $queryParamValue);
        $this->assertEquals($queryParam, $exception->getMalformedQueryParam());
        $this->assertEquals($queryParamValue, $exception->getMalformedQueryParamValue());
    }

    private function createException($queryParam, $queryParamValue): QueryParamMalformed
    {
        return new QueryParamMalformed($queryParam, $queryParamValue);
    }
}
