<?php
declare(strict_types=1);

namespace WoohooLabs\Yin\JsonApi\Document;

use WoohooLabs\Yin\JsonApi\Schema\Error;
use WoohooLabs\Yin\JsonApi\Schema\JsonApi;
use WoohooLabs\Yin\JsonApi\Schema\Links;

class ErrorDocument extends AbstractErrorDocument
{
    /**
     * @var JsonApi|null
     */
    protected $jsonApi;

    /**
     * @var array
     */
    protected $meta = [];

    /**
     * @var Links|null
     */
    protected $links;

    /**
     * @param Error[] $errors
     */
    public function __construct(array $errors = [])
    {
        foreach ($errors as $error) {
            $this->addError($error);
        }
    }

    /**
     * @return JsonApi|null
     */
    public function getJsonApi()
    {
        return $this->jsonApi;
    }

    /**
     * @return $this
     */
    public function setJsonApi(JsonApi $jsonApi)
    {
        $this->jsonApi = $jsonApi;

        return $this;
    }

    public function getMeta(): array
    {
        return $this->meta;
    }

    /**
     * @return $this
     */
    public function setMeta(array $meta)
    {
        $this->meta = $meta;

        return $this;
    }

    public function getLinks()
    {
        return $this->links;
    }

    /**
     * @return $this
     */
    public function setLinks(Links $links)
    {
        $this->links = $links;

        return $this;
    }
}
