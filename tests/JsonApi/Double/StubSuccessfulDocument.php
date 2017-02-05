<?php
declare(strict_types=1);

namespace WoohooLabs\Yin\Tests\JsonApi\Double;

use WoohooLabs\Yin\JsonApi\Document\AbstractSuccessfulDocument;
use WoohooLabs\Yin\JsonApi\Schema\Data\DataInterface;
use WoohooLabs\Yin\JsonApi\Schema\JsonApi;
use WoohooLabs\Yin\JsonApi\Schema\Links;
use WoohooLabs\Yin\JsonApi\Transformer\Transformation;

class StubSuccessfulDocument extends AbstractSuccessfulDocument
{
    /**
     * @var JsonApi|null
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
        JsonApi $jsonApi = null,
        array $meta = [],
        Links $links = null,
        DataInterface $data = null,
        array $relationshipResponseContent = []
    ) {
        $this->jsonApi = $jsonApi;
        $this->meta = $meta;
        $this->links = $links;
        $this->data = $data;
        $this->relationshipResponseContent = $relationshipResponseContent;
    }

    public function getJsonApi()
    {
        return $this->jsonApi;
    }

    public function getMeta(): array
    {
        return $this->meta;
    }

    public function getLinks()
    {
        return $this->links;
    }

    protected function createData(): DataInterface
    {
        return $this->data ?? new DummyData();
    }

    protected function fillData(Transformation $transformation)
    {
    }

    protected function getRelationshipContent(
        string $relationshipName,
        Transformation $transformation,
        array $additionalMeta = []
    ) {
        return $this->relationshipResponseContent;
    }
}
