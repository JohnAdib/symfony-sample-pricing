<?php

declare(strict_types=1);

namespace App\Tests\Unit\Lib;

use PHPUnit\Framework\TestCase;
use App\Lib\ReadFromExcel;

final class ReadFromExcelTest extends TestCase
{
    private const TOTAL_RECORD = 487;

    private function getExcelFileRemoteUrl()
    {
        return __DIR__ . '/../../..' . $_ENV['EXCEL_URL_LOCAL'];
    }


    public function testReadRemoteExcelFile(): void
    {
        // get remote url from env
        $filePathXlsx = $this->getExcelFileRemoteUrl();

        // check xlsx file exist
        $this->assertFileExists($filePathXlsx);
        // check xlsx file readable
        $this->assertFileIsReadable($filePathXlsx);
    }


    public function testReadFromExcelAndReturnArray(): void
    {
        // get remote url from env
        $filePathXlsx = $this->getExcelFileRemoteUrl();

        // create new object from reader
        $excelReaderObj = new ReadFromExcel($filePathXlsx);
        // get tmp file location
        $tmpFileAddr = $excelReaderObj->getExcelTmpAddr();

        // check tmp xlsx file exist
        $this->assertFileExists($tmpFileAddr);
        // check tmp xlsx file readable
        $this->assertFileIsReadable($tmpFileAddr);
        // check tmp xlsx file writable
        $this->assertFileIsWritable($tmpFileAddr);

        // fetch excel data into array
        $excelArray = $excelReaderObj->fetch('Sheet2');
        // check count of excel data
        $this->assertCount(self::TOTAL_RECORD, $excelArray);
    }
}