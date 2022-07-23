<?php
// src/Lib/ImportFromExcel.php
namespace App\Lib;

use App\Lib\DataStructure\ServerInfo;
use Symfony\Component\Filesystem\Filesystem;
use Symfony\Component\Serializer\Encoder\JsonEncoder;
use Symfony\Component\Serializer\Normalizer\ObjectNormalizer;
use Symfony\Component\Serializer\Serializer;


class ImportFromExcel
{
    // constants
    private const EXCEL_FILE_NAME = 'datalist.xlsx';
    private const FILE_RELATIVE_PATH = '/Lib/File/';
    // location of excel file
    private readonly string $EXCEL_ABSOLUTE_PATH;
    private string $jsonPath;
    

    /**
     * set absolute path of excel file
     */
    public function __construct()
    {
        $this->EXCEL_ABSOLUTE_PATH = dirname(__DIR__). self::FILE_RELATIVE_PATH. self::EXCEL_FILE_NAME;
    }

    public function saveInJson(): array
    {
        // create object of filesystem
        $filesystem = new Filesystem();
        // check if xlsx file exist
        if($filesystem->exists($this->EXCEL_ABSOLUTE_PATH))
        {
            // get md5 of xlsx and create json path from that
            $jsonFileName = self::xlsxMd5(). '.json';
            $jsonFilePath = dirname(__DIR__). self::FILE_RELATIVE_PATH. 'tmp-'. $jsonFileName;

            // create serializer object
            $encoders = [new JsonEncoder()];
            $normalizers = [new ObjectNormalizer()];
            $serializer = new Serializer($normalizers, $encoders);

            // check json file status
            if($filesystem->exists($jsonFilePath))
            {
                // read from json
                $jsonContent = file_get_contents($jsonFilePath);
                var_dump($jsonContent);
                exit();

                $datalist = $serializer->deserialize($jsonContent, ServerInfo::class, 'json');
                var_dump($datalist);
                exit();
            }
            else
            {
                // json is not exist, so read excel
                $datalist = $this->import();

                // serialize datalist to json
                $jsonContent = $serializer->serialize($datalist, 'json');
                // save json
                $filesystem->dumpFile($jsonFilePath, $jsonContent);

                // return datalist
                return $datalist;
            }
        }
        return null;
    }


    private function xlsxMd5(): string
    {
        $filesystem = new Filesystem();

        if($filesystem->exists($this->EXCEL_ABSOLUTE_PATH))
        {
            return md5_file($this->EXCEL_ABSOLUTE_PATH);
        }

        return null;
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
        $filePath = $this->EXCEL_ABSOLUTE_PATH;
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