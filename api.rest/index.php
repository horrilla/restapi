<?php
header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Headers: *");
header("Access-Control-Allow-Methods: *");
header("Access-Control-Allow-Credentials: true");
header("Content-Type: application/json;");
header('Accept: application/json');


include_once __DIR__ . '/connect.php';
require_once __DIR__ . '/functions.php';
global $connect;

$q = $_GET['q'];
$params = explode('/', $q);
$method = $_SERVER['REQUEST_METHOD'];
$api = $params[0];
$version = $params[1];
$type = $params[2];
$id = $params[3];


if ($method === 'GET' and $q === 'api/v1/notebook') {
        if (isset($id) and $id !== '') {
            getNote($connect, $id);
        } else {
            getNotes($connect);
        }
} elseif ($method === 'POST' and $q === 'api/v1/notebook') {
        if (!empty($_FILES['photo']['name'])) {
            $photo = move_uploaded_file($_FILES['photo']['tmp_name'], 'users_photo/' . $_FILES['photo']['name']);
            if ($photo) {
                $_POST['photo'] = 'http://api.rest/' . 'users_photo/' . $_FILES['photo']['name'];
                addNote($connect, $_POST);
            }
        } else {
            $_POST['photo'] = 'http://api.rest/users_photo/png-transparent-user-profile-computer-icons-user-interface-mystique-miscellaneous-user-interface-design-smile.png';
            addNote($connect, $_POST);
        }

} elseif ($method === 'POST' and $q === "api/v1/notebook/$id") {
    if (!empty($_FILES['photo']['name'])) {

        if ($photo = move_uploaded_file($_FILES['photo']['tmp_name'], 'users_photo/' . $_FILES['photo']['name'])) {
            $_POST['photo'] = 'http://api.rest/' . 'users_photo/' . $_FILES['photo']['name'];
            updateNote($connect, $id, $_POST);
        }
    } else {
        updateNote($connect, $id, $_POST);
    }

} elseif ($method === 'DELETE' and $q === "api/v1/notebook/$id") {
    if (isset($id) and $id !== '') {
        deleteNote($connect, $id);
    } else {
        http_response_code(400);
        $res = [
            'status' => false,
            'message' => 'Bad request, please set ID post'
        ];
        file_put_contents('php://output', json_encode($res));
    }
}



