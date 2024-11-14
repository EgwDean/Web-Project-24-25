# Υλοποιήθηκαν έως τώρα: 


- Foreign Keys όπου έπρεπε και cascading


Ερώτημα 6 Διδάσκων (Υπό Ανάθεση)        <br>                                                                                              <br>
- checkInvitations(IN id_diploma INT)  --> Προβολή προσκλήσεων σε καθηγητές με όρισμα το id της διπλωματικής                            <br><br>
- cancelAssignment(IN professor_email VARCHAR(255), IN id_diploma INT) --> Ακύρωση Ανάθεσης Διπλωματικής MONO από τον Επιβλέποντα Καθηγητή και Ενημέρωση των πινάκων trimelis_epitropi_diplomatikis και 
  prosklisi_se_trimeli. Oρίσματα το email του Καθηγητή που κάνει την ακύρωση και το id της Διπλωματικής που ακυρώνεται η ανάθεσή της.  <br> <br>


Ερώτημα 6 Διδάσκων (Ενεργή)
- createNotes(IN professor_email VARCHAR(255), IN id_diploma INT, IN notes TEXT)
- cancelAssignmentActive(IN professor_email VARCHAR(255), IN id_diploma INT)
- setUnderExam(IN professor_email VARCHAR(255), IN id_diploma INT)
