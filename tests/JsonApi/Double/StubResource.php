<?php
declare(strict_types=1);

namespace WoohooLabs\Yin\Tests\JsonApi\Double;

use WoohooLabs\Yin\JsonApi\Exception\ExceptionFactoryInterface;
use WoohooLabs\Yin\JsonApi\Request\RequestInterface;
use WoohooLabs\Yin\JsonApi\Schema\Link\ResourceLinks;
use WoohooLabs\Yin\JsonApi\Schema\Resource\AbstractResource;

class StubResource extends AbstractResource
{
    /**
     * @var string
     */
    protected $type;

    /**
     * @var string
     */
    protected $id;

    /**
     * @var array
     */
    protected $meta;

    /**
     * @var ResourceLinks|null
     */
    protected $links;

    /**
     * @var array
     */
    protected $attributes;

    /**
     * @var array
     */
    protected $defaultRelationships;

    /**
     * @var array
     */
    protected $relationships;

    public function __construct(
        string $type = "",
        string $id = "",
        array $meta = [],
        ?ResourceLinks $links = null,
        array $attributes = [],
        array $defaultRelationships = [],
        array $relationships = []
    ) {
        $this->type = $type;
        $this->id = $id;
        $this->meta = $meta;
        $this->links = $links;
        $this->attributes = $attributes;
        $this->defaultRelationships = $defaultRelationships;
        $this->relationships = $relationships;
    }

    public function getType($object): string
    {
        return $this->type;
    }

    public function getId($object): string
    {
        return $this->id;
    }

    public function getMeta($object): array
    {
        return $this->meta;
    }

    public function getLinks($object): ?ResourceLinks
    {
        return $this->links;
    }

    public function getAttributes($object): array
    {
        return $this->attributes;
    }

    public function getDefaultIncludedRelationships($object): array
    {
        return $this->defaultRelationships;
    }

    public function getRelationships($object): array
    {
        return $this->relationships;
    }

    public function getRequest(): ?RequestInterface
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
}
