<?php
namespace PHPMVC\Controllers;

use PHPMVC\LIB\Helper;
use PHPMVC\Lib\MVC\AbstractController;

class DownloadController extends AbstractController
{
    use Helper;
    public function defaultAction()
    {
        if(!isset($_GET['file'])) {
            $this->redirect('/');
        }

        $file = DOCUMENTS_UPLOAD_STORAGE . DS . $_GET['file'];

        if(!file_exists($file)) {
            $this->redirect('/');
        }

        header('Content-Type: ' . mime_content_type($file));
        header('Content-disposition: attachment; filename=data.' . time() . '.' . (new \SplFileInfo($file))->getExtension());
        header('Content-Length: ' . filesize($file));
        readfile($file);
    }
}




