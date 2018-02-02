<?php
declare(strict_types=1);

namespace WoohooLabs\Yin\Tests\JsonApi\Request\Pagination;

use PHPUnit\Framework\TestCase;
use WoohooLabs\Yin\JsonApi\Request\Pagination\PageBasedPagination;

class PageBasedPaginationTest extends TestCase
{
    /**
     * @test
     */
    public function createFromPaginationQueryParams()
    {
        $page = 1;
        $size = 10;
        $query = ["number" => $page, "size" => $size];

        $this->assertEquals($this->createPagination($page, $size), PageBasedPagination::fromPaginationQueryParams($query));
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
            PageBasedPagination::fromPaginationQueryParams($query, $page, $size)
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
            PageBasedPagination::fromPaginationQueryParams($query, $page, $size)
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

    public function testGetSize()
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
            PageBasedPagination::getPaginationQueryString($page, $size)
        );
    }

    private function createPagination($page, $size)
    {
        return new PageBasedPagination($page, $size);
    }
}
