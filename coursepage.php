<?php
session_start();
$course_id = false;

if (!isset($_SESSION['loggedin_student']) || $_SESSION['loggedin_student'] !== true) {
    header("Location: student_login.php");
    exit();
}

include "connection_db.php";
$name = $_SESSION['user_name'];

if (isset($_GET['id'])) {
    $course_id = $_GET['id'];
}

// Fetch course details from the database
$query = "SELECT course_name, course_des, course_features, course_rec FROM courses WHERE course_id = '$course_id'";
$result = $conn->query($query);

if ($result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
        $nm = $row['course_name'];
        $intro = $row['course_des'];
        $fea = $row['course_features'];
        $rec = $row['course_rec'];
    }
}

// Function to get all study materials from the directory
function getStudyMaterials($directory)
{
    $files = [];
    if (is_dir($directory)) {
        if ($dh = opendir($directory)) {
            while (($file = readdir($dh)) !== false) {
                if ($file != '.' && $file != '..') {
                    $files[] = $file;
                }
            }
            closedir($dh);
        }
    }
    return $files;
}

function getPdfNotes($directory)
{
    $files = [];
    if (is_dir($directory)) {
        if ($dh = opendir($directory)) {
            while (($file = readdir($dh)) !== false) {
                if ($file != '.' && $file != '..') {
                    $files[] = $file;
                }
            }
            closedir($dh);
        }
    }
    return $files;
}

$studyMaterials = getStudyMaterials('Uploads/' . $nm);
$pdfNotes = getPdfNotes('Uploads_material/'.$nm);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Course Page</title>
    <style>
        body {
            font-family: 'Arial', sans-serif;
            margin: 0;
            padding: 0;
            background-color: #f4f4f9;
            color: #333;
        }
        header {
            background-color: #75acac;
            color: white;
            padding: 1.5em;
            text-align: center;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.2);
        }
        header h1 {
            margin: 0;
            font-size: 2.5em;
            animation: fadeIn 2s;
        }
        .container {
            padding: 2em;
            margin: 0 auto;
            max-width: 1200px;
        }
        .main-content {
            background-color: white;
            padding: 2em;
            border-radius: 12px;
            box-shadow: 0 4px 12px rgba(0, 0, 0, 0.1);
            position: relative;
            overflow: hidden;
            animation: slideIn 1s;
        }
        .image-section {
            display: flex;
            justify-content: center;
            align-items: center;
            margin-bottom: 2em;
        }
        .image-section img {
            height: 200px;
            border-radius: 10px;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
        }
        a {
            text-decoration: none;
            color: #75acac;
        }
        .course-name {
            font-size: 2em;
            font-weight: bold;
            color: #75acac;
        }
        .course-description {
            font-size: 1.2em;
            margin-top: 10px;
            color: #555;
        }
        .separator {
            border-bottom: 2px solid #ddd;
            margin: 2em 0;
        }
        .secondary-content {
            background-color: white;
            padding: 2em;
            border-radius: 12px;
            box-shadow: 0 4px 12px rgba(0, 0, 0, 0.1);
            margin-top: 2em;
        }
        .tabs {
            display: flex;
            gap: 1em;
            margin-bottom: 1em;
            justify-content: center;
            flex-wrap: wrap;
        }
        .tab {
            padding: 1em;
            background-color: #e0e0e0;
            color: #75acac;
            cursor: pointer;
            border-radius: 8px;
            transition: background-color 0.3s, color 0.3s;
            border: 1px solid #ccc;
        }
        .tab:hover {
            background-color: #d0d0d0;
        }
        .tab.active {
            background-color: #f0f0f0;
            color: #75acac;
            border: 1px solid #aaa;
        }
        .content {
            display: none;
        }
        .content.active {
            display: block;
        }
        .video-lectures {
            display: flex;
            flex-wrap: wrap;
            gap: 1em;
        }
        .video-block {
            position: relative;
            width: 100%;
            margin-bottom: 1em;
            border-radius: 12px;
            overflow: hidden;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
        }
        .video-block img {
            width: 100%;
            height: auto;
            border-radius: 12px;
        }
        .video-block .play-button {
            position: absolute;
            top: 50%;
            left: 50%;
            transform: translate(-50%, -50%);
            background-color: rgba(0, 0, 0, 0.7);
            color: white;
            border: none;
            border-radius: 50%;
            width: 50px;
            height: 50px;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 1.5em;
            cursor: pointer;
            transition: background-color 0.3s;
        }
        .video-block .play-button:hover {
            background-color: rgba(0, 0, 0, 0.9);
        }
        .modal {
            display: none;
            position: fixed;
            z-index: 1;
            left: 0;
            top: 0;
            width: 100%;
            height: 100%;
            overflow: auto;
            background-color: #0000003b;
            padding-top: 60px;
        }
        .modal-content {
            background-color: white;
            margin: auto;
            padding: 20px;
            border: 1px solid #888;
            width: 80%;
            max-width: 400px;
            border-radius: 12px;
            text-align: center;
            animation: zoomIn 0.5s;
        }
        .modal-thumbnail {
            width: 100%;
            border-radius: 8px;
            margin-bottom: 1em;
        }
        .modal-button {
            background-color: #75acac;
            color: white;
            padding: 15px 32px;
            font-size: 16px;
            border: none;
            border-radius: 8px;
            cursor: pointer;
            transition: background-color 0.3s;
        }
        .modal-button:hover {
            background-color: #5b9595;
        }
        .notes-content {
            margin-bottom: 1em;
        }
        @media (min-width: 600px) {
            .video-block {
                width: calc(50% - 1em); /* Two blocks per row on medium screens */
            }
        }
        @media (min-width: 900px) {
            .video-block {
                width: calc(33.333% - 1em); /* Three blocks per row on large screens */
            }
        }
        @keyframes fadeIn {
            from { opacity: 0; }
            to { opacity: 1; }
        }
        @keyframes slideIn {
            from { transform: translateX(-100%); }
            to { transform: translateX(0); }
        }
        @keyframes zoomIn {
            from { transform: scale(0); }
            to { transform: scale(1); }
        }
    </style>
</head>
<body>
    <header>
        <h1><?php echo $nm . " Language" ?></h1>
    </header>
    <div class="container">
        <div class="main-content">
            <section class="introduction">
                <div class="image-section">
                    <img src="images/course.jpg" alt="<?php echo htmlspecialchars($nm); ?>">
                </div>
                <h1 class="course-review-title"><center>Course Review</center></h1>
            </section>
            <div class="separator"></div>
            <section class="intro">
                <h2><strong>📚 Introduction</strong></h2>
                <p><?php echo $intro ?></p>
            </section>
            <div class="separator"></div>
            <section class="features">
                <h2>🌟 Features of <?php echo $nm ?></h2>
                <p><?php echo $fea ?></p>
            </section>
            <div class="separator"></div>
            <section class="books">
                <h2>📚 Recommended Books</h2>
                <p><?php echo $rec ?></p>
            </section>
        </div>
        <div class="secondary-content">
            <div class="tabs">
                <div class="tab active" onclick="showTab('overview')">📊 Overview</div>
                <div class="tab" onclick="showTab('notes')">📝 Notes</div>
            </div>
            <div id="overview" class="content active">
                <h2>🎥 Video Lectures</h2>
                <div class="video-lectures">
                    <?php foreach ($studyMaterials as $file): ?>
                            <div class="video-block">
                                <img src="images/course.jpg" alt="<?php echo htmlspecialchars($file); ?> Thumbnail">
                                <div class="play-button" onclick="showModal('<?php echo htmlspecialchars(ucwords(str_replace('_', ' ', str_replace('.mp4', '', $file)))) ?>', 'images/course.jpg', '<?php echo htmlspecialchars($file); ?>')">▶</div>
                                <p style="margin-left: 20px;"><?php echo htmlspecialchars(ucwords(str_replace('_', ' ', str_replace('.mp4', '', $file)))); ?></p>
                            </div>
                    <?php endforeach; ?>
                </div>
            </div>
            <div id="notes" class="content">
                <h2>📁 Study Materials</h2>
                <?php foreach ($pdfNotes as $pdfFile): ?>
                        <div class="notes-content">
                            <h3>📚 <?php echo htmlspecialchars(ucwords(str_replace('_', ' ', str_replace('.pdf', '', $pdfFile)))); ?></h3>
                            <button style="background-color:rgba(121, 154, 154, 0.49); padding: 10px;" onclick="window.location.href='Uploads_material/<?php echo htmlspecialchars($nm); ?>/<?php echo htmlspecialchars($pdfFile); ?>'">Open <?php echo htmlspecialchars(ucwords(str_replace('_', ' ', str_replace('.pdf', '', $pdfFile)))); ?> PDF</button>
                        </div>
                        <div class="separator"></div>
                <?php endforeach; ?>
            </div>
        </div>
    </div>

    <!-- Modal Structure -->
    <div id="lectureModal" class="modal">
        <div class="modal-content">
            <img id="modalThumbnail" class="modal-thumbnail" src="" alt="Video Thumbnail">
            <h3 id="popupMessage"></h3>
            <button class="modal-button" onclick="startLecture()">Start</button>
        </div>
    </div>

    <script>
        // Show modal when a lecture is clicked
        function showModal(lectureName, thumbnailSrc, videoFile) {
            document.getElementById('popupMessage').innerText = `Do you want to start this lecture? ${lectureName}`;
            document.getElementById('modalThumbnail').src = thumbnailSrc;
            var modal = document.getElementById("lectureModal");
            modal.style.display = "block";
            // Store the video file name in a data attribute
            modal.setAttribute('data-video', videoFile);
        }

        // Start the lecture when the "Start" button is clicked
        function startLecture() {
            var modal = document.getElementById("lectureModal");
            var videoFile = modal.getAttribute('data-video');
            modal.style.display = "none";
            window.location.href = `video.php?video=${videoFile}&id=<?php echo $course_id ?>`;
        }

        // Hide the modal if clicked outside of the modal content
        window.onclick = function(event) {
            var modal = document.getElementById("lectureModal");
            if (event.target == modal) {
                modal.style.display = "none";
            }
        }

        // Switch between tabs
        function showTab(tabId) {
            document.querySelectorAll('.tab').forEach(tab => tab.classList.remove('active'));
            document.querySelectorAll('.content').forEach(content => content.classList.remove('active'));
            document.querySelector(`#${tabId}`).classList.add('active');
            document.querySelector(`.tab[onclick="showTab('${tabId}')"]`).classList.add('active');
        }
    </script>
</body>
</html>
