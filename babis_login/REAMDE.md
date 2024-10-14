<br><br>
# Complete Flow:
<br>
1.)   User submits the form: The form data is sent to insert.php via POST.   <br>
2.)   PHP checks the request: It verifies if the request method is POST.    <br>
3.)   Database connection: The script attempts to connect to the database.    <br>
4.)   Data retrieval: It retrieves the submitted form data.                      <br>
5.)   Prepare and execute SQL: The script prepares an SQL insert statement and executes it with the retrieved data.    <br>
6.)   Success or error: If successful, a success message is shown; if not, an error message is displayed.       <br><br>



# What does <i>box-sizing: border-box;</i>  do?
<br>
When you set box-sizing to border-box, the following applies:
The width and height properties include padding and border, not just the content area. 
This means that if you set an element’s width to 200 pixels and add 20 pixels of padding and a 5-pixel border, 
the actual content area will adjust to fit within the specified width of 200 pixels instead of less.


---------------------------------------------------------------------------------------------------------------------------------------------------
# Explanation about <i>form</i> directive and how it works   <br><br>

# &lt;form action="login.php" method="post"&gt;

- The <form> tag encapsulates input fields and buttons that allow users to submit data.
- The action attribute specifies the URL (in this case, login.php) to which the form data will 
   be sent for processing when the form is submitted.
- The method attribute specifies the HTTP method used for sending the data. 
   Here, it is set to post, meaning the data will be sent in the request body (rather than the URL).  
<br>
<br>

# &lt;input type="text" id="username" name="username" required&gt;
# &lt;input type="password" id="password" name="password" required&gt;

- Each input field has a name attribute (username and password) that identifies the data being sent. 
  This is crucial because the server uses these names to access the values sent from the form.
- The required attribute ensures that the user must fill in these fields before submission. 
   If either field is empty, the browser will prevent the form from being submitted and prompt the user 
   to complete the fields.

<br>
<br>   

# &lt;input type="submit" value="Login"&gt;

- This button, when clicked, triggers the form submission process. 
- The type="submit" indicates that this button is meant to submit the form data.

# When the "Login" button is pressed, the browser collects the data from all input 
# fields within the <form> and sends it to the server specified in the action attribute.

-----------------------------------------------------------------------------------------------------------------------
<br><br>
# Σημαντική Επισήμανση:
<br>

- Keeping different functionalities in separate files helps maintain a clean code structure. login.html handles user login, while insert.php is focused on user registration.
This makes the code easier to read, maintain, and debug.

- If there are changes needed for the login process, they can be made in login.html without affecting the registration logic in insert.php, and vice versa.

-----------------------------------------------------------------------------------------------------------------------
