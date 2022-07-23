<?php
// src/Lib/Import/FromExcel.php
namespace App\Lib\Import;

use App\Lib\Import\PrepareServerDataFromExcel;


/**
 * Try load data from Json
 * if not exist from excel
 * it the future we can save data in Redis for better performance
 */
class FromExcel
{
    // constants
    private const FILENAME = 'servers';
    private const ADDR_FOLDER_PATH = '/File/';

    // path of files
    private string $ADDR_ABSOLUTE_EXCEL;
    private string $ADDR_ABSOLUTE_JSON;


    /**
     * set absolute path of excel file
     */
    public function __construct()
    {
        // save absolute path of excel
        $this->ADDR_ABSOLUTE_EXCEL = dirname(__DIR__). self::ADDR_FOLDER_PATH. self::FILENAME .'.xlsx';
        $this->ADDR_ABSOLUTE_JSON  = dirname(__DIR__). self::ADDR_FOLDER_PATH. 'tmp-'. self::FILENAME .'.json';

        // check excel file exist
        if(!file_exists($this->ADDR_ABSOLUTE_EXCEL))
        {
            throw new \Exception("ExcelFileNotExist");
        }

        $this->openAndReadExcelAndSaveAsJson();
    }


    /**
     * read excel and return array of objects
     * @todo read xlsx from url
     *
     * @return array
     */
    private function openAndReadExcelAndSaveAsJson(): void
    {
        // read data from excel and return array of unfiltered data
        $xlsxData = $this->readExcelAndGetData();

        // clean data and save them inside array of objects
        $preperadDataObj = new PrepareServerDataFromExcel($xlsxData);

        // get dataset of data
        $datalist = $preperadDataObj->dataset();

        // convert it to json
        $jsonContent = json_encode($datalist);

        // save inside json file
        file_put_contents($this->ADDR_ABSOLUTE_JSON, $jsonContent);
    }


    /**
     * open xlsx and read excel data
     *
     * @return array value of all filled cells inside xlsx file
     */
    private function readExcelAndGetData(): array
    {
        $filePath = $this->ADDR_ABSOLUTE_EXCEL;
        $sheetname = 'Sheet2';

        // Identify the type of $filePath
        $inputFileType = \PhpOffice\PhpSpreadsheet\IOFactory::identify($filePath);

        // Create a new Reader of the type that has been identified
        $reader = \PhpOffice\PhpSpreadsheet\IOFactory::createReader($inputFileType);

        // Advise the Reader of which WorkSheets we want to load
        $reader->setLoadSheetsOnly($sheetname);

        // Load $filePath to a Spreadsheet Object
        $spreadsheet = $reader->load($filePath);

        // Convert Spreadsheet Object to an Array for ease of use
        $xslxData = $spreadsheet->getActiveSheet()->toArray();

        // return array of xlsx
        return $xslxData;
    }
}