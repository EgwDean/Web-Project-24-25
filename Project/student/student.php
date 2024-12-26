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
<link rel="stylesheet" type="text/css" href="student.css">
</head>
<body onload="get()">
    <!-- Navigation bar -->
    <div class="navbar">
        <div class="logo">
            <a href="student.php">
                <img src="/Project/media/logo.png" alt="Logo" class="logo-img">
            </a>
        </div>

        <!-- Menu items (Always visible) -->
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
	
	
	
<script>	

function get() {

    var table1 = document.getElementById("item_table1");
    table1.classList.add("responsive-table");
    table1.style.marginBottom = "75px";

    var table2 = document.getElementById("item_table2");
    table2.classList.add("responsive-table");

    var xhr = new XMLHttpRequest();
    xhr.open('GET', 'view_topic.php');
    xhr.send();

    xhr.onreadystatechange = function() {
        if (xhr.readyState === XMLHttpRequest.DONE) {
            if (xhr.status === 200) {
                var data1 = JSON.parse(xhr.responseText)['table1'];
                var data2 = JSON.parse(xhr.responseText)['table2'];

                // Create table1
                var header = table1.createTHead();
                var row = header.insertRow(0);
                var headers = ["Title", "Description", "PDF Link", "Status", "Time active"];

                headers.forEach((text, index) => {
                    var cell = row.insertCell(index);
                    cell.innerHTML = text;
                    cell.classList.add("table-header");
                });

                var tbody = table1.createTBody();
                for (let i = 0; i < data1.length; i++) {
                    var row = tbody.insertRow(-1);
                    row.classList.add("table-row");

                    var cell1 = row.insertCell(0);
                    var cell2 = row.insertCell(1);
                    var cell3 = row.insertCell(2);
                    var cell4 = row.insertCell(3);
                    var cell5 = row.insertCell(4);

                    cell1.innerHTML = data1[i]['title'];
                    cell2.innerHTML = data1[i]['description'];
                    cell4.innerHTML = data1[i]['status'];
                    cell5.innerHTML = data1[i]['dateDiff'];

                    [cell1, cell2, cell3, cell4, cell5].forEach((cell, index) => {
                        cell.classList.add("table-cell");
                        cell.setAttribute("data-label", headers[index]); // Add data-label for responsive styling
                    });

                    var pdfLink = document.createElement('a');
                    pdfLink.href = data1[i]['pdf_link_topic'];
                    pdfLink.innerHTML = data1[i]['pdf_link_topic'].slice(26);
                    pdfLink.target = '_blank';
                    cell3.appendChild(pdfLink);
                }

                // Create table2
                header = table2.createTHead();
                row = header.insertRow(0);
                var headers2 = ["Supervisor", "Member 1", "Member 2"];

                headers2.forEach((text, index) => {
                    var cell = row.insertCell(index);
                    cell.innerHTML = text;
                    cell.classList.add("table-header");
                });

                tbody = table2.createTBody();
                for (let i = 0; i < data2.length; i++) {
                    var row = tbody.insertRow(-1);
                    row.classList.add("table-row");

                    var cell1 = row.insertCell(0);
                    var cell2 = row.insertCell(1);
                    var cell3 = row.insertCell(2);

                    cell1.innerHTML = data2[i]['supervisor'];
                    cell2.innerHTML = data2[i]['member1'];
                    cell3.innerHTML = data2[i]['member2'];

                    [cell1, cell2, cell3].forEach((cell, index) => {
                        cell.classList.add("table-cell");
                        cell.setAttribute("data-label", headers2[index]); // Add data-label for responsive styling
                    });
                }
            } else {
                console.error("Error fetching data");
            }
        }
    };
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

</body>
</html>
