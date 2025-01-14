DELIMITER $$

CREATE PROCEDURE TestQueryTime()
BEGIN
    DECLARE counter INT DEFAULT 1;
    DECLARE total_duration FLOAT DEFAULT 0;
    DECLARE avg_duration FLOAT;

    -- Enable profiling
    SET profiling = 1;

    -- Loop to execute the query 10 times (you can adjust the number of iterations)
    WHILE counter <= 10 DO
        -- Run the query, but don't display the result
        SELECT 1 FROM anathesi_diplomatikis WHERE status = 'active' LIMIT 1;

        -- Get the duration of the last executed query from profiling
        SELECT DURATION INTO total_duration
        FROM information_schema.PROFILING
        WHERE QUERY_ID = LAST_INSERT_ID();

        -- Accumulate the total duration
        SET total_duration = total_duration + total_duration;

        -- Increment the counter
        SET counter = counter + 1;
    END WHILE;

    -- Calculate the average duration
    SET avg_duration = total_duration / 10;

    -- Output the average time
    SELECT avg_duration AS average_time;

    -- Show profiling results
    SHOW PROFILES;

END$$

DELIMITER ;


CALL TestQueryTime();
