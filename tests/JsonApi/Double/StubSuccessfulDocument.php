<?php
declare(strict_types=1);

namespace WoohooLabs\Yin\Tests\JsonApi\Double;

use WoohooLabs\Yin\JsonApi\Schema\Data\DataInterface;
use WoohooLabs\Yin\JsonApi\Schema\Document\AbstractSuccessfulDocument;
use WoohooLabs\Yin\JsonApi\Schema\JsonApiObject;
use WoohooLabs\Yin\JsonApi\Schema\Link\DocumentLinks;
use WoohooLabs\Yin\JsonApi\Schema\Resource\Transformation;
use WoohooLabs\Yin\JsonApi\Transformer\ResourceTransformer;
use WoohooLabs\Yin\JsonApi\Transformer\SuccessfulDocumentTransformation;

class StubSuccessfulDocument extends AbstractSuccessfulDocument
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

    public function getData(SuccessfulDocumentTransformation $transformation, ResourceTransformer $transformer): DataInterface
    {
        return $this->data ?? new DummyData();
    }

    protected function fillData(Transformation $documentTransformation): void
    {
    }

    public function getRelationshipData(SuccessfulDocumentTransformation $transformation): array
    {
        return $this->relationshipResponseContent;
    }
}
