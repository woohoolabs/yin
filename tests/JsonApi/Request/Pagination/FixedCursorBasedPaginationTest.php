<?php
declare(strict_types=1);

namespace WoohooLabs\Yin\Tests\JsonApi\Request\Pagination;

use PHPUnit\Framework\TestCase;
use WoohooLabs\Yin\JsonApi\Request\Pagination\FixedCursorBasedPagination;
use function urldecode;

class FixedCursorBasedPaginationTest extends TestCase
{
    /**
     * @test
     */
    public function fromPaginationQueryParams(): void
    {
        $pagination = FixedCursorBasedPagination::fromPaginationQueryParams(["cursor" => "abc"]);

        $this->assertEquals("abc", $pagination->getCursor());
    }

    /**
     * @test
     */
    public function fromMissingPaginationQueryParams(): void
    {
        $pagination = FixedCursorBasedPagination::fromPaginationQueryParams([], "abc");

        $this->assertEquals("abc", $pagination->getCursor());
    }

    /**
     * @test
     */
    public function fromEmptyPaginationQueryParams(): void
    {
        $pagination = FixedCursorBasedPagination::fromPaginationQueryParams(["cursor" => ""], "abc");

        $this->assertEquals("", $pagination->getCursor());
    }

    /**
     * @test
     */
    public function getCursor(): void
    {
        $pagination = $this->createPagination("abc");

        $cursor = $pagination->getCursor();

        $this->assertEquals("abc", $cursor);
    }

    /**
     * @test
     */
    public function getPaginationQueryString(): void
    {
        $queryString = FixedCursorBasedPagination::getPaginationQueryString("abc");

        $this->assertEquals("page[cursor]=abc", urldecode($queryString));
    }

    private function createPagination(string $cursor): FixedCursorBasedPagination
    {
        return new FixedCursorBasedPagination($cursor);
    }
}
