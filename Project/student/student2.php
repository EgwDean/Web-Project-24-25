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

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Student Page</title>
	<link rel="stylesheet" type="text/css" href="student2.css">
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

</body>
</html>
