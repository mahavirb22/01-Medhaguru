<?php
session_start();
include "connection_db.php";
$showError=false;

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $query_id = $_POST['query_id'];
    $answer = $_POST['answer'];

    if($answer!='') {
        // Update the query with the answer and change the status to "Answered"
        $update_sql = "UPDATE users_query SET query_ans='$answer', status='Answered' WHERE query_num='$query_id'";
        mysqli_query($conn, $update_sql);
        header("location: adminqueries.php?success=1");
    }
    else {
        $showError="Please enter some answer to query";
    }
}

// Fetch all queries, both pending and answered
$sql = "SELECT * FROM users_query ORDER BY dt DESC";
$result = mysqli_query($conn, $sql);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>MedhaGuru - Admin Queries</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
    <link href="https://fonts.googleapis.com/css2?family=Roboto:wght@400;700&display=swap" rel="stylesheet">
    <link href="dash_css.css" rel="stylesheet">
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
                <h1 style="color: #75acac"><center>Queries</center></h1>
                <hr class="separator1"><br>
                <?php
                if (mysqli_num_rows($result) > 0) {
                    while ($row = mysqli_fetch_assoc($result)) {
                        echo '<div class="query" style="position: relative;">';
                        echo '<strong>From: '. htmlspecialchars($row['user_name']).' </strong><br><br>';
                        echo '<strong>Query:</strong> ' . htmlspecialchars($row['query']) . '<br>';
                        if ($row['status'] == 'Pending') {
                            echo '<form method="POST" action="adminqueries.php">';
                            echo '<input type="hidden" name="query_id" value="' . $row['query_num'] . '">';
                            echo '<textarea class="answer-textarea" name="answer" placeholder="Type your answer here..."></textarea><br>';
                            echo '<button class="submit-button" type="submit">Answer</button>';
                            echo '</form>';
                        } else {
                            echo '<p><strong>Answer:</strong> ' . htmlspecialchars($row['query_ans']) . '</p>';
                        }
                        echo '<p class="query-status ' . ($row['status'] == 'Answered' ? 'answered' : 'pending') . '">' . $row['status'] . '</p>';
                        echo '</div><hr class="separator1"><br>';
                    }
                } else {
                    echo '<p>No queries available.</p>';
                }
                ?>
            </div>
        </div>
        <?php
            if($showError) {
                echo '<div id="errorMessage" class="errorMessage">'.$showError.'</div>
                <script>
                    setTimeout(function() {
                        document.getElementById("errorMessage").style.display="none";
                    },3000);
                </script>';
            }
            if (isset($_GET['success']) && $_GET['success'] == 1) {
                echo '<div id="success" class="success">Answer is sent successfully..</div>
                <script>
                    setTimeout(function() {
                        document.getElementById("success").style.display="none";
                    },4000);
                </script>';
            }
        ?>
    </div>
</body>
</html>
