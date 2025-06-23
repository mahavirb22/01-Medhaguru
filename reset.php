<?php
$showError=false;
$showMsg=false;

if($_SERVER["REQUEST_METHOD"]=="POST") {
    include "connection_db.php";
    $password=$_POST["password"];
    $cpassword=$_POST["confirm-password"];

    if($password==$cpassword) {
        $hash=password_hash($password,PASSWORD_DEFAULT);
        $sql_user="UPDATE edus_users set user_pass='$hash'";
        $result=mysqli_query($conn,$sql_user);
        if($result && $res) {
            $shoSuccess=true;
        }
        header("location: student_login.php?success=1");
    }
    else {
        $showError="Password do not match";
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>MedhaGuru - Reset Password</title>
    <link rel="stylesheet" href="login_styles.css">
    <style>
        #forgot {
            color: #75acac;
            cursor: pointer;
            text-decoration: none;
            margin-top: 20px;
        }
    </style>
    <link href="https://fonts.googleapis.com/css2?family=Montserrat:wght@400;600&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
</head>
<body>
    <div class="login-container">
        <div class="login-card">
            <div class="login-illustration animate-left">
                <img src="images/sign-login.avif" alt="Login Illustration">
            </div>
            <div class="login-form">
                <h2 class="animate-left">Reset password</h2>
                <form id="loginForm" method="POST" action="reset.php">
                    <div class="input-box animate-left">
                        <label for="password">Password</label>
                        <input type="password" id="password" name="password" required>
                    </div>
                    <div class="input-box animate-left">
                        <label for="confirm-password">Confirm Password</label>
                        <input type="password" id="confirm-password" name="confirm-password" required>
                    </div>
                    <a href="student_login.php" id="forgot" style="color: #75acac" class="forgot-password animate-left">Go to Forgot Page !</a><br><br>
                    <input type="submit" class="login-button animate-left" value="Submit"><br><br>
                </form>
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
                    echo '<div id="success" class="success">Successful</div>
                    <script>
                        setTimeout(function() {
                            document.getElementById("success").style.display="none";
                        },4000);
                    </script>';
                }
                ?>
            </div>
        </div>
    </div>
    <script src="login_scripts.js"></script>
</body>
</html>
