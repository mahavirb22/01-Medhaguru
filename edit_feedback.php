<?php
session_start();
$feedbackData = null;

if (!isset($_SESSION['loggedin_student']) || $_SESSION['loggedin_student'] !== true) {
    header("Location: student_login.php");
    exit();
}
include "connection_db.php";

if (!isset($_SESSION['feedback_id'])) {
    header("Location: feedback.php");
    exit();
}

$feedback_id = $_SESSION['feedback_id'];

// Fetch the feedback data
$sql = "SELECT name, feedback FROM feedback WHERE id = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $feedback_id);
$stmt->execute();
$stmt->bind_result($name, $feedback);
$stmt->fetch();
$stmt->close();

$feedbackData = ['name' => $name, 'feedback' => $feedback];

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $name = $_POST['name'];
    $feedback = $_POST['feedback'];

    $sql = "UPDATE feedback SET name = ?, feedback = ? WHERE id = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("ssi", $name, $feedback, $feedback_id);

    if ($stmt->execute()) {
        $showSuccess = "Feedback updated successfully!";
        header("location: feedback.php?success=1");
    } else {
        $showError = "Error updating feedback: " . $stmt->error;
    }
    $stmt->close();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Medhaguru - Edit Feedback</title>
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
                <h2>Edit Feedback</h2>
                
                <form method="POST" action="edit_feedback.php">
                    <label for="name">Name:</label>
                    <input type="text" id="name" name="name" value="<?php echo htmlspecialchars($feedbackData['name']); ?>" required>
                    <label for="feedback">Feedback:</label>
                    <textarea id="feedback" name="feedback" rows="4" required><?php echo htmlspecialchars($feedbackData['feedback']); ?></textarea>
                    <button type="submit">Update Feedback</button>
                </form>
            </div>
        </div>
        <?php
                if (isset($_GET['success']) && $_GET['success'] == 1) {
                    echo '<div id="success" class="success">Feedback stored successfully..</div>
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
