<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Sign Up</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #f4f4f4;
            margin: 0;
            padding: 20px;
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh; /* Ύψος ολόκληρης της σελίδας για καλύτερη στοίχιση */
        }

        h1 {
            text-align: center; /* Κεντραρισμένος τίτλος */
            font-size: 24px;
            margin-bottom: 20px;
        }

        .form-container {
            background: #fff;
            padding: 20px;
            border-radius: 5px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
            max-width: 350px;
            width: 100%; /* Για να είναι προσαρμοσμένη η φόρμα */
            margin: 0 auto; /* Κεντραρισμένη φόρμα */
        }

        input[type="text"], input[type="password"], input[type="email"] {
            width: 100%;
            padding: 10px;
            margin: 10px 0;
            border: 1px solid #ccc;
            border-radius: 5px;
            box-sizing: border-box; /* Για να περιλαμβάνει το padding */
        }

        input[type="submit"] {
            background-color: #28a745;
            color: white;
            border: none;
            padding: 10px;
            cursor: pointer;
            border-radius: 5px;
            width: 100%; /* Κουμπί πλήρους πλάτους */
        }

        input[type="submit"]:hover {
            background-color: #218838;
        }

        .error {
            color: red; /* Κόκκινο χρώμα για τα μηνύματα σφάλματος */
            margin: 5px 0; /* Λίγο περιθώριο πάνω και κάτω */
            font-size: 12px; /* Μικρότερο μέγεθος γραμματοσειράς */
            text-align: center; /* Κεντραρισμένα μηνύματα */
        }
    </style>
    <script>
        // Function to display the pop-up message and then redirect to the login page
        function showPopup() {
            alert("User inserted successfully!");
            window.location.href = 'login.php'; // Redirect after the alert
        }
    </script>
</head>
<body>
    <div class="form-container">
        <h1>Sign Up</h1>
        <form action="insert.php" method="POST">
            <input type="text" name="username" placeholder="Username" required value="<?php echo isset($_POST['username']) ? htmlspecialchars($_POST['username']) : ''; ?>">
            <input type="password" name="password" placeholder="Password" required value="<?php echo isset($_POST['password']) ? htmlspecialchars($_POST['password']) : ''; ?>">
            <input type="text" name="firstname" placeholder="First Name" required value="<?php echo isset($_POST['firstname']) ? htmlspecialchars($_POST['firstname']) : ''; ?>">
            <input type="text" name="lastname" placeholder="Last Name" required value="<?php echo isset($_POST['lastname']) ? htmlspecialchars($_POST['lastname']) : ''; ?>">
            <input type="email" name="email" placeholder="Email" required value="<?php echo isset($_POST['email']) ? htmlspecialchars($_POST['email']) : ''; ?>">
            <input type="submit" value="Sign Up">
        </form>
        <?php
        // Initialize error messages
        $username_error = "";
        $email_error = "";

        if ($_SERVER["REQUEST_METHOD"] == "POST") {
            // Database connection
            $dsn = "mysql:host=localhost;port=3306;dbname=html";
            $dbusername = "root";
            $dbpassword = "12345theo";

            try {
                $pdo = new PDO($dsn, $dbusername, $dbpassword);
                $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

                // Get form data
                $username = $_POST['username'];
                $password = $_POST['password']; // Consider hashing in a real application
                $firstname = $_POST['firstname'];
                $lastname = $_POST['lastname'];
                $email = $_POST['email'];

                // Prepare the statements for checking username and email
                // Check if the username is already taken using the PROCEDURE name_taken
                $stmt_username = $pdo->prepare("CALL name_taken(?, @username_taken)");
                $stmt_username->execute([$username]);
                $stmt_username->closeCursor();

                // Retrieve the result of the OUT parameter for username
                $result_username = $pdo->query("SELECT @username_taken")->fetch(PDO::FETCH_ASSOC);
                $username_taken = $result_username['@username_taken'];

                // Check if the email is already in use using the PROCEDURE email_taken
                $stmt_email = $pdo->prepare("CALL email_taken(?, @email_taken)");
                $stmt_email->execute([$email]);
                $stmt_email->closeCursor();

                // Retrieve the result of the OUT parameter for email
                $result_email = $pdo->query("SELECT @email_taken")->fetch(PDO::FETCH_ASSOC);
                $email_taken = $result_email['@email_taken'];

                // Now we check the conditions
                if ($username_taken == 1) {
                    // Username already taken
                    $username_error = "Username is already taken. Please choose another.";
                } elseif ($email_taken == 1) {
                    // Email already in use
                    $email_error = "Email is already in use. Please use another email.";
                } else {
                    // Insert the user data into the database since both username and email are available
                    $stmt = $pdo->prepare("INSERT INTO users (username, password, firstname, lastname, email) VALUES (?, ?, ?, ?, ?)");
                    $stmt->execute([$username, $password, $firstname, $lastname, $email]);

                    // Call JavaScript to show the popup and redirect
                    echo "<script>showPopup();</script>";
                }
            } catch (PDOException $e) {
                echo "Connection failed: " . $e->getMessage();
            }
        }
        ?>
        <!-- Display error messages -->
        <div class="error"><?php echo $username_error; ?></div>
        <div class="error"><?php echo $email_error; ?></div>
    </div>
</body>
</html>
