<?php
$login=false;
$showError=false;
$showMsg=false;
$val=1;

if($_SERVER["REQUEST_METHOD"]=="POST") {
    include "connection_db.php";
    $email=$_POST["email"];
    $password=$_POST["password"];

    $sql="SELECT * FROM edus_users where user_email='$email'";
    $result=mysqli_query($conn,$sql);
    $num=mysqli_num_rows($result);
    if($num == 1) {
        while($row=mysqli_fetch_assoc($result)) {
            if(password_verify($password,$row['user_pass'])) {
                $login=true;
                session_start();
                $_SESSION['loggedin_student']=true;
                $detailSql="SELECT * FROM edus_users where user_email='$email'";
                $detailResult=mysqli_query($conn,$detailSql);
                while($rows=mysqli_fetch_assoc($detailResult)) {
                    $_SESSION['user_id']=$rows['user_id'];
                    $_SESSION['user_name']=$rows['user_name'];
                }
                header("location: studentdashboard.php?success=1");
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
    <title>MedhaGuru - Login</title>
    <link rel="stylesheet" href="login_styles.css">
    <style>
        #forgot {
            color: #75acac;
            cursor: pointer;
            text-decoration: none;
            margin-top: 20px;
        }
        #home {
            color: #75acac;
            cursor: pointer;
            text-decoration: none;
            margin-top: 20px;
            margin-left: 270px;
            z-index: 1;
            margin-bottom: 50px;
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
                <h2 class="animate-left">Student Login</h2>
                <form id="loginForm" method="POST" action="student_login.php">
                    <div class="input-box animate-left">
                        <label for="email">Email Address</label>
                        <input type="email" id="email" name="email" required>
                    </div>
                    <div class="input-box animate-left">
                        <label for="password">Password</label>
                        <div class="password-field">
                            <input type="password" id="password" name="password" required>
                            <i class="fas fa-eye" id="toggle-password"></i>
                        </div>
                    </div>
                    <a href="forgot.php" id="forgot" style="color: #75acac" class="forgot-password animate-left">Forgot Password ?</a><br><br>
                    <a href="index.php" id="home" style="color: #75acac" class="home animate-left">Go to Home Page !</a><br><br>
                    <input type="submit" class="login-button animate-left" value="Login"><br><br>
                    <a href="student_signup.php" id="forgot" style="color: #75acac;" class="forgot-password animate-left">Don't have an account ? Go to Sign Up</a>
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
                    echo '<div id="success" class="success">Sign Up Successfully..</div>
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
