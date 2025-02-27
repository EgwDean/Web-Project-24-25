<?php
session_start();


// Έλεγχος αν ο χρήστης είναι συνδεδεμένος
if (!isset($_SESSION['email']) OR $_SESSION['type'] != 'PROF') {
    header("Location: logout.php");
    exit();
}

// Λήψη του ID
$id = isset($_GET['id']) ? $_GET['id'] : null;

if ($id) {
    // Στοιχεία σύνδεσης με τη βάση δεδομένων
    $servername = "localhost";
    $username = "root";
    $password = explode(' ', file_get_contents('../dbpassword.txt'))[0];
    $dbname = "diplomatiki_support";

    // Σύνδεση στη βάση δεδομένων
    $conn = new mysqli($servername, $username, $password, $dbname);

    // Έλεγχος σύνδεσης
    if ($conn->connect_error) {
        die("Connection failed: " . $conn->connect_error);
    }

    // Προετοιμασία του SQL query
    $sql = "SELECT title, pdf_main_diploma, email_stud, supervisor, member1, member2, final_grade, Nemertes_link, praktiko_bathmologisis, external_links 
            FROM anathesi_diplomatikis 
            INNER JOIN trimelis_epitropi_diplomatikis ON anathesi_diplomatikis.id_diploma = trimelis_epitropi_diplomatikis.id_dipl
            INNER JOIN diplomatiki ON anathesi_diplomatikis.id_diploma = diplomatiki.id_diplomatiki
            LEFT JOIN eksetasi_diplomatikis ON trimelis_epitropi_diplomatikis.id_dipl = eksetasi_diplomatikis.id_diplomatikis
            WHERE trimelis_epitropi_diplomatikis.id_dipl = ? 
	    AND ( anathesi_diplomatikis.status = 'active' OR anathesi_diplomatikis.status = 'pending'
            OR anathesi_diplomatikis.status = 'under examination' OR anathesi_diplomatikis.status = 'finished')";

    $stmt = $conn->prepare($sql);
    $stmt->bind_param("i", $id);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        echo json_encode($result->fetch_assoc());
    } else {
        echo json_encode(["error" => "No details found for this ID"]);
    }

    $stmt->close();
    $conn->close();
} else {
    echo json_encode(["error" => "ID is required"]);
}
?>
