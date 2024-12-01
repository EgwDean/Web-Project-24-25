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
