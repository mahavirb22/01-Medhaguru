<?php
session_start();
$showError=false;
$showSuccess=false;

if (!isset($_SESSION['loggedin_student']) || $_SESSION['loggedin_student'] !== true) {
    header("Location: student_login.php");
    exit();
}
include "connection_db.php";
$name = $_SESSION['user_name'];
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Events - Medhaguru</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
    <link href="https://fonts.googleapis.com/css2?family=Roboto:wght@400;700&display=swap" rel="stylesheet">
    <link href="dash_css.css" rel="stylesheet">
</head>
<body>
    <div class="container">
        <div class="sidebar">
            <div class="profile-photo">
                <img id="profilePhoto" src="images/default-profile.jpg" alt="Profile Photo">
                <input type="file" id="fileInput" style="display: none;" accept="image/*">
            </div>
            <div class="welcome-message">Welcome <?php echo htmlspecialchars($_SESSION['user_name']); ?> !!</div>
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
            <div id="events" class="content-wrapper event-section">
                <h1><center>Events</center></h1>
                <div class="event-container">
                    <h2><center>Upcoming Events</center></h2>
                    <div class="event-card">
                        <img src="images/quiz.avif" alt="Quiz Competition">
                        <h3>Quiz Competition</h3>
                        <p>Aiming to achieve the highest score and be crowned the winner!</p>
                        <button onclick="registerEvent('Quiz Competition')">Register</button>
                    </div>
                    <div class="event-card">
                        <img src="images/hackathon.jpg" alt="Hackathon Event">
                        <h3>Hackathon</h3>
                        <p>Join our hackathon and innovate with the best!</p>
                        <button onclick="registerEvent('Hackathon')">Register</button>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script src="stu_dashboard.js"></script>
</body>
</html>
