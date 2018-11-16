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
    public function createFromPaginationQueryParams()
    {
        $pagination = $this->createPagination(1, 10);
        $paginationFromQueryParam = OffsetBasedPagination::fromPaginationQueryParams(["offset" => 1, "limit" => 10]);

        $this->assertEquals($pagination, $paginationFromQueryParam);
    }

    /**
     * @test
     */
    public function createFromMissingPaginationQueryParams()
    {
        $pagination = $this->createPagination(1, 10);
        $paginationFromQueryParam = OffsetBasedPagination::fromPaginationQueryParams([], 1, 10);

        $this->assertEquals($pagination, $paginationFromQueryParam);
    }

    /**
     * @test
     */
    public function createFromEmptyPaginationQueryParams()
    {
        $pagination = $this->createPagination(1, 10);
        $paginationFromQueryParam = OffsetBasedPagination::fromPaginationQueryParams(["offset" => "", "limit" => ""], 1, 10);

        $this->assertEquals($pagination, $paginationFromQueryParam);
    }

    /**
     * @test
     */
    public function getOffset()
    {
        $pagination = $this->createPagination(1, 10);

        $offset = $pagination->getOffset();

        $this->assertEquals(1, $offset);
    }

    /**
     * @test
     */
    public function getLimit()
    {
        $pagination = $this->createPagination(1, 10);

        $limit = $pagination->getLimit();

        $this->assertEquals(10, $limit);
    }

    /**
     * @test
     */
    public function getPaginationQueryString()
    {
        $offset = 1;
        $limit = 10;

        $this->assertEquals(
            "page[offset]=$offset&page[limit]=$limit",
            OffsetBasedPagination::getPaginationQueryString($offset, $limit)
        );
    }

    private function createPagination(int $offset, int $limit)
    {
        return new OffsetBasedPagination($offset, $limit);
    }
}
