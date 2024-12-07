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
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Professor Dashboard</title>

    <style>
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
            justify-content: space-between;
            align-items: center;
        }

        .navbar .menu {
            display: flex;
            gap: 20px;
            justify-content: center;
            flex: 1;
        }

        .navbar .menu > div {
            position: relative;
        }

        .navbar .menu > div:hover .submenu {
            display: block;
        }

        .navbar .menu-item {
            color: #fff;
            text-decoration: none;
            padding: 10px;
            font-weight: bold;
        }

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

        .navbar .user-info {
            display: flex;
            align-items: center;
            gap: 15px;
            white-space: nowrap;
        }

        .user-info span {
            font-weight: bold;
            color: #fff;
        }

        .logout-btn {
            padding: 8px 15px;
            background-color: #e74c3c;
            color: #fff;
            border: none;
            border-radius: 4px;
            cursor: pointer;
            font-weight: bold;
        }

        .logout-btn:hover {
            background-color: #c0392b;
        }

        .container {
            display: flex;
            flex-direction: row;
            margin: 20px;
        }

        .left-section {
            flex: 1;
            padding: 20px;
        }

        .right-section {
            flex: 1; /* Αύξηση πλάτους για το δεξί τμήμα */
            padding: 20px;
        }

        .table-title {
            text-align: center;
            font-size: 24px;
            font-weight: bold;
            color: #333;
            margin-bottom: 20px;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            box-shadow: 0 2px 10px rgba(0, 0, 0, 0.1);
        }

        th, td {
            padding: 12px;
            text-align: left;
            border: 1px solid #ddd;
        }

        th {
            background-color: #4a4a8d;
            color: white;
        }

        tr:nth-child(even) {
            background-color: #f2f2f2;
        }

        tr:hover {
            background-color: #e1e1f0;
        }


        .left-section h1 {
            text-align: center;
            font-size: 28px;  /* Προαιρετικά, μπορείς να αλλάξεις το μέγεθος του τίτλου */
            margin-bottom: 20px;
        }


	/* Βασικά στυλ για την φόρμα */
.form-container {
    max-width: 400px;
    margin: 50px auto 0; /* Προσθήκη 50px κενό στην κορυφή */
    padding: 20px;
    background-color: #fff;
    border-radius: 20px;
    box-shadow: 0 4px 12px rgba(0, 0, 0, 0.1);
    font-family: 'Calibri', sans-serif;
    display: none; /* Αρχικά κρυφή */
}

.form-container h2 {
    text-align: center;
    color: #333;
    margin-bottom: 20px;
    font-size: 24px;
    font-weight: bold;
}

/* Στυλ για τα πεδία εισαγωγής */
.form-input {
    width: 90%;
    padding: 12px;
    margin: 12px 0;
    border: 2px solid #ddd;
    border-radius: 20px;
    font-size: 16px;
    transition: border-color 0.3s, box-shadow 0.3s;
}

/* Εφέ hover για τα πεδία εισαγωγής */
.form-input:focus {
    border-color: #3498db;
    box-shadow: 0 0 8px rgba(52, 152, 219, 0.6);
    outline: none;
}

/* Στυλ για τα κουμπιά */
.form-button {
    padding: 12px 23px;
    margin: 10px 0;
    border-radius: 9px;
    border: none;
    font-size: 14px;
    font-weight: bold;
    cursor: pointer;
    transition: background-color 0.3s, transform 0.2s;
}

/* Εφέ hover για το κουμπί Submit */
.form-button.submit-btn {
    background-color: #3498db;
    color: white;
}

.form-button.submit-btn:hover {
    background-color: #2980b9;
    transform: scale(1.05);
}

/* Εφέ hover για το κουμπί Clear */
.form-button.clear-btn {
    background-color: #e74c3c;
    color: white;
}

.form-button.clear-btn:hover {
    background-color: #c0392b;
    transform: scale(1.05);
}

/* Στυλ για το wrapper των κουμπιών */
.button-wrapper {
    display: flex;
    justify-content: center;
    gap: 15px;
}

/* Στυλ για τα labels */
.form-container label {
    font-size: 18px;
    font-weight: 500;
    color: #555;
    margin-bottom: 8px;
    display: block;
}

/* Στυλ για το πεδίο φόρμας */
.form-input::placeholder {
    color: #aaa;
    font-style: italic;
}


.logo {
    margin-right: 20px;
}

.logo-img {
    height: 40px; /* Adjust size as needed */
    width: auto;
}

</style>
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
</head>
<body onload="get()">

    <!-- Navigation bar -->
    <div class="navbar">
	
		<div class="logo">
			<img src="media/ceid_logo.png" alt="Logo" class="logo-img">
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
            <span><?php echo htmlspecialchars($userEmail);?></span>
            <form action="" method="post" style="display:inline;">
                <button type="submit" name="logout" class="logout-btn">Logout</button>
            </form>
        </div>
    </div>



     <!-- Περιεχόμενο -->
    <div class="container">


<div class="left-section">



            <!-- Τίτλος στο κέντρο του δεξιού div -->
            <h1 class="table-title">Αιτήσεις</h1>

            <table id="item_table"></table>


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

</body>
</html>
