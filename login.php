<?php
session_start();
include 'db.php';

/**
 * Handle sign up and sign in.
 *
 * Passwords are stored with password_hash() (bcrypt) and verified with
 * password_verify(). All queries use prepared statements to prevent SQL
 * injection.
 */

// SIGN UP
if ($_SERVER['REQUEST_METHOD'] === 'POST' && ($_POST['action'] ?? '') === 'register') {
    $username = trim($_POST['username'] ?? '');
    $email    = trim($_POST['email'] ?? '');
    $password = $_POST['password'] ?? '';

    // Make sure the username/email is not already taken.
    $stmt = $conn->prepare('SELECT admin_id FROM tb_admin WHERE username = ? OR admin_email = ?');
    $stmt->bind_param('ss', $username, $email);
    $stmt->execute();
    $stmt->store_result();
    if ($stmt->num_rows > 0) {
        echo "<script>alert('Username or Email already exists!'); window.location='login.php';</script>";
        exit;
    }
    $stmt->close();

    $hash = password_hash($password, PASSWORD_DEFAULT);

    $stmt = $conn->prepare(
        'INSERT INTO tb_admin (admin_name, username, password, admin_telp, admin_email, admin_address)
         VALUES (?, ?, ?, "", ?, "")'
    );
    $stmt->bind_param('ssss', $username, $username, $hash, $email);

    if ($stmt->execute()) {
        echo "<script>alert('Sign up successful! Please sign in.'); window.location='login.php';</script>";
    } else {
        echo "<script>alert('Sign up failed!'); window.location='login.php';</script>";
    }
    exit;
}

// SIGN IN
if ($_SERVER['REQUEST_METHOD'] === 'POST' && ($_POST['action'] ?? '') === 'login') {
    $username = trim($_POST['username'] ?? '');
    $password = $_POST['password'] ?? '';

    $stmt = $conn->prepare('SELECT admin_id, password FROM tb_admin WHERE username = ?');
    $stmt->bind_param('s', $username);
    $stmt->execute();
    $result = $stmt->get_result();
    $admin  = $result->fetch_assoc();

    if ($admin && password_verify($password, $admin['password'])) {
        $_SESSION['login']    = true;
        $_SESSION['admin_id'] = $admin['admin_id'];
        $_SESSION['username'] = $username;
        echo "<script>alert('Login successful!'); window.location='dashboard.php';</script>";
    } else {
        echo "<script>alert('Login failed! Username or password incorrect.'); window.location='login.php';</script>";
    }
    exit;
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1" />
    <title>Company</title>
    <link rel="stylesheet" href="css/login.css" />
    <script src="js/login.js" defer></script>
    <link href="https://fonts.googleapis.com/css?family=Open+Sans:400,300,700" rel="stylesheet" type="text/css">
</head>
<body>
    <div id="container" class="container">
        <!-- FORM SECTION -->
        <div class="row">
            <!-- SIGN UP -->
            <div class="col align-items-center flex-col sign-up">
                <div class="form-wrapper align-items-center">
                    <form class="form sign-up" method="POST" action="login.php" onsubmit="return validateRegisterForm()">
                        <input type="hidden" name="action" value="register" />
                        <div class="input-group">
                            <i class='bx bxs-user'></i>
                            <input type="text" name="username" placeholder="Username" required>
                        </div>
                        <div class="input-group">
                            <i class='bx bx-mail-send'></i>
                            <input type="email" name="email" placeholder="Email" required>
                        </div>
                        <div class="input-group">
                            <i class='bx bxs-lock-alt'></i>
                            <input type="password" name="password" placeholder="Password" required>
                        </div>
                        <div class="input-group">
                            <i class='bx bxs-lock-alt'></i>
                            <input type="password" name="confirm_password" placeholder="Confirm password" required>
                        </div>
                        <button type="submit">Sign up</button>
                        <p>
                            <span>Already have an account?</span>
                            <b onclick="toggle()" class="pointer">Sign in here</b>
                        </p>
                    </form>
                </div>
            </div>
            <!-- END SIGN UP -->
            <!-- SIGN IN -->
            <div class="col align-items-center flex-col sign-in">
                <div class="form-wrapper align-items-center">
                    <form class="form sign-in" method="POST" action="login.php">
                        <input type="hidden" name="action" value="login" />
                        <div class="input-group">
                            <i class='bx bxs-user'></i>
                            <input type="text" name="username" placeholder="Username" required>
                        </div>
                        <div class="input-group">
                            <i class='bx bxs-lock-alt'></i>
                            <input type="password" name="password" placeholder="Password" required>
                        </div>
                        <button type="submit">Sign in</button>
                        <p>
                            <b>Forgot password?</b>
                        </p>
                        <p>
                            <span>Don't have an account?</span>
                            <b onclick="toggle()" class="pointer">Sign up here</b>
                        </p>
                    </form>
                </div>
            </div>
            <!-- END SIGN IN -->
        </div>
        <!-- END FORM SECTION -->
        <!-- CONTENT SECTION -->
        <div class="row content-row">
            <!-- SIGN IN CONTENT -->
            <div class="col align-items-center flex-col">
                <div class="text sign-in">
                    <h2>Welcome</h2>
                </div>
                <div class="img sign-in"></div>
            </div>
            <!-- END SIGN IN CONTENT -->
            <!-- SIGN UP CONTENT -->
            <div class="col align-items-center flex-col">
                <div class="img sign-up"></div>
                <div class="text sign-up">
                    <h2>Join with us</h2>
                </div>
            </div>
            <!-- END SIGN UP CONTENT -->
        </div>
        <!-- END CONTENT SECTION -->
    </div>
    <script>
        function validateRegisterForm() {
            var password = document.querySelector('input[name="password"]').value;
            var confirmPassword = document.querySelector('input[name="confirm_password"]').value;
            if (password !== confirmPassword) {
                alert("Passwords do not match!");
                return false;
            }
            return true;
        }
    </script>
</body>
</html>
