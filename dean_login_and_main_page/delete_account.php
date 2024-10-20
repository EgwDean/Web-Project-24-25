<?php
session_start();
$dsn = "mysql:host=localhost;port=3306;dbname=html";
$dbusername = "root";
$dbpassword = "Matsaniarakos9";

$username = $_SESSION['username'];

try {
    $pdo = new PDO($dsn, $dbusername, $dbpassword);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    if ($_SESSION['user_type'] == 'USER')
    $stmt = $pdo->prepare("DELETE FROM users WHERE username = :username");
    else
    $stmt = $pdo->prepare("DELETE FROM admins WHERE username = :username");
    $stmt->execute([':username' => $username]);

    header("Location: logout.php");

} catch (PDOException $e) {
    echo "Error: " . $e->getMessage();
    exit();
}
?>