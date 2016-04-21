<?php

class TaskController extends BaseController {

    public static function index() {
        self::check_logged_in();
        $user = self::get_user_logged_in();
        $user_id = $user->id;
        $tasks = Task::all($user_id);
        View::make('task/index.html', array('tasks' => $tasks));
    }

    public static function store() {
        self::check_logged_in();
        $params = $_POST;
        $user = self::get_user_logged_in();
        $user_id = $user->id;
        
        $attributes = array(
            'name' => $params['name'],
            'actor_id' => $user_id,
            'description' => $params['description'],
            // kategoriat!! miten
            'done' => false,
            'priority' => $params['priority'],
            'added' => date('Y-m-d'),
            'deadline' => $params['deadline']
        );

        $task = new Task($attributes);

        $errors = $task->errors();

        if (count($errors) == 0) {
            $task->save();
            Redirect::to('/task/' . $task->id, array('message' => 'Tehtävä on lisätty!'));
        } else {
            View::make('task/new.html', array('errors' => $errors, 'attributes' => $attributes));
        }
    }

    public static function create() {
        self::check_logged_in();
        View::make('task/new.html');
    }

    public static function show($id) {
        self::check_logged_in();
        $task = Task::find($id);
        View::make('task/show.html', array('task' => $task));
    }

    public static function edit($id) {
        self::check_logged_in();
        $task = Task::find($id);
        View::make('task/edit.html', array('attributes' => $task));
    }

    public static function update($id) {
        self::check_logged_in();
        $params = $_POST;

        $attributes = array(
            'id' => $params('id'),
            'name' => $params['name'],
            'description' => $params['description'],
            // kategoriat!! miten
            'done' => $params('done'),
            'priority' => $params['priority'],
            'deadline' => $params['deadline']
        );

        $task = new Task($attributes);
        $errors = $task->errors();

        if (count($errors) > 0) {
            View::make('task/edit.html', array('errors' => $errors, 'attributes' => $attributes));
        } else {
            $task->update();

            Redirect::to('/task/' . $task->id, array('message' => 'Tehtävän muokkaus onnistui!'));
        }
    }

    public static function destroy($id) {
        self::check_logged_in();
        $task = new Task(array('id' => $id));
        $task_id = $task->id;
        $task->destroy($task_id);

        Redirect::to('/task', array('message' => 'Tehtävä on poistettu!'));
    }

}
