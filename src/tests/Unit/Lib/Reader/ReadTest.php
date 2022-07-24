<?php

declare(strict_types=1);

namespace App\Tests\Unit\Lib\Reader;

use App\Lib\Reader\Read;
use PHPUnit\Framework\TestCase;

final class ReadTest extends TestCase
{
    private const TOTAL_RECORD = 486;
    private const TOTAL_RECORD_FILTER_HDD_SAS = 11;
    private const TOTAL_RECORD_MULTIPLE_FILTER = 4;

    public function testCreateNewObjectFromFilter(): void
    {
        // create instance of server with valid data
        $myReadObj = new Read();

        // check count of total row
        $allData = $myReadObj->fetch();
        $this->assertCount(self::TOTAL_RECORD, $allData);

        // filter hdd sas and check count of records
        $myReadObj->addFilter('hdd', 'sas');
        $filteredData = $myReadObj->fetch();
        $this->assertCount(self::TOTAL_RECORD_FILTER_HDD_SAS, $filteredData);

        // add some other filter
        $myReadObj->addFilter('ram', '32');
        $myReadObj->addFilter('ram', 64);
        $myReadObj->addFilter('location', 'DallasDAL-10');
        $myReadObj->addFilter('hdd', 'SSD');
        // force only once - example
        $myReadObj->onlyFilter('ram', '32');
        // add range for storage
        $myReadObj->onlyFilterRange('storage', 2000, 3000);

        // fetch data after filter
        $filteredData = $myReadObj->fetch();
        // check count after multiple filter
        $this->assertCount(self::TOTAL_RECORD_MULTIPLE_FILTER, $filteredData);
    }
}