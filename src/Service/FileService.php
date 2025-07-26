<?php

namespace App\Service;

use Symfony\Component\Filesystem\Filesystem;

/**
 * @author David
 */
class FileService
{

    public const FOLDER_DUMPS = 'dumps';
    public const FOLDER_UPLOADS = 'uploads';

    private string $privateFolder = '';
    private string $publicFolder = '';

    protected Filesystem $filesystem;

    /**
     * Constructor
     *
     * @param string $privateFolder
     * @param string $publicFolder
     */
    public function __construct(string $privateFolder, string $publicFolder)
    {
        $this->privateFolder = $privateFolder;
        $this->publicFolder = $publicFolder;
        $this->filesystem = new Filesystem();
    }

    /**
     * @param string $fileName
     * @param string $content
     * @param bool $asAbsolute
     * @return string
     */
    public function dumpContent(string $fileName, string $content, bool $asAbsolute = true): string
    {
        $path = sprintf('%s/%s', self::FOLDER_DUMPS, $fileName);
        $fullPath = $this->writeContent(
            sprintf('%s/%s', $this->privateFolder, $path),
            $content
        );

        return !empty($fullPath) ?
            ($asAbsolute ? $fullPath : $path) :
            '';
    }

    /**
     * @param string $fileName
     * @param string $content
     * @param bool $asAbsolute
     * @return string
     */
    public function uploadContent(string $fileName, string $content, bool $asAbsolute = true): string
    {
        $path = sprintf('%s/%s', self::FOLDER_UPLOADS, $fileName);
        $fullPath = $this->writeContent(
            sprintf('%s/%s', $this->publicFolder, $path),
            $content
        );

        return !empty($fullPath) ?
            ($asAbsolute ? $fullPath : $path) :
            '';
    }

    /**
     * @param string $fileName
     * @param string $content
     * @return string
     */
    private function writeContent(string $fileName, string $content): string
    {
        $output = '';

        $this->filesystem->dumpFile($fileName, $content);

        if ($this->filesystem->exists($fileName)) {
            $output = $fileName;
        }

        return $output;
    }

    /**
     * @return Filesystem
     */
    public function getFilesystem(): Filesystem
    {
        return $this->filesystem;
    }
}
