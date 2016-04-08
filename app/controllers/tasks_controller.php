<?php

class TaskController extends BaseController {

    public static function index() {
        $tasks = Task::all();
        View::make('task/index.html', array('tasks' => $tasks));
    }

    public static function store() {
        $params = $_POST;
        $task = new Task(array(
            'name' => $params['name'],
            'actor_id' => 1,
            'description' => $params['description'],
            // kategoriat!! miten
            'priority' => $params['priority'],
            'added' => date('Y-m-d'),
            'deadline' => $params['deadline']
        ));

        $task->save();

        Redirect::to('/task/' . $task->id, array('message' => 'Tehtävä on lisätty!'));
    }
    
    public static function create() {
        View::make('task/new.html');
    }

    public static function show($id) {
        $task = Task::find($id);
        View::make('task/show.html', array('task' => $task));
    }

}
