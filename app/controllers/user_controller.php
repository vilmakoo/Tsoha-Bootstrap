<?php

class UserController extends BaseController {

    public static function login() {
        View::make('user/login.html');
    }

    public static function handle_login() {
        $params = $_POST;

        $user = User::authenticate($params['username'], $params['password']);

        if (!$user) {
            View::make('user/login.html', array('error' => 'Väärä käyttäjätunnus tai salasana!', 'username' => $params['username']));
        } else {
            $_SESSION['user'] = $user->id;

            Redirect::to('/', array('message' => 'Tervetuloa takaisin ' . $user->username . '!'));
        }
    }

    public static function logout() {
        $_SESSION['user'] = null;
        Redirect::to('/login', array('message' => 'Olet kirjautunut ulos!'));
    }

    public static function signin() {
        View::make('user/signin.html');
    }
    
    public static function handle_signin() {
        $params = $_POST;
        
        $attributes = array(
            'username' => $params['username'],
            'password' => $params['password1']
        );

        $user = new User($attributes);

        $errors = $user->errors();
        
        if ($params['password1'] != $params['password2']) {
            $errors[] = 'Toistettu salasana ei vastannut ensimmäistä. Anna se uudelleen.';
        }

        if (count($errors) == 0) {
            $user->save();
            Redirect::to('/login', array('message' => 'Käyttäjä on luotu! Jatka kirjautumalla sisään.'));
        } else {
            View::make('user/signin.html', array('errors' => $errors, 'username' => $attributes['username']));
        }
    }
}
