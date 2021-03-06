<?php
/**
 * @file FormCheckbox.template.php
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

<?php $langTemplate='Form';Language::loadLanguageFile('de', $langTemplate, 'json', dirname(__FILE__).'/'); ?>

<?php 
 header('Content-Type: text/html; charset=utf-8');
 ?>
<span class="right">
<?php echo MakeInfoButton('extension/LForm','formCheckbox.md'); ?>
</span>

<input type="hidden" class="input-choice" name="exercises[0][subexercises][0][type]" value="2">
<?php if (isset($form['formId'])){ ?>
<input type="hidden" name="exercises[0][subexercises][0][formId]" value="<?php echo $form['formId']; ?>" />
<?php } ?>
<label class="short label bold" for="task"><?php echo Language::Get('main','task', $langTemplate); ?>:</label>
<!--ckeditor--><textarea name="exercises[0][subexercises][0][task]"
                              class="form-field task-field"
                              rows="5"
                              style="width:100%"
                              maxlength="2500">
<?php echo (isset($form['task']) ? $form['task'] : ''); ?>
</textarea>
 
<?php 
if (isset($form['choices'])){ 
    foreach ($form['choices'] as $choice){
        $checkbox = Template::WithTemplateFile('include/CreateSheet/Form/FormAddCheckbox.template.php');
        if (isset($cid))
            $checkbox->bind(array('cid'=>$cid));
        if (isset($uid))
            $checkbox->bind(array('uid'=>$uid));
        if (isset($sid))
            $checkbox->bind(array('sid'=>$sid));
        $checkbox->bind(array('choice'=>$choice));
        $checkbox->show();
    }
}
?>
 
<a href="javascript:void(0);" class="body-option-color add-choice left"><?php echo Language::Get('main','addChoice', $langTemplate); ?></a>

<br><br>
<label class="short label bold" for="solution"><?php echo Language::Get('main','solution', $langTemplate); ?>:</label>
<!--ckeditor--><textarea name="exercises[0][subexercises][0][solution]"
          class="form-field"
          rows="5"
          style="width:100%"
          maxlength="2500">
<?php echo (isset($form['solution']) ? $form['solution'] : ''); ?>
</textarea>
