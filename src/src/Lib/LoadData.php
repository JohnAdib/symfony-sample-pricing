<?php
// src/Lib/LoadData.php
namespace App\Lib;

use App\Lib\DataStructure\PrepareServerDataFromExcel;
use App\Lib\DataStructure\PrepareServerDataFromJson;


/**
 * Try load data from Json
 * if not exist from excel
 * it the future we can save data in Redis for better performance
 */
class LoadData
{
    // constants
    private const ADDR_EXCEL_FILE_NAME = 'datalist.xlsx';
    private const ADDR_FOLDER_PATH = '/Lib/File/';
    // location of excel file
    private string $ADDR_ABSOLUTE_PATH_EXCEL;
    private string $ADDR_ABSOLUTE_PATH_JSON_PREFIX;
    private string $ADDR_ABSOLUTE_PATH_JSON_FULL;


    /**
     * set absolute path of excel file
     */
    public function __construct()
    {
        // save absolute path of excel
        $this->ADDR_ABSOLUTE_PATH_EXCEL = dirname(__DIR__). self::ADDR_FOLDER_PATH. self::ADDR_EXCEL_FILE_NAME;

        // check excel file exist
        if(!file_exists($this->ADDR_ABSOLUTE_PATH_EXCEL))
        {
            throw new \Exception("ExcelFileNotExist");
        }

        // get md5 of xlsx and create json path from that
        $jsonFileName = self::getExcelMd5();
        // save absolute path of temp json
        $this->ADDR_ABSOLUTE_PATH_JSON_PREFIX = dirname(__DIR__). self::ADDR_FOLDER_PATH. 'tmp-'. $jsonFileName;
        $this->ADDR_ABSOLUTE_PATH_JSON_FULL = $this->ADDR_ABSOLUTE_PATH_JSON_PREFIX. '-servers.json';

    }


    /**
     * return md5 of excel to save json as unique name
     * because if after a while excel updated, we are update json automatically
     *
     * @return string
     */
    private function getExcelMd5(): string
    {
        return md5_file($this->ADDR_ABSOLUTE_PATH_EXCEL);
    }


    /**
     * return data inside array of objects
     *
     * @return array
     */
    public function dataset(): array
    {
        // check json file status
        if(file_exists($this->ADDR_ABSOLUTE_PATH_JSON_FULL))
        {
            // read from json
            $jsonContent = file_get_contents($this->ADDR_ABSOLUTE_PATH_JSON_FULL);

            // decode json to array
            $jsonDecoded = json_decode($jsonContent, true);

            // passed to prepare data to fill in object
            $datalist = new PrepareServerDataFromJson($jsonDecoded);
            $datalistArr = $datalist->dataset();

            return $datalistArr;
        }


        // json is not exist, so read excel
        return $this->openAndReadExcelAndSaveAsJson();
    }


    /**
     * read excel and return array of objects
     * @todo read xlsx from url
     *
     * @return array
     */
    public function openAndReadExcelAndSaveAsJson(): array
    {
        // read data from excel and return array of unfiltered data
        $xlsxData = $this->readExcel();

        // clean data and save them inside array of objects
        $preperadDataObj = new PrepareServerDataFromExcel($xlsxData);
        $datalist = $preperadDataObj->dataset();

        foreach ($datalist as $group => $dataArr)
        {
            // serialize datalist to json
            // $jsonContent = json_encode($dataArr, JSON_PRETTY_PRINT);
            $jsonContent = json_encode($dataArr);

            // save json
            $jsonFullPath = $this->ADDR_ABSOLUTE_PATH_JSON_PREFIX. '-'. $group. '.json';
            file_put_contents($jsonFullPath, $jsonContent);
        }

        // return datalist
        return $datalist['servers'];
    }


    /**
     * open xlsx and read excel data
     *
     * @return array value of all filled cells inside xlsx file
     */
    private function readExcel(): array
    {
        $filePath = $this->ADDR_ABSOLUTE_PATH_EXCEL;
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