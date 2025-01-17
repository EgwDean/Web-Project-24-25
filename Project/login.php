<?php
session_start(); // Ξεκινάμε τη συνεδρία

$host = "localhost";
$dbusername = "root";
$dbpassword = explode(' ', file_get_contents('dbpassword.txt'))[0];
$dbname = "diplomatiki_support";
$error = ""; // Μεταβλητή για σφάλματα
$email = ""; // Μεταβλητή για το όνομα χρήστη
$password = ""; // Μεταβλητή για τον κωδικό

// Σύνδεση με τη βάση δεδομένων
$conn = new mysqli($host, $dbusername, $dbpassword, $dbname);

// Έλεγχος σύνδεσης
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Έλεγχος αν η φόρμα έχει υποβληθεί
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $email = $_POST['email'];
    $password = $_POST['password'];

    // Ετοιμάζουμε και εκτελούμε τη διαδικασία
    $stmt = $conn->prepare("CALL login(?, ?, @ptype)");
    $stmt->bind_param("ss", $email, $password);
    $stmt->execute();
    $stmt->close();

    // Λήψη του αποτελέσματος από την έξοδο της αποθηκευμένης μεταβλητής @ptype
    $result = $conn->query("SELECT @ptype AS ptype");
    $row = $result->fetch_assoc();

    // Έλεγχος του τύπου χρήστη και ανακατεύθυνση
    $_SESSION['email'] = $email; // Αποθήκευση του username στη συνεδρία
    $_SESSION['type'] = $row['ptype']; // Αποθήκευση του τύπου χρήστη στη συνεδρία

    if ($row['ptype'] == 'STUDENT') {
        header("Location: student/student.php");
    } elseif ($row['ptype'] == 'PROF') {
        header("Location: professor/professor.php");
    } elseif ($row['ptype'] == 'GRAM') {
        header("Location: secretary/secretary.php");
    } else {
        $error = "Wrong combination, please try again."; // Μήνυμα σφάλματος
    }
}

$conn->close();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login</title>    
	<link rel="stylesheet" type="text/css" href="login.css">
</head>
<body>
    <div class="login-container">
        <h1>Login</h1>
        <form action="" method="post">
            <label for="email">Email:</label>
            <input type="text" id="email" name="email" value="<?php echo htmlspecialchars($email); ?>" required>

            <label for="password">Password:</label>
            <input type="password" id="password" name="password" value="<?php echo htmlspecialchars($password); ?>" required>

            <div class="button-group">
                <input type="submit" value="Login">
            </div>
        </form>
      
        <div class="error-message">
            <?php if (!empty($error)) echo $error; ?>
        </div>
    </div>
</body>
</html>