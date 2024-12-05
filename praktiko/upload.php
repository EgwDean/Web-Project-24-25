<?php
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_FILES['file'])) {
    $targetDir = "praktiko/";  // Ο φάκελος που θέλεις να αποθηκεύσεις το αρχείο

    // Δημιουργία νέου ονόματος για το αρχείο (π.χ. new_ και το όνομα του αρχικού αρχείου)
    $newFileName = "new_" . basename($_FILES["file"]["name"]);
    $targetFile = $targetDir . $newFileName;

    // Κίνδυνοι κατά την αποθήκευση
    if (move_uploaded_file($_FILES["file"]["tmp_name"], $targetFile)) {
        echo "Το αρχείο ανέβηκε επιτυχώς με όνομα: " . $newFileName;
    } else {
        echo "Σφάλμα κατά την αποθήκευση του αρχείου.";
    }
}
?>