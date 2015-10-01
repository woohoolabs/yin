<?php
namespace WoohooLabs\Yin\JsonApi\Document;

use Psr\Http\Message\ResponseInterface;
use WoohooLabs\Yin\JsonApi\Request\RequestInterface;

abstract class AbstractSuccessfulDocument extends AbstractDocument
{
    /**
     * @var mixed
     */
    protected $domainObject;

    /**
     * @var \WoohooLabs\Yin\JsonApi\Schema\Data\DataInterface
     */
    protected $data;

    /**
     * @return \WoohooLabs\Yin\JsonApi\Schema\Data\DataInterface
     */
    abstract protected function instantiateData();

    /**
     * Sets the value of the "data" and "included" properties based on the "domainObject" property.
     *
     * @param \WoohooLabs\Yin\JsonApi\Request\RequestInterface $request
     */
    abstract protected function setData(RequestInterface $request);

    /**
     * Returns a response content whose primary data is a relationship object with $relationshipName name.
     *
     * @param string $relationshipName
     * @param \WoohooLabs\Yin\JsonApi\Request\RequestInterface $request
     * @return array
     */
    abstract protected function getRelationshipContent($relationshipName, RequestInterface $request);

    /**
     * Returns a response with a status code of $responseCode, containing all the provided sections of the document,
     * assembled based on the $domainObject.
     *
     * @param \Psr\Http\Message\ResponseInterface $response
     * @param mixed $domainObject
     * @param \WoohooLabs\Yin\JsonApi\Request\RequestInterface $request
     * @param int $responseCode
     * @return \Psr\Http\Message\ResponseInterface
     */
    public function getResponse(ResponseInterface $response, $domainObject, RequestInterface $request, $responseCode)
    {
        $this->initializeDocument($domainObject);
        $content = $this->transformContent($request);

        return $this->doGetResponse($response, $responseCode, $content);
    }

    /**
     * Returns a response with a status code of $responseCode, only containing meta information (without the "data" and
     * the "included" sections) about the document, assembled based on the $domainObject.
     *
     * @param \Psr\Http\Message\ResponseInterface $response
     * @param mixed $domainObject
     * @param int $responseCode
     * @return \Psr\Http\Message\ResponseInterface
     */
    public function getMetaResponse(ResponseInterface $response, $domainObject, $responseCode)
    {
        $this->initializeDocument($domainObject);
        $content = $this->transformBaseContent();

        return $this->doGetResponse($response, $responseCode, $content);
    }

    /**
     * Returns a response with a status code of $responseCode, containing the $relationshipName relationship object as
     * the primary data, assembled based on the $domainObject.
     *
     * @param string $relationshipName
     * @param \Psr\Http\Message\ResponseInterface $response
     * @param mixed $domainObject
     * @param \WoohooLabs\Yin\JsonApi\Request\RequestInterface $request
     * @param int $responseCode
     * @return \Psr\Http\Message\ResponseInterface
     */
    public function getRelationshipResponse(
        $relationshipName,
        ResponseInterface $response,
        $domainObject,
        RequestInterface $request,
        $responseCode
    ) {
        $this->initializeDocument($domainObject);
        $content = $this->transformRelationshipContent($relationshipName, $request);

        return $this->doGetResponse($response, $responseCode, $content);
    }

    /**
     * @return \WoohooLabs\Yin\JsonApi\Schema\Data\DataInterface
     */
    public function getData()
    {
        return $this->data;
    }

    /**
     * @param mixed $domainObject
     */
    private function initializeDocument($domainObject)
    {
        $this->domainObject = $domainObject;
        $this->data = $this->instantiateData();
    }

    /**
     * @param \Psr\Http\Message\ResponseInterface $response
     * @param int $responseCode
     * @param array $content
     * @return \Psr\Http\Message\ResponseInterface
     */
    private function doGetResponse(ResponseInterface $response, $responseCode, array $content)
    {
        $response = $response->withStatus($responseCode);
        $response = $response->withHeader("Content-Type", $this->getContentType());
        $response->getBody()->rewind();
        $response->getBody()->write(json_encode($content));

        return $response;
    }

    /**
     * @param \WoohooLabs\Yin\JsonApi\Request\RequestInterface $request
     * @return array
     */
    protected function transformContent(RequestInterface $request)
    {
        $content = $this->transformBaseContent();

        // Data
        $this->setData($request);
        $content["data"] = $this->data->transformPrimaryResources();

        // Included
        if ($this->data->hasIncludedResources()) {
            $content["included"] = $this->data->transformIncludedResources();
        }

        return $content;
    }

    /**
     * @param string $relationshipName
     * @param \WoohooLabs\Yin\JsonApi\Request\RequestInterface $request
     * @return array
     */
    protected function transformRelationshipContent($relationshipName, RequestInterface $request)
    {
        $response = $this->getRelationshipContent($relationshipName, $request);

        // Included
        if ($this->data->hasIncludedResources()) {
            $response["included"] = $this->data->transformIncludedResources();
        }

        return $response;
    }
}
