<?php

$routes->get('/', function() {
    TaskController::index();
});

$routes->get('/hiekkalaatikko', function() {
    HelloWorldController::sandbox();
});

$routes->get('/task', function() {
    TaskController::index();
});

$routes->post('/task', function(){
  TaskController::store();
});

$routes->get('/task/new', function(){
  TaskController::create();
});

$routes->get('/task/:id', function($id) {
    TaskController::show($id);
});

$routes->get('/task/list', function() {
    HelloWorldController::task_list();
});

$routes->get('/task/show', function() {
    HelloWorldController::task_show();
});
$routes->get('/task/edit', function() {
    HelloWorldController::task_edit();
});

$routes->get('/login', function() {
    HelloWorldController::login();
});
