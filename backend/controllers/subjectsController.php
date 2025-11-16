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


function handlePost($conn) 
{
    $input = json_decode(file_get_contents("php://input"), true);
    $name=trim($input['name']);

    $existing = getsubjectbyname($conn,$name);
    //si existe devuelve error
    if($existing){
        http_response_code(409); //conflicto
        echo json_encode(["error" =>"la materia ya existe"]);
        return;
    }
    // si no existe se crea la materia
    $result = createSubject($conn, $input['name']);
    if ($result['inserted'] > 0) 
    {
        echo json_encode(["message" => "Materia creada correctamente"]);
    } 
    else 
    {
        http_response_code(500);
        echo json_encode(["error" => "No se pudo crear"]);
    }
}

/*function handlePost($conn) //3.0 new
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
    } catch (mysqli_sql_exception $e) { //1062 error duplicado
        if ($e->getCode() == 1062)  {
            http_response_code(400);
            echo json_encode(["error" => "La materia ya existe."]);
        } else {
            http_response_code(500);
            echo json_encode(["error" => "Error interno del servidor"]);
        }
    }
}*/

function handlePut($conn) 
{
    $input = json_decode(file_get_contents("php://input"), true);
    $name = trim($input['name']);

    //  verificar si ya existe una materia con ese nombre
    $existing = getSubjectByName($conn, $name);
    if ($existing) {
        http_response_code(409); // conflicto
        echo json_encode(["error" => "la materia ya existe"]);
        return;
    }

    //   actualiza si puede
    $result = updateSubject($conn, $input['id'], $name);

    if ($result['updated'] > 0) {
        echo json_encode(["message" => "Materia actualizada correctamente"]);
    } 
    else {
        http_response_code(500);
        echo json_encode(["error" => "No se pudo actualizar"]);
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


/*function handlePost($conn) //3.0 new
{
    $input = json_decode(file_get_contents("php://input"), true);

    // Validar que se envió el nombre
    if (empty($input['name'])) {
        http_response_code(400);
        echo json_encode(["error" => "El nombre de la materia es obligatorio."]);
        return;
    }

    // 🔹 Validar si ya existe antes de insertar
    if (subjectExists($conn, $input['name'])) {
        http_response_code(409); // Código más apropiado: Conflicto
        echo json_encode(["error" => "Ya existe una materia con ese nombre."]);
        return;
    }

    try {
        $result = createSubject($conn, $input['name']);
        if ($result['inserted'] > 0) {
            echo json_encode(["message" => "Materia creada correctamente"]);
        } else {
            http_response_code(400);
            echo json_encode(["error" => "No se pudo crear la materia"]);
        }
    } catch (mysqli_sql_exception $e) {
        // Solo manejamos errores graves aquí
        http_response_code(500);
        echo json_encode(["error" => "Error interno del servidor"]);
        error_log($e->getMessage());
    }
}*/


?>