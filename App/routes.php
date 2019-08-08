<?php

require_once $_SERVER['DOCUMENT_ROOT'] . '/App/Controller/UserController.php';
require_once $_SERVER['DOCUMENT_ROOT'] . '/App/Controller/CustomerController.php';
require_once $_SERVER['DOCUMENT_ROOT'] . '/App/Controller/HomeController.php';

$router = new Router(new Request);

$router->get('/', function ($request) {
    // Xử lý middleware
    HomeController::index($request);
});

$router->get('/home', function ($request) {
    // Xử lý middleware
    HomeController::home($request);
});

$router->post('/user/login', function ($request) {
    // Xử lý middleware
    UserController::login($request);
});

$router->get('/user/add', function ($request) {
    // Xử lý middleware
    UserController::add($request);
});

$router->post('/customer/get_all', function ($request) {
    // Xử lý middleware
    CustomerController::getAlls($request);
});

$router->post('/customer/get', function ($request) {
    // Xử lý middleware
    CustomerController::getCustomerPagination($request);
});

$router->post('/customer/find', function ($request) {
    // Xử lý middleware
    CustomerController::findCustomer($request);
});

$router->post('/customer/add', function ($request) {
    // Xử lý middleware
    CustomerController::add($request);
});

$router->post('/customer/edit', function ($request) {
    // Xử lý middleware
    CustomerController::edit($request);
});

$router->post('/customer/remove', function ($request) {
    // Xử lý middleware
    CustomerController::remove($request);
});