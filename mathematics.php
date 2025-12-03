<?php
require_once 'config.php';
requireLogin();

$conn = getDBConnection();
$puzzle_number = isset($_GET['puzzle']) ? (int)$_GET['puzzle'] : 1;
$puzzle_number = max(1, min(10, $puzzle_number)); // Ensure between 1-10

// Get current puzzle
$stmt = $conn->prepare("SELECT * FROM mathematics_puzzles WHERE puzzle_number = ?");
$stmt->bind_param("i", $puzzle_number);
$stmt->execute();
$result = $stmt->get_result();
$puzzle = $result->fetch_assoc();
$stmt->close();

// Parse options
$options = [];
if ($puzzle) {
    $options = array_map('trim', explode(',', $puzzle['options']));
}

$message = '';
$message_type = '';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $user_answer = isset($_POST['answer']) ? (int)$_POST['answer'] : 0;
    $correct_answer = (int)$puzzle['answer'];
    
    if ($user_answer === $correct_answer) {
        if ($puzzle_number < 10) {
            // Move to next puzzle
            header("Location: mathematics.php?puzzle=" . ($puzzle_number + 1));
            exit();
        } else {
            // Game completed
            $message = 'Congratulations! You completed all mathematics puzzles!';
            $message_type = 'success';
        }
    } else {
        $message = 'Incorrect answer! Please try again.';
        $message_type = 'danger';
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Mathematics - Le Math Game</title>
    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- Tailwind CSS -->
    <script src="https://cdn.tailwindcss.com"></script>
    <!-- Material Icons -->
    <link href="https://fonts.googleapis.com/icon?family=Material+Icons" rel="stylesheet">
    <style>
        :root {
            --primary-color: #dc2626; /* Red */
            --secondary-color: #16a34a; /* Green */
        }
        body {
            background: linear-gradient(135deg, #f5f5f5 0%, #e0e0e0 100%);
            min-height: 100vh;
        }
        .navbar {
            background-color: var(--secondary-color) !important;
        }
        .game-container {
            background: white;
            border-radius: 16px;
            box-shadow: 0 10px 40px rgba(0,0,0,0.1);
            padding: 2.5rem;
            margin-top: 2rem;
        }
        .progress-bar-custom {
            background-color: var(--secondary-color);
        }
        .btn-secondary-custom {
            background-color: var(--secondary-color);
            border-color: var(--secondary-color);
        }
        .btn-secondary-custom:hover {
            background-color: #15803d;
            border-color: #15803d;
        }
        .answer-box {
            min-height: 80px;
            font-size: 2rem;
            text-align: center;
            border: 3px dashed #dee2e6;
            border-radius: 12px;
            display: flex;
            align-items: center;
            justify-content: center;
            cursor: pointer;
            transition: all 0.3s;
        }
        .answer-box:hover {
            border-color: var(--secondary-color);
            background-color: #f0f9f4;
        }
        .answer-box.selected {
            border-color: var(--secondary-color);
            background-color: #dcfce7;
            border-style: solid;
        }
        .option-btn {
            font-size: 1.5rem;
            padding: 1rem 2rem;
            margin: 0.5rem;
            min-width: 100px;
        }
    </style>
</head>
<body>
    <nav class="navbar navbar-expand-lg navbar-dark">
        <div class="container">
            <a class="navbar-brand fw-bold" href="home.php">
                <span class="material-icons" style="vertical-align: middle;">calculate</span>
                Le Math Game
            </a>
            <div class="navbar-nav ms-auto">
                <a class="nav-link" href="home.php">
                    <span class="material-icons" style="vertical-align: middle;">home</span>
                    Home
                </a>
                <a class="nav-link" href="logout.php">
                    <span class="material-icons" style="vertical-align: middle;">logout</span>
                    Logout
                </a>
            </div>
        </div>
    </nav>

    <div class="container">
        <div class="game-container">
            <div class="mb-4">
                <h2 class="fw-bold" style="color: var(--secondary-color);">Mathematics Puzzle #<?php echo $puzzle_number; ?></h2>
                <div class="progress mt-3" style="height: 25px;">
                    <div class="progress-bar progress-bar-custom progress-bar-striped progress-bar-animated" 
                         role="progressbar" 
                         style="width: <?php echo ($puzzle_number / 10) * 100; ?>%">
                        <?php echo $puzzle_number; ?> / 10
                    </div>
                </div>
            </div>

            <?php if ($message): ?>
                <div class="alert alert-<?php echo $message_type; ?> alert-dismissible fade show" role="alert">
                    <span class="material-icons" style="vertical-align: middle; font-size: 20px;">
                        <?php echo $message_type === 'success' ? 'check_circle' : 'error'; ?>
                    </span>
                    <?php echo htmlspecialchars($message); ?>
                    <?php if ($message_type === 'success' && $puzzle_number >= 10): ?>
                        <div class="mt-3">
                            <a href="home.php" class="btn btn-secondary-custom">
                                <span class="material-icons" style="vertical-align: middle;">home</span>
                                Back to Home
                            </a>
                        </div>
                    <?php endif; ?>
                    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                </div>
            <?php endif; ?>

            <?php if ($puzzle): ?>
                <div class="card mb-4" style="border-left: 5px solid var(--secondary-color);">
                    <div class="card-body">
                        <h4 class="card-title mb-3">
                            <span class="material-icons" style="vertical-align: middle; color: var(--secondary-color);">calculate</span>
                            Question
                        </h4>
                        <p class="card-text fs-3 fw-bold text-center my-4"><?php echo htmlspecialchars($puzzle['question']); ?></p>
                    </div>
                </div>

                <form method="POST" action="" id="mathForm">
                    <input type="hidden" name="answer" id="selectedAnswer" value="">
                    
                    <div class="mb-4">
                        <label class="form-label fw-semibold fs-5 mb-3">Select the correct answer:</label>
                        <div class="answer-box mb-3" id="answerBox">
                            <span class="text-muted">Click an option below to fill this box</span>
                        </div>
                    </div>

                    <div class="text-center mb-4">
                        <?php foreach ($options as $option): ?>
                            <button type="button" 
                                    class="btn btn-outline-secondary option-btn" 
                                    onclick="selectAnswer(<?php echo (int)trim($option); ?>)"
                                    data-value="<?php echo (int)trim($option); ?>">
                                <?php echo htmlspecialchars(trim($option)); ?>
                            </button>
                        <?php endforeach; ?>
                    </div>

                    <div class="d-grid gap-2 d-md-flex justify-content-md-between">
                        <a href="home.php" class="btn btn-outline-secondary btn-lg">
                            <span class="material-icons" style="vertical-align: middle;">arrow_back</span>
                            Back to Home
                        </a>
                        <button type="submit" class="btn btn-secondary-custom btn-lg" id="submitBtn" disabled>
                            <span class="material-icons" style="vertical-align: middle;">check</span>
                            Submit Answer
                        </button>
                    </div>
                </form>
            <?php else: ?>
                <div class="alert alert-warning">
                    Puzzle not found!
                </div>
                <a href="home.php" class="btn btn-secondary-custom">Back to Home</a>
            <?php endif; ?>
        </div>
    </div>

    <!-- Bootstrap JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        function selectAnswer(value) {
            document.getElementById('selectedAnswer').value = value;
            document.getElementById('answerBox').innerHTML = '<span class="fw-bold">' + value + '</span>';
            document.getElementById('answerBox').classList.add('selected');
            document.getElementById('submitBtn').disabled = false;
            
            // Update button styles
            document.querySelectorAll('.option-btn').forEach(btn => {
                btn.classList.remove('btn-secondary-custom');
                btn.classList.add('btn-outline-secondary');
                if (parseInt(btn.dataset.value) === value) {
                    btn.classList.remove('btn-outline-secondary');
                    btn.classList.add('btn-secondary-custom');
                }
            });
        }
    </script>
</body>
</html>
<?php $conn->close(); ?>

