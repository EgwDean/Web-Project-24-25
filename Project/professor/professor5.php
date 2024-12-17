<?php
session_start();

// Έλεγχος αν ο χρήστης είναι συνδεδεμένος
if (!isset($_SESSION['email'])) {
    header("Location: logout.php");
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
        /* Στυλ για τη σελίδα */
       body {
            font-family: Calibri, Arial, sans-serif;
            margin: 0;
            background-color: #f4f4f4;
            display: flex;
            flex-direction: column;
        }


        /* Στυλ για το navigation bar */
.navbar {
    background-color: #333;
    color: #fff;
    padding: 15px;
    display: flex;
    justify-content: space-between; /* Χωρίζει τα sections από το user info */
    align-items: center;
}

.navbar .menu {
    display: flex;
    gap: 20px;
    justify-content: center; /* Κέντρο μεταξύ τους */
    flex: 1; /* Παίρνει όλο τον διαθέσιμο χώρο */
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

        /* Στυλ για τα υπομενού */
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

	h1 {
            text-align: center; /* Κεντράρει το κείμενο */
		padding-top: 50px
        }

        /* Στυλ για τα στοιχεία του χρήστη */
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


        /* Στυλ για τα διαγράμματα */
        .chart-container {
            display: flex;
            justify-content: space-between;
            gap: 20px;
            flex-wrap: wrap;
            margin: 20px;
    	    padding: 150px 200px; /* Προσθέτει padding αριστερά και δεξιά γύρω από τα διαγράμματα */
        }

        canvas {
            width: 25% !important; /* Κάνει τα διαγράμματα να είναι μεγαλύτερα */
            height: 300px !important; /* Ρυθμίζει το ύψος των διαγραμμάτων */
        }
		
		
		.logo {
			margin-right: 20px;
		}

		.logo-img {
			height: 40px; /* Adjust size as needed */
			width: auto;
		}
    </style>

    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

    <script>
        function get() {
            var xhr = new XMLHttpRequest();
            xhr.open('GET', 'stats.php');
            xhr.send();

            xhr.onreadystatechange = function() {
                if (xhr.readyState === XMLHttpRequest.DONE) {
                    if (xhr.status === 200) {
                        var data = JSON.parse(xhr.responseText)['items'];

                    var chartData1 = data.slice(0, 2).map(item => item.stats);
                    var chartData2 = data.slice(2, 4).map(item => item.stats);
                    var chartData3 = data.slice(4, 6).map(item => item.stats);

                    // Δημιουργία των διαγραμμάτων
                    createChart1('chart1', chartData1);
                    createChart2('chart2', chartData2);
                    createChart3('chart3', chartData3);
                } else {
                    console.error("Error fetching data");
                }
            }
        }
    }

// Δημιουργία του πρώτου διαγράμματος
function createChart1(chartId, chartData) {
    var ctx = document.getElementById(chartId).getContext('2d');
    new Chart(ctx, {
        type: 'bar',
        data: {
            labels: ['Ως επιβλέπων', 'Ως μέλος τριμελούς'], // Προσαρμοσμένα labels
            datasets: [{
                label: 'Μέσος χρόνος περάτωσης διπλωματικών (μήνες)',
                data: chartData,
                backgroundColor: ['rgba(54, 162, 235, 0.2)', 'rgba(255, 99, 132, 0.2)'],
                borderColor: ['rgba(54, 162, 235, 1)', 'rgba(255, 99, 132, 1)'],
                borderWidth: 1
            }]
        },
        options: {
            responsive: true,
            scales: {
                x: {
                    ticks: {
                        font: {
                            size: 22 // Αλλαγή μεγέθους γραμματοσειράς στα labels του άξονα X
                        }
                    }
                },
                y: {
                    beginAtZero: true,
                    ticks: {
                        font: {
                            size: 14 // Αλλαγή μεγέθους γραμματοσειράς στα labels του άξονα Y
                        }
                    }
                }
            },
            plugins: {
                legend: {
                    labels: {
                        font: {
                            size: 20 // Αλλαγή μεγέθους γραμματοσειράς στο legend
                        }
                    }
                }
            }
        }
    });
}

// Δημιουργία του δεύτερου διαγράμματος
function createChart2(chartId, chartData) {
    var ctx = document.getElementById(chartId).getContext('2d');
    new Chart(ctx, {
        type: 'bar',
        data: {
            labels: ['Ως επιβλέπων', 'Ως μέλος τριμελούς'], // Προσαρμοσμένα labels
            datasets: [{
                label: 'Μέσος Βαθμός Διπλωματικών',
                data: chartData,
                backgroundColor: ['rgba(75, 192, 192, 0.2)', 'rgba(153, 102, 255, 0.2)'],
                borderColor: ['rgba(75, 192, 192, 1)', 'rgba(153, 102, 255, 1)'],
                borderWidth: 1
            }]
        },
        options: {
            responsive: true,
            scales: {
                x: {
                    ticks: {
                        font: {
                            size: 22 // Αλλαγή μεγέθους γραμματοσειράς στα labels του άξονα X
                        }
                    }
                },
                y: {
                    beginAtZero: true,
                    ticks: {
                        font: {
                            size: 14 // Αλλαγή μεγέθους γραμματοσειράς στα labels του άξονα Y
                        }
                    }
                }
            },
            plugins: {
                legend: {
                    labels: {
                        font: {
                            size: 20 // Αλλαγή μεγέθους γραμματοσειράς στο legend
                        }
                    }
                }
            }
        }
    });
}

// Δημιουργία του τρίτου διαγράμματος
function createChart3(chartId, chartData) {
    var ctx = document.getElementById(chartId).getContext('2d');
    new Chart(ctx, {
        type: 'bar',
        data: {
            labels: ['Ως επιβλέπων', 'Ως μέλος τριμελούς'], // Προσαρμοσμένα labels
            datasets: [{
                label: 'Συνολικό Πλήθος Διπλωματικών',
                data: chartData,
                backgroundColor: ['rgba(255, 159, 64, 0.2)', 'rgba(255, 205, 86, 0.2)'],
                borderColor: ['rgba(255, 159, 64, 1)', 'rgba(255, 205, 86, 1)'],
                borderWidth: 1
            }]
        },
        options: {
            responsive: true,
            scales: {
                x: {
                    ticks: {
                        font: {
                            size: 22 // Αλλαγή μεγέθους γραμματοσειράς στα labels του άξονα X
                        }
                    }
                },
                y: {
                    beginAtZero: true,
                    ticks: {
                        font: {
                            size: 14 // Αλλαγή μεγέθους γραμματοσειράς στα labels του άξονα Y
                        }
                    }
                }
            },
            plugins: {
                legend: {
                    labels: {
                        font: {
                            size: 20 // Αλλαγή μεγέθους γραμματοσειράς στο legend
                        }
                    }
                }
            }
        }
    });
}
</script>



</head>
<body onload="get()">

    <!-- Navigation bar -->
    <div class="navbar">
	
		<div class="logo">
			<img src="media/ceid_logo.png" alt="Logo" class="logo-img">
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
            <span><?php echo htmlspecialchars($userEmail);?></span>
            <form action="" method="post" style="display:inline;">
                <button type="submit" name="logout" class="logout-btn">Logout</button>
            </form>
        </div>
    </div>

	<h1>Στατιστικά</h1>


    <!-- Διαγράμματα δίπλα δίπλα -->
    <div class="chart-container">
        <!-- Διάγραμμα 1 -->
        <canvas id="chart1"></canvas>

        <!-- Διάγραμμα 2 -->
        <canvas id="chart2"></canvas>

        <!-- Διάγραμμα 3 -->
        <canvas id="chart3"></canvas>
    </div>

</body>
</html>
