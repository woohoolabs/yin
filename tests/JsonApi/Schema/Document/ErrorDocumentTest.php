<?php
declare(strict_types=1);

namespace WoohooLabs\Yin\Tests\JsonApi\Schema\Document;

use PHPUnit\Framework\TestCase;
use WoohooLabs\Yin\JsonApi\Schema\Document\ErrorDocument;
use WoohooLabs\Yin\JsonApi\Schema\JsonApiObject;
use WoohooLabs\Yin\JsonApi\Schema\Link\ErrorLinks;

class ErrorDocumentTest extends TestCase
{
    /**
     * @test
     */
    public function getJsonApi()
    {
        $jsonApi = new JsonApiObject("1.0");

        $errorDocument = $this->createErrorDocument()->setJsonApi($jsonApi);
        $this->assertEquals($jsonApi, $errorDocument->getJsonApi());
    }

    /**
     * @test
     */
    public function getMeta()
    {
        $meta = ["abc" => "def"];

        $errorDocument = $this->createErrorDocument()->setMeta($meta);
        $this->assertEquals($meta, $errorDocument->getMeta());
    }

    /**
     * @test
     */
    public function getLinks()
    {
        $links = new ErrorLinks("https://example.com");

        $errorDocument = $this->createErrorDocument()->setLinks($links);
        $this->assertEquals($links, $errorDocument->getLinks());
    }

    private function createErrorDocument(): ErrorDocument
    {
        return new ErrorDocument();
    }
}
