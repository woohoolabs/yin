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
    public function getErrors(): void
    {
        $exception = $this->createException("");

        $errors = $exception->getErrorDocument()->getErrors();

        $this->assertCount(1, $errors);
        $this->assertEquals("400", $errors[0]->getStatus());
    }

    /**
     * @test
     */
    public function getQueryParam(): void
    {
        $exception = $this->createException("param");

        $queryParam = $exception->getUnrecognizedQueryParam();

        $this->assertEquals("param", $queryParam);
    }

    private function createException(string $queryParam): QueryParamUnrecognized
    {
        return new QueryParamUnrecognized($queryParam);
    }
}
