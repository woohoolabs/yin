<?php
declare(strict_types=1);

namespace WoohooLabs\Yin\Tests\JsonApi\Schema\Document;

use PHPUnit\Framework\TestCase;
use WoohooLabs\Yin\JsonApi\Schema\Document\ErrorDocument;
use WoohooLabs\Yin\JsonApi\Schema\JsonApiObject;
use WoohooLabs\Yin\JsonApi\Schema\Link\DocumentLinks;

class ErrorDocumentTest extends TestCase
{
    /**
     * @test
     */
    public function getJsonApi()
    {
        $errorDocument = $this->createErrorDocument();

        $errorDocument->setJsonApi(new JsonApiObject("1.0"));

        $this->assertEquals(new JsonApiObject("1.0"), $errorDocument->getJsonApi());
    }

    /**
     * @test
     */
    public function getMeta()
    {
        $errorDocument = $this->createErrorDocument();

        $errorDocument->setMeta(["abc" => "def"]);

        $this->assertEquals(["abc" => "def"], $errorDocument->getMeta());
    }

    /**
     * @test
     */
    public function getLinks()
    {
        $errorDocument = $this->createErrorDocument();

        $errorDocument->setLinks(new DocumentLinks("https://example.com"));

        $this->assertEquals(new DocumentLinks("https://example.com"), $errorDocument->getLinks());
    }

    private function createErrorDocument(): ErrorDocument
    {
        return new ErrorDocument();
    }
}
