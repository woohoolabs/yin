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
    public function fromPaginationQueryParams(): void
    {
        $pagination = CursorBasedPagination::fromPaginationQueryParams(["cursor" => "abc", "size" => "10"]);

        $this->assertEquals("abc", $pagination->getCursor());
        $this->assertEquals(10, $pagination->getSize());
    }

    /**
     * @test
     */
    public function fromMissingPaginationQueryParams(): void
    {
        $pagination = CursorBasedPagination::fromPaginationQueryParams([], "abc", 10);

        $this->assertEquals("abc", $pagination->getCursor());
        $this->assertEquals(10, $pagination->getSize());
    }

    /**
     * @test
     */
    public function fromEmptyPaginationQueryParams(): void
    {
        $pagination = CursorBasedPagination::fromPaginationQueryParams(["cursor" => "", "size" => ""], "abc", 10);

        $this->assertEquals("", $pagination->getCursor());
        $this->assertEquals(10, $pagination->getSize());
    }

    /**
     * @test
     */
    public function getCursor(): void
    {
        $pagination = $this->createPagination("abc", 10);

        $cursor = $pagination->getCursor();
        $size = $pagination->getSize();

        $this->assertEquals("abc", $cursor);
        $this->assertEquals(10, $size);
    }

    /**
     * @test
     */
    public function getPaginationQueryString(): void
    {
        $queryString = CursorBasedPagination::getPaginationQueryString("abc", 10);

        $this->assertEquals("page%5Bcursor%5D=abc&page%5Bsize%5D=10", $queryString);
    }

    private function createPagination(string $cursor, int $page = 0): CursorBasedPagination
    {
        return new CursorBasedPagination($cursor, $page);
    }
}
