<?php
session_start();
include "connection_db.php";

if (isset($_GET['id'])) {
    $course_id = $_GET['id'];
    $_SESSION['course_id'] = $course_id; // Store course_id in the session
}

$query = "SELECT course_name FROM courses WHERE course_id = '$course_id'";
$result = $conn->query($query);

if ($result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
        $_SESSION['course_name'] = $row['course_name']; // Corrected variable name
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0"/>
  <title>Learning Quiz Challenge</title>
  <link href="https://cdnjs.cloudflare.com/ajax/libs/tailwindcss/2.2.19/tailwind.min.css" rel="stylesheet"/>
  <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;600;700&display=swap" rel="stylesheet"/>
  <style>
    body {
      font-family: 'Poppins', sans-serif;
      background: white;
    }
    .flip-card {
      perspective: 1000px;
      width: 100%;
      max-width: 500px;
      height: 500px;
    }
    .flip-card-inner {
      position: relative;
      width: 100%;
      height: 100%;
      text-align: center;
      transition: transform 0.6s;
      transform-style: preserve-3d;
    }
    .flip-card-front, .flip-card-back {
      position: absolute;
      width: 100%;
      height: 100%;
      backface-visibility: hidden;
      display: flex;
      flex-direction: column;
      justify-content: center;
      align-items: center;
      border-radius: 20px;
      box-shadow: 0 10px 25px rgba(0,0,0,0.2);
    }
    .flip-card-front {
      background-color: #75acac3e;
      color: black;
      z-index: 2;
      border: 1px solid #e0e0e0;
    }
    .flip-card-back {
      transform: rotateY(180deg);
    }
    .flipped .flip-card-inner {
      transform: rotateY(180deg);
    }
    .option-btn {
      transition: all 0.3s ease;
      border: 2px solid transparent;
    }
    .option-btn:hover {
      transform: scale(1.05);
      border-color: #5b9595;
    }
    .option-btn.selected {
      background-color: #5b9595;
      color: white;
    }
    #answer-btn {
      background-color: #75acacc8;
    }
    #answer-btn:hover {
      background-color: #5b9595e4;
    }
  </style>
</head>
<body class="flex items-center justify-center min-h-screen p-4 bg-white">
  <div class="w-full max-w-md">
    <div class="text-center mb-6">
      <h1 class="text-3xl font-bold text-gray-800 mb-4 drop-shadow-lg" style="color: #fff; padding: 15px; display: block; background-color: #75acac;">Learning Quiz Challenge</h1>
      <p class="text-gray-700 text-lg px-4 mb-6 opacity-80">
        Answer the questions carefully and challenge yourself to learn something new. Each question will help you grow!
      </p>
    </div>

    <div id="quiz-card" class="flip-card">
      <div class="flip-card-inner">
        <!-- Front Side -->
        <div class="flip-card-front p-6 bg-white shadow-lg rounded-lg">
          <div class="mb-6 text-center">
            <span id="question-number" class="text-xl font-semibold text-gray-600 block mb-2"></span>
            <h2 id="question" class="text-2xl font-bold text-gray-900 mb-6"></h2>
          </div>
          <div id="options" class="space-y-4"></div>
          <button id="answer-btn" class="mt-6 w-full bg-black text-white py-3 rounded-lg font-bold hover:bg-gray-800 transition duration-300">Submit Answer</button>
        </div>

        <!-- Back Side -->
        <div id="feedback-card" class="flip-card-back p-6 rounded-lg">
          <h3 id="feedback-text" class="text-2xl font-bold mb-4"></h3>
          <p id="correct-answer" class="mt-2 text-lg"></p>
          <button id="next-question" class="mt-6 w-full bg-green-500 text-white py-3 rounded-lg font-bold hover:bg-green-600 transition duration-300">Next Question</button>
        </div>
      </div>
    </div>
  </div>

  <script>
    const quizCard = document.getElementById('quiz-card');
    const questionNumberElement = document.getElementById('question-number');
    const questionElement = document.getElementById('question');
    const optionsContainer = document.getElementById('options');
    const answerBtn = document.getElementById('answer-btn');
    const feedbackText = document.getElementById('feedback-text');
    const correctAnswerElement = document.getElementById('correct-answer');
    const nextQuestionBtn = document.getElementById('next-question');

    let quizQuestions = [];
    let currentQuestionIndex = 0;
    let selectedOption = null;
    let score = 0;

    async function fetchQuestions() {
      try {
        const response = await fetch('fetch_questions.php');
        if (!response.ok) throw new Error('Failed to fetch questions');
        quizQuestions = await response.json();
        if (!quizQuestions.length) throw new Error('No questions returned');
        renderQuestion();
      } catch (error) {
        console.error('Error fetching questions:', error);
        alert('Could not load quiz questions.');
      }
    }

    function renderQuestion() {
      const question = quizQuestions[currentQuestionIndex];
      questionNumberElement.textContent = `Question ${currentQuestionIndex + 1} of ${quizQuestions.length}`;
      questionElement.textContent = question.question;
      optionsContainer.innerHTML = '';
      selectedOption = null;
      answerBtn.disabled = false;

      question.options.forEach(option => {
        const optionBtn = document.createElement('button');
        optionBtn.textContent = option;
        optionBtn.setAttribute('data-value', option);
        optionBtn.classList.add('option-btn', 'w-full', 'py-3', 'rounded-lg', 'text-white', 'bg-gray-800', 'hover:bg-gray-700');

        optionBtn.addEventListener('click', () => {
          document.querySelectorAll('.option-btn').forEach(btn => btn.classList.remove('selected'));
          optionBtn.classList.add('selected');
          selectedOption = optionBtn.getAttribute('data-value');
        });

        optionsContainer.appendChild(optionBtn);
      });

      quizCard.classList.remove('flipped');
      feedbackText.classList.remove('text-green-900', 'text-red-900');
      const feedbackCard = document.querySelector('.flip-card-back');
      feedbackCard.classList.remove('bg-green-400', 'bg-red-400');
    }

    answerBtn.addEventListener('click', () => {
      if (!selectedOption) {
        alert('Please select an answer.');
        return;
      }

      const currentQuestion = quizQuestions[currentQuestionIndex];
      const feedbackCardElement = document.querySelector('.flip-card-back');
      const isCorrect = selectedOption.trim().toLowerCase() === currentQuestion.correctAnswer.trim().toLowerCase();

      // DEBUG
      console.log("Selected Option:", selectedOption);
      console.log("Correct Answer:", currentQuestion.correctAnswer);

      if (isCorrect) {
        feedbackCardElement.classList.add('bg-green-400');
        feedbackText.textContent = '🎉 Correct Answer!';
        feedbackText.classList.add('text-green-900');
        correctAnswerElement.textContent = 'Well done! You got it right.';
      } else {
        feedbackCardElement.classList.add('bg-red-400');
        feedbackText.textContent = '❌ Incorrect Answer';
        feedbackText.classList.add('text-red-900');
        correctAnswerElement.textContent = `Correct Answer: ${currentQuestion.correctAnswer}`;
      }

      quizCard.classList.add('flipped');
      answerBtn.disabled = true;
    });

    nextQuestionBtn.addEventListener('click', () => {
      const currentQuestion = quizQuestions[currentQuestionIndex];
      const isCorrect = selectedOption?.trim().toLowerCase() === currentQuestion.correctAnswer.trim().toLowerCase();
      if (isCorrect) score++;

      currentQuestionIndex++;

      if (currentQuestionIndex < quizQuestions.length) {
        renderQuestion();
      } else {
        setTimeout(() => {
          alert(`🎉 Quiz completed!\n✅ Correct Answers: ${score} / ${quizQuestions.length}`);
          window.location.href = 'studentdashboard.php';
        }, 300);
      }
    });

    fetchQuestions();
  </script>
</body>
</html>
