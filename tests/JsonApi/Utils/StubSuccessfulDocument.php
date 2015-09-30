<?php
namespace WoohooLabsTest\Yin\JsonApi\Utils;

use WoohooLabs\Yin\JsonApi\Request\RequestInterface;
use WoohooLabs\Yin\JsonApi\Schema\Data\DataInterface;
use WoohooLabs\Yin\JsonApi\Schema\JsonApi;
use WoohooLabs\Yin\JsonApi\Schema\Links;
use WoohooLabs\Yin\JsonApi\Transformer\AbstractSuccessfulDocument;

class StubSuccessfulDocument extends AbstractSuccessfulDocument
{
    /**
     * @var array
     */
    protected $extensions;

    /**
     * @var array
     */
    protected $supportedExtensions;

    /**
     * @var \WoohooLabs\Yin\JsonApi\Schema\JsonApi
     */
    protected $jsonApi;

    /**
     * @var array
     */
    protected $meta;

    /**
     * @var \WoohooLabs\Yin\JsonApi\Schema\Links
     */
    protected $links;

    /**
     * @var array
     */
    protected $relationshipResponseContent;

    /**
     * @param array $extensions
     * @param array $supportedExtensions
     * @param \WoohooLabs\Yin\JsonApi\Schema\JsonApi|null $jsonApi
     * @param array $meta
     * @param \WoohooLabs\Yin\JsonApi\Schema\Links|null $links
     * @param \WoohooLabs\Yin\JsonApi\Schema\Data\DataInterface $data
     * @param array $relationshipResponseContent
     */
    public function __construct(
        array $extensions = [],
        array $supportedExtensions = [],
        JsonApi $jsonApi = null,
        array $meta = [],
        Links $links = null,
        DataInterface $data = null,
        array $relationshipResponseContent = []
    ) {
        $this->extensions = $extensions;
        $this->supportedExtensions = $supportedExtensions;
        $this->jsonApi = $jsonApi;
        $this->meta = $meta;
        $this->links = $links;
        $this->data = $data;
        $this->relationshipResponseContent = $relationshipResponseContent;
    }

    /**
     * @inheritDoc
     */
    public function getExtensions()
    {
        return $this->extensions;
    }

    /**
     * @inheritDoc
     */
    public function getSupportedExtensions()
    {
        return $this->supportedExtensions;
    }

    /**
     * @inheritDoc
     */
    public function getJsonApi()
    {
        return $this->jsonApi;
    }

    /**
     * @inheritDoc
     */
    public function getMeta()
    {
        return $this->meta;
    }

    /**
     * @inheritDoc
     */
    public function getLinks()
    {
        return $this->links;
    }

    /**
     * @inheritDoc
     */
    protected function instantiateData()
    {
        return $this->data ? $this->data : new DummyData();
    }

    /**
     * Sets the value of the "data" and "included" properties based on the "domainObject" property.
     *
     * @param \WoohooLabs\Yin\JsonApi\Request\RequestInterface $request
     */
    protected function setData(RequestInterface $request)
    {
    }

    /**
     * Returns a response content whose primary data is a relationship object with $relationshipName name.
     *
     * @param string $relationshipName
     * @param \WoohooLabs\Yin\JsonApi\Request\RequestInterface $request
     * @return array
     */
    protected function getRelationshipContent($relationshipName, RequestInterface $request)
    {
        return $this->relationshipResponseContent;
    }
}
