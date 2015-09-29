<?php
namespace WoohooLabs\Yin\JsonApi\Transformer;

use WoohooLabs\Yin\JsonApi\Schema\JsonApi;
use WoohooLabs\Yin\JsonApi\Schema\Links;

class ErrorDocument extends AbstractErrorDocument
{
    /**
     * @var \WoohooLabs\Yin\JsonApi\Schema\JsonApi
     */
    protected $jsonApi;

    /**
     * @var array
     */
    protected $meta = [];

    /**
     * @var \WoohooLabs\Yin\JsonApi\Schema\Links
     */
    protected $links;

    /**
     * @param \WoohooLabs\Yin\JsonApi\Schema\Error[] $errors
     */
    public function __construct(array $errors = [])
    {
        foreach ($errors as $error) {
            $this->addError($error);
        }
    }

    /**
     * @return \WoohooLabs\Yin\JsonApi\Schema\JsonApi|null
     */
    public function getJsonApi()
    {
        return $this->jsonApi;
    }

    /**
     * @param \WoohooLabs\Yin\JsonApi\Schema\JsonApi $jsonApi
     * @return $this
     */
    public function setJsonApi(JsonApi $jsonApi)
    {
        $this->jsonApi = $jsonApi;
        return $this;
    }

    /**
     * @return array
     */
    public function getMeta()
    {
        return $this->meta;
    }

    /**
     * @param array $meta
     * @return $this
     */
    public function setMeta(array $meta)
    {
        $this->meta = $meta;
        return $this;
    }

    /**
     * @return \WoohooLabs\Yin\JsonApi\Schema\Links
     */
    public function getLinks()
    {
        return $this->links;
    }

    /**
     * @param \WoohooLabs\Yin\JsonApi\Schema\Links $links
     * @return $this
     */
    public function setLinks(Links $links)
    {
        $this->links = $links;
        return $this;
    }
}
