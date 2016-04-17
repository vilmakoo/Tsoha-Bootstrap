<?php

class Task extends BaseModel {

    public $id, $actor_id, $name, $description, $priority, $done, $added, $deadline;

    public function __construct($attributes) {
        parent::__construct($attributes);
        $this->validators = array('validate_name', 'validate_priority', 'validate_deadline');
    }

    public function validate_name() {
        $errors = array();
        if ($this->name == '' || $this->name == null) {
            $errors[] = 'Tehtävällä tulee olla nimi!';
        }
        if (strlen($this->name) < 3) {
            $errors[] = 'Tehtävän nimen tulee olla vähintään kolme merkkiä pitkä!';
        }
        return $errors;
    }

    public function validate_priority() {
        $errors = array();
        if ($this->priority == null) {
            $errors[] = 'Anna tehtävälle tärkeysaste!';
        }
        return $errors;
    }

    public function validate_deadline() {
        $errors = array();
        if ($this->deadline == null) {
            $errors[] = 'Anna tehtävän deadline!';
        }
        return $errors;
    }

    public static function all($user_id) {
        $query = DB::connection()->prepare('SELECT * FROM Task WHERE actor_id = :user_id');
        $query->execute();
        $rows = $query->fetchAll();
        $tasks = array();

        foreach ($rows as $row) {
            $tasks[] = new Task(array(
                'id' => $row['id'],
                'actor_id' => $row['actor_id'],
                'name' => $row['name'],
                'description' => $row['description'],
                'priority' => $row['priority'],
                'done' => $row['done'],
                'added' => $row['added'],
                'deadline' => $row['deadline']
            ));
        }

        return $tasks;
    }

    public static function find($id) {
        $query = DB::connection()->prepare('SELECT * FROM Task WHERE id = :id LIMIT 1');
        $query->execute(array('id' => $id));
        $row = $query->fetch();

        if ($row) {
            $task = new Task(array(
                'id' => $row['id'],
                'actor_id' => $row['actor_id'],
                'name' => $row['name'],
                'description' => $row['description'],
                'priority' => $row['priority'],
                'done' => $row['done'],
                'added' => $row['added'],
                'deadline' => $row['deadline']
            ));

            return $task;
        }

        return null;
    }

    public function save() {
        $query = DB::connection()->prepare('INSERT INTO Task (name, actor_id, description, done, priority, added, deadline) VALUES (:name, :actor_id, :description, :done, :priority, :added, :deadline) RETURNING id');
        $query->execute(array('name' => $this->name, 'actor_id' => $this->actor_id, 'description' => $this->description, 'done' => $this->done, 'priority' => $this->priority, 'added' => $this->added, 'deadline' => $this->deadline));

        $row = $query->fetch();
        $this->id = $row['id'];
    }

    public function update($id) {
        $query = DB::connection()->prepare('UPDATE Task (name, description, done, priority, deadline) VALUES (:name, :description, :done, :priority, :deadline) WHERE id = :id RETURNING id');
        $query->execute(array('name' => $this->name, 'description' => $this->description, 'done' => $this->done, 'priority' => $this->priority, 'deadline' => $this->deadline));
        
        $row = $query->fetch();
        $this->id = $row['id'];
    }
    
    public function destroy($id) {
        $query = DB::connection()->prepare('DELETE FROM Task WHERE id = :id');
    }
}
