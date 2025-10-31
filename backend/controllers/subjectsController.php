<?php
/**
*    File        : backend/controllers/subjectsController.php
*    Project     : CRUD PHP
*    Author      : Tecnologías Informáticas B - Facultad de Ingeniería - UNMdP
*    License     : http://www.gnu.org/licenses/gpl.txt  GNU GPL 3.0
*    Date        : Mayo 2025
*    Status      : Prototype
*    Iteration   : 1.0 ( prototype )
*/

require_once("./repositories/subjects.php");

function handleGet($conn) 
{
    $input = json_decode(file_get_contents("php://input"), true);

    if (isset($input['id'])) 
    {
        $subject = getSubjectById($conn, $input['id']);
        echo json_encode($subject);
    } 
    //2.1
    else if (isset($_GET['page']) && isset($_GET['limit'])) 
    {
        $page = (int)$_GET['page'];
        $limit = (int)$_GET['limit'];
        $offset = ($page - 1) * $limit;

        $subjects = getPaginatedSubjects($conn, $limit, $offset);
        $total = getTotalSubjects($conn);

        echo json_encode([
            'subjects' => $subjects, // ya es array
            'total' => $total        // ya es entero
        ]);
    }
    else 
    {
        $subjects = getAllSubjects($conn);
        echo json_encode($subjects);
    }
}

function handlePost($conn) //3.0 new
{
    $input = json_decode(file_get_contents("php://input"), true);

    try {
        $result = createSubject($conn, $input['name']);
        if ($result['inserted'] > 0) {
            echo json_encode(["message" => "Materia creada correctamente"]);
        } else {
            http_response_code(400);
            echo json_encode(["error" => "No se pudo crear la materia"]);
        }
    } catch (mysqli_sql_exception $e) {
        if ($e->getCode() == 1062) {
            http_response_code(400);
            echo json_encode(["error" => "La materia ya existe."]);
        } else {
            http_response_code(500);
            echo json_encode(["error" => "Error interno del servidor"]);
        }
    }
}

function handlePut($conn) 
{
    $input = json_decode(file_get_contents("php://input"), true);

    try {
        $result = updateSubject($conn, $input['id'], $input['name']);

        if ($result['updated'] > 0) {
            echo json_encode(["message" => "Materia actualizada correctamente"]);
        } else {
            http_response_code(400);
            echo json_encode(["error" => "No se pudo actualizar"]);
        }

    } catch (mysqli_sql_exception $e) {
        if ($e->getCode() == 1062) { //maneja el error 1062
            
            http_response_code(400);
            echo json_encode(["error" => "Ya existe una materia con ese nombre"]);
        } else {
            // Otros errores graves → 500
            http_response_code(500);
            echo json_encode(["error" => "Error interno del servidor"]);
            error_log($e->getMessage()); // opcional: log para depuración
        }
    }
}

function handleDelete($conn) 
{
    $input = json_decode(file_get_contents("php://input"), true);
    
    $result = deleteSubject($conn, $input['id']);
    if ($result['deleted'] > 0) 
    {
        echo json_encode(["message" => "Materia eliminada correctamente"]);
    } 
    else 
    {
        http_response_code(500);
        echo json_encode(["error" => "No se pudo eliminar"]);
    }
}
?>