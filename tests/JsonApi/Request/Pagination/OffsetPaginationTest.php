<?php
namespace WoohooLabsTest\Yin\JsonApi\Request\Pagination;

use PHPUnit_Framework_TestCase;
use WoohooLabs\Yin\JsonApi\Request\Pagination\OffsetPagination;

class OffsetPaginationTest extends PHPUnit_Framework_TestCase
{
    public function testCreateFromPaginationQueryParams()
    {
        $offset = 1;
        $limit = 10;
        $query = ["offset" => $offset, "limit" => $limit];

        $this->assertEquals(
            $this->createPagination($offset, $limit),
            OffsetPagination::fromPaginationQueryParams($query)
        );
    }

    public function testCreateFromMissingPaginationQueryParams()
    {
        $offset = 1;
        $limit = 10;
        $query = [];

        $this->assertEquals(
            $this->createPagination($offset, $limit),
            OffsetPagination::fromPaginationQueryParams($query, $offset, $limit)
        );
    }

    public function testCreateFromEmptyPaginationQueryParams()
    {
        $offset = 1;
        $limit = 10;
        $query = ["offset" => "", "limit" => ""];

        $this->assertEquals(
            $this->createPagination($offset, $limit),
            OffsetPagination::fromPaginationQueryParams($query, $offset, $limit)
        );
    }

    public function testGetOffset()
    {
        $offset = 1;

        $pagination = $this->createPagination($offset, 10);
        $this->assertEquals($offset, $pagination->getOffset());
    }

    public function testGetLimit()
    {
        $limit = 10;

        $pagination = $this->createPagination(1, $limit);
        $this->assertEquals($limit, $pagination->getLimit());
    }

    public function testGetPaginationQueryString()
    {
        $offset = 1;
        $limit = 10;

        $this->assertEquals(
            "page[offset]=$offset&page[limit]=$limit",
            OffsetPagination::getPaginationQueryString($offset, $limit)
        );
    }

    private function createPagination($offset, $limit)
    {
        return new OffsetPagination($offset, $limit);
    }
}
