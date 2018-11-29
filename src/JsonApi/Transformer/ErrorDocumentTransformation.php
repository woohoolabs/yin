<?php
declare(strict_types=1);

namespace WoohooLabs\Yin\JsonApi\Transformer;

use WoohooLabs\Yin\JsonApi\Exception\ExceptionFactoryInterface;
use WoohooLabs\Yin\JsonApi\Request\JsonApiRequestInterface;
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

    public function __construct(
        ErrorDocumentInterface $document,
        JsonApiRequestInterface $request,
        array $additionalMeta,
        ExceptionFactoryInterface $exceptionFactory
    ) {
        parent::__construct($document, $request, $additionalMeta, $exceptionFactory);
    }
}
