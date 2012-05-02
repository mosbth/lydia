<?php
/**
 * Interface for class that can be installed / updated / uninstalled.
 *
 * The method should implement the needed actions for installing, updating or deinstalling
 * the module. The module can be the class that implements the interface and its related
 * classes, functions, etc. Ordinary actions to carry out is creating tables in database,
 * checking writable directory for cache-files, checking pre-conditions that PHP-extensions
 * are available and the right versions of software are installed.
 *
 * @package LydiaCore
 */
interface IModule {
  public function Manage($action=null);
}