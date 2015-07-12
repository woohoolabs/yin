<?php
namespace WoohooLabs\Yin\JsonApi\Transformer;

use Psr\Http\Message\ResponseInterface;

abstract class AbstractCollectionDocument extends AbstractCompoundDocument
{
    /**
     * @param \Psr\Http\Message\ResponseInterface $response
     * @param mixed $resource
     */
    public function __construct(ResponseInterface $response, $resource)
    {
        parent::__construct($response, $resource);
    }
}
