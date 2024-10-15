USE html;

-- DROP PROCEDURE login;
-- Check if login credentials are correct
CALL login('admin1', 'password123', @x);
CALL login('emily_white', 'zxcvb321', @x);

SELECT @x;
--

-- DROP PROCEDURE name_taken;
 # Check if a username is taken
CALL name_taken('john_doe', @yes_no);
SELECT @yes_no;

-- DROP PROCEDURE email_taken;
-- Check if an email is already registered
CALL email_taken('john.doe@example.com', @yes_no);
SELECT @yes_no;
