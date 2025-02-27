<?php
session_start(); // Αναζήτηση συνόδου

if (!isset($_SESSION['email'])) { // Ανακατεύθυνση του χρήστη αν δεν είναι logged in
  header("Location: logout.php");
    exit();
}

if ($_SESSION['type'] != 'GRAM') {
  header("Location: logout.php");
  exit();
}

$email = $_SESSION['email']; // Αποθήκευση email χρήστη για προβολή στο nav bar
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Secretary System</title>
  <link rel="stylesheet" type="text/css" href="proccess_diplomatiki.css">
</head>
<body>
<div>
  <div class="navbar"> <!-- Navigation bar -->
    <div class="logo"> <!-- Logo/home button -->
      <a href="secretary.php">
        <img src="/Project/media/logo.png" alt="Logo">
      </a>
    </div>
    <div class="nav-items">
      <button class="nav-button" id="logout">Logout</button> <!-- logout button -->
      <div class="user-info"><?php echo htmlspecialchars($email); ?></div> <!-- user email -->
    </div>
  </div>

  <div class="main-content">
    <div class="left-column"> <!-- Πίνακας με στοιχεία επιλεγμένης διπλωματικής -->
      <h3>Πληροφορίες</h3>
      <div id="details">
        <!-- Τα στοιχεία θα εισαχθούν εδώ -->
      </div>
    </div>

    <div class="right-column">
      <div class="row">
        <div class="form-container">
          <form id="protocolForm"> <!-- Φόρμα για εισαγωγή protocol number -->
            <label for="protocolNumber" class="form-label">
              Καταχώρηση αριθμού πρωτοκόλλου από τη γενική συνέλευση που ενέκρινε την ανάθεση θέματος:
            </label>
            <input step="1" type="number" id="protocolNumber" class="form-input" 
                  placeholder="Εισάγετε αριθμό πρωτοκόλλου" required disabled>
            <label class="red-label">*Για ενεργές διπλωματικές</label>
            <button id="submitButton1" type="submit" class="form-button" disabled>Υποβολή</button>
          </form>
        </div>
      </div>
      <div class="row">
        <div class="form-container">
          <form id = 'meetingForm'> <!-- Φόρμα για εισαγωγή meeting number και meeting year -->
            <label for="numberDate" class="form-label">
              Καταχώρηση αριθμού και έτους από τη γενική συνέλευση που ενέκρινε την ακύρωση θέματος: 
            </label>
            <input step='1' type="number" id="meetingNumber" class="form-input" placeholder="Εισάγετε αριθμό συνέλευσης" required disabled>
            <input step='1' type="number" id="meetingYear" class="form-input" placeholder="Εισάγετε έτος συνέλευσης" required disabled>
            <label class="red-label">*Για ενεργές διπλωματικές</label>
            <button id="submitButton2" class="form-button" disabled>Υποβολή</button>
          </form>
        </div>
      </div>
      <div class="row">
        <div class="form-container"> <!-- Κουμπί για περάτωση διπλωματικής -->
          <label class="form-label">
            Περάτωση διπλωματικής:
          </label>
          <label class="red-label">*Για διπλωματικές υπό εξέταση</label>
          <button id="changeButton" type="button" class="form-button" disabled>Περάτωση</button>
        </div>
    </div>
  </div>
</div>

<footer>
	<p>Computer Engineering and Informatics Department</p>
</footer>



<script>
    const urlParams = new URLSearchParams(window.location.search); // Ανάκτηση id από το URL
    const id = urlParams.get('data');

    getDetails(id); // Φόρτωση πίνακα στοιχείων διπλωματικής

    function getDetails(id) { // ίδια υλοποίηση με αυτή του secretary.php αλλά με έλεγο του status για ενεργοποίηση φόρμας
      var xhr = new XMLHttpRequest();
      xhr.open('GET', 'print_more_diplomatiki_details.php?id_diplomatiki=' + id, true);
      xhr.send();

      xhr.onload = function() {
        if (xhr.status === 200) {
          var data = JSON.parse(xhr.responseText);
          if (data.message === "Success") {
            var detailsDiv = document.getElementById("details");
            detailsDiv.innerHTML = "";
            var table = document.createElement("table");
            table.style.width = "100%";
            table.style.borderCollapse = "collapse";
            table.style.marginTop = "20px";

            var details = data.item;
            for (var key in details) {
              if (details.hasOwnProperty(key)) {
                var row = table.insertRow();
                var cell1 = row.insertCell(0);
                cell1.style.padding = "10px";
                cell1.style.border = "1px solid #000";
                cell1.style.backgroundColor = "#fff";
                cell1.innerHTML = key.replace(/_/g, ' ');
                cell1.style.backgroundColor = "#00BFFF";
                cell1.style.color = "white";
                cell1.style.width = "50px";

                var cell2 = row.insertCell(1);
                cell2.style.padding = "10px";
                cell2.style.border = "1px solid #000";
                cell2.innerHTML = details[key];
              }
            }
            detailsDiv.appendChild(table);

            var fourthRow = table.rows[3]; // έλεγχος status για ενεργοποίηση φόρμας
                    var statusCellContent = fourthRow.cells[1].innerText;
                    if (statusCellContent === "active") {
                        document.getElementById("protocolNumber").disabled = false;
                        document.getElementById("submitButton1").disabled = false;
                        document.getElementById("meetingNumber").disabled = false;
                        document.getElementById("meetingYear").disabled = false;
                        document.getElementById("submitButton2").disabled = false;
                    } else {
                      document.getElementById("changeButton").disabled = false;
                    }
          } else {
            getLessDetails(id);
          }
        } else {
          alert("Error fetching details:", xhr.status);
        }
      };
    }

    function getLessDetails(id) { // ίδια υλοποίηση με την πάνω αλλά για άλλο path (είχα θέμα αν έβαζα στην πάνω παραμετρικά το path)
      var xhr = new XMLHttpRequest();
      xhr.open('GET', 'print_less_diplomatiki_details.php?id_diplomatiki=' + id, true);
      xhr.send();

      xhr.onload = function() {
        if (xhr.status === 200) {
          var data = JSON.parse(xhr.responseText);
          if (data.message === "Success") {
            var detailsDiv = document.getElementById("details");
            detailsDiv.innerHTML = "";
            var table = document.createElement("table");
            table.style.width = "100%";
            table.style.borderCollapse = "collapse";
            table.style.marginTop = "20px";

            var details = data.item;
            for (var key in details) {
              if (details.hasOwnProperty(key)) {
                var row = table.insertRow();
                var cell1 = row.insertCell(0);
                cell1.style.padding = "10px";
                cell1.style.border = "1px solid #000";
                cell1.style.backgroundColor = "#fff";
                cell1.innerHTML = key.replace(/_/g, ' ');
                cell1.style.backgroundColor = "#00BFFF";
                cell1.style.color = "white";
                cell1.style.width = "50px";

                var cell2 = row.insertCell(1);
                cell2.style.padding = "10px";
                cell2.style.border = "1px solid #000";
                cell2.innerHTML = details[key];
              }
            }
            detailsDiv.appendChild(table);

            var fourthRow = table.rows[3]; // έλεγχος status για ενεργοποίηση φόρμας
                    var statusCellContent = fourthRow.cells[1].innerText;
                    if (statusCellContent === "active") {
                        document.getElementById("protocolNumber").disabled = false;
                        document.getElementById("submitButton1").disabled = false;
                        document.getElementById("meetingNumber").disabled = false;
                        document.getElementById("meetingYear").disabled = false;
                        document.getElementById("submitButton2").disabled = false;
                    } else if (statusCellContent === "under examination") {
                        document.getElementById("changeButton").disabled = false;
                    }
          } else {
            document.getElementById("details").innerHTML = "<p>No details available for this selection.</p>";
          }
        } else {
          alert("Error fetching details:", xhr.status);
        }
      };
    }

    document.getElementById('logout').addEventListener('click', function() { // logout functionality
      window.location.href = 'logout.php';
    });

    document.getElementById('protocolForm').addEventListener('submit', function (event) { // submit functionality
      event.preventDefault();

      const protocolNumber = document.getElementById('protocolNumber').value; // Ανάκτηση protocol number από φόρμα

      var xhr = new XMLHttpRequest();
      xhr.open('POST', 'insert_protocol_number.php', true);
      xhr.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded');

      xhr.onload = function () {
        if (xhr.status !== 200) { // Ανεπιτυχές request
          alert("Request not completed successfully");
        }
      };

      // Αποστολή δεδομένων
      xhr.send('id=' + encodeURIComponent(id) + '&protocolNumber=' + encodeURIComponent(protocolNumber));
      getDetails(id); // Ανανέωση πίνακα με νέα στοιχεία
      getDetails(id);
      //header("Location: " . $_SERVER['PHP_SELF']); // Page reload
    });

    document.getElementById('meetingForm').addEventListener('submit', function (event) { // submit functionality

        event.preventDefault();

        const meetingNumber = document.getElementById('meetingNumber').value; // Ανάκτηση meeting number από το form
        const meetingYear = document.getElementById('meetingYear').value; // Ανάκτηση meeting year από το form

        if (!/^\d{4}$/.test(meetingYear)) { // Έλεγχος αν το έτος είναι τετραψήφιο
            alert("Meeting date must be a valid 4-digit year.");
            return;
        }

        // URL με παραμέτρους
        const url = 'update_cancellation.php?id=' + encodeURIComponent(id) + '&meetingNumber=' + encodeURIComponent(meetingNumber) + '&meetingYear=' + encodeURIComponent(meetingYear);

        
        var xhr = new XMLHttpRequest(); // Get request γιατί η post έκανε reload τη σελίδα
        xhr.open('GET', url, true); 

        xhr.onload = function () {
          if (xhr.status !== 200) { // Failed request
            alert("Request not completed successfully");
          }
        };

        xhr.send(); // Αποστολή request
        getLessDetails(id); // Ανανέωση πίνακα δεδομένων
        //header("Location: " . $_SERVER['PHP_SELF']); // Page reload
        });

        document.getElementById('changeButton').addEventListener('click', function () {
            // URL για αποστολή id
            const url = 'finish_diplomatiki.php?id=' + encodeURIComponent(id);
            const xhr = new XMLHttpRequest(); // Δημιουργία request
            xhr.open('GET', url, true);
            xhr.onload = function () {
                if (xhr.status === 200) { // Επιτυχές request
                    try {
                        const response = JSON.parse(xhr.responseText);
                        alert(response.message); // Εμφάνιση αποτελέσματος σε alert box

                        if (response.message === 'Diploma status successfully updated to "finished".') {
                            getLessDetails(id); // Ανανέωση πίνακα
                        }

                    } catch (e) {
                        alert("An error occurred while processing the response.");
                    }
                } else {
                    alert("Failed to communicate with the server. Status code: " + xhr.status);
                }
            };

            xhr.send(); // Αποστολή request
            //header("Location: " . $_SERVER['PHP_SELF']); // Page reload
        });
</script>

</body>
</html>
