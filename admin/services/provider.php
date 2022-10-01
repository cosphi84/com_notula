<?php

/**
 * @package     FtUntagC.Notula
 * @subpackage  com_notula
 *
 * @copyright   (C) 2022 risam
 * @license     FT Untag Cirebon Used Only
 * 
 */

 defined('_JEXEC') or die;

 use Joomla\DI\ServiceProviderInterface;
 use Joomla\DI\Container;
 use Joomla\CMS\Extension\Service\Provider\CategoryFactory;
 use Joomla\CMS\Extension\Service\Provider\MVCFactory;
 use Joomla\CMS\Extension\Service\Provider\ComponentDispatcherFactory;
 use Joomla\CMS\Extension\Service\Provider\RouterFactory;
 use Joomla\CMS\Extension\ComponentInterface;
 use FtUntagC\Component\Notula\Administrator\Extension\NotulaComponent;
 use Joomla\CMS\Dispatcher\ComponentDispatcherFactoryInterface;
 use Joomla\CMS\HTML\Registry;
 use Joomla\CMS\MVC\Factory\MVCFactoryInterface;
 use Joomla\CMS\Categories\CategoryFactoryInterface;
 use Joomla\CMS\Component\Router\RouterFactoryInterface;



 /**
 * The Notula service provider.
 *
 * @since  1.0.0
 */
return new class implements ServiceProviderInterface 
{
    /**
     * Registers the service provider with a DI container.
     *
     * @param   Container  $container  The DI container.
     *
     * @return  void
     *
     * @since   1.0.0
     */
    public function register(Container $container)
    {
        $container->registerServiceProvider(new CategoryFactory('\\FtUntagC\\Component\\Notula'));
        $container->registerServiceProvider(new MVCFactory('\\FtUntagC\\Component\\Notula'));
        $container->registerServiceProvider(new ComponentDispatcherFactory('\\FtUntagC\\Component\\Notula'));
        $container->registerServiceProvider(new RouterFactory('\\FtUntagC\\Component\\Notula'));

        $container->set(
            ComponentInterface::class,
            function(Container $container)
            {
                $component = new NotulaComponent($container->get(ComponentDispatcherFactoryInterface::class));
                $component->setRegistry($container->get(Registry::class));
                $component->setMVCFactory($container->get(MVCFactoryInterface::class));
                $component->setCategoryFactory($container->get(CategoryFactoryInterface::class));
                $component->setRouterFactory($container->get(RouterFactoryInterface::class));

                return $component;
            }
        );
    }
}
?>