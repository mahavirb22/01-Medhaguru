<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>MedhaGuru - Users</title>
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
        .delete-btn {
            background-color: #ff4d4d;
            color: white;
            border: none;
            padding: 5px 10px;
            cursor: pointer;
            border-radius: 4px;
        }
        .delete-btn:hover {
            background-color: #ff1a1a;
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
                <h1><center>User Management</center></h1>
                <hr class="separator1">
                <table>
                    <tr>
                        <th>ID</th>
                        <th>Name</th>
                        <th>Email</th>
                        <th>Date</th>
                        <th>Action</th>
                    </tr>
                    <?php
                    include "connection_db.php";
                    
                    // Fetch user data
                    $sql = "SELECT user_id, user_name, user_email, dt FROM edus_users";
                    $result = $conn->query($sql);
                    
                    if ($result->num_rows > 0) {
                        while($row = $result->fetch_assoc()) {
                            echo "<tr>";
                            echo "<td>" . $row["user_id"] . "</td>";
                            echo "<td>" . $row["user_name"] . "</td>";
                            echo "<td>" . $row["user_email"] . "</td>";
                            echo "<td>" . $row["dt"] . "</td>";
                            echo "<td><form method='post' action=''>
                                        <input type='hidden' name='delete_id' value='" . $row["user_id"] . "'>
                                        <button type='submit' class='delete-btn' name='delete'>Delete</button>
                                    </form></td>";
                            echo "</tr>";
                        }
                    } else {
                        echo "<tr><td colspan='5'><center>No users found</center></td></tr>";
                    }
                    
                    // Delete user and related profile
                    if (isset($_POST['delete'])) {
                        $delete_id = $_POST['delete_id'];
                    
                        // Delete related profile
                        $delete_profile_sql = "DELETE FROM edus_users_profile WHERE user_id=$delete_id";
                        if ($conn->query($delete_profile_sql) === TRUE) {
                            // Delete user
                            $delete_user_sql = "DELETE FROM edus_users WHERE user_id=$delete_id";
                            if ($conn->query($delete_user_sql) === TRUE) {
                                echo "<meta http-equiv='refresh' content='0'>"; // Refresh the page
                            } else {
                                echo "Error deleting user: " . $conn->error;
                            }
                        } else {
                            echo "Error deleting profile: " . $conn->error;
                        }
                    }
                    
                    $conn->close();
                    
                    ?>
                </table>
            </div>
        </div>
    </div>
</body>
</html>
