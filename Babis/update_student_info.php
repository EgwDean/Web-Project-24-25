<?php
session_start();

// Έλεγχος της τιμής της μεταβλητής $email στη συνεδρία 
if (empty($_SESSION['email'])) {
    // Ανακατεύθυνση στη Login σελίδα αν ο χρήστης δεν έχει συνδεθεί
    header("Location: login.php");
    exit();
}

// Database credentials
$host = "localhost";
$dbusername = "root";
$dbpassword = "556782340";
$dbname = "diplomatiki_support";

// Σύνδεση με τη βάση δεδομένων
$conn = new mysqli($host, $dbusername, $dbpassword, $dbname);

// Έλεγχος σύνδεσης
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Έλεγχος για AJAX request για ενημέρωση των στοιχείων χρήστη
if (isset($_POST['ajax']) && $_POST['ajax'] === '1') {
    $email = isset($_SESSION['email']) ? $_SESSION['email'] : null;

    // Ανάκτηση δεδομένων φόρμας για ενημέρωση στοιχείων χρήστη
    if (isset($_POST['street'], $_POST['number'], $_POST['city'], $_POST['postcode'], $_POST['landline_telephone'], $_POST['mobile_telephone'])) {
        $street = $_POST['street'];
        $number = $_POST['number'];
        $city = $_POST['city'];
        $postcode = $_POST['postcode'];
        $landline_telephone = $_POST['landline_telephone'];
        $mobile_telephone = $_POST['mobile_telephone'];

        // Prepared statement για ενημέρωση των στοιχείων χρήστη στη βάση
        $update_query = "UPDATE student SET street = ?, number = ?, city = ?, postcode = ?, landline_telephone = ?, mobile_telephone = ? WHERE email_student = ?";

        if ($stmt = $conn->prepare($update_query)) {
            // Bind parameters (string 'sssssss' σημαίνει 7 strings)
            $stmt->bind_param("sssssss", $street, $number, $city, $postcode, $landline_telephone, $mobile_telephone, $email);

            // Εκτέλεση του query
            if ($stmt->execute()) {
                echo json_encode(['message' => 'Profile updated successfully']);
            } else {
                echo json_encode(['error' => 'Error updating profile: ' . $stmt->error]);
            }

            // Close the statement
            $stmt->close();
        } else {
            echo json_encode(['error' => 'Error preparing statement: ' . $conn->error]);
        }
    } else {
        echo json_encode(['error' => 'Incomplete data received.']);
    }
    exit();
}

// Αν δεν λάβεις AJAX Request, πρόβαλλε απλά τη σελίδα
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Update Contact Info</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #f4f7f6;
            color: #333;
            margin: 0;
            padding: 0;
            display: flex;
            flex-direction: column;
            min-height: 100vh;
        }

        header {
            background-color: #192f59;
            color: white;
            padding: 20px 0;
            text-align: center;
        }

        .table-container {
            margin: 20px auto;
            padding: 20px;
            max-width: 1000px;
            background-color: white;
            border-radius: 8px;
            box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
            flex-grow: 1;
        }

        label {
            display: block;
            margin-top: 10px;
        }

        input {
            width: 100%;
            padding: 10px;
            margin-top: 5px;
            margin-bottom: 15px;
            border: 1px solid #ccc;
            border-radius: 4px;
        }

        button {
            padding: 10px 20px;
            background-color: #192f59;
            color: white;
            border: none;
            border-radius: 4px;
            cursor: pointer;
            width: 100%;
            font-size: 16px;
            text-align: center;
        }

        button:hover {
            background-color: #16324b;
        }

        footer {
            background-color: #192f59;
            color: white;
            text-align: center;
            padding: 10px 0;
            width: 100%;
            position: relative;
        }
    </style>
</head>
<body>
<header>
    <h1>Ενημέρωση Στοιχείων Επικοινωνίας</h1>
</header>

<div class="table-container">
    <form id="updateForm">
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

    <div id="responseMessage"></div>
</div>


<footer>
    <p>&copy; 2024 University of Patras - All Rights Reserved</p>
</footer>


<script>
    function handleFormSubmit(e) {
    e.preventDefault();
    var formData = new FormData(e.target);
    formData.append('ajax', '1');
    
    var xhr = new XMLHttpRequest();
    xhr.open('POST', 'update_student_info.php', true);
    xhr.onreadystatechange = function() {
        if (xhr.readyState === 4 && xhr.status === 200) {
            var response = JSON.parse(xhr.responseText);
            document.getElementById('responseMessage').innerText = response.message || response.error;
        }
    };
    xhr.send(formData);
}

document.getElementById('updateForm').addEventListener('submit', handleFormSubmit);

</script>
</body>
</html>
