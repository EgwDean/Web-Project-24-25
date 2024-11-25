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

        .navbar .menu-item {
            color: #fff;
            text-decoration: none;
            padding: 10px;
            font-weight: bold;
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

        /* Δομή σελίδας για την εμφάνιση των στοιχείων */
        .container {
            display: flex;
            padding: 20px;
            gap: 20px;
            align-items: flex-start;
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

	.selected-row {
    		background-color: #cce5ff; /* Χρώμα για την επιλεγμένη γραμμή */
	}


        /* Στυλ για τα κουμπιά "Create" και "Edit" */
        .create-btn, .edit-btn {
            padding: 10px 15px;
            background-color: #4a4a8d;
            color: white;
            border: none;
            cursor: pointer;
            font-weight: bold;
            border-radius: 4px;
            text-align: center;
        }

        .create-btn:hover, .edit-btn:hover {
            background-color: #3a3a7c;
        }

        .edit-btn {
            display: none; /* Αρχικά κρυφό */
            margin-left: 10px;
        }

        /* Στυλ για τη φόρμα επεξεργασίας */

.container {
    display: flex;
    padding: 20px;
    gap: 20px;
    align-items: flex-start;
    justify-content: space-between; /* Χώρος μεταξύ πίνακα και φορμών */
}


.container > div:first-child {
    width: 50%;
}


/* Οι φόρμες στο μισό δεξιά */
.create-form,
.edit-form,
.upload-form {
    position: absolute;
    width: 50%; /* Καταλαμβάνουν το μισό πλάτος */
    max-width: none; /* Κατάργηση του περιορισμού μεγέθους */
    padding: 20px;
    background-color: #f9f9f9;
    display: none; /* Αρχικά κρυφές */
    box-shadow: 0 2px 10px rgba(0, 0, 0, 0.1);
    right: 140px;
    margin-top: 80px;
    max-width: 500px; /* Όριο μεγέθους */
    justify-content: center; /* Κεντράρει τα κουμπιά οριζόντια */

}




/* Στυλ για τη φόρμα δημιουργίας */

        .create-form input, .create-form textarea{
            width: 95%;  
            padding: 12px;
            margin: 12px 0;
   	    border: 2px solid #ddd;
            border-radius: 20px;
            font-size: 16px;
    	    transition: border-color 0.3s, box-shadow 0.3s;
        }

        .create-form textarea {
            height: 150px;
            resize: vertical;
        }

        .create-form button {
    	padding: 12px 23px;
    	margin: 10px 0;
    	border-radius: 9px;
    	border: none;
    	font-size: 14px;
    	font-weight: bold;
    	cursor: pointer;
    	transition: background-color 0.3s, transform 0.2s;
        }


        .submit-btn {
            background-color: #90ee90;
        }

        .submit-btn:hover {
            background-color: #006400;
        }

        .cancel-btn {
            background-color: #e74c3c;
        }

        .cancel-btn:hover {
            background-color: #c0392b;
        }



        .edit-form input, .edit-form textarea{
            width: 95%;  
            padding: 12px;
            margin: 12px 0;
   	    border: 2px solid #ddd;
            border-radius: 20px;
            font-size: 16px;
    	    transition: border-color 0.3s, box-shadow 0.3s;
        }

        .edit-form button {
    	padding: 12px 23px;
    	margin: 10px 0;
    	border-radius: 9px;
    	border: none;
    	font-size: 14px;
    	font-weight: bold;
    	cursor: pointer;
    	transition: background-color 0.3s, transform 0.2s;
        }


        .edit_form-btn {
            background-color: #90ee90;
        }

        .edit_form-btn:hover {
            background-color: #006400;
        }


        .cancel-btn {
            background-color: #e74c3c;
        }

        .cancel-btn:hover {
            background-color: #c0392b;
        }


	.button-container {
    		display: flex; /* Χρησιμοποιούμε flexbox για να τοποθετήσουμε τα κουμπιά δίπλα-δίπλα */
    		gap: 10px; /* Απόσταση μεταξύ των κουμπιών */
	}




	/* Στυλ για τη φόρμα Upload PDF */


.upload-form input {
    width: 100%;
    padding: 10px;
    margin: 10px 0;
    border: 1px solid #ddd;
    border-radius: 4px;
    box-sizing: border-box;
}

.upload-form .button-container {
    display: flex;
    justify-content: space-between;
    margin-top: 20px;
}

.upload-btn {
    display: none; /* Κρύβει το κουμπί στην αρχή */
}


.upload-form button {
    padding: 10px 15px;
    background-color: #27ae60;
    color: white;
    border: none;
    border-radius: 4px;
    cursor: pointer;
    font-weight: bold;
    width: 48%;
    margin-right: 4%;
}

.upload-form button:hover {
    background-color: #2ecc71;
}

.upload-form .cancel-btn {
    background-color: #e74c3c;
}

.upload-form .cancel-btn:hover {
    background-color: #c0392b;
}


h1 {
    text-align: center;
    margin-bottom: 20px; /* Προαιρετικό, για να δώσεις κάποια απόσταση από τον πίνακα */
}

.create-form h2, .edit-form h2, .upload-form h2 {
    text-align: center; /* Κεντράρισμα του τίτλου */
    margin-bottom: 20px; /* Προαιρετική απόσταση κάτω από τον τίτλο */
    font-size: 24px; /* Ρύθμιση μεγέθους για καλύτερη εμφάνιση */
    color: #333; /* Χρώμα για τον τίτλο */
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



        let selectedRowData = null;

function get() {
    var table = document.getElementById("item_table");
    var xhr = new XMLHttpRequest();
    xhr.open('GET', 'print_1.php');
    xhr.send();

    xhr.onreadystatechange = function() {
        if (xhr.readyState === XMLHttpRequest.DONE) {
            if (xhr.status === 200) {
                var data = JSON.parse(xhr.responseText)['items'];

                // Δημιουργία κεφαλίδας πίνακα
                var header = table.createTHead();
                var row = header.insertRow(0);
                var cell1 = row.insertCell(0);
                var cell2 = row.insertCell(1);
                var cell3 = row.insertCell(2);
                var cell4 = row.insertCell(3);
                cell1.innerHTML = "ID";
                cell2.innerHTML = "Title";
                cell3.innerHTML = "Summary";
                cell4.innerHTML = "PDF";

                // Δημιουργία σώματος πίνακα
                var tbody = table.createTBody();
                for (let i = 0; i < data.length; i++) {
                    var row = tbody.insertRow(-1);
                    var cell1 = row.insertCell(0);
                    var cell2 = row.insertCell(1);
                    var cell3 = row.insertCell(2);
                    var cell4 = row.insertCell(3);
                    cell1.innerHTML = data[i]['id_diplomatiki'];
                    cell2.innerHTML = data[i]['title'];
                    cell3.innerHTML = data[i]['description'];

                    // Δημιουργία του συνδέσμου στο pdf_link
                    var pdfLink = document.createElement('a');
                    pdfLink.href = data[i]['pdf_link_topic'];
                    pdfLink.innerHTML = data[i]['pdf_link_topic'];
                    pdfLink.target = '_blank';
                    cell4.appendChild(pdfLink);

                    // Χειριστής για επιλογή γραμμής
                    row.onclick = function() {

                        // Αφαίρεση της κλάσης από όλες τις γραμμές
                        var rows = document.querySelectorAll("#item_table tbody tr");
                        rows.forEach(function(r) {
                            r.classList.remove("selected-row");
                        });

                        // Προσθήκη της κλάσης στην επιλεγμένη γραμμή
                        this.classList.add("selected-row");


                        selectedRowData = data[i];
                        document.querySelector('.edit-btn').style.display = 'block';
                        document.querySelector('.upload-btn').style.display = 'block'; // Εμφανίζουμε το κουμπί Upload PDF
                    };
                }
            } else {
                console.error("Error fetching data");
            }
        }
    }
}

function showCreateForm() {
    var uploadForm = document.querySelector('.upload-form');
    var createForm = document.querySelector('.create-form');
    var editForm = document.querySelector('.edit-form');
    
    // Κρύβουμε τη φόρμα επεξεργασίας και upload
    editForm.style.display = 'none';
    uploadForm.style.display = 'none';    
    
    // Εμφανίζουμε τη φόρμα δημιουργίας
    createForm.style.display = 'block';
}

function showEditForm() {
    if (selectedRowData) {
        var uploadForm = document.querySelector('.upload-form');
        var createForm = document.querySelector('.create-form');
        var editForm = document.querySelector('.edit-form');
        
        // Κρύβουμε τη φόρμα δημιουργίας και upload
        createForm.style.display = 'none';
        uploadForm.style.display = 'none';
        
        // Εμφανίζουμε τη φόρμα επεξεργασίας
        editForm.style.display = 'block';
        
        // Γέμισμα των πεδίων με τις πληροφορίες της επιλεγμένης εγγραφής
        document.getElementById('edit-id').value = selectedRowData.id_diplomatiki;
        document.getElementById('edit-title').value = selectedRowData.title;
        document.getElementById('edit-description').value = selectedRowData.description;
    }
}

function showUploadForm() {
    var uploadForm = document.querySelector('.upload-form');
    var createForm = document.querySelector('.create-form');
    var editForm = document.querySelector('.edit-form');

    // Κλείνουμε τις άλλες φόρμες (Create, Edit)
    createForm.style.display = 'none';
    editForm.style.display = 'none';
    
    // Εμφανίζουμε τη φόρμα Upload PDF
    uploadForm.style.display = 'block';

    // Γέμισμα του πεδίου ID με το ID της επιλεγμένης γραμμής
    if (selectedRowData) {
        document.getElementById('upload-id').value = selectedRowData.id_diplomatiki;
    }
}


function cancelEdit() {
    var form = document.querySelector('.edit-form');
    form.style.display = 'none';
}

function cancelCreate() {
    var form = document.querySelector('.create-form');
    form.style.display = 'none';
}

function cancelUpload() {
    var form = document.querySelector('.upload-form');
    form.style.display = 'none';
}



function createDiploma(event){
	event.preventDefault(); // Prevent default form submission

    // Get the form element
    const form = document.getElementById("createDiplomaForm");
    const formData = new FormData(form); // Collect form data
	
    // Send the form data via AJAX
    const xhr = new XMLHttpRequest();
    xhr.open("POST", "insert_diplomatic.php", true);
    xhr.onload = function () {
        if (xhr.status === 200) {
            const response = JSON.parse(xhr.responseText);
            if (response.success) {
                alert("Diploma created successfully!"); 		// Success alert
				form.style.display = 'none';					// Hide form after alert
            } else {
                alert("Error: " + response.error); 				// Error alert
            }
        } else {
            alert("Server error. Please try again.");
        }
    };
    xhr.send(formData); // Send the form data
}


function editDiploma(event){
	event.preventDefault(); // Prevent default form submission

    // Get the form element
    const form = document.getElementById("editDiplomaForm");
    const formData = new FormData(form); // Collect form data
	
    // Send the form data via AJAX
    const xhr = new XMLHttpRequest();
    xhr.open("POST", "update_diplomatic.php", true);
    xhr.onload = function () {
        if (xhr.status === 200) {
            const response = JSON.parse(xhr.responseText);
            if (response.success) {
                alert("Diploma edited successfully!"); 		// Success alert
				form.style.display = 'none';					// Hide form after alert
            } else {
                alert("Error: " + response.error); 				// Error alert
            }
        } else {
            alert("Server error. Please try again.");
        }
    };
    xhr.send(formData); // Send the form data
}


function uploadDiploma(event){
	event.preventDefault(); // Prevent default form submission

    // Get the form element
    const form = document.getElementById("uploadDiplomaForm");
    const formData = new FormData(form); // Collect form data
	
    // Send the form data via AJAX
    const xhr = new XMLHttpRequest();
    xhr.open("POST", "upload_pdf.php", true);
    xhr.onload = function () {
        if (xhr.status === 200) {
            const response = JSON.parse(xhr.responseText);
            if (response.success) {
                alert("Diploma uploaded successfully!"); 		// Success alert
				form.style.display = 'none';					// Hide form after alert
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
</head>

<body onload="get()">

    <!-- Navigation bar -->
    <div class="navbar">
	
		<div class="logo">
			<img src="media/ceid_logo.png" alt="Logo" class="logo-img">
		</div>
	
        <div class="menu">
            <div>
                <a href="professor.php" class="menu-item">Section 1</a>
            </div>
            <div>
                <a href="professor2.php" class="menu-item">Section 2</a>
                <div class="submenu">
                    <a href="professor2.php">Submenu 2-1</a>
                    <a href="professor2_2.php">Submenu 2-2</a>
                </div>
            </div>
            <div>
                <a href="professor3.php" class="menu-item">Section 3</a>
            </div>
            <div>
                <a href="professor4.php" class="menu-item">Section 4</a>
            </div>
            <div>
                <a href="professor5.php" class="menu-item">Section 5</a>
            </div>
            <div>
                <a class="menu-item">Section 6</a>
                <div class="submenu">
                    <a>Submenu 6-1</a>
                    <a>Submenu 6-2</a>
                    <a>Submenu 6-3</a>
                </div>
            </div>
        </div>



        <div class="user-info">
            <span>Welcome, <?php echo $userEmail; ?></span>
            <form method="POST" action="">
                <button type="submit" name="logout" class="logout-btn">Logout</button>
            </form>
        </div>
    </div>


    <div class="container">
        <div>
            <h1>Διαθέσιμες Διπλωματικές</h1>

            <table id="item_table"></table>

	<div class="button-container">
		<button class="create-btn" onclick="showCreateForm()">Create Item</button>
    		<button class="edit-btn" onclick="showEditForm()">Edit</button>
		<button class="upload-btn" onclick="showUploadForm()">Upload PDF</button>

	</div>

    </div>

<!-- Φόρμα Δημιουργίας -->
	<form  id="createDiplomaForm" method="POST" onsubmit="createDiploma(event)" class="create-form">
	<h2>Δημιουργία Νέας Διπλωματικής</h2>
		<div>
			<label for="create-title">Title:</label>
			<input type="text" id="create-title" name="title" required>
		</div>
		<div>
			<label for="create-description">Description:</label>
			<textarea id="create-description" name="description" rows="4" required></textarea>
		</div>
		<div class="button-container">
			<button type="submit" class="submit-btn">Submit</button>
			<button type="button" onclick="cancelCreate()" class="cancel-btn">Cancel</button>
		</div>
	</form>
</div>



<!-- Φόρμα Επεξεργασίας -->
	<form id="editDiplomaForm" method="POST" onsubmit="editDiploma(event)" class="edit-form">
	<h2>Επεξεργασία Διπλωματικής</h2>
		<div>
			<label for="edit-id">ID:</label>
			<input type="text" id="edit-id" name="id" style="pointer-events: none; background-color: lightgray; cursor: not-allowed;">
		</div>
		<div>
			<label for="edit-title">New Title:</label>
			<input type="text" id="edit-title" name="title">
		</div>
		<div>
			<label for="edit-description">New Description:</label>
			<textarea id="edit-description" name="description" rows="4"></textarea>
		</div>
		<div class="button-container">
			<button type="submit" class="edit_form-btn">Update</button>
			<button type="button" class="cancel-btn" onclick="cancelEdit()">Cancel</button>
		</div>
	</form>
</div>


<!-- Φόρμα Upload PDF -->
    <form id="uploadDiplomaForm" method="POST" onsubmit="uploadDiploma(event)" class="upload-form" enctype="multipart/form-data">
	<h2>Ανέβασμα Αρχείου PDF</h2>
        <div>
            <label for="upload-id">ID:</label>
            <input type="text" id="upload-id" name="id" readonly>
        </div>
        <div>
            <label for="upload-file">Select PDF File:</label>
            <input type="file" id="upload-file" name="pdf_file" accept="application/pdf" required>
        </div>
        <div class="button-container">
            <button type="submit">Upload</button>
            <button type="button" class="cancel-btn" onclick="cancelUpload()">Close</button>
        </div>
    </form>
</div>

</div>
</body>
</html>