<?php
namespace PHPMVC\Controllers;

use PHPMVC\Classes\FilePersistence;
use PHPMVC\Classes\Mainpulators\DataFilter;
use PHPMVC\Classes\Mainpulators\DataSorter;
use PHPMVC\Classes\Parsers\SimpleParserFactory;
use PHPMVC\lib\FileUpload;
use PHPMVC\LIB\Helper;
use PHPMVC\LIB\InputFilter;
use PHPMVC\lib\Messenger;
use PHPMVC\Lib\MVC\AbstractController;

class IndexController extends AbstractController
{

    use Helper;
    use InputFilter;

    public function defaultAction()
    {
        $this->language->load('index.default');
        $this->language->load('index.messages');

        if(isset($_POST['submit'])) {

            // Get the upload csv file and save it to desk
            try {
                $uploader = new FileUpload('data');
                try {
                    $uploader->upload();
                } catch (\Exception $e) {
                    $this->messenger->add($e->getMessage(), Messenger::APP_MESSAGE_ERROR);
                    $this->redirect('/');
                }
            } catch (\Exception $e) {
                $this->messenger->add($e->getMessage(), Messenger::APP_MESSAGE_ERROR);
                $this->redirect('/');
            }

            // open the csv file
            $csv = fopen(DOCUMENTS_UPLOAD_STORAGE . DS . $uploader->getFileName(), 'r');

            // extract the first line of the CSV file
            $data = fgetcsv($csv, 1000, ",");

            // check if the file is empty
            if(array_splice($data,0) === null) {
                $this->redirect('/');
            }

            // build the final data array
            $finalDataArray = [];

            // looping through the csv file to build a valid data array
            while (($data = fgetcsv($csv, 1000, ",")) !== false) {
                $finalDataArray[] = $data;
            }

            // if filters are sent we filter the data
            if(isset($_POST['filter']) && !empty($_POST['filter'])) {
                $filters = $_POST['filter'];

                try {
                    $filter = new DataFilter($finalDataArray, $filters);
                    $filter->filterData();
                    $finalDataArray = $filter->getFilteredData();
                } catch (\Exception $e) {
                    $this->messenger->add($e->getMessage(), Messenger::APP_MESSAGE_ERROR);
                    $this->redirect('/');
                }
            }

            // if sort options are sent we sort the data
            if(isset($_POST['sort']) && !empty($_POST['sort'])) {
                $sorters = $_POST['sort'];
                try {
                    $sorter = new DataSorter($finalDataArray, $sorters);
                    if(isset($filter)) {
                        $sorter->setFilter($filter);
                    }
                    $sorter->SortData();
                    $finalDataArray = $sorter->getSortedData();
                } catch (\Exception $e) {
                    $this->messenger->add($e->getMessage(), Messenger::APP_MESSAGE_ERROR);
                    $this->redirect('/');
                }
            }

            // Calling the SimpleParserFactory to create a IParser Object in your favor
            /*
             * we have to flavors
             * 1- JSON => SimpleParserFactory::JSON_PARSER
             * 2- XML => SimpleParserFactory::XML_PARSER
             */
            try {
                // Get the desired type
                $outputType = $this->filterString($_POST['output_type']);
                // create an IParser Object of your flavor
                $parser = SimpleParserFactory::getInstance($outputType);
                // Set the filtered data
                $parser->setData($finalDataArray);
                // Set the required fields if filters have been sent
                if(isset($filters)) {
                    $parser->setRequiredFields($_POST['filter']);
                }
                // Prepare the data to validate it and then parse it (converting it to the proper format)
                $parser->prepare()->parse();
                // Create a file persistence object to save the output to the desk
                $filePersistence = new FilePersistence($parser);

                // create and save the file
                if($filePersistence->save() === true) {
                    // Return a success message with a download link
                    $this->language->swapKey('text_download_link', ['/download/?file=' . $filePersistence->getFileName()]);
                    $this->messenger->add($this->language->get('text_download_link'));
                    $this->redirect('/');
                }
            } catch (\Exception $e) {
                echo $e->getMessage();
            }
        }

        $this->_view();
    }
}




