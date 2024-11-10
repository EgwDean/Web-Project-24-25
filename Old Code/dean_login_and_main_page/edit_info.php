<?php
session_start();

if (!isset($_SESSION['username'])) {
    header("Location: login.php");
    exit();
}

$dsn = "mysql:host=localhost;port=3306;dbname=html";
$dbusername = "root";
$dbpassword = "Matsaniarakos9";

$username = $_SESSION['username'];

try {
    $pdo = new PDO($dsn, $dbusername, $dbpassword);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    if ($_SESSION['user_type'] == 'USER') {
        $stmt = $pdo->prepare("SELECT * FROM users WHERE username = :username");
    } else {
        $stmt = $pdo->prepare("SELECT * FROM admins WHERE username = :username");
    }
    $stmt->execute([':username' => $username]);
    $user = $stmt->fetch(PDO::FETCH_ASSOC);

    if ($_SERVER["REQUEST_METHOD"] == "POST") {
        $email = $_POST['email'];
        $firstname = $_POST['firstname'];
        $lastname = $_POST['lastname'];
        $password = $_POST['password'];

        if ($_SESSION['user_type'] == 'USER') {
            $updateStmt = $pdo->prepare("UPDATE users SET email = :email, firstname = :firstname, lastname = :lastname, password = :password WHERE username = :username");
        } else {
            $updateStmt = $pdo->prepare("UPDATE admins SET email = :email, firstname = :firstname, lastname = :lastname, password = :password WHERE username = :username");
        }
        $updateStmt->execute([
            ':email' => $email,
            ':firstname' => $firstname,
            ':lastname' => $lastname,
            ':password' => $password,
            ':username' => $username
        ]);

        echo "Information updated successfully!";
    }

} catch (PDOException $e) {
    echo "Connection failed: " . $e->getMessage();
    exit;
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit Your Information</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            margin: 0;
            padding: 0;
            background: url('https://raw.githubusercontent.com/EgwDean/Multimedia/main/pine.jpg') center center / cover no-repeat; /* Full background image */
            height: 100vh;
        }
        header {
            background-color: rgba(0, 0, 0, 0.5);
            padding: 10px 0;
            color: white;
            width: 100%;
            position: fixed;
            top: 0;
            left: 0;
            z-index: 1000;
        }
        .nav-menu {
            display: flex;
            justify-content: center;
            gap: 0;
            position: relative;
        }
        .nav-menu a {
            text-decoration: none;
            background-color: transparent;
            color: white;
            padding: 10px 20px;
            border: none;
            border-radius: 0;
            transition: background-color 0.3s ease;
            font-size: 16px;
            display: inline-block;
            margin: 0;
        }
        .nav-menu a:hover {
            background-color: rgba(255, 255, 255, 0.2);
        }
        .dropdown {
            position: relative;
        }
        .dropdown-content {
            display: none;
            position: absolute;
            background-color: rgba(255, 255, 255, 0.2);
            min-width: 160px;
            box-shadow: 0px 8px 16px rgba(0,0,0,0.2);
            z-index: 1;
        }
        .dropdown:hover .dropdown-content {
            display: block;
        }
        .dropdown-content a {
            color: white;
            padding: 12px 16px;
            text-decoration: none;
            display: block;
        }
        .dropdown-content a:hover {
            background-color: rgba(255, 255, 255, 0.3);
        }
        main {
            margin-top: 60px;
            display: flex;
            flex-direction: column;
            justify-content: center; 
            align-items: center; 
            height: calc(100vh - 60px); 
            text-align: center; 
        }
        h2 {
            color: white; 
            font-size: 36px; 
            margin-bottom: 20px; 
            transform: translateY(100%); 
            opacity: 0; 
            animation: slideIn 0.8s forwards; 
        }
        @keyframes slideIn {
            to {
                transform: translateY(0); 
                opacity: 1; 
            }
        }
        form {
            max-width: 600px;
            margin: 0 auto;
            padding: 20px; 
            border-radius: 8px; 
        }
        label {
            display: block;
            margin-bottom: 10px;
            font-weight: bold;
            color: white; 
        }
        input[type="text"], input[type="email"], input[type="password"] {
            width: 100%;
            padding: 10px;
            margin-bottom: 20px;
            border: 1px solid rgba(255, 255, 255, 0.5); 
            border-radius: 5px;
            background-color: transparent; 
            color: white; 
            caret-color: white; 
        }
        input[type="submit"] {
            background-color: transparent; 
            color: white; 
            padding: 10px 20px; 
            border: 1px solid white; 
            border-radius: 5px; 
            cursor: pointer; 
        }
        input[type="submit"]:hover {
            background-color: rgba(255, 255, 255, 0.2); 
        }
    </style>
</head>
<body>

<header>
    <nav class="nav-menu">
        <a href="main_page.php">Home</a>
        <div class="dropdown">
            <a href="javascript:void(0)">Account</a>
            <div class="dropdown-content">
                <a href="edit_info.php">Edit Info</a>
                <a href="javascript:void(0)" onclick="confirmDelete()">Delete Account</a>
            </div>
        </div>
        <a href="logout.php">Logout</a>
    </nav>
</header>

<main>
    <h2>Personal Data</h2> 
    <form method="post" action="edit_info.php">
        <label for="username">Username (cannot be changed)</label>
        <input type="text" id="username" name="username" value="<?php echo htmlspecialchars($user['username']); ?>" readonly>

        <label for="email">Email</label>
        <input type="email" id="email" name="email" value="<?php echo htmlspecialchars($user['email']); ?>" required>

        <label for="firstname">First Name</label>
        <input type="text" id="firstname" name="firstname" value="<?php echo htmlspecialchars($user['firstname']); ?>" required>

        <label for="lastname">Last Name</label>
        <input type="text" id="lastname" name="lastname" value="<?php echo htmlspecialchars($user['lastname']); ?>" required>

        <label for="password">New Password</label>
        <input type="password" id="password" name="password" required>

        <input type="submit" value="Update Information">
    </form>
</main>

<script>
    function confirmDelete() {
        if (confirm("Are you sure?")) {
            window.location.href = "delete_account.php";
        }
    }
</script>

</body>
</html>
