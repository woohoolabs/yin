<?php
declare(strict_types=1);

namespace WoohooLabs\Yin\Tests\JsonApi\Request\Pagination;

use PHPUnit\Framework\TestCase;
use WoohooLabs\Yin\JsonApi\Request\Pagination\FixedPageBasedPagination;
use function urldecode;

class FixedPageBasedPaginationTest extends TestCase
{
    /**
     * @test
     */
    public function fromPaginationQueryParams()
    {
        $pagination = FixedPageBasedPagination::fromPaginationQueryParams(["number" => 1]);

        $this->assertEquals(1, $pagination->getPage());
    }

    /**
     * @test
     */
    public function fromPaginationQueryParamsWhenMissing()
    {
        $pagination = FixedPageBasedPagination::fromPaginationQueryParams([], 1);

        $this->assertEquals(1, $pagination->getPage());
    }

    /**
     * @test
     */
    public function fromPaginationQueryParamsWhenEmpty()
    {
        $pagination = FixedPageBasedPagination::fromPaginationQueryParams(["number" => ""], 1);

        $this->assertEquals(1, $pagination->getPage());
    }

    /**
     * @test
     */
    public function fromPaginationQueryParamsWhenZero()
    {
        $pagination = FixedPageBasedPagination::fromPaginationQueryParams(["number" => "0"], 1);

        $this->assertEquals(0, $pagination->getPage());
    }

    /**
     * @test
     */
    public function fromPaginationQueryParamsWhenNonNumeric()
    {
        $pagination = FixedPageBasedPagination::fromPaginationQueryParams(["number" => "abc"], 1);

        $this->assertEquals(1, $pagination->getPage());
    }

    /**
     * @test
     */
    public function getPage()
    {
        $pagination = $this->createPagination(1);

        $page = $pagination->getPage();

        $this->assertEquals(1, $page);
    }

    /**
     * @test
     */
    public function getPaginationQueryString()
    {
        $queryString = FixedPageBasedPagination::getPaginationQueryString(1);

        $this->assertEquals("page[number]=1", urldecode($queryString));
    }

    private function createPagination($page): FixedPageBasedPagination
    {
        return new FixedPageBasedPagination($page);
    }
}
