<?php

namespace App\Services\Helpers;

use InvalidArgumentException;
use LogicException;
use Symfony\Component\HttpFoundation\File\File;
use Symfony\Component\Mime\Exception\LogicException as ExceptionLogicException;

class Base64Service
{
    /**
     * @param string $value
     *
     * @return File
     */
    public function convertToFile(string $value): File
    {
        if (strpos($value, ';base64') !== false) {
            [, $value] = explode(';', $value);
            [, $value] = explode(',', $value);
        }

        $binaryData = base64_decode($value);
        $tmpFile = tempnam(sys_get_temp_dir(), 'base64validator');
        file_put_contents($tmpFile, $binaryData);

        return new File($tmpFile);
    }

    /**
     * @param File $file
     * @return string
     * @throws LogicException
     * @throws InvalidArgumentException
     * @throws ExceptionLogicException
     */
    public function getExtensionByMimeType(File $file): string
    {
        $extract = explode('/', $file->getMimeType());

        return end($extract);
    }
}
