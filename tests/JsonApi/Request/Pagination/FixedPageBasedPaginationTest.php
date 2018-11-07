<?php
declare(strict_types=1);

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
        $pagination = $this->createPagination(1);
        $paginationFromQueryParam = FixedPageBasedPagination::fromPaginationQueryParams(["number" => 1]);

        $this->assertEquals($pagination, $paginationFromQueryParam);
    }

    /**
     * @test
     */
    public function createFromMissingPaginationQueryParams()
    {
        $pagination = $this->createPagination(1);
        $paginationFromQueryParam = FixedPageBasedPagination::fromPaginationQueryParams([], 1);

        $this->assertEquals($pagination, $paginationFromQueryParam);
    }

    /**
     * @test
     */
    public function createFromEmptyPaginationQueryParams()
    {
        $pagination = $this->createPagination(1);
        $paginationFromQueryParam = FixedPageBasedPagination::fromPaginationQueryParams(["number" => ""], 1);

        $this->assertEquals($pagination, $paginationFromQueryParam);
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

        $this->assertEquals("page[number]=1", $queryString);
    }

    private function createPagination($page): FixedPageBasedPagination
    {
        return new FixedPageBasedPagination($page);
    }
}
