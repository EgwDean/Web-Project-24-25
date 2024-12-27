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
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Professor Dashboard</title>
	<link rel="stylesheet" type="text/css" href="professor4.css">
</head>
<body onload="get()">

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



            <!-- Τίτλος στο κέντρο του δεξιού div -->
            <h1 class="table-title">Αιτήσεις</h1>

			<div class="table-container">
				<table id="item_table"></table>
			</div>


</div>

        <div class="right-section">



    <!-- Φόρμα για τη νέα είσοδο -->
<form id="studentForm" method="POST" class="form-container">
    <h2>Απάντηση σε αίτηση τριμελούς</h2>
    
    <label for="Id">ID</label>
    <input type="text" id="Id" name="Id" placeholder="ID Διπλωματικής" class="form-input" style="pointer-events: none; background-color: lightgray; cursor: not-allowed;">
    
    <div class="button-wrapper">
        <button type="submit" onclick="accept(event)" class="form-button submit-btn">Accept</button>
        <button type="submit" onclick="decline(event)" class="form-button clear-btn">Decline</button>
    </div>
</form>

        </div>
    </div>

	<footer>
		<p>&copy; 2024 University of Patras - All Rights Reserved</p>
	</footer>
	
	
	
<script>
function get() {
    var table = document.getElementById("item_table");
    var xhr = new XMLHttpRequest();

    xhr.open('GET', 'invites.php');
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

                cell1.innerHTML = "Email Φοιτητή";
                cell2.innerHTML = "Id Διπλωματικής";
                cell3.innerHTML = "Τίτλος Διπλωματικής";
                cell4.innerHTML = "Email Καθηγητή";

                var tbody = table.createTBody();
                for (let i = 0; i < data.length; i++) {
                    var row = tbody.insertRow(-1);
                    var cell1 = row.insertCell(0);
                    var cell2 = row.insertCell(1);
                    var cell3 = row.insertCell(2);
                    var cell4 = row.insertCell(3);

                    cell1.innerHTML = data[i]['student'];
                    cell2.innerHTML = data[i]['kodikos'];
                    cell3.innerHTML = data[i]['titlos'];
                    cell4.innerHTML = data[i]['email'];

                    // Προσθήκη event listener για την επιλογή γραμμής
                    row.addEventListener('click', function() {
                        selectRow(row, data[i]['kodikos']);
                    });
                }
            } else {
                console.error("Error fetching data");
            }
        }
    };
}

// Επιλογή γραμμής και ενημέρωση της φόρμας
function selectRow(row, diplomaId) {
    // Καθαρισμός προηγούμενης επιλογής
    const rows = document.querySelectorAll('#item_table tbody tr');
    rows.forEach(r => r.classList.remove('selected-row'));

    // Σήμανση της επιλεγμένης γραμμής
    row.classList.add('selected-row');

    // Ενημέρωση του πεδίου ID στη φόρμα
    const idField = document.getElementById('Id');
    idField.value = diplomaId;

    // Εμφάνιση της φόρμας
    const form = document.getElementById('studentForm');
    form.style.display = 'block'; // Εμφάνιση της φόρμας
}

function setFormAction(actionUrl) {
    const form = document.getElementById('studentForm');
    form.action = actionUrl; // Ορισμός του action της φόρμας δυναμικά
}



function accept(event){
	event.preventDefault(); // Prevent default form submission

    // Get the form element
    const form = document.getElementById("studentForm");
    const formData = new FormData(form); // Collect form data

    // Send the form data via AJAX
    const xhr = new XMLHttpRequest();
    xhr.open("POST", "accept.php", true);
    xhr.onload = function () {
        if (xhr.status === 200) {
            const response = JSON.parse(xhr.responseText);
            if (response.success) {
                alert("Invitation accepted successfully!"); 	// Success alert
				form.style.display = 'none';					// Hide form after alert
				location.reload();								// Refresh the page to see changes
            } else {
                alert("Error: " + response.error); 	// Error alert
            }
        } else {
            alert("Server error. Please try again.");
        }
    };
    xhr.send(formData); // Send the form data
}


function decline(event){
	event.preventDefault(); // Prevent default form submission

    // Get the form element
    const form = document.getElementById("studentForm");
    const formData = new FormData(form); // Collect form data

    // Send the form data via AJAX
    const xhr = new XMLHttpRequest();
    xhr.open("POST", "decline.php", true);
    xhr.onload = function () {
        if (xhr.status === 200) {
            const response = JSON.parse(xhr.responseText);
            if (response.success) {
                alert("Invitation declined successfully!"); 	// Success alert
				form.style.display = 'none';					// Hide form after alert
				location.reload();								// Refresh the page to see changes
            } else {
                alert("Error: " + response.error); 	// Error alert
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
