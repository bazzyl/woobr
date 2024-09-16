<?php

use App\Controller\LeapYearController;
use Symfony\Component\Routing\Route;
use Symfony\Component\Routing\RouteCollection;

$routes = new RouteCollection();
$routes->add('home', new Route('/'));
$routes->add('hello', new Route('/hello/{name}'));
$routes->add('bye', new Route('/bye'));

$routes->add('leap_year', new Route('/is_leap_year/{year}', [
    'year' => null,
    '_controller' => [new LeapYearController(), 'index'],
]));

return $routes;