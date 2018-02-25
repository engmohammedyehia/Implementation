<?php
namespace PHPMVC;

use PHPMVC\LIB\AppConfig;
use PHPMVC\lib\Authentication;
use PHPMVC\lib\CSRFSecHandler;
use PHPMVC\lib\Messenger;
use PHPMVC\Lib\Registry;
use PHPMVC\LIB\FrontController;
use PHPMVC\LIB\Language;
use PHPMVC\LIB\SessionManager;
use PHPMVC\LIB\Template\Template;

if(!defined('DS')) {
    define('DS', DIRECTORY_SEPARATOR);
}

// Call the autoloader
require_once realpath(dirname(__FILE__)) . DS . '..' . DS . 'vendor' . DS . 'autoload.php';

// Instantiate the application with its basic configuration
$appConfig = AppConfig::getInstance();
$appConfig->loadAppConfiguration();

// Starts the session
$session = new SessionManager();
$session->start();
$session->setLanguage();

// Instantiate the template to prepare for rendering
$template = new Template();

// Instantiate the language to apply multi-lengual interface
$language = new Language();

// Instantiate a messenger object to send messages across the application
$messenger = Messenger::getInstance($session);

// Instantiate the authentication process
$authentication = Authentication::getInstance($session);


// Register the previous objects
$registry = Registry::getInstance();
$registry->session = $session;
$registry->language = $language;
$registry->messenger = $messenger;

// Calling the front controller to render the app
$frontController = new FrontController($template, $registry, $authentication);
$frontController->dispatch();
