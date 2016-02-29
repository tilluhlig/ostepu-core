<?php
/**
 * @file GetMemberRights.sql
 *
 * @author Till Uhlig <till.uhlig@student.uni-halle.de>
 * @date 2015
 */
?>

DROP PROCEDURE IF EXISTS `DBCourseStatusGetMemberRights`;
CREATE PROCEDURE `DBCourseStatusGetMemberRights` (IN userid INT)
READS SQL DATA
begin
SET @s = concat("
select SQL_CACHE
    U.U_id,
    U.U_username,
    U.U_firstName,
    U.U_lastName,
    U.U_email,
    U.U_title,
    U.U_flag,
    U.U_lang,
    U.U_studentNumber,
    U.U_isSuperAdmin,
    U.U_comment,
    CS.CS_status,
    C.C_id,
    C.C_name,
    C.C_semester,
    C.C_defaultGroupSize
from

    CourseStatus CS
        join
    Course C ON (CS.C_id = C.C_id)
 join
    User U
       ON (U.U_id = CS.U_id)
WHERE
    CS.U_id = '",userid,"';");
PREPARE stmt1 FROM @s;
EXECUTE stmt1;
DEALLOCATE PREPARE stmt1;
end;