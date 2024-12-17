<?php
session_start();

// Έλεγχος αν ο χρήστης είναι συνδεδεμένος
if (!isset($_SESSION['email'])) {
    header("Location: logout.php");
    exit();
}

$email = $_SESSION['email'];

// Έλεγχος αν τα δεδομένα έχουν αποσταλεί μέσω POST
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Λήψη των δεδομένων από τη φόρμα
    $notes = $_POST['notes'];  
    $id = $_POST['diplomaId'];
    $status = trim($_POST['diplomaStatus']); // Αφαιρεί κενά

    // Έλεγχος αν η διπλωματική είναι ενεργή
    if ($status !== 'active') {
        echo json_encode(["success" => false, "error" => "Η διπλωματική πρέπει να είναι ενεργή για να προσθέσετε σημειώσεις."]); 
    } else {
        // Στοιχεία σύνδεσης με τη βάση δεδομένων
        $servername = "localhost";
        $username = "root";
        $password = "Matsaniarakos9";
        $dbname = "diplomatiki_support";

        // Σύνδεση στη βάση δεδομένων
        $conn = new mysqli($servername, $username, $password, $dbname);

        // Έλεγχος σύνδεσης
        if ($conn->connect_error) {
            die("Connection failed: " . $conn->connect_error);
        }

        // Ετοιμάζουμε την κλήση της stored procedure
        $sql = "CALL createNotes(?, ?, ?, @output)";
        
        if ($stmt = $conn->prepare($sql)) {
            // Δέσιμο των παραμέτρων
            $stmt->bind_param("sis", $email, $id, $notes);

            // Εκτέλεση της διαδικασίας
            if ($stmt->execute()) {
                // Αποδέσμευση της αποθηκευμένης διαδικασίας
                $stmt->close();
                while ($conn->next_result()) {;} // Αδειάζει τα υπόλοιπα αποτελέσματα

                // Ανάκτηση του error_code
                $result = $conn->query("SELECT @output as output");
                if ($result) {
                    $row = $result->fetch_assoc();
                    $output = (int)$row['output'];

                    if ($output === 0) {
                        echo json_encode(["success" => true]);
                    } elseif ($output === null) {
                        echo json_encode(["success" => false, "error" => "Σφάλμα κατά την ανάκτηση του αποτελέσματος."]); 
                    } elseif ($output === 1) {
                        echo json_encode(["success" => false, "error" => "Πρέπει να είστε επιβλέπων ή μέλος για να προσθέσετε σημειώσεις."]);  
                    } else {
                        echo json_encode(["success" => false, "error" => "Άγνωστο σφάλμα. Επικοινωνήστε με τον διαχειριστή."]); 
                    }
                } else {
                    echo json_encode(["success" => false, "error" => "Σφάλμα κατά την ανάκτηση του @output: " . $conn->error]);
                }
            } else {
                echo json_encode(["success" => false, "error" => $stmt->error]);
            }
        } else {
             echo json_encode(["success" => false, "error" => $conn->error]);
        }

        // Κλείσιμο της σύνδεσης
        $conn->close();
    }
}
?>