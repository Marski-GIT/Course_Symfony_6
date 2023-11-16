<?php

declare(strict_types=1);

namespace App\Services;

use Symfony\Component\HttpFoundation\File\Exception\FileException;
use Symfony\Component\HttpFoundation\File\UploadedFile;

class ImageUploader
{
    private string $targetDirectory;

    /**
     * @param string $targetDirectory
     */
    public function __construct(string $targetDirectory)
    {
        $this->targetDirectory = $targetDirectory;
    }

    public function upload(UploadedFile $file): string
    {
        $newFileName = uniqid() . '.' . $file->guessExtension();

        try {
            $file->move($this->getTargetDirectory(), $newFileName);
        } catch (FileException $e) {

        }

        return $newFileName;
    }

    public function getTargetDirectory(): string
    {
        return $this->targetDirectory;
    }
}