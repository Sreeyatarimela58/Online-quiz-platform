<?php
session_start();

// Redirect to main page if not in an exam session
if (!isset($_SESSION['current_exam']) || !isset($_SESSION['current_questions']) || !isset($_SESSION['question_index'])) {
    header("Location: casestudy[1].php");
    exit();
}

// Check if time has expired
if (isset($_SESSION['exam_start_time']) && isset($_SESSION['exam_duration'])) {
    $time_elapsed = time() - $_SESSION['exam_start_time'];
    $time_remaining = $_SESSION['exam_duration'] - $time_elapsed;
    
    if ($time_remaining <= 0) {
        // Time's up! Calculate score based on answers so far
        $score = 0;
        $questions = $_SESSION['current_questions'];
        $user_answers = $_SESSION['user_answers'] ?? [];
        $detailed_results = [];
        
        foreach ($questions as $q_index => $question) {
            $user_answer = $user_answers[$q_index] ?? null;
            $is_correct = ($user_answer === $question['answer']);
            
            if ($is_correct) {
                $score++;
            }
            
            $detailed_results[] = [
                'question' => $question['question'],
                'user_answer' => $user_answer,
                'correct_answer' => $question['answer'],
                'is_correct' => $is_correct
            ];
        }
        
        // Store the score and detailed results
        if (!isset($_SESSION['previous_scores'])) {
            $_SESSION['previous_scores'] = [];
        }
        
        $_SESSION['previous_scores'][] = [
            'exam' => $_SESSION['current_exam'],
            'score' => $score,
            'total' => count($questions),
            'detailed_results' => $detailed_results
        ];
        
        // Clear exam session data except scores
        $previous_scores = $_SESSION['previous_scores'] ?? [];
        unset($_SESSION['current_exam']);
        unset($_SESSION['current_questions']);
        unset($_SESSION['question_index']);
        unset($_SESSION['user_answers']);
        unset($_SESSION['exam_start_time']);
        unset($_SESSION['exam_duration']);
        $_SESSION['previous_scores'] = $previous_scores;
        
        // Redirect to time's up page
        header("Location: Bhaavs_exam.php?time_expired=true");
        exit();
    }
}

// Get current exam data
$current_exam = $_SESSION['current_exam'];
$questions = $_SESSION['current_questions'];
$current_index = $_SESSION['question_index'];

// Handle answer submission
if (isset($_POST['submit_answer'])) {
    $selected_option = $_POST['answer'] ?? null;
    
    // Store the user's answer
    if (!isset($_SESSION['user_answers'])) {
        $_SESSION['user_answers'] = [];
    }
    
    $_SESSION['user_answers'][$current_index] = $selected_option;
    
    // Move to next question or finish exam
    if ($current_index < count($questions) - 1) {
        $_SESSION['question_index']++;
        header("Location: " . $_SERVER['PHP_SELF']);
        exit();
    } else {
        // Calculate score and detailed results
        $score = 0;
        $detailed_results = [];
        
        foreach ($questions as $q_index => $question) {
            $user_answer = $_SESSION['user_answers'][$q_index] ?? null;
            $is_correct = ($user_answer === $question['answer']);
            
            if ($is_correct) {
                $score++;
            }
            
            $detailed_results[] = [
                'question' => $question['question'],
                'user_answer' => $user_answer,
                'correct_answer' => $question['answer'],
                'is_correct' => $is_correct
            ];
        }
        
        // Store the score and detailed results
        if (!isset($_SESSION['previous_scores'])) {
            $_SESSION['previous_scores'] = [];
        }
        
        $_SESSION['previous_scores'][] = [
            'exam' => $current_exam,
            'score' => $score,
            'total' => count($questions),
            'detailed_results' => $detailed_results
        ];
        
        // Clear timer data
        unset($_SESSION['exam_start_time']);
        unset($_SESSION['exam_duration']);
        
        // Redirect to results page
        header("Location:Bhaavs_exam.php?show_results=true");
        exit();
    }
}

// Handle time expiration
if (isset($_GET['time_expired'])) {
    $latest_score = end($_SESSION['previous_scores']);
    $percentage = ($latest_score['score'] / $latest_score['total']) * 100;
    
    $result_message = "Time's up! Your exam has been automatically submitted.";
    $result_class = "text-warning";
    
    // Show results page with time expired message
    ?>
    <!DOCTYPE html>
    <html lang="en">
    <head>
        <meta charset="UTF-8">
        <title>Exam Results</title>
        <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
        <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
        <style>
            body {
                background-color: white;
            }
            
            .result-card {
                border-radius: 12px;
                border: none;
                box-shadow: 0 5px 15px rgba(0, 0, 0, 0.08);
            }
            
            .progress {
                height: 20px;
                border-radius: 10px;
            }
            
            .progress-bar {
                background-color: #EA738D;
            }
            
            .btn-primary {
                background-color: #EA738D;
                border-color: #EA738D;
            }
            
            .btn-primary:hover {
                background-color: #d45f79;
                border-color: #d45f79;
            }
            
            .result-icon {
                font-size: 5rem;
                margin-bottom: 1rem;
            }
            
            .result-details {
                max-height: 400px;
                overflow-y: auto;
                margin-top: 20px;
            }
            
            .question-item {
                padding: 15px;
                border-radius: 8px;
                margin-bottom: 15px;
                background-color: #f8f9fa;
            }
            
            .correct-answer {
                border-left: 4px solid #28a745;
            }
            
            .wrong-answer {
                border-left: 4px solid #dc3545;
            }
            
            .answer-status {
                font-weight: bold;
                margin-bottom: 5px;
            }
            
            .correct-text {
                color: #28a745;
            }
            
            .wrong-text {
                color: #dc3545;
            }
        </style>
    </head>
    <body class="d-flex justify-content-center align-items-center vh-100">
        <div class="container">
            <div class="row justify-content-center">
                <div class="col-md-10">
                    <div class="card result-card">
                        <div class="card-body p-5">
                            <div class="text-center">
                                <div class="result-icon text-warning">
                                    <i class="fas fa-hourglass-end"></i>
                                </div>
                                
                                <h2 class="card-title fw-bold mb-4">Time's Up: <?= $latest_score['exam'] ?></h2>
                                <h4 class="text-warning mb-4"><?= $result_message ?></h4>
                                
                                <div class="mb-4">
                                    <h5>Your Score: <?= $latest_score['score'] ?>/<?= $latest_score['total'] ?></h5>
                                    <div class="progress mt-3">
                                        <div class="progress-bar" role="progressbar" style="width: <?= $percentage ?>%" 
                                            aria-valuenow="<?= $percentage ?>" aria-valuemin="0" aria-valuemax="100">
                                            <?= number_format($percentage, 1) ?>%
                                        </div>
                                    </div>
                                </div>
                            </div>
                            
                            <div class="result-details">
                                <h5 class="mb-3">Question Details:</h5>
                                <?php foreach ($latest_score['detailed_results'] as $index => $result): ?>
                                    <div class="question-item <?= $result['is_correct'] ? 'correct-answer' : 'wrong-answer' ?>">
                                        <div class="answer-status">
                                            Question <?= $index + 1 ?>: 
                                            <span class="<?= $result['is_correct'] ? 'correct-text' : 'wrong-text' ?>">
                                                <?= $result['is_correct'] ? 'Correct' : 'Incorrect' ?>
                                            </span>
                                        </div>
                                        <div class="mb-2"><strong>Question:</strong> <?= $result['question'] ?></div>
                                        <div class="mb-2"><strong>Your Answer:</strong> <?= $result['user_answer'] ?? 'Not answered' ?></div>
                                        <div><strong>Correct Answer:</strong> <?= $result['correct_answer'] ?></div>
                                    </div>
                                <?php endforeach; ?>
                            </div>
                            
                            <div class="mt-5 text-center">
                                <a href="casestudy[1].php" class="btn btn-primary btn-lg px-5">
                                    <i class="fas fa-home me-2"></i>Back to Home
                                </a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        
        <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    </body>
    </html>
    <?php
    exit();
}

// Handle exam completion and results display
if (isset($_GET['show_results'])) {
    // Clear exam session data except scores
    $previous_scores = $_SESSION['previous_scores'] ?? [];
    unset($_SESSION['current_exam']);
    unset($_SESSION['current_questions']);
    unset($_SESSION['question_index']);
    unset($_SESSION['user_answers']);
    $_SESSION['previous_scores'] = $previous_scores;
    
    // Show results page
    $latest_score = end($previous_scores);
    $percentage = ($latest_score['score'] / $latest_score['total']) * 100;
    
    // Determine result message and class based on score
    if ($percentage >= 70) {
        $result_message = "Congratulations! You passed the exam.";
        $result_class = "text-success";
    } else {
        $result_message = "You didn't pass this time. Try again!";
        $result_class = "text-danger";
    }
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Exam Results</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <style>
        body {
            background-color: white;
        }
        
        .result-card {
            border-radius: 12px;
            border: none;
            box-shadow: 0 5px 15px rgba(0, 0, 0, 0.08);
        }
        
        .progress {
            height: 20px;
            border-radius: 10px;
        }
        
        .progress-bar {
            background-color: #EA738D;
        }
        
        .btn-primary {
            background-color: #EA738D;
            border-color: #EA738D;
        }
        
        .btn-primary:hover {
            background-color: #d45f79;
            border-color: #d45f79;
        }
        
        .result-icon {
            font-size: 5rem;
            margin-bottom: 1rem;
        }
        
        .result-details {
            max-height: 400px;
            overflow-y: auto;
            margin-top: 20px;
        }
        
        .question-item {
            padding: 15px;
            border-radius: 8px;
            margin-bottom: 15px;
            background-color: #f8f9fa;
        }
        
        .correct-answer {
            border-left: 4px solid #28a745;
        }
        
        .wrong-answer {
            border-left: 4px solid #dc3545;
        }
        
        .answer-status {
            font-weight: bold;
            margin-bottom: 5px;
        }
        
        .correct-text {
            color: #28a745;
        }
        
        .wrong-text {
            color: #dc3545;
        }
    </style>
</head>
<body class="d-flex justify-content-center align-items-center vh-100">
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-10">
                <div class="card result-card">
                    <div class="card-body p-5">
                        <div class="text-center">
                            <?php if ($percentage >= 70): ?>
                                <div class="result-icon text-success">
                                    <i class="fas fa-trophy"></i>
                                </div>
                            <?php else: ?>
                                <div class="result-icon text-danger">
                                    <i class="fas fa-times-circle"></i>
                                </div>
                            <?php endif; ?>
                            
                            <h2 class="card-title fw-bold mb-4">Exam Results: <?= $latest_score['exam'] ?></h2>
                            <h4 class="<?= $result_class ?> mb-4"><?= $result_message ?></h4>
                            
                            <div class="mb-4">
                                <h5>Your Score: <?= $latest_score['score'] ?>/<?= $latest_score['total'] ?></h5>
                                <div class="progress mt-3">
                                    <div class="progress-bar" role="progressbar" style="width: <?= $percentage ?>%" 
                                        aria-valuenow="<?= $percentage ?>" aria-valuemin="0" aria-valuemax="100">
                                        <?= number_format($percentage, 1) ?>%
                                    </div>
                                </div>
                            </div>
                        </div>
                        
                        <div class="result-details">
                            <h5 class="mb-3">Question Details:</h5>
                            <?php foreach ($latest_score['detailed_results'] as $index => $result): ?>
                                <div class="question-item <?= $result['is_correct'] ? 'correct-answer' : 'wrong-answer' ?>">
                                    <div class="answer-status">
                                        Question <?= $index + 1 ?>: 
                                        <span class="<?= $result['is_correct'] ? 'correct-text' : 'wrong-text' ?>">
                                            <?= $result['is_correct'] ? 'Correct' : 'Incorrect' ?>
                                        </span>
                                    </div>
                                    <div class="mb-2"><strong>Question:</strong> <?= $result['question'] ?></div>
                                    <div class="mb-2"><strong>Your Answer:</strong> <?= $result['user_answer'] ?? 'Not answered' ?></div>
                                    <div><strong>Correct Answer:</strong> <?= $result['correct_answer'] ?></div>
                                </div>
                            <?php endforeach; ?>
                        </div>
                        
                        <div class="mt-5 text-center">
                            <a href="casestudy[1].php" class="btn btn-primary btn-lg px-5">
                                <i class="fas fa-home me-2"></i>Back to Home
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
<?php
    exit();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title><?= $current_exam ?> - Question <?= $current_index + 1 ?></title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <style>
        body {
            background-color: white;
        }
        
        .question-card {
            border-radius: 12px;
            border: none;
            box-shadow: 0 5px 15px rgba(0, 0, 0, 0.08);
        }
        
        .option-label {
            display: block;
            padding: 15px;
            border-radius: 8px;
            border: 1px solid #dee2e6;
            margin-bottom: 10px;
            cursor: pointer;
            transition: all 0.2s ease;
        }
        
        .option-label:hover {
            background-color: #f8f9fa;
        }
        
        .option-input:checked + .option-label {
            background-color: #EA738D;
            color: white;
            border-color: #EA738D;
        }
        
        .option-input {
            display: none;
        }
        
        .progress {
            height: 10px;
            border-radius: 5px;
        }
        
        .progress-bar {
            background-color: #EA738D;
        }
        
        .btn-primary {
            background-color: #EA738D;
            border-color: #EA738D;
        }
        
        .btn-primary:hover {
            background-color: #d45f79;
            border-color: #d45f79;
        }
        
        .exam-header {
            background: linear-gradient(135deg, #89ABE3 0%, #EA738D 100%);
            color: white;
            border-radius: 12px 12px 0 0;
        }
        
        .timer {
            font-size: 1.2rem;
            font-weight: bold;
            color: #EA738D;
            background-color: white;
            padding: 5px 15px;
            border-radius: 20px;
            box-shadow: 0 2px 5px rgba(0,0,0,0.1);
        }
        
        .timer-warning {
            color: #dc3545;
            animation: pulse 1s infinite;
        }
        
        @keyframes pulse {
            0% { opacity: 1; }
            50% { opacity: 0.5; }
            100% { opacity: 1; }
        }
    </style>
</head>
<body>
    <div class="container py-5">
        <div class="row justify-content-center">
            <div class="col-md-8">
                <div class="card question-card">
                    <div class="card-header exam-header p-4">
                        <div class="d-flex justify-content-between align-items-center">
                            <h3 class="m-0"><?= $current_exam ?></h3>
                            <div class="d-flex align-items-center">
                                <span class="badge bg-white text-dark p-2 me-3">Question <?= $current_index + 1 ?> of <?= count($questions) ?></span>
                                <div class="timer" id="examTimer">
                                    <i class="fas fa-clock me-2"></i><span id="timeDisplay">05:00</span>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="card-body p-4">
                        <div class="progress mb-4">
                            <div class="progress-bar" role="progressbar" 
                                style="width: <?= (($current_index + 1) / count($questions)) * 100 ?>%" 
                                aria-valuenow="<?= (($current_index + 1) / count($questions)) * 100 ?>" 
                                aria-valuemin="0" aria-valuemax="100">
                            </div>
                        </div>
                        
                        <h4 class="card-title mb-4"><?= $questions[$current_index]['question'] ?></h4>
                        
                        <form method="post">
                            <div class="options-container mb-4">
                                <?php foreach ($questions[$current_index]['options'] as $option): ?>
                                    <div class="option">
                                        <input type="radio" name="answer" id="option_<?= $option ?>" value="<?= $option ?>" class="option-input" required>
                                        <label for="option_<?= $option ?>" class="option-label"><?= $option ?></label>
                                    </div>
                                <?php endforeach; ?>
                            </div>
                            
                            <div class="d-flex justify-content-between">
                                <?php if ($current_index > 0): ?>
                                    <a href="javascript:history.back()" class="btn btn-outline-secondary">
                                        <i class="fas fa-arrow-left me-2"></i>Previous
                                    </a>
                                <?php else: ?>
                                    <div></div>
                                <?php endif; ?>
                                
                                <button type="submit" name="submit_answer" class="btn btn-primary px-4">
                                    <?= ($current_index == count($questions) - 1) ? 'Finish Exam' : 'Next Question' ?>
                                    <?php if ($current_index < count($questions) - 1): ?>
                                        <i class="fas fa-arrow-right ms-2"></i>
                                    <?php else: ?>
                                        <i class="fas fa-check ms-2"></i>
                                    <?php endif; ?>
                                </button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
    
    <script>
        // Timer functionality
        document.addEventListener('DOMContentLoaded', function() {
            const timeDisplay = document.getElementById('timeDisplay');
            const timerElement = document.getElementById('examTimer');
            let timeLeft = <?= $time_remaining ?? 300 ?>; // Fallback to 5 minutes if not set
            
            function updateTimer() {
                const minutes = Math.floor(timeLeft / 60);
                const seconds = timeLeft % 60;
                timeDisplay.textContent = `${minutes.toString().padStart(2, '0')}:${seconds.toString().padStart(2, '0')}`;
                
                // Change color when less than 1 minute remains
                if (timeLeft <= 60) {
                    timerElement.classList.add('timer-warning');
                }
                
                if (timeLeft <= 0) {
                    // Time's up! Submit the form automatically
                    alert('Time is up! Your exam will be submitted automatically.');
                    window.location.href = 'Bhaavs_exam.php?time_expired=true';
                } else {
                    timeLeft--;
                    setTimeout(updateTimer, 1000);
                }
            }
            updateTimer();
        });
    </script>
    
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>