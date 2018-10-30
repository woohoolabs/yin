<?php
declare(strict_types=1);

namespace WoohooLabs\Yin\JsonApi\Schema\Document;

use WoohooLabs\Yin\JsonApi\Schema\Error\Error;
use WoohooLabs\Yin\JsonApi\Schema\JsonApiObject;
use WoohooLabs\Yin\JsonApi\Schema\Link\ErrorLinks;

class ErrorDocument extends AbstractErrorDocument
{
    /**
     * @var JsonApiObject|null
     */
    protected $jsonApi;

    /**
     * @var array
     */
    protected $meta = [];

    /**
     * @var ErrorLinks|null
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

    public function getJsonApi(): ?JsonApiObject
    {
        return $this->jsonApi;
    }

    public function setJsonApi(?JsonApiObject $jsonApi): ErrorDocument
    {
        $this->jsonApi = $jsonApi;

        return $this;
    }

    public function getMeta(): array
    {
        return $this->meta;
    }

    public function setMeta(array $meta): ErrorDocument
    {
        $this->meta = $meta;

        return $this;
    }

    public function getLinks(): ?ErrorLinks
    {
        return $this->links;
    }

    public function setLinks(?ErrorLinks $links): ErrorDocument
    {
        $this->links = $links;

        return $this;
    }
}
