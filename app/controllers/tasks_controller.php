<?php

class TaskController extends BaseController {

    public static function index() {
        self::check_logged_in();
        $user = self::get_user_logged_in();
        $user_id = $user->id;

        $params = $_GET;
        $options = array('actor_id' => $user_id);

        if (isset($params['showUndoneTasks'])) {
            $options['showUndoneTasks'] = $params['showUndoneTasks'];
        }
        
        $options['sortByPriority'] = true;

        $tasks = Task::all($options);
        View::make('task/index.html', array('tasks' => $tasks));
    }

    public static function store() {
        self::check_logged_in();
        $params = $_POST;
        $user = self::get_user_logged_in();
        $user_id = $user->id;
        $category_ids = $params['category_ids'];

        $attributes = array(
            'name' => $params['name'],
            'actor_id' => $user_id,
            'description' => $params['description'],
//            'category_ids' => array(),
            'done' => false,
            'priority' => $params['priority'],
            'added' => date('Y-m-d'),
            'deadline' => $params['deadline']
        );

        $task = new Task($attributes);

        $errors = $task->errors();

        if (count($errors) == 0) {
            $task->save();
            Task::addCategories($task->id, $category_ids);
            Redirect::to('/task/' . $task->id, array('message' => 'Tehtävä on lisätty!'));
        } else {
            View::make('task/new.html', array('errors' => $errors, 'attributes' => $attributes, 'categories' => Category::all($user_id)));
        }
    }

    public static function create() {
        self::check_logged_in();
        $user = self::get_user_logged_in();
        $user_id = $user->id;
        $categories = Category::all($user_id);
        View::make('task/new.html', array('categories' => $categories));
    }

    public static function show($id) {
        self::check_logged_in();
        $task = Task::find($id);
        $categories = Task::getCategories($task->id);
        View::make('task/show.html', array('task' => $task, 'categories' => $categories));
    }

    public static function edit($id) {
        self::check_logged_in();
        $user = self::get_user_logged_in();
        $task = Task::find($id);
        $user_id = $user->id;
        $categories = Category::all($user_id);
        $tasks_categories = Task::getCategoryIds($task->id);
        
        View::make('task/edit.html', array('attributes' => $task, 'categories' => $categories, 'tasks_categories' => $tasks_categories));
    }

    public static function update($id) {
        self::check_logged_in();
        $params = $_POST;
        $user = self::get_user_logged_in();
        $user_id = $user->id;
        if (isset($params['category_ids'])) {
            $selected_category_ids = $params['category_ids'];
        } else {
            $selected_category_ids = null;
        }

        $attributes = array(
            'id' => $id,
            'actor_id' => $user_id,
            'name' => $params['name'],
            'description' => $params['description'],
            'priority' => $params['priority'],
            'deadline' => $params['deadline']
        );

        $task = new Task($attributes);
        $errors = $task->errors();

        if (count($errors) > 0) {
            View::make('task/edit.html', array('errors' => $errors, 'attributes' => $attributes, 'categories' => Category::all($user_id)));
        } else {
            $task->update($task->id, $attributes);
            $task->removeAllCategories($task->id);

            if ($selected_category_ids != null) {
                Task::addCategories($id, $selected_category_ids);
            }

            Redirect::to('/task/' . $task->id, array('message' => 'Tehtävän muokkaus onnistui!'));
        }
    }

    public static function update_status($id) {
        self::check_logged_in();
        $task = new Task(array('id' => $id));
        $task->update_status($task->id);

        Redirect::to('/task/' . $task->id, array('message' => 'Tehtävä merkitty suoritetuksi!'));
    }

    public static function destroy($id) {
        self::check_logged_in();
        $task = new Task(array('id' => $id));
        $task_id = $task->id;
        $task->removeAllCategories($task_id);
        $task->destroy($task_id);

        Redirect::to('/task', array('message' => 'Tehtävä on poistettu!'));
    }

}
