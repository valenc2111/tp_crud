<?php
/**
*    File        : backend/models/studentsSubjects.php
*    Project     : CRUD PHP
*    Author      : Tecnologías Informáticas B - Facultad de Ingeniería - UNMdP
*    License     : http://www.gnu.org/licenses/gpl.txt  GNU GPL 3.0
*    Date        : Mayo 2025
*    Status      : Prototype
*    Iteration   : 1.0 ( prototype )
*/

function assignSubjectToStudent($conn, $student_id, $subject_id, $approved) 
{
    $sql = "INSERT INTO students_subjects (student_id, subject_id, approved) VALUES (?, ?, ?)";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("iii", $student_id, $subject_id, $approved);
    $stmt->execute();

    return 
    [
        'inserted' => $stmt->affected_rows,        
        'id' => $conn->insert_id
    ];
}

//Query escrita sin ALIAS resumidos (a mi me gusta más):
function getAllSubjectsStudents($conn) 
{
    $sql = "SELECT students_subjects.id,
                students_subjects.student_id,
                students_subjects.subject_id,
                students_subjects.approved,
                students.fullname AS student_fullname,
                subjects.name AS subject_name
            FROM students_subjects
            JOIN subjects ON students_subjects.subject_id = subjects.id
            JOIN students ON students_subjects.student_id = students.id";

    return $conn->query($sql)->fetch_all(MYSQLI_ASSOC);
}

//2.1
function getPaginatedStudentsSubjects($conn, $limit, $offset) 
{
    $sql = "SELECT students_subjects.id,
                   students_subjects.student_id,
                   students_subjects.subject_id,
                   students_subjects.approved,
                   students.fullname AS student_fullname,
                   subjects.name AS subject_name
            FROM students_subjects
            JOIN students ON students_subjects.student_id = students.id
            JOIN subjects ON students_subjects.subject_id = subjects.id
            LIMIT ? OFFSET ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("ii", $limit, $offset);
    $stmt->execute();
    $result = $stmt->get_result();
    return $result->fetch_all(MYSQLI_ASSOC);
}

//2.1
function getTotalStudentsSubjects($conn) 
{
    $sql = "SELECT COUNT(*) AS total FROM students_subjects";
    $result = $conn->query($sql);
    return $result->fetch_assoc()['total'];
}

//Query escrita con ALIAS resumidos:
function getSubjectsByStudent($conn, $student_id) 
{
    $sql = "SELECT ss.subject_id, s.name, ss.approved
        FROM students_subjects ss
        JOIN subjects s ON ss.subject_id = s.id
        WHERE ss.student_id = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("i", $student_id);
    $stmt->execute();
    $result= $stmt->get_result();

    return $result->fetch_all(MYSQLI_ASSOC); 
}

function updateStudentSubject($conn, $id, $student_id, $subject_id, $approved) 
{
    $sql = "UPDATE students_subjects 
            SET student_id = ?, subject_id = ?, approved = ? 
            WHERE id = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("iiii", $student_id, $subject_id, $approved, $id);
    $stmt->execute();

    return ['updated' => $stmt->affected_rows];
}

function removeStudentSubject($conn, $id) 
{
    $sql = "DELETE FROM students_subjects WHERE id = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("i", $id);
    $stmt->execute();

    return ['deleted' => $stmt->affected_rows];
}
?>
