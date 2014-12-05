<?php


class wpccConfig
{

    /**
     * This function convert a textarea content to an array of string
     *
     * @param string $textareaContent
     * @param array $textareaArray
     */
    public static function save($root_dir, $content, $fileName)
    {
        $configDir =  $root_dir . 'config/';
        $oldmask = umask(0);
        if (!is_dir($configDir)) {
            mkdir($configDir);
        }
        umask($oldmask);
        wpccFile::writeToFile($configDir . $fileName .'.php', $content);
        wpccConfigLog::save($root_dir, $content, $fileName);
    }
}
?>