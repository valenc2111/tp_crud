<?php
/**
*    File        : backend/models/students.php
*    Project     : CRUD PHP
*    Author      : Tecnologías Informáticas B - Facultad de Ingeniería - UNMdP
*    License     : http://www.gnu.org/licenses/gpl.txt  GNU GPL 3.0
*    Date        : Mayo 2025
*    Status      : Prototype
*    Iteration   : 2.0 ( prototype )
*/

function getAllStudents($conn) 
{
    $sql = "SELECT * FROM students";

    //MYSQLI_ASSOC devuelve un array ya listo para convertir en JSON:
    return $conn->query($sql)->fetch_all(MYSQLI_ASSOC);
}

//2.0
function getPaginatedStudents($conn, $limit, $offset) 
{
    $stmt = $conn->prepare("SELECT * FROM students LIMIT ? OFFSET ?");
    $stmt->bind_param("ii", $limit, $offset);
    $stmt->execute();
    $result = $stmt->get_result();
    return $result->fetch_all(MYSQLI_ASSOC);
}

//2.0
function getTotalStudents($conn) 
{
    $sql = "SELECT COUNT(*) AS total FROM students";
    $result = $conn->query($sql);
    return $result->fetch_assoc()['total'];
}

function getStudentById($conn, $id) 
{
    $stmt = $conn->prepare("SELECT * FROM students WHERE id = ?");
    $stmt->bind_param("i", $id);
    $stmt->execute();
    $result = $stmt->get_result();

    //fetch_assoc() devuelve un array asociativo ya listo para convertir en JSON de una fila:
    return $result->fetch_assoc(); 
}

function createStudent($conn, $fullname, $email, $age) 
{
    $checkSql = "SELECT id from students where email = ? "; // Selecciona los id con el mismo email haciendo la consulta de sql
    $checkStmt = $conn->prepare($checkSql); //prepara la consulta previa 
    $checkStmt->bind_param("s",$email); // reemplaza el valor del ? por $email
    $checkStmt->execute(); // ejecuta la consulta preparada , luego de reemplazar los valores
    $result = $checkStmt->get_result(); //asigna a result el valor obtenido de la consulta (en este caso un entero)

    if ($result->num_rows > 0){
        return [
            'inserted'=> 0,
            'Error' => 'email_exists'  // error por mail repetido
        ];
    }
    else
    {
        $sql = "INSERT INTO students (fullname, email, age) VALUES (?, ?, ?)";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("ssi", $fullname, $email, $age);
        if ($stmt->execute()){  // si la funcion que prepare , al ejecutarla no funciona , entonces tira otro error 
            return 
            [
                'inserted' => $stmt->affected_rows,        
                'id' => $conn->insert_id
            ];
        }
        else{
            return [
                'inserted' => 0,
                'Error' => 'db_inserted_failed'   // error que no corresponde a la validacion de mail
            ];
        }
    } 
}
// modifique
function updateStudent($conn, $id, $fullname, $email, $age) 
{
    $sql = "UPDATE students SET fullname = ?, email = ?, age = ? WHERE id = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("ssii", $fullname, $email, $age, $id);
    $stmt->execute();

    //Se retorna fila afectadas para validar en controlador:
    return ['updated' => $stmt->affected_rows];
}

function deleteStudent($conn, $id) 
{
    $sql = "DELETE FROM students WHERE id = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("i", $id);
    $stmt->execute();

    //Se retorna fila afectadas para validar en controlador
    return ['deleted' => $stmt->affected_rows];
}
?>