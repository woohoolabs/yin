<?php

declare(strict_types=1);

namespace Devleand\Yin\Tests\JsonApi\Double;

use Devleand\Yin\JsonApi\Exception\ExceptionFactoryInterface;
use Devleand\Yin\JsonApi\Request\JsonApiRequestInterface;
use Devleand\Yin\JsonApi\Schema\Data\DataInterface;
use Devleand\Yin\JsonApi\Schema\Document\AbstractResourceDocument;
use Devleand\Yin\JsonApi\Schema\JsonApiObject;
use Devleand\Yin\JsonApi\Schema\Link\DocumentLinks;
use Devleand\Yin\JsonApi\Transformer\ResourceDocumentTransformation;
use Devleand\Yin\JsonApi\Transformer\ResourceTransformer;

class StubResourceDocument extends AbstractResourceDocument
{
    /**
     * @var JsonApiObject|null
     */
    protected $jsonApi;

    /**
     * @var array
     */
    protected $meta;

    /**
     * @var DocumentLinks|null
     */
    protected $links;

    /**
     * @var DataInterface|null
     */
    protected $data;

    /**
     * @var array
     */
    protected $relationshipResponseContent;

    public function __construct(
        ?JsonApiObject $jsonApi = null,
        array $meta = [],
        ?DocumentLinks $links = null,
        ?DataInterface $data = null,
        array $relationshipResponseContent = []
    ) {
        $this->jsonApi = $jsonApi;
        $this->meta = $meta;
        $this->links = $links;
        $this->data = $data;
        $this->relationshipResponseContent = $relationshipResponseContent;
    }

    public function getJsonApi(): ?JsonApiObject
    {
        return $this->jsonApi;
    }

    public function getMeta(): array
    {
        return $this->meta;
    }

    public function getLinks(): ?DocumentLinks
    {
        return $this->links;
    }

    public function getData(ResourceDocumentTransformation $transformation, ResourceTransformer $transformer): DataInterface
    {
        return $this->data ?? new DummyData();
    }

    public function getRelationshipData(
        ResourceDocumentTransformation $transformation,
        ResourceTransformer $transformer,
        DataInterface $data
    ): ?array {
        $ownData = $this->getData($transformation, $transformer);

        $included = $ownData->transformIncluded();
        $data->setIncludedResources($included);

        return $this->relationshipResponseContent;
    }

    public function getRequest(): ?JsonApiRequestInterface
    {
        return $this->request;
    }

    /**
     * @return mixed
     */
    public function getObject()
    {
        return $this->object;
    }

    public function getExceptionFactory(): ?ExceptionFactoryInterface
    {
        return $this->exceptionFactory;
    }

    public function getAdditionalMeta(): array
    {
        return $this->additionalMeta;
    }
}
