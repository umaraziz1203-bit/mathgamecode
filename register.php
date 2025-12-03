<?php
require_once 'config.php';

// Redirect if already logged in
if (isLoggedIn()) {
    header('Location: home.php');
    exit();
}

$error = '';
$success = '';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $username = sanitizeInput($_POST['username'] ?? '');
    $email = sanitizeInput($_POST['email'] ?? '');
    $password = $_POST['password'] ?? '';
    $confirm_password = $_POST['confirm_password'] ?? '';
    
    if (empty($username) || empty($email) || empty($password) || empty($confirm_password)) {
        $error = 'Please fill in all fields!';
    } elseif ($password !== $confirm_password) {
        $error = 'Passwords do not match!';
    } elseif (strlen($password) < 6) {
        $error = 'Password must be at least 6 characters!';
    } else {
        $conn = getDBConnection();
        
        // Check if username or email already exists
        $stmt = $conn->prepare("SELECT id FROM users WHERE username = ? OR email = ?");
        $stmt->bind_param("ss", $username, $email);
        $stmt->execute();
        $result = $stmt->get_result();
        
        if ($result->num_rows > 0) {
            $error = 'Username or email already exists!';
        } else {
            $hashed_password = password_hash($password, PASSWORD_DEFAULT);
            $stmt = $conn->prepare("INSERT INTO users (username, email, password) VALUES (?, ?, ?)");
            $stmt->bind_param("sss", $username, $email, $hashed_password);
            
            if ($stmt->execute()) {
                $success = 'Registration successful! You can now login.';
                // Clear form
                $username = $email = '';
            } else {
                $error = 'Registration failed! Please try again.';
            }
        }
        $stmt->close();
        $conn->close();
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Le Math Game - Register</title>
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
            padding: 20px;
        }
        .register-card {
            background: white;
            border-radius: 16px;
            box-shadow: 0 10px 40px rgba(0,0,0,0.1);
            padding: 2.5rem;
            max-width: 500px;
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
    <div class="register-card">
        <div class="text-center mb-4">
            <h1 class="display-4 fw-bold" style="color: var(--primary-color);">Le Math Game</h1>
            <p class="text-muted">Create your account</p>
        </div>
        
        <?php if ($error): ?>
            <div class="alert alert-danger alert-dismissible fade show" role="alert">
                <span class="material-icons" style="vertical-align: middle; font-size: 20px;">error</span>
                <?php echo htmlspecialchars($error); ?>
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
        <?php endif; ?>
        
        <?php if ($success): ?>
            <div class="alert alert-success alert-dismissible fade show" role="alert">
                <span class="material-icons" style="vertical-align: middle; font-size: 20px;">check_circle</span>
                <?php echo htmlspecialchars($success); ?>
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
        <?php endif; ?>
        
        <form method="POST" action="">
            <div class="mb-3">
                <label for="username" class="form-label fw-semibold">Username</label>
                <div class="input-group">
                    <span class="input-group-text"><span class="material-icons">person</span></span>
                    <input type="text" class="form-control" id="username" name="username" required autofocus value="<?php echo htmlspecialchars($username ?? ''); ?>">
                </div>
            </div>
            
            <div class="mb-3">
                <label for="email" class="form-label fw-semibold">Email</label>
                <div class="input-group">
                    <span class="input-group-text"><span class="material-icons">email</span></span>
                    <input type="email" class="form-control" id="email" name="email" required value="<?php echo htmlspecialchars($email ?? ''); ?>">
                </div>
            </div>
            
            <div class="mb-3">
                <label for="password" class="form-label fw-semibold">Password</label>
                <div class="input-group">
                    <span class="input-group-text"><span class="material-icons">lock</span></span>
                    <input type="password" class="form-control" id="password" name="password" required>
                </div>
                <small class="text-muted">Minimum 6 characters</small>
            </div>
            
            <div class="mb-4">
                <label for="confirm_password" class="form-label fw-semibold">Confirm Password</label>
                <div class="input-group">
                    <span class="input-group-text"><span class="material-icons">lock_outline</span></span>
                    <input type="password" class="form-control" id="confirm_password" name="confirm_password" required>
                </div>
            </div>
            
            <button type="submit" class="btn btn-primary-custom btn-lg w-100 mb-3">
                <span class="material-icons" style="vertical-align: middle;">person_add</span>
                Register
            </button>
            
            <a href="index.php" class="btn btn-outline-secondary btn-lg w-100">
                <span class="material-icons" style="vertical-align: middle;">arrow_back</span>
                Back to Login
            </a>
        </form>
    </div>
    
    <!-- Bootstrap JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>

