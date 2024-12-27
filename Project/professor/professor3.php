<?php
session_start();

// Έλεγχος αν ο χρήστης είναι συνδεδεμένος
if (!isset($_SESSION['email']) OR $_SESSION['type'] != 'PROF') {
    header("Location: logout.php");
    exit();
}

// Χρήστης που συνδέθηκε
$userEmail = $_SESSION['email'];

// Λογική για αποσύνδεση
if (isset($_POST['logout'])) {
    header("Location: logout.php");
    exit();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Professor Dashboard</title>
	<link rel="stylesheet" type="text/css" href="professor3.css">
</head>
<body onload="get()">

    <!-- Navigation bar -->
    <div class="navbar">
	
		<div class="logo">
    			<a href="professor.php">
			<img src="../media/logo.png" alt="Logo" class="logo-img">
		</div>
	
        <div class="menu">
            <!-- Ενότητες με υπομενού που οδηγούν στο professor.php -->
            <div>
                <a href="professor.php" class="menu-item">Θέματα</a>
            </div>
            <div>
                <a href="professor2.php" class="menu-item">Αναθέσεις</a>
                <div class="submenu">
                    <a href="professor2.php">Ανάθεση</a>
                    <a href="professor2_2.php">Ακύρωση Ανάθεσης</a>
                </div>
            </div>
            <div>
                <a href="professor3.php" class="menu-item">Διπλωματικές</a>
            </div>
            <div>
                <a href="professor4.php" class="menu-item">Προσκλήσεις</a>
            </div>
            <div>
                <a href="professor5.php" class="menu-item">Στατιστικά</a>
            </div>
            
        </div>

        <!-- Στοιχεία χρήστη -->
        <div class="user-info">
            <form action="" method="post" style="display:inline;">
                <button type="submit" name="logout" class="logout-btn">Logout</button>
            </form>
			<span id="userEmail"><?php echo htmlspecialchars($userEmail);?></span>
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

			<div class="table-container">
				<table id="item_table"></table>
			</div>

			<!-- Προσθήκη κουμπιών εξαγωγής -->
			<div class="export-btn-container">
				<button class="export-btn" onclick="exportCSV()">Export to CSV</button>
				<button class="export-btn" onclick="exportJSON()">Export to JSON</button>
				<button class="export-btn" id="view_info_btn" onclick="viewInfo()" style="cursor: not-allowed; opacity: 0.6;">View Info</button>
				<button class="export-btn" id="notes_btn" onclick="showNotesForm()" style="cursor: not-allowed; opacity: 0.6;">Add Notes</button>
				<button class="export-btn" id="exam_btn" onclick="markUnderExamination()" style="cursor: not-allowed; opacity: 0.6;">Set Under Examination</button>
				<button class="export-btn" id="grade_btn" onclick="showGradesForm()" style="cursor: not-allowed; opacity: 0.6;">Grade</button>
				<button class="export-btn" id="view_grades_btn" onclick="fetchGradesDetails()" style="cursor: not-allowed; opacity: 0.6;">View Grades</button>
				<button class="export-btn" id="invites_btn" onclick="viewInvites()" style="cursor: not-allowed; opacity: 0.6;">View Invites</button>
				<button class="export-btn" id="presentation_btn" onclick="viewPresentation()" style="cursor: not-allowed; opacity: 0.6;">View Presentation</button>
			</div>
		</div>

		<div class="right-section">
			<h1 class="table-title"></h1>
			
			<table id="info_table"></table>
		
	
			<!-- Νέος πίνακας για τα δεδομένα από το log -->
			<div  id="log_table_container">
				<table id="log_table"></table>
			</div>
			
			<div>
			<table id="grades_table"></table>
			</div>
	
	
			<!-- Φόρμα για τη νέα είσοδο -->
			<form id="notesForm" onsubmit="createNotes(event)" method="POST" class="notes-form-container" style="display: none;">
				<h2>Δημιουργία Σημειώσεων</h2>
			
				<label for="diplomaId">ID</label>
				<input type="text" id="diplomaId" name="diplomaId" placeholder="Εισάγετε τον κωδικό Διπλωματικής" class="form-input" style="pointer-events: none; background-color: lightgray; cursor: not-allowed;">
			
				<label for="diplomaStatus">Status</label>
				<input type="text" id="diplomaStatus" name="diplomaStatus" placeholder="Εισάγετε την κατάσταση της Διπλωματικής" class="form-input" style="pointer-events: none; background-color: lightgray; cursor: not-allowed;">
			
				<label for="notes">Notes</label>
				<textarea id="notes" name="notes" rows="4" cols="50"></textarea>
			
				<div class="button-wrapper">
					<button type="submit" class="form-button submit-btn">Υποβολή</button>
					<button type="button" onclick="clearForm()" class="form-button clear-btn">Καθαρισμός</button>
				</div>
			</form>
						
			
			<form id="gradesForm" onsubmit="insertGrade(event)" class="grades-form-container" style="display: none;">
				<h2>Καταχώρηση Βαθμού</h2>
			
				<label for="diplId">ID</label>
				<input type="text" id="diplId" name="diplId" placeholder="Εισάγετε τον κωδικό Διπλωματικής" class="form-input" style="pointer-events: none; background-color: lightgray; cursor: not-allowed;">
			
				<label for="diplomaGrade">Grade</label>
				<input type="text" id="diplomaGrade" name="diplomaGrade" placeholder="Εισάγετε τον βαθμό της Διπλωματικής" class="form-input" required>
			
				<div class="button-wrapper">
					<button type="submit" class="form-button submit-btn">Υποβολή</button>
					<button type="button" onclick="clearForm()" class="form-button clear-btn">Καθαρισμός</button>
				</div>
			</form>	
		</div>
	</div>

	<footer>
		<p>&copy; 2024 University of Patras - All Rights Reserved</p>
	</footer>
	
	
	

<script>

		let selectedId = null;     		// Για αποθήκευση του επιλεγμένου ID
		let selectedStatus = null; 		// Για αποθήκευση του επιλεγμένου status

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
						cell1 = row.insertCell(0);
						cell1.style.backgroundColor = "#00BFFF";
						cell1.style.color = "white";
						cell2 = row.insertCell(1);
						cell2.style.backgroundColor = "#00BFFF";
						cell2.style.color = "white";
						cell3 = row.insertCell(2);
						cell3.style.backgroundColor = "#00BFFF";
						cell3.style.color = "white";
						cell4 = row.insertCell(3);
						cell4.style.backgroundColor = "#00BFFF";
						cell4.style.color = "white";
						cell5 = row.insertCell(4);
						cell5.style.backgroundColor = "#00BFFF";
						cell5.style.color = "white";
						cell6 = row.insertCell(5);
						cell6.style.backgroundColor = "#00BFFF";
						cell6.style.color = "white";
						cell1.innerHTML = "Id Διπλωματικής";
						cell2.innerHTML = "Επιβλέπων";
						cell3.innerHTML = "Μέλος Τριμελής";
						cell4.innerHTML = "Μέλος Τριμελής";
						cell5.innerHTML = "Κατάσταση";
						cell6.innerHTML = "Τελικός Βαθμός";

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
								selectedId = item.id;         			// Αποθηκεύουμε το ID
								selectedStatus = item.status; 			// Αποθηκεύουμε το status
								
								// Check if the professor is the supervisor
								const professorEmail = document.getElementById("userEmail").innerHTML;				// Get the logged-in professor's email
								if (item.status === "active") {
									const gradeBtn = document.getElementById("grade_btn");
									gradeBtn.style.cursor = "not-allowed";								// Disable the "Grade" button
									gradeBtn.disabled = true;
									gradeBtn.style.opacity = "0.6";
									
									
									
									const presBtn = document.getElementById("presentation_btn");
									presBtn.style.cursor = "not-allowed";								// Disable the "View Presentation" button
									presBtn.disabled = true;
									presBtn.style.opacity = "0.6";


									const viewGradesBtn = document.getElementById("view_grades_btn");
									viewGradesBtn.style.cursor = "not-allowed";							// Disable the "View Grades" button
									viewGradesBtn.disabled = true;
									viewGradesBtn.style.opacity = "0.6";					
									if (item.supervisor === professorEmail){
										const examBtn = document.getElementById("exam_btn");
										examBtn.style.display = "inline"; 								// Show the "Set Under Examination" button	
										examBtn.disabled = false;
										examBtn.style.cursor = "pointer";
										examBtn.style.opacity = "1";
										
										
										const notesBtn = document.getElementById("notes_btn");
										notesBtn.style.display = "inline"; 								// Show the "Add Notes" button	
										notesBtn.disabled = false;
										notesBtn.style.cursor = "pointer";
										notesBtn.style.opacity = "1";
										
									}else {
										const examBtn = document.getElementById("exam_btn");
										examBtn.style.cursor = "not-allowed";							// Disable the "Set Under Examination" button
										examBtn.disabled = true;
										examBtn.style.opacity = "0.6";   


										const notesBtn = document.getElementById("notes_btn");
										notesBtn.style.display = "inline"; 								// Show the "Add Notes" button	
										notesBtn.disabled = false;
										notesBtn.style.cursor = "pointer";
										notesBtn.style.opacity = "1";
									}
							
								}else if (item.status === "under examination"){
									
									const examBtn = document.getElementById("exam_btn");
									examBtn.style.cursor = "not-allowed";								// Disable the "Set Under Examination" button
									examBtn.disabled = true;
									examBtn.style.opacity = "0.6";   
									
									
									const notesBtn = document.getElementById("notes_btn");
									notesBtn.style.cursor = "not-allowed";								// Disable the "Add Notes" button
									notesBtn.disabled = true;
									notesBtn.style.opacity = "0.6";   

									const viewGradesBtn = document.getElementById("view_grades_btn");
									viewGradesBtn.style.display = "inline"; 							// Show the "View Grades" button
									viewGradesBtn.disabled = false;
									viewGradesBtn.style.cursor = "pointer";
									viewGradesBtn.style.opacity = "1";
									
									if (item.supervisor === professorEmail ) {
										const gradeBtn = document.getElementById("grade_btn");
										gradeBtn.style.display = "inline"; 								// Show the "Grade" button
										gradeBtn.disabled = false;
										gradeBtn.style.cursor = "pointer";
										gradeBtn.style.opacity = "1";	


										const presBtn = document.getElementById("presentation_btn");
										presBtn.style.display = "inline"; 								// Show the "View Presentation" button
										presBtn.disabled = false;
										presBtn.style.cursor = "pointer";
										presBtn.style.opacity = "1";	

										
									}else {
											const xhr = new XMLHttpRequest();
											const url = `supervisor_approval.php?id=${selectedId}`;

											xhr.open('GET', url);
											xhr.send();

											xhr.onreadystatechange = function () {
												if (xhr.readyState === XMLHttpRequest.DONE) {
													if (xhr.status === 200) {
														const data = JSON.parse(xhr.responseText);

														if (data.grade1 !== null){
															const gradeBtn = document.getElementById("grade_btn");
															gradeBtn.style.display = "inline"; 			// Show the "Grade" button
															gradeBtn.disabled = false;
															gradeBtn.style.cursor = "pointer";
															gradeBtn.style.opacity = "1";
														}else{
															const gradeBtn = document.getElementById("grade_btn");
															gradeBtn.style.cursor = "not-allowed";		// Disable the "Grade" button
															gradeBtn.disabled = true;
															gradeBtn.style.opacity = "0.6";   
								
														}	
													} else {
														console.error("Error fetching member details");
													}
												}
											};			

											const presBtn = document.getElementById("presentation_btn");
											presBtn.style.cursor = "not-allowed";								// Disable the "View Presentation" button
											presBtn.disabled = true;
											presBtn.style.opacity = "0.6";
											
									}
								}else{
									const gradeBtn = document.getElementById("grade_btn");
									gradeBtn.style.cursor = "not-allowed";								// Disable the "Grade" button
									gradeBtn.disabled = true;
									gradeBtn.style.opacity = "0.6";  
									
									
									const presBtn = document.getElementById("presentation_btn");
									presBtn.style.cursor = "not-allowed";								// Disable the "View Presentation" button
									presBtn.disabled = true;
									presBtn.style.opacity = "0.6";
								
								
									const viewGradesBtn = document.getElementById("view_grades_btn");
									viewGradesBtn.style.cursor = "not-allowed";							// Disable the "View Grades" button
									viewGradesBtn.disabled = true;
									viewGradesBtn.style.opacity = "0.6";  
									
									const examBtn = document.getElementById("exam_btn");
									examBtn.style.cursor = "not-allowed";								// Disable the "Set Under Examination" button
									examBtn.disabled = true;
									examBtn.style.opacity = "0.6";   	 
									
									
									const notesBtn = document.getElementById("notes_btn");
									notesBtn.style.cursor = "not-allowed";								// Disable the "Add Notes" button
									notesBtn.disabled = true;
									notesBtn.style.opacity = "0.6"; 
								}
								
								
								
								
								
								document.getElementById("view_info_btn").style.display = "inline"; 		// Ενεργοποίηση κουμπιού λεπτομερειών διπλωματικής
								document.getElementById("view_info_btn").style.cursor = "pointer";
								document.getElementById("view_info_btn").style.opacity = "1";
								
								
								if(item.status === 'pending'){
								const invitesBtn = document.getElementById("invites_btn");
								invitesBtn.style.display = "inline"; 			                		// Show the "View Invites" button
								invitesBtn.disabled = false;
								invitesBtn.style.cursor = "pointer";
								invitesBtn.style.opacity = "1";		
								}
								else{
									
									const invitesBtn = document.getElementById("invites_btn");
									invitesBtn.style.cursor = "not-allowed";							// Disable the "View Invites" button
									invitesBtn.disabled = true;
									invitesBtn.style.opacity = "0.6";  
									
								}
								
								rowClickHandler(event);  // συνάρτηση που γεμίζει αυτόματα τις φόρμες σημειώσεων και βαθμών σε κάθε 'click' σε γραμμή
							};
							
						});
					} else {
						console.error("Error fetching data");
					}
				}
			};
		}

			

		// Συνάρτηση εμφάνισης λεπτομερειών για την επιλεγμένη γραμμή πίνακα/id διπλωματικής 
		function viewInfo() {
			if (!selectedId) return;
			
			// Κώδικας για την περίπτωση όπου πριν το View έχω πατήσει άλλο button
			const gradesTable = document.getElementById('grades_table');
			const gradesContainer = document.getElementById('gradesForm');
			const notesContainer = document.getElementById('notesForm');
			const infoTable = document.getElementById('info_table');
			const logTable = document.getElementById('log_table');

			// Hide the Notes and Grades form and the View Grades table
			notesContainer.style.display = 'none';
			gradesContainer.style.display = 'none';
			gradesTable.style.display = 'none';

			// Show the Info table
			infoTable.style.display = 'table';
			
			// Show the Log table
			logTable.style.display = 'table';
			
			
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
    							"PDF (main text)": data.pdf_main_diploma ? `<a href="${data.pdf_main_diploma}" target="_blank">${data.pdf_main_diploma.substring(20)}</a>` : "", // Αφαίρεση των πρώτων 5 χαρακτήρων από το κείμενο του link
    							"Email Student": data.email_stud,
    							"Supervisor": data.supervisor,
    							"Member 1": data.member1,
    							"Member 2": data.member2,
    							"Final Grade": data.final_grade,
   							"Nemertes Link": data.Nemertes_link ? `<a href="${data.Nemertes_link}" target="_blank">Link</a>` : "", // Έλεγχος για κενό link
    							"Πρακτικό Βαθμολόγησης": data.praktiko_bathmologisis ? `<a href="${data.praktiko_bathmologisis}" target="_blank">${data.praktiko_bathmologisis.substring(20)}</a>` : "", // Link για το praktiko bathmologisis
							"Supporting Links": data.external_links							
						};
	
						// Loop through the details and insert rows
						for (const [key, value] of Object.entries(details)) {
							const row = tbody.insertRow();
							const cell1 = row.insertCell(0);
							const cell2 = row.insertCell(1);

							cell1.innerHTML = `<strong>${key}</strong>`;
							cell2.innerHTML = value;
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
					
			
			
		// Συνάρτηση εμφάνισης παρουσίασης για την επιλεγμένη γραμμή πίνακα/id διπλωματικής 
		function viewPresentation() {
			if (!selectedId) return;
			
			// Κώδικας για την περίπτωση όπου πριν το "View Presentation" έχω πατήσει άλλο button
			const gradesTable = document.getElementById('grades_table');
			const gradesContainer = document.getElementById('gradesForm');
			const notesContainer = document.getElementById('notesForm');
			const infoTable = document.getElementById('info_table');
			const logTable = document.getElementById('log_table');

			// Hide the Notes and Grades form and the View Grades, Info, Log tables
			notesContainer.style.display = 'none';
			gradesContainer.style.display = 'none';
			gradesTable.style.display = 'none';
			infoTable.style.display = 'none';
			logTable.style.display = 'none';
			
			
		
			// Redirect to anakoinosi.php with the selected ID
			const url = `anakoinosi_professor.php?id=${selectedId}`;
			window.location.href = url;
		}	
			
			
					
			
		// συνάρτηση για εμφάνιση των προσκλήσεων σε καθηγητές για συγκεκριμένη διπλωματική
		function viewInvites() {
			if (!selectedId) return;
			
			
			// Κώδικας για την περίπτωση όπου πριν το View Invites έχω πατήσει άλλο button
			const gradesTable = document.getElementById('grades_table');
			const gradesContainer = document.getElementById('gradesForm');
			const notesContainer = document.getElementById('notesForm');
			const infoTable = document.getElementById('info_table');
			const logTable = document.getElementById('log_table');

			// Show the log table containing the invites
			logTable.style.display = 'table';
			
			// Hide the Info and View Grades tables and the Grades and Notes form
			infoTable.style.display = 'none';  
			gradesTable.style.display = 'none';
			gradesContainer.style.display = 'none';
			notesContainer.style.display = 'none';


			const xhr = new XMLHttpRequest();
			const url = `view_invites.php?id=${selectedId}`;

			xhr.open('GET', url);
			xhr.send();

			xhr.onreadystatechange = function () {
				if (xhr.readyState === XMLHttpRequest.DONE) {
					if (xhr.status === 200) {
						const logData = JSON.parse(xhr.responseText);
						console.log(logData);

						// Εντοπισμός του πίνακα log
						const logTable = document.getElementById("log_table");

						// Δημιουργία ή καθαρισμός του πίνακα, διατηρώντας μόνο τις στήλες
						logTable.innerHTML = ""; // Καθαρισμός του πίνακα

						// Δημιουργία κεφαλίδας
						const headerRow = logTable.insertRow();
						["Professor", "Reply", "Invitation Date", "Reply Date"].forEach(header => {
							const th = document.createElement("th");
							th.style.backgroundColor = "#00BFFF";
							th.style.color = "#white";
							th.style.fontWeight = "normal";
							th.innerText = header;
							headerRow.appendChild(th);
						});

						if (logData && Array.isArray(logData) && logData.length > 0) {
							// Συμπλήρωση δεδομένων αν υπάρχουν
							logData.forEach(entry => {
								const row = logTable.insertRow();
								cell = row.insertCell(0);
								cell.style.fontWeight = "normal";
								cell.innerText = entry.prof_email;
								row.insertCell(1).innerText = entry.status;
								row.insertCell(2).innerText = entry.invitation_date;
								row.insertCell(3).innerText = entry.reply_date;
							});
						} 
						// Αν δεν υπάρχουν δεδομένα, ο πίνακας παραμένει με τις στήλες αλλά χωρίς γραμμές
					} else {
						console.error("Error fetching invites: " + xhr.statusText);
						// Αν υπάρχει σφάλμα, καθαρίζεται ο πίνακας και παραμένουν οι στήλες
						document.getElementById("log_table").innerHTML = "";
						const logTable = document.getElementById("log_table");
						const headerRow = logTable.insertRow();
						["Professor", "Reply", "Invitation Date", "Reply Date"].forEach(header => {
							const th = document.createElement("th");
							th.innerText = header;
							headerRow.appendChild(th);
						});
					}
				}
			};
		
		}
		   
		
			
		// συνάρτηση για εμφάνιση της φόρμας εισαγωγής σημειώσεων
		function showNotesForm() {
			if (!selectedId) return;
			
			// Κώδικας για την περίπτωση όπου πριν το Notes έχω πατήσει άλλο button
			const gradesTable = document.getElementById('grades_table');
			const gradesContainer = document.getElementById('gradesForm');
			const notesContainer = document.getElementById('notesForm');
			const infoTable = document.getElementById('info_table');
			const logTable = document.getElementById('log_table');

			// Show the Notes form
			notesContainer.style.display = 'block';
			notesContainer.style.pointerEvents = 'auto'; 			// Enable form interaction
			
			// Hide the Log, Info and View Grades tables and the Grades form
			logTable.style.display = 'none';
			infoTable.style.display = 'none';  
			gradesContainer.style.display = 'none';
			gradesTable.style.display = 'none';
		}


		function createNotes(event){
			event.preventDefault(); 								// Prevent default form submission

			// Get the form element
			const form = document.getElementById("notesForm");
			const formData = new FormData(form); 					// Collect form data

			// Send the form data via AJAX
			const xhr = new XMLHttpRequest();
			xhr.open("POST", "make_notes.php", true);
			xhr.onload = function () {
				if (xhr.status === 200) {
					const response = JSON.parse(xhr.responseText);
					if (response.success) {
						alert("Notes created successfully!"); 		// Success alert
						form.style.display = 'none';				// Hide form after alert
						clearForm();								// Clear form 
					} else {
						alert("Error: " + response.error); 	        // Error alert
					}
				} else {
					alert("Server error. Please try again.");
				}
			};
			xhr.send(formData); // Send the form data	
		}
			
			
			
		// συνάρτηση καθαρισμού της φόρμας σημειώσεων και της φόρμας καταχώρησης βαθμού
		function clearForm() {
			document.getElementById('notes').value = '';
			document.getElementById('diplomaId').value = '';
			document.getElementById('diplomaStatus').value = '';
			
			document.getElementById('diplId').value = '';        	// may delete later...
			document.getElementById('diplomaGrade').value = '';
		}
		
	
		// συνάρτηση για ενημέρωση του status διπλωματικής σε "Υπό Εξέταση"
		function markUnderExamination() {
			if (!selectedId) return; // Ensure selectedId is valid

			const xhr = new XMLHttpRequest();
			const url = `update_status.php?id=${selectedId}`;  		// sending the 'id'

			xhr.open('GET', url);
			xhr.send();

			xhr.onreadystatechange = function () {
				if (xhr.readyState === XMLHttpRequest.DONE) {
					if (xhr.status === 200) {
						const response = JSON.parse(xhr.responseText);
						if (response.success) {
							alert("Status updated to 'Under Examination'");	
							applyFilter(); // Refresh the table
						} else {
							alert("Error: " + response.error); 	        // Error alert
						}
								
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
			const gradesTable = document.getElementById('grades_table');
			const gradesContainer = document.getElementById('gradesForm');
			const notesContainer = document.getElementById('notesForm');
			const infoTable = document.getElementById('info_table');
			const logTable = document.getElementById('log_table');

			// Show the Grades form
			gradesContainer.style.display = 'block';
			gradesContainer.style.pointerEvents = 'auto'; 			// Enable form interaction
			
			// Hide the Log, Info, Notes and View Grades tables
			logTable.style.display = 'none';
			infoTable.style.display = 'none'; 
			notesContainer.style.display = 'none';
			gradesTable.style.display = 'none';
		}
			
			
		// συνάρτηση εμφάνισης του κουμπιού εισαγωγής βαθμού
		function showGradeButton() {
			const gradeBtn = document.getElementById("grade_btn");
			gradeBtn.style.display = "inline"; 						// Show the "Grade" button
		}
		
			
		function insertGrade(event) {
			event.preventDefault(); // Prevent default form submission

			// Get the form element
			const form = document.getElementById("gradesForm");
			const formData = new FormData(form); // Collect form data

																	// Send the form data via AJAX
			const xhr = new XMLHttpRequest();
			xhr.open("POST", "insert_grade.php", true);
			xhr.onload = function () {
				if (xhr.status === 200) {
					const response = JSON.parse(xhr.responseText);
					if (response.success) {
						alert("Grade inserted successfully!"); 		// Success alert
						form.style.display = 'none';	            // Hide form after alert
						applyFilter(); // Refresh the table
					} else {
						alert("Error: " + response.error); 	        // Error alert
					}
				} else {
					alert("Server error. Please try again.");
				}
			};
			xhr.send(formData); 									// Send the form data
		}

			
		// συνάρτηση για προβολή των βαθμών των καθηγητών-μελών τριμελούς
		function fetchGradesDetails() {
			if (!selectedId) return;
			
			// Κώδικας για την περίπτωση όπου πριν το View Grades έχω πατήσει άλλο button
			const gradesTable = document.getElementById('grades_table');
			const gradesContainer = document.getElementById('gradesForm');
			const notesContainer = document.getElementById('notesForm');
			const infoTable = document.getElementById('info_table');
			const logTable = document.getElementById('log_table');

			// Hide the Notes and Grades form
			notesContainer.style.display = 'none';
			gradesContainer.style.display = 'none';

			// Hide the Info table
			infoTable.style.display = 'none';
			
			// Hide the Log table
			logTable.style.display = 'none';
			
			// Show the View Grades table
			gradesTable.style.display = 'table';

			const xhr = new XMLHttpRequest();
			const url = `view_grades.php?id=${selectedId}`;

			xhr.open('GET', url);
			xhr.send();

			xhr.onreadystatechange = function () {
				if (xhr.readyState === XMLHttpRequest.DONE) {
					if (xhr.status === 200) {
						const data = JSON.parse(xhr.responseText);

						
						// Ενημέρωση δεξιού πίνακα με τους βαθμούς
						const rightTable = document.getElementById("grades_table");
						rightTable.innerHTML = '';

						// Δημιουργία γραμμών για κάθε λεπτομέρεια
						const tbody = rightTable.createTBody();

						const details = {
							"Supervisor": data.supervisor,
							"Member 1": data.member1,
							"Member 2": data.member2,
							"Grade 1":	data.grade1,
							"Grade 2":	data.grade2,
							"Grade 3":	data.grade3,
							"Final Grade": data.final_grade
						};

						// Loop through the details and insert rows
						for (const [key, value] of Object.entries(details)) {
							const row = tbody.insertRow();
							const cell1 = row.insertCell(0);
							const cell2 = row.insertCell(1);

							cell1.innerHTML = `<strong>${key}</strong>`;
							cell2.innerHTML = value;				
						}
					} else {
						console.error("Error fetching member details");
					}
				}
			};
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

			// Συμπληρώνουμε τα πεδία στις φόρμες σημειώσεων και καταχώρησης βαθμού
			if(selectedStatus === 'active'){
			document.getElementById('diplomaId').value = idDiploma;              // για τη φόρμα σημειώσεων 
			document.getElementById('diplomaStatus').value = statusDiploma;      // για τη φόρμα σημειώσεων
			}
			
			if(selectedStatus === 'under examination'){
				document.getElementById('diplId').value = idDiploma;             // για τη φόρμα καταχώρησης βαθμού
			}
		}
</script>

</body>
</html>
