<?php
session_start();
$dsn = "mysql:host=localhost;port=3306;dbname=html";
$dbusername = "root";
$dbpassword = "Matsaniarakos9";
$error = "";
$username = "";
$password = "";

try {
    $pdo = new PDO($dsn, $dbusername, $dbpassword);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    if ($_SERVER["REQUEST_METHOD"] == "POST") {
        $username = $_POST['username'];
        $_SESSION['username'] = $username;
        $password = $_POST['password'];

        $stmt = $pdo->prepare("CALL login(?, ?, @ptype)");
        $stmt->bindParam(1, $username);
        $stmt->bindParam(2, $password);
        $stmt->execute();

        $stmt->closeCursor();

        $result = $pdo->query("SELECT @ptype AS ptype");
        $row = $result->fetch(PDO::FETCH_ASSOC);

        if ($row['ptype'] == 'USER' || $row['ptype'] == 'ADMIN') {
            $_SESSION['user_type'] = $row['ptype'];
            header("Location: main_page.php");
            exit();
        } else {
            $error = "Wrong combination, please try again.";
        }
    }
} catch (PDOException $e) {
    echo "Connection failed: " . $e->getMessage();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login</title>

    <style>
        body {
            margin: 0;
            padding: 0;
            font-family: Arial, sans-serif;
            height: 100vh;
            display: flex;
            justify-content: center;
            align-items: center;
            background: url('https://raw.githubusercontent.com/EgwDean/Multimedia/main/pine.jpg') center center / cover no-repeat;
            position: relative;
        }

        body::before {
            content: "";
            position: absolute;
            bottom: 0;
            left: 0;
            width: 100%;
            height: 30%;
            background: linear-gradient(to bottom, rgba(0, 0, 0, 0) 0%, rgba(0, 0, 0, 0.7) 100%);
            z-index: 1;
        }

        .container {
            display: flex;
            justify-content: space-between;
            width: 100%;
            height: 100%;
        }

        .left-section {
            width: 50%;
            display: flex;
            justify-content: center;
            align-items: center;
            color: white;
            font-size: 96px;
            font-weight: bold;
            z-index: 2;
            transform: translateX(-100%);
            animation: slideIn 0.8s forwards;
        }

        .right-section {
            width: 50%;
            display: flex;
            justify-content: center;
            align-items: center;
            z-index: 2;
        }

        .login-container {
            background-color: transparent;
            padding: 20px;
            border-radius: 8px;
            width: 300px;
            text-align: center;
        }

        label {
            display: block;
            margin-bottom: 5px;
            text-align: left;
            color: white;
        }

        input[type="text"],
        input[type="password"] {
            width: 100%;
            padding: 10px;
            margin-bottom: 15px;
            border: 1px solid white;
            border-radius: 4px;
            box-sizing: border-box;
            background-color: transparent;
            color: white;
        }

        input[type="text"]:focus,
        input[type="password"]:focus {
            outline: none;
            border-color: rgba(255, 255, 255, 0.5);
        }

        .button-group {
            display: flex;
            justify-content: space-between;
        }

        input[type="submit"],
        .signup-btn {
            width: 48%;
            padding: 10px;
            color: white;
            border: none;
            border-radius: 4px;
            cursor: pointer;
            font-size: 16px;
            background-color: rgba(255, 255, 255, 0.2);
            opacity: 0.7;
            transition: opacity 0.3s ease;
        }

        input[type="submit"]:hover,
        .signup-btn:hover {
            opacity: 1;
        }

        .signup-btn {
            text-decoration: none;
            text-align: center;
        }

        .error-message {
            color: red;
            margin-top: 5px;
            font-size: 12px;
        }

        @keyframes slideIn {
            to {
                transform: translateX(0); 
            }
        }
    </style>
</head>
<body>
    <div class="container">
        <!-- Left section for title -->
        <div class="left-section">
            Our Webpage
        </div>

        <!-- Right section for login form -->
        <div class="right-section">
            <div class="login-container">
                <form action="" method="post">
                    <label for="username">Username:</label>
                    <input type="text" id="username" name="username" value="<?php echo htmlspecialchars($username); ?>" required>

                    <label for="password">Password:</label>
                    <input type="password" id="password" name="password" value="<?php echo htmlspecialchars($password); ?>" required>

                    <div class="button-group">
                        <input type="submit" value="Login">
                        <a href="insert.php" class="signup-btn">Sign Up</a>
                    </div>
                </form>
                
                <div class="error-message">
                    <?php if (!empty($error)) echo $error; ?>
                </div>
            </div>
        </div>
    </div>
</body>
</html>