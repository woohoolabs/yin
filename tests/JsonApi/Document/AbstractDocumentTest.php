<?php
namespace WoohooLabsTest\Yin\JsonApi\Transformer;

use PHPUnit_Framework_TestCase;
use WoohooLabs\Yin\JsonApi\Schema\JsonApi;
use WoohooLabs\Yin\JsonApi\Schema\Links;
use WoohooLabsTest\Yin\JsonApi\Utils\StubDocument;

class AbstractDocumentTest extends PHPUnit_Framework_TestCase
{
    /**
     * @test
     */
    public function getExtensions()
    {
        $document = $this->createDocument();
        $this->assertEquals([], $document->getSupportedExtensions());
    }

    /**
     * @test
     */
    public function getRequiredExtensions()
    {
        $document = $this->createDocument();
        $this->assertEquals([], $document->getSupportedExtensions());
    }

    /**
     * @param \WoohooLabs\Yin\JsonApi\Schema\JsonApi|null $jsonApi
     * @param array $meta
     * @param \WoohooLabs\Yin\JsonApi\Schema\Links|null $links
     * @return \WoohooLabs\Yin\JsonApi\Document\AbstractDocument
     */
    private function createDocument(JsonApi $jsonApi = null, array $meta = [], Links $links = null)
    {
        return new StubDocument($jsonApi, $meta, $links);
    }
}
