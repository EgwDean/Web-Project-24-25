<?php
session_start();

// Έλεγχος αν ο χρήστης είναι συνδεδεμένος
if (!isset($_SESSION['email'])) {
    header("Location: logout.php");
    exit();
}

if ($_SESSION['type'] != 'STUDENT') {
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

if (isset($_SESSION['status'])) {
    if ($_SESSION['status']!="finished") {
		header("Location: logout.php");
	}
}

?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Student Page</title>
    <link rel="stylesheet" type="text/css" href="student3finished.css">
</head>
<body>
   <!-- Navigation bar -->
    <div class="navbar">
	
		<div class="logo">
			<a href="student.php">
				<img src="/Project/media/logo.png" alt="Logo" class="logo-img">
			</a>
		</div>
	
        <div class="menu">
            <div>
                <a href="student.php" class="menu-item">Προβολή Θέματος</a>
            </div>
            <div>
				<a href="#" class="menu-item" onclick="statusRedirectProccess()">Επεξεργασία Προφίλ</a>
            </div>
            <div>
				<a href="#" class="menu-item" onclick="statusRedirectManagement()">Διαχείριση Διπλωματικής</a>
            </div>
            
        </div>


        <div class="user-info">
            <span><?php echo $userEmail; ?></span>
            <form method="POST" action="">
                <button type="submit" name="logout" class="logout-btn">Logout</button>
            </form>
        </div>
    </div>

	<div class="main-content"> <!-- Main περιεχόμενο -->
		<!-- Πίνακας διπλωματικών -->
		<div class="table-container">
			<h2>Στοιχεία Διπλωματικής</h2>
			<div class="table-wrapper">
				<table id="items_table1">
					<!-- Εδώ μπαίνουν οι γραμμές του πίνακα διπλωματικών -->
				</table>
			</div>
		</div>
			
		<!-- Πίνακας με logs -->
		<div class="table-container">
			<h2>Activity Log</h2>
			<div class="table-wrapper">
				<table id="items_table2">
					<!-- Εδω μπαίνει το log -->
				</table>
			</div>
		</div>
	</div>
	<button id="showPraktikoButton" class="form-button">Προβολή Πρακτικού Εξέτασης</button>
	
	<footer>
		<p>&copy; 2024 University of Patras - All Rights Reserved</p>
	</footer>



<script>	

     // Συνάρτηση που ελέγχει το status διπλωματικής και ανακατευθύνει κατάλληλα με το πάτημα του "Διαχείριση Διπλωματικής"
	 function statusRedirectManagement() { 
            event.preventDefault();

			const urlParams = new URLSearchParams(window.location.search);
			status = urlParams.get("data");

            // Διαφορετικη ανακατευθυνση αναλογα με το status
            if (status === "pending") {
                window.location.href = "student3pending.php?data=pending";
            } else if (status === "under_examination") {
                window.location.href = "student3under_examination.php?data=under_examination";
            } else if (status === "finished") {
                window.location.href = "student3finished.php?data=finished";
            } else if (status === "notfound") {
                alert("Diploma thesis not found.");
            } else {
                alert("Current status of diploma thesis does not allow its management.");
            }

    }
	
     // Συνάρτηση που ελέγχει το status διπλωματικής και ανακατευθύνει κατάλληλα με το πάτημα του "Επεξεργασία Προφίλ"
	 function statusRedirectProccess() { 
            event.preventDefault();

				const urlParams = new URLSearchParams(window.location.search);
    			status = urlParams.get("data");

                // Διαφορετικη ανακατευθυνση αναλογα με το status
                if (status === "pending") {
                    window.location.href = "student2.php?data=pending";
                } else if (status === "under_examination") {
                    window.location.href = "student2.php?data=under_examination";
                } else if (status === "finished") {
                    window.location.href = "student2.php?data=finished";
                } else if (status === "notfound") {
                    window.location.href = "student2.php?data=notfound"
                }
                else {
                    window.location.href = "student2.php?data=other"
                }
            
    }

	// Φόρτωση δεδομένων επιλεγμένης διπλωματικής στον πίνακα
	function getDetails1() {
		var xhr = new XMLHttpRequest(); // Αποστολή request μέσω AJAX
		xhr.open('GET', 'show_diplomatiki_details.php');
		xhr.send();

		xhr.onload = function() {
			if (xhr.status === 200) { // Επιτυχής request
				var data = JSON.parse(xhr.responseText); // Parse απάντησης
				if (data.message === "Success") {
					var detailsDiv = document.getElementById("items_table1"); // Αποθήκευση details section για επεξεργασία

					// Καθαρισμός πίνακα details
					detailsDiv.innerHTML = "";

					// Κατασκευή του πίνακα
					var table = document.createElement("table");
					table.style.width = "100%";
					table.style.borderCollapse = "collapse";

					// Κατασκευή rows επαναληπτικά από τα attributes του object
					var details = data.item;
					for (var key in details) {
					if (details.hasOwnProperty(key)) {
						var row = table.insertRow();
						var cell1 = row.insertCell(0);
						cell1.style.padding = "10px";
						cell1.style.border = "1px solid #000";
						cell1.style.backgroundColor = "#fff";
						cell1.innerHTML = key.replace(/_/g, ' '); // Τα underscores γίνονται κενά
						cell1.style.backgroundColor = "#00BFFF";
						cell1.style.color = "white";
						cell1.style.width = "50px";

						var cell2 = row.insertCell(1);
						cell2.style.padding = "10px";
						cell2.style.border = "1px solid #000";
						cell2.innerHTML = details[key];
					}
					}

					// Προστίθεται ο πίνακας στο details div
					detailsDiv.appendChild(table);
					detailsDiv.style.boxShadow = 'none';
				} else {
					document.getElementById("items_table1").innerHTML = "<p>No details available for this selection.</p>";
				}
			} else {
				alert("Error fetching details:", xhr.status);
			}
		};
	}

	function getDetails2() { // Κατασκευή πίνακα για log
		const table = document.getElementById("items_table2");

		let allItems = [];

		function renderTable(items) {
			//table.innerHTML = ""; // Καθαρισμός πίνακα

			// Δημιουργία κεφαλίδας πίνακα
			const header = table.createTHead();
			const row = header.insertRow(0);
			const cell1 = row.insertCell(0);
			cell1.innerHTML = "<b>Αλλαγή Κατάστασης</b>";
			cell1.style.border = "1px solid #000";
			row.style.backgroundColor = "#00BFFF";
			row.style.color = "white";

			// Δημιουργία σώματος πίνακα
			const tbody = table.createTBody();
			items.forEach(item => {
				const row = tbody.insertRow(-1);
				const cell1 = row.insertCell(0);
				cell1.innerHTML = item.record; // Προβολή του log entry
				cell1.style.border = "1px solid #000";
			});
		}

		const xhr = new XMLHttpRequest(); // Αποστολή request μέσω AJAX
		xhr.open('GET', 'show_log.php', true); // Request προς το show_log.php
		xhr.onload = function () {
			if (xhr.status === 200) { // Αν το request ήταν επιτυχές
				const data = JSON.parse(xhr.responseText); // Parse την απάντηση του server
				if (data.message === "Success") { 
					allItems = data.items; // Ανάκτηση των δεδομένων
					renderTable(allItems); // Κατασκευή του πίνακα με τα δεδομένα
				} else {
					alert("No items found");
				}
			} else {
				alert("Error fetching data: " + xhr.status);
			}
		};
		xhr.send();
	}

	var id = null;
	function getIdAndShowPraktiko() { // Λήψη id διπλωματικής
		const req = new XMLHttpRequest();
		req.open('POST', 'getId.php', true);
		req.onload = function () {
			if (req.status === 200) {
				id = JSON.parse(req.responseText);
			} else {
				console.error('Failed to fetch id from server.');
			}
		}
		req.send();
	}
	
	document.addEventListener("DOMContentLoaded", function () {
		getDetails1();
		getDetails2();
		getIdAndShowPraktiko();
		document.getElementById('showPraktikoButton').addEventListener("click", function () { // Λειτουργία κουμπιού προβολής πρακτικού
			window.open('/Project/uploads/praktiko/' + id + '_praktiko_simplified.html', '_blank'); // Άνοιγμα html
		});
	});

</script>

</body>
</html>
