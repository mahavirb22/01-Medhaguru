<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>MedhaGuru - Admin Dashboard</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
    <link href="https://fonts.googleapis.com/css2?family=Roboto:wght@400;700&display=swap" rel="stylesheet">
    <link href="dash_css.css" rel="stylesheet">
    <style>
        body {
            font-family: 'Roboto', sans-serif;
        }
        
        .tab-header {
            display: flex;
            justify-content: space-around;
            border-bottom: 2px solid #75acac;
            margin-bottom: 20px;
        }

        .tab-header h2 {
            padding: 10px 20px;
            cursor: pointer;
            color: #75acac;
            border-top-left-radius: 10px;
            border-top-right-radius: 10px;
            transition: background-color 0.3s;
        }

        .tab-header h2:hover {
            background-color: #e9f5ff;
        }

        .tab-content {
            display: none;
        }

        .tab-content.active {
            display: block;
        }

        .section input, .section textarea, .section button {
            width: 100%;
            padding: 12px;
            margin-top: 10px;
            margin-bottom: 15px;
            border-radius: 7px;
            border: 1px solid #ccc;
            box-sizing: border-box;
        }

        .section button {
            background-color: #75acac;
            color: white;
            border: none;
            cursor: pointer;
            font-size: 16px;
        }

        .section .btn {
            background-color: #75acac;
            color: white;
            border: none;
            cursor: pointer;
            font-size: 16px;
        }

        .section button:hover {
            background-color:rgba(117, 172, 172, 0.24);
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
                <div class="tab-header">
                    <h2 onclick="openTab(event, 'addCourse')">Add Course</h2>
                    <h2 onclick="openTab(event, 'uploadLecture')">Upload Lecture</h2>
                    <h2 onclick="openTab(event, 'uploadMaterial')">Upload Study Material</h2>
                    <h2 onclick="openTab(event, 'addQuestions')">Add questions</h2>
                </div>

                <form action="add_course.php" method="post">
                    <div id="addCourse" class="tab-content section active">
                        <input type="text" id="courseName" name="cname" placeholder="Course Name" required>
                        <input type="text" id="courseId" name="cid" placeholder="Course ID" required>
                        <textarea id="courseDescription" name="des" placeholder="Course Description" rows="5" required></textarea>
                        <textarea id="courseFeatures" name="fea" placeholder="Course Features" rows="5" required></textarea>
                        <textarea id="courseRec" name="rec" placeholder="Recommended Books" rows="5" required></textarea>
                        <input type="submit" class="btn" value="Add Course">
                    </div>
                </form>

                <div id="uploadLecture" class="tab-content section">
                    <input type="submit" class="btn" onclick="window.location.href='courses_lec.php';" value="Add Lecture">
                </div>

                <div id="uploadMaterial" class="tab-content section">
                    <input type="submit" class="btn" onclick="window.location.href='courses_material.php';" value="Add Study Material">
                </div>

                <div id="addQuestions" class="tab-content section">
                    <input type="submit" class="btn" onclick="window.location.href='courses_ques.php';" value="Add Questions">
                </div>
                <?php
                 if (isset($_GET['added']) && $_GET['added'] == 1) {
                    echo '<div id="success" class="success">Course Added Successfully..</div>
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

    <script>
        function openTab(event, tabId) {
            const tabContents = document.querySelectorAll('.tab-content');
            tabContents.forEach(content => content.classList.remove('active'));
            document.getElementById(tabId).classList.add('active');
        }
    </script>
</body>
</html>
