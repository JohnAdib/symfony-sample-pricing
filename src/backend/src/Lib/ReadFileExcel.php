<?php
declare(strict_types = 1);

namespace App\Lib;

use App\Lib\ReadFileAbstract;

class ReadFileExcel extends ReadFileAbstract
{
    /**
     * open xlsx and read excel data
     *
     * @return array value of all filled cells inside xlsx file
     */
    public function fetchDataArray(string $sheetname = null): array
    {
        if(!$sheetname)
        {
            $sheetname = 'Sheet1';
        }

        // Identify the type of $filePath
        $inputFileType = \PhpOffice\PhpSpreadsheet\IOFactory::identify($this->FILE_ADDR);

        // Create a new Reader of the type that has been identified
        $reader = \PhpOffice\PhpSpreadsheet\IOFactory::createReader($inputFileType);

        // Advise the Reader of which WorkSheets we want to load
        $reader->setLoadSheetsOnly($sheetname);

        // Load $filePath to a Spreadsheet Object
        $spreadsheet = $reader->load($this->FILE_ADDR);

        // Convert Spreadsheet Object to an Array for ease of use
        $xslxData = $spreadsheet->getActiveSheet()->toArray();

        // return array of xlsx
        return $xslxData;
    }
}