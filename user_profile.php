<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>MedhaGuru - Users Profile</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
    <link href="https://fonts.googleapis.com/css2?family=Roboto:wght@400;700&display=swap" rel="stylesheet">
    <link href="dash_css.css" rel="stylesheet">
    <style>
        body {
            font-family: 'Roboto', sans-serif;
        }
        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 20px;
        }
        table, th, td {
            border: 1px solid #ddd;
        }
        th, td {
            padding: 5px;
            text-align: left;
        }
        th {
            background-color: #f2f2f2;
        }
        tr:nth-child(even) {
            background-color: #f9f9f9;
        }
        tr:hover {
            background-color: #f1f1f1;
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
                <h1><center>Profile Management</center></h1>
                <hr class="separator1">
                <table>
                    <tr>
                        <th>ID</th>
                        <th>Name</th>
                        <th>Mobile</th>
                        <th>Address</th>
                        <th>Pincode</th>
                    </tr>
                    <?php
                    include "connection_db.php";
                    
                    // Fetch user data
                    $sql = "SELECT user_id, user_name, user_mobile, user_addr, user_pincode FROM edus_users_profile";
                    $result = $conn->query($sql);
                    
                    if ($result->num_rows > 0) {
                        while($row = $result->fetch_assoc()) {
                            echo "<tr>";
                            echo "<td>" . $row["user_id"] . "</td>";
                            echo "<td>" . $row["user_name"] . "</td>";
                            echo "<td>" . $row["user_mobile"] . "</td>";
                            echo "<td>" . $row["user_addr"] . "</td>";
                            echo "<td>" . $row["user_pincode"] . "</td>";
                            echo "</tr>";
                        }
                    } else {
                        echo "<tr><td colspan='5'><center>No users found</center></td></tr>";
                    }
                    
                    $conn->close();
                    
                    ?>
                </table>
            </div>
        </div>
    </div>
</body>
</html>
