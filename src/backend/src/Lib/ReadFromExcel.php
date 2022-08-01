<?php
// src/Lib/ReadFromExcel.php
namespace App\Lib;

use Symfony\Component\Filesystem\Filesystem;


/**
 * Try load data from Json
 * @todo add interface for reader and implement from it
 */
class ReadFromExcel
{
    private string $EXCEL_ADDR;


    /**
     * set absolute path of excel file
     */
    public function __construct(string $remote)
    {
        // check excel file exist
        if (!file_exists($remote)) {
            throw new \Exception("ExcelFileNotExist");
        }

        // read excel from url
        $myExcelData = file_get_contents($remote);

        $filesystem = new Filesystem();
        $tmpFile = $filesystem->tempnam('/tmp', 'tmp_read');

        // save inside excel in local - for example in temp folder
        file_put_contents($tmpFile, $myExcelData);

        // save tmp remote url
        $this->EXCEL_ADDR = $tmpFile;
    }


    /**
     * return temporary url of excel file get from remote url
     *
     * @return string
     */
    public function getExcelTmpAddr(): string
    {
        return $this->EXCEL_ADDR;
    }


    /**
     * open xlsx and read excel data
     *
     * @return array value of all filled cells inside xlsx file
     */
    public function fetch(string $sheetname): array
    {
        // Identify the type of $filePath
        $inputFileType = \PhpOffice\PhpSpreadsheet\IOFactory::identify($this->EXCEL_ADDR);

        // Create a new Reader of the type that has been identified
        $reader = \PhpOffice\PhpSpreadsheet\IOFactory::createReader($inputFileType);

        // Advise the Reader of which WorkSheets we want to load
        $reader->setLoadSheetsOnly($sheetname);

        // Load $filePath to a Spreadsheet Object
        $spreadsheet = $reader->load($this->EXCEL_ADDR);

        // Convert Spreadsheet Object to an Array for ease of use
        $xslxData = $spreadsheet->getActiveSheet()->toArray();

        // return array of xlsx
        return $xslxData;
    }
}