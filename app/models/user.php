<?php

class User extends BaseModel {

    public $id, $username, $password;

    public function __construct($attributes) {
        parent::__construct($attributes);
    }

    public static function all() {
        
    }

    public static function find($id) {
        $query = DB::connection()->prepare('SELECT * FROM Actor WHERE id = :id LIMIT 1');
        $query->execute(array('id' => $id));
        $row = $query->fetch();

        if ($row) {
            $user = new User(array(
                'id' => $row['id'],
                'username' => $row['username'],
                'password' => $row['password']
            ));

            return $user;
        }

        return null;
    }

    public function authenticate($username, $password) {
        $query = DB::connection()->prepare('SELECT * FROM Actor WHERE username = :username AND password = :password LIMIT 1');
        $query->execute(array('username' => $username, 'password' => $password));
        $row = $query->fetch();
        if ($row) {
            $user = new User(array(
                'id' => $row['id'],
                'username' => $row['username'],
                'password' => $row['password']
            ));

            return $user;
        } else {
            return null;
        }
    }

}
