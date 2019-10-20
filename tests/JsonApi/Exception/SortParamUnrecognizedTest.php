<?php

declare(strict_types=1);

namespace WoohooLabs\Yin\Tests\JsonApi\Exception;

use PHPUnit\Framework\TestCase;
use WoohooLabs\Yin\JsonApi\Exception\SortParamUnrecognized;

class SortParamUnrecognizedTest extends TestCase
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
    public function getSortParam(): void
    {
        $exception = $this->createException("param");

        $sortParam = $exception->getSortParam();

        $this->assertEquals("param", $sortParam);
    }

    private function createException(string $sortParam): SortParamUnrecognized
    {
        return new SortParamUnrecognized($sortParam);
    }
}
