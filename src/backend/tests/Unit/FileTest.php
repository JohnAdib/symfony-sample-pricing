<?php

declare(strict_types=1);

namespace App\Tests\Unit;

use PHPUnit\Framework\TestCase;

final class FileTest extends TestCase
{
    public function testFileExist(): void
    {
        $filePathXlsx =  __DIR__ . '/../..' . $_ENV['EXCEL_URL_LOCAL'];

        // check xlsx file exist
        $this->assertFileExists($filePathXlsx);
        // check xlsx file readable
        $this->assertFileIsReadable($filePathXlsx);
    }
}