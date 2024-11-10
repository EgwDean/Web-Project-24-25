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


    $stmt = $pdo->query("SELECT * FROM users");
    $users = $stmt->fetchAll(PDO::FETCH_ASSOC);

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
    <title>Main Page</title>
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
        h1 {
            color: white;
            font-size: 96px;
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
    <h1>Welcome to Our Page</h1>
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
