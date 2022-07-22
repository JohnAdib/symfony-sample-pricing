<?php
// src/Lib/ImportFromExcel.php
namespace App\Lib;

use App\Lib\DataStructure\ServerInfo;

class ImportFromExcel
{
    private const EXCEL_DATALIST_PATH = '/Lib/datalist.xlsx';

    /**
     * do nothing
     */
    public function __construct()
    {
        // do nothing
    }


    /**
     * read excel and return array of objects
     *
     * @return array
     */
    public function import(): array
    {
        // read data from excel and return array of unfiltered data
        $xlsxData = $this->readExcel();

        // clean data and save them inside array of objects
        $datalist = $this->clearData($xlsxData);

        // return datalist
        return $datalist;
    }


    /**
     * @todo check files exist
     * @todo read xlsx from url
     *
     * @return array
     */
    private function readExcel(): array
    {
        $filePath = dirname(__DIR__). self::EXCEL_DATALIST_PATH;
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


    /**
     * clean data and saved them inside object
     *
     * @param array $datalist
     * @return object
     */
    private function clearData(array $datalist): array
    {
        // variable to save final result
        $result = [];
        // loop for each row
        foreach( $datalist as $row => $my_line )
        {
            if($row === 0)
            {
                // skip first line, because contain headers
                continue;
            }
            // cast array to object to use nullsafe operator
            $my_line_obj = (object) $my_line;
            // create array of args
            $args =
            [
                $row,
                $my_line_obj?->{'0'},
                $my_line_obj?->{'1'},
                $my_line_obj?->{'2'},
                $my_line_obj?->{'3'},
                $my_line_obj?->{'4'}
            ];
            // passed to server info and save object
            $result[] = new ServerInfo(...$args);
        }

        // return array of objects
        return $result;
    }
}