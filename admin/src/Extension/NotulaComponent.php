<?php 

namespace FtUntagC\Component\Notula\Administrator\Extension;

use FtUntagC\Component\Notula\Administrator\Service\Html\Notula;
use Joomla\CMS\Categories\CategoryServiceInterface;
use Joomla\CMS\Categories\CategoryServiceTrait;
use Joomla\CMS\Component\Router\RouterServiceInterface;
use Joomla\CMS\Component\Router\RouterServiceTrait;
use Joomla\CMS\Extension\BootableExtensionInterface;
use Joomla\CMS\Extension\MVCComponent;
use Joomla\CMS\HTML\HTMLRegistryAwareTrait;
use Psr\Container\ContainerInterface;

\defined('_JEXEC') or die;

class NotulaComponent extends MVCComponent implements BootableExtensionInterface, CategoryServiceInterface, RouterServiceInterface {
    use HTMLRegistryAwareTrait;
    use RouterServiceTrait;
    use CategoryServiceTrait;

    public function boot(ContainerInterface $container)
    {
        $notula = new Notula();
        $this->getRegistry()->register('notula', $notula);
    }

    
}