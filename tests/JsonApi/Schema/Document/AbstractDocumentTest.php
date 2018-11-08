<?php
declare(strict_types=1);

namespace WoohooLabs\Yin\Tests\JsonApi\Schema\Document;

use PHPUnit\Framework\TestCase;
use WoohooLabs\Yin\JsonApi\Exception\DefaultExceptionFactory;
use WoohooLabs\Yin\JsonApi\Transformer\ResourceDocumentTransformation;
use WoohooLabs\Yin\Tests\JsonApi\Double\StubRequest;
use WoohooLabs\Yin\Tests\JsonApi\Double\StubResourceDocument;

class AbstractDocumentTest extends TestCase
{
    /**
     * @test
     */
    public function initializeTransformation()
    {
        $document = $this->createDocument();

        $document->initializeTransformation(
            new ResourceDocumentTransformation(
                $document,
                [],
                new StubRequest(),
                "",
                "",
                [],
                new DefaultExceptionFactory()
            )
        );

        $this->assertNotNull($document->getRequest());
        $this->assertEquals([], $document->getObject());
        $this->assertNotNull($document->getExceptionFactory());
        $this->assertNotNull($document->getAdditionalMeta());
    }

    /**
     * @test
     */
    public function clearTransformation()
    {
        $document = $this->createDocument();

        $document->initializeTransformation(
            new ResourceDocumentTransformation(
                $document,
                [],
                new StubRequest(),
                "",
                "",
                [],
                new DefaultExceptionFactory()
            )
        );
        $document->clearTransformation();

        $this->assertNull($document->getRequest());
        $this->assertNull($document->getObject());
        $this->assertNull($document->getExceptionFactory());
        $this->assertEmpty($document->getAdditionalMeta());
    }

    protected function createDocument(): StubResourceDocument
    {
        return new StubResourceDocument();
    }
}
