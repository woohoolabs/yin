<?php

declare(strict_types=1);

namespace Devleand\Yin\Tests\JsonApi\Schema;

use PHPUnit\Framework\TestCase;
use Devleand\Yin\JsonApi\Schema\MetaTrait;

class MetaTraitTest extends TestCase
{
    /**
     * @test
     */
    public function getMeta(): void
    {
        $metaTrait = $this->createMetaTrait()
            ->setMeta(["abc" => "def"]);

        $meta = $metaTrait->getMeta();

        $this->assertEquals(["abc" => "def"], $meta);
    }

    /**
     * @return mixed
     */
    private function createMetaTrait()
    {
        return $this->getObjectForTrait(MetaTrait::class);
    }
}
