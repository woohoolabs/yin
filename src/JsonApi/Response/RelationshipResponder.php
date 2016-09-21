<?php
namespace WoohooLabs\Yin\JsonApi\Response;

use Psr\Http\Message\ResponseInterface;
use WoohooLabs\Yin\JsonApi\Document\AbstractSuccessfulDocument;
use WoohooLabs\Yin\JsonApi\Exception\ExceptionFactoryInterface;
use WoohooLabs\Yin\JsonApi\Request\RequestInterface;
use WoohooLabs\Yin\JsonApi\Serializer\SerializerInterface;

class RelationshipResponder extends AbstractResponder
{
    /**
     * @var string
     */
    protected $relationshipName;

    /**
     * @param \WoohooLabs\Yin\JsonApi\Request\RequestInterface $request
     * @param \Psr\Http\Message\ResponseInterface $response
     * @param \WoohooLabs\Yin\JsonApi\Exception\ExceptionFactoryInterface $exceptionFactory
     * @param SerializerInterface $serializer
     * @param string $relationshipName
     */
    public function __construct(
        RequestInterface $request,
        ResponseInterface $response,
        ExceptionFactoryInterface $exceptionFactory,
        SerializerInterface $serializer,
        $relationshipName
    ) {
        parent::__construct($request, $response, $exceptionFactory, $serializer);
        $this->relationshipName = $relationshipName;
    }

    /**
     * Returns a "200 Ok" response, containing a document in the body with the relationship.  You can also
     * pass additional meta information for the document in the $additionalMeta argument.
     *
     * @param \WoohooLabs\Yin\JsonApi\Document\AbstractSuccessfulDocument $document
     * @param mixed $domainObject
     * @param array $additionalMeta
     * @return \Psr\Http\Message\ResponseInterface
     */
    public function ok(AbstractSuccessfulDocument $document, $domainObject, array $additionalMeta = [])
    {
        return $this->getDocumentRelationshipResponse(
            $this->relationshipName,
            $document,
            $domainObject,
            200,
            $additionalMeta
        );
    }

    /**
     * Returns a "200 Ok" response, containing a document with the relationship meta data in the body. You can also
     * pass additional meta information for the document in the $additionalMeta argument.
     *
     * @param \WoohooLabs\Yin\JsonApi\Document\AbstractSuccessfulDocument $document
     * @param mixed $domainObject
     * @param array $additionalMeta
     * @return \Psr\Http\Message\ResponseInterface
     */
    public function okWithMeta(AbstractSuccessfulDocument $document, $domainObject, array $additionalMeta = [])
    {
        return $this->getDocumentRelationshipMetaResponse(
            $this->relationshipName,
            $document,
            $domainObject,
            200,
            $additionalMeta
        );
    }
}
