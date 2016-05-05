<?php

class Task extends BaseModel {

    public $id, $actor_id, $name, $description, $priority, $done, $added, $deadline;

    public function __construct($attributes) {
        parent::__construct($attributes);
        $this->validators = array('validate_name', 'validate_priority', 'validate_deadline', 'validate_description');
    }

    public function validate_name() {
        $errors = array();
        if ($this->name == '' || $this->name == null) {
            $errors[] = 'Tehtävällä tulee olla nimi!';
        }
        if (strlen($this->name) < 3) {
            $errors[] = 'Tehtävän nimen tulee olla vähintään kolme merkkiä pitkä!';
        }
        if (strlen($this->name) > 50) {
            $errors[] = 'Nimi saa olla enintään 50 merkkiä pitkä!!';
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

    public function validate_description() {
        $errors = array();
        if (strlen($this->description) > 400) {
            $errors[] = 'Kuvaus saa olla enintään 400 merkkiä pitkä!';
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

    public static function all($options) {
        $query_string = 'SELECT * FROM Task WHERE actor_id = :actor_id';
        
        if (isset($options['showUndoneTasks'])) {
            $query_string .= ' AND done = false';
        }
        if ($options['sortByPriority']) {
            $query_string .= ' ORDER BY priority DESC';
        }

        $query = DB::connection()->prepare($query_string);
        $query->execute(array('actor_id' => $options['actor_id']));

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
        $query = DB::connection()->prepare('INSERT INTO Task (name, actor_id, description, priority, added, deadline) VALUES (:name, :actor_id, :description, :priority, :added, :deadline) RETURNING id');
        $query->execute(array('name' => $this->name, 'actor_id' => $this->actor_id, 'description' => $this->description, 'priority' => $this->priority, 'added' => $this->added, 'deadline' => $this->deadline));

        $row = $query->fetch();
        $this->id = $row['id'];
    }

    public function update($id, $attributes) {
        $query = DB::connection()->prepare('UPDATE Task SET (name, description, priority, deadline) = (:name, :description, :priority, :deadline) WHERE id = :id');
        $query->execute(array('id' => $id, 'priority' => $attributes['priority'], 'deadline' => $attributes['deadline'], 'name' => $attributes['name'], 'description' => $attributes['description']));
    }
    
    public function update_status($id) {
        $query = DB::connection()->prepare('UPDATE Task SET done = :done WHERE id = :id');
        $query->execute(array('id' => $id, 'done' => true));
    }

    public function destroy($id) {
        $query = DB::connection()->prepare('DELETE FROM Task WHERE id = :id');
        $query->execute(array('id' => $id));
    }

    public function addCategories($task_id, $category_ids) {
        foreach ($category_ids as $category_id) {
            $query = DB::connection()->prepare('INSERT INTO TaskCategory (task_id, category_id) VALUES (:task_id, :category_id)');
            $query->execute(array('task_id' => $task_id, 'category_id' => $category_id));
        }
    }

    public function removeAllCategories($task_id) {
        $query = DB::connection()->prepare('DELETE FROM TaskCategory WHERE task_id = :task_id');
        $query->execute(array('task_id' => $task_id));
    }

    public function getCategories($task_id) {
        $query = DB::connection()->prepare('SELECT * FROM TaskCategory WHERE task_id = :task_id');
        $query->execute(array('task_id' => $task_id));
        $rows = $query->fetchAll();
        $categories = array();

        foreach ($rows as $row) {
            $categories[] = Category::find($row['category_id']);
        }

        return $categories;
    }
    
    public function getCategoryIds($task_id) {
        $categories = Task::getCategories($task_id);
        $category_ids = array();
        
        foreach ($categories as $category) {
            $category_ids[] = $category->id;
        }
        return $category_ids;
    }
}
