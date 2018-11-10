<?php
declare(strict_types=1);

namespace WoohooLabs\Yin\Tests\JsonApi\Exception;

use PHPUnit\Framework\TestCase;
use WoohooLabs\Yin\JsonApi\Exception\RequestBodyInvalidJson;
use WoohooLabs\Yin\Tests\JsonApi\Double\StubRequest;

class RequestBodyInvalidJsonTest extends TestCase
{
    /**
     * @test
     */
    public function getErrors()
    {
        $exception = $this->createException();

        $errors = $exception->getErrorDocument()->getErrors();

        $this->assertCount(1, $errors);
        $this->assertEquals("400", $errors[0]->getStatus());
    }

    /**
     * @test
     */
    public function getErrorDocumentWhenNotIncludeOriginal()
    {
        $exception = $this->createException("abc", "", false);

        $meta = $exception->getErrorDocument()->getMeta();

        $this->assertEmpty($meta);
    }

    /**
     * @test
     */
    public function getErrorDocumentWhenIncludeOriginal()
    {
        $exception = $this->createException("abc", "", true);

        $meta = $exception->getErrorDocument()->getMeta();

        $this->assertEquals(["original" => "abc"], $meta);
    }

    /**
     * @test
     */
    public function getLintMessage()
    {
        $exception = $this->createException("", "abc");

        $lintMessage = $exception->getLintMessage();

        $this->assertEquals("abc", $lintMessage);
    }

    private function createException(string $body = "", string $lintMessage = "", bool $includeOriginal = false): RequestBodyInvalidJson
    {
        $request = StubRequest::create();
        $request->getBody()->write($body);

        return new RequestBodyInvalidJson($request, $lintMessage, $includeOriginal);
    }
}
