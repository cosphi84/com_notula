<?php

namespace FtUntagC\Component\Notula\Administrator\View\Note;

\defined('_JEXEC') or die;

use Joomla\CMS\Factory;
use Joomla\CMS\Helper\ContentHelper;
use Joomla\CMS\Language\Text;
use Joomla\CMS\MVC\View\GenericDataException;
use Joomla\CMS\MVC\View\HtmlView as BaseView;
use Joomla\CMS\Toolbar\Toolbar;
use Joomla\CMS\Toolbar\ToolbarHelper;

class HtmlView extends BaseView
{
    protected $form;
    protected $item;
    protected $state;
    protected $candDo;

    public function display($tpl = null)
    {
        
        $this->form = $this->get('Form');
        $this->item = $this->get('Item');
        $this->state = $this->get('State');
        $this->candDo = ContentHelper::getActions('com_notula', 'notulen', $this->item->id);

        if (\count($errors = $this->get('Errors'))) {
            throw new GenericDataException(implode("\n", $errors), 500);
        }

        $this->addToolbar();

        parent::display($tpl);
    }

    protected function addToolbar()
    {
        $app = Factory::getApplication();
        $app->input->set('hidemainmenu', true);

        $user = $this->getCurrentUser();
        $userID = $user->id;
        $isNew = ($this->item->id == 0);
        $checkedOut = !(is_null($this->item->checked_out) || $this->item->checked_out == $userID);

        $canDo = $this->candDo;

        $toolbar = Toolbar::getInstance();

        ToolbarHelper::title(
            Text::_('COM_NOTULA_PAGE_' . ($checkedOut ? 'VIEW_NOTULEN' : ($isNew ? 'ADD_NOTULEN':'EDIT_NOTULEN'))),
            'pencil-alt article-add'
        );

        if($isNew && (count($user->getAuthorisedCategories('com_notula', 'core.create')) > 0)) {
            $toolbar->apply('note.apply');

            $saveGroup = $toolbar->dropdownButton('save-group');

            $saveGroup->configure(
                function(Toolbar $childBar) use ($user) {
                    $childBar->save('note.save');

                    $childBar->save2new('note.save2new');
                }
            );

            $toolbar->cancel('note.cancel', 'JTOOLBAR_CANCEL');
        }else{
            $itemEditable = $canDo->get('core.edit') || ($canDo->get('core.edit.own') && $this->item->created_by == $userID);

            if ($checkedOut && $itemEditable) {
                $toolbar->apply('note.apply');
            }
            $saveGroup->configure(
                function(Toolbar $childBar) use ($checkedOut, $itemEditable, $canDo, $user) {
                    if(!$checkedOut && $itemEditable)
                    {
                        $childBar->save('note.save');

                        if($canDo->get('core.create'))
                        {
                            $childBar->save2new('note.save2new');
                        }
                    }

                    if($canDo->get('core.create')){
                        $childBar->save2copy('note.save2copy');
                    }
                }
            );

            $toolbar->cancel('note.cancel', 'JTOOLBAR_CLOSE');
        }   
        $toolbar->divider();
        ToolbarHelper::inlinehelp();
        $toolbar->help('Notula:_Edit');
    }
}
