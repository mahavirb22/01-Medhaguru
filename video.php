<?php
$course_id = false;
include "connection_db.php";

if (isset($_GET['id'])) {
    $course_id = $_GET['id'];
}

$query = "SELECT course_name FROM courses WHERE course_id = '$course_id'";
$result = $conn->query($query);

if ($result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
        $course_name = $row['course_name'];
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Video Lectures</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            display: flex;
            flex-direction: column;
            align-items: center;
            height: 100vh;
            margin: 0;
            background-color: #f0f0f0;
        }

        header {
            width: 100%;
            background-color: #75acac;
            color: white;
            padding: 20px 0;
            text-align: center;
            font-size: 24px;
            font-weight: bold;
            box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
        }

        .container {
            display: flex;
            flex-direction: column;
            align-items: center;
            width: 80%;
            padding: 20px;
            flex-grow: 1;
        }

        .video-container {
            display: flex;
            flex-direction: column;
            align-items: center;
            padding: 20px;
            margin: 20px;
            background-color: #fff;
            border-radius: 10px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
            animation: fadeIn 1s ease-in-out;
            position: relative;
            width: 67.5%;
            height: 52.5%;
        }

        .video-title {
            margin-bottom: 10px;
            font-size: 20px;
            font-weight: bold;
            position: relative;
            width: 100%;
            text-align: center;
        }

        .video-title::before {
            content: '📚';
            margin-right: 5px;
            font-size: 24px;
        }

        .video-controls {
            margin-top: 20px;
            display: flex;
            justify-content: center;
            align-items: center;
            width: 100%;
        }

        button {
            margin: 5px;
            padding: 10px;
            font-size: 16px;
            cursor: pointer;
            background-color: #fff;
            border: 1px solid #ccc;
            border-radius: 5px;
            transition: background-color 0.3s, transform 0.3s;
        }

        button:hover {
            background-color: #e0e0e0;
            transform: scale(1.1);
        }

        .separator {
            width: 90%;
            height: 4px;
            background-color: #ccc;
            margin: 20px 0;
        }

        .sticky-circles {
            position: absolute;
            top: 10px;
            right: 10px;
            display: flex;
            gap: 10px;
            z-index: 100;
        }

        .sticky-circle {
            width: 30px;
            height: 30px;
            border-radius: 50%;
            cursor: pointer;
            box-shadow: 0 2px 5px rgba(0, 0, 0, 0.2);
            transition: transform 0.3s;
        }

        .sticky-circle:hover {
            transform: scale(1.1);
        }

        .red-circle {
            background-color: #ff6b6b;
        }

        .yellow-circle {
            background-color: #ffd93d;
        }

        .green-circle {
            background-color: #6bff8a;
        }

        .sticky-popup {
            display: none;
            position: absolute;
            top: 50px;
            right: 10px;
            width: 250px;
            background-color: #fff;
            border-radius: 10px;
            padding: 15px;
            box-shadow: 0 4px 15px rgba(0, 0, 0, 0.2);
            z-index: 101;
            animation: fadeIn 0.3s ease-in-out;
        }

        .sticky-popup textarea {
            width: 90%;
            min-height: 120px;
            padding: 10px;
            font-size: 16px;
            color: #fff;
            border: 1px solid #ccc;
            border-radius: 5px;
            resize: none;
            margin-bottom: 10px;
        }

        .sticky-popup textarea:focus {
            outline: none;
            border-color: #4CAF50;
        }

        .sticky-popup button {
            background-color: #333;
            color: white;
            border: none;
            padding: 5px 10px;
            border-radius: 5px;
            cursor: pointer;
        }

        .sticky-popup button:hover {
            background-color: #555;
        }

        .red-popup {
            border-top: 5px solid #ff6b6b;
        }

        .yellow-popup {
            border-top: 5px solid #ffd93d;
        }

        .green-popup {
            border-top: 5px solid #6bff8a;
        }

        @keyframes fadeIn {
            from {
                opacity: 0;
            }
            to {
                opacity: 1;
            }
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
            background-color: rgba(0, 0, 0, 0.5);
        }

        .modal-content {
            background-color: #fff;
            margin: 15% auto;
            padding: 20px;
            border: 1px solid #888;
            width: 80%;
            max-width: 500px;
            border-radius: 10px;
            text-align: center;
        }

        .close {
            color: #aaa;
            float: right;
            font-size: 28px;
            font-weight: bold;
        }

        .close:hover,
        .close:focus {
            color: black;
            text-decoration: none;
            cursor: pointer;
        }

        .ok-button {
            background-color: #75acac;
            color: white;
            border: none;
            padding: 10px 20px;
            border-radius: 5px;
            cursor: pointer;
            transition: background-color 0.3s;
        }

        .ok-button:hover {
            background-color: #5b9595;
        }

        .sticky-note {
            position: absolute;
            width: 150px;
            padding: 10px;
            border-radius: 10px;
            box-shadow: 0 4px 15px rgba(0, 0, 0, 0.2);
            cursor: move;
            user-select: none;
            font-family: 'Arial', sans-serif;
            transition: transform 0.3s;
        }

        .sticky-note p {
            margin: 0;
            word-wrap: break-word;
            color: #333;
        }

        .sticky-note:hover {
            transform: scale(1.05);
        }

        /* Light colors for notes */
        .sticky-note[style*="background-color: red"] {
            background-color: #ff6b6b !important;
        }

        .sticky-note[style*="background-color: yellow"] {
            background-color: #ffd93d !important;
        }

        .sticky-note[style*="background-color: green"] {
            background-color: #6bff8a !important;
        }

        .delete-btn {
            background-color: transparent;
            border: none;
            color: #000;
            font-size: 12px;
            position: absolute;
            top: 5px;
            right: 5px;
            cursor: pointer;
            padding: 2px 5px;
            border-radius: 50%;
            transition: background-color 0.3s;
        }

        .delete-btn:hover {
            background-color: #ffcccc;
        }
    </style>
</head>
<body>
    <header>
        <?php echo $course_name; ?> Language Course
    </header>
    <div class="container">
        <div class="video-container">
            <div class="video-title">Video Lecture</div>

            <div class="sticky-circles">
                <div class="sticky-circle red-circle" id="redCircle"></div>
                <div class="sticky-circle yellow-circle" id="yellowCircle"></div>
                <div class="sticky-circle green-circle" id="greenCircle"></div>
            </div>

            <div class="sticky-popup red-popup" id="redPopup">
                <textarea id="red"></textarea>
                <button class="save-btn">Save</button>
                <button class="close-btn">Close</button>
            </div>

            <div class="sticky-popup yellow-popup" id="yellowPopup">
                <textarea id="yellow"></textarea>
                <button class="save-btn">Save</button>
                <button class="close-btn">Close</button>
            </div>

            <div class="sticky-popup green-popup" id="greenPopup">
                <textarea id="green"></textarea>
                <button class="save-btn">Save</button>
                <button class="close-btn">Close</button>
            </div>

            <video id="courseVideo" width="100%" height="auto" controls autoplay>
                <source id="videoSource" type="video/mp4">
                Your browser does not support the video tag.
            </video>
            <div class="video-controls">
                <button id="rewindBtn">⏪</button>
                <button id="replayBtn">⟲</button>
                <button id="playPauseBtn">⏯</button>
                <button id="forwardBtn">⏩</button>
            </div><br>
            <div class="progress-tracker"><br><br>
                Progress: <span id="progressPercentage">0%</span> Complete<br><br>
                <div class="progress-bar">
                    <div id="progressBar"></div>
                </div>
            </div>
        </div>
    </div>

    <div id="completionModal" class="modal">
        <div class="modal-content">
            <span class="close">&times;</span>
            <h2>Congratulations!</h2>
            <p>Thank you for completing the lecture.</p>
            <a href=""><button class="ok-button">Let's solve a small quiz</button></a>
        </div>
    </div>

    <script>
        document.addEventListener('DOMContentLoaded', function () {
            const video = document.getElementById('courseVideo');
            const rewindBtn = document.getElementById('rewindBtn');
            const replayBtn = document.getElementById('replayBtn');
            const playPauseBtn = document.getElementById('playPauseBtn');
            const forwardBtn = document.getElementById('forwardBtn');
            const progressBar = document.getElementById('progressBar');
            const progressPercentage = document.getElementById('progressPercentage');
            const modal = document.getElementById('completionModal');
            const closeBtn = document.getElementsByClassName('close')[0];
            const okButton = document.querySelector('.ok-button');

            // Sticky note elements
            const redCircle = document.getElementById('redCircle');
            const yellowCircle = document.getElementById('yellowCircle');
            const greenCircle = document.getElementById('greenCircle');

            const redPopup = document.getElementById('redPopup');
            const yellowPopup = document.getElementById('yellowPopup');
            const greenPopup = document.getElementById('greenPopup');

            const redtxt = document.getElementById('red');
            const yellowtxt = document.getElementById('yellow');
            const greentxt = document.getElementById('green');

            const closeBtns = document.querySelectorAll('.close-btn');
            const saveBtns = document.querySelectorAll('.save-btn');

            function closeAllPopups() {
                redPopup.style.display = 'none';
                yellowPopup.style.display = 'none';
                greenPopup.style.display = 'none';
            }

            redCircle.addEventListener('click', function () {
                closeAllPopups();
                redPopup.style.display = 'block';
                redtxt.style.backgroundColor = '#ff6b6b';
            });

            yellowCircle.addEventListener('click', function () {
                closeAllPopups();
                yellowPopup.style.display = 'block';
                yellowtxt.style.backgroundColor = '#ffd93d';
            });

            greenCircle.addEventListener('click', function () {
                closeAllPopups();
                greenPopup.style.display = 'block';
                greentxt.style.backgroundColor = '#6bff8a';
            });

            closeBtns.forEach(button => {
                button.addEventListener('click', closeAllPopups);
            });

            saveBtns.forEach(button => {
                button.addEventListener('click', function () {
                    const color = button.closest('.sticky-popup').classList[1].split('-')[0];
                    const note = button.previousElementSibling.value;
                    const videoName = videoSource.src.split('/').pop();
                    const positionTop = '0px';
                    const positionLeft = '0px';
                    saveNoteToDatabase(color, note, videoName, positionTop, positionLeft);
                    alert('Note saved!');
                    closeAllPopups();
                });
            });

            document.addEventListener('click', function (event) {
                if (!event.target.closest('.sticky-popup') &&
                    !event.target.closest('.sticky-circle')) {
                    closeAllPopups();
                }
            });

            rewindBtn.addEventListener('click', function () {
                video.currentTime -= 10;
            });

            replayBtn.addEventListener('click', function () {
                video.currentTime = 0;
                video.play();
            });

            playPauseBtn.addEventListener('click', function () {
                if (video.paused) {
                    video.play();
                    playPauseBtn.textContent = '⏸';
                } else {
                    video.pause();
                    playPauseBtn.textContent = '▶';
                }
            });

            forwardBtn.addEventListener('click', function () {
                video.currentTime += 10;
            });

            video.addEventListener('timeupdate', function () {
                const percentage = (video.currentTime / video.duration) * 100;
                progressBar.style.width = percentage + '%';
                progressPercentage.textContent = Math.round(percentage) + '%';
            });

            video.addEventListener('ended', function () {
                modal.style.display = 'block';

                // Update the href attribute of the okButton with the course_id
                const courseId = '<?php echo htmlspecialchars($course_id); ?>';
                okButton.closest('a').href = `flip.php?id=${courseId}`;
            });

            closeBtn.addEventListener('click', function () {
                modal.style.display = 'none';
            });

            okButton.addEventListener('click', function () {
                modal.style.display = 'none';
            });

            window.addEventListener('click', function (event) {
                if (event.target == modal) {
                    modal.style.display = 'none';
                }
            });

            const textareas = document.querySelectorAll('textarea');
            textareas.forEach(textarea => {
                textarea.addEventListener('input', function () {
                    this.style.height = 'auto';
                    this.style.height = (this.scrollHeight) + 'px';
                });
            });

            const urlParams = new URLSearchParams(window.location.search);
            const videoFile = urlParams.get('video');

            if (videoFile) {
                videoSource.src = `Uploads/<?php echo $course_name; ?>/${videoFile}`;
                video.load();
            } else {
                alert('Lecture will start soon.');
                window.location.href = 'coursepage.php';
            }

            loadNotesFromDatabase();

            function saveNoteToDatabase(color, note, videoName, positionTop, positionLeft) {
                const xhr = new XMLHttpRequest();
                xhr.open("POST", "save_note.php", true);
                xhr.setRequestHeader("Content-Type", "application/x-www-form-urlencoded");
                xhr.onreadystatechange = function () {
                    if (xhr.readyState === 4 && xhr.status === 200) {
                        const response = JSON.parse(xhr.responseText);
                        createOrUpdateStickyNote({ id: response.id, color, note, video_name: videoName, position_top: positionTop, position_left: positionLeft });
                    }
                };
                xhr.send(`color=${color}&note=${note}&videoName=${videoName}&positionTop=${positionTop}&positionLeft=${positionLeft}`);
            }

            function loadNotesFromDatabase() {
                const videoName = videoSource.src.split('/').pop();
                const xhr = new XMLHttpRequest();
                xhr.open("GET", `load_notes.php?videoName=${videoName}`, true);
                xhr.onreadystatechange = function () {
                    if (xhr.readyState === 4 && xhr.status === 200) {
                        const notes = JSON.parse(xhr.responseText);
                        notes.forEach(note => {
                            createOrUpdateStickyNote(note);
                        });
                    }
                };
                xhr.send();
            }

            window.addEventListener('load', loadNotesFromDatabase);

            function createOrUpdateStickyNote(note) {
                let stickyNote = document.querySelector(`.sticky-note[data-id='${note.id}']`);
                if (!stickyNote) {
                    stickyNote = document.createElement('div');
                    stickyNote.classList.add('sticky-note');
                    stickyNote.setAttribute('data-id', note.id);
                    document.body.appendChild(stickyNote);
                }
                stickyNote.style.backgroundColor = note.color;
                stickyNote.style.top = note.position_top;
                stickyNote.style.left = note.position_left;
                stickyNote.innerHTML = `
                    <p>${note.note}</p>
                    <button class="delete-btn">✖</button>
                `;

                // Add event listener to the delete button
                const deleteBtn = stickyNote.querySelector('.delete-btn');
                deleteBtn.addEventListener('click', function () {
                    deleteNoteFromDatabase(note.id);
                    stickyNote.remove();
                });

                makeDraggable(stickyNote, note);
            }

            function deleteNoteFromDatabase(noteId) {
                const xhr = new XMLHttpRequest();
                xhr.open("POST", "delete_note.php", true);
                xhr.setRequestHeader("Content-Type", "application/x-www-form-urlencoded");
                xhr.onreadystatechange = function () {
                    if (xhr.readyState === 4 && xhr.status === 200) {
                        console.log("Note deleted successfully");
                        window.location.replace(window.location.href);
                    }
                };
                xhr.send(`id=${noteId}`);
            }

            function makeDraggable(element, note) {
                let offsetX, offsetY;

                element.addEventListener('mousedown', function (e) {
                    offsetX = e.clientX - element.offsetLeft;
                    offsetY = e.clientY - element.offsetTop;
                    document.addEventListener('mousemove', onMouseMove);
                    document.addEventListener('mouseup', onMouseUp);
                });

                function onMouseMove(e) {
                    element.style.left = (e.clientX - offsetX) + 'px';
                    element.style.top = (e.clientY - offsetY) + 'px';
                }

                function onMouseUp() {
                    document.removeEventListener('mousemove', onMouseMove);
                    document.removeEventListener('mouseup', onMouseUp);
                    note.position_top = element.style.top;
                    note.position_left = element.style.left;
                    saveNoteToDatabase(note.color, note.note, note.video_name, note.position_top, note.position_left);
                }
            }
        });
    </script>
</body>
</html>
