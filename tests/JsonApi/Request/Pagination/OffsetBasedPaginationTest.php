<?php

declare(strict_types=1);

namespace WoohooLabs\Yin\Tests\JsonApi\Request\Pagination;

use PHPUnit\Framework\TestCase;
use WoohooLabs\Yin\JsonApi\Request\Pagination\OffsetBasedPagination;

class OffsetBasedPaginationTest extends TestCase
{
    /**
     * @test
     */
    public function fromPaginationQueryParams(): void
    {
        $pagination = OffsetBasedPagination::fromPaginationQueryParams(["offset" => 1, "limit" => 10]);

        $this->assertEquals(1, $pagination->getOffset());
        $this->assertEquals(10, $pagination->getLimit());
    }

    /**
     * @test
     */
    public function fromPaginationQueryParamsWhenMissing(): void
    {
        $pagination = OffsetBasedPagination::fromPaginationQueryParams([], 1, 10);

        $this->assertEquals(1, $pagination->getOffset());
        $this->assertEquals(10, $pagination->getLimit());
    }

    /**
     * @test
     */
    public function fromPaginationQueryParamsWhenEmpty(): void
    {
        $pagination = OffsetBasedPagination::fromPaginationQueryParams(["offset" => "", "limit" => ""], 1, 10);

        $this->assertEquals(1, $pagination->getOffset());
        $this->assertEquals(10, $pagination->getLimit());
    }

    /**
     * @test
     */
    public function fromPaginationQueryParamsWhenZero(): void
    {
        $pagination = OffsetBasedPagination::fromPaginationQueryParams(["offset" => "0", "limit" => "0"], 1, 10);

        $this->assertEquals(0, $pagination->getOffset());
        $this->assertEquals(0, $pagination->getLimit());
    }

    /**
     * @test
     */
    public function fromPaginationQueryParamsWhenNonNumeric(): void
    {
        $pagination = OffsetBasedPagination::fromPaginationQueryParams(["offset" => "abc", "limit" => "abc"], 1, 10);

        $this->assertEquals(1, $pagination->getOffset());
        $this->assertEquals(10, $pagination->getLimit());
    }

    /**
     * @test
     */
    public function getOffset(): void
    {
        $pagination = $this->createPagination(1, 10);

        $offset = $pagination->getOffset();

        $this->assertEquals(1, $offset);
    }

    /**
     * @test
     */
    public function getLimit(): void
    {
        $pagination = $this->createPagination(1, 10);

        $limit = $pagination->getLimit();

        $this->assertEquals(10, $limit);
    }

    /**
     * @test
     */
    public function getPaginationQueryString(): void
    {
        $queryString = OffsetBasedPagination::getPaginationQueryString(1, 10);

        $this->assertEquals("page%5Boffset%5D=1&page%5Blimit%5D=10", $queryString);
    }

    private function createPagination(int $offset, int $limit): OffsetBasedPagination
    {
        return new OffsetBasedPagination($offset, $limit);
    }
}
