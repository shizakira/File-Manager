<?php

namespace App\Validators\Interfaces;

interface FileValidatorInterface
{
    public function validateName($name);
    public function validateExtension($fileName);
    public function validateFileSize($tmpFilePath);
}
