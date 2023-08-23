<?php

declare(strict_types=1);

namespace Devleand\Yin\JsonApi\Request\Pagination;

use Devleand\Yin\JsonApi\Request\JsonApiRequestInterface;

class PaginationFactory
{
    /**
     * @var JsonApiRequestInterface
     */
    private $request;

    public function __construct(JsonApiRequestInterface $request)
    {
        $this->request = $request;
    }

    /**
     * Returns a FixedPageBasedPagination class in order to be used for fixed page-based pagination.
     *
     * The FixedPageBasedPagination class stores the value of the "page[number]" query parameter if present
     * or the $defaultPage otherwise.
     */
    public function createFixedPageBasedPagination(int $defaultPage = 0): FixedPageBasedPagination
    {
        return FixedPageBasedPagination::fromPaginationQueryParams($this->request->getPagination(), $defaultPage);
    }

    /**
     * Returns a PageBasedPagination class in order to be used for page-based pagination.
     *
     * The PageBasedPagination class stores the value of the "page[number]" and "page[size]" query parameters
     * if present or the $defaultPage and $defaultSize otherwise.
     */
    public function createPageBasedPagination(int $defaultPage = 0, int $defaultSize = 0): PageBasedPagination
    {
        return PageBasedPagination::fromPaginationQueryParams($this->request->getPagination(), $defaultPage, $defaultSize);
    }

    /**
     * Returns a OffsetBasedPagination class in order to be used for offset-based pagination.
     *
     * The OffsetBasedPagination class stores the value of the "page[offset]" and "page[limit]" query parameters
     * if present or the $defaultOffset and $defaultLimit otherwise.
     */
    public function createOffsetBasedPagination(int $defaultOffset = 0, int $defaultLimit = 0): OffsetBasedPagination
    {
        return OffsetBasedPagination::fromPaginationQueryParams($this->request->getPagination(), $defaultOffset, $defaultLimit);
    }

    /**
     * Returns a FixedCursorBasedPagination class in order to be used for cursor-based pagination.
     *
     * The FixedCursorBasedPagination class stores the value of the "page[cursor]" query parameter if present
     * or the $defaultCursor otherwise.
     * @param mixed $defaultCursor
     */
    public function createFixedCursorBasedPagination($defaultCursor = null): FixedCursorBasedPagination
    {
        return FixedCursorBasedPagination::fromPaginationQueryParams($this->request->getPagination(), $defaultCursor);
    }

    /**
     * Returns a CursorBasedPagination class in order to be used for cursor-based pagination.
     *
     * The CursorBasedPagination class stores the value of the "page[cursor]" and "page[size]" query parameters if present
     * or the $defaultCursor and $defaultSize otherwise.
     * @param mixed $defaultCursor
     */
    public function createCursorBasedPagination($defaultCursor = null, int $defaultSize = 0): CursorBasedPagination
    {
        return CursorBasedPagination::fromPaginationQueryParams($this->request->getPagination(), $defaultCursor, $defaultSize);
    }
}
