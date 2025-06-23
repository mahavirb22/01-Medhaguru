<?php
session_start();
$showError = false;
$showSuccess = false;
$showEditButton = false;

if (!isset($_SESSION['loggedin_student']) || $_SESSION['loggedin_student'] !== true) {
    header("Location: student_login.php");
    exit();
}
include "connection_db.php";

// Enable error reporting
mysqli_report(MYSQLI_REPORT_ERROR | MYSQLI_REPORT_STRICT);

try {
    $name = $_SESSION['user_name'];
    $check_sql = "SELECT * FROM feedback WHERE name = ?";
    $check_stmt = $conn->prepare($check_sql);

    if ($check_stmt === false) {
        throw new Exception("Error preparing statement: " . $conn->error);
    }

    // Bind the name parameter correctly
    $check_stmt->bind_param("s", $name);
    $check_stmt->execute();
    $result = $check_stmt->get_result();

    if ($result->num_rows > 0) {
        $showEditButton = true;
        $_SESSION['feedback_id'] = $result->fetch_assoc()['id']; // Store the feedback ID in the session
    }
    $check_stmt->close();
} catch (Exception $e) {
    $showError = "Error: " . $e->getMessage();
}

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    try {
        $feedback = $_POST['feedback'];

        // Correct the SQL query and binding
        $sql = "INSERT INTO feedback (name, feedback) VALUES (?, ?)";
        $stmt = $conn->prepare($sql);

        if ($stmt === false) {
            throw new Exception("Error preparing statement: " . $conn->error);
        }

        // Bind the parameters correctly
        $stmt->bind_param("ss", $name, $feedback);

        if ($stmt->execute()) {
            $showSuccess = "Feedback submitted successfully!";
            $showEditButton = true;
            $_SESSION['feedback_id'] = $stmt->insert_id; // Store the feedback ID in the session
            header("location: edit_feedback.php?success=1");
        } else {
            $showError = "Error submitting feedback: " . $stmt->error;
        }
        $stmt->close();
    } catch (Exception $e) {
        $showError = "Error: " . $e->getMessage();
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Medhaguru - Feedback</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
    <link href="https://fonts.googleapis.com/css2?family=Roboto:wght@400;700&display=swap" rel="stylesheet">
    <link href="dash_css.css" rel="stylesheet">
    <style>
        /* Feedback Form Styling */
        .content-wrapper {
            background-color: #fff;
            padding: 40px;
            border-radius: 12px;
            box-shadow: 0 4px 15px rgba(0, 0, 0, 0.1);
            width: 100%;
            max-width: 500px;
            text-align: center;
            margin: 20px auto;
        }

        .content-wrapper h2 {
            color: #75acac;
            margin-bottom: 20px;
            font-size: 2em;
        }

        .content-wrapper form {
            display: flex;
            flex-direction: column;
        }

        .content-wrapper label {
            margin-bottom: 5px;
            color: #555;
            text-align: left;
            font-weight: bold;
        }

        .content-wrapper input,
        .content-wrapper textarea {
            margin-bottom: 20px;
            padding: 15px;
            border: 1px solid #ddd;
            border-radius: 8px;
            font-size: 1em;
            transition: border-color 0.3s ease;
        }

        .content-wrapper input:focus,
        .content-wrapper textarea:focus {
            border-color: #75acac;
            outline: none;
        }

        .content-wrapper button {
            padding: 15px;
            background-color: #75acac;
            color: #fff;
            border: none;
            border-radius: 8px;
            cursor: pointer;
            font-size: 1.1em;
            transition: background-color 0.3s ease;
        }

        .content-wrapper button:hover {
            background-color: #5b9595;
        }

        .errorMessage {
            color: red;
            margin-bottom: 20px;
            font-weight: bold;
        }

        .success {
            color: green;
            margin-bottom: 20px;
            font-weight: bold;
        }

        .edit-button {
            margin-top: 20px;
            padding: 10px 20px;
            background-color: #5b9595;
            color: #fff;
            text-decoration: none;
            border: none;
            border-radius: 8px;
            cursor: pointer;
            font-size: 1em;
            transition: background-color 0.3s ease;
        }

        .edit-button:hover {
            background-color:rgba(87, 158, 158, 0.86);
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="sidebar">
            <div class="profile-photo">
                <img id="profilePhoto" src="images/default-profile.jpg" alt="Profile Photo">
                <form id="profileForm" method="POST" enctype="multipart/form-data">
                </form>
            </div>
            <div class="welcome-message">Welcome, <?php echo htmlspecialchars($_SESSION['user_name']); ?>!!</div>
            <ul>
                <hr class="separator">
                <li><a href="studentdashboard.php"><i class="fas fa-graduation-cap"></i> Courses</a></li>
                <hr class="separator">
                <li><a href="profile.php"><i class="fas fa-user"></i> Profile</a></li>
                <hr class="separator">
                <li><a href="event.php"><i class="fas fa-calendar-alt"></i> Events</a></li>
                <hr class="separator">
                <li><a href="query.php"><i class="fas fa-question-circle"></i> Query</a></li>
                <hr class="separator">
                <li><a href="feedback.php"><i class="fas fa-comment"></i> Feedback</a></li>
                <hr class="separator">
                <li><a href="logout.php"><i class="fas fa-sign-out-alt"></i> Logout</a></li>
                <hr class="separator">
            </ul>
        </div>

        <div class="main-content">
            <div id="feedback" class="content-wrapper">
                <h2>Feedback Form</h2>
                <?php
                if ($showError) {
                    echo '<div class="errorMessage">' . $showError . '</div>';
                }
                if ($showSuccess) {
                    echo '<div class="success">' . $showSuccess . '</div>';
                }
                ?>
                <?php if ($showEditButton): ?>
                    <br><a href="edit_feedback.php" class="edit-button">Edit Feedback</a>
                <?php else: ?>
                    <form method="POST" action="feedback.php">
                        <label for="name">Name:</label>
                        <input type="text" id="name" name="name" value="<?php echo htmlspecialchars($_SESSION['user_name']); ?>" required>
                        <label for="feedback">Feedback:</label>
                        <textarea id="feedback" name="feedback" rows="4" required></textarea>
                        <button type="submit">Submit Feedback</button>
                    </form>
                <?php endif; ?>

            </div>
        </div>
        <?php
                if (isset($_GET['success']) && $_GET['success'] == 1) {
                    echo '<div id="success" class="success">Feedback updated successfully..</div>
                    <script>
                        setTimeout(function() {
                            document.getElementById("success").style.display="none";
                        },4000);
                    </script>';
                }
                ?>
    </div>

    <script src="stu_dashboard.js"></script>
</body>
</html>
