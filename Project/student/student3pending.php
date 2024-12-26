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
    if ($_SESSION['status']!="pending") {
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
	<link rel="stylesheet" type="text/css" href="student3pending.css">
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

<div class="table-container">
	<h1>Αίτηση Τριμελούς</h1>
	<div class="search-bar-container">
		<input type="text" id="searchBar" placeholder="Αναζήτηση βάσει ονόματος, επιθέτου ή email...">
		<button class="button" id="sendRequest" disabled>Αποστολή Αίτησης</button>
	</div>
	<div class="table-scroll">
		<table id="item_table">
			<!-- Τα δεδομένα εισάγονται εδώ δυναμικά -->
		</table>
	</div>
</div>

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


	let selectedEmail = null; // Αποθήκευση του email του επιλεγμένου διδάσκοντα
	let selectedRow = null; // Αποθήσευση της γραμμής του πίνακα που έγινε κλικ


	function get() { // Κατασευή πίνακα και γέμισμα με καθηγητές
		const table = document.getElementById("item_table");
		const searchBar = document.getElementById("searchBar");

		let allItems = []; // Αποθήκευση στοιχείων για filtering

		function renderTable(items) {
			table.innerHTML = ""; // Καθαρισμός πίνακα

			const header = table.createTHead(); // Κεφαλίδα πίνακα
			const row = header.insertRow(0);
			const cell1 = row.insertCell(0);
			const cell2 = row.insertCell(1);
			const cell3 = row.insertCell(2);
			cell1.innerHTML = "<b>Όνομα</b>";
			cell2.innerHTML = "<b>Επώνυμο</b>";
			cell3.innerHTML = "<b>Email</b>";
			row.style.backgroundColor = "#00BFFF";
			row.style.color = "white";

			const tbody = table.createTBody(); // Σώμα πίνακα
			items.forEach(item => {
			const row = tbody.insertRow(-1);
			const cell1 = row.insertCell(0);
			const cell2 = row.insertCell(1);
			const cell3 = row.insertCell(2);
			cell1.innerHTML = item.name;
			cell2.innerHTML = item.surname;
			cell3.innerHTML = item.email_professor;

			row.style.cursor = 'pointer'; // Clickable row indicator
			row.addEventListener('click', function () {
				// Ενεργοποίηση κουμπιού αίτησης
				document.getElementById("sendRequest").disabled = false;
				// Reset background color
                if (selectedRow) {
                    selectedRow.style.backgroundColor = ""; // Reset background
					selectedRow.style.color = ""; // Reset color
                }
                // Highlight
                row.style.backgroundColor = "skyblue";
				row.style.color = "white";
                selectedRow = row;

                // Store email για περαιτέρω επεξεργασία
                selectedEmail = item.email_professor; 
			});
			});
	}

	function filterItems(query) { // filtering για το search bar
		query = query.toLowerCase(); // Case-insensitive αναζήτηση για id ή θέμα
		return allItems.filter(item =>
		item.name.toString().toLowerCase().includes(query) || 
		item.surname.toString().toLowerCase().includes(query) || 
		item.email_professor.toLowerCase().includes(query)
		);
	}

	var xhr = new XMLHttpRequest(); // Αποστολή request μέσω AJAX
	xhr.open('GET', 'getProfessorNames.php');
	xhr.send();

	xhr.onload = function () {
		if (xhr.status === 200) { // Αν το request ήταν επιτυχές
			const data = JSON.parse(xhr.responseText); // Parse την απάντηση του server
			if (data.message === "Success") { 
				allItems = data.items; // Ανάκτηση των δεδομένων
				renderTable(allItems);

				// Event Listener για το search bar
				searchBar.addEventListener('input', function () {
					const query = searchBar.value;
					const filteredItems = filterItems(query);
					renderTable(filteredItems);
					document.getElementById("sendRequest").disabled = true;
				});
			} else {
				alert("No items found");
			}
		} else {
			alert("Error fetching data:", xhr.status);
		}
	};

		// Ενέργεια του κουμπιού αίτησης
		document.getElementById('sendRequest').addEventListener('click', function() { 
            // URL για αποστολή id
			const url = 'sendRequest.php?selectedEmail=' + encodeURIComponent(selectedEmail);
            const xhr = new XMLHttpRequest(); // Δημιουργία request
            xhr.open('GET', url, true);
            xhr.onload = function () {
                if (xhr.status === 200) { // Επιτυχές request
                    try {
                        const response = JSON.parse(xhr.responseText);
                        alert(response.message); // Εμφάνιση αποτελέσματος σε alert box

                    } catch (e) {
                        alert("An error occurred while processing the response.");
                    }
                } else {
                    alert("Failed to communicate with the server. Status code: " + xhr.status);
                }
            };

            xhr.send(); // Αποστολή request
    });
}

    document.addEventListener('DOMContentLoaded', get);

</script>

</body>
</html>
