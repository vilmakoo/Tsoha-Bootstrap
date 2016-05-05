<?php

class User extends BaseModel {

    public $id, $username, $password;

    public function __construct($attributes) {
        parent::__construct($attributes);
        $this->validators = array('validate_username', 'validate_password');
    }
    
    public function validate_username() {
        $errors = array();
        if ($this->username == '' || $this->username == null) {
            $errors[] = 'Anna käyttäjänimi!';
        }
        if (strlen($this->username) < 4) {
            $errors[] = 'Käyttäjänimen tulee olla vähintään neljä merkkiä pitkä!';
        }
        if (strlen($this->username) > 20) {
            $errors[] = 'Käyttäjänimi saa olla enintään 20 merkkiä pitkä!';
        }
        if (!User::usernameIsUnique($this->username)) {
            $errors[] = 'Käyttäjänimen tulee olla uniikki!';
        }
        return $errors;
    }
    
    public function validate_password() {
        $errors = array();
        if ($this->password == '' || $this->password == null) {
            $errors[] = 'Anna salasana!';
        }
        if (strlen($this->password) < 8) {
            $errors[] = 'Salasanan tulee olla vähintään 8 merkkiä pitkä!';
        }
        if (strlen($this->password) > 20) {
            $errors[] = 'Salasana saa olla enintään 20 merkkiä pitkä!!';
        }
        
        return $errors;
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
    
    public static function usernameIsUnique($username) {
        $query = DB::connection()->prepare('SELECT * FROM Actor WHERE username = :username');
        $query->execute(array('username' => $username));
        $row = $query->fetch();
        
        if ($row) {
            return false;
        } else {
            return true;
        }
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

    public function save() {
        $query = DB::connection()->prepare('INSERT INTO Actor (username, password) VALUES (:username, :password) RETURNING id');
        $query->execute(array('username' => $this->username, 'password' => $this->password));

        $row = $query->fetch();
        $this->id = $row['id'];
    }
}
