<?php
namespace PHPMVC\Classes;
use PHPMVC\Classes\Parsers\IParser;

class FilePersistence
{
    /**
     * @var string the parsed data ready to save to disk
     */
    private $_data;

    /**
     * @var IParser the parse object
     */
    private $_parser;

    /**
     * @var the file name saved to disk
     */
    private $_fileName;

    /**
     * FilePersistence constructor.
     * @param IParser $parser
     */
    public function __construct(IParser $parser)
    {
        $this->_parser = $parser;
        $this->_data = $parser->getData();
    }

    /**
     * @return string returns the filename which has been saved to the disk
     */
    public function getFileName()
    {
        return $this->_fileName;
    }

    /**
     * @return string the parsed data
     */
    private function getData() : string
    {
        return $this->_data;
    }

    /**
     * Saved the parsed data to a file to the disk
     * @return bool
     * @throws \Exception
     */
    public function save()
    {
        if($this->storageDirectoryExists() && $this->storageDirectoryIsWritable()) {
            $this->_fileName = trim(base64_encode($this->_parser->getFileExtension() . '_' . time()), '=') . '.' . $this->_parser->getFileExtension();
            if(file_put_contents(DOCUMENTS_UPLOAD_STORAGE.DS.$this->_fileName, $this->getData()) !== false) {
                return true;
            }
        }
        return false;
    }

    /**
     * Ensure the storage directory exists
     * @return bool
     * @throws \Exception
     */
    private function storageDirectoryExists()
    {
        if(!file_exists(DOCUMENTS_UPLOAD_STORAGE) &&
            !is_dir(DOCUMENTS_UPLOAD_STORAGE)) {
            throw new \Exception('Directory doesn\'t exists or not a directory');
        }
        return true;
    }

    /**
     * Ensure the storage directory is writable
     * @return bool
     * @throws \Exception
     */
    private function storageDirectoryIsWritable()
    {
        if(!is_writable(DOCUMENTS_UPLOAD_STORAGE)) {
            throw new \Exception('Directory is not writable');
        }
        return true;
    }
}