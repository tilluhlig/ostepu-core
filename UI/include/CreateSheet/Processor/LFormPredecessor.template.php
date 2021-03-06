<?php
/**
 * @file LFormPredecessor.template.php
 *
 * @license http://www.gnu.org/licenses/gpl-3.0.html GPL version 3
 *
 * @package OSTEPU (https://github.com/ostepu/ostepu-core)
 * @since 0.1.1
 *
 * @author Till Uhlig <till.uhlig@student.uni-halle.de>
 * @date 2014-2015
 */
?>

<?php include_once dirname(__FILE__) . '/../../../../Assistants/Language.php'; ?>
<?php include_once dirname(__FILE__) . '/../../Boilerplate.php'; ?>
<?php $langTemplate='Processor_LFormPredecessor';Language::loadLanguageFile('de', $langTemplate, 'json', dirname(__FILE__).'/'); ?>

<?php
 header('Content-Type: text/html; charset=utf-8');
 ?>
<div class="content-element ProcessorParameterArea" style="outline:2px solid #b9b8b8;border-radius: 0px;margin: 0px;">
    <?php echo MakeInfoButton('extension/LFormPredecessor','LFormPredecessor.md'); ?>
    <div class="content-body-wrapper">
        <div class="content-body left">
            <table border="0" style="width:100%">          
                <?php
                $liste = array(
                                'isprintable' => Language::Get('main','isprintable', $langTemplate),
                                'isalpha' => Language::Get('main','isalpha', $langTemplate),
                                'isalphanum' => Language::Get('main','isalphanum', $langTemplate),
                                'isnumeric' => Language::Get('main','isnumeric', $langTemplate),
                                'ishex' => Language::Get('main','ishex', $langTemplate),
                                'isdigit' => Language::Get('main','isdigit', $langTemplate));
                
                $i=0;
                $params = array();
                if (isset($process['parameter']))
                    $params = explode(' ',$process['parameter']);
                foreach ($liste as $key => $value) {
                ?>
                
                <?php if ($i%3==0){ ?>
                <tr>
                <?php } ?>
                    <td>
                        <input type="checkbox" class="parameter-choice" name="exercises[0][subexercises][0][processorParameterList][0][]" value="<?php echo $key; ?>"<?php echo (in_array($key, $params) ? ' checked':'');?>/>
                        <?php if (in_array($key, $params))unset($params[array_search($key, $params)]); ?>
                    </td>
                    <td>
                        <label class="label bold"><?php echo $value; ?></label>
                    </td>
                <?php if ($i%3==2){ ?>
                </tr> 
                <?php } ?>
                
                <?php
                    $i++;
                } 
                ?>
                
                <tr>
                    <td colspan="6">
                        <label class="label bold"><?php echo Language::Get('main','regularExpression', $langTemplate); ?>: </label>
                        <input type="text" class="parameter-choice" name="exercises[0][subexercises][0][processorParameterList][0][]" value="<?php echo implode(' ',$params); ?>" placeholder="%^([0-9]{5})$%" />
                    </td>
                </tr>
            </table>
        </div>
    </div>
</div>