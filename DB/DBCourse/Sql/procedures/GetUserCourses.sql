<?php
/**
 * @file GetUserCourses.sql
 *
 * @license http://www.gnu.org/licenses/gpl-3.0.html GPL version 3
 *
 * @package OSTEPU (https://github.com/ostepu/system)
 * @since 0.3.0
 *
 * @author Till Uhlig <till.uhlig@student.uni-halle.de>
 * @date 2015
 */
?>

DROP PROCEDURE IF EXISTS `DBCourseGetUserCourses`;
CREATE PROCEDURE `DBCourseGetUserCourses` (IN profile varchar(30), IN exerciseSheetProfile varchar(30), IN userid INT)
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
    (`Course",profile,"` C
    NATURAL JOIN `CourseStatus`)
        left join
    `ExerciseSheet",exerciseSheetProfile,"` ES ON C.C_id = ES.C_id
where
    U_id = '",userid,"';");
PREPARE stmt1 FROM @s;
EXECUTE stmt1;
DEALLOCATE PREPARE stmt1;
end;