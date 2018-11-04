<?php
declare(strict_types=1);

require_once "../vendor/autoload.php";

use WoohooLabs\Yin\Examples\Book\Action\CreateBookAction;
use WoohooLabs\Yin\Examples\Book\Action\GetAuthorsOfBookAction;
use WoohooLabs\Yin\Examples\Book\Action\GetBookAction;
use WoohooLabs\Yin\Examples\Book\Action\GetBookRelationshipsAction;
use WoohooLabs\Yin\Examples\Book\Action\GetBooksAction;
use WoohooLabs\Yin\Examples\Book\Action\UpdateBookAction;
use WoohooLabs\Yin\Examples\Book\Action\UpdateBookRelationshipAction;
use WoohooLabs\Yin\Examples\User\Action\GetUserAction;
use WoohooLabs\Yin\Examples\User\Action\GetUserRelationshipsAction;
use WoohooLabs\Yin\Examples\User\Action\GetUsersAction;
use WoohooLabs\Yin\JsonApi\Exception\DefaultExceptionFactory;
use WoohooLabs\Yin\JsonApi\JsonApi;
use WoohooLabs\Yin\JsonApi\Request\Request;
use WoohooLabs\Yin\JsonApi\Serializer\JsonDeserializer;
use Zend\Diactoros\Response;
use Zend\HttpHandlerRunner\Emitter\SapiEmitter;
use Zend\Diactoros\ServerRequestFactory;

// Defining routes
$routes = [
    "GET /books" => function (Request $request): Request {
        return $request
            ->withAttribute("action", GetBooksAction::class);
    },
    "GET /books/{id}" => function (Request $request, array $matches): Request {
        return $request
            ->withAttribute("action", GetBookAction::class)
            ->withAttribute("id", $matches[1]);
    },
    "GET /books/{id}/relationships/{rel}" => function (Request $request, array $matches): Request {
        return $request
            ->withAttribute("action", GetBookRelationshipsAction::class)
            ->withAttribute("id", $matches[1])
            ->withAttribute("rel", $matches[2]);
    },
    "GET /books/{id}/authors" => function (Request $request, array $matches): Request {
        return $request
            ->withAttribute("action", GetAuthorsOfBookAction::class)
            ->withAttribute("id", $matches[1]);
    },
    "POST /books" => function (Request $request) {
        return $request
            ->withAttribute("action", CreateBookAction::class);
    },
    "PATCH /books/{id}" => function (Request $request, array $matches): Request {
        return $request
            ->withAttribute("action", UpdateBookAction::class)
            ->withAttribute("id", $matches[1]);
    },
    "PATCH /books/{id}/relationships/{rel}" => function (Request $request, array $matches): Request {
        return $request
            ->withAttribute("action", UpdateBookRelationshipAction::class)
            ->withAttribute("id", $matches[1])
            ->withAttribute("rel", $matches[2]);
    },

    "GET /users" => function (Request $request): Request {
        return $request
            ->withAttribute("action", GetUsersAction::class);
    },
    "GET /users/{id}" => function (Request $request, array $matches): Request {
        return $request
            ->withAttribute("action", GetUserAction::class)
            ->withAttribute("id", $matches[1]);
    },
    "GET /users/{id}/relationships/{rel}" => function (Request $request, array $matches): Request {
        return $request
            ->withAttribute("action", GetUserRelationshipsAction::class)
            ->withAttribute("id", $matches[1])
            ->withAttribute("rel", $matches[2]);
    },
];

// Finding the current route
$exceptionFactory = new DefaultExceptionFactory();
$deserializer = new JsonDeserializer();
$request = new Request(ServerRequestFactory::fromGlobals(), $exceptionFactory, $deserializer);
$request = findRoute($request, $routes);

// Invoking the current action
$jsonApi = new JsonApi($request, new Response(), $exceptionFactory);
$action = $request->getAttribute("action");
$response = call_user_func(new $action(), $jsonApi);
$response = $response->withHeader("Access-Control-Allow-Origin", "*");

// Emitting the response
$emitter = new SapiEmitter();
$emitter->emit($response);

function findRoute(Request $request, array $routes): Request
{
    $path = $request->getUri()->getPath();
    $method = $request->getMethod();
    $requestLine = "$method $path";

    foreach ($routes as $pattern => $route) {
        $matches = [];
        $pattern = str_replace(
            ["{id}", "{rel}"],
            ["([A-Za-z0-9-]+)", "([A-Za-z0-9-]+)"],
            $pattern
        );
        if (preg_match("#^$pattern/{0,1}$#", $requestLine, $matches) === 1) {
            return $route($request, $matches);
        }
    }

    die("Resource not found!");
}
