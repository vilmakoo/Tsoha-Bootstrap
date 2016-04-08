<?php

class Task extends BaseModel {

    public $id, $actor_id, $name, $description, $priority, $done, $added, $deadline;

    public function __construct($attributes) {
        parent::__construct($attributes);
    }

    public static function all() {
        $query = DB::connection()->prepare('SELECT * FROM Task');
        $query->execute();
        $rows = $query->fetchAll();
        $games = array();

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

    }


