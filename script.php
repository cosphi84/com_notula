<?php

/**
 * @package     FtUntagC.Notula
 * @subpackage  com_notula
 *
 * @copyright   (C) 2022 risam
 * @license     FT Untag Cirebon Used Only

 * @phpcs:disable PSR1.Classes.ClassDeclaration.MissingNamespace
 */

\defined('_JEXEC') or die;

/**
 * Script Install Notulen
 * @since 1.0.0
 */
class NotulaInstallerScript
{
    public function preflight($action, $installer)
    {
        return true;
    }
    public function install($installer)
    {
        return true;
    }

    public function update($installer)
    {
    }
    public function uninstall($installer)
    {
        return true;
    }
    public function postflight($action, $installer)
    {
        return true;
    }
}
