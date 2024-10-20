<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Sign Up</title>
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
            justify-content: center;
            align-items: center;
            z-index: 2;
            width: 100%;
            height: 100%;
        }

        .form-container {
            background: transparent;
            padding: 20px;
            border-radius: 5px;
            width: 350px;
            text-align: center;
            position: relative;
        }

        h1 {
            text-align: center;
            font-size: 48px;
            margin-bottom: 20px;
            color: white;
        }

        input[type="text"], input[type="password"], input[type="email"] {
            width: 100%;
            padding: 10px;
            margin: 10px 0;
            border: 1px solid white;
            border-radius: 5px;
            box-sizing: border-box;
            background-color: transparent; 
            color: white;
        }

        input[type="text"]:focus,
        input[type="password"]:focus,
        input[type="email"]:focus {
            outline: none;
            border-color: rgba(255, 255, 255, 0.5);
        }

        input[type="submit"] {
            background-color: rgba(255, 255, 255, 0.2);
            color: white;
            border: none;
            padding: 10px;
            cursor: pointer;
            border-radius: 5px;
            width: 100%;
            opacity: 0.7;
            transition: opacity 0.3s ease;
        }

        input[type="submit"]:hover {
            opacity: 1;
        }

        .error {
            color: red; 
            margin: 5px 0;
            font-size: 12px;
            text-align: center;
        }
    </style>
    <script>
        function showPopup() {
            alert("Account created successfully!");
            window.location.href = 'login.php';
        }
    </script>
</head>
<body>
    <div class="container">
        <div class="form-container">
            <h1>Sign Up</h1>
            <form action="insert.php" method="POST">
                <input type="text" name="username" placeholder="Username" required value="<?php echo isset($_POST['username']) ? htmlspecialchars($_POST['username']) : ''; ?>">
                <input type="password" name="password" placeholder="Password" required value="<?php echo isset($_POST['password']) ? htmlspecialchars($_POST['password']) : ''; ?>">
                <input type="text" name="firstname" placeholder="First Name" required value="<?php echo isset($_POST['firstname']) ? htmlspecialchars($_POST['firstname']) : ''; ?>">
                <input type="text" name="lastname" placeholder="Last Name" required value="<?php echo isset($_POST['lastname']) ? htmlspecialchars($_POST['lastname']) : ''; ?>">
                <input type="email" name="email" placeholder="Email" required value="<?php echo isset($_POST['email']) ? htmlspecialchars($_POST['email']) : ''; ?>">
                <input type="submit" value="Sign Up">
            </form>

            <?php
            session_start();
            $username_error = "";
            $email_error = "";

            if ($_SERVER["REQUEST_METHOD"] == "POST") {
                $dsn = "mysql:host=localhost;port=3306;dbname=html";
                $dbusername = "root";
                $dbpassword = "Matsaniarakos9";

                try {
                    $pdo = new PDO($dsn, $dbusername, $dbpassword);
                    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

                    $username = $_POST['username'];
                    $_SESSION['username'] = $username;
                    $password = $_POST['password'];
                    $firstname = $_POST['firstname'];
                    $lastname = $_POST['lastname'];
                    $email = $_POST['email'];

                    $stmt_username = $pdo->prepare("CALL name_taken(?, @username_taken)");
                    $stmt_username->execute([$username]);
                    $stmt_username->closeCursor();
                    $result_username = $pdo->query("SELECT @username_taken")->fetch(PDO::FETCH_ASSOC);
                    $username_taken = $result_username['@username_taken'];

                    $stmt_email = $pdo->prepare("CALL email_taken(?, @email_taken)");
                    $stmt_email->execute([$email]);
                    $stmt_email->closeCursor();
                    $result_email = $pdo->query("SELECT @email_taken")->fetch(PDO::FETCH_ASSOC);
                    $email_taken = $result_email['@email_taken'];

                    if ($username_taken == 1) {
                        $username_error = "Username is already taken. Please choose another.";
                    } elseif ($email_taken == 1) {
                        $email_error = "Email is already in use. Please use another email.";
                    } else {
                        $stmt = $pdo->prepare("INSERT INTO users (username, password, firstname, lastname, email) VALUES (?, ?, ?, ?, ?)");
                        $stmt->execute([$username, $password, $firstname, $lastname, $email]);

                        echo "<script>showPopup();</script>";
                    }
                } catch (PDOException $e) {
                    echo "Connection failed: " . $e->getMessage();
                }
            }
            ?>
            <div class="error"><?php echo $username_error; ?></div>
            <div class="error"><?php echo $email_error; ?></div>
        </div>
    </div>
</body>
</html>