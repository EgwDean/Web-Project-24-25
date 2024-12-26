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
	<link rel="stylesheet" type="text/css" href="professor5.css">
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
            <span><?php echo htmlspecialchars($userEmail);?></span>
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

	<footer>
		<p>&copy; 2024 University of Patras - All Rights Reserved</p>
	</footer>
	
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
                        callback: function(value) {
                            return Number.isInteger(value) ? value : ''; // Εμφάνιση μόνο ακέραιων τιμών
                        },
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

</body>
</html>
