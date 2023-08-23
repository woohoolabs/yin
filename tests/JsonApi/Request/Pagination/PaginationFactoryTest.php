<?php

declare(strict_types=1);

namespace Devleand\Yin\Tests\JsonApi\Request\Pagination;

use Laminas\Diactoros\ServerRequest;
use PHPUnit\Framework\TestCase;
use Devleand\Yin\JsonApi\Exception\DefaultExceptionFactory;
use Devleand\Yin\JsonApi\Request\JsonApiRequest;
use Devleand\Yin\JsonApi\Request\Pagination\CursorBasedPagination;
use Devleand\Yin\JsonApi\Request\Pagination\FixedCursorBasedPagination;
use Devleand\Yin\JsonApi\Request\Pagination\FixedPageBasedPagination;
use Devleand\Yin\JsonApi\Request\Pagination\OffsetBasedPagination;
use Devleand\Yin\JsonApi\Request\Pagination\PageBasedPagination;
use Devleand\Yin\JsonApi\Request\Pagination\PaginationFactory;
use Devleand\Yin\JsonApi\Serializer\JsonDeserializer;

class PaginationFactoryTest extends TestCase
{
    /**
     * @test
     */
    public function createFixedPageBasedPagination(): void
    {
        $paginationFactory = $this->createPaginationFactoryFromRequestQueryParams(
            [
                "page" => ["number" => 1],
            ]
        );

        $pagination = $paginationFactory->createFixedPageBasedPagination();

        $this->assertEquals(new FixedPageBasedPagination(1), $pagination);
    }

    /**
     * @test
     */
    public function createPageBasedPagination(): void
    {
        $paginationFactory = $this->createPaginationFactoryFromRequestQueryParams(
            [
                "page" => ["number" => 1, "size" => 10],
            ]
        );

        $pagination = $paginationFactory->createPageBasedPagination();

        $this->assertEquals(new PageBasedPagination(1, 10), $pagination);
    }

    /**
     * @test
     */
    public function createOffsetBasedPagination(): void
    {
        $paginationFactory = $this->createPaginationFactoryFromRequestQueryParams(
            [
                "page" => ["offset" => 1, "limit" => 10],
            ]
        );

        $pagination = $paginationFactory->createOffsetBasedPagination();

        $this->assertEquals(new OffsetBasedPagination(1, 10), $pagination);
    }

    /**
     * @test
     */
    public function createCursorBasedPagination(): void
    {
        $paginationFactory = $this->createPaginationFactoryFromRequestQueryParams(
            [
                "page" => ["cursor" => "abc", "size" => 10],
            ]
        );

        $pagination = $paginationFactory->createCursorBasedPagination();

        $this->assertEquals(new CursorBasedPagination("abc", 10), $pagination);
    }

    /**
     * @test
     */
    public function createFixedCursorBasedPagination(): void
    {
        $paginationFactory = $this->createPaginationFactoryFromRequestQueryParams(
            [
                "page" => ["cursor" => "abc"],
            ]
        );

        $pagination = $paginationFactory->createFixedCursorBasedPagination();

        $this->assertEquals(new FixedCursorBasedPagination("abc"), $pagination);
    }

    private function createPaginationFactoryFromRequestQueryParams(array $queryParams): PaginationFactory
    {
        return new PaginationFactory($this->createRequestWithQueryParams($queryParams));
    }

    private function createRequestWithQueryParams(array $queryParams): JsonApiRequest
    {
        $psrRequest = new ServerRequest();
        $psrRequest = $psrRequest->withQueryParams($queryParams);

        return new JsonApiRequest($psrRequest, new DefaultExceptionFactory(), new JsonDeserializer());
    }
}
