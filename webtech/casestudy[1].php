<?php
session_start();
function showLoginPage()
{
    echo '<!DOCTYPE html>
    <html lang="en">
    <head>
        <meta charset="UTF-8">
        <title>Login</title>
        <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
        <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
        <style>
            body {
                background-color: white;
            }
            .login-card {
                border-radius: 12px;
                border: none;
                box-shadow: 0 5px 15px rgba(0, 0, 0, 0.08);
                background: linear-gradient(135deg, #89ABE3 0%, #EA738D 100%);
                color: white;
                padding: 2rem;
            }
            .form-control {
                border-radius: 8px;
                padding: 12px;
                margin-bottom: 1rem;
                border: none;
            }
            .form-control:focus {
                box-shadow: 0 0 0 0.25rem rgba(234, 115, 141, 0.25);
            }
            .btn-primary {
                background-color: white;
                border-color: white;
                color: #EA738D;
                padding: 12px 30px;
                border-radius: 8px;
                font-weight: bold;
                transition: all 0.3s ease;
            }
            .btn-primary:hover {
                background-color: #f8f9fa;
                border-color: #f8f9fa;
                color: #d45f79;
                transform: translateY(-2px);
            }
            .login-icon {
                font-size: 3rem;
                margin-bottom: 1rem;
            }
        </style>
    </head>
    <body class="d-flex justify-content-center align-items-center vh-100">
        <div class="container">
            <div class="row justify-content-center">
                <div class="col-md-6 col-lg-5">
                    <div class="login-card text-center">
                        <div class="login-icon">
                            <i class="fas fa-graduation-cap"></i>
                        </div>
                        <h2 class="mb-4 fw-bold">Welcome to Online Exam</h2>
                        <p class="mb-4">Please login to start your exam</p>
                        <form method="post">
                            <div class="mb-3">
                                <input type="text" name="name" placeholder="Enter Name" required class="form-control">
                            </div>
                            <div class="mb-4">
                                <input type="email" name="email" placeholder="Enter Email" required class="form-control">
                            </div>
                            <button type="submit" name="login" class="btn btn-primary w-100">
                                <i class="fas fa-sign-in-alt me-2"></i>Login
                            </button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </body>
    </html>';
}

// Exam questions data - moved to a global array for access from both files
$exam_questions = [
    "Math Quiz" => [
        ["question" => "If a train travels at 60 mph for 2.5 hours, how far does it travel?", "options" => ["120 miles", "150 miles", "180 miles", "200 miles"], "answer" => "150 miles"],
        ["question" => "Solve for x: 3x + 7 = 22", "options" => ["3", "5", "7", "8"], "answer" => "5"],
        ["question" => "What is the area of a triangle with base 8 units and height 12 units?", "options" => ["24 sq units", "48 sq units", "96 sq units", "144 sq units"], "answer" => "48 sq units"],
        ["question" => "If 15% of a number is 45, what is the number?", "options" => ["250", "300", "350", "400"], "answer" => "300"],
        ["question" => "What is the sum of the angles in a hexagon?", "options" => ["540°", "600°", "720°", "800°"], "answer" => "720°"],
        ["question" => "Simplify: (4² × 2³) ÷ 8", "options" => ["8", "12", "16", "24"], "answer" => "8"],
        ["question" => "What is the probability of rolling a sum of 7 with two dice?", "options" => ["1/6", "1/8", "1/12", "1/36"], "answer" => "1/6"],
        ["question" => "If a rectangle's length is triple its width and its perimeter is 32 units, what is its width?", "options" => ["3 units", "4 units", "6 units", "8 units"], "answer" => "4 units"],
        ["question" => "What is the value of π rounded to 4 decimal places?", "options" => ["3.1415", "3.1416", "3.1417", "3.1418"], "answer" => "3.1416"],
        ["question" => "Solve: log₂(x) = 5", "options" => ["16", "25", "32", "64"], "answer" => "32"]
    ],
    "General Knowledge" => [
        ["question" => "Which element has the chemical symbol 'Au'?", "options" => ["Silver", "Gold", "Copper", "Aluminum"], "answer" => "Gold"],
        ["question" => "Who painted the ceiling of the Sistine Chapel?", "options" => ["Leonardo da Vinci", "Michelangelo", "Raphael", "Donatello"], "answer" => "Michelangelo"],
        ["question" => "What is the smallest prime number greater than 100?", "options" => ["101", "103", "107", "109"], "answer" => "101"],
        ["question" => "Which planet has the most moons in our solar system?", "options" => ["Jupiter", "Saturn", "Uranus", "Neptune"], "answer" => "Saturn"],
        ["question" => "Who wrote 'The Art of War'?", "options" => ["Sun Tzu", "Confucius", "Lao Tzu", "Buddha"], "answer" => "Sun Tzu"],
        ["question" => "What is the hardest natural substance on Earth?", "options" => ["Gold", "Iron", "Diamond", "Platinum"], "answer" => "Diamond"],
        ["question" => "Which country invented paper?", "options" => ["Japan", "India", "China", "Egypt"], "answer" => "China"],
        ["question" => "What is the speed of light in miles per second (approximate)?", "options" => ["156,000", "186,000", "196,000", "206,000"], "answer" => "186,000"],
        ["question" => "Who discovered penicillin?", "options" => ["Alexander Fleming", "Louis Pasteur", "Robert Koch", "Joseph Lister"], "answer" => "Alexander Fleming"],
        ["question" => "What percentage of the Earth's surface is covered by water?", "options" => ["51%", "61%", "71%", "81%"], "answer" => "71%"]
    ],
    "Programming Basics" => [
        ["question" => "What is the time complexity of binary search?", "options" => ["O(n)", "O(log n)", "O(n²)", "O(1)"], "answer" => "O(log n)"],
        ["question" => "Which of these is not a valid HTTP request method?", "options" => ["GET", "POST", "FETCH", "PUT"], "answer" => "FETCH"],
        ["question" => "What does SQL stand for?", "options" => ["Structured Query Language", "Simple Query Language", "Standard Query Logic", "System Query Language"], "answer" => "Structured Query Language"],
        ["question" => "Which data structure uses LIFO?", "options" => ["Queue", "Stack", "Array", "Tree"], "answer" => "Stack"],
        ["question" => "What is the result of 2 + '2' in JavaScript?", "options" => ["4", "'22'", "22", "Error"], "answer" => "'22'"],
        ["question" => "Which is not a JavaScript data type?", "options" => ["undefined", "boolean", "float", "symbol"], "answer" => "float"],
        ["question" => "What does API stand for?", "options" => ["Application Programming Interface", "Advanced Programming Interface", "Application Process Integration", "Advanced Process Implementation"], "answer" => "Application Programming Interface"],
        ["question" => "Which symbol is used for single-line comments in Python?", "options" => ["//", "#", "/*", "--"], "answer" => "#"],
        ["question" => "What is the primary function of CSS?", "options" => ["Server scripting", "Database management", "Styling web pages", "Network protocols"], "answer" => "Styling web pages"],
        ["question" => "Which protocol is used for secure data transmission over the web?", "options" => ["HTTP", "FTP", "HTTPS", "SMTP"], "answer" => "HTTPS"]
    ],
    "Science Quiz" => [
        ["question" => "What is the atomic number of Carbon?", "options" => ["4", "6", "8", "12"], "answer" => "6"],
        ["question" => "Which part of the human brain is responsible for balance and coordination?", "options" => ["Cerebrum", "Cerebellum", "Medulla", "Hypothalamus"], "answer" => "Cerebellum"],
        ["question" => "What is the process by which plants convert light energy into chemical energy?", "options" => ["Photosynthesis", "Respiration", "Fermentation", "Oxidation"], "answer" => "Photosynthesis"],
        ["question" => "What is the unit of electrical resistance?", "options" => ["Volt", "Ampere", "Ohm", "Watt"], "answer" => "Ohm"],
        ["question" => "Which subatomic particle has a negative charge?", "options" => ["Proton", "Neutron", "Electron", "Positron"], "answer" => "Electron"],
        ["question" => "What is the chemical formula for sulfuric acid?", "options" => ["H2SO3", "H2SO4", "HSO4", "H2S2O7"], "answer" => "H2SO4"],
        ["question" => "What is the speed of sound in air at sea level (approximate)?", "options" => ["343 m/s", "443 m/s", "543 m/s", "643 m/s"], "answer" => "343 m/s"],
        ["question" => "Which blood type is known as the universal donor?", "options" => ["A+", "B-", "O-", "AB+"], "answer" => "O-"],
        ["question" => "What is the half-life of Carbon-14?", "options" => ["4,730 years", "5,730 years", "6,730 years", "7,730 years"], "answer" => "5,730 years"],
        ["question" => "Which element has the highest melting point?", "options" => ["Tungsten", "Platinum", "Diamond", "Titanium"], "answer" => "Tungsten"]
    ],
    "History Quiz" => [
        ["question" => "In which year did the Russian Revolution begin?", "options" => ["1905", "1917", "1921", "1925"], "answer" => "1917"],
        ["question" => "Who was the first Emperor of China?", "options" => ["Qin Shi Huang", "Sun Yat-sen", "Kublai Khan", "Wu Zetian"], "answer" => "Qin Shi Huang"],
        ["question" => "Which ancient wonder was located in Alexandria?", "options" => ["Colossus of Rhodes", "Hanging Gardens", "Lighthouse", "Temple of Artemis"], "answer" => "Lighthouse"],
        ["question" => "When did the Renaissance period begin (approximately)?", "options" => ["12th century", "14th century", "16th century", "18th century"], "answer" => "14th century"],
        ["question" => "Who was the longest-reigning British monarch?", "options" => ["Victoria", "Elizabeth II", "George III", "Henry VIII"], "answer" => "Elizabeth II"],
        ["question" => "Which empire was the largest in history?", "options" => ["Roman Empire", "Mongol Empire", "British Empire", "Persian Empire"], "answer" => "British Empire"],
        ["question" => "When was the Declaration of Independence signed?", "options" => ["July 2, 1776", "July 4, 1776", "August 2, 1776", "September 4, 1776"], "answer" => "August 2, 1776"],
        ["question" => "Who was the first woman to win a Nobel Prize?", "options" => ["Mother Teresa", "Marie Curie", "Pearl Buck", "Jane Addams"], "answer" => "Marie Curie"],
        ["question" => "Which civilization built the Machu Picchu?", "options" => ["Aztecs", "Mayans", "Incas", "Olmecs"], "answer" => "Incas"],
        ["question" => "When did the Berlin Wall fall?", "options" => ["1987", "1988", "1989", "1990"], "answer" => "1989"]
    ],
    "Geography Quiz" => [
        ["question" => "What is the deepest point in the Earth's oceans?", "options" => ["Mariana Trench", "Puerto Rico Trench", "Java Trench", "Tonga Trench"], "answer" => "Mariana Trench"],
        ["question" => "Which country has the most time zones?", "options" => ["Russia", "United States", "France", "China"], "answer" => "France"],
        ["question" => "What is the smallest country by area?", "options" => ["Monaco", "Vatican City", "San Marino", "Nauru"], "answer" => "Vatican City"],
        ["question" => "Which river flows through the most countries?", "options" => ["Nile", "Amazon", "Danube", "Rhine"], "answer" => "Danube"],
        ["question" => "What is the highest waterfall in the world?", "options" => ["Niagara Falls", "Victoria Falls", "Angel Falls", "Iguazu Falls"], "answer" => "Angel Falls"],
        ["question" => "Which country has the longest coastline?", "options" => ["Russia", "Canada", "Indonesia", "Norway"], "answer" => "Canada"],
        ["question" => "What is the largest desert by area?", "options" => ["Sahara", "Arabian", "Antarctic", "Arctic"], "answer" => "Antarctic"],
        ["question" => "Which mountain range is the longest in the world?", "options" => ["Himalayas", "Rockies", "Andes", "Alps"], "answer" => "Andes"],
        ["question" => "What percentage of the world's freshwater is in Antarctica?", "options" => ["50%", "60%", "70%", "90%"], "answer" => "90%"],
        ["question" => "Which city is located on two continents?", "options" => ["Istanbul", "Moscow", "Cairo", "Dubai"], "answer" => "Istanbul"]
    ],
    "Movies Quiz" => [
        ["question" => "Which film won the first Academy Award for Best Picture?", "options" => ["Wings", "Sunrise", "The Jazz Singer", "The Broadway Melody"], "answer" => "Wings"],
        ["question" => "Who directed 'Pulp Fiction'?", "options" => ["Martin Scorsese", "Quentin Tarantino", "Steven Spielberg", "Francis Ford Coppola"], "answer" => "Quentin Tarantino"],
        ["question" => "Which actor has won the most Academy Awards?", "options" => ["Jack Nicholson", "Daniel Day-Lewis", "Marlon Brando", "Katharine Hepburn"], "answer" => "Katharine Hepburn"],
        ["question" => "What was the first feature-length animated film?", "options" => ["Snow White", "Pinocchio", "Fantasia", "Bambi"], "answer" => "Snow White"],
        ["question" => "Which film holds the record for most Academy Award wins?", "options" => ["Titanic", "Ben-Hur", "The Lord of the Rings: The Return of the King", "La La Land"], "answer" => "The Lord of the Rings: The Return of the King"],
        ["question" => "Who played the Joker in 'The Dark Knight'?", "options" => ["Jack Nicholson", "Heath Ledger", "Jared Leto", "Joaquin Phoenix"], "answer" => "Heath Ledger"],
        ["question" => "Which movie features the character Jack Dawson?", "options" => ["The Departed", "Inception", "Titanic", "The Aviator"], "answer" => "Titanic"],
        ["question" => "What is the highest-grossing film of all time (unadjusted for inflation)?", "options" => ["Avatar", "Avengers: Endgame", "Titanic", "Star Wars: The Force Awakens"], "answer" => "Avatar"],
        ["question" => "Who directed 'Jaws'?", "options" => ["George Lucas", "Steven Spielberg", "Martin Scorsese", "Francis Ford Coppola"], "answer" => "Steven Spielberg"],
        ["question" => "Which film franchise has the most movies?", "options" => ["James Bond", "Marvel Cinematic Universe", "Star Wars", "Harry Potter"], "answer" => "James Bond"]
    ]
];

// Available exams data
$available_exams = [
    ["title" => "Math Quiz", "description" => "Basic arithmetic questions.", "icon" => "🧮"],
    ["title" => "General Knowledge", "description" => "Test your general knowledge.", "icon" => "🌍"],
    ["title" => "Programming Basics", "description" => "Questions on programming fundamentals.", "icon" => "💻"],
    ["title" => "Science Quiz", "description" => "Basic science questions.", "icon" => "🔬"],
    ["title" => "History Quiz", "description" => "Test your knowledge of history.", "icon" => "📜"],
    ["title" => "Geography Quiz", "description" => "Questions about world geography.", "icon" => "🌎"],
    ["title" => "Movies Quiz", "description" => "Test your knowledge of cinema and film history.", "icon" => "🎬"]
];

// Check if user selected an exam
if (isset($_GET['start_exam']) && isset($_GET['exam'])) {
    $selected_exam = $_GET['exam'];
    if (isset($exam_questions[$selected_exam])) {
 $_SESSION['exam_start_time'] = time(); 
        $_SESSION['exam_duration'] = 300; 
        $_SESSION['current_exam'] = $selected_exam;
        $_SESSION['current_questions'] = $exam_questions[$selected_exam];
        $_SESSION['question_index'] = 0;
        header("Location: Bhaavs_exam.php");
        exit();
    } else {
        echo "<script>alert('Invalid exam selection!');</script>";
    }
}

// Handle login
if (isset($_POST['login'])) {
    $_SESSION['user'] = $_POST['name'];
    $_SESSION['email'] = $_POST['email'];

    header("Location: " . $_SERVER['PHP_SELF']);
    exit();
}

// Redirect to login page if user is not set
if (!isset($_SESSION['user'])) {
    showLoginPage();
    exit();
}

// Handle logout
if (isset($_GET['logout'])) {
    session_destroy();
    header("Location: " . $_SERVER['PHP_SELF']);
    exit();
}
?>


<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <title>Online Exam Platform</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <style>
        body {
            background-color: white;
        }

        .exam-card {
            border-radius: 12px;
            transition: all 0.3s ease;
            border: none;
            box-shadow: 0 5px 15px rgba(0, 0, 0, 0.08);
            height: 100%;
            background-color: #89ABE3;
        }

        .exam-card:hover {
            transform: translateY(-5px);
            box-shadow: 0 10px 25px rgba(0, 0, 0, 0.12);
        }

        .exam-icon {
            font-size: 2.5rem;
            margin-bottom: 1rem;
        }

        .score-card {
            border-radius: 12px;
            border: none;
            box-shadow: 0 5px 15px rgba(0, 0, 0, 0.08);
            height: 100%;
            background-color: white;
        }

        .progress {
            height: 10px;
            border-radius: 5px;
        }

        .nav-shadow {
            box-shadow: 0 2px 10px rgba(0, 0, 0, 0.1);
        }

        .welcome-section {
            background: linear-gradient(135deg, #89ABE3 0%, #EA738D 100%);
            border-radius: 12px;
            color: white;
        }

        .profile-dropdown {
            background: transparent;
            border: none;
        }

        .profile-dropdown:focus {
            box-shadow: none;
        }

        .profile-dropdown::after {
            display: none;
        }

        .profile-menu {
            border-radius: 10px;
            border: none;
            box-shadow: 0 5px 15px rgba(0, 0, 0, 0.1);
            margin-top: 10px;
        }

        .profile-menu .dropdown-item:active {
            background-color: #f8f9fa;
            color: #000;
        }

        .profile-img {
            width: 32px;
            height: 32px;
            border-radius: 50%;
            margin-right: 8px;
            background: rgba(255, 255, 255, 0.2);
            display: inline-flex;
            align-items: center;
            justify-content: center;
        }

        .btn-primary {
            background-color: #EA738D;
            border-color: #EA738D;
        }

        .btn-primary:hover {
            background-color: #d45f79;
            border-color: #d45f79;
        }

        .navbar {
            background-color: #EA738D !important;
        }

        .progress-bar {
            background-color: #EA738D;
        }

        .badge {
            background-color: #EA738D;
        }
    </style>
</head>

<body>
    <?php if (isset($_SESSION['user'])): ?>
        <nav class="navbar navbar-expand-lg navbar-dark nav-shadow">
            <div class="container">
                <a class="navbar-brand fw-bold" href="#"><i class="fas fa-graduation-cap me-2"></i>Online Exam</a>
                <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav">
                    <span class="navbar-toggler-icon"></span>
                </button>
                <div class="collapse navbar-collapse" id="navbarNav">
                    <ul class="navbar-nav me-auto">
                        <li class="nav-item"><a class="nav-link active" href="#"><i class="fas fa-home me-1"></i> Home</a></li>
                    </ul>
                    <div class="d-flex align-items-center">
                        <div class="dropdown">
                            <button class="btn profile-dropdown dropdown-toggle d-flex align-items-center" type="button" id="profileDropdown" data-bs-toggle="dropdown" aria-expanded="false">
                                <div class="profile-img">
                                    <i class="fas fa-user text-white"></i>
                                </div>
                                <span class="text-white"><?= $_SESSION['user'] ?></span>
                            </button>
                            <ul class="dropdown-menu profile-menu dropdown-menu-end" aria-labelledby="profileDropdown">
                                <li class="px-3 py-2">
                                    <div class="d-flex flex-column">
                                        <span class="fw-bold"><?= $_SESSION['user'] ?></span>
                                        <small class="text-muted"><?= $_SESSION['email'] ?></small>
                                    </div>
                                </li>
                                <li>
                                    <hr class="dropdown-divider">
                                </li>
                                <li><a class="dropdown-item" href="#"><i class="fas fa-user me-2"></i>Profile</a></li>
                                <li><a class="dropdown-item" href="#"><i class="fas fa-cog me-2"></i>Settings</a></li>
                                <li><a class="dropdown-item" href="?logout=true"><i class="fas fa-sign-out-alt me-2"></i>Logout</a></li>
                            </ul>
                        </div>
                    </div>
                </div>
            </div>
        </nav>
    <?php endif; ?>

    <div class="container mt-4 mb-5">
        <div class="welcome-section p-4 mb-4 text-center">
            <h2 class="fw-bold">Welcome, <?= $_SESSION['user'] ?></h2>
            <p class="mb-0">Select an exam to begin or check your previous results</p>
        </div>

        <h3 class="mt-5 mb-4 fw-bold"><i class="fas fa-book-open me-2"></i>Available Exams</h3>
        <div class="row row-cols-1 row-cols-md-3 g-4 mb-5">
            <?php foreach ($available_exams as $exam): ?>
                <div class="col">
                    <div class="card exam-card h-100">
                        <div class="card-body text-center p-4">
                            <div class="exam-icon"><?= $exam['icon'] ?></div>
                            <h4 class="card-title fw-bold"><?= $exam['title'] ?></h4>
                            <p class="card-text text-muted"><?= $exam['description'] ?></p>
                            <a href="?start_exam=true&exam=<?= urlencode($exam['title']) ?>" class="btn btn-primary mt-3 px-4">
                                <i class="fas fa-play me-2"></i>Start Exam
                            </a>
                        </div>
                    </div>
                </div>
            <?php endforeach; ?>
        </div>

        <h3 class="mt-5 mb-4 fw-bold"><i class="fas fa-chart-line me-2"></i>Your Exam Results</h3>
        <?php if (!empty($_SESSION['previous_scores'])): ?>
            <div class="row row-cols-1 row-cols-md-3 g-4">
                <?php foreach ($_SESSION['previous_scores'] as $score):
                    $percentage = ($score['score'] / $score['total']) * 100;
                    $progress_class = 'bg-primary';
                ?>
                    <div class="col">
                        <div class="card score-card h-100">
                            <div class="card-body p-4">
                                <h4 class="card-title fw-bold"><?= $score['exam'] ?></h4>
                                <div class="d-flex justify-content-between mb-2">
                                    <span class="text-muted">Score:</span>
                                    <span class="fw-bold"><?= $score['score'] ?>/<?= $score['total'] ?></span>
                                </div>
                                <div class="progress mb-3">
                                    <div class="progress-bar <?= $progress_class ?>" role="progressbar"
                                        style="width: <?= $percentage ?>%" aria-valuenow="<?= $percentage ?>"
                                        aria-valuemin="0" aria-valuemax="100"></div>
                                </div>
                                <div class="text-ccasestudyenter">
                                    <span class="badge rounded-pill p-2 px-3">
                                        <?= number_format($percentage, 1) ?>%
                                    </span>
                                </div>
                            </div>
                        </div>
                    </div>
                <?php endforeach; ?>
            </div>
        <?php else: ?>
            <div class="card shadow-sm">
                <div class="card-body text-center py-5">
                    <i class="fas fa-inbox fa-3x text-muted mb-3"></i>
                    <h5 class="text-muted">No exam results yet</h5>
                    <p class="text-muted">Take an exam to see your results here</p>
                </div>
            </div>
        <?php endif; ?>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>

</html> 