<?php

$routes->get('/', function() {
    HelloWorldController::index();
});

$routes->get('/hiekkalaatikko', function() {
    HelloWorldController::sandbox();
});

$routes->get('/task', function() {
    HelloWorldController::task_list();
});
$routes->get('/task/1', function() {
    HelloWorldController::task_show();
});
$routes->get('/task/1/edit', function() {
    HelloWorldController::task_edit();
});

$routes->get('/login', function() {
    HelloWorldController::login();
});
