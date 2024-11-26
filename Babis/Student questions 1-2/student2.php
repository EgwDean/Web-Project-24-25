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

   
h1 {
    text-align: center;
    color: #192f59;
    font-size: 24px;
}



.logo {
    margin-right: 20px;
}

.logo-img {
    height: 40px; 
    width: auto;
}

footer {
            background-color: #192f59;
            color: white;
            text-align: center;
            padding: 10px 0;
            position: relative;
            width: 100%;
}

.update-form{
	margin: 20px auto;
			margin: 20px auto;
			padding: 30px;
			max-width: 500px;
			background-color: #f9f9f9;
			border-radius: 8px;
			box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
			flex-grow: 1;
}



  label {	
            display: block;
			font-weight: bold;
			margin-bottom: 5px;
            margin-top: 10px;
        }

        input {
            width: 100%;
            padding: 10px;
            margin-top: 5px;
            margin-bottom: 20px;
            border: 1px solid #ccc;
            border-radius: 4px;
        }
		
		input:focus {
			border-color: #192f59;
			outline: none;
			box-shadow: 0 0 5px rgba(25, 47, 89, 0.5);
		}

        button {
            padding: 12px 25px;
            background-color: #192f59;
            color: white;
            border: none;
            border-radius: 6px;
            cursor: pointer;
            width: 100%;
            font-size: 16px;
            text-align: center;
        }

        button:hover {
            background-color: #16324b;
        }		

</style>
	
	<script>	

	function handleFormSubmit(event) {
		event.preventDefault(); // Prevent default form submission

    // Get the form element
    const form = document.getElementById("updateForm");
    const formData = new FormData(form); // Collect form data
	
    // Send the form data via AJAX
    const xhr = new XMLHttpRequest();
    xhr.open("POST", "update_student_info.php", true);
    xhr.onload = function () {
        if (xhr.status === 200) {
            const response = JSON.parse(xhr.responseText);
            if (response.success) {
                alert("Personal Info changed successfully!"); 		// Success alert
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
<body>
   <!-- Navigation bar -->
    <div class="navbar">
	
		<div class="logo">
			<img src="media/ceid_logo.png" alt="Logo" class="logo-img">
		</div>
	
        <div class="menu">
            <div>
                <a href="student.php" class="menu-item">Προβολή Θέματος</a>
            </div>
            <div>
                <a href="student2.php" class="menu-item">Επεξεργασία Προφίλ</a>
            </div>
            <div>
                <a href="student3.php" class="menu-item">Διαχείριση Διπλωματικής</a>
            </div>
            
        </div>



        <div class="user-info">
            <span>Welcome, <?php echo $userEmail; ?></span>
            <form method="POST" action="">
                <button type="submit" name="logout" class="logout-btn">Logout</button>
            </form>
        </div>
    </div>
		
		
	<form id="updateForm" onsubmit="handleFormSubmit(event)" class="update-form">
	<h1>Ενημέρωση Στοιχείων Επικοινωνίας</h1>
        <label for="street">Street:</label>
        <input type="text" id="street" name="street" required>

        <label for="number">Number:</label>
        <input type="text" id="number" name="number" required>

        <label for="city">City:</label>
        <input type="text" id="city" name="city" required>

        <label for="postcode">Postcode:</label>
        <input type="text" id="postcode" name="postcode" required>

        <label for="landline_telephone">Landline Telephone:</label>
        <input type="text" id="landline_telephone" name="landline_telephone">

        <label for="mobile_telephone">Mobile Telephone:</label>
        <input type="text" id="mobile_telephone" name="mobile_telephone" required>

        <button type="submit">Update Information</button>
    </form>
	
	
	
	<footer>
		<p>&copy; 2024 University of Patras - All Rights Reserved</p>
	</footer>

</body>
</html>