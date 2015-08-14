<?php
namespace WoohooLabs\Yin\JsonApi\Response\Traits;

use Psr\Http\Message\ResponseInterface;
use WoohooLabs\Yin\JsonApi\Request\RequestInterface;
use WoohooLabs\Yin\JsonApi\Transformer\AbstractCompoundDocument;

trait RelationshipOkTrait
{
    use RelationshipResponseTrait
    use GenericResponseTrait;

    /**
     * @param \WoohooLabs\Yin\JsonApi\Transformer\AbstractCompoundDocument $document
     * @param mixed $resource
     * @return \Psr\Http\Message\ResponseInterface
     */
    public function ok(AbstractCompoundDocument $document, $resource)
    {
        return self::getOk($this->relationshipName, $this->request, $this->response, $document, $resource);
    }

    /**
     * @param string $relationshipName
     * @param \WoohooLabs\Yin\JsonApi\Request\RequestInterface $request
     * @param \Psr\Http\Message\ResponseInterface $response
     * @param \WoohooLabs\Yin\JsonApi\Transformer\AbstractCompoundDocument $document
     * @param mixed $resource
     * @return \Psr\Http\Message\ResponseInterface
     */
    public static function getOk(
        $relationshipName,
        RequestInterface $request,
        ResponseInterface $response,
        AbstractCompoundDocument $document,
        $resource
    ) {
        return self::getDocumentRelationshipResponse($relationshipName, $request, $response, $document, $resource, 200);
    }
}
