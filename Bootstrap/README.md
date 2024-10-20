# Download

Κατεβάζουμε το Bootstrap από την ιστοσελίδα:   https://getbootstrap.com/    <br>

1.)  Κάνουμε κλικ στην αρχή της σελίδας εκεί που λέει Download. <br>
2.)  Ανακατευθυνόμαστε σε μια άλλη σελίδα και πατάμε Download ξανά στην ενότητα: "Compiled CSS and JS"  <br>
3.)  Θα κατέβει ένα αρχείο zip. Το κάνουμε unzip και βλέπουμε δύο φακέλους css και js. Μετακινούμε αυτούς τους δύο φακέλους στο ίδιο directory με τα υπόλοιπα αρχεία 
που υποστηρίζουν την ιστοσελίδα μας (σε εμένα: XAMMP -> htdocs -> babis_login/ ).      <br>
4.)  Τέλος, πάμε στο αρχείο login.php και κάνουμε link τα αρχεία των φακέλων css και js στο &lt;head&gt; tag και ακριβώς πριν το τέλος του &lt;body&gt; tag αντίστοιχα.    <br>

<br><br>
Note: Αντί να κάνω link στο body tag "js/bootstrap.js" έκανα link "js/bootstrap.bundle.js" το οποίο περιέχει όλα τα dependencies (τα οποία μπορεί να τα χρειαστώ στη συνέχεια). <br>


# Grid System

https://getbootstrap.com/docs/5.3/layout/breakpoints/
