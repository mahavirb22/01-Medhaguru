<?php
include "connection_db.php";

// Fetch feedback from the database
$query = "SELECT id, name, feedback FROM feedback ORDER BY created_at DESC LIMIT 3";
$result = $conn->query($query);
$feedbacks = [];

if ($result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
        $feedbacks[] = $row;
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>MedhaGuru - Enhanced Educational Platform</title>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;600&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
    <link rel="stylesheet" href="styles.css">
</head>
<body>
    <header>
        <div class="logo">MedhaGuru</div>
        <nav class="nav-links">
            <ul>
                <li><a href="#home">Home</a></li>
                <li><a href="#courses">Courses</a></li>
                <li><a href="#testimonials">Testimonials</a></li>
                <li><a href="student_login.php" class="cta">Enter</a></li>
                <li><a href="student_signup.php" class="cta">Sign Up</a></li>
            </ul>
        </nav>
        <div class="admin-button" id="adminButton">ADMIN</div>
        <div class="menu-toggle" id="menuToggle">
            <i class="fas fa-bars"></i>
        </div>
    </header>

    <section id="home">
        <div class="container">
            <h1>Welcome to Our Educational Platform</h1>
            <p>Unlock your potential with our comprehensive online courses.</p>
            <div class="slider-container">
                <div class="slider">
                    <div class="slide active">
                        <img src="images/indeximage.jpg" alt="Educational Platform">
                    </div>
                    <div class="slide">
                        <img src="images/indeximage1.jpg" alt="Coding Session">
                    </div>
                    <div class="slide">
                        <img src="images/indeximage2.jpg" alt="Virtual Classroom">
                    </div>
                    <div class="slide">
                        <img src="images/indeximage3.jpg" alt="Students Learning">
                    </div>
                </div>
            </div>
            <a href="#courses" class="button">Explore Courses</a>
        </div>
    </section>

    <section id="courses">
        <div class="container">
            <h2>Courses</h2>
            <div class="course-list">
                <div class="course-item">
                    <img src="images/c.png" alt="C Course">
                    <h3>C</h3>
                    <p>Learn the basics of C programming and build applications.</p>
                </div>
                <div class="course-item">
                    <img src="images/js.jpeg" alt="JavaScript Course">
                    <h3>JavaScript</h3>
                    <p>Master JavaScript to create interactive and dynamic web applications.</p>
                </div>
                <div class="course-item">
                    <img src="images/java.jpeg" alt="Java Course">
                    <h3>Java</h3>
                    <p>Dive into Java programming and develop robust applications.</p>
                </div>
                <div class="course-item">
                    <img src="images/c++.jpeg" alt="C++ Course">
                    <h3>C++</h3>
                    <p>Explore C++ and learn object-oriented programming concepts.</p>
                </div>
                <div class="course-item">
                    <img src="images/php1.png" alt="PHP Course">
                    <h3>PHP</h3>
                    <p>Discover PHP and develop powerful web applications.</p>
                </div>
                <div class="course-item">
                    <img src="images/python.jpeg" alt="Python Course">
                    <h3>Python</h3>
                    <p>Learn Python for data science, web development, and automation.</p>
                </div>
            </div>
        </div>
    </section>

    <section id="testimonials">
        <div class="container">
            <h2>Testimonials</h2>
            <p>Here's what our students have to say about their learning experience.</p>
            <div class="testimonial-container">
                <div class="testimonial-slider">
                    <?php foreach ($feedbacks as $index => $feedback): ?>
                        <div class="testimonial-card" id="testimonial-<?php echo $index + 1; ?>">
                            <p class="testimonial-text"><?php echo htmlspecialchars($feedback['feedback']); ?></p>
                            <div class="testimonial-author">
                                <div class="author-info">
                                    <h4><?php echo htmlspecialchars($feedback['name']); ?></h4>
                                </div>
                            </div>
                        </div>
                    <?php endforeach; ?>
                </div>
            </div>
            <div class="testimonial-controls">
                <?php foreach ($feedbacks as $index => $feedback): ?>
                    <div class="testimonial-dot <?php echo $index === 0 ? 'active' : ''; ?>" data-index="<?php echo $index; ?>"></div>
                <?php endforeach; ?>
            </div>
        </div>
    </section>

    <footer>
        <p>&copy; 2025 Medhaguru Educational Platform. All rights reserved.</p>
    </footer>

    <script src="script.js"></script>
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const menuToggle = document.getElementById('menuToggle');
            const navLinks = document.querySelector('.nav-links');

            menuToggle.addEventListener('click', function() {
                navLinks.classList.toggle('active');
            });

            // Image slider functionality
            const slides = document.querySelectorAll('.slide');
            let currentSlide = 0;

            function showSlide(n) {
                slides.forEach(slide => {
                    slide.classList.remove('active');
                });
                slides[n].classList.add('active');
            }

            function nextSlide() {
                currentSlide = (currentSlide + 1) % slides.length;
                showSlide(currentSlide);
            }

            setInterval(nextSlide, 5000);

            // Testimonial slider functionality
            const testimonialSlider = document.querySelector('.testimonial-slider');
            const testimonialCards = document.querySelectorAll('.testimonial-card');
            const dots = document.querySelectorAll('.testimonial-dot');
            let currentTestimonial = 0;

            testimonialCards.forEach((card, index) => {
                if (index !== 0) {
                    card.style.display = 'none';
                }
            });

            function showTestimonial(index) {
                dots.forEach(dot => dot.classList.remove('active'));
                dots[index].classList.add('active');

                testimonialCards[currentTestimonial].style.transform = 'translateX(-100%)';
                setTimeout(() => {
                    testimonialCards[currentTestimonial].style.display = 'none';

                    testimonialCards[index].style.display = 'block';
                    testimonialCards[index].style.transform = 'translateX(100%)';

                    setTimeout(() => {
                        testimonialCards[index].style.transform = 'translateX(0)';
                    }, 50);

                    currentTestimonial = index;
                }, 500);
            }

            dots.forEach((dot, index) => {
                dot.addEventListener('click', () => {
                    if (index !== currentTestimonial) {
                        showTestimonial(index);
                    }
                });
            });

            setInterval(() => {
                let nextTestimonial = (currentTestimonial + 1) % testimonialCards.length;
                showTestimonial(nextTestimonial);
            }, 8000);

            // Admin button functionality
            const adminButton = document.getElementById('adminButton');
            const popupOverlay = document.createElement('div');
            popupOverlay.className = 'popup-overlay';

            const popupBox = document.createElement('div');
            popupBox.className = 'popup-box';
            popupBox.innerHTML = `
                <button class="close-btn">&times;</button>
                <h3>Admin Login</h3>
                <input type="password" id="adminPassword" placeholder="Enter admin password">
                <button id="loginButton">Login</button>
                <p id="errorMessage" style="color: red; display: none;">Incorrect password</p>
            `;

            popupOverlay.appendChild(popupBox);
            document.body.appendChild(popupOverlay);

            adminButton.addEventListener('click', function() {
                popupOverlay.style.display = 'flex';
            });

            const closeBtn = popupBox.querySelector('.close-btn');
            closeBtn.addEventListener('click', function() {
                popupOverlay.style.display = 'none';
            });

            const loginButton = popupBox.querySelector('#loginButton');
            const errorMessage = popupBox.querySelector('#errorMessage');

            loginButton.addEventListener('click', function() {
                const passwordInput = document.getElementById('adminPassword').value;
                const hashedPassword = 'your_hashed_password_here';

                if (passwordInput === 'Admin@medha') {
                    window.location.href = 'admindashboard.php';
                } else {
                    errorMessage.style.display = 'block';
                }
            });
        });
    </script>
</body>
</html>
