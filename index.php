<?php
header('Content-type: application/json');
require_once 'autoload.php';

$app = new \Slim\Slim(
    array(
        'conn' => get_connection($settings),
        'widgets' => $widgets_registration
    )
);

function register_list($model_class){
    global $app;
    $url = strtolower($model_class);

    $app->get("/$url/", function() use ($app, $model_class){
        $conn = $app->config('conn');
        $model = new $model_class($conn);
        $sql = 'SELECT * from ' . $model->get_table();

        echo json_encode($model_class::queryset($conn, $sql));
    });
}

function register_detail($model_class){
    global $app;
    $url = strtolower($model_class);

    $app->get("/$url/:id/", function($id) use ($app, $model_class){
        $conn = $app->config('conn');
        $model = new $model_class($conn);
        $model->get($id);
        echo json_encode($model);
    });
}

function register_create($model_class){
    global $app;
    $url = strtolower($model_class);

    $app->post("/$url/", function() use ($app, $model_class, $url){
        $conn = $app->config('conn');
        $model = new $model_class($conn);
        $data = json_decode($app->request->getBody(), true);
        $model->set_data($data);
        $model->save();
        echo json_encode($model);
    });
}

function register_delete($model_class){
    global $app;
    $url = strtolower($model_class);

    $app->delete("/$url/:id/", function($id) use ($app, $model_class){
        $conn = $app->config('conn');
        $model = new $model_class($conn);
        $model->get($id);
        $model->delete();
        echo json_encode(array('ok' => True));
    });
}

function register_update($model_class){
    global $app;
    $url = strtolower($model_class);

    $app->put("/$url/:id/", function($id) use ($app, $model_class, $url){
        $conn = $app->config('conn');
        $model = new $model_class($conn);
        $model->get($id);

        $data = json_decode($app->request->getBody(), true);

        $model->set_data($data);
        $model->save();
        echo json_encode($model);
    });
}

function create_crud($model_class){
    register_list($model_class);
    register_detail($model_class);
    register_create($model_class);
    register_delete($model_class);
    register_update($model_class);
}

create_crud('Template');
create_crud('Email');

$app->run();
