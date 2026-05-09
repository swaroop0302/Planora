<?php
session_start();
require 'db_connect.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $fullname = trim($_POST['fullname']);
    $email = trim($_POST['email']);
    $password = $_POST['password'];
    $confirm_password = $_POST['confirm_password'];

    $errors = [];

    if (empty($fullname)) $errors[] = "Full name is required.";
    if (empty($email)) $errors[] = "Email is required.";
    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) $errors[] = "Invalid email format.";
    if (empty($password)) $errors[] = "Password is required.";
    if (strlen($password) < 8) $errors[] = "Password must be at least 8 characters.";
    if ($password !== $confirm_password) $errors[] = "Passwords do not match.";

    $stmt = $conn->prepare("SELECT id FROM users WHERE email = ?");
    $stmt->execute([$email]);
    if ($stmt->rowCount() > 0) $errors[] = "Email is already registered.";

    if (empty($errors)) {
        $password_hash = password_hash($password, PASSWORD_DEFAULT);
        $stmt = $conn->prepare("INSERT INTO users (full_name, email, password_hash) VALUES (?, ?, ?)");
        if ($stmt->execute([$fullname, $email, $password_hash])) {
            $_SESSION['success'] = "Account created successfully! You can now login.";
            header('Location: login.php');
            exit;
        } else {
            $errors[] = "Something went wrong. Please try again.";
        }
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Sign Up - TravelExplorer</title>
<link rel="preconnect" href="https://fonts.googleapis.com">
<link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
<link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap" rel="stylesheet">
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
<style>
    /* Prevent shrinking below 320px */
        @media (max-width: 400px) {
            html, body {
                min-width: 320px;
                width: 100%;
                overflow-x: hidden;
                position: relative;
            }
            
            .auth-container {
                min-width: 320px !important;
                width: 100% !important;
                padding: 20px 15px !important;
            }
            
            .auth-header {
                min-width: 320px !important;
                padding: 0 10px !important;
                text-align: center;
            }
            
            .logo {
                font-size: 1.5rem !important;
                white-space: nowrap;
            }
            
            .auth-header h1 {
                font-size: 1.75rem !important;
                line-height: 1.2;
                margin: 10px 0 !important;
            }
            
            .auth-header p {
                font-size: 0.95rem !important;
                padding: 0 5px;
            }
            
            .auth-form {
                min-width: 290px !important;
                padding: 1.5rem !important;
            }
            
            .form-control {
                padding: 0.8rem !important;
                font-size: 16px !important;
            }
            
            .btn {
                padding: 0.8rem !important;
                font-size: 1rem !important;
            }
        }
        
        :root {
            /* Color-blind friendly palette */
            --primary-blue: #0066cc;    /* Blue - visible to all color blindness types */
            --primary-orange: #e69f00;  /* Orange - distinct from blue for all types */
            --success-green: #009e73;   /* Green - distinguishable for most types */
            --error-red: #d55e00;       /* Red - distinguishable from green and blue */
            --dark-blue: #004c99;
            --light-blue: #e6f2ff;
            --white: #ffffff;
            --off-white: #f8fafc;
            --gray-100: #f3f4f6;
            --gray-200: #e5e7eb;
            --gray-700: #2d3748;
            --shadow-sm: 0 1px 3px rgba(0, 0, 0, 0.1);
            --shadow-md: 0 4px 6px rgba(0, 0, 0, 0.1);
            --shadow-lg: 0 10px 25px rgba(0, 0, 0, 0.1);
            --transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
        }

        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
            font-family: 'Poppins', sans-serif;
        }

        body {
            background: linear-gradient(135deg, #0f2027, #203a43, #2c5364);
            min-height: 100vh;
            display: flex;
            justify-content: center;
            align-items: center;
            color: var(--white);
            font-family: 'Poppins', sans-serif;
            line-height: 1.6;
            margin: 0;
            padding: 0;
            min-width: 320px;
        }


        .auth-container {
            position: relative;
            z-index: 10;
            width: 100%;
            max-width: 460px;
            padding: 2.5rem 3rem;
            background: rgba(255, 255, 255, 0.1);
            backdrop-filter: blur(12px);
            -webkit-backdrop-filter: blur(12px);
            border-radius: 20px;
            box-shadow: 0 20px 50px rgba(0, 0, 0, 0.2);
            border: 1px solid rgba(255, 255, 255, 0.18);
            transform: translateY(0);
            transition: var(--transition);
            animation: slideUp 0.6s cubic-bezier(0.23, 1, 0.32, 1) forwards;
            opacity: 0;
        }

        @keyframes slideUp {
            from { opacity: 0; transform: translateY(30px); }
            to { opacity: 1; transform: translateY(0); }
        }

        .auth-container:hover {
            transform: translateY(-5px);
            box-shadow: 0 25px 60px rgba(0, 0, 0, 0.25);
        }

        .auth-header {
            text-align: center;
            margin-bottom: 1.5rem;
        }

        .logo {
            font-size: 2rem;
            font-weight: 700;
            color: var(--white);
            margin-bottom: 0.5rem;
            text-shadow: 0 2px 4px rgba(0, 0, 0, 0.2);
        }

        .logo span {
            color: var(--primary-orange);
        }

        .auth-header h1 {
            font-size: 1.8rem;
            margin-bottom: 0.5rem;
            color: var(--white);
        }

        .auth-header p {
            color: var(--gray-200);
            font-weight: 300;
        }

        .auth-form {
            margin-top: 1.5rem;
        }

        .form-group {
            margin-bottom: 1.75rem;
            position: relative;
        }

        .form-group label {
            display: block;
            margin-bottom: 0.75rem;
            color: var(--white);
            font-weight: 500;
            font-size: 0.95rem;
            letter-spacing: 0.3px;
            transition: var(--transition);
        }

        .form-control {
            width: 100%;
            padding: 1rem 1.25rem;
            border: 2px solid rgba(255, 255, 255, 0.2);
            border-radius: 12px;
            background: rgba(255, 255, 255, 0.1);
            color: var(--white);
            font-size: 1rem;
            transition: var(--transition);
            box-shadow: var(--shadow-sm);
            font-family: 'Poppins', sans-serif;
        }

        .form-control::placeholder {
            color: rgba(255, 255, 255, 0.6);
            font-weight: 300;
        }

        .form-control:focus {
            outline: none;
            border-color: var(--primary-orange);
            background: rgba(255, 255, 255, 0.15);
            box-shadow: 0 0 0 3px rgba(230, 159, 0, 0.3);
            transform: translateY(-1px);
        }

        .form-control:not(:placeholder-shown) {
            background: rgba(255, 255, 255, 0.12);
        }

        .password-container {
            position: relative;
        }

        .toggle-password {
            position: absolute;
            right: 16px;
            top: 50%;
            transform: translateY(-50%);
            color: rgba(255, 255, 255, 0.6);
            cursor: pointer;
            transition: color 0.3s ease;
        }

        .toggle-password:hover {
            color: var(--white);
        }

        .password-strength {
            height: 4px;
            background: rgba(255, 255, 255, 0.1);
            border-radius: 2px;
            margin-top: 0.75rem;
            overflow: hidden;
            position: relative;
        }

        .strength-meter {
            height: 100%;
            width: 0;
            border-radius: 2px;
            transition: width 0.4s ease, background 0.4s ease;
            position: relative;
            overflow: hidden;
        }

        .strength-meter::after {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            bottom: 0;
            background: linear-gradient(
                90deg,
                transparent,
                rgba(255, 255, 255, 0.2),
                transparent
            );
            animation: shimmer 2s infinite linear;
        }

        @keyframes shimmer {
            0% { transform: translateX(-100%); }
            100% { transform: translateX(100%); }
        }

        .strength-weak { background: var(--error-red); }
        .strength-medium { background: var(--primary-orange); }
        .strength-strong { background: var(--success-green); }

        .btn {
            display: inline-block;
            width: 100%;
            padding: 0.875rem 1.5rem;
            border: none;
            border-radius: 10px;
            font-size: 1rem;
            font-weight: 600;
            text-align: center;
            cursor: pointer;
            transition: all 0.3s ease;
        }

        .btn-primary {
            background: linear-gradient(135deg, var(--primary-blue), var(--dark-blue));
            color: white;
            box-shadow: 0 4px 15px rgba(37, 99, 235, 0.3);
            margin-top: 0.5rem;
        }

        .btn-primary:hover {
            transform: translateY(-2px);
            box-shadow: 0 6px 20px rgba(37, 99, 235, 0.4);
        }

        .btn-google {
            background: rgba(255, 255, 255, 0.9);
            color: var(--gray-700);
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 10px;
            margin-top: 1rem;
            border: 1px solid var(--gray-200);
        }

        .btn-google:hover {
            background: white;
            transform: translateY(-2px);
            box-shadow: 0 4px 15px rgba(0, 0, 0, 0.1);
        }

        .divider {
            display: flex;
            align-items: center;
            margin: 1.5rem 0;
            color: rgba(255, 255, 255, 0.6);
            font-size: 0.9rem;
        }

        .divider::before,
        .divider::after {
            content: '';
            flex: 1;
            height: 1px;
            background: rgba(255, 255, 255, 0.2);
            margin: 0 10px;
        }

        .auth-footer {
            text-align: center;
            margin-top: 1.5rem;
            color: rgba(255, 255, 255, 0.8);
            font-size: 0.95rem;
        }

        .auth-footer a {
            color: var(--primary-orange);
            text-decoration: none;
            font-weight: 600;
            margin-left: 5px;
            transition: color 0.3s ease;
        }

        .auth-footer a:hover {
            text-decoration: underline;
            color: #ffc107;
        }

        /* Form States */
        .form-group.error .form-control {
            border-color: #ef4444;
            background: rgba(239, 68, 68, 0.1);
        }

        .error-message {
            color: #fecaca;
            font-size: 0.8rem;
            margin-top: 0.25rem;
            display: none;
        }

        .form-group.error .error-message {
            display: block;
        }

        .terms {
            display: flex;
            align-items: flex-start;
            gap: 0.5rem;
            margin: 1.25rem 0;
        }

        .terms input[type="checkbox"] {
            margin-top: 0.25rem;
            width: 18px;
            height: 18px;
            accent-color: var(--primary-orange);
            cursor: pointer;
            flex-shrink: 0;
        }

        .terms label {
            font-size: 0.9rem;
            color: var(--white);
            cursor: pointer;
            line-height: 1.4;
        }

        .terms a {
            color: var(--primary-orange);
            text-decoration: none;
            font-weight: 500;
        }

        .terms a:hover {
            text-decoration: underline;
        }

        /* Responsive Design */
        @media (max-width: 480px) {
            .auth-container {
                margin: 1rem;
                padding: 1.5rem;
            }

            .form-options {
                flex-direction: column;
                align-items: flex-start;
                gap: 1rem;
            }
        }
</style>
</head>
<body>
<div class="auth-container">
    <div class="auth-header">
        <div class="logo">Travel<span>Explorer</span></div>
        <h1>Create Account</h1>
        <p>Join our travel community today</p>
    </div>
    <?php
    if (!empty($errors)) {
        echo '<div class="error-message" style="display:block;margin-bottom:1rem;">';
        foreach ($errors as $error) echo $error . '<br>';
        echo '</div>';
    }
    ?>
    <form action="" method="POST" class="auth-form">
        <div class="form-group">
            <label for="fullname">Full Name</label>
            <input type="text" name="fullname" id="fullname" class="form-control" placeholder="Enter your full name" required>
        </div>
        <div class="form-group">
            <label for="email">Email Address</label>
            <input type="email" name="email" id="email" class="form-control" placeholder="Enter your email" required>
        </div>
        <div class="form-group">
            <label for="password">Password</label>
            <input type="password" name="password" id="password" class="form-control" placeholder="Create a strong password" required>
        </div>
        <div class="form-group">
            <label for="confirm_password">Confirm Password</label>
            <input type="password" name="confirm_password" id="confirm_password" class="form-control" placeholder="Confirm your password" required>
        </div>
        <button type="submit" class="btn btn-primary">Create Account</button>
        <p style="text-align:center;margin-top:1rem;">Already have an account? <a href="login.php" style="color:var(--primary-orange);">Sign In</a></p>
    </form>
</div>
</body>
</html>
