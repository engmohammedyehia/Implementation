<?php
namespace PHPMVC\LIB;
use PHPMVC\LIB\Template\Template;

/**
 * Class FrontController
 * @package PHPMVC\LIB
 * A Simple front controller class
 * @author Mohammed Yehia <firefoxegy@gmail.com>
 */
class FrontController
{
    use Helper;

    /**
     * The not found action method name
     */
    const NOT_FOUND_ACTION = 'notFoundAction';

    /**
     * The not found controller class FQDN
     */
    const NOT_FOUND_CONTROLLER = 'PHPMVC\Controllers\\NotFoundController';

    /**
     * @var string The default controller name
     */
    private $_controller = 'index';

    /**
     * @var string The default action name
     */
    private $_action = 'default';

    /**
     * @var array collected parameters extracted from the request
     */
    private $_params = array();

    /**
     * @var Registry The Registry object
     */
    private $_registry;

    /**
     * @var Template The template object
     */
    private $_template;

    /**
     * @var Authentication The Authentication object
     */
    private $_authentication;

    /**
     * FrontController constructor.
     * @param Template $template
     * @param Registry $registry
     * @param Authentication $auth
     */
    public function __construct(Template $template, Registry $registry, Authentication $auth)
    {
        $this->_template = $template;
        $this->_registry = $registry;
        $this->_authentication = $auth;
        $this->_parseUrl();
    }

    /**
     * Parse the request URL to extract controller, action and parameters
     */
    private function _parseUrl()
    {
        $url = explode('/', trim(parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH), '/'), 3);
        if(isset($url[0]) && $url[0] != '') {
            $this->_controller = $url[0];
        }
        if(isset($url[1]) && $url[1] != '') {
            $this->_action = $url[1];
        }
        if(isset($url[2]) && $url[2] != '') {
            $this->_params = explode('/', $url[2]);
        }
    }

    /**
     * Dispatch the request
     */
    public function dispatch()
    {
        $controllerClassName = 'PHPMVC\Controllers\\' . ucfirst($this->_controller) . 'Controller';
        $actionName = $this->_action . 'Action';

        // Check if the user is authorized to access the application
        if(!$this->_authentication->isAuthorized()) {
            if(!empty($_SERVER['HTTP_X_REQUESTED_WITH']) && strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) == 'xmlhttprequest') {
                if($this->_controller != 'auth' || !in_array($this->_action, ['authenticate','loadprofile','loadprivileges','dologin'])) {
                    exit('This request is not allowed');
                }
            } else {
                if($this->_controller != 'auth' || $this->_action != 'login') {
                    $this->redirect('/auth/login');
                }
            }
        } else {
            // deny access to the auth/login
            if($this->_controller == 'auth' && $this->_action == 'login') {
                isset($_SERVER['HTTP_REFERER']) ? $this->redirect($_SERVER['HTTP_REFERER']) : $this->redirect('/');
            }
            // Check if the user has access to specific url
            if((bool) CHECK_FOR_PRIVILEGES === true) {
                if(!$this->_authentication->hasAccess($this->_controller, $this->_action))
                {
                    $this->redirect('/accessdenied');
                }
            }
        }

        if(!class_exists($controllerClassName) || !method_exists($controllerClassName, $actionName)) {
            $controllerClassName = self::NOT_FOUND_CONTROLLER;
            $this->_action = $actionName = self::NOT_FOUND_ACTION;
        }

        $controller = new $controllerClassName();
        $controller->setController($this->_controller);
        $controller->setAction($this->_action);
        $controller->setParams($this->_params);
        $controller->setTemplate($this->_template);
        $controller->setRegistry($this->_registry);
        $controller->$actionName();
    }
}