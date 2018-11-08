<?php
declare(strict_types=1);

namespace WoohooLabs\Yin\Tests\JsonApi\Schema;

use PHPUnit\Framework\TestCase;
use WoohooLabs\Yin\JsonApi\Schema\MetaTrait;

class MetaTraitTest extends TestCase
{
    /**
     * @test
     */
    public function getMeta()
    {
        $metaTrait = $this->createMetaTrait()
            ->setMeta(["abc" => "def"]);

        $meta = $metaTrait->getMeta();

        $this->assertEquals(["abc" => "def"], $meta);
    }

    /**
     * @return object|MetaTrait
     */
    private function createMetaTrait()
    {
        return $this->getObjectForTrait(MetaTrait::class);
    }
}
