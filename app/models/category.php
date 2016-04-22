<?php

class Category extends BaseModel {

    public $id, $actor_id, $name, $description;

    public function __construct($attributes) {
        parent::__construct($attributes);
        $this->validators = array('validate_name', 'validate_description');
    }

    public function validate_name() {
        $errors = array();
        if ($this->name == '' || $this->name == null) {
            $errors[] = 'Kategorialla tulee olla nimi!';
        }
        if (strlen($this->name) < 3) {
            $errors[] = 'Kategorian nimen tulee olla vähintään kolme merkkiä pitkä!';
        }
        if (strlen($this->name) > 50) {
            $errors[] = 'Kategorian nimi saa olla korkeintaan 50 merkkiä pitkä!!';
        }
        return $errors;
    }

    public function validate_description() {
        $errors = array();
        if (strlen($this->description) > 400) {
            $errors[] = 'Kuvaus saa olla enintään 400 merkkiä pitkä!';
        }
        return $errors;
    }

    public static function all($actor_id) {
        $query = DB::connection()->prepare('SELECT * FROM Category WHERE actor_id = :actor_id');
        $query->execute(array('actor_id' => $actor_id));
        $rows = $query->fetchAll();
        $categories = array();

        foreach ($rows as $row) {
            $categories[] = new Category(array(
                'id' => $row['id'],
                'actor_id' => $row['actor_id'],
                'name' => $row['name'],
                'description' => $row['description']
            ));
        }
        return $categories;
    }

    public static function find($id) {
        $query = DB::connection()->prepare('SELECT * FROM Category WHERE id = :id LIMIT 1');
        $query->execute(array('id' => $id));
        $row = $query->fetch();

        if ($row) {
            $category = new Category(array(
                'id' => $row['id'],
                'actor_id' => $row['actor_id'],
                'name' => $row['name'],
                'description' => $row['description']
            ));

            return $category;
        }
        return null;
    }

    public function save() {
        $query = DB::connection()->prepare('INSERT INTO Category (name, actor_id, description) VALUES (:name, :actor_id, :description) RETURNING id');
        $query->execute(array('name' => $this->name, 'actor_id' => $this->actor_id, 'description' => $this->description));

        $row = $query->fetch();
        $this->id = $row['id'];
    }

    public function update($id, $attributes) {
        $query = DB::connection()->prepare('UPDATE Category SET (name, description) = (:name, :description) WHERE id = :id');
        $query->execute(array('id' => $id, 'name' => $attributes['name'], 'description' => $attributes['description']));
    }

    public function destroy($id) {
        $query = DB::connection()->prepare('DELETE FROM Category WHERE id = :id');
        $query->execute(array('id' => $id));
    }

    public function removeTasks($category_id) {
        $query = DB::connection()->prepare('DELETE FROM TaskCategory WHERE category_id = :category_id');
        $query->execute(array('category_id' => $category_id));
    }
    
    public function getTasks($category_id) {
        $query = DB::connection()->prepare('SELECT * FROM TaskCategory WHERE category_id = :category_id');
        $query->execute(array('category_id' => $category_id));
        $rows = $query->fetchAll();
        $tasks = array();
        
        foreach ($rows as $row) {
            $tasks[] = Task::find($row['task_id']);
        }
        
        return $tasks;
    }
}
