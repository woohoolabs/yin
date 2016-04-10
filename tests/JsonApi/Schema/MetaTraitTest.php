<?php
namespace WoohooLabsTest\Yin\JsonApi\Schema;

use PHPUnit_Framework_TestCase;
use WoohooLabs\Yin\JsonApi\Schema\MetaTrait;

class MetaTraitTest extends PHPUnit_Framework_TestCase
{
    /**
     * @test
     */
    public function getMeta()
    {
        $meta = ["abc" => "def"];

        $metaTrait = $this->createMetaTrait()->setMeta($meta);
        $this->assertEquals($meta, $metaTrait->getMeta());
    }

    /**
     * @return \WoohooLabs\Yin\JsonApi\Schema\MetaTrait
     */
    private function createMetaTrait()
    {
        return $this->getObjectForTrait(MetaTrait::class);
    }
}
