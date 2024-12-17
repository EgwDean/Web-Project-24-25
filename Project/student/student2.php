<?php
session_start();

// Έλεγχος αν ο χρήστης είναι συνδεδεμένος
if (!isset($_SESSION['email'])) {
    header("Location: logout.php");
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

/* Δομή σελίδας για την εμφάνιση των στοιχείων */
.container {
	display: flex;
	padding: 20px;
	gap: 20px;
	align-items: flex-start;
}

   
h1 {
    text-align: center;
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
            background-color: #444;
            color: white;
            border: none;
            border-radius: 6px;
            cursor: pointer;
            width: 100%;
            font-size: 16px;
            text-align: center;
        }

        button:hover {
            background-color: #00BFFF;
            transition: background-color 0.3s ease;
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
        try {
            const response = JSON.parse(xhr.responseText);

            if (response.errors && response.errors.length > 0) {
                // If errors exist, display them
                alert("Errors: " + response.errors.join(", "));
            } else if (response.success === false) {
                // If success is false but no specific errors, display the error message
                alert("Error: " + (response.error || "An unknown error occurred."));
            } else if (response.success === true) {
                // Success case
                alert("Personal Info changed successfully!");
            }

        } catch (e) {
            console.error("Invalid JSON response", e);
            alert("Unexpected server response.");
        }
    } else {
        alert("Server error. Please try again.");
    }
    };
    xhr.send(formData); // Send the form data
}

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
	
	</script>
	
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
