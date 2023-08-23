<?php

declare(strict_types=1);

namespace Devleand\Yin\JsonApi\Transformer;

use Devleand\Yin\JsonApi\Schema\Document\ErrorDocumentInterface;

/**
 * @internal
 */
class ErrorDocumentTransformation extends AbstractDocumentTransformation
{
    /**
     * @var ErrorDocumentInterface
     */
    public $document;
}
