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
    public function fromPaginationQueryParams()
    {
        $pagination = CursorBasedPagination::fromPaginationQueryParams(["cursor" => "abc"]);

        $this->assertEquals("abc", $pagination->getCursor());
    }

    /**
     * @test
     */
    public function fromMissingPaginationQueryParams()
    {
        $pagination = CursorBasedPagination::fromPaginationQueryParams([], "abc");

        $this->assertEquals("abc", $pagination->getCursor());
    }

    /**
     * @test
     */
    public function fromEmptyPaginationQueryParams()
    {
        $pagination = CursorBasedPagination::fromPaginationQueryParams(["cursor" => ""], "abc");

        $this->assertEquals("", $pagination->getCursor());
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

        $this->assertEquals("page[cursor]=abc", urldecode($queryString));
    }

    private function createPagination($cursor): CursorBasedPagination
    {
        return new CursorBasedPagination($cursor);
    }
}
