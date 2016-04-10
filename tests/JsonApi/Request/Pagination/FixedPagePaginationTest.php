<?php
namespace WoohooLabsTest\Yin\JsonApi\Request\Pagination;

use PHPUnit_Framework_TestCase;
use WoohooLabs\Yin\JsonApi\Request\Pagination\FixedPagePagination;

class FixedPagePaginationTest extends PHPUnit_Framework_TestCase
{
    /**
     * @test
     */
    public function CreateFromPaginationQueryParams()
    {
        $page = 1;
        $query = ["number" => $page];

        $this->assertEquals($this->createPagination($page), FixedPagePagination::fromPaginationQueryParams($query));
    }

    /**
     * @test
     */
    public function CreateFromMissingPaginationQueryParams()
    {
        $page = 1;
        $query = [];

        $this->assertEquals(
            $this->createPagination($page),
            FixedPagePagination::fromPaginationQueryParams($query, $page)
        );
    }

    /**
     * @test
     */
    public function CreateFromEmptyPaginationQueryParams()
    {
        $page = 1;
        $query = ["number" => ""];

        $this->assertEquals(
            $this->createPagination($page),
            FixedPagePagination::fromPaginationQueryParams($query, $page)
        );
    }

    /**
     * @test
     */
    public function GetPage()
    {
        $page = 1;

        $pagination = $this->createPagination($page);
        $this->assertEquals($page, $pagination->getPage());
    }

    /**
     * @test
     */
    public function GetPaginationQueryString()
    {
        $page = 1;

        $this->assertEquals("page[number]=$page", FixedPagePagination::getPaginationQueryString($page));
    }

    private function createPagination($page)
    {
        return new FixedPagePagination($page);
    }
}
