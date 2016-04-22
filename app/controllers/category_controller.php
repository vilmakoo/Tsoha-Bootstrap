<?php

class CategoryController extends BaseController {

    public static function index() {
        self::check_logged_in();

        $user = self::get_user_logged_in();
        $user_id = $user->id;
        $categories = Category::all($user_id);
        
        View::make('category/index.html', array('categories' => $categories));
    }

    public static function show($id) {
        self::check_logged_in();
        $category = Category::find($id);
        $tasks = Category::getTasks($id);
        View::make('category/show.html', array('category' => $category, 'tasks' => $tasks));
    }

    public static function create() {
        self::check_logged_in();

        View::make('category/new.html');
    }

    public static function store() {
        self::check_logged_in();
        $user = self::get_user_logged_in();
        $params = $_POST;

        $attributes = array(
            'name' => $params['name'],
            'description' => $params['description'],
            'actor_id' => $user->id
        );

        $category = new Category($attributes);
        $errors = $category->errors();

        if (count($errors) == 0) {
            $category->save();

            Redirect::to('/category/' . $category->id, array('message' => 'Kategoria on luotu!'));
        } else {
            View::make('category/new.html', array('errors' => $errors, 'attributes' => $attributes));
        }
    }

    public static function edit($id) {
        self::check_logged_in();
        $category = Category::find($id);

        View::make('category/edit.html', array('attributes' => $category));
    }

    public static function update($id) {
        self::check_logged_in();
        $params = $_POST;

        $attributes = array(
            'id' => $id,
            'name' => $params['name'],
            'description' => $params['description']
        );

        $category = new Category($attributes);
        $errors = $category->errors();

        if (count($errors) == 0) {
            $category->update($id, $attributes);

            Redirect::to('/category/' . $category->id, array('message' => 'Kategoriaa muokattiin onnistuneesti.'));
        } else {
            View::make('/category/edit.html', array('errors' => $errors, 'attributes' => $attributes));
        }
    }

    public static function destroy($id) {
        self::check_logged_in();
        $category = new Category(array('id' => $id));
        $category->removeTasks($category->id);
        $category->destroy($category->id);

        Redirect::to('/category', array('message' => 'Kategoria on poistettu!'));
    }

}
