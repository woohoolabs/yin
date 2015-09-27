<?php
namespace WoohooLabs\Yin\JsonApi\Response;

use Psr\Http\Message\ResponseInterface;
use WoohooLabs\Yin\JsonApi\Request\RequestInterface;
use WoohooLabs\Yin\JsonApi\Transformer\AbstractSuccessfulDocument;

class RelationshipResponder extends AbstractResponder
{
    /**
     * @var string
     */
    protected $relationshipName;

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

    /**
     * Returns a "200 Ok" response, containing a document in the body with the relationship.
     *
     * @param \WoohooLabs\Yin\JsonApi\Transformer\AbstractSuccessfulDocument $document
     * @param mixed $domainObject
     * @return \Psr\Http\Message\ResponseInterface
     */
    public function ok(AbstractSuccessfulDocument $document, $domainObject)
    {
        return $this->getDocumentRelationshipResponse(
            $this->relationshipName,
            $this->request,
            $this->response,
            $document,
            $domainObject,
            200
        );
    }

    /**
     * Returns a "200 Ok" response, containing a document with the relationship meta data in the body.
     *
     * @param \WoohooLabs\Yin\JsonApi\Transformer\AbstractSuccessfulDocument $document
     * @param mixed $domainObject
     * @return \Psr\Http\Message\ResponseInterface
     */
    public function okWithMeta(AbstractSuccessfulDocument $document, $domainObject)
    {
        return $this->getDocumentRelationshipResponse(
            $this->relationshipName,
            $this->request,
            $this->response,
            $document,
            $domainObject,
            200
        );
    }
}
