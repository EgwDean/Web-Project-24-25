<?php
session_start(); 			// Ξεκινάμε τη συνεδρία

// Έλεγχος της τιμής της μεταβλητής $email στη συνεδρία 
if (empty($_SESSION['email'])) {
    // Ανακατεύθυνση στη Login σελίδα αν ο χρήστης δεν έχει συνδεθεί
    header("Location: login.php");
    exit();
}


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
        * {
            box-sizing: border-box;
        }

        body {
            margin: 0;
            font-family: "Gothamgrbook", sans-serif;
            font-size: 16px;
            line-height: 27.2px;
            color: rgb(107, 107, 107);
            background-color: rgba(0, 0, 0, 0);
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
		

        .top-bar {
            height: 35px;  
            width: 100%;
            background-color: #192f59;
            display: flex;
            justify-content: space-between; /* Distributes space between menu and welcome text */
            align-items: center;
            padding: 0 20px; /* Adds padding to the left and right of the bar */
        }

        .ceid-logo img {
            width: 100%;
            max-width: 538px;
            height: auto;
        }

        .logo-header {
            background-color: #f5f5f5;
        }

        #top-bar-menu {
            display: flex;
            justify-content: center; /* Centers the list of menu items */
            flex-grow: 1; /* Ensures menu takes available space */
            margin: 0;
        }

        .top-bar-menu li {
            display: inline-block;
            margin-right: 15px;
        }

        #top-bar-menu li a {
            font-family: 'Calibri', sans-serif;
            color: white;
            text-decoration: none;
            transition: color 0.3s ease, background-color 0.3s ease;
        }

        #top-bar-menu li a:hover {
            color: #192f59;
            background-color: #ffffff;
            padding: 5px;
            border-radius: 5px;
        }
       
        .header-user-menu {
            font-family: 'Calibri', sans-serif;
            color: white;
            font-size: 14px;
            font-weight: bold;
            display: flex;
            align-items: center;
            gap: 10px;
            background-color: rgba(255, 255, 255, 0.2);
            padding: 5px 10px;
            border-radius: 5px;
            transition: background-color 0.3s ease;
        }

        .header-user-menu:hover {
            background-color: rgba(255, 255, 255, 0.4);
        }

        .header-user-menu img {
            width: 25px;
            height: 25px;
            border-radius: 50%;
        }

    </style>
</head>
<body>
    <div class="top-bar">
        <ul id="top-bar-menu" class="top-bar-menu">
            <li><a href="view_topic.php">Προβολή Θέματος</a></li>
            <li><a href="update_student_info.php">Επεξεργασία Προφίλ</a></li>
            <li><a href="manage_thesis.php">Διαχείριση Διπλωματικής</a></li>
        </ul>
		
		
        <!-- Επίδειξη ενός welcome message, ενός profile logo και ενός logout button !-->
        <div class="header-user-menu">
			<img src="media/icons8-user-48.png" alt="Logo">
            <span>Hello, <?php echo $_SESSION['email']; ?>!</span>
		
        </div>
		<form method="POST" action="">
                <button type="submit" name="logout" class="logout-btn">Logout</button>
        </form>
    </div>
    
	
	<!-- Logo Κεφαλίδα --!>
    <div class="logo-header">
        <div class="ceid-logo">
            <a href="">
                <img src="https://www.ceid.upatras.gr/assets/2021/10/logo_gr.png" alt="">
            </a>
        </div>
    </div>
	
</body>
</html>
