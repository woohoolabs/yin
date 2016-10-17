<?php
namespace WoohooLabsTest\Yin\JsonApi\Request\Pagination;

use PHPUnit\Framework\TestCase;
use WoohooLabs\Yin\JsonApi\Request\Pagination\OffsetBasedPagination;

class OffsetPaginationTest extends TestCase
{
    /**
     * @test
     */
    public function createFromPaginationQueryParams()
    {
        $offset = 1;
        $limit = 10;
        $query = ["offset" => $offset, "limit" => $limit];

        $this->assertEquals(
            $this->createPagination($offset, $limit),
            OffsetBasedPagination::fromPaginationQueryParams($query)
        );
    }

    /**
     * @test
     */
    public function createFromMissingPaginationQueryParams()
    {
        $offset = 1;
        $limit = 10;
        $query = [];

        $this->assertEquals(
            $this->createPagination($offset, $limit),
            OffsetBasedPagination::fromPaginationQueryParams($query, $offset, $limit)
        );
    }

    /**
     * @test
     */
    public function createFromEmptyPaginationQueryParams()
    {
        $offset = 1;
        $limit = 10;
        $query = ["offset" => "", "limit" => ""];

        $this->assertEquals(
            $this->createPagination($offset, $limit),
            OffsetBasedPagination::fromPaginationQueryParams($query, $offset, $limit)
        );
    }

    /**
     * @test
     */
    public function getOffset()
    {
        $offset = 1;

        $pagination = $this->createPagination($offset, 10);
        $this->assertEquals($offset, $pagination->getOffset());
    }

    /**
     * @test
     */
    public function getLimit()
    {
        $limit = 10;

        $pagination = $this->createPagination(1, $limit);
        $this->assertEquals($limit, $pagination->getLimit());
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

    private function createPagination($offset, $limit)
    {
        return new OffsetBasedPagination($offset, $limit);
    }
}
