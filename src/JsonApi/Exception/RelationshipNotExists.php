<?php
namespace WoohooLabs\Yin\JsonApi\Exception;

use WoohooLabs\Yin\JsonApi\Schema\Error;

class RelationshipNotExists extends JsonApiException
{
    /**
     * @var string
     */
    protected $relationship;

    public function __construct($relationship)
    {
        parent::__construct("The requested relationship '" . $relationship . "' does not exist!");
        $this->relationship = $relationship;
    }

    /**
     * @inheritDoc
     */
    protected function getErrors()
    {
        return [
            Error::create()
                ->setStatus(400)
                ->setCode("RELATIONSHIP_NOT_EXISTS")
                ->setTitle("The requested relationship does not exist!")
                ->setDetail($this->getMessage())
        ];
    }
}
