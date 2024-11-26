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
   
   
	
	
	
	<footer>
		<p>&copy; 2024 University of Patras - All Rights Reserved</p>
	</footer>

</body>
</html>