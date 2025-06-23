<?php
$correct=false;
$showError=false;
$showMsg=false;
$val=1;

if($_SERVER["REQUEST_METHOD"]=="POST") {
    include "connection_db.php";
    $email=$_POST["email"];

    $sql="SELECT * FROM edus_users where user_email='$email'";
    $result=mysqli_query($conn,$sql);
    $num=mysqli_num_rows($result);
    if($num == 1) {
        while($row=mysqli_fetch_assoc($result)) {
            if($row['user_email']==$email) {
                $correct=true;
                session_start();
                $_SESSION['user_email']=$email;
                header("location: reset.php?success=1");
            }
            else {
                $showError="Invalid Credentials";
            }
        }
    }
    else {
        $showError="Invalid Credentials";
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>MedhaGuru - Forgot Password</title>
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
                <h2 class="animate-left">Forgot password</h2>
                <form id="loginForm" method="POST" action="forgot.php">
                    <div class="input-box animate-left">
                        <label for="email">Email Address</label>
                        <input type="email" id="email" name="email" required>
                    </div>
                    <a href="student_login.php" id="forgot" style="color: #75acac" class="forgot-password animate-left">Go to Login Page !</a><br><br>
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
                ?>
            </div>
        </div>
    </div>
    <script src="login_scripts.js"></script>
</body>
</html>
