<?php
/**
 * @file EditGateAuth.sql
 * updates a specified auth method from %GateAuth table
 * @license http://www.gnu.org/licenses/gpl-3.0.html GPL version 3
 *
 * @package OSTEPU (https://github.com/ostepu/system)
 * @since 0.6.0
 *
 * @author Till Uhlig <till.uhlig@student.uni-halle.de>
 * @date 2016
 *
 * @param string \$gaid a %GateAuth identifier
 * @param string \<?php echo $values; ?> the input data, e.g. 'a=1, b=2'
 * @result -
 */
?>

<?php $profile = '';
    if (isset($profileName) && trim($profileName) !== ''){
        $profile = '_'.$profileName;
    }?>

UPDATE `GateAuth<?php echo $profile;?>`
SET <?php echo $values; ?>
WHERE GA_id = '<?php echo $gaid; ?>'