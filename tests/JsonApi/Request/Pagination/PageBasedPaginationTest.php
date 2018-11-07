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
        $pagination = $this->createPagination(1, 10);
        $paginationFromQueryParam = PageBasedPagination::fromPaginationQueryParams(["number" => 1, "size" => 10]);

        $this->assertEquals($pagination, $paginationFromQueryParam);
    }

    /**
     * @test
     */
    public function createFromMissingPaginationQueryParams()
    {
        $pagination = $this->createPagination(1, 10);
        $paginationFromQueryParam = PageBasedPagination::fromPaginationQueryParams([], 1, 10);

        $this->assertEquals($pagination, $paginationFromQueryParam);
    }

    /**
     * @test
     */
    public function createFromEmptyPaginationQueryParams()
    {
        $pagination = $this->createPagination(1, 10);
        $paginationFromQueryParam = PageBasedPagination::fromPaginationQueryParams(["number" => "", "size" => ""], 1, 10);

        $this->assertEquals($pagination, $paginationFromQueryParam);
    }

    /**
     * @test
     */
    public function getPage()
    {
        $pagination = $this->createPagination(1, 10);

        $page = $pagination->getPage();

        $this->assertEquals(1, $page);
    }

    /**
     * @test
     */
    public function getSizeTest()
    {
        $pagination = $this->createPagination(1, 10);

        $size = $pagination->getSize();

        $this->assertEquals(10, $size);
    }

    /**
     * @test
     */
    public function getPaginationQueryString()
    {
        $queryString = PageBasedPagination::getPaginationQueryString(1, 10);

        $this->assertEquals("page[number]=1&page[size]=10", $queryString);
    }

    private function createPagination(?int $page, ?int $size): PageBasedPagination
    {
        return new PageBasedPagination($page, $size);
    }
}
