<?php


function getNotes($connect) {
    $sql = 'SELECT * FROM notes';
    $query = $connect->prepare($sql);
    $query->execute();
    $res = $query->fetchAll();
    $res = json_encode($res);
    file_put_contents('php://output', $res);
}


function getNote($connect, $id) {
    $id = (int) htmlspecialchars($id);

    $sql = 'SELECT * FROM notes WHERE id = :id';
    $query = $connect->prepare($sql);
    $query->execute(['id' => $id]);
    $res = $query->fetch();

    if ($res == false) {
       http_response_code(404);
       $res = [
         'status' => '404',
         'message' => 'Note not found'
       ];
        file_put_contents('php://output', json_encode($res));
    } else {
        $res = json_encode($res);
        file_put_contents('php://output', $res);
    }
}


function addNote($connect, $data) {

    if (isset($data)) {
        $name = htmlspecialchars($data['name']);
        $company = htmlspecialchars($data['company']);
        $phone = htmlspecialchars($data['phone']);
        $email = htmlspecialchars($data['email']);
        $born = htmlspecialchars($data['born']);
        $photo = htmlspecialchars($data['photo']);

        $sql = 'INSERT INTO notes (name, company, phone, email, born, photo) VALUES (:name, :company, :phone, :email, :born, :photo)';
        $query = $connect->prepare($sql);
        $query->execute([
            'name' => $name,
            'company' => $company,
            'phone' => $phone,
            'email' => $email,
            'born' => $born,
            'photo' => $photo
        ]);

        if ($connect->lastInsertId()) {
            http_response_code(201);
            $res = [
                'status' => true,
                'post_id' => $connect->lastInsertId()
            ];

            file_put_contents('php://output', json_encode($res));
        }
    } else {
        http_response_code(400);
        $res = [
            'status' => false,
            'message' => 'Do not cretated',
            'error' => $connect->errorInfo()
        ];
        file_put_contents('php://output', json_encode($res));
    }
}


function updateNote($connect, $id, $data) {
    if (isset($data)) {
        $id = htmlspecialchars($id);

        $elem = 1;
        $sql = "UPDATE notes SET ";
        $param = [];
        foreach ($data as $key => $value) {
            if ($elem !== count($data)) {
                if (!empty($value)) {
                    $sql .= "$key = :$key, ";
                    $param[$key] .= $value;
                }
            } else {
                $sql .= "$key = :$key ";
                $param[$key] .= $value;
            }
            $elem++;
        }
        $sql .= "WHERE id = :id";
        $param['id'] = $id;

        $query = $connect->prepare($sql);
        $res = $query->execute($param);

        if ($res) {
            http_response_code(200);
            $res = [
                'status' => true,
                'message' => 'Note is updated'
            ];

            file_put_contents('php://output', json_encode($res));
        }
    } else {
        http_response_code(400);
        $res = [
            'status' => false,
            'message' => 'Do not updated'
        ];
        file_put_contents('php://output', json_encode($res));
    }
}


function deleteNote($connect, $id) {

    $id = htmlspecialchars($id);
    $sql = 'DELETE FROM notes WHERE id = :id';
    $query = $connect->prepare($sql);
    $res = $query->execute(['id' => $id]);

    if ($res) {
        $res = [
            'status' => true,
            'message' => 'Note is deleted'
        ];

        file_put_contents('php://output', json_encode($res));
    } else {

        http_response_code(400);
        $res = [
            'status' => false,
            'message' => 'Note is not deleted'
        ];

        file_put_contents('php://output', json_encode($res));
    }
}

































