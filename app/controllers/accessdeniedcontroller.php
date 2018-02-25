<?php
namespace PHPMVC\Controllers;
use PHPMVC\Lib\MVC\AbstractController;

class AccessDeniedController extends AbstractController
{
    public function defaultAction()
    {
        $this->language->load('accessdenied.default');
        $this->_view();
    }
}