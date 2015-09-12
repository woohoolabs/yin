<?php
namespace WoohooLabsTest\Yin\JsonApi\Request\Pagination;

use PHPUnit_Framework_TestCase;
use WoohooLabs\Yin\JsonApi\Request\Pagination\PagePagination;

class PagePaginationTest extends PHPUnit_Framework_TestCase
{
    public function testCreateFromPaginationQueryParams()
    {
        $page = 1;
        $size = 10;
        $query = ["number" => $page, "size" => $size];

        $this->assertEquals($this->createPagination($page, $size), PagePagination::fromPaginationQueryParams($query));
    }

    public function testCreateFromMissingPaginationQueryParams()
    {
        $page = 1;
        $size = 10;
        $query = [];

        $this->assertEquals(
            $this->createPagination($page, $size),
            PagePagination::fromPaginationQueryParams($query, $page, $size)
        );
    }

    public function testCreateFromEmptyPaginationQueryParams()
    {
        $page = 1;
        $size = 10;
        $query = ["number" => "", "size" => ""];

        $this->assertEquals(
            $this->createPagination($page, $size),
            PagePagination::fromPaginationQueryParams($query, $page, $size)
        );
    }

    public function testGetPage()
    {
        $page = 1;

        $pagination = $this->createPagination($page, 10);
        $this->assertEquals($page, $pagination->getPage());
    }

    public function testGetSize()
    {
        $size = 10;

        $pagination = $this->createPagination(1, $size);
        $this->assertEquals($size, $pagination->getSize());
    }

    public function testGetPaginationQueryString()
    {
        $page = 1;
        $size = 10;

        $this->assertEquals(
            "page[number]=$page&page[size]=$size",
            PagePagination::getPaginationQueryString($page, $size)
        );
    }

    private function createPagination($page, $size)
    {
        return new PagePagination($page, $size);
    }
}
