<?php
namespace WoohooLabsTest\Yin\JsonApi\Request\Pagination;

use PHPUnit_Framework_TestCase;
use WoohooLabs\Yin\JsonApi\Request\Pagination\FixedPagePagination;

class FixedPagePaginationTest extends PHPUnit_Framework_TestCase
{
    public function testCreateFromPaginationQueryParams()
    {
        $page = 1;
        $query = ["number" => $page];

        $this->assertEquals($this->createPagination($page), FixedPagePagination::fromPaginationQueryParams($query));
    }

    public function testCreateFromMissingPaginationQueryParams()
    {
        $page = 1;
        $query = [];

        $this->assertEquals(
            $this->createPagination($page),
            FixedPagePagination::fromPaginationQueryParams($query, $page)
        );
    }

    public function testCreateFromEmptyPaginationQueryParams()
    {
        $page = 1;
        $query = ["number" => ""];

        $this->assertEquals(
            $this->createPagination($page),
            FixedPagePagination::fromPaginationQueryParams($query, $page)
        );
    }

    public function testGetPage()
    {
        $page = 1;

        $pagination = $this->createPagination($page);
        $this->assertEquals($page, $pagination->getPage());
    }

    public function testGetPaginationQueryString()
    {
        $page = 1;

        $this->assertEquals("page[number]=$page", FixedPagePagination::getPaginationQueryString($page));
    }

    private function createPagination($page)
    {
        return new FixedPagePagination($page);
    }
}
