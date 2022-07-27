<?php

declare(strict_types=1);

namespace App\Tests\Unit\Lib\DataStructure;;

use App\Lib\Import\FromExcel;
use PHPUnit\Framework\TestCase;

final class FileTest extends TestCase
{

    public function testFileExist(): void
    {
        $reader = new FromExcel();
        $filePathJson = $reader->ADDR_ABSOLUTE_JSON;
        $filePathXlsx = $reader->ADDR_ABSOLUTE_EXCEL;

        // check xlsx file exist
        $this->assertFileExists($filePathXlsx);
        // check xlsx file readable
        $this->assertFileIsReadable($filePathXlsx);

        // check json file exist
        $this->assertFileExists($filePathXlsx);
        // check json file readable
        $this->assertFileIsReadable($filePathXlsx);
        // check json file writable
        $this->assertFileIsWritable($filePathXlsx);
    }
}