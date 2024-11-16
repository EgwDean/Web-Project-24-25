<?php
// Ελέγχουμε αν τα δεδομένα έχουν αποσταλεί μέσω POST
$message = ''; // Αρχικοποιούμε τη μεταβλητή για το μήνυμα
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Λήψη των δεδομένων από τη φόρμα
    $id = $_POST['Id'];  // Αριθμός ID για το decline

    // Ξεκινάμε τη συνεδρία αν δεν έχει ξεκινήσει
    session_start();

    // Ελέγχουμε αν υπάρχει email στη συνεδρία
    if (!isset($_SESSION['email'])) {
        $message = "Η συνεδρία δεν είναι ενεργή ή δεν υπάρχει email.";
    } else {
        // Λήψη του email από τη συνεδρία
        $email = $_SESSION['email'];

        // Σύνδεση στη βάση δεδομένων
        $servername = "localhost";
        $username = "root";
        $password = "12345theo";
        $dbname = "diplomatiki_support";

        $conn = new mysqli($servername, $username, $password, $dbname);

        // Έλεγχος σύνδεσης
        if ($conn->connect_error) {
            $message = "Η σύνδεση απέτυχε: " . $conn->connect_error;
        } else {
            // Προετοιμασία του SQL query για το UPDATE
            $sql = "UPDATE prosklisi_se_trimeli
                    SET status = 'declined', reply_date = CURDATE()
                    WHERE prof_email = ? AND id_dip = ?";

            if ($stmt = $conn->prepare($sql)) {
                // Δέσιμο των παραμέτρων
                $stmt->bind_param("ss", $email, $id);  

                // Εκτέλεση του query
                if ($stmt->execute()) {
                    $message = "Η απόφαση καταγράφηκε επιτυχώς ως 'Απορριφθείσα'.";
                } else {
                    $message = "Σφάλμα κατά την εκτέλεση του query: " . $stmt->error;
                }

                // Κλείσιμο της δήλωσης
                $stmt->close();
            } else {
                $message = "Σφάλμα κατά την προετοιμασία της δήλωσης.";
            }

            // Κλείσιμο της σύνδεσης
            $conn->close();
        }
    }
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
    <!-- Το υπόλοιπο περιεχόμενο της σελίδας -->

    <!-- Αποθήκευση του μηνύματος στο data-attribute -->
    <div id="message" data-message="<?php echo htmlspecialchars($message); ?>"></div>

    <script type="text/javascript">
        window.onload = function() {
            // Λήψη του μηνύματος από το data-attribute
            var message = document.getElementById('message').getAttribute('data-message');
            
            // Αν υπάρχει μήνυμα, να το εμφανίσουμε με alert
            if (message) {
                alert(message);
                
                // Αμέσως μετά την εμφάνιση του alert, ανακατεύθυνση στην σελίδα professor.php
                window.location.href = "professor4.php"; // Προσαρμόστε τη διεύθυνση εάν χρειάζεται
            }
        };
    </script>
</body>
</html>
