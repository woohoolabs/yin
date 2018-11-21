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
    public function fromPaginationQueryParams()
    {
        $pagination = PageBasedPagination::fromPaginationQueryParams(["number" => 1, "size" => 10]);

        $this->assertEquals(1, $pagination->getPage());
        $this->assertEquals(10, $pagination->getSize());
    }

    /**
     * @test
     */
    public function fromPaginationQueryParamsWhenMissing()
    {
        $pagination = PageBasedPagination::fromPaginationQueryParams([], 1, 10);

        $this->assertEquals(1, $pagination->getPage());
        $this->assertEquals(10, $pagination->getSize());
    }

    /**
     * @test
     */
    public function fromPaginationQueryParamsWhenEmpty()
    {
        $pagination = PageBasedPagination::fromPaginationQueryParams(["number" => "", "size" => ""], 1, 10);

        $this->assertEquals(1, $pagination->getPage());
        $this->assertEquals(10, $pagination->getSize());
    }

    /**
     * @test
     */
    public function fromPaginationQueryParamsWhenZero()
    {
        $pagination = PageBasedPagination::fromPaginationQueryParams(["number" => "0", "size" => "0"], 1, 10);

        $this->assertEquals(0, $pagination->getPage());
        $this->assertEquals(0, $pagination->getSize());
    }

    /**
     * @test
     */
    public function fromPaginationQueryParamsWhenNonNumeric()
    {
        $pagination = PageBasedPagination::fromPaginationQueryParams(["number" => "abc", "size" => "abc"], 1, 10);

        $this->assertEquals(1, $pagination->getPage());
        $this->assertEquals(10, $pagination->getSize());
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

        $this->assertEquals("page[number]=1&page[size]=10", urldecode($queryString));
    }

    private function createPagination(?int $page, ?int $size): PageBasedPagination
    {
        return new PageBasedPagination($page, $size);
    }
}
