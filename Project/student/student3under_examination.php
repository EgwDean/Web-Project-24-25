<?php
session_start();

// Έλεγχος αν ο χρήστης είναι συνδεδεμένος
if (!isset($_SESSION['email'])) {
    header("Location: logout.php");
    exit();
}

if ($_SESSION['type'] != 'STUDENT') {
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

if (isset($_SESSION['status'])) {
    if ($_SESSION['status'] != "under examination") {
        header("Location: logout.php");
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
	<script src="https://cdnjs.cloudflare.com/ajax/libs/xlsx/0.17.3/xlsx.full.min.js"></script>
    <title>Student Page</title>
    <style>
        /* Στυλ για τη σελίδα */
        body {
            font-family: Calibri, Arial, sans-serif;
            margin: 0;
            background-color: #f4f4f4;
            display: flex;
            flex-direction: column;
			height: 100vh
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

        .menu-item {
            color: #fff;
			background-color: #444;
            text-decoration: none;
            padding: 10px;
			transition: background-color 0.3s ease;
			border-radius: 4px;
        }

		.menu-item:hover {
			background-color: #00BFFF;
			color: white;
		}

        .navbar .user-info {
            display: flex;
            align-items: center;
            gap: 15px;
            white-space: nowrap;
        }

        .logout-btn {
            padding: 8px 15px;
            background-color: #444;
            color: #fff;
            border: none;
            border-radius: 4px;
            cursor: pointer;
			transition: background-color 0.3s ease;
        }

        .logout-btn:hover {
            background-color: #00BFFF;
        }

        /* Στυλ για τη διάταξη των στηλών */
		.content {
			flex: 1; 
			display: flex;
			gap: 10px;
			padding: 10px;
			box-sizing: border-box;
			overflow: auto;
			margin-top: 10px;
		}

		.column {
			flex: 1; 
			display: flex;
			flex-direction: column; 
			gap: 10px;
		}

		.box {
			flex: 1; 
			justify-content: center;
			align-items: center;
			background-color: #e9e9e9;
			padding: 20px;
			border-radius: 10px;
			box-shadow: 0 2px 5px rgba(0, 0, 0, 0.1);
			box-sizing: border-box; 
			text-align: center;
		}

		footer {
			background-color: #192f59;
			color: white;
			text-align: center;
			padding: 10px 0;
			width: 100%;
		}

		.form-label {
		font-size: 16px;
		color: #333;
		display: block;
		text-align: left;
		}

		.form-input {
		width: 100%;
		border: 1px solid #ccc;
		border-radius: 4px;
		font-size: 14px;
		}

		.form-button {
		background-color: #fff;
		color: black;
		border: none;
		border-radius: 4px;
		cursor: pointer;
		font-size: 16px;
		transition: background-color 0.3s ease;
		margin-top: 5px;
		}

		.form-button:hover {
		background-color: #00BFFF;
		color: white;
		}

		#praktikoButton {
			background-color: #fff;
			color: black;
			border: none;
			border-radius: 4px;
			cursor: pointer;
			font-size: 16px;
			transition: background-color 0.3s ease;
			margin-top: 30px;
			margin-right: 2px;
			margin-left: 2px;
		}

		#showPraktikoButton {
			background-color: #fff;
			color: black;
			border: none;
			border-radius: 4px;
			cursor: pointer;
			font-size: 16px;
			transition: background-color 0.3s ease;
			margin-top: 30px;
			margin-right: 2px;
			margin-left: 2px;
		}

		#showPraktikoButton:enabled:hover {
			background-color: #00BFFF;
			color: white;
		}

		#showPraktikoButton:disabled {
			cursor: not-allowed;
			background-color: #ccc;
			color: #666;
		}

		#praktikoButton:disabled {
			cursor: not-allowed;
			background-color: #ccc;
			color: #666;
		}

		#praktikoButton:enabled:hover {
			background-color: #00BFFF;
			color: white;
		}

		#praktikoLabel {
			font-size: 12px;
			color: #F00;
			margin-top: 5px;
			display: block;
			text-align: center;
		}

		.hidden {
			display: none;
		}

		#fileLabel a {
			display: inline-block;
			margin-bottom: 2px;
			color: white;
		}

		#nemertesLabel a {
			display: inline-block;
			margin-bottom: 2px;
			color: white; 
		}

		h3 {
			display: block;
			margin: 1px;
			margin-top: 8px;
			background-color: #00BFFF;
			color: white;
		}

		.praktiko-container {
			display: flex;
			justify-content: center;
		}

    </style>

<script>	

     // Συνάρτηση που ελέγχει το status διπλωματικής και ανακατευθύνει κατάλληλα με το πάτημα του "Διαχείριση Διπλωματικής"
	 function statusRedirectManagement() { 
            event.preventDefault();

			const urlParams = new URLSearchParams(window.location.search);
			status = urlParams.get("data");

            // Διαφορετικη ανακατευθυνση αναλογα με το status
            if (status === "pending") {
                window.location.href = "student3pending.php?data=pending";
            } else if (status === "under_examination") {
                window.location.href = "student3under_examination.php?data=under_examination";
            } else if (status === "finished") {
                window.location.href = "student3finished.php?data=finished";
            } else if (status === "notfound") {
                alert("Diploma thesis not found.");
            } else {
                alert("Current status of diploma thesis does not allow its management.");
            }

    }
	
     // Συνάρτηση που ελέγχει το status διπλωματικής και ανακατευθύνει κατάλληλα με το πάτημα του "Επεξεργασία Προφίλ"
	 function statusRedirectProccess() { 
            event.preventDefault();

				const urlParams = new URLSearchParams(window.location.search);
    			status = urlParams.get("data");

                // Διαφορετικη ανακατευθυνση αναλογα με το status
                if (status === "pending") {
                    window.location.href = "student2.php?data=pending";
                } else if (status === "under_examination") {
                    window.location.href = "student2.php?data=under_examination";
                } else if (status === "finished") {
                    window.location.href = "student2.php?data=finished";
                } else if (status === "notfound") {
                    window.location.href = "student2.php?data=notfound"
                }
                else {
                    window.location.href = "student2.php?data=other"
                }
            
    }

	function get() { // Εμφάνιση πινάκων
		getTable1();
		getTable2();
	}

	function getTable2() { // Κατασκευή πίνακα για στοιχεία εξέτασης
		const table = document.getElementById("item_table2");

		function renderTable(item) {
			table.innerHTML = ""; // Καθαρισμός πίνακα

			table.style.height = "30%";
			table.style.width = "100%";

			const header = table.createTHead(); // Κεφαλίδα πίνακα
			const row = header.insertRow(0);
			const cell1 = row.insertCell(0);
			const cell2 = row.insertCell(1);
			const cell3 = row.insertCell(2);
			cell1.innerHTML = "<b>Επιβλέπων</b>";
			cell2.innerHTML = "<b>Καθηγητής Α</b>";
			cell3.innerHTML = "<b>Καθηγητής Β</b>";
			row.style.backgroundColor = "#00BFFF";
			row.style.color = "white";

			const tbody = table.createTBody(); // Σώμα πίνακα
			const rowData = tbody.insertRow(-1); 
			const cell1Data = rowData.insertCell(0);
			const cell2Data = rowData.insertCell(1);
			const cell3Data = rowData.insertCell(1);
			cell1Data.innerHTML = item.supervisor;
			cell2Data.innerHTML = item.member1;
			cell3Data.innerHTML = item.member2;
		}

		var xhr = new XMLHttpRequest(); // Αποστολή request μέσω AJAX
		xhr.open('GET', 'getExamDetails2.php');
		xhr.send();

		xhr.onload = function () {
			if (xhr.status === 200) { // Αν το request ήταν επιτυχές
				const data = JSON.parse(xhr.responseText); // Parse την απάντηση του server
				const item = data.item; // Ανάκτηση του δεδομένου
				renderTable(item);
			} else {
				alert("Error fetching data:", xhr.status);
			}
		};
	}

	function getTable1() { // Κατασκευή πίνακα για στοιχεία εξέτασης
		const table = document.getElementById("item_table1");

		function renderTable(item) {
			table.innerHTML = ""; // Καθαρισμός πίνακα

			table.style.height = "30%";
			table.style.width = "100%";

			const header = table.createTHead(); // Κεφαλίδα πίνακα
			const row = header.insertRow(0);
			const cell1 = row.insertCell(0);
			const cell2 = row.insertCell(1);
			cell1.innerHTML = "<b>Ημερομηνία και Ώρα</b>";
			cell2.innerHTML = "<b>Αίθουσα/Link</b>";
			row.style.backgroundColor = "#00BFFF";
			row.style.color = "white";

			const tbody = table.createTBody(); // Σώμα πίνακα
			const rowData = tbody.insertRow(-1); 
			const cell1Data = rowData.insertCell(0);
			const cell2Data = rowData.insertCell(1);
			cell1Data.innerHTML = item.exam_date;
			cell2Data.innerHTML = item.exam_room;
		}

		var xhr = new XMLHttpRequest(); // Αποστολή request μέσω AJAX
		xhr.open('GET', 'getExamDetails1.php');
		xhr.send();

		xhr.onload = function () {
			if (xhr.status === 200) { // Αν το request ήταν επιτυχές
				const data = JSON.parse(xhr.responseText); // Parse την απάντηση του server
				const item = data.item; // Ανάκτηση του δεδομένου
				renderTable(item);
			} else {
				alert("Error fetching data:", xhr.status);
			}
		};
	}

	// Έλεγχος αν η ημερομηνία και ώρα έχουν το κατάλληλο format
	function validateDateTime() {
		const dateTime = document.getElementById('dateField').value;

		const regex = /^\d{4}-\d{2}-\d{2} \d{2}:\d{2}:\d{2}$/;

		if (!regex.test(dateTime)) {
			alert('Please enter the date and time in the format: YYYY-MM-DD HH:MM:SS');
			return false;
		}
		return true;
	}

	function fetchAndProcessFile() { // 
		var xhr = new XMLHttpRequest();
		xhr.open('GET', 'fetchExcelFile.php', true); // Fetch file from the server
		xhr.responseType = 'arraybuffer'; 

		xhr.onload = function() {
			if (xhr.status === 200) {
				var arrayBuffer = xhr.response;

				// Read the Excel file
				var workbook = XLSX.read(new Uint8Array(arrayBuffer), {type: 'array'});
				var sheet = workbook.Sheets[workbook.SheetNames[0]];

				// Αίτηση στο server για τα δεδομένα
				var dataRequest = new XMLHttpRequest();
				dataRequest.open('GET', 'get_praktiko.php', true);

				dataRequest.onload = function() {
					if (dataRequest.status === 200) {
						var data = JSON.parse(dataRequest.responseText)['items'];

						if (data && data.length > 0) {
							var diploma_id = data[0]['diploma_id'];
							var supervisor = data[0]['supervisor_full_name'];
							var member1 = data[0]['member1_full_name'];
							var member2 = data[0]['member2_full_name'];
							var student_name = data[0]['student_full_name'];
							var am = data[0]['am'];            
							var examination_date = data[0]['examination_date'];
							var examination_room = data[0]['examination_room'];
							var grade1 = data[0]['grade_member1'];
							var grade2 = data[0]['grade_member2'];
							var grade3 = data[0]['grade_member3'];
							var final_grade = data[0]['final_grade'];
							var diploma_title = data[0]['diploma_title'];
							var p_number = data[0]['protocol_number'];

							// Γράψιμο των δεδομένων στα κελιά
							sheet["C7"] = { v: student_name };
							sheet["I7"] = { v: am };
							sheet["C8"] = { v: diploma_title };
							sheet["A10"] = { v: supervisor };
							sheet["A11"] = { v: member1 };
							sheet["A12"] = { v: member2 };
							sheet["E13"] = { v: examination_room };
							sheet["E14"] = { v: examination_date };
							sheet["A17"] = { v: supervisor };
							sheet["A18"] = { v: member1 };
							sheet["A19"] = { v: member2 };
							sheet["G21"] = { v: p_number };
							sheet["G28"] = { v: student_name };
							sheet["F44"] = { v: grade1 };
							sheet["F45"] = { v: grade2 };
							sheet["F46"] = { v: grade3 };
							sheet["F48"] = { v: final_grade };
							sheet["A44"] = { v: supervisor };
							sheet["A45"] = { v: member1 };
							sheet["A46"] = { v: member2 };
							sheet["A51"] = { v: student_name };
							sheet["A59"] = { v: supervisor };
							sheet["A60"] = { v: member1 };
							sheet["A61"] = { v: member2 };

							// Αποθήκευση του excel file
							var wbout = XLSX.write(workbook, {bookType: 'xlsx', type: 'binary'});

							// Μετατροπή σε blob για ανέβασμα
							var blob = new Blob([s2ab(wbout)], {type: 'application/octet-stream'});

							// Ανέβασμα του Excel file
							var uploadRequest = new XMLHttpRequest();
							uploadRequest.open('POST', 'uploadPraktiko.php', true);

							var formData = new FormData();
							formData.append('file', blob, 'praktiko_simplified.xlsx');

							uploadRequest.onload = function() {
								if (uploadRequest.status === 200) {
									if (JSON.parse(uploadRequest.responseText)['message'] === "Success") {
										console.log('Το αρχείο ανέβηκε επιτυχώς στον server!');
									} else {
										console.log('Σφάλμα κατά την αποθήκευση!');
									}
								} else {
									console.log('Σφάλμα κατά το ανέβασμα του αρχείου.');
								}
							};

							uploadRequest.send(formData);
						}
					}
				};

				dataRequest.send();
			}
		};

		xhr.send();

		// Helper function to convert string to array buffer
		function s2ab(s) {
			var buf = new ArrayBuffer(s.length);
			var view = new Uint8Array(buf);
			for (var i = 0; i < s.length; i++) {
				view[i] = s.charCodeAt(i) & 0xFF;
			}
			return buf;
		}
	}

	function excelToHtml() {
		var xhr = new XMLHttpRequest();
		xhr.open('GET', 'get_excel_data.php', true); // Λήψη του νέου file από το server
		xhr.responseType = 'arraybuffer'; 

		xhr.onload = function() {
			if (xhr.status === 200) {
				var arrayBuffer = xhr.response;

				// Διάβασμα του Excel file
				var workbook = XLSX.read(new Uint8Array(arrayBuffer), {type: 'array'});
				var sheet = workbook.Sheets[workbook.SheetNames[0]];
				// Μετατροπή σε HTML
				var htmlContent = XLSX.utils.sheet_to_html(sheet, { id: "Sheet1" });
				// Μετατροπή σε blob για αποστολή
				var blob = new Blob([htmlContent], { type: "text/html" });

				// Ανέβασμα του HTML file
				var uploadRequest = new XMLHttpRequest();
				uploadRequest.open('POST', 'uploadPraktiko.php', true);

				var formData = new FormData();
				formData.append('file', blob, 'praktiko_simplified.html');

				uploadRequest.onload = function() {
					if (uploadRequest.status === 200) {
						if (JSON.parse(uploadRequest.responseText)['message'] === "Success") {
							console.log('Το αρχείο ανέβηκε επιτυχώς στον server!');
						} else {
							console.log('Σφάλμα κατά την αποθήκευση του HTML!');
						}
					} else {
						console.log('Σφάλμα κατά το ανέβασμα του HTML.');
					}
				};

				uploadRequest.send(formData);
			} else {
				console.log('Server error!');
			}
		}
		xhr.send();
	}


    document.addEventListener('DOMContentLoaded', waitToLoad);

	function waitToLoad() { // Εδώ μπαίνει ότι απαιτεί να έχει φορτωθεί η σελίδα για να τρέξει
		get(); // Φόρτωση πίνακα
		getPdfLink(); // Ενημέρωση pdf link
		getNemertesLink(); // Ενημέρωση Nemertes Link

		const xhr = new XMLHttpRequest(); // Έλεγχος αν υπάρχει προβιβάσιμος βαθμός 
		xhr.open("POST", "checkGrade.php", true);
		xhr.onload = function () {
			if (xhr.status === 200) {
				const response = JSON.parse(xhr.responseText);
				if (response.message = 1) {
					document.getElementById('praktikoButton').disabled = false
					document.getElementById('showPraktikoButton').disabled = false
				}
			}
		};
		xhr.send();

		document.getElementById('examForm').addEventListener('submit', function (event) { // Λειτουργία φόρμας
			event.preventDefault();

			if (validateDateTime()) { // Αν η ημερομηνία είαι σε σωστό format
				const exam_date = document.getElementById('dateField').value; // Ανάκτηση input
				const exam_room = document.getElementById('roomField').value;

				var xhr = new XMLHttpRequest(); // Δημιουργία αίτησης
				xhr.open('POST', 'updateExam.php', true);
				xhr.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded');

				xhr.onload = function () {
					if (xhr.status !== 200) { // Ανεπιτυχής αίτηση
						alert("Request not completed");
					} else { // Επιτυχής αίτηση
						alert("Examination details updated."); 
					}
				};

				// Αποστολή δεδομένων
				xhr.send('dateField=' + encodeURIComponent(exam_date) + '&roomField=' + encodeURIComponent(exam_room));
				get(); // Ανανέωση πίνακα
				header("Location: " . $_SERVER['PHP_SELF']); // Page reload
			}
		});

		document.getElementById('addProxeiro').addEventListener('click', function () {
			document.getElementById('inputProxeiro').click(); // Ενεργοποιείται το file input για click του κουμπιού "Κείμενο Διπλωματικής"
		});

		document.getElementById('inputProxeiro').addEventListener('change', function (event) {
			const fileInput = event.target;
    		const file = fileInput.files[0]; // Ανάκτηση του file

			if (file.type === "application/pdf") { // Έλεγχος αν είναι pdf
				const formData = new FormData();
				formData.append('file', file);

				const xhr = new XMLHttpRequest(); // Δημιουργία αίτησης
				xhr.open('POST', 'uploadProxeiro.php', true);
				xhr.onload = function () {
					if (xhr.status === 200) {
						const response = JSON.parse(xhr.responseText);
						if (response.success) { // Προβολή του pdf link κάτω από το κουμπί
							const fileLabel = document.getElementById('fileLabel');
							const fileLink = document.createElement('a');
							fileLink.textContent = response.file;
							fileLink.href = `/Project/uploads/students/${response.file}`;
							fileLink.target = '_blank';
							fileLabel.innerHTML = '';
							fileLabel.appendChild(fileLink);

                    	alert(response['success']);
						} else {
							alert(response.error);
						}
					} else {
						alert(response.error);
					}
				};

				xhr.send(formData); // Αποστολή του file
				fileInput.value = ""; // reset
			} else {
				alert("Not a PDF file");
				fileInput.value = "";
			}
		});

		function getPdfLink() { // Ανάκτηση του pdf link
			const xhr = new XMLHttpRequest();
			xhr.open('POST', 'getPdfLink.php', true);
			xhr.onload = function () {
				if (xhr.status === 200) {
					const response = JSON.parse(xhr.responseText);
					const pdfLink = response['pdf'];
					if (pdfLink) {
						updatePdfLink(pdfLink);
					}
				} else {
					console.error('Failed to fetch PDF link.');
				}
			};
			xhr.send();
		}

		function updatePdfLink(pdfLink) { // Ενημέρωση του HTML link
			const fileLabel = document.getElementById('fileLabel');
			const fileLink = document.createElement('a');
			fileLink.textContent = pdfLink.slice(17);
			fileLink.href = `/Project/${pdfLink}`;
			fileLink.target = '_blank';
			fileLabel.innerHTML = '';
			fileLabel.appendChild(fileLink);
		}

		function getNemertesLink() { // Ανάκτηση του Nemertes link
			const xhr = new XMLHttpRequest();
			xhr.open('POST', 'getNemertesLink.php', true);
			xhr.onload = function () {
				if (xhr.status === 200) {
					const response = JSON.parse(xhr.responseText);
					const link = response['link'];
					if (link) {
						updateNemertesLink(link);
					}
				} else {
					console.error('Failed to fetch Nemertes link.');
				}
			};
			xhr.send();
		}

		function updateNemertesLink(link) { // Ενημέρωση του Nemertes link
			const nemertesLabel = document.getElementById('nemertesLabel');
			const nemertesLink = document.createElement('a');
			nemertesLink.textContent = link;
			nemertesLink.href = link;
			nemertesLink.target = '_blank';
			nemertesLabel.innerHTML = '';
			nemertesLabel.appendChild(nemertesLink);
		}

		// Λειτουργία του κουμπιού για ανέβασμα links
		document.getElementById("linksForm").addEventListener("submit", function (event) {
			event.preventDefault();

			const inputs = document.querySelectorAll("#linksForm .form-input"); // Παίρνει όλα τα links
			const links = Array.from(inputs).map(input => input.value); // Βάζει τις τιμές τους σε πίνακα

			const formData = new FormData();
			links.forEach(link => formData.append("links[]", link)); // Τα βάζει σε formData

			const xhr = new XMLHttpRequest();
			xhr.open("POST", "uploadLinks.php", true);
			xhr.onload = function () {
				if (xhr.status === 200) {
					const response = JSON.parse(xhr.responseText);
					alert(response.message);
				} else {
					alert(response.message);
				}
			};
			xhr.send(formData); // Αποστολή δεδομένων
		});

		document.getElementById('nemertesForm').addEventListener('submit', function (event) { // Λειτουργία κουμπιού για nemertes link
			event.preventDefault();

			const link = document.getElementById('nemertesInput').value; // Ανάκτηση input

			var xhr = new XMLHttpRequest(); // Δημιουργία αίτησης
			xhr.open('POST', 'insertNemertes.php', true);
			xhr.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded');

			xhr.onload = function () {
				if (xhr.status !== 200) { // Ανεπιτυχής αίτηση
					alert("Request not completed");
				} else { // Επιτυχής αίτηση
					alert("Link uploaded."); 
				}
			};

			xhr.send('link=' + encodeURIComponent(link)); // Αποστολή δεδομένων
			getNemertesLink(); // Ενημέρωση link πάνω στο label
			header("Location: " . $_SERVER['PHP_SELF']); // Page reload
		});

		document.getElementById('praktikoButton').addEventListener('click', function () { // Λειτουργία κουμπιού πρακτικού
			const xhr = new XMLHttpRequest();
			xhr.open('POST', 'checkPraktiko.php', true);
			xhr.onload = function () {
				if (xhr.status === 200) {
					const response = JSON.parse(xhr.responseText);
					const exists = response['exists'];
					if (exists ===1) {
						alert("Υπάρχει ήδη πρακτικό εξέτασης.");
					} else {
						alert("Το πρακτικό εξέτασης δημιουργήθηκε επιτυχώς.");
						fetchAndProcessFile();
					}
				} else {
					console.error('Failed to communicate with server.');
				}
			};
			xhr.send();
		});

		var id = 0; // Λήψη id διπλωματικής
		const req = new XMLHttpRequest();
		req.open('POST', 'getId.php', true);
		req.onload = function () {
			if (req.status === 200) {
				id = JSON.parse(req.responseText);
			} else {
				console.error('Failed to fetch id from server.');
			}
		}
		req.send();

		document.getElementById('showPraktikoButton').addEventListener('click', function () { // Λειτουργία προβολής πρακτικού
			const xhr = new XMLHttpRequest();
			xhr.open('POST', 'checkPraktiko.php', true);
			xhr.onload = function () {
				if (xhr.status === 200) {
					const response = JSON.parse(xhr.responseText);
					const exists = response['exists'];
					if (exists ===1) {
						const xhr = new XMLHttpRequest();
						xhr.open('POST', 'checkHTMLPraktiko.php', true);
						xhr.onload = function () {
							if (xhr.status === 200) {
								const response = JSON.parse(xhr.responseText);
								const exists = response['exists'];
								if (exists ===1) { 
									window.open('/Project/uploads/praktiko/' + id + '_praktiko_simplified.html', '_blank'); // Άνοιγμα html
								} else {
									excelToHtml(); // create html
									window.open('/Project/uploads/praktiko/' + id + '_praktiko_simplified.html', '_blank'); // Άνοιγμα html
								}
							} else {
								console.error('Failed to search for praktiko server.');
							}
						}
						xhr.send();
					} else {
						alert("Δεν υπάρχει πρακτικό εξέτασης");
					}
				} else {
					console.error('Failed to communicate with server.');
				}
			};
			xhr.send();
		});

	}

</script>


</head>
<body>
   <!-- Navigation bar -->
    <div class="navbar">
        <div class="logo">
            <a href="student.php">
                <img src="/Project/media/logo.png" alt="Logo" class="logo-img" style="height: 40px;">
            </a>
        </div>

        <div class="menu">
            <div>
                <a href="student.php" class="menu-item">Προβολή Θέματος</a>
            </div>
            <div>
                <a href="#" class="menu-item" onclick="statusRedirectProccess()">Επεξεργασία Προφίλ</a>
            </div>
            <div>
                <a href="#" class="menu-item" onclick="statusRedirectManagement()">Διαχείριση Διπλωματικής</a>
            </div>
        </div>

        <div class="user-info">
            <span><?php echo $userEmail; ?></span>
            <form method="POST" action="">
                <button type="submit" name="logout" class="logout-btn">Logout</button>
            </form>
        </div>
    </div>

    <!-- Main content -->
    <div class="content">
        <!-- Left column -->
        <div class="column">
            <div class="box">
				<h2>
					Πληροφορίες Εξέτασης
				</h2>
				<table id="item_table1">
					<!-- Τα δεδομένα εισάγονται εδώ δυναμικά -->
				</table>
				<table id="item_table2">
					<!-- Τα δεδομένα εισάγονται εδώ δυναμικά -->
				</table>
            </div>
			<div class="box">
    			<h2>Προγραμματισμός Εξέτασης</h2>
    
				<!-- Φόρμα για προγραμματισμό εξέτασης -->
				<form id=examForm>
					<!-- Ημερομηνία και ώρα -->
					<label for="dateField" class="form-label">Ημερομηνία και Ώρα</label>
					<input type="text" class="form-input" id="dateField"  placeholder="YYYY-MM-DD HH:MM:SS" required>
					<br><br>
					
					<!-- Δωμάτιο/link -->
					<label for="roomField" class="form-label">Αίθουσα/Link</label>
					<input type="text" class="form-input" id="roomField" placeholder="Φυσική/Ηλεκτρονική Αίθουσα Εξέτασης" required>
					<br><br>
					
					<!-- Κουμπί υποβολής -->
					<button type="submit" class="form-button" id="submitExam">Υποβολή</button>
				</form>
			</div>
        </div>

        <!-- Right column -->
        <div class="column">
			<div class="box">
			<h2>Καταχώρηση Αρχείων Διπλωματικής</h2>
			<h3>Κείμενο Διπλωματικής: <a id="fileLabel" href="#" target="_blank"><!-- Εδώ μπαίνει το όνομα του file δυναμικά --></a></h3>
			<input type="file" id="inputProxeiro" accept=".pdf" class="hidden"> <!-- Input για pdf file με πρόχειο διπλωματικής -->
			<button class="form-button" id="addProxeiro">Ανέβασμα Αρχείου</button> 
			<div id="input-fields-container">
				<h3>Εξωτερικοί Σύνδεσμοι:</h3>
				<form id="linksForm"> <!-- Φόρμα για Links -->
					<div class="input-container">
						<input type="text" class="form-input" placeholder="σύνδεσμος" required>
					</div>
					<button type="button" class="form-button" onclick="addTextField()">Προσθήκη Νέου Συνδέσμου</button>
					<button type="submit" class="form-button">Καταχώρηση</button>
				</form>
			</div>

			<script>
				function addTextField() { // Προσθέτει inputs για extra links
					const newDiv = document.createElement("div");
					newDiv.classList.add("input-container");
					const newInput = document.createElement("input");
					newInput.type = "text";
					newInput.classList.add("form-input");
					newInput.required = true;
					newInput.placeholder = "σύνδεσμος";
					newDiv.appendChild(newInput);
					const form = document.getElementById("linksForm");
					const firstInputContainer = form.querySelector(".input-container");
					form.insertBefore(newDiv, firstInputContainer.nextSibling);
				}
			</script>
		</div>
			<div class="box">
				<h2>Στοιχεία Περατωμένης Διπλωματικής</h2>
				<h3>Σύνδεσμος προς Νημερτή: <a id="nemertesLabel" href="#" target="_blank"><!-- Εδώ μπαίνει το link δυναμικά --></a></h3>
				<form id="nemertesForm">
					<input type="text" id="nemertesInput" class="form-input" placeholder="σύνδεσμος" required>
					<button type="submit" id="nemertesButton" class="form-button">Ανέβασμα Συνδέσμου</button>
				</form>
				<div class="praktiko-container">
					<button id="praktikoButton" class="form-button" disabled>Δημιουργία Πρακτικού Εξέτασης</button>
					<button id="showPraktikoButton" class="form-button" disabled>Προβολή Πρακτικού Εξέτασης</button>
				</div>
				<label id="praktikoLabel">*Για διπλωματικές με προβιβάσιμο βαθμό</label>
			</div>
        </div>
    </div>

	<!-- Footer -->
	<footer>
        <p>&copy; 2024 University of Patras - All Rights Reserved</p>
    </footer>

</body>
</html>
