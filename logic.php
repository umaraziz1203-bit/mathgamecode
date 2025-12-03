<?php
require_once 'config.php';
requireLogin();

$conn = getDBConnection();
$puzzle_number = isset($_GET['puzzle']) ? (int)$_GET['puzzle'] : 1;
$puzzle_number = max(1, min(10, $puzzle_number)); // Ensure between 1-10

// Get current puzzle
$stmt = $conn->prepare("SELECT * FROM logic_puzzles WHERE puzzle_number = ?");
$stmt->bind_param("i", $puzzle_number);
$stmt->execute();
$result = $stmt->get_result();
$puzzle = $result->fetch_assoc();
$stmt->close();

$message = '';
$message_type = '';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $user_answer = sanitizeInput($_POST['answer'] ?? '');
    $correct_answer = strtolower(trim($puzzle['answer']));
    $user_answer_clean = strtolower(trim($user_answer));
    
    if ($user_answer_clean === $correct_answer) {
        if ($puzzle_number < 10) {
            // Move to next puzzle
            header("Location: logic.php?puzzle=" . ($puzzle_number + 1));
            exit();
        } else {
            // Game completed
            $message = 'Congratulations! You completed all logic puzzles!';
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
    <title>Logic Puzzles - Le Math Game</title>
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
            background-color: var(--primary-color) !important;
        }
        .game-container {
            background: white;
            border-radius: 16px;
            box-shadow: 0 10px 40px rgba(0,0,0,0.1);
            padding: 2.5rem;
            margin-top: 2rem;
        }
        .progress-bar-custom {
            background-color: var(--primary-color);
        }
        .btn-primary-custom {
            background-color: var(--primary-color);
            border-color: var(--primary-color);
        }
        .btn-primary-custom:hover {
            background-color: #b91c1c;
            border-color: #b91c1c;
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
                <h2 class="fw-bold" style="color: var(--primary-color);">Logic Puzzle #<?php echo $puzzle_number; ?></h2>
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
                            <a href="home.php" class="btn btn-primary-custom">
                                <span class="material-icons" style="vertical-align: middle;">home</span>
                                Back to Home
                            </a>
                        </div>
                    <?php endif; ?>
                    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                </div>
            <?php endif; ?>

            <?php if ($puzzle): ?>
                <div class="card mb-4" style="border-left: 5px solid var(--primary-color);">
                    <div class="card-body">
                        <h4 class="card-title mb-3">
                            <span class="material-icons" style="vertical-align: middle; color: var(--primary-color);">help_outline</span>
                            Question
                        </h4>
                        <p class="card-text fs-5"><?php echo htmlspecialchars($puzzle['question']); ?></p>
                        <?php if ($puzzle['hint']): ?>
                            <div class="alert alert-info mt-3">
                                <strong>Hint:</strong> <?php echo htmlspecialchars($puzzle['hint']); ?>
                            </div>
                        <?php endif; ?>
                    </div>
                </div>

                <form method="POST" action="">
                    <div class="mb-4">
                        <label for="answer" class="form-label fw-semibold fs-5">Your Answer:</label>
                        <input type="text" 
                               class="form-control form-control-lg" 
                               id="answer" 
                               name="answer" 
                               required 
                               autofocus
                               placeholder="Enter your answer here...">
                    </div>

                    <div class="d-grid gap-2 d-md-flex justify-content-md-between">
                        <a href="home.php" class="btn btn-outline-secondary btn-lg">
                            <span class="material-icons" style="vertical-align: middle;">arrow_back</span>
                            Back to Home
                        </a>
                        <button type="submit" class="btn btn-primary-custom btn-lg">
                            <span class="material-icons" style="vertical-align: middle;">check</span>
                            Submit Answer
                        </button>
                    </div>
                </form>
            <?php else: ?>
                <div class="alert alert-warning">
                    Puzzle not found!
                </div>
                <a href="home.php" class="btn btn-primary-custom">Back to Home</a>
            <?php endif; ?>
        </div>
    </div>

    <!-- Bootstrap JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
<?php $conn->close(); ?>

