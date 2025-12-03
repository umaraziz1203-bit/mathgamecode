<?php
require_once 'config.php';
requireLogin();

$conn = getDBConnection();
$level = isset($_GET['level']) ? (int)$_GET['level'] : 1;
$level = max(1, min(10, $level)); // Ensure between 1-10

// Get all animals for current and previous levels
$stmt = $conn->prepare("SELECT * FROM memory_animals WHERE level <= ? ORDER BY level");
$stmt->bind_param("i", $level);
$stmt->execute();
$result = $stmt->get_result();
$all_animals = [];
while ($row = $result->fetch_assoc()) {
    $all_animals[] = $row;
}
$stmt->close();

// Get current level animal
$stmt = $conn->prepare("SELECT * FROM memory_animals WHERE level = ?");
$stmt->bind_param("i", $level);
$stmt->execute();
$result = $stmt->get_result();
$current_animal = $result->fetch_assoc();
$stmt->close();

$message = '';
$message_type = '';
$show_animal = false;
$game_state = isset($_GET['state']) ? $_GET['state'] : 'show'; // show, guess

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $selected_animal = sanitizeInput($_POST['animal'] ?? '');
    $correct_animal = $current_animal['animal_name'];
    
    if ($selected_animal === $correct_animal) {
        if ($level < 10) {
            // Move to next level
            header("Location: memory.php?level=" . ($level + 1) . "&state=show");
            exit();
        } else {
            // Game completed
            $message = 'Congratulations! You completed all 10 levels of the memory game!';
            $message_type = 'success';
            $game_state = 'completed';
        }
    } else {
        $message = 'Wrong animal! Please try again.';
        $message_type = 'danger';
        $game_state = 'guess';
    }
}

// Handle state transitions
if ($game_state === 'show' && isset($_GET['ready'])) {
    $game_state = 'guess';
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Memory Game - Le Math Game</title>
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
            background-color: #3b82f6 !important;
        }
        .game-container {
            background: white;
            border-radius: 16px;
            box-shadow: 0 10px 40px rgba(0,0,0,0.1);
            padding: 2.5rem;
            margin-top: 2rem;
        }
        .progress-bar-custom {
            background-color: #3b82f6;
        }
        .animal-display {
            font-size: 8rem;
            text-align: center;
            padding: 2rem;
            background: linear-gradient(135deg, #f0f9ff 0%, #e0f2fe 100%);
            border-radius: 16px;
            margin: 2rem 0;
            animation: fadeIn 0.5s;
        }
        @keyframes fadeIn {
            from { opacity: 0; transform: scale(0.8); }
            to { opacity: 1; transform: scale(1); }
        }
        .animal-option {
            font-size: 3rem;
            padding: 1.5rem;
            margin: 0.5rem;
            border: 3px solid #dee2e6;
            border-radius: 12px;
            cursor: pointer;
            transition: all 0.3s;
            background: white;
        }
        .animal-option:hover {
            border-color: #3b82f6;
            background-color: #eff6ff;
            transform: scale(1.1);
        }
        .animal-option.selected {
            border-color: #3b82f6;
            background-color: #dbeafe;
        }
        .btn-primary-custom {
            background-color: #3b82f6;
            border-color: #3b82f6;
        }
        .btn-primary-custom:hover {
            background-color: #2563eb;
            border-color: #2563eb;
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
                <h2 class="fw-bold" style="color: #3b82f6;">Memory Game - Level <?php echo $level; ?></h2>
                <div class="progress mt-3" style="height: 25px;">
                    <div class="progress-bar progress-bar-custom progress-bar-striped progress-bar-animated" 
                         role="progressbar" 
                         style="width: <?php echo ($level / 10) * 100; ?>%">
                        Level <?php echo $level; ?> / 10
                    </div>
                </div>
            </div>

            <?php if ($message): ?>
                <div class="alert alert-<?php echo $message_type; ?> alert-dismissible fade show" role="alert">
                    <span class="material-icons" style="vertical-align: middle; font-size: 20px;">
                        <?php echo $message_type === 'success' ? 'check_circle' : 'error'; ?>
                    </span>
                    <?php echo htmlspecialchars($message); ?>
                    <?php if ($message_type === 'success' && $level >= 10): ?>
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

            <?php if ($current_animal): ?>
                <?php if ($game_state === 'show'): ?>
                    <!-- Show animal phase -->
                    <div class="text-center">
                        <h3 class="mb-4">Memorize this animal:</h3>
                        <div class="animal-display">
                            <?php echo htmlspecialchars($current_animal['animal_image']); ?>
                        </div>
                        <p class="fs-4 text-muted">Look carefully! You'll need to identify it next.</p>
                        <a href="memory.php?level=<?php echo $level; ?>&state=guess&ready=1" class="btn btn-primary-custom btn-lg mt-4">
                            <span class="material-icons" style="vertical-align: middle;">visibility_off</span>
                            I'm Ready - Hide Animal
                        </a>
                    </div>
                <?php elseif ($game_state === 'guess'): ?>
                    <!-- Guess phase -->
                    <form method="POST" action="" id="memoryForm">
                        <input type="hidden" name="animal" id="selectedAnimal" value="">
                        
                        <div class="text-center mb-4">
                            <h3 class="mb-4">Which animal did you see?</h3>
                            <p class="text-muted">Select the animal you just saw:</p>
                        </div>

                        <div class="row g-3 mb-4">
                            <?php 
                            // Shuffle animals for options
                            $options = $all_animals;
                            shuffle($options);
                            foreach ($options as $animal): 
                            ?>
                                <div class="col-md-3 col-6">
                                    <div class="animal-option text-center" 
                                         onclick="selectAnimal('<?php echo htmlspecialchars($animal['animal_name']); ?>')"
                                         data-animal="<?php echo htmlspecialchars($animal['animal_name']); ?>">
                                        <div><?php echo htmlspecialchars($animal['animal_image']); ?></div>
                                        <div class="mt-2 small"><?php echo htmlspecialchars($animal['animal_name']); ?></div>
                                    </div>
                                </div>
                            <?php endforeach; ?>
                        </div>

                        <div class="d-grid gap-2 d-md-flex justify-content-md-between">
                            <a href="home.php" class="btn btn-outline-secondary btn-lg">
                                <span class="material-icons" style="vertical-align: middle;">arrow_back</span>
                                Back to Home
                            </a>
                            <button type="submit" class="btn btn-primary-custom btn-lg" id="submitBtn" disabled>
                                <span class="material-icons" style="vertical-align: middle;">check</span>
                                Submit Answer
                            </button>
                        </div>
                    </form>
                <?php endif; ?>
            <?php else: ?>
                <div class="alert alert-warning">
                    Animal not found!
                </div>
                <a href="home.php" class="btn btn-primary-custom">Back to Home</a>
            <?php endif; ?>
        </div>
    </div>

    <!-- Bootstrap JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        function selectAnimal(animalName) {
            document.getElementById('selectedAnimal').value = animalName;
            document.getElementById('submitBtn').disabled = false;
            
            // Update visual selection
            document.querySelectorAll('.animal-option').forEach(option => {
                option.classList.remove('selected');
                if (option.dataset.animal === animalName) {
                    option.classList.add('selected');
                }
            });
        }
    </script>
</body>
</html>
<?php $conn->close(); ?>

