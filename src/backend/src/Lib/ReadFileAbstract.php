<?php
declare(strict_types = 1);

namespace App\Lib;

use App\Lib\ReadFileInterface;
use Symfony\Component\Filesystem\Filesystem;

abstract class ReadFileAbstract implements ReadFileInterface
{
    private const TMP_FOLDER = '/tmp';
    protected string $FILE_ADDR;


    /**
     * copy file with data into tmp folder
     */
    public function __construct(string $fileAddr)
    {
        // check file exist
        if (!file_exists($fileAddr)) {
            throw new \Exception("FileNotExist");
        }

        // read file from url
        $myFileData = file_get_contents($fileAddr);

        // copy file to tmp location
        $filesystem = new Filesystem();
        $tmpFile = $filesystem->tempnam(self::TMP_FOLDER, 'tmp_file_');

        // save in local
        file_put_contents($tmpFile, $myFileData);

        // save in private variable
        $this->FILE_ADDR = $tmpFile;
    }


    /**
     * return temporary url of file get from remote url
     *
     * @return string
     */
    public function getFileAddr(): string
    {
        return $this->FILE_ADDR;
    }


    /**
     * default fn to return file data
     *
     * @return mixed
     */
    public function getFileData(): mixed
    {
        // read file data
        $myFileData = file_get_contents($this->FILE_ADDR);

        // return data
        return $myFileData;
    }
}