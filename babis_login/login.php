<?php
$dsn = "mysql:host=localhost;port=3306;dbname=html"; // Βάση δεδομένων
$dbusername = "root"; // Όνομα χρήστη
$dbpassword = "556782340"; // Κωδικός

try {
    $pdo = new PDO($dsn, $dbusername, $dbpassword);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    if ($_SERVER["REQUEST_METHOD"] == "POST") {
        $username = $_POST['username'];
        $password = $_POST['password'];


        $stmt = $pdo->prepare("CALL login(?, ?, @ptype)");
        $stmt->bindParam(1, $username);
        $stmt->bindParam(2, $password);
        $stmt->execute();

        // error xoris auto gia kapoion gamhmeno logo
        $stmt->closeCursor();

        $result = $pdo->query("SELECT @ptype AS ptype");
        $row = $result->fetch(PDO::FETCH_ASSOC);

        if ($row['ptype'] == 'USER') {
            //echo "Welcome, User.";
   	    //echo "<script>alert('Welcome User'); window.location.href='display_users.php';</script>";
    	    header("Location: display_users.php");
	    exit(); 
        } elseif ($row['ptype'] == 'ADMIN') {
            // echo "Welcome, Admin.";
   	    // echo "<script>alert('Welcome Admin'); window.location.href='display_admins.php';</script>";
            header("Location: display_admins.php");
            exit();
        } else {
	    echo "<script>alert('Wrong combination, please try again.'); window.location.href='login.html';</script>";
        }
    }

} catch (PDOException $e) {
    echo "Connection failed: " . $e->getMessage();
}

?> 
