<?php
/**
 * Note Model
 */
namespace FtUntagC\Component\Notula\Administrator\Model;

defined('_JEXEC') or die;

use Joomla\CMS\MVC\Model\AdminModel;
use Joomla\CMS\Versioning\VersionableModelTrait;
use Joomla\CMS\MVC\Factory\MVCFactoryInterface;
use Joomla\CMS\Form\FormFactoryInterface;
use Joomla\CMS\Factory;


class NoteModel extends AdminModel 
{
    use VersionableModelTrait;

    protected $text_prefix = 'COM_NOTULA';

    public $typeAlias = 'com_notula.note';

    public function __construct($config = array(), MVCFactoryInterface $factory = null, FormFactoryInterface $formFactory = null)
    {
        $config['events_map'] = $config['events_map'] ?? [];

        parent::__construct($config, $factory, $formFactory);
    }

    public function getTable($name = 'Meeting', $prefix = 'Table', $options = array())
    {
        return parent::getTable($name, $prefix, $options);
    }

    protected function canDelete($record)
    {
        if(empty($record->id) || ($record->state != -2) ){
            return false;
        }

        return Factory::getApplication()
            ->getIdentity()
            ->authorise('core.delete', 'com_notula.note.'. (int) $record->id);
    }

    protected function canEditState($record)
    {
        $user = Factory::getApplication()->getIdentity();

        if(!empty($record->id)) {
            return $user->authorise('core.edit.state', 'com_notula.note.'.(int)$record->id);
        }

        if(!empty($record->catid)){
            return $user->authorise('core.edit.state', 'com_notula.category.'.(int) $record->catid);
        }

        return parent::canEditState($record);
    }

    public function getForm($data = array(), $loadData = true)
    {
        $app = Factory::getApplication();

        $form = $this->loadForm('com_notula.note', 'note', array('control'=>'jform', 'load_data'=>$loadData));
        
        if (empty($form)) {
            return false;
        }

        return $form;

    }
}