<?php
namespace PHPMVC\Controllers;
use PHPMVC\Lib\MVC\AbstractController;

class NotFoundController extends AbstractController
{
    public function notFoundAction()
    {
        $this->_view();
    }
}