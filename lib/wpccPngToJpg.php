<?php

class wpccFile
{

    /**
     * This function write a given content to a specific file
     *
     * @param string $filename
     * @param string $content
     */
    public static function writeToFile($filename, $content)
    {
        try {
            file_put_contents($filename, $content);
        } catch (Exception $e) {
            die ('ERROR: ' . $e->getMessage());
        }
    }

    /**
     * This function get the content of the file from a given url
     *
     * @param string $url
     * @return string $content
     */
    public static function getContentFromFile($url, $debug = true)
    {
        try {
            if (false === $debug)
            {
                $content = @file_get_contents($url);
            } else {
                $content = file_get_contents($url);
            }
            return $content;
        } catch (Exception $e) {
            die ('ERROR: ' . $e->getMessage());
        }
    }
}
?>