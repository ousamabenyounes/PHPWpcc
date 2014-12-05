<?php

class wpccConfigLog
{

    /**
     * This function convert a textarea content to an array of string
     *
     * @param string $textareaContent
     * @param array $textareaArray
     */
    public static function save($root_dir, $content, $fileName)
    {
        $configLogDir = $root_dir . 'config/history/';
        $oldmask = umask(0);
        if (!is_dir($configLogDir)) {
            mkdir($configLogDir);
        }
        if (!is_dir($configLogDir . $fileName)) {
            mkdir($configLogDir . $fileName);
        }
        umask($oldmask);
        $dateString = date("YmdHis");
        wpccFile::writeToFile($configLogDir . $fileName . '/' . $dateString . '.php', $content);
    }
}
?>