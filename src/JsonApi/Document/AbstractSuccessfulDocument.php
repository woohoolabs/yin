<?php
namespace WoohooLabs\Yin\JsonApi\Document;

use Psr\Http\Message\ResponseInterface;
use WoohooLabs\Yin\JsonApi\Exception\ExceptionFactoryInterface;
use WoohooLabs\Yin\JsonApi\Request\RequestInterface;
use WoohooLabs\Yin\JsonApi\Transformer\Transformation;

abstract class AbstractSuccessfulDocument extends AbstractDocument
{
    /**
     * @var mixed
     */
    protected $domainObject;

    /**
     * @return \WoohooLabs\Yin\JsonApi\Schema\Data\DataInterface
     */
    abstract protected function getData();

    /**
     * Fills the transformation data based on the "domainObject" property.
     *
     * @param \WoohooLabs\Yin\JsonApi\Transformer\Transformation $transformation
     */
    abstract protected function fillData(Transformation $transformation);

    /**
     * Returns a response content whose primary data is a relationship object with $relationshipName name.
     *
     * @param string $relationshipName
     * @param \WoohooLabs\Yin\JsonApi\Transformer\Transformation $transformation
     * @return array
     */
    abstract protected function getRelationshipContent($relationshipName, Transformation $transformation);

    /**
     * Returns a response with a status code of $responseCode, containing all the provided sections of the document,
     * assembled based on the $domainObject.
     *
     * @param \WoohooLabs\Yin\JsonApi\Request\RequestInterface $request
     * @param \Psr\Http\Message\ResponseInterface $response
     * @param \WoohooLabs\Yin\JsonApi\Exception\ExceptionFactoryInterface $exceptionFactory
     * @param mixed $domainObject
     * @param int $responseCode
     * @return \Psr\Http\Message\ResponseInterface
     */
    public function getResponse(
        RequestInterface $request,
        ResponseInterface $response,
        ExceptionFactoryInterface $exceptionFactory,
        $domainObject,
        $responseCode
    ) {
        $transformation = new Transformation($request, $this->getData(), $exceptionFactory, "");

        $this->initializeDocument($domainObject);
        $content = $this->transformContent($transformation);

        return $this->doGetResponse($response, $responseCode, $content);
    }

    /**
     * Returns a response with a status code of $responseCode, only containing meta information (without the "data" and
     * the "included" sections) about the document, assembled based on the $domainObject.
     *
     * @param \WoohooLabs\Yin\JsonApi\Request\RequestInterface $request
     * @param \Psr\Http\Message\ResponseInterface $response
     * @param \WoohooLabs\Yin\JsonApi\Exception\ExceptionFactoryInterface $exceptionFactory
     * @param mixed $domainObject
     * @param int $responseCode
     * @return \Psr\Http\Message\ResponseInterface
     */
    public function getMetaResponse(
        RequestInterface $request,
        ResponseInterface $response,
        ExceptionFactoryInterface $exceptionFactory,
        $domainObject,
        $responseCode
    ) {
        $this->initializeDocument($domainObject);
        $content = $this->transformBaseContent();

        return $this->doGetResponse($response, $responseCode, $content);
    }

    /**
     * Returns a response with a status code of $responseCode, containing the $relationshipName relationship object as
     * the primary data, assembled based on the $domainObject.
     *
     * @param string $relationshipName
     * @param \WoohooLabs\Yin\JsonApi\Request\RequestInterface $request
     * @param \Psr\Http\Message\ResponseInterface $response
     * @param \WoohooLabs\Yin\JsonApi\Exception\ExceptionFactoryInterface $exceptionFactory
     * @param mixed $domainObject
     * @param int $responseCode
     * @return \Psr\Http\Message\ResponseInterface
     */
    public function getRelationshipResponse(
        $relationshipName,
        RequestInterface $request,
        ResponseInterface $response,
        ExceptionFactoryInterface $exceptionFactory,
        $domainObject,
        $responseCode
    ) {
        $transformation = new Transformation($request, $this->getData(), $exceptionFactory, "");
        $this->initializeDocument($domainObject);
        $content = $this->transformRelationshipContent($relationshipName, $transformation);

        return $this->doGetResponse($response, $responseCode, $content);
    }

    /**
     * @param mixed $domainObject
     */
    private function initializeDocument($domainObject)
    {
        $this->domainObject = $domainObject;
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
     * @param \WoohooLabs\Yin\JsonApi\Transformer\Transformation $transformation
     * @return array
     */
    protected function transformContent(Transformation $transformation)
    {
        $content = $this->transformBaseContent();

        // Data
        $this->fillData($transformation);
        $content["data"] = $transformation->data->transformPrimaryResources();

        // Included
        if ($transformation->data->hasIncludedResources()) {
            $content["included"] = $transformation->data->transformIncludedResources();
        }

        return $content;
    }

    /**
     * @param string $relationshipName
     * @param \WoohooLabs\Yin\JsonApi\Transformer\Transformation $transformation
     * @return array
     */
    protected function transformRelationshipContent($relationshipName, Transformation $transformation)
    {
        $response = $this->getRelationshipContent($relationshipName, $transformation);

        // Included
        if ($transformation->data->hasIncludedResources()) {
            $response["included"] = $transformation->data->transformIncludedResources();
        }

        return $response;
    }
}
