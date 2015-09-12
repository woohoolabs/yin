<?php
namespace WoohooLabsTest\Yin\JsonApi\Exception;

use PHPUnit_Framework_TestCase;
use WoohooLabs\Yin\JsonApi\Request\Pagination\CursorPagination;

class CursorPaginationTest extends PHPUnit_Framework_TestCase
{
    public function testCreateFromPaginationQueryParams()
    {
        $cursor = "abc";
        $query = ["cursor" => $cursor];

        $this->assertEquals($this->createPagination($cursor), CursorPagination::fromPaginationQueryParams($query));
    }

    public function testCreateFromMissingPaginationQueryParams()
    {
        $cursor = "abc";
        $query = [];

        $this->assertEquals(
            $this->createPagination($cursor),
            CursorPagination::fromPaginationQueryParams($query, $cursor)
        );
    }

    public function testCreateFromEmptyPaginationQueryParams()
    {
        $cursor = "abc";
        $query = ["cursor" => ""];

        $this->assertEquals(
            $this->createPagination($cursor),
            CursorPagination::fromPaginationQueryParams($query, $cursor)
        );
    }

    public function testGetCursor()
    {
        $cursor = "abc";

        $pagination = $this->createPagination($cursor);
        $this->assertEquals($cursor, $pagination->getCursor());
    }

    public function testGetPaginationQueryString()
    {
        $cursor = "abc";

        $this->assertEquals("page[cursor]=$cursor", CursorPagination::getPaginationQueryString($cursor));
    }

    private function createPagination($cursor)
    {
        return new CursorPagination($cursor);
    }
}
