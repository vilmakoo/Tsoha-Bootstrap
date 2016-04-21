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

$routes->get('/task/:id/edit', function($id){
  TaskController::edit($id);
});

$routes->post('/task/:id/edit', function($id){
  TaskController::update($id);
});

$routes->post('/task/:id/destroy', function($id){
  TaskController::destroy($id);
});

$routes->get('/login', function(){
  UserController::login();
});

$routes->post('/login', function(){
  UserController::handle_login();
});

$routes->post('/logout', function(){
  UserController::logout();
});

$routes->get('/task', function() {
    HelloWorldController::task_list();
});

$routes->get('/task/42', function() {
    HelloWorldController::task_show();
});
$routes->get('/task/42/edit', function() {
    HelloWorldController::task_edit();
});

$routes->get('/login', function() {
    HelloWorldController::login();
});
