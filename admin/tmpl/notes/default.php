<?php

defined('_JEXEC') or die;

use Joomla\CMS\Factory;
use Joomla\CMS\HTML\HTMLHelper;
use Joomla\CMS\Language\Text;
use Joomla\CMS\Layout\LayoutHelper;
use Joomla\CMS\Router\Route;

$wa = $this->document->getWebAssetManager();
$wa->useScript('table.columns')
    ->useScript('multiselect');

$app = Factory::getApplication();
$user = $app->getIdentity();
$userId = $user->get('id');
$listOrder = $this->escape($this->state->get('list.ordering'));
$listDirn = $this->escape($this->state->get('list.direction'));

?>
<form method="POST" action="<?php echo Route::_('index.php?option=com_notula&view=notes'); ?>" name="adminForm" id="adminForm">
    <div class="row">
        <div class="col-md-12">
            <div id="j-main-container" class="j-main-container">
                <?php echo LayoutHelper::render('joomla.searchtools.default', array('view'=>$this)); ?>
                <?php if (empty($this->items)) : ?>
                    <div class="alert alert-info">
                        <span class="icon-info-circle" aria-hidden="true"></span>
                        <span class="visually-hidden"><?php echo Text::_('INFO'); ?></span>
                        <?php echo Text::_('JGLOBAL_NO_MATCHING_RESULTS'); ?>
                    </div>
                <?php else : ?>
                    <table class="table itemList" id="notulaList">
                        <caption class="visually-hidden">
                            <?php echo Text::_('COM_NOTULA_NOTULEN_TABLE_CATION'); ?>
                            <span id="orderedBy"><?php echo Text::_('JGLOBAL_SORTED_BY'); ?> </span>,
                            <span id="filteredBy"><?php echo Text::_('JGLOBAL_FILTERED_BY'); ?></span>
                        </caption>
                        <thead>
                            <tr>
                                <td class="w-1 text-center">
                                    <?php echo HTMLHelper::_('grid.checkall'); ?>
                                </td>
                                <th scope="col" class="w-1 text-center d-none d-md-table-cell">
                                    <?php echo HTMLHelper::_('searchtools.sort', '', 'a.ordering', $listDirn, $listOrder, null, 'asc', 'JGRID_HEADING_ORDERING', 'icon-sort' ); ?>
                                </th>
                                <th scope="col" class="w-10 d-none d-md-table-cell text-center">
                                    <?php echo HTMLHelper::_('searchtools.sort', 'COM_NOTULA_HEADING_DATE', 'a.meeting_time', $listDirn, $listOrder); ?>
                                </th>
                                <th scope="col" style="min-width: 100px; text-align: center" >
                                    <?php echo HTMLHelper::_('searchtools.sort', 'JGLOBAL_TITLE', 'a.title', $listDirn, $listOrder); ?>
                                </th>                                
                                <th scope="col" class="w-1 text-center">
                                    <?php echo HTMLHelper::_('searchtools.sort','COM_NOTULA_NOTES_HEADING_VENUE', 'a.venue', $listDirn, $listOrder); ?>
                                </th>
                                <th scope="col" class="w-1 text-center">
                                    <?php echo HTMLHelper::_('searchtools.sort','COM_NOTULA_NOTES_HEADING_PRESENTED_BY', 'a.presented_by', $listDirn, $listOrder); ?>
                                </th>
                                <th scope="col" class="w-1 text-center">
                                    <?php echo Text::_('COM_NOTULA_NOTES_HEADING_PARTICIPANT'); ?>
                                </th>
                                <th scope="col" class="w-3 d-none d-lg-table-cell">
                                    <?php echo HTMLHelper::_('searchtools.sort', 'JGRID_HEADING_ID', 'a.id', $listDirn, $listOrder); ?>
                                </th>                                
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach($this->items as $i=>$item) : ?> 
                                <tr class="row<?php echo $i % 2; ?>">
                                    <td class="text-center">
                                        <?php echo HTMLHelper::_('grid.id', $i, $item->id, false, 'cid', 'cb', $item->title); ?>
                                    </td>
                                    <td class="text-center d-none d-md-table-cell">
                                        <?php echo $item->ordering; ?>
                                    </td>
                                    <td>
                                        <?php echo HTMLHelper::date($item->meeting_time); ?>
                                    </td>
                                    <td>
                                        <?php echo $this->escape($item->title); ?>
                                    </td>
                                    <td>
                                        <?php echo $this->escape($item->venue); ?>
                                    </td>
                                    <td>
                                        <?php echo $item->presented_by; ?>
                                    </td>
                                    <td>
                                        <?php echo (int) $item->participant; ?>
                                    </td>
                                    <td>
                                       <?php echo (int) $item->id; ?>
                                    </td>
                                </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                    <?php echo $this->pagination->getListFooter(); ?>
                <?php endif; ?>
            </div>
        </div>
    </div>
    <input type="hidden" name="task" value="" >
    <input type="hidden" name="boxchecked" value="0" >
    <?php echo HTMLHelper::_('form.token'); ?>
</form>