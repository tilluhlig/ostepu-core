<?php
/**
 * @file AddExercise.sql
 * inserts an exercise into %Exercise table
 * @author  Till Uhlig
 * @param string \$values the input data, e.g. 'a=1, b=2'
 * @result -
 */
?>

INSERT INTO Exercise SET <?php echo $values; ?>