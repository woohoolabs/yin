<?php
namespace WoohooLabsTest\Yin\JsonApi\Request\Pagination;

use PHPUnit_Framework_TestCase;
use WoohooLabs\Yin\JsonApi\Request\Pagination\PagePagination;

class PagePaginationTest extends PHPUnit_Framework_TestCase
{
    /**
     * @test
     */
    public function createFromPaginationQueryParams()
    {
        $page = 1;
        $size = 10;
        $query = ["number" => $page, "size" => $size];

        $this->assertEquals($this->createPagination($page, $size), PagePagination::fromPaginationQueryParams($query));
    }

    /**
     * @test
     */
    public function createFromMissingPaginationQueryParams()
    {
        $page = 1;
        $size = 10;
        $query = [];

        $this->assertEquals(
            $this->createPagination($page, $size),
            PagePagination::fromPaginationQueryParams($query, $page, $size)
        );
    }

    /**
     * @test
     */
    public function createFromEmptyPaginationQueryParams()
    {
        $page = 1;
        $size = 10;
        $query = ["number" => "", "size" => ""];

        $this->assertEquals(
            $this->createPagination($page, $size),
            PagePagination::fromPaginationQueryParams($query, $page, $size)
        );
    }

    /**
     * @test
     */
    public function getPage()
    {
        $page = 1;

        $pagination = $this->createPagination($page, 10);
        $this->assertEquals($page, $pagination->getPage());
    }

    /**
     * @test
     */
    public function getSize()
    {
        $size = 10;

        $pagination = $this->createPagination(1, $size);
        $this->assertEquals($size, $pagination->getSize());
    }

    /**
     * @test
     */
    public function getPaginationQueryString()
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
