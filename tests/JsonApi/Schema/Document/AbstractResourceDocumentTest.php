<?php

declare(strict_types=1);

namespace Devleand\Yin\Tests\JsonApi\Schema\Document;

use PHPUnit\Framework\TestCase;
use Devleand\Yin\JsonApi\Exception\DefaultExceptionFactory;
use Devleand\Yin\JsonApi\Schema\Data\DataInterface;
use Devleand\Yin\JsonApi\Schema\Document\ResourceDocumentInterface;
use Devleand\Yin\JsonApi\Schema\JsonApiObject;
use Devleand\Yin\JsonApi\Schema\Link\DocumentLinks;
use Devleand\Yin\JsonApi\Transformer\ResourceDocumentTransformation;
use Devleand\Yin\Tests\JsonApi\Double\StubJsonApiRequest;
use Devleand\Yin\Tests\JsonApi\Double\StubResourceDocument;

class AbstractResourceDocumentTest extends TestCase
{
    /**
     * @test
     */
    public function initializeTransformation(): void
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
    public function clearTransformation(): void
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
