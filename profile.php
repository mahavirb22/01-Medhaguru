<?php
session_start();

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

// Handle file upload
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_FILES['fileInput'])) {
    $target_dir = "profile_images/";
    $target_file = $target_dir . basename($_FILES["fileInput"]["name"]);
    $uploadOk = 1;
    $imageFileType = strtolower(pathinfo($target_file, PATHINFO_EXTENSION));

    // Check if image file is a actual image or fake image
    $check = getimagesize($_FILES["fileInput"]["tmp_name"]);
    if ($check !== false) {
        $uploadOk = 1;
    } else {
        echo "File is not an image.";
        $uploadOk = 0;
    }

    // Check file size
    if ($_FILES["fileInput"]["size"] > 500000) {
        echo "Sorry, your file is too large.";
        $uploadOk = 0;
    }

    // Allow certain file formats
    if ($imageFileType != "jpg" && $imageFileType != "png" && $imageFileType != "jpeg"
        && $imageFileType != "gif") {
        echo "Sorry, only JPG, JPEG, PNG & GIF files are allowed.";
        $uploadOk = 0;
    }

    // Check if $uploadOk is set to 0 by an error
    if ($uploadOk == 0) {
        echo "Sorry, your file was not uploaded.";
    // If everything is ok, try to upload file
    } else {
        if (move_uploaded_file($_FILES["fileInput"]["tmp_name"], $target_file)) {
            // Update the database with the new image path
            $update_sql = "UPDATE edus_users_profile SET profile_image='$target_file' WHERE user_name='$name'";
            mysqli_query($conn, $update_sql);
            $showSuccess="The file ". htmlspecialchars(basename($_FILES["fileInput"]["name"])). " has been uploaded.";
            header("location: studentdashboard.php?success=1");
        } else {
            $showError="Sorry, there was an error uploading your file.";
        }
    }
}
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
        label {
            background-color:rgba(91, 149, 149, 0.09);
            border-radius: 5px;
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

        <div class="main-content">
            <div id="profile" class="content-wrapper profile-section">
                <h2 style="color: #75acac; font-size: 2rem;"><center>User Profile</center></h2>
                <div class="profile-form">
                    <p id="msg" style="color: red;">*Here is your profile information.</p>
                    <label for="fullName">Full Name</label>
                    <p><strong><?php echo htmlspecialchars($nm); ?></strong></p>
                    <label for="mobileno">Mobile Number</label>
                    <p><strong><?php if(htmlspecialchars($mob)=='') {
                        echo '---------';
                    }
                    else {
                        echo htmlspecialchars($mob);
                    }; ?></strong></p>
                    <label for="address">Address</label>
                    <p><strong><?php if(htmlspecialchars($addr)=='') {
                        echo '---------';
                    }
                    else {
                        echo htmlspecialchars($addr);
                    }; ?></strong></p>
                    <label for="pincode">Pincode</label>
                    <p><strong><?php if(htmlspecialchars($pin)=='') {
                        echo '---------';
                    }
                    else {
                        echo htmlspecialchars($pin);
                    }; ?></strong></p>
                    <center><a style="text-decoration: none" href="update_profile.php" class="update-button">Update Profile</a></center>
                </div>
            </div>
        </div>
        <?php
        if (isset($_GET['success']) && $_GET['success'] == 1) {
            echo '<div id="success" class="success">Profile Updated Successfully..</div>
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
