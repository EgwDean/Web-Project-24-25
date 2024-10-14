# What does <i>box-sizing: border-box;</i>  do?
<br>
When you set box-sizing to border-box, the following applies:
The width and height properties include padding and border, not just the content area. 
This means that if you set an element’s width to 200 pixels and add 20 pixels of padding and a 5-pixel border, 
the actual content area will adjust to fit within the specified width of 200 pixels instead of less.


---------------------------------------------------------------------------------------------------------------------------------------------------
# Explanation about <i>form</i> directive and how it works

&lt <form action="login.php" method="post">  &gt

- The <form> tag encapsulates input fields and buttons that allow users to submit data.
- The action attribute specifies the URL (in this case, login.php) to which the form data will 
   be sent for processing when the form is submitted.
- The method attribute specifies the HTTP method used for sending the data. 
   Here, it is set to post, meaning the data will be sent in the request body (rather than the URL).


<input type="text" id="username" name="username" required>
<input type="password" id="password" name="password" required>

- Each input field has a name attribute (username and password) that identifies the data being sent. 
  This is crucial because the server uses these names to access the values sent from the form.
- The required attribute ensures that the user must fill in these fields before submission. 
   If either field is empty, the browser will prevent the form from being submitted and prompt the user 
   to complete the fields.


<input type="submit" value="Login">

- This button, when clicked, triggers the form submission process. 
- The type="submit" indicates that this button is meant to submit the form data.

When the "Login" button is pressed, the browser collects the data from all input 
fields within the <form> and sends it to the server specified in the action attribute.





