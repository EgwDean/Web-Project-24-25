<?php
session_start(); // Αναζήτηση συνόδου

if (!isset($_SESSION['email'])) { // Αν δεν έχει γίνει login ανακατεύθυνση
  header("Location: logout.php");
    exit();
}

if ($_SESSION['type'] != 'GRAM') {
  header("Location: logout.php");
  exit();
}

$email = $_SESSION['email']; //email χρήστη
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Secretary System</title>
  <style>
    * {
      margin: 0;
      padding: 0;
      box-sizing: border-box;
    }

    body {
      font-family: Arial, sans-serif;
      display: flex;
      flex-direction: column;
      height: 100vh;
    }

    .navbar {
      display: flex;
      justify-content: space-between;
      align-items: center;
      background-color: #333;
      padding: 10px 20px;
      color: white;
      width: 100%;
    }

    .nav-items {
      display: flex;
      align-items: center;
      gap: 15px;
      margin-left: auto;
    }

    .nav-button {
      background-color: #444;
      color: white;
      border: none;
      padding: 8px 12px;
      border-radius: 4px;
      cursor: pointer;
      transition: background-color 0.3s;
    }

    .nav-button:hover {
      background-color: rgb(0, 191, 255);
    }

    .logo img {
      height: 40px;
      cursor: pointer;
    }

    .user-info {
      margin-right: 8px;
      margin-left: 8px;
    }

    .main-content {
      display: flex;
      width: 100%;
      flex-grow: 1;
      padding: 20px;
      gap: 20px;
      overflow-y: auto;
      justify-content: space-between;
    }

    .table-container {
      flex: 1;
      max-width: 50%;
      border-right: 1px solid #ddd;
      padding-right: 20px;
      overflow-y: auto;
    }

    .details-container {
      flex: 1;
      max-width: 50%;
      border-right: 1px solid #ddd;
      padding-right: 20px;
      overflow-y: auto;
    }

    table {
      width: 100%;
      border-collapse: collapse;
      margin-top: 20px;
    }

    th, td {
      padding: 10px;
      border: 1px solid black;
      text-align: left;
    }

    th {
      background-color: #f4f4f4;
      font-weight: bold;
    }

    table tr:hover {
      background-color: #f0f0f0;
      cursor: pointer;
    }

    #searchBar {
      flex-grow: 1;
      margin: 0 20px;
      padding: 8px 12px;
      border: 1px solid #ccc;
      border-radius: 4px;
      font-size: 16px;
      outline: none;
      transition: border-color 0.3s;
      background-color: rgb(255, 255, 255);
    }

    #searchBar:focus {
      border-color: #555;
    }

    .details-button {
      margin-top: 20px;
      padding: 10px 15px;
      background-color: #444;
      color: white;
      border: none;
      border-radius: 4px;
      cursor: pointer;
      transition: background-color 0.3s;
    }

    .details-button2 {
      margin-top: 20px;
      padding: 10px 15px;
      background-color: #444;
      color: white;
      border: none;
      border-radius: 4px;
      cursor: pointer;
      transition: background-color 0.3s;
      margin: 30px;
    }

    .details-button:hover {
      background-color: rgb(0, 191, 255);
    }

    .hidden {
    display: none;
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
</head>
<body>
<div class="navbar"> <!-- Navigation bar -->
  <div class="logo"> <!-- Logo/home-button -->
    <a href="secretary.php">
      <img src="/Project/media/logo.png" alt="Logo">
    </a>
  </div>
  <input type="text" id="searchBar" placeholder="Αναζήτηση βάσει ID ή θέματος..." /> <!-- Search bar -->
  <div class="nav-items"> <!-- Navigation bar buttons -->
    <button class="nav-button" id="addStudents">Add Students</button>
    <button class="nav-button" id="addProfessors">Add Professors</button>
    <button class="nav-button" id="logout">Logout</button>
  </div>
  <div class="user-icon"> <!-- User icon & username -->
    <div class="user-info" id="userInfo"><?php echo htmlspecialchars($email); ?></div>
  </div>
</div>

<div class="main-content"> <!-- Main περιεχόμενο -->
  <div class="table-container"> <!-- Πίνακας διπλωματικών -->
    <h2>Διπλωματικές Εργασίες</h2>
    <table id="item_table">
      <!-- Εδώ μπαίνουν οι γραμμές του πίνακα διπλωματικών -->
    </table>
  </div>
    
  <div class="details-container"> <!-- Πίνακας με στοιχεία διπλωματικής -->
    <h3>Πληροφορίες</h3>
    <div id="details">
      <!-- Εδω μπαίνουν τα στοιχεία της επιλεγμένης διπλωματικής -->
    </div>
    <button class="hidden" id="detailsActionButton">Επεξεργασία Διπλωματικής</button>
  </div>
</div>
  
<input type="file" id="fileInputStudents" accept=".json" class="hidden"> <!-- Input για json file με student records -->

<footer>
		<p>&copy; 2024 University of Patras - All Rights Reserved</p>
</footer>

<script>
document.getElementById('addStudents').addEventListener('click', function () {
    document.getElementById('fileInputStudents').click(); // Ενεργοποιείται το file input για click του Add Students button
});

document.getElementById('fileInputStudents').addEventListener('change', function (event) {
    const file = event.target.files[0]; // Επιλεγμένο file

    if (file.type === "application/json") { // Ελέγχει αν το file είναι JSON
        const reader = new FileReader();
        reader.readAsText(file);
        reader.onload = function () {
            try {
                const jsonData = JSON.parse(reader.result); // Parse JSON
                validateAndSendStudentData(jsonData); // Έλεγχος ορθότητας δεδομένων και αποστολή μέσω AJAX
            } catch (error) {
                alert("Wrong File Format");
            }
        };
    } else {
        alert("Not a JSON file");
    }
});

function validateAndSendStudentData(data) {
    if (Array.isArray(data) && data.every(record => //έλεγχος αν το αρχείο έχει τη σωστή δομή
        record.hasOwnProperty('password') && 
        record.hasOwnProperty('name') && 
        record.hasOwnProperty('surname') && 
        record.hasOwnProperty('student_number') && 
        record.hasOwnProperty('street') && 
        record.hasOwnProperty('number') && 
        record.hasOwnProperty('city') && 
        record.hasOwnProperty('postcode') && 
        record.hasOwnProperty('father_name') && 
        record.hasOwnProperty('landline_telephone') && 
        record.hasOwnProperty('mobile_telephone') && 
        record.hasOwnProperty('email_student'))) {
        
        const xhr = new XMLHttpRequest(); // Αποστολή αρχείου μέσω AJAX
        xhr.open('POST', 'insert_students.php', true);
        xhr.setRequestHeader('Content-Type', 'application/json');
        xhr.onload = function () {
            if (xhr.status === 200) { // Ολοκληρώθηκε το request επιτυχώς
                try {
                    const response = JSON.parse(xhr.responseText);
                    if (response.success) {
                        alert(response.success); // Επιτυχής εισαγωγή δεδομένων
                    } else if (response.error) {
                        if (Array.isArray(response.error)) {
                            alert("Errors:\n" + response.error.join("\n")); // Πολλά σφάλματα
                        } else {
                            alert("Error: " + response.error); // Μοναδικό σφάλμα
                        }
                    }
                } catch (e) {
                    alert("Error parsing server response: " + e.message); // Σφάλμα κατά την απάντηση του server
                }
            } else {
                alert("Error inserting data: " + xhr.statusText); // Σφάλμα κατά το request
            }
        };
        xhr.send(JSON.stringify(data)); // Αποστολή JSON δεδομένων
    } else {
        alert("Wrong File Format"); // Λάθος δομή
    }
}
</script>

<!-- Παρόμοια υλοποίηση με την εισαγωγή φοιτητών αλλά για καθηγητές -->
<input type="file" id="fileInputProfessors" accept=".json" class=hidden>

<script>
document.getElementById('addProfessors').addEventListener('click', function () {
    document.getElementById('fileInputProfessors').click();
});

document.getElementById('fileInputProfessors').addEventListener('change', function (event) {
    const file = event.target.files[0];

    if (file && file.type === "application/json") {
        const reader = new FileReader();
        reader.readAsText(file);
        reader.onload = function () {
            try {
                const jsonData = JSON.parse(reader.result);
                validateAndSendProfessorData(jsonData);
            } catch (error) {
                alert("Wrong File Format");
            }
        };
    } else {
        alert("Wrong File Format");
    }
});

function validateAndSendProfessorData(data) {
    if (Array.isArray(data) && data.every(record => 
        record.hasOwnProperty('password') && 
        record.hasOwnProperty('name') && 
        record.hasOwnProperty('surname') && 
        record.hasOwnProperty('email_professor') && 
        record.hasOwnProperty('topic') && 
        record.hasOwnProperty('landline') && 
        record.hasOwnProperty('mobile') && 
        record.hasOwnProperty('department') && 
        record.hasOwnProperty('university'))) {
        
        const xhr = new XMLHttpRequest();
        xhr.open('POST', 'insert_professors.php', true);
        xhr.setRequestHeader('Content-Type', 'application/json');
        xhr.onload = function () {
    if (xhr.status === 200) {
        try {
            const response = JSON.parse(xhr.responseText);
            if (response.success) {
                alert(response.success);
            } else if (response.error) {
                if (Array.isArray(response.error)) {
                    alert("Errors:\n" + response.error.join("\n"));
                } else {
                    alert("Error: " + response.error);
                }
            }
        } catch (e) {
            alert("Error parsing server response: " + e.message);
        }
    } else {
        alert("Error inserting data: " + xhr.statusText);
    }
};
        xhr.send(JSON.stringify(data));
    } else {
        alert("Wrong File Format");
    }
}


let selectedId = null; // Αποθήκευση του Id της επιλεγμένης διπλωματικής


function get() { // Κατασευή πίνακα και γέμισμα με διπλωματικές
  const table = document.getElementById("item_table");
  const searchBar = document.getElementById("searchBar");

  let allItems = []; // Αποθήκευση στοιχείων για filtering

  function renderTable(items) {
    table.innerHTML = ""; // Καθαρισμός πίνακα

    const header = table.createTHead(); // Κεφαλίδα πίνακα
    const row = header.insertRow(0);
    const cell1 = row.insertCell(0);
    const cell2 = row.insertCell(1);
    cell1.innerHTML = "<b>ID</b>";
    cell2.innerHTML = "<b>Θέμα</b>";
    row.style.backgroundColor = "#00BFFF";
    row.style.color = "white";

    const tbody = table.createTBody(); // Σώμα πίνακα
    items.forEach(item => {
      const row = tbody.insertRow(-1);
      const cell1 = row.insertCell(0);
      const cell2 = row.insertCell(1);
      cell1.innerHTML = item.id_diplomatiki;
      cell2.innerHTML = item.title;

      row.style.cursor = 'pointer'; // Clickable row indicator
      row.addEventListener('click', function () {
        selectedId = item.id_diplomatiki; // Αποθήκευση Id γραμμής που έγινε κλικ
        getDetails(selectedId); // Φόρτωση δεδομένων διπλωματικής σε δίπλα πίνακα
        const detailsActionButton = document.getElementById("detailsActionButton"); // Εμφανίζεται το κουμπί επεξεργασίας
        detailsActionButton.classList.remove("hidden");
        detailsActionButton.classList.add("details-button2");
      });
    });
  }

  function filterItems(query) { // filtering για το search bar
    query = query.toLowerCase(); // Case-insensitive αναζήτηση για id ή θέμα
    return allItems.filter(item =>
      item.id_diplomatiki.toString().toLowerCase().includes(query) || 
      item.title.toLowerCase().includes(query)
    );
  }

  var xhr = new XMLHttpRequest(); // Αποστολή request μέσω AJAX
  xhr.open('GET', 'print_diplomatiki_titles.php');
  xhr.send();

  xhr.onload = function () {
    if (xhr.status === 200) { // Αν το request ήταν επιτυχές
        const data = JSON.parse(xhr.responseText); // Parse την απάντηση του server
        if (data.message === "Success") { 
            allItems = data.items; // Ανάκτηση των δεδομένων
            renderTable(allItems);

            // Event Listener για το search bar
            searchBar.addEventListener('input', function () {
                const query = searchBar.value;
                const filteredItems = filterItems(query);
                renderTable(filteredItems);
            });
        } else {
            alert("No items found");
        }
    } else {
        alert("Error fetching data:", xhr.status);
    }
};
}

// Φόρτωση δεδομένων επιλεγμένης διπλωματικής στον πίνακα
function getDetails(id) {
  var xhr = new XMLHttpRequest(); // Αποστολή request μέσω AJAX
  xhr.open('GET', 'print_diplomatiki_details.php?id_diplomatiki=' + id);
  xhr.send();

  xhr.onload = function() {
      if (xhr.status === 200) { // Επιτυχής request
          var data = JSON.parse(xhr.responseText); // Parse απάντησης
          if (data.message === "Success") {
            var detailsDiv = document.getElementById("details"); // Αποθήκευση details section για επεξεργασία

            // Καθαρισμός πίνακα details
            detailsDiv.innerHTML = "";

            // Κατασκευή του πίνακα
            var table = document.createElement("table");
            table.style.width = "100%";
            table.style.borderCollapse = "collapse";
            table.style.marginTop = "20px";

            // Κατασκευή rows επαναληπτικά από τα attributes του object
            var details = data.item;
            for (var key in details) {
              if (details.hasOwnProperty(key)) {
                var row = table.insertRow();
                var cell1 = row.insertCell(0);
                cell1.style.padding = "10px";
                cell1.style.border = "1px solid #000";
                cell1.style.backgroundColor = "#fff";
                cell1.innerHTML = key.replace(/_/g, ' '); // Τα underscores γίνονται κενά
                cell1.style.backgroundColor = "#00BFFF";
                cell1.style.color = "white";
                cell1.style.width = "50px";

                var cell2 = row.insertCell(1);
                cell2.style.padding = "10px";
                cell2.style.border = "1px solid #000";
                cell2.innerHTML = details[key];
              }
            }

            // Προστίθεται ο πίνακας στο details div
            detailsDiv.appendChild(table);
          } else {
            document.getElementById("details").innerHTML = "<p>No details available for this selection.</p>";
          }
      } else {
        alert("Error fetching details:", xhr.status);
      }
  };
}

document.getElementById('detailsActionButton').addEventListener('click', function () { // Μετάβαση σε επεξεργασία διπλωματικής
  window.location.href = 'proccess_diplomatiki.php?data='+ encodeURIComponent(selectedId);
});

document.getElementById('logout').addEventListener('click', function() { // Επιστροφή στο login screen
  window.location.href = 'logout.php';
});

document.addEventListener("DOMContentLoaded", get); // Φόρτωση διπλωματικών στον πίνακα μέσω της get() κατά τη φόρτωση της σελίδας
</script>
</body>
</html>