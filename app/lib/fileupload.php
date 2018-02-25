<?php
namespace PHPMVC\lib;


class FileUpload
{
    private $name;
    private $type;
    private $size;
    private $error;
    private $tmpPath;

    private $fileExtension;

    public $hasError = false;

    private $allowedExtensions = [
        'jpg', 'png', 'gif', 'pdf', 'doc', 'docx', 'xls', 'csv'
    ];

    public function __construct(string $fileInput)
    {
        if(null === $_FILES || !isset($_FILES[$fileInput]) || $_FILES[$fileInput]['name'] === '') {
            throw new \Exception('Sorry no file has been uploaded');
        }

        $file = $_FILES[$fileInput];
        $this->name = $file['name'];
        $this->type = $file['type'];
        $this->size = $file['size'];
        $this->error = $file['error'];
        $this->tmpPath = $file['tmp_name'];
        $this->name();
    }

    private function name()
    {
        preg_match_all('/([a-z]{1,4})$/i', $this->name, $m);
        $this->fileExtension = $m[0][0];
        $name = substr(strtolower(base64_encode($this->name . APP_SALT)), 0, 30);
        $name = preg_replace('/(\w{6})/i', '$1_', $name);
        $name = rtrim($name, '_');
        $this->name = $name;
        return $name;
    }

    private function isAllowedType()
    {
        return in_array($this->fileExtension, $this->allowedExtensions);
    }

    private function isSizeNotAcceptable()
    {
        preg_match_all('/(\d+)([MG])$/i', MAX_FILE_SIZE_ALLOWED, $matches);
        $maxFileSizeToUpload = $matches[1][0];
        $sizeUnit = $matches[2][0];
        $currentFileSize = ($sizeUnit == 'M') ? ($this->size / 1024 / 1024) : ($this->size / 1024 / 1024 / 1024);
        $currentFileSize = ceil($currentFileSize);
        return(int) $currentFileSize > (int) $maxFileSizeToUpload;
    }

    private function isImage()
    {
        return preg_match('/image/i', $this->type);
    }

    public function getFileName()
    {
        return $this->name . '.' . $this->fileExtension;
    }

    public function getFilePath()
    {
        return $this->isImage() ? IMAGES_UPLOAD_STORAGE . DS . $this->getFileName() : DOCUMENTS_UPLOAD_STORAGE . DS . $this->getFileName();
    }

    public function upload()
    {
        if($this->error != 0) {
            $this->hasError = true;
            throw new \Exception('Sorry file didn\'t upload successfully');
        } elseif(!$this->isAllowedType()) {
            $this->hasError = true;
            throw new \Exception('Sorry files of type ' . $this->fileExtension .  ' are not allowed');
        } elseif ($this->isSizeNotAcceptable()) {
            $this->hasError = true;
            throw new \Exception('Sorry the file size exceeds the maximum allowed size');
        } else {
            $storageFolder = $this->isImage() ? IMAGES_UPLOAD_STORAGE : DOCUMENTS_UPLOAD_STORAGE;
            if(is_writable($storageFolder)) {
                move_uploaded_file($this->tmpPath, $storageFolder . DS . $this->getFileName());
            } else {
                $this->hasError = true;
                throw new \Exception('Sorry the destination folder is not writable');
            }
        }
        return $this;
    }

    public function remove($file)
    {
        if(file_exists(IMAGES_UPLOAD_STORAGE.DS.$file) && is_writable(IMAGES_UPLOAD_STORAGE)) {
            unlink(IMAGES_UPLOAD_STORAGE.DS.$file);
        }
    }
}