<?php
declare(strict_types=1);

namespace WoohooLabs\Yin\Tests\JsonApi\Request\Pagination;

use PHPUnit\Framework\TestCase;
use WoohooLabs\Yin\JsonApi\Request\Pagination\CursorBasedPagination;

class CursorBasedPaginationTest extends TestCase
{
    /**
     * @test
     */
    public function createFromPaginationQueryParams()
    {
        $pagination = $this->createPagination("abc");
        $paginationFromQueryParam = CursorBasedPagination::fromPaginationQueryParams(["cursor" => "abc"]);

        $this->assertEquals($pagination, $paginationFromQueryParam);
    }

    /**
     * @test
     */
    public function createFromMissingPaginationQueryParams()
    {
        $pagination = $this->createPagination("abc");
        $paginationFromQueryParam = CursorBasedPagination::fromPaginationQueryParams([], "abc");

        $this->assertEquals($pagination, $paginationFromQueryParam);
    }

    /**
     * @test
     */
    public function createFromEmptyPaginationQueryParams()
    {
        $pagination = $this->createPagination("abc");
        $paginationFromQueryParam = CursorBasedPagination::fromPaginationQueryParams(["cursor" => ""], "abc");

        $this->assertEquals($pagination, $paginationFromQueryParam);
    }

    /**
     * @test
     */
    public function getCursor()
    {
        $pagination = $this->createPagination("abc");

        $cursor = $pagination->getCursor();

        $this->assertEquals("abc", $cursor);
    }

    /**
     * @test
     */
    public function getPaginationQueryString()
    {
        $queryString = CursorBasedPagination::getPaginationQueryString("abc");

        $this->assertEquals("page[cursor]=abc", $queryString);
    }

    private function createPagination($cursor): CursorBasedPagination
    {
        return new CursorBasedPagination($cursor);
    }
}
