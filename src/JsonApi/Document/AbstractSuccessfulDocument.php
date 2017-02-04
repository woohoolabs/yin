<?php
declare(strict_types=1);

namespace WoohooLabs\Yin\JsonApi\Document;

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
    abstract protected function createData();

    /**
     * Fills the transformation data based on the "domainObject" property.
     *
     * @param \WoohooLabs\Yin\JsonApi\Transformer\Transformation $transformation
     */
    abstract protected function fillData(Transformation $transformation);

    /**
     *
     * @param string $relationshipName
     * @param \WoohooLabs\Yin\JsonApi\Transformer\Transformation $transformation
     * @param array $additionalMeta
     * @return array
     */
    abstract protected function getRelationshipContent(
        $relationshipName,
        Transformation $transformation,
        array $additionalMeta = []
    );

    /**
     *
     * Transform a $domainObject resource in a jsonapi format
     *
     * @param \WoohooLabs\Yin\JsonApi\Request\RequestInterface $request
     * @param \WoohooLabs\Yin\JsonApi\Exception\ExceptionFactoryInterface $exceptionFactory
     * @param mixed $domainObject
     * @param array $additionalMeta
     * @return array
     */
    public function getContent(
        RequestInterface $request,
        ExceptionFactoryInterface $exceptionFactory,
        $domainObject,
        array $additionalMeta = []
    ) {
        $transformation = new Transformation($request, $this->createData(), $exceptionFactory, "");

        $this->initializeDocument($domainObject);

        return $this->transformContent($transformation, $additionalMeta);
    }

    /**
     *
     * @param \WoohooLabs\Yin\JsonApi\Request\RequestInterface $request
     * @param \WoohooLabs\Yin\JsonApi\Exception\ExceptionFactoryInterface $exceptionFactory
     * @param mixed $domainObject
     * @param array $additionalMeta
     * @return array
     */
    public function getMetaContent(
        RequestInterface $request,
        ExceptionFactoryInterface $exceptionFactory,
        $domainObject,
        array $additionalMeta = []
    ) {
        $this->initializeDocument($domainObject);

        return $this->transformBaseContent($additionalMeta);
    }

    /**
     *
     * @param string $relationshipName
     * @param \WoohooLabs\Yin\JsonApi\Request\RequestInterface $request
     * @param \WoohooLabs\Yin\JsonApi\Exception\ExceptionFactoryInterface $exceptionFactory
     * @param mixed $domainObject
     * @param array $additionalMeta
     * @return array
     */
    public function getRelationship(
        $relationshipName,
        RequestInterface $request,
        ExceptionFactoryInterface $exceptionFactory,
        $domainObject,
        array $additionalMeta = []
    ) {
        $transformation = new Transformation($request, $this->createData(), $exceptionFactory, "");
        $this->initializeDocument($domainObject);
        return $this->transformRelationshipContent($relationshipName, $transformation, $additionalMeta);
    }

    /**
     * @param mixed $domainObject
     */
    private function initializeDocument($domainObject)
    {
        $this->domainObject = $domainObject;
    }

    /**
     * @param array $additionalMeta
     * @param \WoohooLabs\Yin\JsonApi\Transformer\Transformation $transformation
     * @return array
     */
    protected function transformContent(Transformation $transformation, array $additionalMeta = [])
    {
        $content = $this->transformBaseContent($additionalMeta);

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
     * @param array $additionalMeta
     * @return array
     */
    protected function transformRelationshipContent(
        $relationshipName,
        Transformation $transformation,
        array $additionalMeta = []
    ) {
        $content = $this->getRelationshipContent($relationshipName, $transformation, $additionalMeta);

        // Included
        if ($transformation->data->hasIncludedResources()) {
            $content["included"] = $transformation->data->transformIncludedResources();
        }

        return $content;
    }
}
