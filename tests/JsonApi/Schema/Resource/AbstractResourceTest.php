<?php
declare(strict_types=1);

namespace WoohooLabs\Yin\Tests\JsonApi\Schema\Resource;

use PHPUnit\Framework\TestCase;
use WoohooLabs\Yin\JsonApi\Schema\Link\ResourceLinks;
use WoohooLabs\Yin\Tests\JsonApi\Double\StubResource;

class AbstractResourceTest extends TestCase
{
    /**
     * @test
     */
    public function transformToResourceIdentifierWhenDomainObjectIsNull()
    {
        $domainObject = null;

        $resource = $this->createResource();

        $this->assertNull(null);
    }

    protected function createResource(
        string $type = "",
        string $id = "",
        array $meta = [],
        ?ResourceLinks $links = null,
        array $attributes = [],
        array $defaultRelationships = [],
        array $relationships = []
    ): StubResource {
        return new StubResource(
            $type,
            $id,
            $meta,
            $links,
            $attributes,
            $defaultRelationships,
            $relationships
        );
    }
}
