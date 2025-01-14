DELIMITER $$

CREATE PROCEDURE TestQueryTime()
BEGIN
    DECLARE counter INT DEFAULT 1;
    DECLARE iteration_duration FLOAT;

    -- Create a table to store the iteration and time (if not exists)
    CREATE TABLE IF NOT EXISTS query_times (
        iteration INT,
        time_taken FLOAT
    );
    

    -- Enable profiling
    SET profiling = 1;

    -- Loop to execute the query 10 times (you can adjust the number of iterations)
    WHILE counter <= 10 DO
        -- Run the query
        CALL recall_thesis(2, 'eleni.papadaki@student.edu', 'maria.ioannou@university.edu', 1004, 2025, @output);

        -- Fetch the last executed query's profiling duration
        SELECT DURATION INTO iteration_duration
        FROM information_schema.PROFILING
        WHERE QUERY_ID = (SELECT QUERY_ID FROM information_schema.PROFILING ORDER BY QUERY_ID DESC LIMIT 1)
        LIMIT 1;

        
        -- Insert the iteration number and the corresponding duration into the table
        INSERT INTO query_times (iteration, time_taken) VALUES (counter, iteration_duration);

        -- Increment the counter
        SET counter = counter + 1;
    END WHILE;

    -- Calculate the average duration
    SELECT AVG(time_taken) AS average_time FROM query_times;

    -- Show profiling results
    SHOW PROFILES;
    

END$$

DELIMITER ;


CALL TestQueryTime();
SELECT * FROM query_times;
DELETE FROM query_times;
