<?php
declare(strict_types = 1);

namespace App\Lib;

interface ReadFileInterface
{
    public function getFileAddr(): string;
    public function getFileData(): mixed;
}