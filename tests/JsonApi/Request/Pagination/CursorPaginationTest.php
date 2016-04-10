<?php
namespace WoohooLabsTest\Yin\JsonApi\Request\Pagination;

use PHPUnit_Framework_TestCase;
use WoohooLabs\Yin\JsonApi\Request\Pagination\CursorPagination;

class CursorPaginationTest extends PHPUnit_Framework_TestCase
{
    /**
     * @test
     */
    public function createFromPaginationQueryParams()
    {
        $cursor = "abc";
        $query = ["cursor" => $cursor];

        $this->assertEquals($this->createPagination($cursor), CursorPagination::fromPaginationQueryParams($query));
    }

    /**
     * @test
     */
    public function createFromMissingPaginationQueryParams()
    {
        $cursor = "abc";
        $query = [];

        $this->assertEquals(
            $this->createPagination($cursor),
            CursorPagination::fromPaginationQueryParams($query, $cursor)
        );
    }

    /**
     * @test
     */
    public function createFromEmptyPaginationQueryParams()
    {
        $cursor = "abc";
        $query = ["cursor" => ""];

        $this->assertEquals(
            $this->createPagination($cursor),
            CursorPagination::fromPaginationQueryParams($query, $cursor)
        );
    }

    /**
     * @test
     */
    public function getCursor()
    {
        $cursor = "abc";

        $pagination = $this->createPagination($cursor);
        $this->assertEquals($cursor, $pagination->getCursor());
    }

    /**
     * @test
     */
    public function getPaginationQueryString()
    {
        $cursor = "abc";

        $this->assertEquals("page[cursor]=$cursor", CursorPagination::getPaginationQueryString($cursor));
    }

    private function createPagination($cursor)
    {
        return new CursorPagination($cursor);
    }
}
