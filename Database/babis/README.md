# Υλοποιήθηκαν έως τώρα: 
<br> 
Foreign Keys όπου έπρεπε και cascading
<br> <br> 
<br> <br> 

Ερώτημα 6 Διδάσκων (Υπό Ανάθεση)                                                                                                    
- checkInvitations(IN id_diploma INT)  --> Προβολή προσκλήσεων σε καθηγητές με όρισμα το id της διπλωματικής                            
- cancelAssignment(IN professor_email VARCHAR(255), IN id_diploma INT) --> Ακύρωση Ανάθεσης Διπλωματικής MONO από τον Επιβλέποντα Καθηγητή και Ενημέρωση των πινάκων trimelis_epitropi_diplomatikis και 
  prosklisi_se_trimeli. Oρίσματα το email του Καθηγητή που κάνει την ακύρωση και το id της Διπλωματικής που ακυρώνεται η ανάθεσή της.  

<br> <br> 
Ερώτημα 6 Διδάσκων (Ενεργή)
- createNotes(IN professor_email VARCHAR(255), IN id_diploma INT, IN notes TEXT)
- cancelAssignmentActive(IN professor_email VARCHAR(255), IN id_diploma INT)
- setUnderExam(IN professor_email VARCHAR(255), IN id_diploma INT)


<br> <br> 
Ερώτημα 6 Διδάσκων (Υπό Εξέταση)
- Ερώτημα α)         -->  seeDiploma(IN id_diploma INT)
- Ερώτημα β)         -->  ΔΕΝ ΑΠΑΝΤΗΘΗΚΕ
- Ερώτημα γ) και δ)  -->  gradeSubmit(IN professor_email VARCHAR(255), IN id_diploma INT, IN grade DECIMAL(3, 2))
                          seeGrades(IN id_diploma INT)
                          trigger_check_grade_update
