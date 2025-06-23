<?php
include "connection_db.php";

$color = $_POST['color'];
$note = $_POST['note'];
$videoName = $_POST['videoName'];
$positionTop = $_POST['positionTop'];
$positionLeft = $_POST['positionLeft'];

$sql_check_note = "SELECT * FROM sticky_notes WHERE note = ? AND video_name = ?";
$stmt = $conn->prepare($sql_check_note);
$stmt->bind_param("ss", $note, $videoName);
$stmt->execute();
$result = $stmt->get_result();
$num = $result->num_rows;

if ($num == 1) {
    $sql_update = "UPDATE sticky_notes SET position_top = ?, position_left = ? WHERE note = ? AND video_name = ?";
    $stmt = $conn->prepare($sql_update);
    $stmt->bind_param("ssss", $positionTop, $positionLeft, $note, $videoName);
} else {
    $sql_insert = "INSERT INTO sticky_notes (color, note, video_name, position_top, position_left) VALUES (?, ?, ?, ?, ?)";
    $stmt = $conn->prepare($sql_insert);
    $stmt->bind_param("sssss", $color, $note, $videoName, $positionTop, $positionLeft);
}

if ($stmt->execute()) {
    $id = $stmt->insert_id;
    echo json_encode(['id' => $id]);
} else {
    echo "Error: " . $stmt->error;
}

$stmt->close();
$conn->close();
?>
