<?php
// src/Lib/DataStructure/Filters.php
declare(strict_types=1);

namespace App\Lib\DataStructure;


use App\Lib\DataStructure\Server;


class Filters extends Server
{
    /**
     * extend features of server class to validate values and apply filter
     *
     * @param array $filters
     * @return boolean
     */
    public function validate(array $filters): bool
    {
        $valid = true;
        foreach ($filters as $field => $conditions)
        {
            // todo use match
            switch ($field)
            {
                case 'storage':
                    $valid = $this->storage($conditions);
                    break;

                case 'ram':
                    $valid = $this->ram($conditions);
                    break;

                case 'hdd':
                    $valid = $this->hdd($conditions);
                    break;

                case 'location':
                    $valid = $this->location($conditions);
                    break;

                // extra conditions
                case 'price':
                    $valid = $this->price($conditions);
                    break;

                case 'brand':
                    $valid = $this->brand($conditions);
                    break;


                default:
                    break;
            }

            // if for current field validation is not validate break the loop
            if($valid === false)
            {
                break;
            }
        }

        return $valid;
    }


    /**
     * filter min and max value of storage
     *
     * @param array $cond
     * @return boolean
     */
    private function storage(array $cond): bool
    {
        if(isset($cond['min']) && $this->hddTotalCapacity < $cond['min'])
        {
            return false;
        }

        if(isset($cond['max']) && $this->hddTotalCapacity > $cond['max'])
        {
            return false;
        }

        return true;
    }


    /**
     * filter min and max value of price
     *
     * @param array $cond
     * @return boolean
     */
    private function price(array $cond): bool
    {
        if(isset($cond['min']) && $this->priceAmount < $cond['min'])
        {
            return false;
        }

        if(isset($cond['max']) && $this->priceAmount > $cond['max'])
        {
            return false;
        }

        return true;
    }


    /**
     * filter brand of server
     *
     * @param array $cond
     * @return boolean
     */
    private function brand(array $cond): bool
    {
        if(in_array($this->modelBrand, $cond))
        {
            return true;
        }

        return false;
    }


    /**
     * filter ram capacity
     *
     * @param array $cond
     * @return boolean
     */
    private function ram(array $cond): bool
    {
        if(in_array($this->ramCapacity, $cond))
        {
            return true;
        }

        return false;
    }


    /**
     * filter hdd type
     *
     * @param array $cond
     * @return boolean
     */
    private function hdd(array $cond): bool
    {
        if(in_array($this->hddType, $cond))
        {
            return true;
        }

        return false;
    }


    /**
     * filter location
     *
     * @param array $cond
     * @return boolean
     */
    private function location(array $cond): bool
    {
        if(in_array($this->location, $cond))
        {
            return true;
        }

        return false;
    }

}