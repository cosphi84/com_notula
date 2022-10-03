<?php

namespace FtUntagC\Component\Notula\Administrator\Table;

\defined('_JEXEC') or die;

use Joomla\CMS\Application\ApplicationHelper;
use Joomla\CMS\Table\Table;
use Joomla\CMS\Versioning\VersionableTableInterface;
use Joomla\Database\DatabaseDriver;
use Joomla\CMS\Factory;

class MeetingTable extends Table implements VersionableTableInterface
{
    protected $_supportNullValue = true;

    public function __construct(DatabaseDriver $db)
    {
        $this->typeAlias = 'com_notula.notula';

        parent::__construct('#__meeting', 'id', $db);
        $this->setColumnAlias('published', 'state');
    }

    public function getTypeAlias()
    {
        return $this->typeAlias;
    }

    public function check()
    {
        try {
            parent::check();
        } catch(\Exception $e) {
            $this->setError($e->getMessage());
            return false;
        }

        if (trim($this->title) == '') {
            $this->setError(Text::_('COM_NOTULA_WARNING_PROVIDE_VALID_NAME'));

            return false;
        }

        if (trim($this->alias) == '') {
            $this->alias = $this->title;
        }

        $this->alias = ApplicationHelper::stringURLSafe($this->title);

        if (trim(str_replace('-', '', $this->alias)) == '') {
            $this->alias = Factory::getDate()->format('Y-m-d-H-i-s');
        }

        // Check for a valid category.
        if (!$this->catid = (int) $this->catid) {
            $this->setError(Text::_('JLIB_DATABASE_ERROR_CATEGORY_REQUIRED'));

            return false;
        }

        // Set publish_up to null if not set
        if (!$this->publish_up) {
            $this->publish_up = null;
        }

        // Set publish_down to null if not set
        if (!$this->publish_down) {
            $this->publish_down = null;
        }

        // Check the publish down date is not earlier than publish up.
        if (!is_null($this->publish_up) && !is_null($this->publish_down) && $this->publish_down < $this->publish_up) {
            // Swap the dates.
            $temp = $this->publish_up;
            $this->publish_up = $this->publish_down;
            $this->publish_down = $temp;
        }

        return true;
    }
}
