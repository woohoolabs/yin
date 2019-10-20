<?php

declare(strict_types=1);

namespace WoohooLabs\Yin\JsonApi\Transformer;

use WoohooLabs\Yin\JsonApi\Schema\Document\ErrorDocumentInterface;

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
