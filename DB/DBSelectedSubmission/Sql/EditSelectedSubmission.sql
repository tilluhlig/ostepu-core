<?php
/**
 * @file EditSelectedSubmission.sql
 * updates a specified selected submission row from %SelectedSubmission table
 * @author Till Uhlig <till.uhlig@student.uni-halle.de>
 * @date 2014-2015
 * @param int \$userid a %User identifier
 * @param int \$eid a %Exercise identifier
 * @param string \<?php echo $values; ?> the input data, e.g. 'a=1, b=2'
 * @result -
 */
?>

UPDATE SelectedSubmission
SET <?php echo $values; ?>
WHERE U_id_leader = '<?php echo $userid; ?>' and E_id = '<?php echo $eid; ?>'