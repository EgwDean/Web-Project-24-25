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

        .form-container {
	    width: 100%;  
            max-width: 350px;
            margin: 50px auto;
            padding: 20px;
            background-color: #fff;
            border-radius: 20px;
            box-shadow: 0 4px 12px rgba(0, 0, 0, 0.1);
            font-family: 'Calibri', sans-serif;
        }

        .form-container h2 {
            text-align: center;
            color: #333;
            margin-bottom: 20px;
            font-size: 24px;
            font-weight: bold;
        }

        .form-input {
            width: 95%;
            padding: 12px;
            margin: 12px 0;
            border: 2px solid #ddd;
            border-radius: 20px;
            font-size: 16px;
            transition: border-color 0.3s, box-shadow 0.3s;
        }

        .form-input:focus {
            border-color: #3498db;
            box-shadow: 0 0 8px rgba(52, 152, 219, 0.6);
            outline: none;
        }

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

        .form-button.submit-btn {
            background-color: #3498db;
            color: white;
        }

        .form-button.submit-btn:hover {
            background-color: #2980b9;
            transform: scale(1.05);
        }

        .form-button.clear-btn {
            background-color: #e74c3c;
            color: white;
        }

        .form-button.clear-btn:hover {
            background-color: #c0392b;
            transform: scale(1.05);
        }

        .button-wrapper {
            display: flex;
            justify-content: center;
            gap: 15px;
        }

        .form-container label {
            font-size: 18px;
            font-weight: 500;
            color: #555;
            margin-bottom: 8px;
            display: block;
        }

        .form-input::placeholder {
            color: #aaa;
            font-style: italic;
        }

    </style>
</head>


<script>


function clearForm() {
    document.getElementById('studentId').value = '';
    document.getElementById('studentEmail').value = '';
}


</script>





<body>

    <!-- Navigation bar -->
    <div class="navbar">
        <div class="menu">
            <!-- Ενότητες με υπομενού που οδηγούν στο professor.php -->
            <div>
                <a href="professor.php" class="menu-item">Section 1</a>
                <div class="submenu">
                    <a href="professor.php">Submenu 1-1</a>
                </div>
            </div>
            <div>
                <a href="professor2.php" class="menu-item">Section 2</a>
                <div class="submenu">
                    <a href="professor2.php">Submenu 2-1</a>
                    <a href="professor2_2.php">Submenu 2-2</a>
                </div>
            </div>
            <div>
                <a href="professor.php" class="menu-item">Section 3</a>
                <div class="submenu">
                    <a href="professor.php">Submenu 3-1</a>
                    <a href="professor.php">Submenu 3-2</a>
                    <a href="professor.php">Submenu 3-3</a>
                </div>
            </div>
            <div>
                <a href="professor4.php" class="menu-item">Section 4</a>
            </div>
            <div>
                <a href="professor5.php" class="menu-item">Section 5</a>
            </div>
            <div>
                <a href="professor.php" class="menu-item">Section 6</a>
                <div class="submenu">
                    <a href="professor.php">Submenu 6-1</a>
                    <a href="professor.php">Submenu 6-2</a>
                    <a href="professor.php">Submenu 6-3</a>
                </div>
            </div>
        </div>

        <!-- Στοιχεία χρήστη -->
        <div class="user-info">
            <span>Welcome, <?php echo htmlspecialchars($userEmail); ?></span>
            <form action="" method="post" style="display:inline;">
                <button type="submit" name="logout" class="logout-btn">Logout</button>
            </form>
        </div>
    </div>


    <!-- Content -->
    <div class="form-container">
        <h2>Ανάκληση Διπλωματικής</h2>

        <form id="studentForm" action="recall_thesis.php" method="POST">
            <label for="studentId">ID</label>
            <input type="text" id="studentId" name="studentId" placeholder="Εισάγετε τον κωδικό Διπλωματικής" class="form-input" required>

            <label for="studentEmail">Ηλεκτρονικό Ταχυδρομείο</label>
            <input type="text" id="studentEmail" name="studentEmail" placeholder="Εισάγετε το email του φοιτητή" class="form-input" required>

            <div class="button-wrapper">
                <button type="submit" class="form-button submit-btn">Ανάκληση</button>
                <button type="button" onclick="clearForm()" class="form-button clear-btn">Καθαρισμός</button>
            </div>
        </form>
    </div>



</body>
</html>

