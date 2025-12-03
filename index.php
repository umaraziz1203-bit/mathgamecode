<?php
require_once 'config.php';

// Redirect if already logged in
if (isLoggedIn()) {
    header('Location: home.php');
    exit();
}

$error = '';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $username = sanitizeInput($_POST['username'] ?? '');
    $password = $_POST['password'] ?? '';
    
    if (!empty($username) && !empty($password)) {
        $conn = getDBConnection();
        $stmt = $conn->prepare("SELECT id, username, password FROM users WHERE username = ? OR email = ?");
        $stmt->bind_param("ss", $username, $username);
        $stmt->execute();
        $result = $stmt->get_result();
        
        if ($result->num_rows === 1) {
            $user = $result->fetch_assoc();
            if (password_verify($password, $user['password'])) {
                $_SESSION['user_id'] = $user['id'];
                $_SESSION['username'] = $user['username'];
                header('Location: home.php');
                exit();
            } else {
                $error = 'Invalid username or password!';
            }
        } else {
            $error = 'Invalid username or password!';
        }
        $stmt->close();
        $conn->close();
    } else {
        $error = 'Please fill in all fields!';
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Le Math Game - Login</title>
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
            display: flex;
            align-items: center;
            justify-content: center;
        }
        .login-card {
            background: white;
            border-radius: 16px;
            box-shadow: 0 10px 40px rgba(0,0,0,0.1);
            padding: 2.5rem;
            max-width: 450px;
            width: 100%;
        }
        .btn-primary-custom {
            background-color: var(--primary-color);
            border-color: var(--primary-color);
        }
        .btn-primary-custom:hover {
            background-color: #b91c1c;
            border-color: #b91c1c;
        }
        .btn-secondary-custom {
            background-color: var(--secondary-color);
            border-color: var(--secondary-color);
        }
        .btn-secondary-custom:hover {
            background-color: #15803d;
            border-color: #15803d;
        }
        .form-control:focus {
            border-color: var(--primary-color);
            box-shadow: 0 0 0 0.2rem rgba(220, 38, 38, 0.25);
        }
    </style>
</head>
<body>
    <div class="login-card">
        <div class="text-center mb-4">
            <h1 class="display-4 fw-bold" style="color: var(--primary-color);">Le Math Game</h1>
            <p class="text-muted">Login to continue</p>
        </div>
        
        <?php if ($error): ?>
            <div class="alert alert-danger alert-dismissible fade show" role="alert">
                <span class="material-icons" style="vertical-align: middle; font-size: 20px;">error</span>
                <?php echo htmlspecialchars($error); ?>
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
        <?php endif; ?>
        
        <form method="POST" action="">
            <div class="mb-3">
                <label for="username" class="form-label fw-semibold">Username or Email</label>
                <div class="input-group">
                    <span class="input-group-text"><span class="material-icons">person</span></span>
                    <input type="text" class="form-control" id="username" name="username" required autofocus>
                </div>
            </div>
            
            <div class="mb-4">
                <label for="password" class="form-label fw-semibold">Password</label>
                <div class="input-group">
                    <span class="input-group-text"><span class="material-icons">lock</span></span>
                    <input type="password" class="form-control" id="password" name="password" required>
                </div>
            </div>
            
            <button type="submit" class="btn btn-primary-custom btn-lg w-100 mb-3">
                <span class="material-icons" style="vertical-align: middle;">login</span>
                Login
            </button>
            
            <a href="register.php" class="btn btn-secondary-custom btn-lg w-100">
                <span class="material-icons" style="vertical-align: middle;">person_add</span>
                Register Account
            </a>
        </form>
    </div>
    
    <!-- Bootstrap JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>

