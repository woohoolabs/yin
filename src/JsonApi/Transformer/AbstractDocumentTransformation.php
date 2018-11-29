<?php
declare(strict_types=1);

namespace WoohooLabs\Yin\JsonApi\Transformer;

use WoohooLabs\Yin\JsonApi\Exception\ExceptionFactoryInterface;
use WoohooLabs\Yin\JsonApi\Request\JsonApiRequestInterface;
use WoohooLabs\Yin\JsonApi\Schema\Document\DocumentInterface;

/**
 * @internal
 */
abstract class AbstractDocumentTransformation
{
    /**
     * @var DocumentInterface
     */
    public $document;

    /**
     * @var JsonApiRequestInterface
     */
    public $request;

    /**
     * @var array
     */
    public $additionalMeta;

    /**
     * @var ExceptionFactoryInterface
     */
    public $exceptionFactory;

    /**
     * @var array
     */
    public $result = [];

    public function __construct(
        DocumentInterface $document,
        JsonApiRequestInterface $request,
        array $additionalMeta,
        ExceptionFactoryInterface $exceptionFactory
    ) {
        $this->document = $document;
        $this->request = $request;
        $this->additionalMeta = $additionalMeta;
        $this->exceptionFactory = $exceptionFactory;
    }
}
