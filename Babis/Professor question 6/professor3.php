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

        .container {
            display: flex;
            flex-direction: row;
            margin: 20px;
        }

        .left-section {
            flex: 1;
            padding: 20px;
        }

        .right-section {
            flex: 1; /* Αύξηση πλάτους για το δεξί τμήμα */
            padding: 20px;
        }

        .table-title {
            text-align: center;
            font-size: 24px;
            font-weight: bold;
            color: #333;
            margin-bottom: 20px;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            box-shadow: 0 2px 10px rgba(0, 0, 0, 0.1);
        }

        th, td {
            padding: 12px;
            text-align: left;
            border: 1px solid #ddd;
        }

        th {
            background-color: #4a4a8d;
            color: white;
        }

        tr:nth-child(even) {
            background-color: #f2f2f2;
        }

        tr:hover {
            background-color: #e1e1f0;
        }


        h1 {
            text-align: center;
            font-size: 28px;  /* Προαιρετικά, μπορείς να αλλάξεις το μέγεθος του τίτλου */
            margin-bottom: 20px;
        }

/* Ειδικό styling για τα φίλτρα */
label {
    font-size: 16px;
    font-weight: bold;
    color: #333;
    margin-right: 10px;
}

select {
    font-size: 14px;
    padding: 8px;
    border-radius: 5px;
    border: 1px solid #ddd;
    background-color: #fff;
    margin-right: 20px;
    transition: all 0.3s ease;
}

select:hover, select:focus {
    border-color: #4a4a8d;
    background-color: #f9f9f9;
    outline: none;
}

.filter-btn {
    padding: 8px 15px;
    background-color: #4a4a8d;
    color: white;
    border: none;
    border-radius: 5px;
    cursor: pointer;
    font-size: 16px;
    transition: background-color 0.3s ease;
}

.filter-btn:hover {
    background-color: #333;
}

/* Styling για το container των φίλτρων */
div[style="margin-bottom: 20px;"] {
    display: flex;
    flex-wrap: wrap;
    align-items: center;
    gap: 15px;
}

.export-btn {
    padding: 8px 15px;
    background-color: #4a4a8d;
    color: white;
    border: none;
    border-radius: 5px;
    cursor: pointer;
    font-size: 16px;
    transition: background-color 0.3s ease;
    margin-top: 20px;  /* Προσθήκη κενών από πάνω */
}

.export-btn:hover {
    background-color: #333;
}


.right-section table td:first-child {
    font-weight: bold;
    background-color: #f9f9f9;
    width: 150px; /* Σταθερό πλάτος για την κεφαλίδα */
}

.right-section table td:last-child {
    padding-left: 10px;
}

.right-section h1 {
    text-align: center;
    font-size: 24px; /* Ανάλογα με την ανάγκη σου, μπορείς να προσαρμόσεις το μέγεθος */
    margin-top: 28px;  /* Κατάργηση του περιττού περιθωρίου */
    margin-bottom: 64px;
}

#log_table_container {
    max-height: 250px;   /* Περιορίζει το ύψος του container */
    overflow-y: auto;    /* Ενεργοποιεί το κάθετο scroll */
    width: 100%;         /* Καταλαμβάνει όλο το πλάτος */
    margin-top: 20px;    /* Προσθήκη κενών από πάνω */
}

#log_table {
    border-collapse: collapse; /* Καταρρέει τα όρια των κελιών για καθαρή εμφάνιση */
    width: 100%; /* Επιτρέπει στον πίνακα να καταλαμβάνει όλο το διαθέσιμο πλάτος */
}

#log_table th, #log_table td {
    padding: 8px;         /* Στυλ για την απόσταση μέσα στα κελιά */
    border: 1px solid #ddd; /* Όρια για τα κελιά */
}

#log_table th {
    background-color: #4a4a8d; /* Χρώμα φόντου για την κεφαλίδα */
    color: white; /* Χρώμα κειμένου για την κεφαλίδα */
}








/* Βασικά στυλ για την φόρμα */
.notes-form-container.grades-form-container {
    max-width: 400px;
    margin: 50px auto 0; /* Προσθήκη 50px κενό στην κορυφή */
    padding: 20px;
    background-color: #fff;
    border-radius: 20px;
    box-shadow: 0 4px 12px rgba(0, 0, 0, 0.1);
    font-family: 'Calibri', sans-serif;
}

.notes-form-container.grades-form-container h2 {
    text-align: center;
    color: #333;
    margin-bottom: 20px;
    font-size: 24px;
    font-weight: bold;
}

/* Στυλ για τα πεδία εισαγωγής */
.form-input {
    width: 90%;
    padding: 12px;
    margin: 12px 0;
    border: 2px solid #ddd;
    border-radius: 20px;
    font-size: 16px;
    transition: border-color 0.3s, box-shadow 0.3s;
}

/* Εφέ hover για τα πεδία εισαγωγής */
.form-input:focus {
    border-color: #3498db;
    box-shadow: 0 0 8px rgba(52, 152, 219, 0.6);
    outline: none;
}

/* Στυλ για τα κουμπιά */
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

/* Εφέ hover για το κουμπί Submit */
.form-button.submit-btn {
    background-color: #3498db;
    color: white;
}

.form-button.submit-btn:hover {
    background-color: #2980b9;
    transform: scale(1.05);
}

/* Εφέ hover για το κουμπί Clear */
.form-button.clear-btn {
    background-color: #e74c3c;
    color: white;
}

.form-button.clear-btn:hover {
    background-color: #c0392b;
    transform: scale(1.05);
}

/* Στυλ για το wrapper των κουμπιών */
.button-wrapper {
    display: flex;
    justify-content: center;
    gap: 15px;
}

/* Στυλ για τα labels */
.notes-form-container.grades-form-container label {
    font-size: 18px;
    font-weight: 500;
    color: #555;
    margin-bottom: 8px;
    display: block;
}

/* Στυλ για το πεδίο φόρμας */
.form-input::placeholder {
    color: #aaa;
    font-style: italic;
}

</style>
<script>

    let selectedId = null;     // Για αποθήκευση του επιλεγμένου ID
	let selectedStatus = null; // Για αποθήκευση του επιλεγμένου status

    function applyFilter() {
        const statusFilter = document.getElementById('status_filter').value;
        const roleFilter = document.getElementById('role_filter').value;
        get(statusFilter, roleFilter);
    }

    function get(statusFilter = 'none', roleFilter = 'none') {
        const table = document.getElementById("item_table");
        const xhr = new XMLHttpRequest();

        // Προσθήκη παραμέτρων φίλτρου
        const url = `print_all_dip.php?status=${encodeURIComponent(statusFilter)}&role=${encodeURIComponent(roleFilter)}`;

        xhr.open('GET', url);
        xhr.send();

        xhr.onreadystatechange = function() {
            if (xhr.readyState === XMLHttpRequest.DONE) {
                if (xhr.status === 200) {
                    const data = JSON.parse(xhr.responseText).items;

                    // Ενημέρωση πίνακα
                    table.innerHTML = '';

                    // Δημιουργία επικεφαλίδων
                    const header = table.createTHead();
                    const row = header.insertRow(0);
                    row.insertCell(0).innerHTML = "Id Διπλωματικής";
                    row.insertCell(1).innerHTML = "Επιβλέπων";
                    row.insertCell(2).innerHTML = "Μέλος Τριμελής";
                    row.insertCell(3).innerHTML = "Μέλος Τριμελής";
                    row.insertCell(4).innerHTML = "Κατάσταση";
                    row.insertCell(5).innerHTML = "Τελικός Βαθμός";

                    const tbody = table.createTBody();
                    data.forEach(item => {
                        const row = tbody.insertRow();
                        row.insertCell(0).innerHTML = item.id;
                        row.insertCell(1).innerHTML = item.supervisor;
                        row.insertCell(2).innerHTML = item.member1;
                        row.insertCell(3).innerHTML = item.member2;
                        row.insertCell(4).innerHTML = item.status;
                        row.insertCell(5).innerHTML = item.final_grade;

                        // Event listener για click
                        row.onclick = function (event) {
                            selectedId = item.id; // Αποθηκεύουμε το ID
							selectedStatus = item.status; // Αποθηκεύουμε το status
							
							// Check if the professor is the supervisor
							const professorEmail = "<?php echo $userEmail; ?>"; // Get the logged-in professor's email
							if (item.supervisor === professorEmail && item.status === "active") {
								const examBtn = document.getElementById("exam_btn");
								examBtn.style.display = "inline"; // Show the "Exam" button
							}else if (item.supervisor === professorEmail && item.status === "under examination") {
								const gradeEnableBtn = document.getElementById("grade_enable_btn");
								gradeEnableBtn.style.display = "inline"; // Show the "Grade Enable" button
							}
							else {
								// Hide if not the supervisor and status not active or under examination
								document.getElementById("exam_btn").style.display = "none"; 		
								document.getElementById("grade_enable_btn").style.display = "none"; 
							}
							
							
                            document.getElementById("view_info_btn").style.display = "inline"; // Εμφάνιση κουμπιού λεπτομερειών
							document.getElementById("notes_btn").style.display = "inline";     // Εμφάνιση κουμπιού δημιουργίας σημειώσεων
							
							rowClickHandler(event);  // συνάρτηση που γεμίζει αυτόματα τις φόρμες σημειώσεων και βαθμών σε κάθε 'click' σε γραμμή
                        };
						
	                });
                } else {
                    console.error("Error fetching data");
                }
            }
        };
    }


	// συνάρτηση για προβολή απάντησης μέλους τριμελούς σε πρόσκληση(υπό ανάθεση διπλωματική)
	function fetchMemberDetails(member) {
		if (!member) return;
		
		console.log('selectedId:', selectedId);
		console.log('member:', member);
		const xhr = new XMLHttpRequest();
		const url = `fetch_member_details.php?id=${selectedId}&member=${member}`;

		xhr.open('GET', url);
		xhr.send();

		xhr.onreadystatechange = function () {
			if (xhr.readyState === XMLHttpRequest.DONE) {
				if (xhr.status === 200) {
					const memberData = JSON.parse(xhr.responseText);

					// Populate a new table or modal with member details
					const rightTable = document.getElementById("info_table");
					rightTable.innerHTML = ''; // Clear the existing content

					const tbody = rightTable.createTBody();
					for (const [key, value] of Object.entries(memberData)) {
						const row = tbody.insertRow();
						row.insertCell(0).innerHTML = `<strong>${key}</strong>`;
						row.insertCell(1).innerHTML = value;
					}
				} else {
					console.error("Error fetching member details");
				}
			}
		};
	}     

	

    // Συνάρτηση εμφάνισης λεπτομερειών για την επιλεγμένη γραμμή πίνακα/id διπλωματικής 
    function viewInfo() {
        if (!selectedId) return;
		
		// Κώδικας για την περίπτωση όπου πριν το View έχω πατήσει άλλο button
		const gradesContainer = document.getElementById('gradesForm');
		const notesContainer = document.getElementById('notesForm');
		const infoTable = document.getElementById('info_table');
		const logTable = document.getElementById('log_table');

		// Hide the Notes and Grades form
		notesContainer.style.display = 'none';
		gradesContainer.style.display = 'none';

		// Show the Info table
		infoTable.style.display = 'block';
		
		// Show the Log table
		logTable.style.display = 'block';
		
		
        const xhr = new XMLHttpRequest();
        const url = `view_info.php?id=${selectedId}`;

        xhr.open('GET', url);
        xhr.send();

        xhr.onreadystatechange = function() {
            if (xhr.readyState === XMLHttpRequest.DONE) {
                if (xhr.status === 200) {
                    const data = JSON.parse(xhr.responseText);

                    // Ενημέρωση δεξιού πίνακα με τα στοιχεία της διπλωματικής
                    const rightTable = document.getElementById("info_table");
                    rightTable.innerHTML = '';

                    // Δημιουργία γραμμών για κάθε λεπτομέρεια
                    const tbody = rightTable.createTBody();

                    const details = {
                        "Title": data.title,
						"Pdf(main text)": data.pdf_main_diploma,
                        "Email Student": data.email_stud,
                        "Supervisor": data.supervisor,
                        "Member 1": data.member1,
                        "Member 2": data.member2,
                        "Final Grade": data.final_grade,
                        "Nemertes Link": data.Nemertes_link ? `<a href="${data.Nemertes_link}" target="_blank">Link</a>` : "", // Έλεγχος για κενό link
                        "Praktiko Bathmologisis": data.praktiko_bathmologisis
                    };

                    // Loop through the details and insert rows
					for (const [key, value] of Object.entries(details)) {
						const row = tbody.insertRow();
						const cell1 = row.insertCell(0);
						const cell2 = row.insertCell(1);

						cell1.innerHTML = `<strong>${key}</strong>`;
						cell2.innerHTML = value;

						if(selectedStatus === 'pending'){

							// Add event listener to "Member 1" and "Member 2"
							if (key === "Member 1" || key === "Member 2") {
								row.style.cursor = "pointer"; 					// Change cursor to hand on hover
								row.addEventListener('click', function () {
								fetchMemberDetails(value);                   	// Call the fetch_member_details function on click
								});
							}
						}
                    }

                    // Εμφάνιση του log για την επιλεγμένη διπλωματική
                    const logXhr = new XMLHttpRequest();
                    const logUrl = `view_log.php?id=${selectedId}`;

                    logXhr.open('GET', logUrl);
                    logXhr.send();

                    logXhr.onreadystatechange = function() {
                        if (logXhr.readyState === XMLHttpRequest.DONE) {
                            if (logXhr.status === 200) {
                                const logData = JSON.parse(logXhr.responseText);

                                // Ενημέρωση πίνακα log
                                const logTable = document.getElementById("log_table");
                                logTable.innerHTML = '';

                                // Δημιουργία επικεφαλίδων για το log
                                const logHeader = logTable.createTHead();
                                const logRow = logHeader.insertRow(0);
                                logRow.insertCell(0).innerHTML = "Log Record";

                                const logTbody = logTable.createTBody();
                                logData.forEach(item => {
                                    const logRow = logTbody.insertRow();
                                    logRow.insertCell(0).innerHTML = item.record;
                                });
                            } else {
                                console.error("Error fetching log data");
                            }
                        }
                    };

                } else {
                    console.error("Error fetching detailed info");
                }
            }
        };
    }
	
	
	// συνάρτηση για εμφάνιση της φόρμας εισαγωγής σημειώσεων
	function showNotesForm() {
		if (!selectedId) return;
		
		// Κώδικας για την περίπτωση όπου πριν το Notes έχω πατήσει άλλο button
		const gradesContainer = document.getElementById('gradesForm');
		const notesContainer = document.getElementById('notesForm');
		const infoTable = document.getElementById('info_table');
		const logTable = document.getElementById('log_table');

		// Show the Notes form
		notesContainer.style.display = 'block';
		notesContainer.style.pointerEvents = 'auto'; // Enable form interaction
		
		// Hide the Log and Info tables and the Grades form
		logTable.style.display = 'none';
		infoTable.style.display = 'none';  
		gradesContainer.style.display = 'none';
	}


	// συνάρτηση απόκρυψης της φόρμας σημειώσεων
	function cancelNotes() {
    var form = document.querySelector('.notesForm');
    form.style.display = 'none';
	}
	
	
	// συνάρτηση καθαρισμού της φόρμας σημειώσεων
	function clearForm() {
		document.getElementById('notes').value = '';
		document.getElementById('diplomaId').value = '';
		document.getElementById('diplomaStatus').value = '';
	}
	
	
	
	// συνάρτηση για ενημέρωση του status διπλωματικής σε "Υπό Εξέταση"
	function markUnderExamination() {
		if (!selectedId) return; // Ensure selectedId is valid

		const xhr = new XMLHttpRequest();
		const url = `update_status.php?id=${selectedId}`;  // sending the 'id'

		xhr.open('GET', url);
		xhr.send();

		xhr.onreadystatechange = function () {
			if (xhr.readyState === XMLHttpRequest.DONE) {
				if (xhr.status === 200) {
					alert("Status updated to 'Under Examination'");
					applyFilter(); // Refresh the table
				} else {
					console.error("Error updating status");
				}
			}
		};
	}
	
	
	// συνάρτηση για εμφάνιση της φόρμας καταχώρησης βαθμού
	function showGradesForm() {
		if (!selectedId) return;
		
		// Κώδικας για την περίπτωση όπου πριν το Grade έχω πατήσει άλλο button
		const gradesContainer = document.getElementById('gradesForm');
		const notesContainer = document.getElementById('notesForm');
		const infoTable = document.getElementById('info_table');
		const logTable = document.getElementById('log_table');

		// Show the Grades form
		gradesContainer.style.display = 'block';
		gradesContainer.style.pointerEvents = 'auto'; // Enable form interaction
		
		// Hide the Log, Info and Notes tables
		logTable.style.display = 'none';
		infoTable.style.display = 'none'; 
		notesContainer.style.display = 'none';
	}
	
	
	// συνάρτηση εμφάνισης του κουμπιού εισαγωγής βαθμού
	function showGradeButton() {
		const gradeBtn = document.getElementById("grade_btn");
		gradeBtn.style.display = "inline"; // Show the "Grade" button
	}
	
	

    // Λειτουργία για εξαγωγή σε CSV
    function exportCSV() {
        var table = document.getElementById("item_table");
        var csv = "Id, Supervisor, 1st Member, 2nd Member, Status, Final Grade\n";

        for (var i = 1, row; row = table.rows[i]; i++) {
            for (var j = 0, col; col = row.cells[j]; j++) {
                csv += col.innerText + (j < row.cells.length - 1 ? "," : "");
            }
            csv += "\n";
        }

        // Δημιουργία και λήψη αρχείου CSV
        var hiddenElement = document.createElement('a');
        hiddenElement.href = 'data:text/csv;charset=utf-8,' + encodeURI(csv);
        hiddenElement.target = '_blank';
        hiddenElement.download = 'Διπλωματικές.csv';
        hiddenElement.click();
    }

    // Λειτουργία για εξαγωγή σε JSON
    function exportJSON() {
        var table = document.getElementById("item_table");
        var data = [];
        
        for (var i = 1, row; row = table.rows[i]; i++) { // Ξεκινάμε από το 1 για να παραλείψουμε την κεφαλίδα
            var rowData = {
                id: row.cells[0].innerText,
                supervisor: row.cells[1].innerText,
                member1: row.cells[2].innerText,
                member2: row.cells[3].innerText,
                status: row.cells[4].innerText,
                final_grade: row.cells[5].innerText
            };
            data.push(rowData);
        }

        // Δημιουργία και λήψη αρχείου JSON
        var hiddenElement = document.createElement('a');
        hiddenElement.href = 'data:text/json;charset=utf-8,' + encodeURIComponent(JSON.stringify(data, null, 2));
        hiddenElement.target = '_blank';
        hiddenElement.download = 'Διπλωματικές.json';
        hiddenElement.click();
    }
	
	
	// συνάρτηση για την αυτόματη εισαγωγή δεδομένων στις φόρμες σημειώσεων και καταχώρησης βαθμού
	function rowClickHandler(event) {
		var row = event.target.closest('tr'); // Βρίσκουμε τη γραμμή του πίνακα που έγινε κλικ
		if (!row) return;

		// Παίρνουμε τα δεδομένα από τα κελιά της γραμμής
		var idDiploma = row.cells[0].textContent; 
		var statusDiploma = row.cells[4].textContent;  

		// Συμπληρώνουμε τα πεδία στη φόρμα
		document.getElementById('diplomaId').value = idDiploma;              // για τη φόρμα σημειώσεων 
		document.getElementById('diplomaStatus').value = statusDiploma;      // για τη φόρμα σημειώσεων
		
		document.getElementById('diplId').value = idDiploma;                 // για τη φόρμα καταχώρησης βαθμού
	}
</script>
</head>
<body onload="get()">

    <!-- Navigation bar -->
    <div class="navbar">
        <div class="menu">
            <!-- Ενότητες με υπομενού που οδηγούν στο professor.php -->
            <div>
                <a href="professor.php" class="menu-item">Section 1</a>
            </div>
            <div>
                <a href="professor2.php" class="menu-item">Section 2</a>
                <div class="submenu">
                    <a href="professor2.php">Submenu 2-1</a>
                    <a href="professor2_2.php">Submenu 2-2</a>
                </div>
            </div>
            <div>
                <a href="professor3.php" class="menu-item">Section 3</a>
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

     <!-- Περιεχόμενο -->
<div class="container">


<div class="left-section">
    <h1 class="table-title">Διπλωματικές</h1>

    <!-- Προσθήκη φίλτρου -->
<div style="margin-bottom: 20px;">
    <!-- Φίλτρο για status -->
    <label for="status_filter">Filter by Status:</label>
    <select id="status_filter">
        <option value="none">None</option>
        <option value="pending">Pending</option>
        <option value="active">Active</option>
        <option value="under examination">Under Examination</option>
        <option value="finished">Finished</option>
    </select>

    <!-- Φίλτρο για τύπο χρήστη -->
    <label for="role_filter">Filter by Role:</label>
    <select id="role_filter">
        <option value="none">None</option>
        <option value="supervisor">Supervisor</option>
        <option value="member">Member</option>
    </select>

	<button class="filter-btn" onclick="applyFilter()">Filter</button>
</div>

    <table id="item_table"></table>


            <!-- Προσθήκη κουμπιών εξαγωγής -->
            <div class="export-btn-container">
                <button class="export-btn" onclick="exportCSV()">Export to CSV</button>
                <button class="export-btn" onclick="exportJSON()">Export to JSON</button>
				<button class="export-btn" id="view_info_btn" onclick="viewInfo()" style="display: none;">View Info</button>
				<button class="export-btn" id="notes_btn" onclick="showNotesForm()" style="display: none;">Add Notes</button>
				<button class="export-btn" id="exam_btn" onclick="markUnderExamination()" style="display: none;">Set Under Examination</button>
				<button class="export-btn" id="grade_enable_btn" onclick="showGradeButton()" style="display: none;">Grade Enable</button>
				<button class="export-btn" id="grade_btn" onclick="showGradesForm()" style="display: none;">Grade</button>
            </div>
</div>

<div class="right-section">
    <h1 class="table-title">Λεπτομέρειες</h1>
    <table id="info_table"></table>

    <!-- Νέος πίνακας για τα δεδομένα από το log -->
	<div  id="log_table_container">
    <table id="log_table"></table>
	</div>
	
	
	<!-- Φόρμα για τη νέα είσοδο -->
	<form id="notesForm" action="make_notes.php" method="POST" class="notes-form-container" style="display: none;">
		<h2>Δημιουργία Σημειώσεων</h2>
    
		<label for="diplomaId">ID</label>
		<input type="text" id="diplomaId" name="diplomaId" placeholder="Εισάγετε τον κωδικό Διπλωματικής" class="form-input" required>
    
		<label for="diplomaStatus">Status</label>
		<input type="text" id="diplomaStatus" name="diplomaStatus" placeholder="Εισάγετε την κατάσταση της Διπλωματικής" class="form-input" required>
    
		<label for="notes">Notes</label>
		<textarea id="notes" name="notes" rows="4" cols="50"></textarea>
	
		<div class="button-wrapper">
			<button type="submit" onclick="cancelNotes()" class="form-button submit-btn">Υποβολή</button>
			<button type="button" onclick="clearForm()" class="form-button clear-btn">Καθαρισμός</button>
		</div>
	</form>
	
	
	
	<form id="gradesForm" action="insert_grade.php" method="POST" class="grades-form-container" style="display: none;">
		<h2>Καταχώρηση Βαθμού</h2>
    
		<label for="diplId">ID</label>
		<input type="text" id="diplId" name="diplId" placeholder="Εισάγετε τον κωδικό Διπλωματικής" class="form-input" required>
    
		<label for="diplomaGrade">Grade</label>
		<input type="text" id="diplomaGrade" name="diplomaGrade" placeholder="Εισάγετε τον βαθμό της Διπλωματικής" class="form-input" required>
	
		<div class="button-wrapper">
			<button type="submit" onclick="cancelNotes()" class="form-button submit-btn">Υποβολή</button>
			<button type="button" onclick="clearForm()" class="form-button clear-btn">Καθαρισμός</button>
		</div>
	</form>
</div>


</div>

</body>
</html>
