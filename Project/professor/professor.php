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
	<link rel="stylesheet" type="text/css" href="professor.css">
</head>

<body onload="get()">

    <!-- Navigation bar -->
    <div class="navbar">
	
		<div class="logo">
    			<a href="professor.php">
			<img src="../media/logo.png" alt="Logo" class="logo-img">
		</div>
	
        <div class="menu">
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



        <div class="user-info">
            <form method="POST" action="">
                <button type="submit" name="logout" class="logout-btn">Logout</button>
            </form>
            <span><?php echo $userEmail;?></span>
        </div>
    </div>


    <div class="container">
        <div>
            <h1>Διαθέσιμες Διπλωματικές</h1>
			<div class="table-container">
            <table id="item_table"></table>
			</div>
			<div class="button-container">
				<button class="create-btn" onclick="showCreateForm()">Create Thesis</button>
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

	<footer>
		<p>Computer Engineering and Informatics Department</p>
	</footer>



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
                cell1.style.backgroundColor = "#00BFFF";
                cell1.style.color = "white";
                var cell2 = row.insertCell(1);
                cell2.style.backgroundColor = "#00BFFF";
                cell2.style.color = "white";
                var cell3 = row.insertCell(2);
                cell3.style.backgroundColor = "#00BFFF";
                cell3.style.color = "white";
                var cell4 = row.insertCell(3);
                cell4.style.backgroundColor = "#00BFFF";
                cell4.style.color = "white";
                
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
			//var pdfLink = document.createElement('a');
			//pdfLink.href = data[i]['pdf_link_topic'];
			//pdfLink.innerHTML = data[i]['pdf_link_topic'].slice(26); // Αφαιρεί τους πρώτους 26 χαρακτήρες
			//pdfLink.target = '_blank';
			//cell4.appendChild(pdfLink);


			var pdfLink = document.createElement('a');
                        if (data[i]['pdf_link_topic']) {
                        	pdfLink.href = data[i]['pdf_link_topic'];
                        	pdfLink.innerHTML = data[i]['pdf_link_topic'].slice(26); // Αφαιρεί τους πρώτους 26 χαρακτήρες
                   	} else {
                        	pdfLink.href = "";
                        	pdfLink.innerHTML = "";
                    	}
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
				location.reload();								// Refresh the page to see changes
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
                alert("Diploma edited successfully!"); 			// Success alert
				form.style.display = 'none';					// Hide form after alert
				location.reload();								// Refresh the page to see changes
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
				location.reload();								// Refresh the page to see changes
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
