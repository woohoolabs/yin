<?php

declare(strict_types=1);

namespace Devleand\Yin\Examples\Utils;

use Devleand\Yin\JsonApi\Exception\DefaultExceptionFactory;
use Devleand\Yin\JsonApi\Exception\JsonApiExceptionInterface;
use Devleand\Yin\JsonApi\Request\JsonApiRequestInterface;

class ExampleExceptionFactory extends DefaultExceptionFactory
{
    public function createResourceNotFoundException(JsonApiRequestInterface $request): JsonApiExceptionInterface
    {
        return new ExampleResourceNotFound();
    }
}
