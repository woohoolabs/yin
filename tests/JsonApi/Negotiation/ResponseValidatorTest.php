<?php
declare(strict_types=1);

namespace WoohooLabs\Yin\Tests\JsonApi\Negotiation;

use PHPUnit\Framework\TestCase;
use WoohooLabs\Yin\JsonApi\Exception\DefaultExceptionFactory;
use WoohooLabs\Yin\JsonApi\Exception\ResponseBodyInvalidJson;
use WoohooLabs\Yin\JsonApi\Exception\ResponseBodyInvalidJsonApi;
use WoohooLabs\Yin\JsonApi\Negotiation\ResponseValidator;
use WoohooLabs\Yin\JsonApi\Serializer\JsonSerializer;
use Zend\Diactoros\Response;

class ResponseValidatorTest extends TestCase
{
    /**
     * @test
     */
    public function lintBodySuccessfully()
    {
        $response = new Response();
        $response->getBody()->write('{"data": {"type":"abc", "id":"cde"}}');

        $validator = new ResponseValidator(new JsonSerializer(), new DefaultExceptionFactory());

        $validator->lintBody($response);
        $this->assertTrue(true);
    }

    /**
     * @test
     */
    public function lintBodyUnsuccessfully()
    {
        $response = new Response();
        $response->getBody()->write('{"type');

        $validator = new ResponseValidator(new JsonSerializer(), new DefaultExceptionFactory());

        $this->expectException(ResponseBodyInvalidJson::class);
        $validator->lintBody($response);
    }

    /**
     * @test
     */
    public function validateBodySuccessfully()
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

        $validator->validateBody($response);
        $this->assertTrue(true);
    }

    /**
     * @test
     */
    public function validateEmptyBodySuccessfully()
    {
        $response = new Response();

        $validator = new ResponseValidator(new JsonSerializer(), new DefaultExceptionFactory());

        $validator->validateBody($response);
        $this->assertTrue(true);
    }

    /**
     * @test
     */
    public function validateBodyUnsuccessfully()
    {
        $response = new Response();
        $response->getBody()->write('{"type":"abc", "id":"cde"}');

        $validator = new ResponseValidator(new JsonSerializer(), new DefaultExceptionFactory());

        $this->expectException(ResponseBodyInvalidJsonApi::class);
        $validator->validateBody($response);
    }
}
