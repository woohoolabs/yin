<?php

declare(strict_types=1);

namespace Devleand\Yin\Tests\JsonApi\Negotiation;

use Laminas\Diactoros\Response;
use PHPUnit\Framework\TestCase;
use Devleand\Yin\JsonApi\Exception\DefaultExceptionFactory;
use Devleand\Yin\JsonApi\Exception\ResponseBodyInvalidJson;
use Devleand\Yin\JsonApi\Exception\ResponseBodyInvalidJsonApi;
use Devleand\Yin\JsonApi\Negotiation\ResponseValidator;
use Devleand\Yin\JsonApi\Serializer\JsonSerializer;

class ResponseValidatorTest extends TestCase
{
    /**
     * @test
     */
    public function validateJsonBodySuccessfully(): void
    {
        $response = new Response();
        $response->getBody()->write('{"data": {"type":"abc", "id":"cde"}}');
        $validator = new ResponseValidator(new JsonSerializer(), new DefaultExceptionFactory());

        $validator->validateJsonBody($response);

        $this->addToAssertionCount(1);
    }

    /**
     * @test
     */
    public function validateJsonBodyUnsuccessfully(): void
    {
        $response = new Response();
        $response->getBody()->write('{"type');
        $validator = new ResponseValidator(new JsonSerializer(), new DefaultExceptionFactory());

        $this->expectException(ResponseBodyInvalidJson::class);

        $validator->validateJsonBody($response);
    }

    /**
     * @test
     */
    public function validateJsonApiBodySuccessfully(): void
    {
        $response = new Response();
        $response->getBody()->write(
            <<<EOF
{
  "data": {
    "type": "articles",
    "id": "1",
    "attributes": {
      "title": "JSON API paints my bikeshed!"
    }
  }
}
EOF
        );
        $validator = new ResponseValidator(new JsonSerializer(), new DefaultExceptionFactory());

        $validator->validateJsonApiBody($response);

        $this->addToAssertionCount(1);
    }

    /**
     * @test
     */
    public function validateJsonApiSuccessfullyWhenEmptyBody(): void
    {
        $response = new Response();
        $validator = new ResponseValidator(new JsonSerializer(), new DefaultExceptionFactory());

        $validator->validateJsonApiBody($response);

        $this->addToAssertionCount(1);
    }

    /**
     * @test
     */
    public function validateJsonApiBodyUnsuccessfully(): void
    {
        $response = new Response();
        $response->getBody()->write('{"type":"abc", "id":"cde"}');

        $validator = new ResponseValidator(new JsonSerializer(), new DefaultExceptionFactory());

        $this->expectException(ResponseBodyInvalidJsonApi::class);
        $validator->validateJsonApiBody($response);
    }
}
