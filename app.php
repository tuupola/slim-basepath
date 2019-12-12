<?php

date_default_timezone_set("UTC");
error_reporting(E_ALL);
ini_set("display_errors", 1);

require __DIR__ . "/vendor/autoload.php";

use Slim\Factory\AppFactory;

$app = AppFactory::create();
$app->setBasePath("/test");

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
