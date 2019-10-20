<?php

declare(strict_types=1);

namespace WoohooLabs\Yin\Tests\JsonApi\Response;

use PHPUnit\Framework\TestCase;
use WoohooLabs\Yin\JsonApi\Exception\DefaultExceptionFactory;
use WoohooLabs\Yin\JsonApi\Response\Responder;
use WoohooLabs\Yin\JsonApi\Schema\Document\ErrorDocument;
use WoohooLabs\Yin\JsonApi\Schema\Error\Error;
use WoohooLabs\Yin\JsonApi\Schema\Link\DocumentLinks;
use WoohooLabs\Yin\JsonApi\Schema\Link\Link;
use WoohooLabs\Yin\JsonApi\Serializer\JsonSerializer;
use WoohooLabs\Yin\Tests\JsonApi\Double\StubJsonApiRequest;
use WoohooLabs\Yin\Tests\JsonApi\Double\StubResourceDocument;
use Zend\Diactoros\Response;

use function json_decode;

class ResponderTest extends TestCase
{
    /**
     * @test
     */
    public function ok(): void
    {
        $response = $this->createResponder()->ok(new StubResourceDocument(), []);

        $statusCode = $response->getStatusCode();

        $this->assertEquals(200, $statusCode);
    }

    /**
     * @test
     */
    public function okWithoutLinks(): void
    {
        $response = $this->createResponder()->ok(
            new StubResourceDocument(),
            []
        );

        $contentType = $response->getHeaderLine("content-type");

        $this->assertEquals("application/vnd.api+json", $contentType);
    }

    /**
     * @test
     */
    public function okWithLinksWithoutProfiles(): void
    {
        $response = $this->createResponder()->ok(
            new StubResourceDocument(
                null,
                [],
                DocumentLinks::createWithoutBaseUri()
            ),
            []
        );

        $contentType = $response->getHeaderLine("content-type");

        $this->assertEquals("application/vnd.api+json", $contentType);
    }

    /**
     * @test
     */
    public function nokWithProfiles(): void
    {
        $response = $this->createResponder()->ok(
            new StubResourceDocument(
                null,
                [],
                DocumentLinks::createWithoutBaseUri()
                    ->addProfile(new Link("https://example.com/profiles/last-modified"))
                    ->addProfile(new Link("https://example.com/profiles/created"))
            ),
            []
        );

        $contentType = $response->getHeaderLine("content-type");

        $this->assertEquals(
            'application/vnd.api+json;profile="https://example.com/profiles/last-modified https://example.com/profiles/created"',
            $contentType
        );
    }

    /**
     * @test
     */
    public function okWithMeta(): void
    {
        $response = $this->createResponder()->okWithMeta(new StubResourceDocument(null, ["abc" => "def"]), []);

        $statusCode = $response->getStatusCode();
        $meta = json_decode($response->getBody()->__toString(), true)["meta"];

        $this->assertEquals(200, $statusCode);
        $this->assertEquals("def", $meta["abc"]);
    }

    /**
     * @test
     */
    public function okWithRelationship(): void
    {
        $response = $this->createResponder()->okWithRelationship("", new StubResourceDocument(), []);

        $statusCode = $response->getStatusCode();

        $this->assertEquals(200, $statusCode);
    }

    /**
     * @test
     */
    public function created(): void
    {
        $response = $this->createResponder()->created(new StubResourceDocument(), []);

        $statusCode = $response->getStatusCode();
        $body = json_decode($response->getBody()->__toString(), true);

        $this->assertEquals(201, $statusCode);
        $this->assertNotEmpty($body);
    }

    /**
     * @test
     */
    public function createdWithLinks(): void
    {
        $response = $this->createResponder()->created(
            new StubResourceDocument(
                null,
                [],
                new DocumentLinks("", ["self" => new Link("https://example.com/users")])
            ),
            []
        );

        $location = $response->getHeader("location");

        $this->assertEquals(["https://example.com/users"], $location);
    }

    /**
     * @test
     */
    public function createdWithMeta(): void
    {
        $response = $this->createResponder()->createdWithMeta(
            new StubResourceDocument(
                null,
                [],
                new DocumentLinks("", ["self" => new Link("https://example.com/users")])
            ),
            []
        );

        $statusCode = $response->getStatusCode();
        $location = $response->getHeader("location");
        $body = json_decode($response->getBody()->__toString(), true);

        $this->assertEquals(201, $statusCode);
        $this->assertEquals(["https://example.com/users"], $location);
        $this->assertNotEmpty($body);
    }

    /**
     * @test
     */
    public function createdWithRelationship(): void
    {
        $response = $this->createResponder()->createdWithRelationship("", new StubResourceDocument(), []);

        $statusCode = $response->getStatusCode();

        $this->assertEquals(201, $statusCode);
    }

    /**
     * @test
     */
    public function accepted(): void
    {
        $response = $this->createResponder()->accepted();

        $statusCode = $response->getStatusCode();

        $this->assertEquals(202, $statusCode);
    }

    /**
     * @test
     */
    public function noContent(): void
    {
        $response = $this->createResponder()->noContent();

        $statusCode = $response->getStatusCode();

        $this->assertEquals(204, $statusCode);
    }

    /**
     * @test
     */
    public function forbidden(): void
    {
        $response = $this->createResponder()->forbidden(new ErrorDocument());

        $statusCode = $response->getStatusCode();

        $this->assertEquals(403, $statusCode);
    }

    /**
     * @test
     */
    public function notFound(): void
    {
        $response = $this->createResponder()->notFound(new ErrorDocument());

        $statusCode = $response->getStatusCode();

        $this->assertEquals(404, $statusCode);
    }

    /**
     * @test
     */
    public function notFoundWithProfiles(): void
    {
        $response = $this->createResponder()->notFound(
            ErrorDocument::create()
                ->setLinks(
                    DocumentLinks::createWithoutBaseUri()
                        ->addProfile(new Link("https://example.com/profiles/last-modified"))
                        ->addProfile(new Link("https://example.com/profiles/created"))
                )
        );

        $contentType = $response->getHeaderLine("content-type");

        $this->assertEquals(
            'application/vnd.api+json;profile="https://example.com/profiles/last-modified https://example.com/profiles/created"',
            $contentType
        );
    }

    /**
     * @test
     */
    public function conflict(): void
    {
        $response = $this->createResponder()->conflict(new ErrorDocument());

        $statusCode = $response->getStatusCode();

        $this->assertEquals(409, $statusCode);
    }

    /**
     * @test
     */
    public function genericSuccess(): void
    {
        $response = $this->createResponder()->genericSuccess(201);

        $statusCode = $response->getStatusCode();

        $this->assertEquals(201, $statusCode);
    }

    /**
     * @test
     */
    public function genericError(): void
    {
        $response = $this->createResponder()->genericError(
            new ErrorDocument([new Error(), new Error()]),
            418
        );

        $statusCode = $response->getStatusCode();
        $errors = json_decode($response->getBody()->__toString(), true)["errors"];

        $this->assertEquals(418, $statusCode);
        $this->assertCount(2, $errors);
    }

    private function createResponder(): Responder
    {
        return Responder::create(
            new StubJsonApiRequest(),
            new Response(),
            new DefaultExceptionFactory(),
            new JsonSerializer()
        );
    }
}
