<?php
declare(strict_types=1);

namespace WoohooLabs\Yin\Tests\JsonApi\Scema\Document;

use PHPUnit\Framework\TestCase;
use WoohooLabs\Yin\JsonApi\Schema\Error\Error;
use WoohooLabs\Yin\Tests\JsonApi\Double\StubErrorDocument;

class AbstractErrorDocumentTest extends TestCase
{
    /**
     * @test
     */
    public function getErrors()
    {
        $error = (new Error())->setId("abc");

        $errorDocument = $this->createErrorDocument()->addError($error)->addError($error);
        $this->assertEquals([$error, $error], $errorDocument->getErrors());
    }

    /**
     * @test
     */
    public function getResponseWithoutError()
    {
        $content = $this->createErrorDocument()->getContent();

        $this->assertArrayNotHasKey("errors", $content);
    }

    /**
     * @test
     */
    public function getResponseWithOneError()
    {
        $content = $this->createErrorDocument()->addError((new Error())->setStatus("404"))->getContent();

        $this->assertCount(1, $content["errors"]);
    }

    /**
     * @test
     */
    public function getResponseWithMultipleErrors()
    {
        $content = $this
            ->createErrorDocument()
            ->addError((new Error())->setStatus("403"))
            ->addError((new Error())->setStatus("404"))
            ->addError((new Error())->setStatus("418"))
            ->getContent();

        $this->assertCount(3, $content["errors"]);
    }

    private function createErrorDocument(): StubErrorDocument
    {
        return new StubErrorDocument();
    }
}
