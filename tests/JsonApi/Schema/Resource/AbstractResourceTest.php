<?php
declare(strict_types=1);

namespace WoohooLabs\Yin\Tests\JsonApi\Schema\Resource;

use PHPUnit\Framework\TestCase;
use WoohooLabs\Yin\JsonApi\Exception\DefaultExceptionFactory;
use WoohooLabs\Yin\JsonApi\Transformer\ResourceTransformation;
use WoohooLabs\Yin\Tests\JsonApi\Double\StubRequest;
use WoohooLabs\Yin\Tests\JsonApi\Double\StubResource;

class AbstractResourceTest extends TestCase
{
    /**
     * @test
     */
    public function initializeTransformation()
    {
        $resource = $this->createResource();

        $resource->initializeTransformation(
            new ResourceTransformation(
                $resource,
                [],
                "",
                new StubRequest(),
                "",
                "",
                "",
                new DefaultExceptionFactory()
            )
        );

        $this->assertNotNull($resource->getRequest());
        $this->assertEquals([], $resource->getObject());
        $this->assertNotNull($resource->getExceptionFactory());
    }

    /**
     * @test
     */
    public function clearTransformation()
    {
        $resource = $this->createResource();

        $resource->initializeTransformation(
            new ResourceTransformation(
                $resource,
                [],
                "",
                new StubRequest(),
                "",
                "",
                "",
                new DefaultExceptionFactory()
            )
        );
        $resource->clearTransformation();

        $this->assertNull($resource->getRequest());
        $this->assertNull($resource->getObject());
        $this->assertNull($resource->getExceptionFactory());
    }

    protected function createResource(): StubResource
    {
        return new StubResource();
    }
}
