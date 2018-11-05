<?php
declare(strict_types=1);

namespace WoohooLabs\Yin\JsonApi\Schema\Document;

use WoohooLabs\Yin\JsonApi\Schema\Error\Error;

abstract class AbstractErrorDocument implements ErrorDocumentInterface
{
    /**
     * @var Error[]
     */
    protected $errors = [];

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

    public function getResponseCode(?int $statusCode = null): int
    {
        if ($statusCode !== null) {
            return $statusCode;
        }

        if (count($this->errors) === 1) {
            return (int) $this->errors[0]->getStatus();
        }

        $responseCode = 500;
        foreach ($this->errors as $error) {
            /** @var Error $error */
            $roundedStatusCode = (int) (((int)$error->getStatus()) / 100) * 100;

            if (abs($responseCode - $roundedStatusCode) >= 100) {
                $responseCode = $roundedStatusCode;
            }
        }

        return $responseCode;
    }
}
