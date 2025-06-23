<?php
session_start();
include "connection_db.php";

// Fetch feedback data
$query = "SELECT id, name, feedback, created_at FROM feedback ORDER BY created_at DESC";
$result = mysqli_query($conn, $query);

$feedbacks = [];
if ($result) {
    while ($row = mysqli_fetch_assoc($result)) {
        $feedbacks[] = $row;
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>MedhaGuru - Feedback</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
    <link href="https://fonts.googleapis.com/css2?family=Roboto:wght@400;700&display=swap" rel="stylesheet">
    <link href="dash_css.css" rel="stylesheet">
    <style>
        .feedback-container {
            margin-top: 20px;
        }

        .feedback-item {
            background-color: #f9f9f9;
            border: 1px solid #ddd;
            border-radius: 8px;
            padding: 20px;
            margin-bottom: 20px;
        }

        .feedback-item h3 {
            margin-top: 0;
        }

        .feedback-item p {
            margin: 10px 0;
        }

        .feedback-item .created-at {
            font-size: 14px;
            color: #777;
        }
    </style>
</head>
<body>
    <div class="container">
        <!-- Side Menu -->
        <div class="sidebar">
            <div class="profile-photo">
                <img id="profilePhoto" src="images/adminimg.jpg" alt="Profile Photo">
                <form id="profileForm" method="POST" enctype="multipart/form-data">
                </form>
            </div>
            <div class="welcome-message">Welcome, Admin!!</div>

            <!-- Separator Line -->
            <ul>
                <li><a href="admindashboard.php"><i class="fas fa-home"></i> Home</a></li>
                <hr class="separator">
                <li><a href="admin_uploads.php"><i class="fas fa-graduation-cap"></i> Uploads</a></li>
                <hr class="separator">
                <li><a href="users.php"><i class="fas fa-user"></i>Users</a></li>
                <hr class="separator">
                <li><a href="user_profile.php"><i class="fas fa-calendar-alt"></i>Profiles</a></li>
                <hr class="separator">
                <li><a href="adminqueries.php"><i class="fas fa-question-circle"></i>Queries</a></li>
                <hr class="separator">
                <li><a href="users_feedback.php"><i class="fas fa-comment"></i> Feedback</a></li>
                <hr class="separator">
                <li><a href="index.php"><i class="fas fa-sign-out-alt"></i> Logout</a></li>
                <hr class="separator">
            </ul>
        </div>
        <div class="main-content">
            <div class="content-wrapper">
                <h1 style="color: #75acac"><center>Feedback</center></h1>
                <hr class="separator1">

                <div class="feedback-container">
                    <?php if (!empty($feedbacks)): ?>
                        <?php foreach ($feedbacks as $feedback): ?>
                            <div class="feedback-item">
                                <h3><?php echo htmlspecialchars($feedback['name']); ?></h3>
                                <p><?php echo nl2br(htmlspecialchars($feedback['feedback'])); ?></p>
                                <p class="created-at">Submitted on: <?php echo htmlspecialchars($feedback['created_at']); ?></p>
                            </div>
                        <?php endforeach; ?>
                    <?php else: ?>
                        <p>No feedback available.</p>
                    <?php endif; ?>
                </div>
            </div>
        </div>
    </div>
</body>
</html>
