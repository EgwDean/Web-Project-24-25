<?php
session_start();

// Έλεγχος αν ο χρήστης είναι συνδεδεμένος
if (!isset($_SESSION['email'])) {
    header("Location: login.php");
    exit();
}

// Χρήστης που συνδέθηκε
$userEmail = $_SESSION['email'];

// Λογική για αποσύνδεση
if (isset($_POST['logout'])) {
    session_destroy();
    header("Location: login.php");
    exit();
}

if (isset($_POST['status'])) { // Λήψη του status μέσω AJAX
    $_SESSION['status'] = $_POST['status'];
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Student Page</title>
<style>
	/* Στυλ για τη σελίδα */
	body {
		font-family: Calibri, Arial, sans-serif;
		margin: 0;
		background-color: #f4f4f4;
		display: flex;
		flex-direction: column;
	}

	.navbar {
		background-color: #333;
		color: #fff;
		padding: 15px;
		display: flex;
		justify-content: space-between; /* Χωρίζει τα sections από το user info */
		align-items: center;
	}

	.navbar .menu {
		display: flex;
		gap: 20px;
		justify-content: center; /* Κέντρο μεταξύ τους */
		flex: 1; /* Παίρνει όλο τον διαθέσιμο χώρο */
	}

	.navbar .menu > div {
		position: relative;
	}

	.navbar .menu > div:hover .submenu {
		display: block;
	}

	.menu-item {
        color: #fff;
		background-color: #444;
        text-decoration: none;
        padding: 10px;
		transition: background-color 0.3s ease;
		border-radius: 4px;
    }

	.menu-item:hover {
		background-color: #00BFFF;
		color: white;
	}

	/* Στυλ για τα υπομενού */
	.submenu {
		display: none;
		position: absolute;
		top: 100%;
		left: 0;
		background-color: #444;
		box-shadow: 0 2px 5px rgba(0, 0, 0, 0.2);
		border-radius: 5px;
	}

	.submenu a {
		display: block;
		padding: 10px;
		color: #fff;
		text-decoration: none;
		width: 150px;
	}

	.submenu a:hover {
		background-color: #555;
	}

    /* Στυλ για τα στοιχεία του χρήστη */
	.navbar .user-info {
    display: flex;
    align-items: center;
    gap: 15px;
    white-space: nowrap; /* Αποφυγή αλλαγής γραμμής */
	}

	.user-info span {
		color: #fff;
	}

	.logout-btn {
        padding: 8px 15px;
        background-color: #444;
        color: #fff;
        border: none;
        border-radius: 4px;
        cursor: pointer;
		transition: background-color 0.3s ease;
    }

    .logout-btn:hover {
        background-color: #00BFFF;
    }

	
	/* Στυλ για τον πίνακα */
	table {
		width: 100%;
		border-collapse: collapse;
		box-shadow: 0 2px 10px rgba(0, 0, 0, 0.1);
		margin-bottom: 20px;  /* Προσθήκη περιθωρίου στο κάτω μέρος του πίνακα */

	}
	
	th, td {
		padding: 12px;
		text-align: left;
		border: 1px solid #ddd;
	}

	th {
		background-color: #4a4a8d; /* Σκούρο μπλε χρώμα για κεφαλίδα */
		color: white;
	}

	tr:nth-child(even) {
		background-color: #f2f2f2; /* Εναλλασσόμενες γραμμές */
	}

	tr:hover {
		background-color: #e1e1f0; /* Χρώμα όταν ο χρήστης περνάει το ποντίκι */
	}


	h1 {
		text-align: center;
		margin-bottom: 20px; /* Προαιρετικό, για να δώσεις κάποια απόσταση από τον πίνακα */
	}

	.logo {
		margin-right: 20px;
	}

	.logo-img {
		height: 40px; /* Adjust size as needed */
		width: auto;
	}

	footer {
		background-color: #192f59;
		color: white;
		text-align: center;
		padding: 10px 0;
		position: fixed;
		bottom: 0;
		width: 100%;
	}
	
</style>
	
<script>	

function get() {
var table1 = document.getElementById("item_table1");
var table2 = document.getElementById("item_table2");
var xhr = new XMLHttpRequest();
xhr.open('GET', 'view_topic.php');
xhr.send();

xhr.onreadystatechange = function() {
	if (xhr.readyState === XMLHttpRequest.DONE) {
		if (xhr.status === 200) {
			var data1 = JSON.parse(xhr.responseText)['table1'];
			var data2 = JSON.parse(xhr.responseText)['table2'];

			// Δημιουργία πρώτου πίνακα
			var header = table1.createTHead();
			var row = header.insertRow(0);
			var headers = ["Title", "Description", "PDF Link", "Status", "Time active"];

			headers.forEach((text, index) => {
				var cell = row.insertCell(index);
				cell.innerHTML = text;
				cell.style.cssText = "background-color: #00BFFF; color: white; text-align: left;";
            });

			// Δημιουργία σώματος πίνακα
			var tbody = table1.createTBody();
			for (let i = 0; i < data1.length; i++) {
				var row = tbody.insertRow(-1);
				var cell1 = row.insertCell(0);
				var cell2 = row.insertCell(1);
				var cell3 = row.insertCell(2);
				var cell4 = row.insertCell(3);
				var cell5 = row.insertCell(4);
				cell1.innerHTML = data1[i]['title'];
				cell2.innerHTML = data1[i]['description'];
				cell4.innerHTML = data1[i]['status'];
				cell5.innerHTML = data1[i]['dateDiff'];

				// Δημιουργία του συνδέσμου στο pdf_link
				var pdfLink = document.createElement('a');
				pdfLink.href = data1[i]['pdf_link_topic'];
				pdfLink.innerHTML = data1[i]['pdf_link_topic'].slice(23);
				pdfLink.target = '_blank';
				cell3.appendChild(pdfLink);
			}
			
			
			
			

			// Δημιουργία δεύτερου πίνακα			 
			header = table2.createTHead();
			row = header.insertRow(0);
			var headers2 = ["Supervisor", "Member 1", "Member 2"];

			headers2.forEach((text, index) => {
				var cell = row.insertCell(index);
				cell.innerHTML = text;
				cell.style.cssText = "background-color: #00BFFF; color: white; text-align: left;";
			});
			

			// Δημιουργία σώματος πίνακα
			var tbody = table2.createTBody();
			for (let i = 0; i < data2.length; i++) {
				var row = tbody.insertRow(-1);
				var cell1 = row.insertCell(0);
				var cell2 = row.insertCell(1);
				var cell3 = row.insertCell(2);
				cell1.innerHTML = data2[i]['supervisor'];
				cell2.innerHTML = data2[i]['member1'];
				cell3.innerHTML = data2[i]['member2'];
				
			}
			sendStatusToServer();			
			
		} else {
			console.error("Error fetching data");
		}
		
	}
}

}

     // Συνάρτηση που ελέγχει το status διπλωματικής και ανακατευθύνει κατάλληλα με το πάτημα του "Διαχείριση Διπλωματικής"
	 function statusRedirectManagement() { 
            event.preventDefault();

            // Αποθηκεύω τον πίνακα διπλωματικής και το δεύτερο row (το πρώτο είναι header)
            var table1 = document.getElementById("item_table1");
            var firstRow = table1.rows[1];

            if (firstRow) {
                var statusCell = firstRow.cells[3]; // Αποθηκεύω το status
                var status = statusCell.textContent;

                // Διαφορετικη ανακατευθυνση αναλογα με το status
                if (status === "pending") {
                    window.location.href = "student3pending.php?data=pending";
                } else if (status === "under examination") {
                    window.location.href = "student3under_examination.php?data=under_examination";
                } else if (status === "finished") {
                    window.location.href = "student3finished.php?data=finished";
                } else {
                    alert("Current status of diploma thesis does not allow its management.");
                }
            } else {
                alert("No diploma thesis found.");
            }
        }

		// Συνάρτηση που ελέγχει το status διπλωματικής και ανακατευθύνει κατάλληλα με το πάτημα του "Επεξεργασία Προφίλ"
		function statusRedirectProccess() { 
            event.preventDefault();

            // Αποθηκεύω τον πίνακα διπλωματικής και το δεύτερο row (το πρώτο είναι header)
            var table1 = document.getElementById("item_table1");
            var firstRow = table1.rows[1];

            if (firstRow) {
                var statusCell = firstRow.cells[3]; // Αποθηκεύω το status
                var status = statusCell.textContent;

                // Διαφορετικη ανακατευθυνση αναλογα με το status
                if (status === "pending") {
                    window.location.href = "student2.php?data=pending";
                } else if (status === "under examination") {
                    window.location.href = "student2.php?data=under_examination";
                } else if (status === "finished") {
                    window.location.href = "student2.php?data=finished";
                } else if (status === "notfound") {
                    window.location.href = "student2.php?data=notfound";
                }
            } else {
                window.location.href = "student2.php?data=other";
            }
        }

		// Συνάρτηση που αποθηκεύει το status σε session variable
		function sendStatusToServer() { 
            event.preventDefault();

            var table1 = document.getElementById("item_table1");
            var firstRow = table1.rows[1];

            if (firstRow) {
                var statusCell = firstRow.cells[3]; // Αποθηκεύω το status
                var status = statusCell.textContent;
				const xhr = new XMLHttpRequest();
            	xhr.open("POST", "", true);
            	xhr.setRequestHeader("Content-Type", "application/x-www-form-urlencoded");
            	xhr.send("status=" + encodeURIComponent(status));
            }
        }

</script>
</head>
<body onload="get()">
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
		<h1>Επισκόπηση Διπλωματικής</h1>
		<table id="item_table1"></table>
		<table id="item_table2"></table>
		
	</div>
	

	<footer>
		<p>&copy; 2024 University of Patras - All Rights Reserved</p>
	</footer>

</body>
</html>
