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
    public function getErrors()
    {
        $exception = $this->createException("", "");

        $errors = $exception->getErrorDocument()->getErrors();

        $this->assertCount(1, $errors);
        $this->assertEquals("400", $errors[0]->getStatus());
    }

    /**
     * @test
     */
    public function getQueryParam()
    {
        $exception = $this->createException("sort", ["field" => "value"]);

        $this->assertEquals("sort", $exception->getMalformedQueryParam());
        $this->assertEquals(["field" => "value"], $exception->getMalformedQueryParamValue());
    }

    private function createException(string $queryParam, $queryParamValue): QueryParamMalformed
    {
        return new QueryParamMalformed($queryParam, $queryParamValue);
    }
}
