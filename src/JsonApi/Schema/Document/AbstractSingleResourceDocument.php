<?php
declare(strict_types=1);

namespace WoohooLabs\Yin\JsonApi\Schema\Document;

use WoohooLabs\Yin\JsonApi\Schema\Data\DataInterface;
use WoohooLabs\Yin\JsonApi\Schema\Data\SingleResourceData;
use WoohooLabs\Yin\JsonApi\Schema\Resource\ResourceInterface;
use WoohooLabs\Yin\JsonApi\Transformer\Transformation;

abstract class AbstractSingleResourceDocument extends AbstractSuccessfulDocument
{
    /**
     * @var ResourceInterface
     */
    protected $transformer;

    public function __construct(ResourceInterface $transformer)
    {
        $this->transformer = $transformer;
    }

    /**
     * Returns the resource ID for the current domain object.
     *
     * It is a shortcut of calling the resource transformer's getId() method.
     */
    public function getResourceId(): string
    {
        return $this->transformer->getId($this->domainObject);
    }

    protected function createData(): DataInterface
    {
        return new SingleResourceData();
    }

    protected function fillData(Transformation $transformation): void
    {
        $resource = $this->transformer->transformToResource($transformation, $this->domainObject);

        if ($resource) {
            $transformation->data->addPrimaryResource($resource);
        }
    }

    protected function getRelationshipMember(
        string $relationshipName,
        Transformation $transformation,
        array $additionalMeta = []
    ): array {
        return $this->transformer->transformRelationship(
            $relationshipName,
            $transformation,
            $this->domainObject,
            $additionalMeta
        );
    }
}
