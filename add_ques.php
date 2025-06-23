<?php
$showSuccess = false;
$showError = false;
$course_name = false;

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

if ($_SERVER["REQUEST_METHOD"] == "POST") {

    // Retrieve input values
    $question = $_POST["question"];
    $option1 = $_POST["option1"];
    $option2 = $_POST["option2"];
    $option3 = $_POST["option3"];
    $option4 = $_POST["option4"];
    $answer = $_POST["answer"];

    // Assign the subject to a variable

    // Prepare the SQL statement
    $sql = "INSERT INTO " . $course_name . "_language_questions (subject, ques, ques_opt1, ques_opt2, ques_opt3, ques_opt4, ques_ans) VALUES (?, ?, ?, ?, ?, ?, ?)";

    // Initialize a statement
    $stmt = mysqli_prepare($conn, $sql);

    if ($stmt) {
        // Bind the parameters
        mysqli_stmt_bind_param($stmt, "sssssss", $course_name, $question, $option1, $option2, $option3, $option4, $answer);

        // Execute the statement
        if (mysqli_stmt_execute($stmt)) {
            echo "<script>alert('Question added successfully!'); window.location.href='courses_ques.php?success=1';</script>";
        } else {
            echo "<script>alert('Error adding question.');</script>";
        }

        // Close the statement
        mysqli_stmt_close($stmt);
    } else {
        $showError = true;
    }

    // Close the database connection
    mysqli_close($conn);
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Add Question</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh;
            margin: 0;
            background-color: #f0f0f0;
        }

        .form-container {
            background: white;
            padding: 20px;
            border-radius: 8px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
            opacity: 0;
            transform: translateY(20px);
            animation: fadeInUp 0.5s forwards;
            width: 500px;
        }

        @keyframes fadeInUp {
            to {
                opacity: 1;
                transform: translateY(0);
            }
        }

        h2 {
            margin-bottom: 20px;
            color: #333;
            text-align: center;
        }

        label {
            display: block;
            margin-bottom: 5px;
            color: #555;
        }

        input[type="text"], textarea, select {
            width: 95%;
            padding: 10px;
            margin-bottom: 15px;
            border: 1px solid #ccc;
            border-radius: 4px;
            transition: border-color 0.3s;
        }

        input[type="text"]:focus, textarea:focus, select:focus {
            border-color: #75acac;
        }

        input[type="submit"] {
            background-color: #75acac;
            color: white;
            border: none;
            padding: 10px 20px;
            border-radius: 4px;
            cursor: pointer;
            transition: background-color 0.3s;
        }

        input[type="submit"]:hover {
            background-color: #5b9595;
        }
    </style>
</head>
<body>
    <div class="form-container">
        <h2>Add a New Question for <?php echo htmlspecialchars($course_name); ?></h2>

        <form action="" method="POST">
            <label for="question">Question:</label>
            <textarea id="question" name="question" rows="4" required></textarea>

            <label for="option1">Option 1:</label>
            <input type="text" id="option1" name="option1" required>

            <label for="option2">Option 2:</label>
            <input type="text" id="option2" name="option2" required>

            <label for="option3">Option 3:</label>
            <input type="text" id="option3" name="option3" required>

            <label for="option4">Option 4:</label>
            <input type="text" id="option4" name="option4" required>

            <label for="answer">Correct Answer (Option Number):</label>
            <select id="answer" name="answer" required>
                <option value="1">Option 1</option>
                <option value="2">Option 2</option>
                <option value="3">Option 3</option>
                <option value="4">Option 4</option>
            </select>

            <input type="submit" value="Add Question">
        </form>
    </div>
</body>
</html>
