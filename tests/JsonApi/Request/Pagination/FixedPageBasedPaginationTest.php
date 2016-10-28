<?php
namespace WoohooLabs\Yin\Tests\JsonApi\Request\Pagination;

use PHPUnit\Framework\TestCase;
use WoohooLabs\Yin\JsonApi\Request\Pagination\FixedPageBasedPagination;

class FixedPageBasedPaginationTest extends TestCase
{
    /**
     * @test
     */
    public function createFromPaginationQueryParams()
    {
        $page = 1;
        $query = ["number" => $page];

        $this->assertEquals($this->createPagination($page), FixedPageBasedPagination::fromPaginationQueryParams($query));
    }

    /**
     * @test
     */
    public function createFromMissingPaginationQueryParams()
    {
        $page = 1;
        $query = [];

        $this->assertEquals(
            $this->createPagination($page),
            FixedPageBasedPagination::fromPaginationQueryParams($query, $page)
        );
    }

    /**
     * @test
     */
    public function createFromEmptyPaginationQueryParams()
    {
        $page = 1;
        $query = ["number" => ""];

        $this->assertEquals(
            $this->createPagination($page),
            FixedPageBasedPagination::fromPaginationQueryParams($query, $page)
        );
    }

    /**
     * @test
     */
    public function getPage()
    {
        $page = 1;

        $pagination = $this->createPagination($page);
        $this->assertEquals($page, $pagination->getPage());
    }

    /**
     * @test
     */
    public function getPaginationQueryString()
    {
        $page = 1;

        $this->assertEquals("page[number]=$page", FixedPageBasedPagination::getPaginationQueryString($page));
    }

    private function createPagination($page)
    {
        return new FixedPageBasedPagination($page);
    }
}
