<?php
declare(strict_types=1);

namespace WoohooLabs\Yin\JsonApi\Schema\Document;

use WoohooLabs\Yin\JsonApi\Schema\Error\Error;
use WoohooLabs\Yin\JsonApi\Schema\JsonApiObject;
use WoohooLabs\Yin\JsonApi\Schema\Link\DocumentLinks;

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
     * @var DocumentLinks|null
     */
    protected $links;

    /**
     * @param Error[] $errors
     */
    public static function create(array $errors = []): ErrorDocument
    {
        return new ErrorDocument($errors);
    }

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

    public function getLinks(): ?DocumentLinks
    {
        return $this->links;
    }

    public function setLinks(?DocumentLinks $links): ErrorDocument
    {
        $this->links = $links;

        return $this;
    }
}
