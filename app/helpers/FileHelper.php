<?php


class FileHelper
{

    public static function getFileContents(string $file, ?array $additional_data = [])
    {
        extract($additional_data);
        if (is_file($file)) {
            ob_start();
            require($file);
            return ob_get_clean();
        } else {
            return "";
        }
    }
}
