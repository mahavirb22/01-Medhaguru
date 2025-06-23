<?php
include "connection_db.php";

// Use prepared statements to prevent SQL injection
$videoName = $_GET['videoName'];

$sql = "SELECT * FROM sticky_notes WHERE video_name = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("s", $videoName);
$stmt->execute();

$result = $stmt->get_result();

$notes = [];
if ($result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
        $notes[] = $row;
    }
}

echo json_encode($notes);

// Close the statement and connection
$stmt->close();
$conn->close();
?>
