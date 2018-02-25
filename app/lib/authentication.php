<?php
namespace PHPMVC\lib;

/**
 * Class Authentication
 * @package PHPMVC\lib
 * @author Mohammed Yehia <firefoxegy@gmail.com>
 */
class Authentication
{
    /**
     * @var self singleton instance of the class
     */
    private static $_instance;

    /**
     * @var SessionManager
     */
    private $_session;

    /**
     * @var array Excluded paths (these routes are not checked against user privileges)
     */
    private $_execludedRoutes = [
        '/index/default',
        '/auth/logout',
        '/users/profile',
        '/users/changepassword',
        '/users/settings',
        '/language/default',
        '/accessdenied/default',
        '/notfound/notfound',
        '/test/default'
    ];

    /**
     * Authentication constructor.
     * @param $session SessionManager
     */
    private function __construct(SessionManager $session)
    {
        $this->_session = $session;
    }

    /**
     * Prevent cloning
     */
    private function __clone()
    {

    }

    /**
     * @param SessionManager $session
     * @return Authentication a singleton instance
     */
    public static function getInstance(SessionManager $session)
    {
        if(self::$_instance === null) {
            self::$_instance = new self($session);
        }
        return self::$_instance;
    }

    /**
     * @return bool true is the user is logged in, false otherwise
     */
    public function isAuthorized()
    {
        if((bool) CHECK_FOR_AUTHORIZATION === false) {
            return true;
        }
        return isset($this->_session->u);
    }

    /**
     * @param $controller
     * @param $action
     * @return bool true is the user privilege matches the route, otherwise false
     */
    public function hasAccess($controller, $action)
    {
        $url = strtolower('/' . $controller . '/' . $action);
        if(in_array($url, $this->_execludedRoutes) || in_array($url, $this->_session->u->privileges)) {
            return true;
        }
    }
}