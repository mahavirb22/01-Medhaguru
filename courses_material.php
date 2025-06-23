<?php
session_start();
$showError = false;
$showSuccess = false;

include "connection_db.php";

// Fetch courses from the database
$courses = [];
$query = "SELECT course_id, course_name, course_des FROM courses";
$result = mysqli_query($conn, $query);

if ($result) {
    while ($row = mysqli_fetch_assoc($result)) {
        $courses[] = $row;
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Medhaguru - Student Dashboard</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
    <link href="https://fonts.googleapis.com/css2?family=Roboto:wght@400;700&display=swap" rel="stylesheet">
    <link href="dash_css.css" rel="stylesheet">
    <style>
        .main-contents {
            padding: 30px;
            padding-right: 50px;
            padding-top: 50px;
            width: calc(100% - 270px);
            animation: slideIn 0.5s ease-in-out;
        }
        .course-container {
            text-align: center;
            padding: 20px;
            border: 1px solid #ddd;
            border-radius: 12px;
            margin: 15px;
            transition: transform 0.3s, box-shadow 0.3s;
            cursor: pointer;
            background-color: #f9f9f9;
        }
        .course-container:hover {
            transform: translateY(-5px);
            box-shadow: 0 4px 15px rgba(0, 0, 0, 0.2);
        }
        .course-container h3 {
            margin-top: 10px;
            color: #fff;
            font-size: 30px;
            background-color:rgba(117, 172, 172, 0.88);
            padding: 15px;
            border-radius: 10px;
        }
        .course-container p {
            color: #777;
            font-size: 14px;
            font-style: bold;
            text-align: justify;
        }
        .no-courses {
            text-align: center;
            font-size: 1.2em;
            color: #888;
            margin-top: 20px;
        }
        .search-bar {
            margin-bottom: 20px;
        }
    
        .back-btn {
    padding: 15px 25px;
    font-size: 16px;
    cursor: pointer;
    border-radius: 25px;
    border: none;
    background-color: #75acac;
    color: white;
    transition: background-color 0.3s ease, transform 0.3s ease;
    box-shadow: 0 4px 8px rgba(0, 0, 0, 0.2);
}
    </style>
</head>
<body>
     <div class="main-contents ">
            <div id="home" class="content-wrapper">
                <button type="button" class="back-btn" onclick="window.location.href='admin_uploads.php'">Back</button>
                <p class="intro-text">Here students gain the authority and agency to make decisions on their own. They will learn new and popular languages!!<br>To start a course, please click on that Course Name Card.</p>
                <div class="search-bar">
                    <input type="text" id="searchInput" placeholder="Search for courses...">
                    <button type="button" onclick="searchCourses()"><i class="fas fa-search"></i></button>
                </div>
                <h2><center>Popular Courses</center></h2>
                <div class="course-grid" id="courseGrid">
                    <?php if (!empty($courses)): ?>
                        <?php foreach ($courses as $course): ?>
                            <div class="course-container" data-title="<?php echo htmlspecialchars($course['course_name']); ?>" onclick="openCoursePage('<?php echo htmlspecialchars($course['course_id']); ?>')">
                                <h3><?php echo htmlspecialchars($course['course_name']); ?></h3>
                                <p><?php echo htmlspecialchars($course['course_des']); ?></p>
                            </div>
                        <?php endforeach; ?>
                    <?php else: ?>
                        <div class="no-courses">No courses added.</div>
                    <?php endif; ?>
                </div>
                <div class="no-results" id="noResults" style="display: none;">No results found!!!</div>
            </div>
        </div>
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
                    echo '<div id="success" class="success">File uploaded successfully...</div>
                    <script>
                        setTimeout(function() {
                            document.getElementById("success").style.display="none";
                        },4000);
                    </script>';
                }
            ?>
    </div>

    <script src="stu_dashboard.js"></script>
    <script>
        document.getElementById('fileInput').addEventListener('change', function(event) {
            document.getElementById('profileForm').submit();
        });

        function openCoursePage(courseId) {
            window.location.href = 'add_material.php?id=' + courseId;
        }

        function searchCourses() {
            const input = document.getElementById('searchInput').value.toLowerCase();
            const courseContainers = document.querySelectorAll('.course-container');
            let found = false;

            courseContainers.forEach(container => {
                const title = container.getAttribute('data-title').toLowerCase();
                if (title.includes(input)) {
                    container.style.display = '';
                    found = true;
                } else {
                    container.style.display = 'none';
                }
            });

            document.getElementById('noResults').style.display = found ? 'none' : 'block';
        }
    </script>
</body>
</html>
