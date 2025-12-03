<?php
require_once 'config.php';
requireLogin();
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Le Math Game - Home</title>
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
        .game-card {
            background: white;
            border-radius: 16px;
            box-shadow: 0 10px 40px rgba(0,0,0,0.1);
            padding: 2rem;
            transition: transform 0.3s, box-shadow 0.3s;
            height: 100%;
            text-decoration: none;
            color: inherit;
            display: block;
        }
        .game-card:hover {
            transform: translateY(-5px);
            box-shadow: 0 15px 50px rgba(0,0,0,0.2);
            text-decoration: none;
            color: inherit;
        }
        .game-card.logic {
            border-top: 5px solid var(--primary-color);
        }
        .game-card.mathematics {
            border-top: 5px solid var(--secondary-color);
        }
        .game-card.memory {
            border-top: 5px solid #3b82f6;
        }
        .game-icon {
            font-size: 4rem;
            margin-bottom: 1rem;
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
                <span class="navbar-text me-3">
                    <span class="material-icons" style="vertical-align: middle; font-size: 20px;">person</span>
                    Welcome, <?php echo htmlspecialchars($_SESSION['username']); ?>
                </span>
                <a class="nav-link" href="logout.php">
                    <span class="material-icons" style="vertical-align: middle;">logout</span>
                    Logout
                </a>
            </div>
        </div>
    </nav>

    <div class="container my-5">
        <div class="text-center mb-5">
            <h1 class="display-3 fw-bold" style="color: var(--primary-color);">Choose Your Game</h1>
            <p class="lead text-muted">Select a game to start playing and improve your skills!</p>
        </div>

        <div class="row g-4">
            <div class="col-md-4">
                <a href="logic.php" class="game-card logic">
                    <div class="text-center">
                        <div class="game-icon">🧩</div>
                        <h3 class="fw-bold mb-3" style="color: var(--primary-color);">Logic Puzzles</h3>
                        <p class="text-muted">Test your logical reasoning with challenging puzzles. Solve 10 unique brain teasers!</p>
                        <button class="btn btn-primary-custom mt-3" style="background-color: var(--primary-color); border-color: var(--primary-color);">
                            Play Now
                            <span class="material-icons" style="vertical-align: middle; font-size: 18px;">play_arrow</span>
                        </button>
                    </div>
                </a>
            </div>

            <div class="col-md-4">
                <a href="mathematics.php" class="game-card mathematics">
                    <div class="text-center">
                        <div class="game-icon">🔢</div>
                        <h3 class="fw-bold mb-3" style="color: var(--secondary-color);">Mathematics</h3>
                        <p class="text-muted">Practice your math skills with counting games. Fill in the correct answers!</p>
                        <button class="btn btn-secondary-custom mt-3" style="background-color: var(--secondary-color); border-color: var(--secondary-color);">
                            Play Now
                            <span class="material-icons" style="vertical-align: middle; font-size: 18px;">play_arrow</span>
                        </button>
                    </div>
                </a>
            </div>

            <div class="col-md-4">
                <a href="memory.php" class="game-card memory">
                    <div class="text-center">
                        <div class="game-icon">🧠</div>
                        <h3 class="fw-bold mb-3" style="color: #3b82f6;">Memory Game</h3>
                        <p class="text-muted">Test your memory by identifying animals. Complete all 10 levels!</p>
                        <button class="btn btn-primary mt-3" style="background-color: #3b82f6; border-color: #3b82f6;">
                            Play Now
                            <span class="material-icons" style="vertical-align: middle; font-size: 18px;">play_arrow</span>
                        </button>
                    </div>
                </a>
            </div>
        </div>
    </div>

    <!-- Bootstrap JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>

