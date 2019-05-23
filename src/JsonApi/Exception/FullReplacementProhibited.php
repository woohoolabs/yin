<?php
declare(strict_types=1);

namespace WoohooLabs\Yin\JsonApi\Exception;

use WoohooLabs\Yin\JsonApi\Schema\Error\Error;
use WoohooLabs\Yin\JsonApi\Schema\Error\ErrorSource;

class FullReplacementProhibited extends AbstractJsonApiException
{
    /**
     * @var string
     */
    protected $relationshipName;

    public function __construct(string $relationshipName)
    {
        parent::__construct("Full replacement of relationship '$relationshipName' is prohibited!", 403);
        $this->relationshipName = $relationshipName;
    }

    protected function getErrors(): array
    {
        return [
            Error::create()
                ->setStatus("403")
                ->setCode("FULL_REPLACEMENT_PROHIBITED")
                ->setTitle("Full replacement is prohibited")
                ->setDetail($this->getMessage())
                ->setSource(ErrorSource::fromPointer("/data/relationships/$this->relationshipName")),
        ];
    }

    public function getRelationshipName(): string
    {
        return $this->relationshipName;
    }
}
