<?php
// src/Lib/DataStructure/PrepareServerDataFromJson.php
declare(strict_types=1);

namespace App\Lib\DataStructure;

use App\Lib\DataStructure\Server;


/**
 *  Save each item of server detail
 *
 *  use php 8.1 Readonly Properties
 *  use php 8.0 Constructor property promotion
 */
class PrepareServerDataFromJson
{
    /**
     * Constructor readonly properties datalist
     */
    public function __construct(public readonly array $datalist) { }


    /**
     * loop on all rows of data, clean them
     * and create instance of server obj for each row
     *
     * @return array list of objects of each server
     */
    public function dataset(): array
    {
        // variable to save final result
        $result = [];

        // loop for each row
        foreach( $this->datalist as $row => $dataline )
        {
            $args = array_values($dataline);

            $result[] = new Server(...$args);
        }

        // return array of objects
        return $result;
    }
}