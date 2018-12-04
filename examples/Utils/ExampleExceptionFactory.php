<?php
declare(strict_types=1);

namespace WoohooLabs\Yin\Examples\Utils;

use WoohooLabs\Yin\JsonApi\Exception\DefaultExceptionFactory;
use WoohooLabs\Yin\JsonApi\Exception\JsonApiExceptionInterface;
use WoohooLabs\Yin\JsonApi\Request\JsonApiRequestInterface;

class ExampleExceptionFactory extends DefaultExceptionFactory
{
    public function createResourceNotFoundException(JsonApiRequestInterface $request): JsonApiExceptionInterface
    {
        return new ExampleResourceNotFound();
    }
}
