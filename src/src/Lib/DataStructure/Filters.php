<?php
// src/Lib/DataStructure/Filters.php
declare(strict_types=1);

namespace App\Lib\DataStructure;


use App\Lib\DataStructure\Server;


class Filters extends Server
{
    public function validate(array $filters): bool
    {
        $valid = null;
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


    private function ram(array $cond): bool
    {
        if(in_array($this->ramCapacity, $cond))
        {
            return true;
        }

        return false;
    }


    private function hdd(array $cond): bool
    {
        if(in_array($this->hddType, $cond))
        {
            return true;
        }

        return false;
    }


    private function location(array $cond): bool
    {
        if(in_array($this->location, $cond))
        {
            return true;
        }

        return false;
    }

}