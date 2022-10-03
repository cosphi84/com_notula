<?php 

namespace FtUntagC\Component\Notula\Administrator\Controller;

\defined('_JEXEC') or die;

use Joomla\CMS\MVC\Controller\BaseController;


class DisplayController extends BaseController
{
    protected $default_view = 'notes';

    public function display($cachable = false, $urlparams = array())
    {
        
        parent::display();
    }
}