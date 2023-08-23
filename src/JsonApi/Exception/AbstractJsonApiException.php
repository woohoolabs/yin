<?php

declare(strict_types=1);

namespace Devleand\Yin\JsonApi\Exception;

use Exception;
use Devleand\Yin\JsonApi\Schema\Document\ErrorDocument;
use Devleand\Yin\JsonApi\Schema\Document\ErrorDocumentInterface;
use Devleand\Yin\JsonApi\Schema\Error\Error;

abstract class AbstractJsonApiException extends Exception implements JsonApiExceptionInterface
{
    /**
     * @return Error[]
     */
    abstract protected function getErrors(): array;

    public function getErrorDocument(): ErrorDocumentInterface
    {
        return new ErrorDocument($this->getErrors());
    }
}
