<?php
declare(strict_types=1);

namespace WoohooLabs\Yin\Tests\JsonApi\Double;

use WoohooLabs\Yin\JsonApi\Document\AbstractSuccessfulDocument;
use WoohooLabs\Yin\JsonApi\Schema\Data\DataInterface;
use WoohooLabs\Yin\JsonApi\Schema\JsonApiObject;
use WoohooLabs\Yin\JsonApi\Schema\Link\Links;
use WoohooLabs\Yin\JsonApi\Transformer\Transformation;

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
     * @var Links|null
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
        ?Links $links = null,
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

    public function getLinks(): ?Links
    {
        return $this->links;
    }

    protected function createData(): DataInterface
    {
        return $this->data ?? new DummyData();
    }

    protected function fillData(Transformation $transformation): void
    {
    }

    protected function getRelationshipMember(
        string $relationshipName,
        Transformation $transformation,
        array $additionalMeta = []
    ): ?array {
        return $this->relationshipResponseContent;
    }
}
