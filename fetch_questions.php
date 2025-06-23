<?php
session_start();
include "connection_db.php";

// Check if course_id is available in session
if (!isset($_SESSION['course_id'])) {
    echo json_encode(['error' => 'Course ID not set in session']);
    exit;
}

$course_id = $_SESSION['course_id'];

// Fetch the course name using the course_id
$query = "SELECT course_name FROM courses WHERE course_id = '$course_id'";
$result = $conn->query($query);

if ($result->num_rows > 0) {
    $row = $result->fetch_assoc();
    $course_name = $row['course_name'];
    $table_name = $conn->real_escape_string($course_name . "_language_questions");

    // Fetch 3 random questions
    $query = "SELECT ques, ques_opt1, ques_opt2, ques_opt3, ques_opt4, ques_ans FROM `$table_name` ORDER BY RAND() LIMIT 3";
    $result = $conn->query($query);

    if (!$result) {
        echo json_encode(['error' => 'Query failed: ' . $conn->error]);
        exit;
    }

    $questions = [];
    if ($result->num_rows > 0) {
        while ($row = $result->fetch_assoc()) {
            $questions[] = [
                'question' => $row['ques'],
                'options' => [
                    $row['ques_opt1'],
                    $row['ques_opt2'],
                    $row['ques_opt3'],
                    $row['ques_opt4']
                ],
                'correctAnswer' => $row['ques_opt'.$row['ques_ans']]
            ];
        }
    } else {
        // Debugging: Log if no rows are returned
        error_log("No rows returned for table: " . $table_name);
    }

    header('Content-Type: application/json');
    echo json_encode($questions);
} else {
    echo json_encode(['error' => 'Course not found']);
}
?>
