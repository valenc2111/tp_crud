<?php
/**
*    File        : backend/models/subjects.php
*    Project     : CRUD PHP
*    Author      : Tecnologías Informáticas B - Facultad de Ingeniería - UNMdP
*    License     : http://www.gnu.org/licenses/gpl.txt  GNU GPL 3.0
*    Date        : Mayo 2025
*    Status      : Prototype
*    Iteration   : 1.0 ( prototype )
*/

function getAllSubjects($conn) 
{
    $sql = "SELECT * FROM subjects";

    return $conn->query($sql)->fetch_all(MYSQLI_ASSOC);
}

//2.1
function getPaginatedSubjects($conn, $limit, $offset) 
{
    $stmt = $conn->prepare("SELECT * FROM subjects LIMIT ? OFFSET ?");
    $stmt->bind_param("ii", $limit, $offset);
    $stmt->execute();
    $result = $stmt->get_result();
    return $result->fetch_all(MYSQLI_ASSOC);
}

//2.1
function getTotalSubjects($conn) 
{
    $sql = "SELECT COUNT(*) AS total FROM subjects";
    $result = $conn->query($sql);
    return $result->fetch_assoc()['total'];
}


function getSubjectById($conn, $id) 
{
    $sql = "SELECT * FROM subjects WHERE id = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("i", $id);
    $stmt->execute();
    $result = $stmt->get_result();

    return $result->fetch_assoc(); 
}

function createSubject($conn, $name) 
{
    $sql = "INSERT INTO subjects (name) VALUES (?)";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("s", $name);
    $stmt->execute();

    return 
    [
        'inserted' => $stmt->affected_rows,        
        'id' => $conn->insert_id
    ];
}

function updateSubject($conn, $id, $name) 
{
    $sql = "UPDATE subjects SET name = ? WHERE id = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("si", $name, $id);
    $stmt->execute();

    return ['updated' => $stmt->affected_rows];
}

function deleteSubject($conn, $id) 
{
    $sql = "DELETE FROM subjects WHERE id = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("i", $id);
    $stmt->execute();

    return ['deleted' => $stmt->affected_rows];
}
?>
