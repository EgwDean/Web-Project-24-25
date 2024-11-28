<?php

// Ξεκινάμε τη συνεδρία 
session_start();

// Έλεγχος αν ο χρήστης είναι συνδεδεμένος
if (!isset($_SESSION['email'])) {
    header("Location: login.php");
    exit();
}

// Λήψη του email από τη συνεδρία
$email = $_SESSION['email'];


// Ελέγχουμε αν τα δεδομένα έχουν αποσταλεί μέσω POST
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Λήψη των δεδομένων από τη φόρμα
    $id = $_POST['Id'];  // Αριθμός ID για το decline
   

    // Σύνδεση στη βάση δεδομένων
    $servername = "localhost";
    $username = "root";
    $password = "556782340";
    $dbname = "diplomatiki_support";

    $conn = new mysqli($servername, $username, $password, $dbname);

    // Έλεγχος σύνδεσης
    if ($conn->connect_error) {
        die("Connection failed: " . $conn->connect_error);
    }
        
	// Προετοιμασία του SQL query για το UPDATE
    $sql = "UPDATE prosklisi_se_trimeli
            SET status = 'declined', reply_date = CURDATE()
            WHERE prof_email = ? AND id_dip = ?";

    if ($stmt = $conn->prepare($sql)) {
        // Δέσιμο των παραμέτρων
        $stmt->bind_param("ss", $email, $id);  

        // Εκτέλεση του query
        if ($stmt->execute()) {
            echo json_encode(["success" => true]);
        } else {
            echo json_encode(["success" => false, "error" => $stmt->error]);
        }

        // Κλείσιμο της δήλωσης
        $stmt->close();
    } else {
        echo json_encode(["success" => false, "error" => $conn->error]); 
    }

    // Κλείσιμο της σύνδεσης
    $conn->close();
}
?>