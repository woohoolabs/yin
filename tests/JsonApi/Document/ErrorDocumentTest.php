<?php
namespace WoohooLabsTest\Yin\JsonApi\Document;

use PHPUnit_Framework_TestCase;
use WoohooLabs\Yin\JsonApi\Schema\JsonApi;
use WoohooLabs\Yin\JsonApi\Schema\Links;
use WoohooLabs\Yin\JsonApi\Document\ErrorDocument;

class ErrorDocumentTest extends PHPUnit_Framework_TestCase
{
    public function testGetJsonApi()
    {
        $jsonApi = new JsonApi("1.0");

        $errorDocument = $this->createErrorDocument()->setJsonApi($jsonApi);
        $this->assertEquals($jsonApi, $errorDocument->getJsonApi());
    }

    public function testGetMeta()
    {
        $meta = ["abc" => "def"];

        $errorDocument = $this->createErrorDocument()->setMeta($meta);
        $this->assertEquals($meta, $errorDocument->getMeta());
    }

    public function testGetLinks()
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
