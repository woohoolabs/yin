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
