<?php
namespace PHPMVC\LIB;

/**
 * Trait Helper
 * @package PHPMVC\LIB
 * @author Mohammed Yehia <firefoxegy@gmail.com>
 */
trait Helper
{
    /**
     * @param string $path the path to which the application will be redirected for
     */
    public function redirect($path)
    {
        session_write_close();
        header('Location: ' . $path);
        exit;
    }
}