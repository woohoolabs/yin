<?php
declare(strict_types=1);

namespace WoohooLabs\Yin\Tests\JsonApi\Schema\Document;

use PHPUnit\Framework\TestCase;
use WoohooLabs\Yin\JsonApi\Exception\DefaultExceptionFactory;
use WoohooLabs\Yin\JsonApi\Schema\Data\DataInterface;
use WoohooLabs\Yin\JsonApi\Schema\Document\ResourceDocumentInterface;
use WoohooLabs\Yin\JsonApi\Schema\JsonApiObject;
use WoohooLabs\Yin\JsonApi\Schema\Link\DocumentLinks;
use WoohooLabs\Yin\JsonApi\Transformer\ResourceDocumentTransformation;
use WoohooLabs\Yin\Tests\JsonApi\Double\StubJsonApiRequest;
use WoohooLabs\Yin\Tests\JsonApi\Double\StubResourceDocument;

class AbstractResourceDocumentTest extends TestCase
{
    /**
     * @test
     */
    public function initializeTransformation()
    {
        $document = $this->createDocument();
        $transformation = $this->createTransformation($document);

        $document->initializeTransformation($transformation);

        $this->assertEquals($transformation->request, $document->getRequest());
        $this->assertEquals($transformation->object, $document->getObject());
        $this->assertEquals($transformation->exceptionFactory, $document->getExceptionFactory());
    }

    /**
     * @test
     */
    public function clearTransformation()
    {
        $document = $this->createDocument();
        $transformation = $this->createTransformation($document);

        $document->initializeTransformation($transformation);
        $document->clearTransformation();

        $this->assertNotNull($document->getRequest());
        $this->assertNotNull($document->getObject());
        $this->assertNotNull($document->getExceptionFactory());
    }

    private function createTransformation(ResourceDocumentInterface $document): ResourceDocumentTransformation
    {
        return new ResourceDocumentTransformation(
            $document,
            [],
            new StubJsonApiRequest(),
            "",
            "",
            [],
            new DefaultExceptionFactory()
        );
    }

    private function createDocument(
        ?JsonApiObject $jsonApi = null,
        array $meta = [],
        ?DocumentLinks $links = null,
        ?DataInterface $data = null,
        array $relationshipResponseContent = []
    ): StubResourceDocument {
        return new StubResourceDocument(
            $jsonApi,
            $meta,
            $links,
            $data,
            $relationshipResponseContent
        );
    }
}
