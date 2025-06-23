<?php
session_start();
$shoSuccess = false;
$showError = false;

if (!isset($_SESSION['loggedin_student']) || $_SESSION['loggedin_student'] !== true) {
    header("Location: student_login.php");
    exit();
}
include "connection_db.php";

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    if (isset($_POST['delete_query'])) {
        $query_id = $_POST['query_id'];
        $delete_sql = "DELETE FROM users_query WHERE query_num='$query_id'";
        if (mysqli_query($conn, $delete_sql)) {
            $shoSuccess = "Your query is deleted successfully.";
        } else {
            $showError = "Error deleting query. Please try again.";
        }
    } else {
        $query = $_POST["query"];
        $name = $_SESSION['user_name'];
        $id = $_SESSION['user_id'];

        if ($query != ' ') {
            $sql = "INSERT INTO users_query (user_id, user_name, query, dt, status) VALUES ('$id', '$name', '$query', current_timestamp(), 'Pending')";
            $result = mysqli_query($conn, $sql);
            if ($result) {
                $shoSuccess = "Query is submitted to admin. They will respond as early as possible.";
            }
            header("location: query.php?success=1");
        } else {
            $showError = "Please enter the query.";
        }
    }
}

$id = $_SESSION['user_id'];
$sql = "SELECT * FROM users_query WHERE user_id='$id' ORDER BY dt DESC";
$result = mysqli_query($conn, $sql);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Profile - Medhaguru</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
    <link href="https://fonts.googleapis.com/css2?family=Roboto:wght@400;700&display=swap" rel="stylesheet">
    <link href="dash_css.css" rel="stylesheet">
    <style>
        .query-status {
            position: absolute;
            bottom: 10px;
            right: 10px;
            font-size: 14px;
        }
        .pending {
            color: red;
        }
        .answered {
            color: green;
        }
    </style>
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

        <form id="queryForm" method="POST" action="query.php">
            <div class="main-content">
                <div id="query" class="content-wrapper query-section">
                    <h2><center>Query</center></h2>
                    <div class="query-form">
                        <label for="queryDetails" style="color: #000">Submit your Query</label>
                        <textarea rows="7" id="query" name="query" placeholder="Enter your query about EdusPlatform..." required></textarea><br>
                        <button type="submit">Submit</button>
                    </div>
                </div>
                <div class="main-content">
                    <div class="content-wrapper">
                        <h2 style="color: #75acac"><center>Your Queries</center></h2>
                        <div class="profile-details">
                            <center><p><strong>Name: <?php echo htmlspecialchars($_SESSION['user_name']); ?></strong></p></center>
                        </div>

                        <div class="queries-section">
                            <?php
                            if (mysqli_num_rows($result) > 0) {
                                while ($row = mysqli_fetch_assoc($result)) {
                                    echo '<div class="query-item" style="position: relative;">';
                                    echo '<p><strong>Query:</strong> ' . htmlspecialchars($row['query']) . '</p>';
                                    echo '<p><strong>Date:</strong> ' . htmlspecialchars($row['dt']) . '</p>';
                                    if (!empty($row['query_ans'])) {
                                        echo '<p class="query-status answered">Answered</p>';
                                        echo '<p><strong>Answer:</strong> ' . htmlspecialchars($row['query_ans']) . '</p>';
                                    } else {
                                        echo '<p class="query-status pending">Pending</p>';
                                    }
                                    echo '<form method="POST" action="query.php" style="display:inline;">';
                                    echo '<input type="hidden" name="query_id" value="' . $row['query_num'] . '">';
                                    echo '<button type="submit" name="delete_query" class="delete-button">Delete</button>';
                                    echo '</form>';
                                    echo '</div>';
                                }
                            } else {
                                echo '<p>No queries submitted yet.</p>';
                            }
                            ?>
                        </div>
                    </div>
                </div>
            </div>
        </form>
        <?php
        if ($showError) {
            echo '<div id="errorMessage" class="errorMessage">' . $showError . '</div>
            <script>
                setTimeout(function() {
                    document.getElementById("errorMessage").style.display="none";
                },3000);
            </script>';
        }
        if (isset($_GET['success']) && $_GET['success'] == 1) {
            echo '<div id="success" class="success">Query is submitted to admin. They will respond as soon as possible.</div>
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
