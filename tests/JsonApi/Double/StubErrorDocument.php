<?php

declare(strict_types=1);

namespace Devleand\Yin\Tests\JsonApi\Double;

use Devleand\Yin\JsonApi\Schema\Document\AbstractErrorDocument;
use Devleand\Yin\JsonApi\Schema\Error\Error;
use Devleand\Yin\JsonApi\Schema\JsonApiObject;
use Devleand\Yin\JsonApi\Schema\Link\DocumentLinks;

class StubErrorDocument extends AbstractErrorDocument
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
     * @param Error[] $errors
     */
    public function __construct(?JsonApiObject $jsonApi = null, array $meta = [], ?DocumentLinks $links = null, array $errors = [])
    {
        $this->jsonApi = $jsonApi;
        $this->meta = $meta;
        $this->links = $links;
        $this->errors = $errors;
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
}
