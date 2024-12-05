<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Professor Dashboard</title>

    <script>
        function get() {
            var xhr = new XMLHttpRequest();
            xhr.open('GET', 'get_praktiko.php'); // Κλήση του get_praktiko.php
            xhr.send();

            xhr.onreadystatechange = function() {
                if (xhr.readyState === XMLHttpRequest.DONE) {
                    if (xhr.status === 200) {
                        var data = JSON.parse(xhr.responseText)['items']; // Απόκριση από το PHP αρχείο

                        // Αν υπάρχει δεδομένο (μια γραμμή)
                        if (data.length > 0) {
                            // Αποθήκευση των τιμών σε μεταβλητές
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
                            var number = data[0]['protocol_number'];

                            // Εκτύπωση των μεταβλητών στην κονσόλα
                            console.log("Diploma ID: " + diploma_id);
                            console.log("Supervisor: " + supervisor);
                            console.log("Member 1: " + member1);
                            console.log("Member 2: " + member2);
                            console.log("Student Name: " + student_name);
                            console.log("Examination Date: " + examination_date);
                            console.log("Examination Room: " + examination_room);
                            console.log("Grade 1: " + grade1);
                            console.log("Grade 2: " + grade2);
                            console.log("Grade 3: " + grade3);
                            console.log("Final Grade: " + final_grade);
                            console.log("Diploma Title: " + diploma_title);

                            // Αν θέλεις να τα εμφανίσεις στη σελίδα, μπορείς να τα βάλεις μέσα σε ένα στοιχείο:
                            var output = `
                                <p><strong>Diploma ID:</strong> ${diploma_id}</p>
                                <p><strong>Supervisor:</strong> ${supervisor}</p>
                                <p><strong>Member 1:</strong> ${member1}</p>
                                <p><strong>Member 2:</strong> ${member2}</p>
                                <p><strong>Student Name:</strong> ${student_name}</p>
                                <p><strong>AM:</strong> ${am}</p>
                                <p><strong>Examination Date:</strong> ${examination_date}</p>
                                <p><strong>Examination Room:</strong> ${examination_room}</p>
                                <p><strong>Grade 1:</strong> ${grade1}</p>
                                <p><strong>Grade 2:</strong> ${grade2}</p>
                                <p><strong>Grade 3:</strong> ${grade3}</p>
                                <p><strong>Final Grade:</strong> ${final_grade}</p>
                                <p><strong>Diploma Title:</strong> ${diploma_title}</p>
                                <p><strong>Number:</strong> ${number}</p>
                            `;

                            // Εμφάνιση των αποτελεσμάτων στην ιστοσελίδα
                            document.getElementById('output').innerHTML = output;
                        }
                    } else {
                        console.error("Error fetching data");
                    }
                }
            }
        }
    </script>

</head>
<body onload="get()">

    <!-- Αυτό το στοιχείο θα εμφανίσει τα αποτελέσματα -->
    <div id="output"></div>

</body>
</html>
