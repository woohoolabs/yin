<?php
namespace WoohooLabsTest\Yin\JsonApi\Schema;

use PHPUnit_Framework_TestCase;
use WoohooLabs\Yin\JsonApi\Exception\FullReplacementProhibited;

class FullReplacementProhibitedTest extends PHPUnit_Framework_TestCase
{
    public function testGetRelationshipName()
    {
        $relationshipName = "authors";

        $exception = $this->createException($relationshipName);
        $this->assertEquals($relationshipName, $exception->getRelationshipName());
    }

    private function createException($relationshipName)
    {
        return new FullReplacementProhibited($relationshipName);
    }
}
