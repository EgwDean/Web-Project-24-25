<?php
session_start();


// Check if the user is logged in by verifying the session variable
if (empty($_SESSION['email'])) {
    // Redirect to the login page if the user is not logged in
    header("Location: login.php");
    exit();
}



// Database credentials
$host = "localhost";
$dbusername = "root";
$dbpassword = "556782340";
$dbname = "diplomatiki_support";
$dateDiff = null;					// variable for the diplomatiki time active

// Database connection
$conn = new mysqli($host, $dbusername, $dbpassword, $dbname);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Check if this is an AJAX request
if (isset($_GET['ajax']) && $_GET['ajax'] === '1') {
    $email = isset($_SESSION['email']) ? $_SESSION['email'] : null;



	// Get the date difference (time active)
    $sqlTime = "CALL get_date_diff(?)";
    $stmTime = $conn->prepare($sqlTime);
    $stmTime->bind_param("s", $email);
    $stmTime->execute();
    $resultTime = $stmTime->get_result();

    // Fetch the date result (since it's a single value)
    if ($rowTime = $resultTime->fetch_assoc()) {
        $dateDiff = $rowTime['dateDiff'];
    }
    $stmTime->close();




    // Define the SELECT query for the first table (Theses)
    $sql = "SELECT diplomatiki.title, diplomatiki.description, diplomatiki.pdf_link_topic, anathesi_diplomatikis.status
            FROM diplomatiki 
            INNER JOIN anathesi_diplomatikis 
            ON diplomatiki.id_diplomatiki = anathesi_diplomatikis.id_diploma
            WHERE anathesi_diplomatikis.email_stud = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("s", $email);
    $stmt->execute();
    $result = $stmt->get_result();

    // Prepare the data for response
    $data = ['theses' => []];
    while ($row = $result->fetch_assoc()) {
        $row['timeactive'] = $dateDiff;
        $data['theses'][] = $row;
    }
    $stmt->close();

    // Fetch ID for the second query
    $sql1 = "SELECT id_diploma FROM anathesi_diplomatikis WHERE email_stud = ?";
    $stmt1 = $conn->prepare($sql1);
    $stmt1->bind_param("s", $email);
    $stmt1->execute();
    $stmt1->bind_result($id);
    $stmt1->fetch();
    $stmt1->close();

    // Execute the second query (Committee)
    $sql2 = "SELECT supervisor, member1, member2 
             FROM committee 
             WHERE id_dipl = ?";
    $stmt2 = $conn->prepare($sql2);
    $stmt2->bind_param("i", $id);
    $stmt2->execute();
    $result2 = $stmt2->get_result();

    $data['committee'] = [];
    while ($row2 = $result2->fetch_assoc()) {
        $data['committee'][] = $row2;
    }
    $stmt2->close();

    // Return the data as JSON
    echo json_encode($data);
    $conn->close();
    exit;
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>View Thesis</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #f4f7f6;
            color: #333;
            margin: 0;
            padding: 0;
        }

        header {
            background-color: #192f59;
            color: white;
            padding: 20px 0;
            text-align: center;
        }

        .table-container {
            margin: 20px auto;
            padding: 20px;
            max-width: 1000px;
            background-color: white;
            border-radius: 8px;
            box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
        }

        table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 20px;
        }

        th, td {
            padding: 12px;
            text-align: left;
            border: 1px solid #ddd;
        }

        th {
            background-color: #192f59;
            color: white;
            font-size: 16px;
        }

        tr:hover {
            background-color: #f1f1f1;
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
    <script>
        function fetchTheses() {
            fetch('?ajax=1')
                .then(response => response.json())
                .then(data => {
                    const tableContainer = document.querySelector('.table-container');
                    let tableHTML = '';

                    // First table: Theses
                    if (data.theses.length > 0) {
                        tableHTML += `
                            <table>
                                <tr>
                                    <th>Title</th>
                                    <th>Description</th>
                                    <th>PDF Link</th>
                                    <th>Status</th>
									<th>Time active</th>
                                </tr>
                        `;
                        data.theses.forEach(row => {
                            tableHTML += `
                                <tr>
                                    <td>${row.title}</td>
                                    <td>${row.description}</td>
                                    <td><a href="${row.pdf_link_topic}" target="_blank">Download PDF</a></td>
                                    <td>${row.status}</td>
									<td>${row.timeactive} days</td> 
                                </tr>
                            `;
                        });
                        tableHTML += '</table>';
                    } else {
                        tableHTML += '<p>No theses found.</p>';
                    }

                    // Second table: Committee
                    if (data.committee.length > 0) {
                        tableHTML += `
                            <table>
                                <tr>
                                    <th>Supervisor</th>
                                    <th>Member 1</th>
                                    <th>Member 2</th>
                                </tr>
                        `;
                        data.committee.forEach(row => {
                            tableHTML += `
                                <tr>
                                    <td>${row.supervisor}</td>
                                    <td>${row.member1}</td>
                                    <td>${row.member2}</td>
                                </tr>
                            `;
                        });
                        tableHTML += '</table>';
                    } else {
                        tableHTML += '<p>No committee data found.</p>';
                    }

                    // Update the container
                    tableContainer.innerHTML = tableHTML;
                })
                .catch(error => {
                    console.error('Error fetching data:', error);
                });
        }

        // Fetch data when the page loads
        document.addEventListener('DOMContentLoaded', fetchTheses);
    </script>
</head>
<body>
<header>
    <h1>Προβολή Διπλωματικής</h1>
</header>

<div class="table-container">
    <!-- Table will be dynamically loaded here -->
</div>

<footer>
    <p>&copy; 2024 University of Patras - All Rights Reserved</p>
</footer>
</body>
</html>
