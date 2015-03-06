DROP PROCEDURE IF EXISTS `DBCourseGetCourse`;
CREATE PROCEDURE `DBCourseGetCourse` (IN courseid INT)
READS SQL DATA
begin
SET @s = concat("
select SQL_CACHE
    C.C_id,
    C.C_name,
    C.C_semester,
    C.C_defaultGroupSize,
    ES.ES_id
from
    Course C
        left join
    ExerciseSheet ES ON C.C_id = ES.C_id
where
    C.C_id = '",courseid,"';");
PREPARE stmt1 FROM @s;
EXECUTE stmt1;
DEALLOCATE PREPARE stmt1;
end;