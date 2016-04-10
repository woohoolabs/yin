<?php
namespace WoohooLabsTest\Yin\JsonApi\Document;

use PHPUnit_Framework_TestCase;
use WoohooLabs\Yin\JsonApi\Schema\JsonApi;
use WoohooLabs\Yin\JsonApi\Schema\Links;
use WoohooLabs\Yin\JsonApi\Document\ErrorDocument;

class ErrorDocumentTest extends PHPUnit_Framework_TestCase
{
    /**
     * @test
     */
    public function getJsonApi()
    {
        $jsonApi = new JsonApi("1.0");

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
        $links = new Links("http://example.com");

        $errorDocument = $this->createErrorDocument()->setLinks($links);
        $this->assertEquals($links, $errorDocument->getLinks());
    }

    private function createErrorDocument()
    {
        return new ErrorDocument();
    }
}
