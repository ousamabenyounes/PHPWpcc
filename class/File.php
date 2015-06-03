<?php

namespace Wpcc;

class File
{

    const WRITEABLE_MODE = 0777;

    /**
     * This function write a given content to a specific file
     *
     * @param string  $filename
     * @param string  $content
     * @param boolean $writeable
     */
    public static function writeToFile($filename, $content, $writeable = false)
    {
        try {
            file_put_contents($filename, $content);
	    if (true === $writeable) {	       
	       chmod($filename, static::WRITEABLE_MODE);
	    }
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
    public static function getContentFromFile($url, $debug = false)
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