<?php
namespace WoohooLabs\Yin\JsonApi\Transformer;

use Psr\Http\Message\ResponseInterface;
use WoohooLabs\Yin\JsonApi\Request\Criteria;
use WoohooLabs\Yin\JsonApi\Schema\Included;

abstract class AbstractCollectionDocument extends AbstractCompoundDocument
{
    /**
     * @param \Psr\Http\Message\ResponseInterface $response
     * @param \WoohooLabs\Yin\JsonApi\Request\Criteria $criteria
     */
    public function __construct(ResponseInterface $response, Criteria $criteria)
    {
        parent::__construct($response, $criteria);
    }
}
