<?php
namespace WoohooLabs\Yin\JsonApi\Response;

use Psr\Http\Message\ResponseInterface;
use WoohooLabs\Yin\JsonApi\Request\RequestInterface;
use WoohooLabs\Yin\JsonApi\Transformer\AbstractCompoundDocument;
use WoohooLabs\Yin\JsonApi\Transformer\AbstractErrorDocument;

class FetchRelationshipResponse extends AbstractResponse
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
     * @param \WoohooLabs\Yin\JsonApi\Transformer\AbstractCompoundDocument $document
     * @param mixed $resource
     * @return \Psr\Http\Message\ResponseInterface
     */
    public function ok(AbstractCompoundDocument $document, $resource)
    {
        return $this->getDocumentRelationshipResponse(
            $this->relationshipName,
            $this->request,
            $this->response,
            $document,
            $resource,
            200
        );
    }

    /**
     * @param \WoohooLabs\Yin\JsonApi\Transformer\AbstractErrorDocument $document
     * @param array $errors
     * @return \Psr\Http\Message\ResponseInterface $response
     */
    public function notFound(AbstractErrorDocument $document, array $errors = [])
    {
        return $this->getErrorResponse($this->response, $document, $errors, 404);
    }
}
