<?php
namespace WoohooLabs\Yin\JsonApi\Response;

use Psr\Http\Message\ResponseInterface;
use WoohooLabs\Yin\JsonApi\Request\RequestInterface;
use WoohooLabs\Yin\JsonApi\Response\Traits\NotFoundTrait;
use WoohooLabs\Yin\JsonApi\Response\Traits\RelationshipOkTrait;
use WoohooLabs\Yin\JsonApi\Response\Traits\RelationshipResponseTrait;

class FetchRelationshipResponse extends AbstractResponse
{
    use RelationshipResponseTrait;
    use RelationshipOkTrait;
    use NotFoundTrait;

    /**
     * @param \WoohooLabs\Yin\JsonApi\Request\RequestInterface $request
     * @param \Psr\Http\Message\ResponseInterface $response
     * @param string $relationshipName
     */
    public function __construct(RequestInterface $request, ResponseInterface $response, $relationshipName)
    {
        parent::__construct($request, $response);
        $this->relationshipName = $relationshipName;
    }
}
