<?php

class HelloWorldController extends BaseController {

    public static function index() {
        // make-metodi renderöi app/views-kansiossa sijaitsevia tiedostoja
        echo 'Tämä on etusivu!';
    }

    public static function sandbox() {
        $doom = new Task(array(
            'name' => 'd',
            'priority' => '4',
            'deadline' => '04.04.2014',
        ));
        $errors = $doom->errors();

        Kint::dump($errors);
    }

    public static function task_list() {
        View::make('suunnitelmat/task_list.html');
    }

    public static function task_show() {
        View::make('suunnitelmat/task_show.html');
    }

    public static function task_edit() {
        View::make('suunnitelmat/task_edit.html');
    }

    public static function login() {
        View::make('suunnitelmat/login.html');
    }

}
