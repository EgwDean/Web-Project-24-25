<?php
session_start();

// Έλεγχος αν ο χρήστης είναι συνδεδεμένος
if (!isset($_SESSION['email']) OR $_SESSION['type'] != 'PROF' ) {
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
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Professor Dashboard</title>
    <link rel="stylesheet" type="text/css" href="professor2.css">
</head>

<body onload="getData('')">

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



     <!-- Περιεχόμενο -->
    <div class="container">




<div class="left-section">
    <!-- Φόρμα για τη νέα είσοδο -->
<form id="studentForm" onsubmit="assignThesis(event)" method="POST" class="form-container">
    <h2>Ανάθεση Διπλωματικής</h2>
    
    <label for="studentId">ID</label>
    <input type="text" id="studentId" name="studentId" placeholder="Εισάγετε τον κωδικό Διπλωματικής" class="form-input" required>
    
    <label for="studentNumber">Αριθμός Φοιτητή</label>
    <input type="text" id="studentNumber" name="studentNumber" placeholder="Εισάγετε τον αριθμό φοιτητή" class="form-input" required>
    
    <label for="studentEmail">Ηλεκτρονικό Ταχυδρομείο</label>
    <input type="text" id="studentEmail" name="studentEmail" placeholder="Εισάγετε το email του φοιτητή" class="form-input" required>
    
    <div class="button-wrapper">
        <button type="submit" class="form-button submit-btn">Υποβολή</button>
        <button type="button" onclick="clearForm()" class="form-button clear-btn">Καθαρισμός</button>
    </div>
</form>
</div>

        <div class="right-section">
            <!-- Τίτλος στο κέντρο του δεξιού div -->
            <h1 class="table-title">Φοιτητές</h1>

            <!-- Φίλτρο πάνω από τον πίνακα και αριστερά -->
            <div class="filter-section">
                <button onclick="filterTable()" class="filter-button">Φίλτρο</button>
                <input type="text" id="filterInput" placeholder="Αναζήτηση... " class="filter-input">
            </div>
			
			<div class="table-container">
				<table id="item_table"></table>
			</div>
			
        </div>
    </div>

	<footer>
		<p>Computer Engineering and Informatics Department</p>
	</footer>





<script>
function getData(filterValue = '') {
    var table = document.getElementById("item_table");
    var xhr = new XMLHttpRequest();
    
    // Στέλνουμε το φίλτρο ως παράμετρο στο URL της κλήσης
    xhr.open('GET', 'print_students.php?filter=' + encodeURIComponent(filterValue), true);
    xhr.send();

    xhr.onreadystatechange = function() {
        if (xhr.readyState === XMLHttpRequest.DONE) {
            if (xhr.status === 200) {
                var data = JSON.parse(xhr.responseText)['items'];

                // Διαγραφή προηγούμενων δεδομένων στον πίνακα
                table.innerHTML = '';

                // Δημιουργία επικεφαλίδων στον πίνακα
                var header = table.createTHead();
                var row = header.insertRow(0);
                var cell1 = row.insertCell(0);
                var cell2 = row.insertCell(1);
                var cell3 = row.insertCell(2);
                var cell4 = row.insertCell(3);
                cell1.style.backgroundColor = "#00BFFF";
                cell1.style.color = "white";
                cell2.style.backgroundColor = "#00BFFF";
                cell2.style.color = "white";
                cell3.style.backgroundColor = "#00BFFF";
                cell3.style.color = "white";
                cell4.style.backgroundColor = "#00BFFF";
                cell4.style.color = "white";

                cell1.innerHTML = "Όνομα";
                cell2.innerHTML = "Επώνυμο";
                cell3.innerHTML = "ΑΜ";
                cell4.innerHTML = "Email";

                var tbody = table.createTBody();
                for (let i = 0; i < data.length; i++) {
                    var row = tbody.insertRow(-1);
                    var cell1 = row.insertCell(0);
                    var cell2 = row.insertCell(1);
                    var cell3 = row.insertCell(2);
                    var cell4 = row.insertCell(3);

                    cell1.innerHTML = data[i]['name'];
                    cell2.innerHTML = data[i]['surname'];
                    cell3.innerHTML = data[i]['student_number'];
                    cell4.innerHTML = data[i]['email_student'];


		    // Προσθήκη του event listener για το κλικ στη γραμμή
                    row.addEventListener('click', rowClickHandler);
                }
            } else {
                console.error("Error fetching data");
            }
        }
    };
}

// Συνάρτηση φίλτρου που καλεί την getData με το φίλτρο.
function filterTable() {
    var filterValue = document.getElementById("filterInput").value; // Λαμβάνουμε την τιμή του φίλτρου
    getData(filterValue); // Ανανεώνουμε τα δεδομένα με το φίλτρο
}

function clearForm() {
    document.getElementById('studentId').value = '';
    document.getElementById('studentNumber').value = '';
    document.getElementById('studentEmail').value = '';
}


function rowClickHandler(event) {
    var row = event.target.closest('tr'); // Βρίσκουμε τη γραμμή του πίνακα που έγινε κλικ
    if (!row) return;

    // Παίρνουμε τα δεδομένα από τα κελιά της γραμμής
    var studentNumber = row.cells[2].textContent; // Η 4η στήλη είναι ο αριθμός φοιτητή
    var studentEmail = row.cells[3].textContent;  // Η 5η στήλη είναι το email του φοιτητή

    // Συμπληρώνουμε τα πεδία στη φόρμα
    document.getElementById('studentNumber').value = studentNumber;
    document.getElementById('studentEmail').value = studentEmail;
}


function assignThesis(event){
	event.preventDefault(); // Prevent default form submission

    // Get the form element
    const form = document.getElementById("studentForm");
    const formData = new FormData(form); // Collect form data

    // Send the form data via AJAX
    const xhr = new XMLHttpRequest();
    xhr.open("POST", "assign_thesis.php", true);
    xhr.onload = function () {
        if (xhr.status === 200) {
            const response = JSON.parse(xhr.responseText);
            if (response.success) {
                alert("Επιτυχής Ανάθεση!"); 		// Success alert
				clearForm();
            } else {
                alert("Error: " + response.error); 				// Error alert
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
