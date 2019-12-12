<?php

date_default_timezone_set("UTC");
error_reporting(E_ALL);
ini_set("display_errors", 1);

require __DIR__ . "/vendor/autoload.php";

$app = new \Slim\App([
    "settings" => [
        "displayErrorDetails" => true,
        "addContentLengthHeader" => false,
    ]
]);

$app->get("/", function ($request, $response, $arguments) {
    $path = $request->getUri()->getPath();
    $response->getBody()->write($path);
    return $response;
});

$app->get("/foo", function ($request, $response, $arguments) {
    $path = $request->getUri()->getPath();
    $response->getBody()->write($path);
    return $response;
});

$app->get("/bar", function ($request, $response, $arguments) {
    $path = $request->getUri()->getPath();
    $response->getBody()->write($path);
    return $response;
});

$app->run();
