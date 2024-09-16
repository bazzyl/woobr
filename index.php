<?php

declare(strict_types = 1);

# file: /index.php

# Pull in Composer autoloading functionality
require_once __DIR__.'/vendor/autoload.php';
$routes = require_once __DIR__.'/routes/web.php';

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Matcher\UrlMatcher;
use Symfony\Component\Routing\RequestContext;
use Twig\Environment;
use Twig\Loader\FilesystemLoader;

$request = Request::createFromGlobals();
$context = new RequestContext();
$context->fromRequest($request);

// Create a UrlMatcher instance
$matcher = new UrlMatcher($routes, $context);

// Match the current request
$attributes = $matcher->match($request->getPathInfo());

// Handle the request if a controller was found
if (isset($attributes['_controller'])) {
    $controllerInfo = $attributes['_controller'];

    // Check if the controller is a string or an array
    if (is_array($controllerInfo) && count($controllerInfo) === 2) {
        $controller = $controllerInfo[0];
        $method = $controllerInfo[1];
    } elseif (is_string($controllerInfo)) {
        // If it's a string (for example, 'App\Controller\SomeController::method')
        list($controllerClass, $method) = explode('::', $controllerInfo);
        $controller = new $controllerClass();
    } else {
        throw new Exception('Invalid controller information');
    }

    // Remove attributes that should not be passed to the controller
    unset($attributes['_controller'], $attributes['_route']);
    unset($attributes['controller'], $attributes['route']);

    // Call the controller and send the response
    $response = call_user_func_array([$controller, $method], $attributes);
    $response->send();
    exit;
}

// Handle the request if no controller was found
$loader = new FilesystemLoader('templates');
$twig = new Environment($loader, [
    //'cache' => '/path/to/compilation_cache',
]);

$template = $twig->load('index.html.twig');

$response = new Response();
$response->setContent($template->render(['name' => $attributes['name'] ?? 'World']));

// Send the response
$response->send();
