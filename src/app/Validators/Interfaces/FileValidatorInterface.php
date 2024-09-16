<?php

namespace App\Validators\Interfaces;

interface FileValidatorInterface
{
    public function validateName(string $name);
    public function validateExtension(string $fileName);
    public function validateFileSize(string $tmpFilePath);
}
