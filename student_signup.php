<?php
$shoSuccess=false;
$showError=false;

if($_SERVER["REQUEST_METHOD"]=="POST") {
    include "connection_db.php";
    $name=$_POST["name"];
    $email=$_POST["email"];
    $password=$_POST["password"];
    $cpassword=$_POST["confirm-password"];

    $existSql="SELECT * FROM edus_users where user_email='$email'";
    $result=mysqli_query($conn,$existSql);
    $numExistRows=mysqli_num_rows($result);
    if($numExistRows > 0) {
        $showError="Email Already Exist";
    }
    else {
        if($password==$cpassword) {
            $hash=password_hash($password,PASSWORD_DEFAULT);
            $sql_user="INSERT into edus_users (user_name,user_email,user_pass,dt) values ('$name','$email','$hash',current_timestamp())";
            $sql_profile="INSERT into edus_users_profile (user_name) values ('$name')";
            $result=mysqli_query($conn,$sql_user);
            $res=mysqli_query($conn,$sql_profile);
            if($result && $res) {
                $shoSuccess=true;
            }
            header("location: student_login.php?success=1");
        }
        else {
            $showError="Password do not match";
        }
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>MedhaGuru - Sign Up</title>
    <style>
        .signup-button {
            width: 100%;
            padding: 12px;
            background-color: #75acac;
            color: #fff;
            border: none;
            border-radius: 5px;
            font-size: 16px;
            cursor: pointer;
            transition: background-color 0.3s;
            font-weight: 600;
            margin-bottom: 10px;
        }
    </style>
    <link rel="stylesheet" href="signup_styles.css">
    <link href="https://fonts.googleapis.com/css2?family=Montserrat:wght@400;600&display=swap" rel="stylesheet">
</head>
<body>
    <div class="signup-container">
        <div class="signup-card">
            <div class="signup-illustration animate-left">
                <img src="images/sign-login.avif" alt="Sign Up Illustration">
            </div>
            <div class="signup-form">
                <h2 class="animate-left">Student Sign Up</h2>
                <form id="signupForm" method="POST" action="student_signup.php">
                    <input type="hidden" id="role" name="role" value="student">
                    <div class="input-box animate-left">
                        <label for="name">Name</label>
                        <input type="text" id="name" name="name" required>
                    </div>
                    <div class="input-box animate-left">
                        <label for="email">Email Address</label>
                        <input type="email" id="email" name="email" required>
                    </div>
                    <div class="input-box animate-left">
                        <label for="password">Password</label>
                        <input type="password" id="password" name="password" required>
                    </div>
                    <div class="input-box animate-left">
                        <label for="confirm-password">Confirm Password</label>
                        <input type="password" id="confirm-password" name="confirm-password" required>
                    </div>
                    <p id="msg" style="color:red"  class="msg animate-left">*Make sure that passwords should be matched*</p>
                    <input type="submit" class="signup-button animate-left" value="Sign Up">
                    <a href="student_login.php" style="color: #75acac" id="msg" class="msg animate-left">Have an account ? Go to Login.</a>
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
    </body>
</html>
