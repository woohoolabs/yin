<?php
namespace WoohooLabs\Yin\JsonApi\Response;

use Psr\Http\Message\ResponseInterface;
use WoohooLabs\Yin\JsonApi\Request\RequestInterface;
use WoohooLabs\Yin\JsonApi\Transformer\AbstractSuccessfulDocument;
use WoohooLabs\Yin\JsonApi\Transformer\AbstractErrorDocument;

class UpdateRelationshipResponse extends AbstractResponse
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
     * Returns a "200 Ok" response, containing a document with the relationship in the body.
     *
     * According to the JSON API specification, this response is applicable in the following conditions:
     * "If a server accepts an update but also changes the targeted relationship(s) in other ways than those
     * specified by the request, it MUST return a 200 OK response. The response document MUST include a
     * representation of the updated relationship(s)."
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
     * According to the JSON API specification, this response is applicable in the following conditions:
     * "A server MUST return a 200 OK status code if an update is successful, the client's current data
     * remain up to date, and the server responds only with top-level meta data. In this case the server
     * MUST NOT include a representation of the updated relationship(s)."
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

    /**
     * Returns a "202 Accepted" response.
     *
     * According to the JSON API specification, this response is applicable in the following conditions:
     * "If a relationship update request has been accepted for processing, but the processing has not been
     * completed by the time the server responds, the server MUST return a 202 Accepted status code."
     *
     * @return \Psr\Http\Message\ResponseInterface $response
     */
    public function accepted()
    {
        return $this->response->withStatus(202);
    }

    /**
     * Returns a "204 No Content" response.
     *
     * According to the JSON API specification, this response is applicable in the following conditions:
     * "A server MUST return a 204 No Content status code if an update is successful and the representation
     * of the resource in the request matches the result."
     *
     * @return \Psr\Http\Message\ResponseInterface $response
     */
    public function noContent()
    {
        return $this->response->withStatus(204);
    }

    /**
     * Returns a "403 Forbidden" response, containing a document in the body with the errors.
     *
     * According to the JSON API specification, this response is applicable in the following conditions:
     * "A server MUST return 403 Forbidden in response to an unsupported request to update a relationship."
     *
     * @param \WoohooLabs\Yin\JsonApi\Transformer\AbstractErrorDocument $document
     * @param \WoohooLabs\Yin\JsonApi\Schema\Error[] $errors
     * @return \Psr\Http\Message\ResponseInterface $response
     */
    public function forbidden(AbstractErrorDocument $document, array $errors = [])
    {
        return $this->getErrorResponse($this->response, $document, $errors, 403);
    }
}
