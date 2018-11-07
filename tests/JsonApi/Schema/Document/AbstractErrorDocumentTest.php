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
    public function getErrorsWhenEmpty()
    {
        $errorDocument = $this->createErrorDocument();

        $errors = $errorDocument->getErrors();

        $this->assertEquals([], $errors);
    }

    /**
     * @test
     */
    public function getErrors()
    {
        $errorDocument = $this->createErrorDocument()
            ->addError(new Error())
            ->addError(new Error());

        $errors = $errorDocument->getErrors();

        $this->assertEquals([new Error(), new Error()], $errors);
    }

    /**
     * @test
     */
    public function getStatusCodeWithOneErrorInDocument()
    {
        $errorDocument = $this->createErrorDocument()
            ->addError(Error::create()->setStatus("404"));

        $statusCode = $errorDocument->getStatusCode();

        $this->assertEquals("404", $statusCode);
    }

    /**
     * @test
     */
    public function getStatusCodeWithErrorInParameter()
    {
        $errorDocument = $this->createErrorDocument()
            ->addError(Error::create());

        $statusCode = $errorDocument->getStatusCode(404);

        $this->assertEquals(404, $statusCode);
    }

    /**
     * @test
     */
    public function getStatusCodeWithMultipleErrorsInDocument()
    {
        $errorDocument = $this->createErrorDocument()
            ->addError(Error::create()->setStatus("418"))
            ->addError(Error::create()->setStatus("404"));

        $statusCode = $errorDocument->getStatusCode();

        $this->assertEquals(400, $statusCode);
    }

    private function createErrorDocument(): StubErrorDocument
    {
        return new StubErrorDocument();
    }
}
