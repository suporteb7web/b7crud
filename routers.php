<?php
global $routes;
$routes = array();

$routes['/u/login'] = '/users/login';
$routes['/u/new'] = '/users/new_record';
$routes['/u'] = '/users/view';

$routes['/{s}/{id}'] = '/structure/item/:s/:id';
$routes['/{s}'] = '/structure/list/:s';