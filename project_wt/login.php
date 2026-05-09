<?php
session_start();
require 'db_connect.php';

$loginSuccess = false;
$userData = null;

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $email = trim($_POST['email']);
    $password = $_POST['password'];
    $errors = [];

    if (empty($email)) $errors[] = "Email is required.";
    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) $errors[] = "Invalid email format.";
    if (empty($password)) $errors[] = "Password is required.";

    if (empty($errors)) {
        $stmt = $conn->prepare("SELECT * FROM users WHERE email = ?");
        $stmt->execute([$email]);
        $user = $stmt->fetch();

        if ($user && password_verify($password, $user['password_hash'])) {
            $_SESSION['user_id'] = $user['id'];
            $_SESSION['user_name'] = $user['full_name'];
            $_SESSION['user_email'] = $user['email'];
            
            $loginSuccess = true;
            $userData = [
                'id' => $user['id'],
                'name' => $user['full_name'],
                'email' => $user['email']
            ];
        } else {
            $errors[] = "Invalid email or password.";
        }
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Login - TravelExplorer</title>
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
        }
:root {
     /* Enhanced color-blind friendly palette with better contrast */
            --primary-blue: #0056b3;    /* Darker blue for better contrast */
            --primary-orange: #d68a00;  /* Darker orange for better contrast */
            --success-green: #2e7d32;   /* Darker green for better contrast */
            --error-red: #c62828;       /* Darker red for better contrast */
            --dark-blue: #003d82;       /* Darker for better contrast */
            --light-blue: #e3f2fd;      /* Lighter for better contrast */
            --white: #ffffff;
            --off-white: #f8f9fa;       /* Slightly darker for better contrast */
            --gray-100: #f1f3f5;        /* Slightly darker for better contrast */
            --gray-200: #e9ecef;        /* Slightly darker for better contrast */
            --gray-700: #212529;        /* Darker for better contrast */
            --shadow-sm: 0 1px 3px rgba(0, 0, 0, 0.1);
            --shadow-md: 0 4px 6px rgba(0, 0, 0, 0.1);
            --shadow-lg: 0 10px 25px rgba(0, 0, 0, 0.1);
            --transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
}

body{
    background: linear-gradient(135deg, #0f2027, #203a43, #2c5364);
    min-height:100vh;
    display:flex;
    justify-content:center;
    align-items:center;
    font-family:'Poppins',sans-serif;
    color: var(--white);
}

.auth-container{
    width:100%;
    max-width:320px;
    padding:2.5rem 3rem;
    background: rgba(255,255,255,0.1);
    backdrop-filter: blur(12px);
    border-radius:20px;
    box-shadow:var(--shadow-lg);
    border:1px solid rgba(255,255,255,0.18);
}

.auth-header{
    text-align:center;
    margin-bottom:1.5rem;
}

.logo{
    font-size:2rem;
    font-weight:700;
    color:var(--white);
    margin-bottom:0.5rem;
}

.logo span{
    color:var(--primary-orange);
}

.auth-header h1{
    font-size:1.8rem;
    margin-bottom:0.5rem;
}

.auth-header p{
    color: #e5e7eb;
    font-weight:300;
}

.form-group{
    margin-bottom:1.5rem;
}

.form-group label{
    display:block;
    margin-bottom:0.5rem;
}

.form-control{
      width: 85%;
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

.form-control::placeholder{
    color: rgba(255, 255, 255, 0.6);
    font-weight: 300;
}

.form-control:focus{
    outline: none;
    border-color: var(--primary-orange);
    background: rgba(255, 255, 255, 0.15);
    box-shadow: 0 0 0 3px rgba(230, 159, 0, 0.3);
    transform: translateY(-1px);
}

.btn{
    width:100%;
    padding:0.875rem 1.5rem;
    border:none;
    border-radius:10px;
    font-size:1rem;
    cursor:pointer;
}

.btn-primary{
    background:linear-gradient(135deg,var(--primary-blue),var(--dark-blue));
    color:white;
    margin-top:0.5rem;
}

.btn-primary:hover{
    transform: translateY(-2px);
    box-shadow: 0 5px 15px rgba(0, 102, 204, 0.4);
}
.error-message{color:#fecaca;font-size:0.8rem;margin-top:0.25rem;display:none;background: rgba(239, 68, 68, 0.2);padding: 0.75rem;border-radius: 8px;margin-bottom: 1rem;}
.form-group.error .error-message{display:block;}
.success-message{color:#86efac;background: rgba(16, 185, 129, 0.2);padding: 0.75rem;border-radius: 8px;margin-bottom: 1rem;text-align:center;}
.signup-link{text-align:center;margin-top:1.5rem;color: #e5e7eb;}
.signup-link a{color:var(--primary-orange);text-decoration:none;font-weight:600;}
.signup-link a:hover{text-decoration:underline;}
</style>
</head>
<body>
<div class="auth-container">
    <div class="auth-header">
        <div class="logo">Travel<span>Explorer</span></div>
        <h1>Welcome Back</h1>
        <p>Sign in to continue your journey</p>
    </div>
    
    <?php if (!empty($errors)): ?>
        <div class="error-message" style="display:block;">
            <?php foreach ($errors as $error): ?>
                <?php echo $error; ?><br>
            <?php endforeach; ?>
        </div>
    <?php endif; ?>
    
    <?php if (isset($_SESSION['success'])): ?>
        <div class="success-message">
            <?php echo $_SESSION['success']; unset($_SESSION['success']); ?>
        </div>
    <?php endif; ?>
    
    <form action="" method="POST" class="auth-form" id="loginForm">
        <div class="form-group">
            <label for="email">Email Address</label>
            <input type="email" name="email" id="email" class="form-control" placeholder="Enter your email" required>
        </div>
        <div class="form-group">
            <label for="password">Password</label>
            <input type="password" name="password" id="password" class="form-control" placeholder="Enter your password" required>
        </div>
        <button type="submit" class="btn btn-primary">Sign In</button>
        <p class="signup-link">Don't have an account? <a href="signup.php">Sign Up</a></p>
    </form>
</div>

<?php if ($loginSuccess && $userData): ?>
<script>
    // Store user data in localStorage
    localStorage.setItem('loggedInUser', <?php echo json_encode($userData['name']); ?>);
    localStorage.setItem('userId', <?php echo json_encode($userData['id']); ?>);
    localStorage.setItem('userEmail', <?php echo json_encode($userData['email']); ?>);
    
    // Show success message
    alert('Login successful! Welcome <?php echo addslashes($userData['name']); ?>');
    
    // Redirect to index page
    window.location.href = 'index.html';
</script>
<?php endif; ?>

</body>
</html>