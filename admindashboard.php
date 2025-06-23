<?php
$total = 0;

include "connection_db.php";

$query = "SELECT count(user_id) as total FROM edus_users";
$result = mysqli_query($conn, $query);

if ($result) {
    while ($row = mysqli_fetch_assoc($result)) {
        $total=$row['total'];
    }
}
?>

<!DOCTYPE html>
    <html lang="en">
    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <title>MedhaGuru - Admin Dahsboard</title>
        <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
        <link href="https://fonts.googleapis.com/css2?family=Roboto:wght@400;700&display=swap" rel="stylesheet">
        <link href="dash_css.css" rel="stylesheet">
        <style>
           .main-content {
                margin-left: 220px; /* Adjusted margin due to sidebar width */
                padding: 20px;
                padding-right: 50px;
                padding-top: 50px;
                width: calc(100% - 220px); /* Adjusted width */
                display: flex;
                justify-content: center; /* Center the content horizontally */
                align-items: center; /* Center the content vertically */
                height: 50vh; /* Full viewport height */
            }

            .content-wrapper {
                background-color: #ffffff; /* White background for content */
                border-radius: 8px;
                padding: 40px;
                box-shadow: 0 0 15px rgba(0, 0, 0, 0.1);
                border: 2px solid #DCDCDC; /* Light gray border */
                width: 50%;
                margin-top: 50px;
                height: 80%; /* Increased width */
                max-width: 800px; /* Maximum width */
            }

            .enrollment-container {
                background-color: #dbe8e8;
                border-radius: 8px;
                padding: 20px;
                text-align: center;
                font-size: 20px;
                margin-top: 60px;
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
                    <h1 style="color: #75acac"><center>Admin Dashboard</center></h1>
                    <hr class="separator1">

                    <div class="enrollment-container">
                        <p>Total students enrolled = <?php echo $total; ?></p>
                    </div>
                </div>
            </div>
        </div>
    </body>
    </html>