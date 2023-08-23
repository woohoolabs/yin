<?php

declare(strict_types=1);

namespace Devleand\Yin\JsonApi\Exception;

use Throwable;
use Devleand\Yin\JsonApi\Schema\Document\ErrorDocumentInterface;

interface JsonApiExceptionInterface extends Throwable
{
    public function getErrorDocument(): ErrorDocumentInterface;
}
