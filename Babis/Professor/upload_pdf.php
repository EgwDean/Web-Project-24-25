<?php
// Αρχικοποιούμε τη μεταβλητή για το μήνυμα
$message = ''; 

// Έλεγχος αν το αρχείο PDF έχει επιλεγεί
if (isset($_FILES['pdf_file']) && $_FILES['pdf_file']['error'] == 0) {
    // Λήψη του ID από τη φόρμα
    $id = $_POST['id'];

    // Καθορισμός του μονοπατιού αποθήκευσης του αρχείου
    $uploadDir = 'uploads/';  // Δημιουργήστε τον φάκελο 'uploads' αν δεν υπάρχει
    $fileName = $id . '.pdf';  // Όνομα αρχείου με βάση το ID
    $uploadFile = $uploadDir . $fileName;

    // Έλεγχος αν το αρχείο υπάρχει ήδη
    if (file_exists($uploadFile)) {
        // Αν το αρχείο υπάρχει, κάνουμε overwrite
        if (!unlink($uploadFile)) {
            $message = "Δεν ήταν δυνατή η διαγραφή του υπάρχοντος αρχείου.";
            exit;
        }
    }

    // Μεταφορά του αρχείου στον κατάλογο uploads
    if (move_uploaded_file($_FILES['pdf_file']['tmp_name'], $uploadFile)) {
        $message = "Το αρχείο φορτώθηκε με επιτυχία ως " . $fileName;

        // Σύνδεση στη βάση δεδομένων
        $servername = "localhost";
        $username = "root";
        $password = "556782340";
        $dbname = "diplomatiki_support";

        $conn = new mysqli($servername, $username, $password, $dbname);

        // Έλεγχος σύνδεσης
        if ($conn->connect_error) {
            $message = "Η σύνδεση απέτυχε: " . $conn->connect_error;
        } else {
            // Κλήση της συνάρτησης upload_pdf στη βάση
            $stmt = $conn->prepare("CALL upload_pdf(?)");
            $stmt->bind_param("i", $id);  // Αν το $id είναι ακέραιος τύπος
            $stmt->execute();

            // Κλείσιμο της σύνδεσης
            $stmt->close();
            $conn->close();
        }
    } else {
        $message = "Υπήρξε πρόβλημα με την αποθήκευση του αρχείου.";
        exit;
    }
} else {
    $message = "Παρακαλώ επιλέξτε ένα αρχείο PDF για να το ανεβάσετε.";
    exit;
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
                
                // Αμέσως μετά την εμφάνιση του alert, ανακατεύθυνση στην σελίδα professor.php
                window.location.href = "professor.php"; // Προσαρμόστε τη διεύθυνση εάν χρειάζεται
            }
        };
    </script>
</body>
</html>
