<?php

declare(strict_types=1);

namespace Devleand\Yin\Tests\JsonApi\Request\Pagination;

use PHPUnit\Framework\TestCase;
use Devleand\Yin\JsonApi\Request\Pagination\PageBasedPagination;

class PageBasedPaginationTest extends TestCase
{
    /**
     * @test
     */
    public function fromPaginationQueryParams(): void
    {
        $pagination = PageBasedPagination::fromPaginationQueryParams(["number" => 1, "size" => "10"]);

        $this->assertEquals(1, $pagination->getPage());
        $this->assertEquals(10, $pagination->getSize());
    }

    /**
     * @test
     */
    public function fromPaginationQueryParamsWhenMissing(): void
    {
        $pagination = PageBasedPagination::fromPaginationQueryParams([], 1, 10);

        $this->assertEquals(1, $pagination->getPage());
        $this->assertEquals(10, $pagination->getSize());
    }

    /**
     * @test
     */
    public function fromPaginationQueryParamsWhenEmpty(): void
    {
        $pagination = PageBasedPagination::fromPaginationQueryParams(["number" => "", "size" => ""], 1, 10);

        $this->assertEquals(1, $pagination->getPage());
        $this->assertEquals(10, $pagination->getSize());
    }

    /**
     * @test
     */
    public function fromPaginationQueryParamsWhenZero(): void
    {
        $pagination = PageBasedPagination::fromPaginationQueryParams(["number" => "0", "size" => "0"], 1, 10);

        $this->assertEquals(0, $pagination->getPage());
        $this->assertEquals(0, $pagination->getSize());
    }

    /**
     * @test
     */
    public function fromPaginationQueryParamsWhenNonNumeric(): void
    {
        $pagination = PageBasedPagination::fromPaginationQueryParams(["number" => "abc", "size" => "abc"], 1, 10);

        $this->assertEquals(1, $pagination->getPage());
        $this->assertEquals(10, $pagination->getSize());
    }

    /**
     * @test
     */
    public function getPage(): void
    {
        $pagination = $this->createPagination(1, 10);

        $page = $pagination->getPage();

        $this->assertEquals(1, $page);
    }

    /**
     * @test
     */
    public function getSizeTest(): void
    {
        $pagination = $this->createPagination(1, 10);

        $size = $pagination->getSize();

        $this->assertEquals(10, $size);
    }

    /**
     * @test
     */
    public function getPaginationQueryString(): void
    {
        $queryString = PageBasedPagination::getPaginationQueryString(1, 10);

        $this->assertEquals("page%5Bnumber%5D=1&page%5Bsize%5D=10", $queryString);
    }

    private function createPagination(int $page, int $size): PageBasedPagination
    {
        return new PageBasedPagination($page, $size);
    }
}
