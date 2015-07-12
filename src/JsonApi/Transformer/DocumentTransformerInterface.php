<?php
namespace WoohooLabs\Yin\JsonApi\Transformer;

use WoohooLabs\Yin\JsonApi\Request\Criteria;

interface DocumentTransformerInterface
{
    /**
     * @param int $statusCode
     * @return \Psr\Http\Message\ResponseInterface
     * @param \WoohooLabs\Yin\JsonApi\Request\Criteria $criteria
     */
    public function getResponse($statusCode, Criteria $criteria);
}
