<?php
declare(strict_types=1);

namespace WoohooLabs\Yin\JsonApi\Document;

use WoohooLabs\Yin\JsonApi\Exception\ExceptionFactoryInterface;
use WoohooLabs\Yin\JsonApi\Request\RequestInterface;
use WoohooLabs\Yin\JsonApi\Schema\Data\DataInterface;
use WoohooLabs\Yin\JsonApi\Transformer\Transformation;

abstract class AbstractSuccessfulDocument extends AbstractDocument
{
    /**
     * @var mixed
     */
    protected $domainObject;

    abstract protected function createData(): DataInterface;

    /**
     * Fills the transformation data based on the "domainObject" property.
     */
    abstract protected function fillData(Transformation $transformation): void;

    abstract protected function getRelationshipMember(
        string $relationshipName,
        Transformation $transformation,
        array $additionalMeta = []
    ): ?array;

    /**
     * Transform a $domainObject resource in a JSON:API format
     *
     * @param mixed $domainObject
     */
    public function getContent(
        RequestInterface $request,
        ExceptionFactoryInterface $exceptionFactory,
        $domainObject,
        array $additionalMeta = []
    ): array {
        $transformation = new Transformation($request, $this->createData(), $exceptionFactory, "");

        $this->initializeDocument($domainObject);

        return $this->transformContent($transformation, $additionalMeta);
    }

    /**
     * @param mixed $domainObject
     */
    public function getMetaContent(
        $domainObject,
        array $additionalMeta = []
    ): array {
        $this->initializeDocument($domainObject);

        return $this->transformBaseContent($additionalMeta);
    }

    /**
     * @param mixed $domainObject
     */
    public function getRelationshipContent(
        string $relationshipName,
        RequestInterface $request,
        ExceptionFactoryInterface $exceptionFactory,
        $domainObject,
        array $additionalMeta = []
    ): array {
        $transformation = new Transformation($request, $this->createData(), $exceptionFactory, "");
        $this->initializeDocument($domainObject);
        return $this->transformRelationshipContent($relationshipName, $transformation, $additionalMeta);
    }

    /**
     * @param mixed $domainObject
     */
    private function initializeDocument($domainObject): void
    {
        $this->domainObject = $domainObject;
    }

    protected function transformContent(Transformation $transformation, array $additionalMeta = []): array
    {
        $content = $this->transformBaseContent($additionalMeta);

        // Data
        $this->fillData($transformation);
        $content["data"] = $transformation->data->transformPrimaryResources();

        // Included
        if ($transformation->data->hasIncludedResources() || $transformation->request->hasIncludedRelationships()) {
            $content["included"] = $transformation->data->transformIncludedResources();
        }

        return $content;
    }

    protected function transformRelationshipContent(
        string $relationshipName,
        Transformation $transformation,
        array $additionalMeta = []
    ): array {
        $content = $this->getRelationshipMember($relationshipName, $transformation, $additionalMeta);

        // Included
        if ($transformation->data->hasIncludedResources() || $transformation->request->hasIncludedRelationships()) {
            $content["included"] = $transformation->data->transformIncludedResources();
        }

        return $content;
    }

    protected function transformRelationshipMetaContent(
        string $relationshipName,
        Transformation $transformation,
        array $additionalMeta = []
    ): array {
        $content = $this->getRelationshipMember($relationshipName, $transformation, $additionalMeta);

        return $content;
    }
}
