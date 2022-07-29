<?php
// src/Lib/Reader/Read.php

namespace App\Lib\Reader;

use App\Lib\DataStructure\Filter;
use App\Lib\Import\FromExcel;

/**
 * Try load data from Json
 * if not exist from excel
 * it the future we can save data in Redis for better performance
 */
class Read
{
    // constants
    private const FILENAME = 'servers';
    private const ADDR_FOLDER_PATH = '/File/';

    // path of files
    public readonly string $ADDR_ABSOLUTE_JSON;

    private array $dataset;

    // on start filters is empty
    private array $filters = [];


    /**
     * set absolute path of excel file
     */
    public function __construct()
    {
        // save absolute path of excel
        $this->ADDR_ABSOLUTE_JSON  = dirname(__DIR__) . self::ADDR_FOLDER_PATH . 'tmp-' . self::FILENAME . '.json';

        // check excel file exist
        if (!file_exists($this->ADDR_ABSOLUTE_JSON)) {
            // throw new \Exception("JsonFileNotExist");
            // don't throw error, run import
            new FromExcel();
        }

        $this->loadDataFormJson();
    }


    /**
     * return data inside array of objects
     *
     * @return array
     */
    public function fetch(): array
    {
        $serversData = $this->dataset['servers'];

        // variable to save final result
        $result = [];

        // loop for each row
        foreach ($serversData as $row => $dataline) {
            // get array values to add inside filter object
            $args = array_values($dataline);

            // create new object from filter
            $filterResult = new Filter(...$args);

            // check if this item validated based on filters, add it to output
            if ($filterResult->validate($this->filters)) {
                // save in result
                $result[] = $filterResult;
            }
        }

        // return final result
        return $result;
    }


    /**
     * return data inside array of objects
     *
     * @return array
     */
    private function loadDataFormJson(): void
    {
        // read from json
        $jsonContent = file_get_contents($this->ADDR_ABSOLUTE_JSON);

        // decode json to array
        $jsonDecoded = json_decode($jsonContent, true);

        // set dataset for future use
        $this->dataset = $jsonDecoded;
    }


    /**
     * append new filter to old ones, create array of filter
     * used for ram or brand
     *
     * @param string $field
     * @param string|integer $query
     * @return void
     */
    public function addFilter(string $field, string $query): void
    {
        $this->filters[$field][] = $this->clearFilterVal($query);
    }


    /**
     * remove old filters and only use this one
     * used for hdd and location
     *
     * @param string $field
     * @param string|integer $query
     * @return void
     */
    public function onlyFilter(string $field, string $query): void
    {
        $this->filters[$field] = [$this->clearFilterVal($query)];
    }


    /**
     * apply range min value
     *
     * @param  string  $field
     * @param  integer $min
     * @return void
     */
    public function onlyFilterRangeMin(string $field, int $min): void
    {
        $this->filters[$field]['min'] = $this->clearFilterVal($min);
    }


    /**
     * apply range max value
     *
     * @param  string  $field
     * @param  integer $max
     * @return void
     */
    public function onlyFilterRangeMax(string $field, int $max): void
    {
        $this->filters[$field]['max'] = $this->clearFilterVal($max);
    }


    /**
     * clear user input value to conver to int or change to lowercase
     *
     * @param  string $val
     * @return mixed
     */
    private function clearFilterVal(string $val): mixed
    {
        if (is_numeric($val)) {
            $query = intval($val);
        } else {
            $query = mb_strtolower($val);
        }

        return $query;
    }
}