<?php
$dsn = "mysql:host=localhost;port=3306;dbname=html"; // Βάση δεδομένων
$dbusername = "root"; // Όνομα χρήστη
$dbpassword = "556782340"; // Κωδικός
$error = ""; // Μεταβλητή για σφάλματα
$username = ""; // Μεταβλητή για το όνομα χρήστη
$password = ""; // Μεταβλητή για τον κωδικό

try {
    $pdo = new PDO($dsn, $dbusername, $dbpassword);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    // Έλεγχος αν η φόρμα έχει υποβληθεί
    if ($_SERVER["REQUEST_METHOD"] == "POST") {
        $username = $_POST['username'];
        $password = $_POST['password'];

        $stmt = $pdo->prepare("CALL login(?, ?, @ptype)");
        $stmt->bindParam(1, $username);
        $stmt->bindParam(2, $password);
        $stmt->execute();

        $stmt->closeCursor();

        $result = $pdo->query("SELECT @ptype AS ptype");
        $row = $result->fetch(PDO::FETCH_ASSOC);

        if ($row['ptype'] == 'USER') {
            header("Location: display_users.php");
            exit(); 
        } elseif ($row['ptype'] == 'ADMIN') {
            header("Location: display_admins.php");
            exit();
        } else {
            $error = "Wrong combination, please try again."; // Μήνυμα σφάλματος
        }
    }
} catch (PDOException $e) {
    echo "Connection failed: " . $e->getMessage();
}
?>

<!DOCTYPE html>
<html lang="en">   <!-- page's language -->
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login</title>    
	
	
	<link rel="stylesheet" href="css/bootstrap.css">



    <style>
        body {
            font-family: Arial, sans-serif;  
            background-color: #f4f4f4;       
            display: flex;                   
            justify-content: center;         
            align-items: center;             
            height: 100vh;                   
            margin: 0;                       
        }

        .login-container {
            background-color: #fff;           
            padding: 20px;                    
            border-radius: 8px;               
            box-shadow: 0 2px 10px rgba(0, 0, 0, 0.1); 
            width: 300px;                     
            text-align: center;               
        }

        h1 {
            font-size: 24px;                  
            margin-bottom: 20px;              
        }

        label {
            display: block;                   
            margin-bottom: 5px;               
            text-align: left;                 
        }

        input[type="text"],
        input[type="password"] {
            width: 100%;                      
            padding: 10px;                    
            margin-bottom: 15px;              
            border: 1px solid #ccc;           
            border-radius: 4px;               
            box-sizing: border-box;           
        }

        .button-group {
            display: flex;                    
            justify-content: space-between;   
        }

        input[type="submit"],
        .signup-btn {
            width: 48%;                       
            padding: 10px;                    
            color: white;                     
            border: none;                     
            border-radius: 4px;               
            cursor: pointer;                  
            font-size: 16px;                  
        }

        input[type="submit"] {
            background-color: #5cb85c;        
        }

        input[type="submit"]:hover {
            background-color: #4cae4c;        
        }

        .signup-btn {
            background-color: #0275d8;        
            text-decoration: none;            
            display: inline-block;            
            text-align: center;               
        }

        .signup-btn:hover {
            background-color: #025aa5;        
        }

        .button-group input[type="submit"] {
            margin-right: 10px;               
        }

        .error-message {
            color: red;                       /* Κόκκινο χρώμα για τα μηνύματα σφάλματος */
            margin-top: 5px;                 /* Μείωσε το περιθώριο πάνω από το μήνυμα */
            font-size: 12px;                 /* Μείωσε το μέγεθος της γραμματοσειράς */
        }
    </style>
  
</head>
<body>


    <div class="login-container">
        <h1>Login</h1>
        
        <form action="" method="post">  <!-- Σημείωση: Το action είναι κενό, έτσι παραμένει στο ίδιο αρχείο -->
            <label for="username">Username:</label>
            <input type="text" id="username" name="username" value="<?php echo htmlspecialchars($username); ?>" required> <!-- Διατήρηση της τιμής του username -->

            <label for="password">Password:</label>
            <input type="password" id="password" name="password" value="<?php echo htmlspecialchars($password); ?>" required> <!-- Διατήρηση της τιμής του password -->

            <div class="button-group">
                <input type="submit" value="Login">
                <a href="insert.php" class="signup-btn">Sign Up</a>
            </div>
        </form>
      
        <div class="error-message">
            <?php if (!empty($error)) echo $error; ?> <!-- Εμφάνιση μηνύματος σφάλματος -->
        </div>
    </div>
	
	
	<script src="js/bootstrap.bundle.js"></script>    <!--bundle contains all the dependencies--s>
</body>
</html>
