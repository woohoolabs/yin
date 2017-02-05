<?php
declare(strict_types=1);

namespace WoohooLabs\Yin\JsonApi\Exception;

use WoohooLabs\Yin\JsonApi\Schema\Error;

class RelationshipNotExists extends JsonApiException
{
    /**
     * @var string
     */
    protected $relationship;

    public function __construct(string $relationship)
    {
        parent::__construct("The requested relationship '" . $relationship . "' does not exist!");
        $this->relationship = $relationship;
    }

    protected function getErrors(): array
    {
        return [
            Error::create()
                ->setStatus("404")
                ->setCode("RELATIONSHIP_NOT_EXISTS")
                ->setTitle("The requested relationship does not exist!")
                ->setDetail($this->getMessage())
        ];
    }
}
