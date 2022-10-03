<?php

use Joomla\CMS\Factory;
use Joomla\CMS\HTML\HTMLHelper;
use Joomla\CMS\Language\Text;
use Joomla\CMS\Layout\LayoutHelper;
use Joomla\CMS\Router\Route;

$filedsetDetailMeet = array('meeting_time', 'venue', 'presented_by');

defined('_JEXEC') or die;

$wa = $this->document->getWebAssetManager();
$wa->useScript('keepalive');
$wa->useScript('form.validate');

$app = Factory::getApplication();
$input = $app->input;

?>
<form method="POST"
	action="<?php echo Route::_('index.php?option=com_notula&layout=edit&id='. (int) $this->item->id); ?>"
	name="adminForm" id="item-form" class="form-validate">
	<?php echo LayoutHelper::render('joomla.edit.title_alias', $this); ?>
	<div class="main-card">
		<?php echo HTMLHelper::_('uitab.startTabSet', 'MyTab', ['active'=>'details', 'recall'=>true, 'breakpoint'=>768]); ?>
		<?php echo HTMLHelper::_('uitab.addTab', 'MyTab', 'details', Text::_('COM_NOTULA_MEETING_DETAILS')); ?>
			<div class="row">
				<div class="col-lg-6">					
					<fieldset id="fieldset-meeting-details" class="options-form">
						<legend><?php echo Text::_($this->form->getFieldsets()['meeting_details']->label); ?></legend>
						<div>
							<?php echo $this->form->renderFieldset('meeting_details'); ?>
						</div>
					</fieldset>
				</div>
				<div class="col-lg-6">
				<fieldset id="fieldset-meeting-state" class="options-form">
						<legend><?php echo Text::_($this->form->getFieldsets()['meeting_state']->label); ?></legend>
						<div>
							<?php echo $this->form->renderFieldset('meeting_state'); ?>
						</div>
					</fieldset>
				</div>
			</div>
			<?php echo HTMLHelper::_('uitab.endTab'); ?>		
		<?php echo HTMLHelper::_('uitab.addTab', 'MyTab', 'notula', Text::_('COM_NOTULA_NOTULA_CONTENT')); ?>
				<div class="row">
					<div class="col-lg-12">
						<div>
							<fieldset class="adminform">
								<?php echo $this->form->getLabel('notulen'); ?>
								<?php echo $this->form->getInput('notulen'); ?>
							</fieldset>
						</div>
					</div>
					
				</div>
			<?php echo HTMLHelper::_('uitab.endTab'); ?>
			
			<?php echo HTMLHelper::_('uitab.addTab', 'MyTab', 'participant', Text::_('COM_NOTULA_MEETING_PARTICIPANT')); ?>
				<table class="table itemList" id="participantTable">
					<thead>
						<tr>
							<td class="w-1 text-center">#</td>
							<th scope="col" class="text-center">
								<?php echo Text::_('JFIELD_NAME_LABEL'); ?>
							</th>
							<th scope="col" class="text-center">
								<?php echo Text::_('COM_NOTULA_HEADING_JABATAN_LABEL'); ?>
							</th>
							<th scope="col" class="text-center">
								<?php echo Text::_('JTOOLBAR_CHECKIN'); ?>
							</th>
							<th scope="col" class="text-center">
								<?php echo Text::_('COM_MOTULA_HEADING_SOURCE_LABEL'); ?>
							</th>
						</tr>
					</thead>
				</table>
			<?php echo HTMLHelper::_('uitab.endTab'); ?>

			<?php echo HTMLHelper::_('uitab.addTab', 'MyTab', 'publishing', Text::_('JGLOBAL_FIELDSET_PUBLISHING')); ?>
			<fieldset id="fieldset-publishingdata" class="options-form">
                        <legend><?php echo Text::_('JGLOBAL_FIELDSET_PUBLISHING'); ?></legend>
                        <div>
                        <?php echo LayoutHelper::render('joomla.edit.publishingdata', $this); ?>
                        </div>
                    </fieldset>
			<?php echo HTMLHelper::_('uitab.endTab'); ?>
		<?php echo HTMLHelper::_('uitab.endTabSet'); ?>
	</div>

	<input type="hidden" name="task" value="">
	<input type="hidden" name="return"
		value="<?php echo $input->getBase64('return'); ?>">
	<?php echo HTMLHelper::_('form.token'); ?>
</form>