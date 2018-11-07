<?php
declare(strict_types=1);

namespace WoohooLabs\Yin\Tests\JsonApi\Exception;

use PHPUnit\Framework\TestCase;
use WoohooLabs\Yin\JsonApi\Exception\ResourceIdInvalid;

class ResourceIdInvalidTest extends TestCase
{
    /**
     * @test
     */
    public function getError()
    {
        $exception = $this->createException("");

        $errors = $exception->getErrorDocument()->getErrors();

        $this->assertCount(1, $errors);
        $this->assertEquals("400", $errors[0]->getStatus());
    }

    /**
     * @test
     */
    public function getId()
    {
        $exception = $this->createException(1);

        $id = $exception->getId();

        $this->assertEquals(1, $id);
    }

    private function createException($id): ResourceIdInvalid
    {
        return new ResourceIdInvalid($id);
    }
}
