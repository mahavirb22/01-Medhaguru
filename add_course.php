<?php
$shoSuccess = false;
$showError = false;

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    include "connection_db.php";

    // Retrieve input values
    $cname = $_POST["cname"];
    $cid = $_POST["cid"];
    $des = $_POST["des"];
    $fea = $_POST["fea"];
    $rec = $_POST["rec"];

    // Prepare the SQL statement
    $sql = "INSERT INTO courses (course_name, course_id, course_des, course_features, course_rec, dt) VALUES (?, ?, ?, ?, ?, CURRENT_TIMESTAMP)";

    // Initialize a statement
    $stmt = mysqli_prepare($conn, $sql);

    if ($stmt) {
        // Bind the parameters
        mysqli_stmt_bind_param($stmt, "sssss", $cname, $cid, $des, $fea, $rec);

        // Execute the statement
        if (mysqli_stmt_execute($stmt)) {
            $shoSuccess = true;

            // Create the table with the course name
            $table_sql = "CREATE TABLE `" . mysqli_real_escape_string($conn, $cname) . "_language_questions` (
                ques_id INT AUTO_INCREMENT PRIMARY KEY,
                subject VARCHAR(255) NOT NULL,
                ques TEXT NOT NULL,
                ques_opt1 VARCHAR(255) NOT NULL,
                ques_opt2 VARCHAR(255) NOT NULL,
                ques_opt3 VARCHAR(255) NOT NULL,
                ques_opt4 VARCHAR(255) NOT NULL,
                ques_ans VARCHAR(255) NOT NULL
            )";

            $res = mysqli_query($conn, $table_sql);

            if (!$res) {
                $showError = true;
            }
        } else {
            $showError = true;
        }

        // Close the statement
        mysqli_stmt_close($stmt);
    } else {
        $showError = true;
    }

    // Redirect to the admin uploads page
    header("Location: admin_uploads.php?added=1");
    exit();
}
?>
