<?php
declare(strict_types=1);

namespace WoohooLabs\Yin\Tests\JsonApi\Schema\Resource;

use PHPUnit\Framework\TestCase;
use WoohooLabs\Yin\JsonApi\Exception\DefaultExceptionFactory;
use WoohooLabs\Yin\JsonApi\Transformer\ResourceTransformation;
use WoohooLabs\Yin\Tests\JsonApi\Double\StubJsonApiRequest;
use WoohooLabs\Yin\Tests\JsonApi\Double\StubResource;

class AbstractResourceTest extends TestCase
{
    /**
     * @test
     */
    public function initializeTransformation()
    {
        $resource = $this->createResource();
        $transformation = $this->createTransformation($resource);

        $resource->initializeTransformation(
            $transformation->request,
            $transformation->object,
            $transformation->exceptionFactory
        );

        $this->assertEquals($transformation->request, $resource->getRequest());
        $this->assertEquals($transformation->object, $resource->getObject());
        $this->assertEquals($transformation->exceptionFactory, $resource->getExceptionFactory());
    }

    /**
     * @test
     */
    public function clearTransformation()
    {
        $resource = $this->createResource();
        $transformation = $this->createTransformation($resource);

        $resource->initializeTransformation(
            $transformation->request,
            $transformation->object,
            $transformation->exceptionFactory
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

    /**
     * @param $resource
     * @return ResourceTransformation
     */
    private function createTransformation($resource): ResourceTransformation
    {
        return new ResourceTransformation(
            $resource,
            [],
            "",
            new StubJsonApiRequest(),
            "",
            "",
            "",
            new DefaultExceptionFactory()
        );
    }
}
