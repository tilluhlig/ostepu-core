<?php
/**
 * @file DeleteUserPermanent.sql
 * deletes a specified user from %User table
 * @author  Till Uhlig
 * @param string \$userid a %User identifier or username
 * @result -
 */
?>

DELETE FROM User
WHERE
    U_id like '<?php echo $userid; ?>' or U_username = '<?php echo $userid; ?>' or U_externalId = '<?php echo $userid; ?>'