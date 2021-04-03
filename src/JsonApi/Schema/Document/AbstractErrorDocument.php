<?php

declare(strict_types=1);

namespace WoohooLabs\Yin\JsonApi\Schema\Document;

use WoohooLabs\Yin\JsonApi\Schema\Error\Error;

use function abs;
use function count;

abstract class AbstractErrorDocument implements ErrorDocumentInterface
{
    /** @var Error[] */
    protected array $errors = [];

    /**
     * @return Error[]
     */
    public function getErrors(): array
    {
        return $this->errors;
    }

    /**
     * Includes a new error in the error document.
     *
     * @return $this
     */
    public function addError(Error $error)
    {
        $this->errors[] = $error;

        return $this;
    }

    public function getStatusCode(?int $statusCode = null): int
    {
        if ($statusCode !== null) {
            return $statusCode;
        }

        if (count($this->errors) === 1) {
            return (int) $this->errors[0]->getStatus();
        }

        $result = 500;
        foreach ($this->errors as $error) {
            $roundedStatusCode = (int) ((int) $error->getStatus() / 100) * 100;

            if (abs($result - $roundedStatusCode) >= 100) {
                $result = $roundedStatusCode;
            }
        }

        return $result;
    }
}
