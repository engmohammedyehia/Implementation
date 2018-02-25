<?php
namespace PHPMVC\Lib\MVC;

use PHPMVC\LIB\FrontController;
use PHPMVC\Lib\Registry;
use PHPMVC\LIB\Template;
use PHPMVC\Lib\Validate;

/**
 * Class AbstractController
 * @package PHPMVC\Lib\MVC
 * @author Mohammed Yehia <firefoxegy@gmail.com>
 */
class AbstractController
{

    use Validate;

    /**
     * @var string Controller Name
     */
    protected $_controller;

    /**
     * @var string Action Name
     */
    protected $_action;

    /**
     * @var GET Request Parameters
     */
    protected $_params;

    /**
     * @var Template\Template
     */
    protected $_template;

    /**
     * @var Registry
     */
    protected $_registry;

    /**
     * @var array Data passed to the view
     */
    protected $_data = [];

    public function __get($key)
    {
        return $this->_registry->$key;
    }

    /**
     * Calls the not found action if either Controller / Action is not found
     */
    public function notFoundAction()
    {
        $this->_view();
    }

    /**
     * @param $controllerName string the Controller Name being requested
     */
    public function setController ($controllerName)
    {
        $this->_controller = $controllerName;
    }

    /**
     * @param $actionName string the Action method being called
     */
    public function setAction ($actionName)
    {
        $this->_action = $actionName;
    }

    /**
     * @param Template\Template $template The template object to be used in conjunction with views
     */
    public function setTemplate(Template\Template $template)
    {
        $this->_template = $template;
    }

    /**
     * @param Registry $registry The registry object which holds most of the required dependant objects
     */
    public function setRegistry(Registry $registry)
    {
        $this->_registry = $registry;
    }

    /**
     * @param $params setting the request parameters
     */
    public function setParams ($params)
    {
        $this->_params = $params;
    }

    /**
     * Calling the appropriate view regarding the called action method
     */
    protected function _view()
    {
        $view = VIEWS_PATH . $this->_controller . DS . $this->_action . '.view.php';
        if($this->_action == FrontController::NOT_FOUND_ACTION || !file_exists($view)) {
            $view = VIEWS_PATH . 'notfound' . DS . 'notfound.view.php';
        }
        $this->_data = array_merge($this->_data, $this->language->getDictionary());
        $this->_template->setRegistry($this->_registry);
        $this->_template->setActionViewFile($view);
        $this->_template->setAppData($this->_data);
        $this->_template->renderApp();
    }

    /**
     * @return bool check if the request has a valid CSRF Token
     */
    protected function requestHasValidToken ()
    {
        return $_REQUEST['token'] === $this->session->CSRFToken ? true : false;
    }
}