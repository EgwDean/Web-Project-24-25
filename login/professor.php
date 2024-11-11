<?php
session_start(); // Ξεκινάμε τη συνεδρία

if (isset($_SESSION['email'])) {
echo "You are " . $_SESSION['email'] . "! Your type is: " . $_SESSION['type'];
} else {
    echo "You are not logged in."; // Μήνυμα αν ο χρήστης δεν είναι συνδεδεμένος
}

?>
