<?php
session_start();


// Έλεγχος αν ο χρήστης είναι συνδεδεμένος
if (!isset($_SESSION['email']) OR $_SESSION['type'] != 'PROF') {
    header("Location: logout.php");
    exit();
}

// Χρήστης που συνδέθηκε
$userEmail = $_SESSION['email'];

// Λογική για αποσύνδεση
if (isset($_POST['logout'])) {
    header("Location: logout.php");
    exit();
}


// Ελέγχουμε αν τα πεδία πρέπει να είναι ενεργοποιημένα
$enableFields = $_SESSION['enable_fields'] ?? false;
unset($_SESSION['enable_fields']); // Καθαρισμός της μεταβλητής μετά την πρώτη εμφάνιση


if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $dipId = $_POST['diplomaId'] ?? null;
    $studentEmail = $_POST['studentEmail'] ?? null;
    $meetNumber = $_POST['meetNumber'] ?? null;
    $meetYear = $_POST['meetYear'] ?? null;

    if (!$dipId || !$studentEmail) {
        $message = "Παρακαλώ εισάγετε και τα δύο πεδία: Αριθμό Διπλώματος και Email Φοιτητή.";
    } elseif ($enableFields) {
        // Κλήση για την ανάκληση διπλωματικής όταν τα πεδία είναι ενεργοποιημένα
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, "recall_thesis.php");
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_POST, true);
        curl_setopt($ch, CURLOPT_POSTFIELDS, http_build_query([
            'diplomaId' => $dipId,
            'studentEmail' => $studentEmail,
            'meetNumber' => $meetNumber,
            'meetYear' => $meetYear
        ]));
        curl_setopt($ch, CURLOPT_HTTPHEADER, ['Content-Type: application/x-www-form-urlencoded']);
        
        $response = curl_exec($ch);
        curl_close($ch);
        
        $responseData = json_decode($response, true);
        
        if ($responseData['status'] === 'success') {
            // Αν είναι επιτυχής η ανάκληση, ανακατευθύνουμε στη σελίδα Μηνύματος
            header('Location: message.php');
            exit;
        } else {
            $message = "Η διαδικασία ανάκλησης απέτυχε.";
        }
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Professor Dashboard</title>
    <link rel="stylesheet" type="text/css" href="professor2_2.css">
</head>
<body>

    <!-- Navigation bar -->
    <div class="navbar">
	
		<div class="logo">
    			<a href="professor.php">
			<img src="../media/logo.png" alt="Logo" class="logo-img">
		</div>
	
        <div class="menu">
            <!-- Ενότητες με υπομενού που οδηγούν στο professor.php -->
            <div>
                <a href="professor.php" class="menu-item">Θέματα</a>
            </div>
            <div>
                <a href="professor2.php" class="menu-item">Αναθέσεις</a>
                <div class="submenu">
                    <a href="professor2.php">Ανάθεση</a>
                    <a href="professor2_2.php">Ακύρωση Ανάθεσης</a>
                </div>
            </div>
            <div>
                <a href="professor3.php" class="menu-item">Διπλωματικές</a>
            </div>
            <div>
                <a href="professor4.php" class="menu-item">Προσκλήσεις</a>
            </div>
            <div>
                <a href="professor5.php" class="menu-item">Στατιστικά</a>
            </div>
         
        </div>

        <!-- Στοιχεία χρήστη -->
        <div class="user-info">
            <form action="" method="post" style="display:inline;">
                <button type="submit" name="logout" class="logout-btn">Logout</button>
            </form>
            <span><?php echo htmlspecialchars($userEmail);?></span>
        </div>
    </div>


    <!-- Content -->
    <div class="form-container">
        <h2>Ανάκληση Διπλωματικής</h2>

        <form id="studentForm" onsubmit="recallThesis(event)" method="POST">
            <label for="diplomaId">ID</label>
            <input type="text" id="diplomaId" name="diplomaId" placeholder="Εισάγετε τον κωδικό Διπλωματικής" class="form-input" required>

            <label for="studentEmail">Ηλεκτρονικό Ταχυδρομείο</label>
            <input type="text" id="studentEmail" name="studentEmail" placeholder="Εισάγετε το email του φοιτητή" class="form-input" required>

			<!-- Hidden Fields -->
			<div id="meetingFields">
				<h3 style="color: rgb(255, 0, 0); text-align: center; margin-bottom: 20px; 
					font-size: 16px; background-color: #FFF; padding: 10px; border-radius: 10px;">
					*Εισάγετε τα παρακάτω μόνο για ανάκληση οριστικοποιημένης διπλωματικής
				</h3>
				
				<label for="meetNumber">Αριθμός Γενικής Συνέλευσης</label>
				<input type="text" id="meetNumber" name="meetNumber" 
				placeholder="Εισάγετε τον αριθμό της Γενικής Συνέλευσης" 
				class="form-input">

				<label for="meetYear">Έτος Γενικής Συνέλευσης</label>
				<input type="text" id="meetYear" name="meetYear" 
				placeholder="Εισάγετε το έτος της Γενικής Συνέλευσης" 
				class="form-input">
			</div>


            <div class="button-wrapper">
                <button type="submit" class="form-button submit-btn">Ανάκληση</button>
                <button type="button" onclick="clearForm()" class="form-button clear-btn">Καθαρισμός</button>
            </div>
        </form>
	</div>


	<footer>
		<p>Computer Engineering and Informatics Department</p>
	</footer>
	
		
	
<script>
// συνάρτηση καθαρισμού φόρμας ανάκλησης Διπλωματικής
function clearForm() {
    document.getElementById('diplomaId').value = '';
    document.getElementById('studentEmail').value = '';
    document.getElementById('meetYear').value = '';
	document.getElementById('meetNumber').value = '';
}




function recallThesis(event){
	event.preventDefault(); // Prevent default form submission

    // Get the form element
    const form = document.getElementById("studentForm");
    const formData = new FormData(form); // Collect form data

    // Send the form data via AJAX
    const xhr = new XMLHttpRequest();
    xhr.open("POST", "recall_thesis.php", true);
    xhr.onload = function () {
        if (xhr.status === 200) {
            const response = JSON.parse(xhr.responseText);
            if (response.success1) {
                alert("Η ανάκληση της ανάθεσης πραγματοποιήθηκε επιτυχώς!"); 					// Success1 alert
				clearForm();
            } else if(response.success2){
				alert("Η ακύρωση της ανάθεσης διπλωματικής πραγματοποιήθηκε επιτυχώς!"); 		// Success2 alert
				clearForm();
			}else{
                alert("Error: " + response.error); 												// Error alert
            }
        } else {
            alert("Server error. Please try again.");
        }
    };
    xhr.send(formData); // Send the form data
}

</script>

</body>
</html>
