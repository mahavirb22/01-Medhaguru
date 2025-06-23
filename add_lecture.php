<?php
session_start();
$course_id = false;
$course_name = '';

include "connection_db.php";

if (isset($_GET['id'])) {
    $course_id = $_GET['id'];
}

$query = "SELECT course_name FROM courses WHERE course_id = '$course_id'";
$result = $conn->query($query);

if ($result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
        $course_name = $row['course_name'];
    }
}

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_FILES['courseFile'])) {
    $uploadDir = 'Uploads/' . $course_name;

    if (!is_dir($uploadDir)) {
        mkdir($uploadDir, 0777, true);
    }

    $targetFile = $uploadDir . '/' . basename($_FILES['courseFile']['name']);

    if (move_uploaded_file($_FILES['courseFile']['tmp_name'], $targetFile)) {
        echo "<script>alert('Lecture uploaded successfully!'); window.location.href='courses_lec.php?success=1';</script>";
    } else {
        echo "<script>alert('Error uploading file.');</script>";
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Upload File for <?php echo htmlspecialchars($course_name); ?></title>
    <style>
        body {
            font-family: 'Arial', sans-serif;
            background: linear-gradient(135deg, #f5f7fa, #c3cfe2);
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh;
            margin: 0;
        }
        .upload-container {
            background: white;
            padding: 40px;
            border-radius: 10px;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
            text-align: center;
            animation: fadeIn 1s ease-in-out;
        }
        .upload-container h1 {
            color: #333;
            margin-bottom: 20px;
            animation: slideIn 0.5s ease-in-out;
        }
        .upload-container form {
            display: flex;
            flex-direction: column;
            align-items: center;
        }
        .upload-container input[type="file"] {
            margin: 20px 0;
            padding: 10px;
            border: 1px solid #ddd;
            border-radius: 5px;
            width: 100%;
            max-width: 300px;
            animation: fadeInUp 0.5s ease-in-out;
        }
        .upload-container button {
            padding: 10px 20px;
            border: none;
            border-radius: 5px;
            background-color: #75acac;
            color: white;
            cursor: pointer;
            transition: background-color 0.3s;
            animation: fadeInUp 0.5s ease-in-out;
        }
        .upload-container button:hover {
            background-color: #5b9595;
        }
        @keyframes fadeIn {
            from { opacity: 0; }
            to { opacity: 1; }
        }
        @keyframes slideIn {
            from { transform: translateY(-20px); opacity: 0; }
            to { transform: translateY(0); opacity: 1; }
        }
        @keyframes fadeInUp {
            from { transform: translateY(20px); opacity: 0; }
            to { transform: translateY(0); opacity: 1; }
        }
    </style>
</head>
<body>
    <div class="upload-container">
        <h1>Upload Lecture for <?php echo htmlspecialchars($course_name); ?></h1>
        <form action="" method="post" enctype="multipart/form-data">
            <input type="file" name="courseFile" required>
            <button type="submit">Upload File</button>
        </form>
    </div>
</body>
</html>
