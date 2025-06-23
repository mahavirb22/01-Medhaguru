<?php
session_start();
$showSuccess = false;

if (!isset($_SESSION['loggedin_student']) || $_SESSION['loggedin_student'] !== true) {
    header("Location: student_login.php");
    exit();
}

include "connection_db.php";
$name = $_SESSION['user_name'];
$id = $_SESSION['user_id'];

$sql = "SELECT * FROM edus_users_profile WHERE user_name='$name'";
$result = mysqli_query($conn, $sql);
$num = mysqli_num_rows($result);

$nm = '';
$mob = '';
$addr = '';
$pin = '';

if ($num == 1) {
    while ($row = mysqli_fetch_assoc($result)) {
        $nm = $row['user_name'];
        $mob = $row['user_mobile'];
        $addr = $row['user_addr'];
        $pin = $row['user_pincode'];
    }
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $fullName = $_POST["fullName"];
    $mobileno = $_POST["mobileno"];
    $address = $_POST["address"];
    $pincode = $_POST["pincode"];

    if ($nm != $fullName) {
        $sql = "UPDATE `edus_users_profile` SET `user_name` = '$fullName' WHERE user_id='$id'";
        mysqli_query($conn, $sql);
    }
    if ($mob != $mobileno) {
        $sql = "UPDATE `edus_users_profile` SET `user_mobile` = '$mobileno' WHERE user_id='$id'";
        mysqli_query($conn, $sql);
    }
    if ($addr != $address) {
        $sql = "UPDATE `edus_users_profile` SET `user_addr` = '$address' WHERE user_id='$id'";
        mysqli_query($conn, $sql);
    }
    if ($pin != $pincode) {
        $sql = "UPDATE `edus_users_profile` SET `user_pincode` = '$pincode' WHERE user_id='$id'";
        mysqli_query($conn, $sql);
    }

    header("Location: profile.php?success=1");
    exit();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Update Profile - Medhaguru</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
    <link href="https://fonts.googleapis.com/css2?family=Roboto:wght@400;700&display=swap" rel="stylesheet">
    <link href="dash_css.css" rel="stylesheet">
</head>
<body>
    <div class="container">
        <div class="sidebar">
            <div class="profile-photo">
                <img id="profilePhoto" src="images/default-profile.jpg" alt="Profile Photo">
                <label for="fileInput" class="camera-icon">
                    <i class="fas fa-camera"></i>
                </label>
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
            <div id="profile" class="content-wrapper profile-section">
                <h2 style="color: #75acac; font-size: 1.5rem;"><center>Update Profile</center></h2>
                <div class="profile-form">
                    <form id="profile" method="POST" action="update_profile.php">
                        <p id="msg">*Here please update your profile. If fields are empty then fill it with proper data / information.</p>
                        <label for="fullName">Full Name*</label>
                        <input type="text" id="fullName" name="fullName" placeholder="Enter your full name" value="<?php echo htmlspecialchars($nm); ?>"><br>
                        <label for="mobileno">Mobile Number*</label>
                        <input type="text" id="mobileno" name="mobileno" maxlength="10" placeholder="Enter your mobile number" value="<?php echo htmlspecialchars($mob); ?>"><br>
                        <label for="address">Address*</label>
                        <input type="text" id="address" name="address" placeholder="Enter your address" value="<?php echo htmlspecialchars($addr); ?>"><br>
                        <label for="pincode">Pincode*</label>
                        <input type="text" id="pincode" name="pincode" placeholder="Enter your pincode" maxlength="6" value="<?php echo htmlspecialchars($pin); ?>"><br>
                        <center><button type="submit">Update Profile</button></center>
                    </form>
                </div>
            </div>
        </div>
    </div>
    <script src="stu_dashboard.js"></script>
</body>
</html>
