<?php
// Ελέγχουμε αν τα δεδομένα έχουν αποσταλεί μέσω POST
$message = ''; // Αρχικοποιούμε τη μεταβλητή για το μήνυμα
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Λήψη των δεδομένων από τη φόρμα
    $title = $_POST['title'];  // Τίτλος
    $description = $_POST['description'];  // Περιγραφή

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
            // Ετοιμάζουμε την κλήση της stored procedure
            $sql = "CALL insert_to_dip(?, ?, ?, ?, @output)";
            
            if ($stmt = $conn->prepare($sql)) {
                // Δέσιμο των παραμέτρων
                $status = 'available';
                $stmt->bind_param("ssss", $email, $title, $description, $status);

                // Εκτέλεση της διαδικασίας
                if ($stmt->execute()) {
                    // Αποδέσμευση της αποθηκευμένης διαδικασίας
                    $stmt->close();
                    while ($conn->next_result()) {;} // Αδειάζει τα υπόλοιπα αποτελέσματα

                    // Ανάκτηση της τιμής του @output
                    $result = $conn->query("SELECT @output AS output");
                    if ($result) {
                        $row = $result->fetch_assoc();
                        $output = $row['output'] ?? null;

                        if ($output === null) {
                            $message = "Σφάλμα κατά την ανάκτηση του αποτελέσματος.";
                        } elseif ($output == 0) {
                            $message = "Ο τίτλος χρησιμοποιείται ήδη. Εισάγεται διαφορετικό.";
                        } elseif ($output == 1) {
                            $message = "Η νέα εγγραφή δημιουργήθηκε με επιτυχία!";
                        } else {
                            $message = "Άγνωστο σφάλμα. Επικοινωνήστε με τον διαχειριστή.";
                        }
                    } else {
                        $message = "Σφάλμα κατά την ανάκτηση του @output: " . $conn->error;
                    }
                } else {
                    $message = "Σφάλμα κατά την εκτέλεση της διαδικασίας: " . $stmt->error;
                }
            } else {
                $message = "Σφάλμα κατά την προετοιμασία της διαδικασίας.";
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
                window.location.href = "professor.php"; // Προσαρμόστε τη διεύθυνση εάν χρειάζεται
            }
        };
    </script>
</body>
</html>
