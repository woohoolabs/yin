<?php
include_once "../vendor/autoload.php";

use WoohooLabs\Yin\Examples\Book\Action\GetBookAction;
use WoohooLabs\Yin\Examples\Book\Action\GetBookRelationshipsAction;
use WoohooLabs\Yin\Examples\Book\Action\CreateBookAction;
use WoohooLabs\Yin\Examples\Book\Action\UpdateBookAction;
use WoohooLabs\Yin\Examples\User\Action\GetUsersAction;
use WoohooLabs\Yin\Examples\User\Action\GetUserAction;
use WoohooLabs\Yin\Examples\User\Action\GetUserRelationshipsAction;
use WoohooLabs\Yin\JsonApi\JsonApi;
use WoohooLabs\Yin\JsonApi\Request\Request;
use Zend\Diactoros\ServerRequestFactory;
use Zend\Diactoros\Response;

// Initializing JsonApi
$request = new Request(ServerRequestFactory::fromGlobals());
$jsonApi = new JsonApi($request, new Response());

// Defining routes
$routes = [
    ["method"=> "GET", "example" => "book", "action" => GetBookAction::class],
    ["method"=> "GET", "example" => "book-rel", "action" => GetBookRelationshipsAction::class],
    ["method"=> "POST", "example" => "book", "action" => CreateBookAction::class],
    ["method"=> "PATCH", "example" => "book", "action" => UpdateBookAction::class],

    ["method"=> "GET", "example" => "users", "action" => GetUsersAction::class],
    ["method"=> "GET", "example" => "user", "action" => GetUserAction::class],
    ["method"=> "GET", "example" => "user-rel", "action" => GetUserRelationshipsAction::class],
];

// Routing
$method = $request->getMethod();
$queryParams = $request->getQueryParams();
$example = "";
$action = "";

if (isset($queryParams["example"])) {
    $example = $queryParams["example"];
} else {
    die("You must provide the 'example' query parameter!");
}

foreach ($routes as $route) {
    if ($method === $route["method"] && $example === $route["example"]) {
        $action = $route["action"];
        break;
    }
}

if ($action === "") {
    die("Route not found!");
}

// Invoking the current action
$response = call_user_func(new $action(), $jsonApi);

// Emitting the response
$emitter = new \Zend\Diactoros\Response\SapiEmitter();
$emitter->emit($response);
