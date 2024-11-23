<?php
session_start();

// Έλεγχος αν ο χρήστης είναι συνδεδεμένος
if (!isset($_SESSION['email'])) {
    header("Location: login.php");
    exit();
}

// Έλεγχος αν υπάρχει το id
if (isset($_GET['id'])) {
    $id = intval($_GET['id']);  // Sanitize the ID

    // Στοιχεία σύνδεσης με τη βάση δεδομένων
    $servername = "localhost";
    $username = "root";
    $password = "556782340";
    $dbname = "diplomatiki_support";

    // Σύνδεση στη βάση δεδομένων
    $conn = new mysqli($servername, $username, $password, $dbname);

    // Έλεγχος σύνδεσης με τη βάση δεδομένων
    if ($conn->connect_error) {
        echo json_encode(["success" => false, "error" => "Connection failed: " . $conn->connect_error]);
        exit();
    }

    // Ενημέρωση της κατάστασης
    $sql = "UPDATE anathesi_diplomatikis SET status = 'under examination' WHERE id_diploma = ?";
    $stmt = $conn->prepare($sql);
    
    // Bind the parameters and execute
    $stmt->bind_param("i", $id);

    if ($stmt->execute()) {
        echo json_encode(["success" => true]);
    } else {
        echo json_encode(["success" => false, "error" => $stmt->error]);
    }

    $stmt->close();
    $conn->close();
} else {
    echo json_encode(["success" => false, "error" => "Invalid request"]);
}
?>
<!DOCTYPE html>
<html lang="el">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Μήνυμα</title>
</head>
<body>
    <!-- Αποθήκευση του μηνύματος στο data-attribute -->
    <div id="message" data-message="<?php echo htmlspecialchars($message); ?>"></div>

    <script type="text/javascript">
        window.onload = function() {
            // Λήψη του μηνύματος από το data-attribute
            var message = document.getElementById('message').getAttribute('data-message');
            
            // Αν υπάρχει μήνυμα, να το εμφανίσουμε με alert
            if (message) {
                alert(message);
                
                // Αμέσως μετά την εμφάνιση του alert, ανακατεύθυνση στην σελίδα professor3.php
                window.location.href = "professor3.php"; 
            }
        };
    </script>
</body>
</html>