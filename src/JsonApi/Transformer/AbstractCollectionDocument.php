<?php
namespace WoohooLabs\Yin\JsonApi\Transformer;

use Psr\Http\Message\ResponseInterface;
use WoohooLabs\Yin\JsonApi\Request\Criteria;

abstract class AbstractCollectionDocument extends AbstractCompoundDocument
{
    /**
     * @param \Psr\Http\Message\ResponseInterface $response
     * @param int $statusCode
     * @param mixed $resource
     * @param \WoohooLabs\Yin\JsonApi\Request\Criteria $criteria
     */
    public function __construct(ResponseInterface $response, $statusCode, $resource, Criteria $criteria)
    {
        parent::__construct($response, $statusCode, $resource, $criteria);
    }
}
