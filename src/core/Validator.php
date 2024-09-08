<?php

namespace Core;

class Validator
{
    public const MAX_NAME_LENGTH = 255;
    public const ALLOWED_EXTENSIONS = ['jpg', 'jpeg', 'png', 'gif', 'txt', 'docx', 'pdf'];

    public function validateName($name)
    {
        return mb_strlen($name) <= self::MAX_NAME_LENGTH;
    }

    public function validateExtension($fileName)
    {
        $extension = strtolower(pathinfo($fileName, PATHINFO_EXTENSION));
        return in_array($extension, self::ALLOWED_EXTENSIONS, true);
    }
}
