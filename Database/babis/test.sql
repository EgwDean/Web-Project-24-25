USE diplomatiki_support;

# Ερώτημα 6 (Υπό Ανάθεση) --> Προβολή Προσκεκλημένων μελών/καθηγητών, status, reply_date
# Το reply_date πρέπει να ενημερώνεται κάθε φορά που κπ καθηγητής αποδέχεται ή απορρίπτει, αλλιώς είναι NULL
# Η κατάλληλη ενημέρωση του reply_date είναι δουλειά του Θεόφραστου
CALL checkInvitations(1);   # προβολή  των προσκλήσεων για τη διπλωματική με Id 1

# Ερώτημα 6 (Υπό Ανάθεση) --> Ακύρωση Ανάθεσης Διπλωματικής από Supervisor Καθηγητή
SELECT * FROM diplomatiki_support.anathesi_diplomatikis;
CALL cancelAssignment('dimitris.papa@university.edu',1);

-- Uncomment to check the result after the cancelation
#SELECT * FROM diplomatiki_support.anathesi_diplomatikis;
#SELECT * FROM diplomatiki_support.prosklisi_se_trimeli;
#SELECT * FROM diplomatiki_support.trimelis_epitropi_diplomatikis;



# Ερώτημα 6 (Ενεργή) --> ...