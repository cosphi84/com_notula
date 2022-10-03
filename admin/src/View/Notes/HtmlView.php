<?php 

namespace FtUntagC\Component\Notula\Administrator\View\Notes;

\defined('_JEXEC') or die;

use Joomla\CMS\MVC\View\GenericDataException;
use Joomla\CMS\MVC\View\HtmlView as BaseHtmlView;

class HtmlView extends BaseHtmlView
{
    public $filterForm;
    public $activeFilters = array();
    protected $items = array();
    protected $pagination;
    protected $state;
    private $isEmptyState = false;

    public function display($tpl = null)
    {
        $model = $this->getModel();
        $this->items = $model->getItems();
        $this->pagination = $model->getPagination();
        $this->state = $model->getState();
        $this->activeFilters = $model->getActiveFilters();
        $this->filterForm = $model->getFilterForm();

        if(\count($errors = $this->get('Errors')))
        {
            throw new GenericDataException(implode("\n", $errors), 500);            
        }

        parent::display($tpl);
    }
}